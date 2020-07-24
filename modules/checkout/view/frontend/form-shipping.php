<?php
/**
 * Le formulaire pour créer son adresse de livraison
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
 * @var Product $third_party Les données d'un tier.
 * @var Contact $contact     Les données d'un contact.
 * @var array $countries     Le tableau contenant toutes les données des pays.
 * @var array $country       Les données d'un pays.
 * @var string $selected     L'attribut HTML "selected".
 */
?>

<div class="wps-checkout-subtitle"><?php esc_html_e( 'Personnal and shipping informations', 'wpshop' ); ?></div>

<?php do_action( 'wps_before_checkout_billing_form' ); ?>

<div class="wpeo-gridlayout grid-6">
	<div class="form-element contact-firstname gridw-3">
		<label class="form-field-container">
			<input type="text" class="form-field" name="contact[firstname]" placeholder="<?php esc_html_e( 'First name', 'wpshop' ); ?>" value="<?php echo ! empty( $contact->data['firstname'] ) ? $contact->data['firstname'] : ''; ?>" />
		</label>
	</div>

	<div class="form-element contact-lastname gridw-3">
		<label class="form-field-container">
			<input type="text" class="form-field" name="third_party[title]" placeholder="<?php esc_html_e( 'Last name/Company Name', 'wpshop' ); ?>"  value="<?php echo ! empty( $third_party->data['title'] ) ? $third_party->data['title'] : ''; ?>" />
		</label>
	</div>

	<div class="form-element contact-email form-element-required gridw-3">
		<label class="form-field-container">
			<input type="text" class="form-field" name="contact[email]" placeholder="<?php esc_html_e( 'Email', 'wpshop' ); ?>" value="<?php echo ! empty( $contact->data['email'] ) ? $contact->data['email'] : ''; ?>"  />
		</label>
	</div>

	<div class="form-element contact-phone gridw-3">
		<label class="form-field-container">
			<input type="text" class="form-field" name="contact[phone]" placeholder="<?php esc_html_e( 'Phone number', 'wpshop' ); ?>" value="<?php echo ! empty( $third_party->data['phone'] ) ? $third_party->data['phone'] : ''; ?>"  />
		</label>
	</div>

	<div class="form-element third_party-address form-element-required gridw-6">
		<label class="form-field-container">
			<input type="text" class="form-field" name="third_party[address]" placeholder="<?php esc_html_e( 'Street address', 'wpshop' ); ?>" value="<?php echo ! empty( $third_party->data['contact'] ) ? $third_party->data['contact'] : ''; ?>"  />
		</label>
	</div>

	<?php $countries = get_countries(); ?>
	<div class="form-element third_party-country_id form-element-required gridw-2">
		<label class="form-field-container">
			<select id="monselect" class="form-field" name="third_party[country_id]">
				<?php
				if ( ! empty( $countries ) ) :
					foreach ( $countries as $country ) :
						$selected = '';

						if ( ! empty( $third_party ) && (int) $country['id'] === (int) $third_party->data['country_id'] ) :
							$selected = 'selected="selected"';
						endif;

						?>
						<option <?php echo $selected; ?> value="<?php echo esc_attr( (int) $country['id'] ); ?>"><?php echo $country['label']; ?></option>
						<?php
					endforeach;
				endif;
				?>
			</select>
		</label>
	</div>

	<div class="form-element third_party-town form-element-required gridw-2">
		<label class="form-field-container">
			<input type="text" class="form-field" name="third_party[town]" placeholder="<?php esc_html_e( 'Town / City', 'wpshop' ); ?>" value="<?php echo ! empty( $third_party->data['town'] ) ? $third_party->data['town'] : ''; ?>"  />
		</label>
	</div>

	<div class="form-element third_party-zip form-element-required gridw-2">
		<label class="form-field-container">
			<input type="text" class="form-field" name="third_party[zip]" placeholder="<?php esc_html_e( 'Postcode / ZIP', 'wpshop' ); ?>" value="<?php echo ! empty( $third_party->data['zip'] ) ? $third_party->data['zip'] : ''; ?>"  />
		</label>
	</div>

</div>

<?php do_action( 'wps_after_checkout_billing_form' ); ?>
