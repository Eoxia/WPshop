<?php
/**
 * Product grid view
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 * @package   WPshop\Templates
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;
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
