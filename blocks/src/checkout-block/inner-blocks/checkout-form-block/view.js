/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any 
 * JavaScript running in the front-end, then you should delete this file and remove 
 * the `viewScript` property from `block.json`. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */
 
import { render } from '../../../utils';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

import { useSelect, useDispatch } from '@wordpress/data';
import { storeName } from '../../../store/checkout-form-store';

import apiFetch from '@wordpress/api-fetch';


/**
 * Form field with a visible label (required fields get the form-element-required class).
 */
const Field = ( { name, label, type = 'text', className = '', required = false, autoComplete, values, onChange } ) => (
    <div className={ `form-element ${ className }${ required ? ' form-element-required' : '' }` }>
        <label class="form-field-container">
            <span class="form-label">{ label }</span>
            <input type={ type } class="form-field" name={ name } autoComplete={ autoComplete } required={ required } value={ values[ name ] || '' } onChange={ onChange } />
        </label>
    </div>
);

const CheckoutForm = () => {

    const [ countries, setCountries ] = useState([]);
    const [ emailError, setEmailError ] = useState(false);

    const { setValue, setFields } = useDispatch(storeName);

    const values = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getValues() : 0;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return 0;
        }
    }, []);

    const error = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getError() : null;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return 0;
        }
    }, []);

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    const handleChange = (event) => {
        const { name, value } = event.target;
        setValue({ ...values, [name]: value });
        if (name === 'email') {
            setEmailError(value.length > 0 && !emailRegex.test(value));
        }
    }


    useEffect(() => {
        apiFetch({
            path: '/wp-shop/v1/checkout/'
        }).then((response) => {
            if (response.countries) {
                setCountries(response.countries);
            }
            if (response.fields) {
                setFields(response.fields);
            }
            if (response.values) {
                setValue(response.values);
            }
        })
    }, []);

    if (! countries || countries.length === 0) {
        return (
            <div>
                <div class="wps-checkout-subtitle wps-checkout-subtitle-step-1">{ __( 'Personnal and shipping informations', 'wpshop' ) } </div>
                <div class="wps-checkout-loading">
                    <span class="dashicons dashicons-update"></span>
                </div>
            </div>
        )
    }

    return (
        <div className="wpeo-form">
        <div class="wps-checkout-subtitle wps-checkout-subtitle-step-1">{ __( 'Personnal and shipping informations', 'wpshop' ) } </div>

        <div class="wpeo-gridlayout grid-6 wps-checkout-fields">
            <Field name="firstname" className="contact-firstname gridw-3" label={ __( 'First name', 'wpshop' ) } autoComplete="given-name" values={ values } onChange={ handleChange } />
            <Field name="lastname" className="contact-lastname gridw-3" label={ __( 'Last name/Company Name', 'wpshop' ) } autoComplete="family-name" values={ values } onChange={ handleChange } />
            <Field name="email" type="email" className={ `contact-email gridw-3${ emailError ? ' form-element-error' : '' }` } required label={ __( 'Email', 'wpshop' ) } autoComplete="email" values={ values } onChange={ handleChange } />
            <Field name="phone" type="tel" className="contact-phone gridw-3" label={ __( 'Phone number', 'wpshop' ) } autoComplete="tel" values={ values } onChange={ handleChange } />
            <Field name="address" className="third_party-address gridw-6" required label={ __( 'Street address', 'wpshop' ) } autoComplete="street-address" values={ values } onChange={ handleChange } />

            <div class="form-element third_party-country_id form-element-required gridw-2">
                <label class="form-field-container">
                    <span class="form-label">{ __( 'Country', 'wpshop' ) }</span>
                    <select id="monselect" class="form-field" name="country_id" autoComplete="country" value={ String( values.country_id || 0 ) } onChange={ handleChange }>
                        { countries.map( ( country ) => (
                            <option key={ country.id } value={ String( country.id ) }>
                                { country.label }
                            </option>
                        ) ) }
                    </select>
                </label>
            </div>

            <Field name="town" className="third_party-town gridw-2" required label={ __( 'Town / City', 'wpshop' ) } autoComplete="address-level2" values={ values } onChange={ handleChange } />
            <Field name="zip" className="third_party-zip gridw-2" required label={ __( 'Postcode / ZIP', 'wpshop' ) } autoComplete="postal-code" values={ values } onChange={ handleChange } />
        </div>
        {error &&
            <div class="wps-checkout-error" dangerouslySetInnerHTML={{ __html: error }}></div>
        }
        </div>
    )

}

render(<CheckoutForm />, '.wp-block-wpshop-checkout-form-block');
export default CheckoutForm;
