<?php
/**
 * Classe principale des emails
 *
 * @package   WPshop\Classes
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Emails Class.
 */
class Emails extends \eoxia\Singleton_Util {

	/**
	 * Tableau contenant les mails par défaut.
	 *
	 * @since 2.0.0
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
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		$this->emails['customer_new_account'] = array(
			'title'             => __( 'New account', 'wpshop' ),
			'filename_template' => 'customer-new-account.php',
		);

		$this->emails['customer_current_order'] = array(
			'title'             => __( 'Pending order', 'wpshop' ),
			'filename_template' => 'customer-processing-order.php',
		);

		$this->emails['customer_paid_order'] = array(
			'title'             => __( 'New order', 'wpshop' ),
			'filename_template' => 'admin-new-order.php',
		);
/*
		$this->emails['customer_completed_order'] = array(
			'title'             => __( 'Completed order', 'wpshop' ),
			'filename_template' => 'customer-completed-order.php',
		);

		$this->emails['customer_delivered_order'] = array(
			'title'             => __( 'Delivered order', 'wpshop' ),
			'filename_template' => 'customer-delivered-order.php',
		);
*/
		$this->emails['customer_invoice'] = array(
			'title'             => __( 'Send invoice', 'wpshop' ),
			'filename_template' => 'customer-invoice.php',
		);


		$wp_upload_dir              = wp_upload_dir();
		$this->log_emails_directory = $wp_upload_dir['basedir'] . '/wpshop/logs/';

		wp_mkdir_p( $this->log_emails_directory );

	}

	/**
	 * Récupère le chemin ABS vers le template du mail dans le thème.
	 * Si introuvable récupère le template du mail dans le plugin WPshop.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $filename Le nom du template.
	 *
	 * @return string           Le chemin vers le template.
	 */
	public function get_path( $filename ) {
		$path = locate_template( array( 'wpshop/emails/view/' . $filename ) );

		if ( empty( $path ) ) {
			$path = \eoxia\Config_Util::$init['wpshop']->emails->path . '/view/' . $filename;
		}

		return $path;
	}

	/**
	 * Renvoie true si le template se trouve dans le thème. Sinon false.
	 *
	 * @since 2.0.0
	 *
	 * @param string $filename Le nom du template.
	 *
	 * @return boolean         True ou false.
	 */
	public function is_override( $filename ) {
		if ( locate_template( array( 'wpshop/emails/view/' . $filename ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Envoie un mail.
	 *
	 * @since 2.0.0
	 *
	 * @use wp_mail.
	 *
	 * @param  string $to   Mail du destinataire.
	 * @param  string $type Le template à utilisé.
	 * @param  array  $data Les données utilisé par le template.
	 */
	public function send_mail( $to, $type, $data = array() ) {
		$shop_options = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		if ( empty( $shop_options['shop_email'] ) ) {
			return;
		}

		$to          = empty( $to ) ? $shop_options['shop_email'] : $to;
		$blog_name   = get_bloginfo();
		$mail        = Emails::g()->emails[ $type ];
		$path_file   = Emails::g()->get_path( $mail['filename_template'] );
		$attachments = null;

		if ( ! empty( $data['attachments'] ) ) {
			$attachments = $data['attachments'];
		}

		/*ob_start();
		include $path_file;
		$content = ob_get_clean();*/

		$content = 'test';

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
		$mail_statut = wp_mail( $to, $mail['title'], $content, $headers, $attachments );

		$this->log_emails( $data_email );

		// translators: Send mail to test@eoxia.com, subject "sujet mail" with result true.
		\eoxia\LOG_Util::log( sprintf( 'Send mail to %s, subject %s with result %s', $to, $mail['title'], $mail_statut ), 'wpshop2' );
	}

	/**
	 * Récupère le chemin ABS vers le template du mail dans le thème.
	 * Si introuvable récupère le template du mail dans le plugin WPshop.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $filename Le nom du template.
	 *
	 * @return string           Le chemin vers le template.
	 */
	public function log_emails( $data_email ) {

		$filename = 'log_emails.txt';
		$filepath = $this->log_emails_directory . $filename;
		$current_time = current_time( 'Y-m-d|H:i:s' );

		$log_email_file = fopen( $filepath,'a' );
		$data = $data_email['user_id']. '|' . $data_email['user_email'] . '|' . $data_email['title'] . '|' . $current_time . "\n";
		fwrite( $log_email_file, $data );
		fclose( $log_email_file );
	}
}

Emails::g();
