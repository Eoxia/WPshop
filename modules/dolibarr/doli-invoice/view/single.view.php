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
	<div class="wps-page-header">
		<div class="wps-page-header-title-container">
			<div class="wps-page-header-title"><?php echo esc_html__( 'Invoice', 'wpshop' ) . ' <strong>' . esc_html( $invoice->data['title'] ) . '</strong>'; ?></div>
			<div class="wps-page-header-actions">
				<a class="button <?php echo empty( $invoice->data['external_id'] ) ? 'disabled' : ''; ?>" href="<?php echo esc_attr( $doli_url ); ?>/compta/facture/card.php?facid=<?php echo $invoice->data['external_id']; ?>" target="_blank"><?php esc_html_e( 'Edit in Dolibarr', 'wpshop' ); ?></a>
			</div>
		</div>
	</div>

	<div class="wps-page-content wpeo-gridlayout grid-4">
		<?php do_action( 'wps_invoice', $invoice ); ?>
	</div>
</div>
