<?php
/**
 * La vue principale de l'association des entités.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;


defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string  $label   Le nom de l'entité à associer.
 * @var integer $wp_id   L'id de WordPress.
 * @var string  $type    Le type de l'entité ( produit, tier ).
 * @var array   $entries Le tableau contenant toutes les données des entités.
 * @var array   $entry   Les données d'une entité.
 */
?>

<p><?php printf( __( 'This %s is not associated to a %s in Dolibarr', 'wpshop' ), esc_html( $label ), esc_html( $label ) ); ?></p>

<p><?php printf( __( 'Please select a %s to associate in the next elements:', 'wpshop' ), esc_html( $label ) ); ?></p>

<p><?php printf( __( '%s available in your ERP', 'wpshop' ), ucfirst( esc_html( $label ) ) ); ?></p>

<!-- Dolibarr id -->
<input type="hidden" name="entry_id" />

<!-- WP id -->
<input type="hidden" name="wp_id" value="<?php echo esc_attr( $wp_id ); ?>" />

<!-- Type can be product, propal or third-party -->
<input type="hidden" name="type" value="<?php echo esc_attr( $type ); ?>" />

<input type="text" class="filter-entry" placeholder="<?php esc_attr_e( 'Search...', 'wpshop' ); ?>" />

<ul class="select">
	<?php if ( ! empty( $entries ) ) :
		foreach ( $entries as $entry ) :
			View_Util::exec( 'wpshop', 'doli-associate', 'item-' . $type, array(
				'entry' => $entry,
			) );
		endforeach;
	endif; ?>
</ul>
