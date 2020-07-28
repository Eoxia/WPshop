<?php
/**
 * La vue affichant le bouton pour demander un devis.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( Settings::g()->use_quotation() ) : ?>
	<a class="wps-checkout-quotation-button wpeo-button button-grey action-input" data-type="proposal" data-parent="wps-checkout">
		<span><?php esc_html_e( 'Ask for a Quotation', 'wpshop' ); ?></span>
	</a>
<?php endif; ?>
