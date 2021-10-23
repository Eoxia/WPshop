<?php
/**
 * La vue principale de la liste des catégories.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */


namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array         $categories Le tableau contanant toutes les données des factures.
 * @var Doli_Category $category  Les données d'une catégorie.
 * @var string        $doli_url L'url de Dolibarr.
 */

?>

<div class="wps-list-category wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-full"><?php esc_html_e( 'Category ID', 'wpshop' ); ?></div>
		<div class="table-cell table-300"><?php esc_html_e( 'Name', 'wpshop' ); ?></div>
		<div class="table-cell table-300"><?php esc_html_e( 'Slug', 'wpshop' ); ?></div>
		<div class="table-cell table-300"><?php esc_html_e( 'Parent', 'wpshop' ); ?></div>
		<div class="table-cell table-300"><?php esc_html_e( 'Doli Status', 'wpshop' ); ?></div>
	</div>
	<?php if ( ! empty( $categories ) ) :
		foreach ( $categories as $category ) :
			View_Util::exec( 'wpshop', 'doli-categories', 'item', array(
				'category'  => $category,
				'doli_url' => $doli_url,
			) );
		endforeach;
	endif; ?>
</div>
