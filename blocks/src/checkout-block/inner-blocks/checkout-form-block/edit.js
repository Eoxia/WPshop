/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './scss/editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {

	const countries = ['France', 'Belgium', 'Germany', 'Spain', 'Italy', 'United Kingdom'].map((country, index) => ({
		id: index + 1,
		label: country,
	}));

	const values = {
		firstname: 'John',
		lastname: 'Doe',
		email: 'john.doe@exemple.com',
		phone: '0123456789',
		address: '123 Main St',
		country_id: '1',
		town: 'Paris',
	}

	return (
		<div className="wpeo-form">
        <div class="wps-checkout-subtitle wps-checkout-subtitle-step-1">{ __( 'Personnal and shipping informations', 'wpshop' ) } </div>

        <div class="wpeo-gridlayout grid-6">
            <div class="form-element contact-firstname gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="firstname" placeholder={ __( 'First name', 'wpshop' ) } value={values.firstname} />
                </label>
            </div>

            <div class="form-element contact-lastname gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="lastname" placeholder={ __( 'Last name/Company Name', 'wpshop' )}  value={values.lastname} />
                </label>
            </div>

            <div class="form-element contact-email form-element-required gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="email" placeholder={__( 'Email', 'wpshop' )} value={values.email} />
                </label>
            </div>

            <div class="form-element contact-phone gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="phone" placeholder={__( 'Phone number', 'wpshop' )} value={values.phone} />
                </label>
            </div>

            <div class="form-element third_party-address form-element-required gridw-6">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="address" placeholder={__( 'Street address', 'wpshop' )} value={values.address} />
                </label>
            </div>

            <div class="form-element third_party-country_id form-element-required gridw-2">
                <label class="form-field-container">
                    <select id="monselect" class="form-field" name="country_id">
                        {countries.map((country) => (
                            <option value={country.id} selected={values.country_id === parseInt(country.id)}>
                                {country.label}
                            </option>
                        ))}
                    </select>
                </label>
            </div>

            <div class="form-element third_party-town form-element-required gridw-2">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="town" placeholder={__( 'Town / City', 'wpshop' )} value={values.town} />
                </label>
            </div>

            <div class="form-element third_party-zip form-element-required gridw-2">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="zip" placeholder={__( 'Postcode / ZIP', 'wpshop' )} value={values.zip} />
                </label>
            </div>

        </div>
        </div>
	);
}
