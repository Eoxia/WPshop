<?php
/**
 * La vue principale de la page de réglages
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<form class="wpeo-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_pages_settings' ); ?>" />
	<input type="hidden" name="tab" value="pages" />
	<?php wp_nonce_field( 'callback_update_pages_settings' ); ?>

	<?php
	if ( ! empty( Pages::g()->page_state_titles ) ) :
		foreach ( Pages::g()->page_state_titles as $key => $page_option ) :
			?>
			<div class="form-element">
				<span class="form-label"><?php echo esc_html( $page_option ); ?></span>
				<label class="form-field-container">
					<select id="" class="form-field" name="wps_page_<?php echo esc_attr( $key ); ?>">
						<?php
						if ( ! empty( $pages ) ) :
							foreach ( $pages as $page ) :
								$selected = '';

								if ( $page->ID === $page_ids_options[ $key ] ) :
									$selected = 'selected="selected"';
								endif;
								?>
								<option <?php echo $selected; ?> value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
								<?php
							endforeach;
						endif;
						?>
					</select>
				</label>
			</div>

			<?php
		endforeach;
	endif;
	?>

	<div>
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
	</div>
</form>
