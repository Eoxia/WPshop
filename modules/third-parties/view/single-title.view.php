<?php
/**
 * La vue affichant le titre d'un tier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Third_Party $third_party Les données d'un tier.
 */
?>

<div>
	<?php printf( __( 'Tier <span style="font-weight: 700;">%s</span>', 'wpshop' ), esc_html( $third_party->data['title'] ) ); ?>
</div>
