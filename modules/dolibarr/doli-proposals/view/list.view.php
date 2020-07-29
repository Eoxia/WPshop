<?php
/**
 * La vue affichant la liste des propositions commerciales dans le backend.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array     $proposals Le tableau contenant toutes les données des propositions commerciales.
 * @var Proposals $proposal  Les données d'une proposition commerciale.
 * @var string    $doli_url  L'url de Dolibarr.
 */
?>

<div class="wps-list-product wpeo-table table-flex table-7">
	<div class="table-row table-header">
		<div class="table-cell table-full"><?php esc_html_e( 'Proposal reference', 'wpshop' ); ?></div>
		<div class="table-cell table-200"><?php esc_html_e( 'Billing', 'wpshop' ); ?></div>
		<div class="table-cell table-150"><?php esc_html_e( 'Status', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Method of payment', 'wpshop' ); ?></div>
		<div class="table-cell table-100"><?php esc_html_e( 'Price TTC(€)', 'wpshop' ); ?></div>
	</div>

	<?php if ( ! empty( $proposals ) ) :
		foreach ( $proposals as $proposal ) :
			View_Util::exec( 'wpshop', 'doli-proposals', 'item', array(
				'proposal'    => $proposal,
				'sync_status' => false,
				'doli_url'    => $doli_url,
			) );
		endforeach;
	endif; ?>
</div>
