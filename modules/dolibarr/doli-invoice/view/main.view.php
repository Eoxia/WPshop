<?php
/**
 * La vue principale de la page des factures
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap wpeo-wrap">
	<h2>
		<?php esc_html_e( 'Invoices', 'wpshop' ); ?>

		<?php
		if ( \eoxia\Config_Util::$init['wpshop']->use_global_sync ) :
			?>
			<div class="wpeo-button button-main wpeo-modal-event"
				data-action="load_modal_synchro"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_modal_synchro' ) ); ?>"
				data-class="modal-sync modal-force-display"
				data-sync="third-parties,contacts,products,proposals,orders,invoices,payments"
				data-title="<?php echo esc_attr_e( 'Data synchronization', 'wpshop' ); ?>">
				<span><?php esc_html_e( 'All Invoices Sync', 'wpshop' ); ?></span>
			</div>
			<?php
		endif;
		?>
	</h2>

	<?php Doli_Invoice::g()->display(); ?>
</div>
