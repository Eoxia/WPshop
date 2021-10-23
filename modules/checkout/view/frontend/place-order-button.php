<?php
/**
 * La vue affichant le bouton pour passer la commande.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<a class="wps-process-checkout-button action-input wpeo-button" data-type="order" data-parent="wps-checkout">
	<?php esc_html_e( 'Place order', 'wpshop' ); ?>
</a>
