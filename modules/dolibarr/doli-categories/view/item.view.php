<?php
/**
 * La vue affichant une catégorie dans la liste des catégorie.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Category $category  Les données d'une facture.
 * @var string       $doli_url L'url de Dolibarr.
 */

?>

<div class="table-row">
	<div class="table-cell table-full">
		<ul class="reference-id">
			<?php if ( ! empty( $category->data['external_id'] ) ) : ?>
				<li><i class="fas fa-hashtag"></i>Doli : <?php echo esc_html( $category->data['external_id'] ); ?></li>
			<?php endif; ?>
			<li><i class="fas fa-calendar-alt"></i> <?php echo esc_html( $category->data['datec']['rendered']['date_time'] ); ?></li>
		</ul>	
		<ul class="reference-title ">
			<?php if ( ! empty( $category->data['name'] ) ) : ?>
				<?php echo esc_html( $category->data['name'] ); ?>
			<?php else : ?>-<?php
			endif; ?>
		</ul>
		<ul class="reference-actions">
		<li><a href="<?php echo esc_attr( $doli_url); ?>/categories/edit.php?id=<?php echo $category->data['external_id'] ?>&type=product" target="_blank"><?php esc_html_e( 'Edit', 'wpshop' ); ?></a></li>
			<?php if ( ! empty( $category->data['external_id'] ) ) : ?>
				<li><a href="<?php echo esc_attr( $doli_url ); ?>/categories/viewcat.php?id=<?php echo $category->data['external_id']; ?>&type=product" target="_blank"><?php esc_html_e( 'See in Dolibarr', 'wpshop' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="table-cell table-300">
		<?php if ( ! empty( $category->data['name'] ) ) : ?>
			<?php echo esc_html( $category->data['name'] ); ?>
		<?php else : ?>-<?php
		endif; ?>
	</div>
	<div class="table-cell table-300">
		<?php if ( ! empty( $category->data['slug'] ) ) : ?>
			<?php echo esc_html( $category->data['slug'] ); ?>
		<?php else : ?>-<?php
		endif; ?>
		</div>
	<div class="table-cell table-300">
		<?php if ( ! empty( $category->data['parent_id'] ) ) : ?>
			<?php echo esc_html( $category->data['parent_id'] ); ?>
		<?php else : ?>-<?php
		endif; ?>
	</div>
	<div class="table-cell table-300">
	<?php do_action( 'wps_listing_table_end', $category, true ); ?>
	</div>
	<?php apply_filters( 'wps_name_table_tr', $category ); ?>
</div>
