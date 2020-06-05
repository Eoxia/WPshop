<?php
/**
 * Gestion des actions des produits avec dolibarr.
 *
 * Gestion de la création d'un nouveau produit.
 * Gestion de la mise à jour d'un produit.
 * Gestion de la suppression d'un produit.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 *
 * @todo      File really useful ?
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Doli Products Action Class.
 */
class Doli_Products_Action extends \eoxia\Singleton_Util {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

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
