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

import { select } from '@wordpress/data';

const ProductAddCart = (props) => {

    const { productId } = props;

    const [ qty, setQty ] = useState( 1 );



    const addToCart = (productId) => {
        apiFetch({
            path: `/wp-shop/v1/cart/${productId}/increment`,
            method: 'POST',
            data: {
                qty: qty,
            },  
        }).then((response) => {
            if (response) {
                setQty(1);
            } else {
                console.error('Erreur lors de l\'incrémentation du produit:', response);
            }
        });
    }

    const incrementProduct = () => {
        setQty((prevQty) => prevQty + 1);
    }

    const decrementProduct = () => {
        setQty((prevQty) => Math.max(prevQty - 1, 1));
    }

    return (
        <div className="wp-block-wpshop-product-add-cart">
            <div class="wps-product-quantity">
                <span class="wps-quantity-minus fas fa-minus-circle" onClick={() => decrementProduct(productId)}></span>
                <span class="qty">{qty}</span>
                <span class="wps-quantity-plus fas fa-plus-circle" onClick={() => incrementProduct(productId)}></span>
            </div>
            <button class="wpeo-button wps-button-add-to-cart" onClick={() => addToCart(productId)}>
                <i class="wps-button icon fas fa-shopping-cart"></i>
                <span class="wps-button-text">{ __( 'Add to cart', 'wpshop' ) }</span>
            </button>
        </div>
    );
}

render(<ProductAddCart 
    productId={document.querySelector('.wp-block-wpshop-product-add-cart').getAttribute('data-product-id')}
/>, '.wp-block-wpshop-product-add-cart');
export default ProductAddCart;
