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


const CheckoutAcceptance = () => {

    const { setError } = useDispatch(storeName);

    const values = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getValues() : 0;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return 0;
        }
    }, []);

    const fields = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getFields() : 0;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return 0;
        }
    }, []);

    const acceptance = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getAcceptance() : false;
        } catch (e) {
            console.error('Error lors de l\'accès au store:', e);
            return 0;
        }
    });

    const [ busy, setBusy ] = useState( false );

    // The message is displayed at the top of the form (checkout form block): bring it into view.
    const showError = ( message ) => {
        setError( message );
        setTimeout( () => {
            document.querySelector( '.wps-checkout-error' )?.scrollIntoView( { behavior: 'smooth', block: 'center' } );
        }, 50 );
    };

    const validateForm = () => {

        if (!acceptance) {
            showError(__('Please accept the terms and conditions.', 'wpshop'));
            return false;
        }

        for (let key in fields) {
            if (fields[key].required && !values[key]) {
                showError(__('Please fill in all required fields.', 'wpshop'));
                return false;
            }
        }

        if ( busy ) {
            return false;
        }
        setBusy( true );

        apiFetch({
            'method': 'POST',
            'path': '/wp-shop/v1/checkout/',
            'data': {
                contact: { ...values },
                third_party: { ...values },
                terms: 'true',
            }
        }).then((response) => {
            const data = response?.data || {};

            if (data.url) {
                // Payment page, or the cart page when products are no longer available.
                window.location.href = data.url;
                return;
            }

            setBusy( false );

            const errors = data.errors?.errors ? Object.values( data.errors.errors ) : [];
            showError( errors.length ? errors[0][0] : __( 'The order could not be completed. Please try again or contact us.', 'wpshop' ) );
        }).catch((error) => {
            setBusy( false );
            showError( error?.message || __( 'The order could not be completed. Please try again or contact us.', 'wpshop' ) );
        });

        return true;
    }

    return (
        <div className="wp-block-wpshop-checkout-button-block wps-checkout">
            <button className={ `wps-process-checkout-button action-input wpeo-button${ busy ? ' button-load' : '' }` } onClick={validateForm} disabled={ busy } aria-busy={ busy ? 'true' : 'false' }>
                {__('Proceed to Checkout', 'wpshop')}
            </button>
        </div>
    )

}

render(<CheckoutAcceptance />, '.wp-block-wpshop-checkout-button-block');
export default CheckoutAcceptance;
