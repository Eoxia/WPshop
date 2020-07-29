<?php
/**
 * La vue affichant la page "EMAILS" dans les réglages.
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
 * @var array  $emails Le tableau contenant toutes les données des emails.
 * @var string $key    Un email.
 * @var array  $email  Le tableau contenant toutes les données d'un email.
 */
?>

<table class="wpeo-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'E-mail', 'wpshop' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( ! empty( $emails ) ) :
			foreach ( $emails as $key => $email ) : ?>
				<tr>
					<td><?php echo $email['title']; ?></td>
					<td>
						<a href="<?php echo admin_url( 'post.php?post=' . $email[$key] . '&action=edit' ); ?>" class="wpeo-button button-main">
							<span><?php esc_html_e( 'Configure', 'wpshop' ); ?></span>
						</a>
					</td>
				</tr>
			<?php endforeach;
		endif; ?>
	</tbody>
</table>
