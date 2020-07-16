<?php
/**
 * Affichage des devis dans la page "Mon compte".
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

<?php
if ( ! empty( $transient ) ) :
	foreach ( $transient as $key => $text ) :
		?>
		<div class="wpeo-notice notice-error">
			<div class="notice-content">
				<div class="notice-subtitle"><?php echo $text; ?></div>
			</div>
		</div>
		<?php
	endforeach;
endif;

?>

<?php do_action( 'wp_footer_end' ); ?>

<form method="POST" action="<?php echo admin_url( 'admin-post.php'); ?>" class="wpeo-form">
	<input type="hidden" name="action" value="update_account_details" />
	<?php wp_nonce_field( 'update_account_details' ); ?>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Email', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $contact->data['email'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Phone', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $contact->data['phone'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Login', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $contact->data['login'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Firstname', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $contact->data['firstname'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Lastname', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $contact->data['lastname'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Address', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $third_party->data['address'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Zip', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $third_party->data['zip'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Town', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $third_party->data['town'] ); ?>" readonly/>
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php echo esc_html_e( 'Country', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="email" value="<?php echo esc_attr( $third_party->data['country'] ); ?>" readonly/>
		</label>
	</div>

<!--	<input type="submit" class="wpeo-button" value="--><?php //esc_html_e( 'Save changes', 'wpshop' ); ?><!--" />-->
</form>
