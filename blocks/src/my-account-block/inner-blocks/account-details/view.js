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

import { useSelect, useDispatch, use } from '@wordpress/data';
import { storeName } from '../../../store/my-account-store';

import apiFetch from '@wordpress/api-fetch';

const AccountDetails = () => {

    const [contact, setContact] = useState( null );
    const [third_party, setThirdParty] = useState( null );

    const page = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getPage() : null;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return null;
        }
    }, []);

    useEffect( () => {
        apiFetch( {
            path: '/wp-shop/v1/account',
        }).then( ( response ) => {
            setContact( response.contact.data );
            setThirdParty( response.third_party.data );
        })
    }, [] );

    if ( page !== 'account' ) {
        return null;
    }

    // Determine if we should show loading state
    const isLoading = contact === null && third_party === null;

    return (
        <div className={'wp-block-wpshop-account-details wpeo-form'}>

            <div class="form-element form-element-disable">
                <span class="form-label">{ __( 'Login', 'wpshop' ) }</span>
                <label class="form-field-container">
                    <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.login} readonly/>
                </label>
            </div>

            <div class="gridlayout grid-2">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Firstname', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.firstname} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Lastname', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.lastname} readonly/>
                    </label>
                </div>
            </div>

            <div class="gridlayout grid-2">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Email', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.email} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Phone', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.phone} readonly/>
                    </label>
                </div>
            </div>

            <div class="form-element form-element-disable">
                <span class="form-label">{ __( 'Address', 'wpshop' ) }</span>
                <label class="form-field-container">
                    <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.address} readonly/>
                </label>
            </div>

            <div class="gridlayout grid-3">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Zip', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.zip} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Town', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.town} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Country', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.country} readonly/>
                    </label>
                </div>
            </div>
        </div>
    )
}

render(<AccountDetails />, '.wp-block-wpshop-account-details');
export default AccountDetails;
