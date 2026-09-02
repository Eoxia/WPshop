<?php
/**
 * La vue affichant la page "PAGES" dans les réglages.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array  $page_state_titles Le tableau contenant toutes les données des pages.
 * @var string $key               La page.
 * @var array  $page_option       Le tableau contenant toutes les données des options d'une page.
 * @var array  $pages             Le tableau contenant toutes les données des pages.
 * @var array  $page              Le tableau contenant toutes les données d'une page.
 * @var string $selected          L'attribut HTML "selected".
 * @var array  $page_ids_options  Le tableau contenant toutes les id des pages.
 */
?>

<form class="wpeo-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_pages_settings' ); ?>" />
	<input type="hidden" name="tab" value="pages" />
	<?php wp_nonce_field( 'callback_update_pages_settings' ); ?>

	<?php
	if ( ! empty( Pages::g()->page_state_titles ) ) :
		foreach ( Pages::g()->page_state_titles as $key => $page_option ) :
			?>
			<div class="form-element">
				<span class="form-label"><?php echo esc_html__( $page_option ); ?></span>
				<label class="form-field-container">
					<select id="" class="form-field" name="wps_page_<?php echo esc_attr( $key ); ?>">
						<?php
						if ( ! empty( $pages ) ) :
							foreach ( $pages as $page ) :
								$selected = '';

								if ( $page->ID === (int) $page_ids_options[ $key ] ) :
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
				<?php
				$selected_page_id = ! empty( $page_ids_options[ $key ] ) ? (int) $page_ids_options[ $key ] : 0;
				$selected_post    = $selected_page_id ? get_post( $selected_page_id ) : false;
				if ( $selected_post ) :
					$slug = $selected_post->post_name;
					$url  = get_permalink( $selected_post->ID );
					?>
					<div style="margin-top: 8px; font-size: 13px; color: #666;">
						Slug : <strong><?php echo esc_html( $slug ); ?></strong>
						<span style="margin: 0 8px; color: #ccc;">|</span>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" style="text-decoration: none; color: #2271b1;">
							<i class="fas fa-external-link-alt" style="margin-right: 4px; font-size: 11px;"></i><?php echo esc_html( urldecode( $url ) ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<?php
		endforeach;
	endif;
	?>

	<div>
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
	</div>
</form>
