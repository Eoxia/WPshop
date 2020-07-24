<?php
/**
 * Gestion des filtres des pages de WordPress.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Pages Filter Class.
 */
class Pages_Filter extends Singleton_Util {

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		add_action( 'template_redirect', array( $this, 'check_checkout_page' ) );
		add_filter( 'display_post_states', array( $this, 'add_states_post' ), 10, 2 );
		add_filter( 'the_content', array( $this, 'do_shortcode_page' ), 99, 1 );
	}

	/**
	 * Vérifie si un produit est dans le panier pour accéder à la page checkout.
	 *
	 * Sinon fait une redirection vers la page panier.
	 *
	 * @since 2.0.0
	 */
	public function check_checkout_page() {
		global $wp;
		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

		if ( $page_ids_options['checkout_id'] && is_page( $page_ids_options['checkout_id'] ) && ! in_array( 'received', $wp->query_vars ) ) {
			$cart_contents = Cart_Session::g()->cart_contents;

			if ( empty( $cart_contents ) ) {
				wp_redirect( Pages::g()->get_cart_link() );
				exit;
			}
		}
	}

	/**
	 * Affiche le status des pages lié à WPshop.
	 *
	 * @since 2.0.0
	 *
	 * @param array   $post_states Les status actuels.
	 * @param WP_Post $post        Les données du POST.
	 *
	 * @return array               Les status avec celui de WPshop en plus.
	 */
	public function add_states_post( $post_states, $post ) {
		$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

		$key = array_search( $post->ID, $page_ids_options, true );

		if ( false !== $key ) {
			//$post_states[] = Pages::g()->page_state_titles[ $key ];
		}

		return $post_states;
	}

	/**
	 * Appel le shortcode correspondant à la page.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $content Le contenu de la page.
	 *
	 * @return string          Le contenu de la page + le contenu du shortcode.
	 */
	public function do_shortcode_page( $content ) {
		if ( ! is_admin() ) {
			$page_ids_options = get_option( 'wps_page_ids', Pages::g()->default_options );

			if ( isset( $GLOBALS['post'] ) && is_object( $GLOBALS['post'] ) ) {
				$key = array_search( $GLOBALS['post']->ID, $page_ids_options, true );

				$shortcode = '';
				$params    = array();

				if ( false !== $key ) {
					switch ( $key ) {
						case 'checkout_id':
							$params['step'] = isset( $_GET['step'] ) ? (int) $_GET['step'] : 1;
							$shortcode      = 'checkout';
							break;
						case 'cart_id':
							$shortcode = 'cart';
							break;
						case 'my_account_id':
							$shortcode = 'account';
							break;
						default:
							break;
					}
				}

				$tmp_content = $content;

				if ( ! empty( $shortcode ) ) {
					$shortcode_attr = '';
					if ( ! empty( $params ) ) {
						foreach ( $params as $key => $value ) {
							$shortcode_attr .= $key . '=' . $value . ' ';
						}
					}
					ob_start();
					do_shortcode( '[wps_' . $shortcode . ' ' . $shortcode_attr . ']' );
					$content  = ob_get_clean();
					$content .= $tmp_content;
				}
			}
		}

		return $content;
	}
}

Pages_Filter::g();
