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


const CheckoutForm = () => {

    const [ countries, setCountries ] = useState([]);

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

    const handleChange = (event) => {   
        const { name, value } = event.target;
        setValue({ ...values, [name]: value });
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

        {error &&
            <div class="wps-checkout-error">
                {error}
            </div>
        }

        <div class="wpeo-gridlayout grid-6">
            <div class="form-element contact-firstname gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="firstname" placeholder={ __( 'First name', 'wpshop' ) } value={values.firstname} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element contact-lastname gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="lastname" placeholder={ __( 'Last name/Company Name', 'wpshop' )}  value={values.lastname} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element contact-email form-element-required gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="email" placeholder={__( 'Email', 'wpshop' )} value={values.email} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element contact-phone gridw-3">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="phone" placeholder={__( 'Phone number', 'wpshop' )} value={values.phone} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element third_party-address form-element-required gridw-6">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="address" placeholder={__( 'Street address', 'wpshop' )} value={values.address} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element third_party-country_id form-element-required gridw-2">
                <label class="form-field-container">
                    <select id="monselect" class="form-field" name="country_id" onChange={handleChange}>
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
                    <input type="text" class="form-field" name="town" placeholder={__( 'Town / City', 'wpshop' )} value={values.town} onChange={handleChange} />
                </label>
            </div>

            <div class="form-element third_party-zip form-element-required gridw-2">
                <label class="form-field-container">
                    <input type="text" class="form-field" name="zip" placeholder={__( 'Postcode / ZIP', 'wpshop' )} value={values.zip} onChange={handleChange} />
                </label>
            </div>

        </div>
        </div>
    )

}

render(<CheckoutForm />, '.wp-block-wpshop-checkout-form-block');
export default CheckoutForm;
