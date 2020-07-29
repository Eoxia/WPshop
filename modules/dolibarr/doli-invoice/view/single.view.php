<?php
/**
 * La vue affichant une facture.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Invoice $invoice Les données d'une facture.
 */
?>

<div class="wrap wpeo-wrap">
	<div class="page-header">
		<h2><?php echo esc_html__( 'Invoice', 'wpshop' ) . ' ' . esc_html( $invoice->data['title'] ); ?></h2>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-4">
		<?php do_action( 'wps_invoice', $invoice ); ?>
	</div>
</div>
