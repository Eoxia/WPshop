<?php
/**
 * La vue affichant une catégorie de produit.
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
 * @var array $product_taxonomy Les données de la catégorie d'un produit.
 */
?>

<div class="wps-taxonomy">
	<?php if ( ! empty( $product_taxonomy->name ) ) : ?>
		<div class="wps-taxonomy-title"><?php echo esc_html( $product_taxonomy->name ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $product_taxonomy->description ) ) : ?>
		<div class="wps-taxonomy-description"><?php echo $product_taxonomy->description; ?></div>
	<?php endif; ?>
	<a href="<?php echo esc_url( get_term_link( $product_taxonomy ) ); ?>" class="wps-taxonomy-link"><?php esc_html_e( 'Read more', 'wpshop' ); ?></a>
</div>
