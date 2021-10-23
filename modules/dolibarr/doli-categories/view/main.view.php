<?php
/**
 * La vue principale de la page des catégories.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 * @var string  $dolibarr_create_category   Endpoint de création de catégorie dolibarr.
 * @var string  $dolibarr_url               L'url de fin dolibarr.
 */
?>

<div class="wrap wpeo-wrap">
	<h2>
		<?php esc_html_e( 'Categories WPshop', 'wpshop' ); ?>
		<?php if ( ! Settings::g()->dolibarr_is_active() ) : ?>
			<a href="<?php echo esc_attr( admin_url( 'post-new.php?post_type=wps-product-cat' ) ); ?>" class="wpeo-button button-main"><?php esc_html_e( 'Add', 'wpshop' ); ?></a>
		<?php else: ?>
			<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_create_category ); ?>" target="_blank" class="wpeo-button button-main"><?php esc_html_e( 'Add', 'wpshop' ); ?></a>
		<?php endif; ?>
	</h2>
		<?php Doli_Category::g()->display(); ?>
</div>
