<?php
/**
 * La classe gérant les actions des produits de Dolibarr.
 *
 * @todo      File really useful ?
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
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
