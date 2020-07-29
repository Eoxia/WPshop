<?php
/**
 * La vue affichant le contenu de la modal de synchronisation.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;


/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array  $sync_infos Le tableau contenant toutes les données des synchronisations d'une entité.
 * @var string $key        La donné à synchroniser.
 * @var array  $info       Les données d'une synchronisation.
 * @var string  $stats     La barre de progression.
 */
?>

<div class="wpeo-gridlayout grid-3">
	<?php if ( ! empty( $sync_infos ) ) :
		foreach ( $sync_infos as $key => $info ) :
			$stats = '0 / ' . $info['total_number']; ?>
			<div>
				<div class="item waiting-item" id="wpeo-upate-item-<?php echo $key; ?>" >
					<div class="item-spin">
						<span class="wps-spinner"><i class="fas fa-circle-notch fa-spin"></i></span>
						<i class="icon dashicons" ></i>
					</div>
					<div class="item-container">
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
							<input type="hidden" name="action" value="sync" />
							<?php wp_nonce_field( 'sync' ); ?>
							<input type="hidden" name="type" value="<?php echo $key; ?>" />
							<input type="hidden" name="last" value="<?php echo $info['last']; ?>" />

							<div class="item-content">
								<div class="item-title"><?php echo esc_attr( $info['title'] ); ?></div>
							</div>
							<div class="item-result">
								<input type="hidden" name="total_number" value="<?php echo ( null !== $info['total_number'] ? esc_attr( $info['total_number'] ) : 0 ); ?>" />
								<input type="hidden" name="done_number" value="0" />
								<div class="item-progress" >
									<div class="item-progression" >&nbsp;</div>
									<div class="item-stats" ><?php echo esc_html( $stats ); ?></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php endforeach;
	endif; ?>
</div>
