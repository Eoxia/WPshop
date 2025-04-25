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

    const [ message, setMessage ] = useState(null);

    const { setAcceptance } = useDispatch(storeName);

    const checkboxChange = (event) => {
        const { checked } = event.target;
        setAcceptance(checked);
    }

    useEffect(() => {
        apiFetch({
            'method': 'GET',
            'path': '/wp-shop/v1/checkout/terms',
        }).then((response) => {
            if (response && response.message) {
                setMessage(response.message);
            }
        })
    }, []);

    if (!message) {
        return (
            <div className="wp-block-wpshop-checkout-acceptance-block">
            </div>
        )
    }

    return (
        <div className="wp-block-wpshop-checkout-acceptance-block">
            <input type="checkbox" id="acceptance" name="acceptance" onChange={checkboxChange} />
            <label htmlFor="acceptance" dangerouslySetInnerHTML={{ __html: message }}></label>
        </div>
    )

}

render(<CheckoutAcceptance />, '.wp-block-wpshop-checkout-acceptance-block');
export default CheckoutAcceptance;
