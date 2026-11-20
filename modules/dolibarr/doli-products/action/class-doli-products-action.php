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
