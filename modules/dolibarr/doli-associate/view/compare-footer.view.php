<?php
/**
 * La vue affichant le footer de la modal de comparaison.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;


defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string  $type     Le type d'entité ( produit / tier ).
 * @var integer $entry_id L'id de Dolibarr.
 * @var integer $wp_id    L'id de WordPress.
 */
?>

<div class="action-attribute wpeo-button button-main"
     data-action="associate_entry"
     data-nonce="<?php echo esc_attr( wp_create_nonce( 'associate_entry' ) ); ?>"
     data-type="<?php echo $type; ?>"
     data-entry-id="<?php echo esc_attr( $entry_id ); ?>"
     data-wp-id="<?php echo esc_attr( $wp_id ); ?>"
     data-modal="1">
	<?php // translators: Choose WordPress. ?>
	<span><?php printf( __( 'Associate and Sync', 'wpshop' ) ); ?></span>
</div>

<div class="wpeo-button button-light modal-close">
	<span><?php esc_html_e( 'Back', 'wpshop' ); ?></span>
</div>
