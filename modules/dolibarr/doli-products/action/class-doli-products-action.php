<?php
/**
 * La classe gérant les actions des produits de Dolibarr.
 *
 * @todo      File really useful ?
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
 * Doli Products Action Class.
 */
class Doli_Products_Action extends Singleton_Util {

	/**
	 * le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	protected function construct() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_filter( 'map_meta_cap', array( $this, 'map_delete_post_cap' ), 10, 4 );

		// Products deleted from Dolibarr: hourly check, and never added to the cart.
		add_action( 'init', array( $this, 'schedule_products_check' ) );
		add_action( self::CHECK_HOOK, array( Doli_Products::g(), 'check_all_products' ) );
		add_filter( 'wps_add_to_cart_product', array( $this, 'refuse_missing_product' ), 10, 2 );
	}

	/**
	 * WP-Cron hook of the hourly check of the products against Dolibarr.
	 *
	 * @since 2.7.0
	 */
	const CHECK_HOOK = 'wps_check_dolibarr_products';

	/**
	 * Schedules the hourly check of the products against Dolibarr.
	 *
	 * @since 2.7.0
	 */
	public function schedule_products_check() {
		if ( ! wp_next_scheduled( self::CHECK_HOOK ) ) {
			wp_schedule_event( time() + MINUTE_IN_SECONDS, 'hourly', self::CHECK_HOOK );
		}
	}

	/**
	 * Legacy add to cart (Cart::add_to_cart()): refuses the products deleted from Dolibarr.
	 *
	 * @since 2.7.0
	 *
	 * @param bool    $can_add Whether the product can be added.
	 * @param Product $product Product.
	 *
	 * @return bool
	 */
	public function refuse_missing_product( $can_add, $product ) {
		if ( ! $can_add || empty( $product->data['id'] ) ) {
			return $can_add;
		}

		return Doli_Products::g()->is_purchasable( $product->data['id'] );
	}

	/**
	 * "delete_post" on a product: refused while the product exists in Dolibarr (it would come back
	 * at the next sync) or when Dolibarr does not answer. Covers trash too.
	 *
	 * @param string[] $caps    Primitive capabilities.
	 * @param string   $cap     Capability being checked.
	 * @param int      $user_id User ID.
	 * @param array    $args    Arguments, $args[0] = post ID.
	 *
	 * @return string[]
	 */
	public function map_delete_post_cap( $caps, $cap, $user_id, $args ) {
		if ( 'delete_post' !== $cap || empty( $args[0] ) || 'wps-product' !== get_post_type( (int) $args[0] ) || ! Settings::g()->dolibarr_is_active() ) {
			return $caps;
		}

		if ( ! Doli_Products::g()->is_deletable( (int) $args[0] ) ) {
			$caps[] = 'do_not_allow';
		}

		return $caps;
	}

	/**
	 * Ajoute une notice.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function admin_notices() {
		if ( ! isset( $_REQUEST['action'] ) ) {
			return;
		}

		if ( $_REQUEST['action'] != 'edit' ) {
			return;
		}

		$transient = get_transient( 'wps_product_already_exist' );
		delete_transient( 'wps_product_already_exist' );

		if ( ! empty( $transient ) ) {
			?>
			<div class="error">
				<p><?php echo $transient; ?></p>
			</div>
			<?php
		}
	}
}

new Doli_Products_Action();
