<?php
/**
 * La classe gérant les fonctions principales des emails.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Emails Class.
 */
class Emails extends Singleton_Util {

	/**
	 * Post type of the email templates (private pages before database version 1.0.4).
	 *
	 * @since 2.7.0
	 */
	const POST_TYPE = 'wps-email';

	/**
	 * Tableau contenant les mails par défaut.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @var array
	 */
	public $emails;

	/**
	 * Le chemin vers le répertoire uploads/wpshop/logs.
	 *
	 * @var string
	 */
	public $log_emails_directory;

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {

		$wp_upload_dir = wp_upload_dir();

		//@todo Permettre le réglages des dossiers et du nom de fichier pour les logs
		//@todo afficher la taille du fichier
		$this->log_emails_directory = $wp_upload_dir['basedir'] . '/wpshop/logs/';
        //@todo est ce que j'ai les droits ?, création de base lors de l'installation et check ?
		wp_mkdir_p( $this->log_emails_directory );
		//@todo erreur création répertoire ?

		
		$this->emails['customer_new_account'] = array(
			'title'   => 'New account',
			'content' => 'Welcome <br> This email confirms that your account has been created. <br> Thank you for your trust and see you soon on our shop.',
		);

		$this->emails['customer_current_order'] = array(
			'title'   => 'Pending order',
			'content' => 'Hello <br> We have just recorded your order, thank you to send us your payment. <br> We thank you for your confidence and see you soon on our shop.',
		);

		$this->emails['customer_paid_order'] = array(
			'title'   => 'New order',
			'content' => 'Hello <br> This email confirms that your payment for your recent order has just been validated. <br> See you soon on our shop.',
		);
		$this->emails['customer_invoice'] = array(
			'title'   => 'Send invoice',
			'content' => 'Hello <br> You can access your invoices by logging in to your account.',
		);

		// Sent by "Mark as delivered" on an order (Doli_Order_Action::mark_as_delivery()).
		$this->emails['customer_delivered_order'] = array(
			'title'   => 'Delivered order',
			'content' => 'Hello <br> Your order has been shipped. <br> See you soon on our shop.',
		);

		// "customer_completed_order" has a template post but is never sent: not listed in the settings.

		foreach ( $this->emails as $key => $email ) {
			// Définit le contenu des emails en fonction des pages.
			$this->emails[$key] = $this->set_email_content( $key, $email );
		}
		
	}

	/**
	 * Envoie un mail.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $to         Email du destinataire.
	 * @param string $type_email Le type de l'email utilisé voir les lignes 45 à 71 Sample : 'customer_new_account'
	 * @param array  $data       Les données utilisées par l'email.
	 */
	public function send_mail( $to, $type_email, $data = array() ) {

		error_log( 'send_mail called with type: ' . $type_email . ' to ' . $to );

		$shop_options = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( empty( $shop_options['shop_email'] ) ) {
			//@todo récupération erreurs
			return;
		}

		$to          = empty( $to ) ? $shop_options['shop_email'] : $to;
		$blog_name   = get_bloginfo();
		$mail        = Emails::g()->emails[ $type_email ];
		$attachments = null;

		if ( ! empty( $data['attachments'] ) ) {
			$attachments = $data['attachments'];
		}

		$content = $mail['content'];
		$user = wp_get_current_user();
		$data_email = array(
			'title' => $mail['title'],
			'user_id' => $user->ID,
			'user_email' => $user->user_email,
		);

		if ( $user->ID == 0 ) {
			$data_email['user_email'] = $to;
			$user = get_user_by('email', $to );
			$data_email['user_id'] = $user->data->ID;
		}

		$headers     = array();
		$headers[]   = 'From: ' . $blog_name . ' <' . $shop_options['shop_email'] . '>';
		$headers[]   = 'Content-Type: text/html; charset=UTF-8';
		wp_mail( $to, $mail['title'], $content, $headers, $attachments );

		$this->log_emails( $data_email );
	}

	/**
	 * Création des lignes de log des mails envoyés.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param string $data_email Les données du mails envoyé.
	 */
	public function log_emails( $data_email ) {

		$filename = 'log.txt';
		$filepath = $this->log_emails_directory . $filename;
		$current_time = current_time( 'Y-m-d|H:i:s' );

		$log_email_file = fopen( $filepath,'a' );
		$data = $current_time . '|' . 'Email' . '|' .$data_email['user_id']. '|' . $data_email['user_email'] . '|' . $data_email['title'] . '|' . "\n";
		fwrite( $log_email_file, $data );
		fclose( $log_email_file );
	}

	/**
	 * Définit le contenu des emails en fonction des pages pévues à cet effet
	 *
	 * @since 2.4.0
	 * @version 2.4.0
	 *
	 * @param array $email Titre et contenu de l'email.
	 *
	 * @return array $email Titre et contenu de l'email.
	 */
	private function set_email_content( $key, $email ) {
		if ( empty( $email ) ) {
			return;
		}

		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

		if ( ! empty( $page_ids_options[$key] ) ) {
			$email_page = get_post( $page_ids_options[$key] );
			if ( ! empty( $email_page ) ) {
				$email['title']   = $email_page->post_title;
				$email['content'] = $email_page->post_content;
			}
		}

		// Keep the default title and content when no template post is set.
		return $email;
	}

	/**
	 * IDs of the email template posts, keyed like Pages::$page_state_titles_private.
	 *
	 * @since 2.7.0
	 *
	 * @return int[]
	 */
	public function get_email_post_ids() {
		$page_ids = get_option( 'wps_page_ids', array() );
		$ids      = array();

		foreach ( array_keys( (array) Pages::g()->page_state_titles_private ) as $key ) {
			if ( ! empty( $page_ids[ $key ] ) ) {
				$ids[ $key ] = (int) $page_ids[ $key ];
			}
		}

		return $ids;
	}

	/**
	 * Moves the email templates out of the pages. Same posts, IDs and content:
	 * only the post type changes, so the settings links and the sending keep working.
	 *
	 * @since 2.7.0
	 */
	public function migrate_email_posts() {
		foreach ( $this->get_email_post_ids() as $post_id ) {
			$post = get_post( $post_id );

			if ( $post && 'page' === $post->post_type ) {
				set_post_type( $post_id, self::POST_TYPE );
			}
		}
	}

	/**
	 * URL of the "E-mails" tab of the WPshop settings.
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public function get_settings_url() {
		return admin_url( 'admin.php?page=wps-settings&tab=emails' );
	}
}

Emails::g();
