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

    const { qty, productId } = props;

    const [ value, setValue ] = useState( parseInt(qty) );

    const incrementProduct = (productId) => {
        apiFetch({
            path: `/wp-shop/v1/cart/${productId}/increment`,
            method: 'POST'
        }).then((response) => {
            if (response) {
                if (!response?.products?.length) {
                    setValue(0);
                } else {
                    const product = response.products.find((product) => product.id === parseInt(productId));
                    if (product) {
                        setValue(product.qty);
                    } else {
                        setValue(0);
                    }
                }
            } else {
                console.error('Erreur lors de l\'incrémentation du produit:', response);
            }
        });
    }

    const decrementProduct = (productId) => {
        apiFetch({
            path: `/wp-shop/v1/cart/${productId}/decrement`,
            method: 'POST'
        }).then((response) => {
            if (response) {
                if (!response?.products?.length) {
                    setValue(0);
                } else {
                    setValue(response);
                }
            } else {
                console.error('Erreur lors de l\'incrémentation du produit:', response);
            }
        });
    }

    return (
        <div className="wp-block-wpshop-product-add-cart">
            <div class="wps-product-quantity">
                <span class="wps-quantity-minus fas fa-minus-circle" onClick={() => decrementProduct(productId)}></span>
                <span class="qty">{value}</span>
                <span class="wps-quantity-plus fas fa-plus-circle" onClick={() => incrementProduct(productId)}></span>
            </div>
            <button class="wpeo-button wps-button-add-to-cart" onClick={() => incrementProduct(productId)}>
                <i class="wps-button icon fas fa-shopping-cart"></i>
                <span class="wps-button-text">{ __( 'Add to cart', 'wpshop' ) }</span>
            </button>
        </div>
    );
}

render(<ProductAddCart 
    qty={document.querySelector('.wp-block-wpshop-product-add-cart').getAttribute('data-qty')}
    productId={document.querySelector('.wp-block-wpshop-product-add-cart').getAttribute('data-product-id')}
/>, '.wp-block-wpshop-product-add-cart');
export default ProductAddCart;
