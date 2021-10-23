<?php
/**
 * La vue affichant si le panier est vide.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<p><?php esc_html_e( 'Your cart is empty.', 'wpshop' ); ?></p>
