<?php
/**
 * La vue affichant la liste des méthodes de paiement dans la page réglages.
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
 * @var array   $payment_methods Le tableau contenant toutes les données des méthodes de paiements.
 * @var string  $key             La méthode de paiement.
 * @var Payment $payment_method  Les données d'une méthode de paiement.
 */
?>

<table class="wpeo-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Method', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'Activated', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'Settings', 'wpshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( ! empty( $payment_methods ) ) :
			foreach ( $payment_methods as $key => $payment_method ) :
				?>
				<tr>
					<td><?php echo $payment_method['title']; ?></td>
					<td><?php echo $payment_method['active'] ? __( 'Activate', 'wpshop' ) : __( 'Deactivate', 'wpshop' ); ?></td>
					<td>
						<a href="<?php echo admin_url( 'admin-post.php?action=wps_load_settings_tab&_wpnonce=' . wp_create_nonce( 'callback_load_tab' ) . '&page=wps-settings&tab=payment_method&section=' . $key ); ?>" class="wpeo-button button-main">
							<span><?php esc_html_e( 'Configure', 'wpshop' ); ?></span>
						</a>
					</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>
</table>
