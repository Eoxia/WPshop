<?php
/**
 * La vue principale de la page des tiers.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Third_Party_List_Table $list_table     Le tableau des tiers.
 * @var array                  $dolibarr_option Les options de Dolibarr.
 */
?>

<div class="wrap wpeo-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Third Parties', 'wpshop' ); ?></h1>

	<?php if ( ! empty( $dolibarr_option['dolibarr_create_tier'] ) && ! empty( $dolibarr_option['dolibarr_url'] ) ) : ?>
		<a href="<?php echo esc_url( $dolibarr_option['dolibarr_url'] . '/societe/card.php?action=create&backtopage=' . urlencode( admin_url( 'admin.php?page=wps-third-party' ) ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Add Third Party in Dolibarr', 'wpshop' ); ?>
		</a>
	<?php endif; ?>

	<hr class="wp-header-end">

	<div id="poststuff">
		<form id="wps-third-party-list" method="get">
			<input type="hidden" name="page" value="wps-third-party" />
			<?php
				$list_table->search_box( __( 'Search', 'wpshop' ), 'third-party' );
				$list_table->display();
			?>
		</form>
	</div>
</div>
