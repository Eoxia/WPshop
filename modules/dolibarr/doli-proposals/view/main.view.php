<?php
/**
 * La vue principale de la page des propositions commerciales.
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
 * @var string  $dolibarr_url             L'url de Dolibarr.
 * @var string  $dolibarr_create_proposal L'url de la liste des propositions commerciales sur Dolibarr.
 * @var string  $s                        Le paramètre de la recherche.
 * @var integer $count                    Le nombre de tier.
 * @var integer $number_page              Le nombre de page.
 * @var string  $current_page             La page actuel.
 * @var string  $begin_url                L'url de début de recherche.
 * @var string  $prev_url                 L'url précédante.
 * @var string  $next_url                 L'url suivante.
 * @var string  $end_url                  L'url de fin de recherche.
 */
?>


<div class="wrap wpeo-wrap">
	<h2><?php esc_html_e( 'Dolibarr Proposals', 'wpshop' ); ?>
		<a href="<?php echo esc_attr( $dolibarr_url . $dolibarr_create_proposal ); ?>" target="_blank" class="wpeo-button button-main"><?php esc_html_e( 'Add', 'wpshop' ); ?></a>
	</h2>

	<div class="wpeo-gridlayout wpeo-form form-light grid-4 alignleft" style="margin-bottom: 20px; margin-top: 15px;">
		<form method="GET" action="<?php echo admin_url( 'admin.php' ); ?>" class="wps-filter-bar wpeo-form form-light" style="display: flex;">
			<div class="form-element">
				<label class="form-field-container">
					<span class="form-field-icon-prev"><i class="fas fa-search"></i></span>
					<input type="hidden" name="page" value="wps-proposal-doli" />
					<input type="text" name="s" class="form-field" value="<?php echo esc_attr( $s ); ?>" />
				</label>
			</div>

			<input type="submit" class="wpeo-button button-grey button-filter" value="<?php esc_html_e( 'Search', 'wpshop' ); ?>" />

		</form>
	</div>

	<?php if ( ! empty( $s ) ) :
		// @todo: Translate ?>
		<p>Résultats de recherche pour « <?php echo $s; ?> »</p>
	<?php endif; ?>

	<div class="alignright" style="display: flex; margin-top: 35px;">
		<p style="line-height: 0px; margin-right: 5px;"><?php echo $count . ' ' . __( 'element(s)', 'wpshop' ); ?></p>

		<?php if ( $number_page > 1 ) : ?>
			<ul class="wpeo-pagination">
				<?php if ( 1 !== $current_page ) : ?>
					<li class="pagination-element pagination-prev">
						<a href="<?php echo esc_attr( $begin_url ); ?>"><<</a>
					</li>

					<li class="pagination-element pagination-prev">
						<a href="<?php echo esc_attr( $prev_url ); ?>"><</a>
					</li>
				<?php endif; ?>

				<form method="GET" action="<?php echo admin_url( 'admin.php' ); ?>" />
					<input type="hidden" name="page" value="wps-proposal-doli" />
					<input type="hidden" name="s" value="<?php echo esc_attr( $s ); ?>" />
					<input style="width: 50px;" type="text" name="current_page" value="<?php echo esc_attr( $current_page ); ?>" />
				</form>

				sur <?php echo $number_page; ?>

				<?php if ( $current_page !== $number_page ) : ?>
					<li class="pagination-element pagination-next">
						<a href="<?php echo esc_attr( $next_url ); ?>">></a>
					</li>

					<li class="pagination-element pagination-next">
						<a href="<?php echo esc_attr( $end_url ); ?>">>></a>
					</li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php Doli_Proposals::g()->display(); ?>
</div>
