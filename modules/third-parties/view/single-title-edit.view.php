<?php
/**
 * La vue pour l'édition d'un titre d'un tier.
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

<form class="wpeo-form" method="post" action="<?php echo ( 0 === $third_party->data['id'] ) ? admin_url( 'admin-post.php' ) : admin_url( 'admin-ajax.php' ); ?>">
	<input type="hidden" name="action" value="third_party_save_title" />
	<input type="hidden" name="post_id" value="<?php echo $third_party->data['id']; ?>" />
	<?php wp_nonce_field( 'save_third' ); ?>

	<div class="form-element">
		<label class="form-field-container">
			<input type="text" class="form-field" name="title" placeholder="<?php esc_attr_e( 'New third party', 'wpshop' ); ?>" value="<?php echo $third_party->data['title']; ?>" />
			<span class="form-field-label-next">
				<?php
				if ( 0 === $third_party->data['id'] ) :
					?>
					<input type="submit" class="wpeo-button button-square-30" value='Save' />
					<?php
				else :
					?>
					<div data-parent="wpeo-form"
						class="action-input wpeo-button button-square-30">
						<i class="button-icon fas fa-save"></i>
					</div>
					<?php
				endif;
				?>
			</span>
		</label>
	</div>
</form>
