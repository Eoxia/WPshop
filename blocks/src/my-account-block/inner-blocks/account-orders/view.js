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

const AccountOrders = () => {

    const [ orders, setOrders ] = useState( null );
    useEffect(() => {
        apiFetch({
            path: '/wp-shop/v1/account/orders',
            method: 'GET'
        }).then((response) => {
            setOrders(response);
        })
    }, []);

    const {setPage} = useDispatch(storeName);
    const page = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getPage() : null;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return null;
        }
    }, []);


    const [ openedOrder, setOpenedOrder ] = useState( [] );

    const openOrder = ( index ) => {
        if ( openedOrder.includes( index ) ) {
            setOpenedOrder( openedOrder.filter( ( item ) => item !== index ) );
        } else {
            setOpenedOrder( [ ...openedOrder, index ] );
        }
    };

    if ( page != 'orders' ) {
        return null;
    }

    if ( orders === null ) {
        return (
            <div className="wp-block-wpshop-account-orders">
                <p>{ __( 'Loading...', 'wpshop' ) }</p>
            </div>
        );
    }

    if ( orders.length === 0 ) {
        return (
            <div className="wp-block-wpshop-account-orders">
                <p>{ __( 'No orders.', 'wpshop' ) }</p>
            </div>
        );
    }

    return (
        <div className="wp-block-wpshop-account-orders">
            <div class="wps-list-order wps-list-box">
                {
                    orders.map( ( order, index ) => (
                        <div class="wps-order wps-box" key={ index }>
                            <div class="wps-box-resume">
                                <div class="wps-box-primary">
                                    <div class="wps-box-title">{ order.data.datec }</div>
                                    <ul class="wps-box-attributes">
                                        <li class="wps-box-subtitle-item"><i class="wps-box-subtitle-icon fas fa-shopping-cart"></i> { order.data.title }</li>
                                    </ul>
                                    <div class={`${openedOrder.includes( index ) ? 'active wps-box-display-more' : 'wps-box-display-more'}`} onClick={ () => openOrder( index ) }>
                                        <i class="wps-box-display-more-icon fas fa-angle-right"></i>
                                        <span class="wps-box-display-more-text"> { __( 'View details', 'wpshop' ) }</span>
                                    </div>
                                </div>
                                <div class="wps-box-secondary">
                                    <div class="wps-box-status"><span class="wps-box-status-dot"></span> { order.data.status.class.text }</div>
                                    <div class="wps-box-price">{ parseFloat(order.data.total_ttc).toFixed(2) }€</div>
                                </div>
                                <div class="wps-box-action">
                                    <a target="_blank"
                                        href={ order.data.download }
                                        class="wpeo-button button-primary button-square-50 button-rounded">
                                        <i class="button-icon fas fa-file-download"></i>
                                    </a>
                                </div>
                            </div>

                            <div class={`${openedOrder.includes( index ) ? 'wps-list-product' : 'wps-box-detail wps-list-product'}`}>
                                {order.data.lines.map((product) => (
                                    <div itemscope itemtype="https://schema.org/Product" class="wps-product">
                                        <figure class="wps-product-thumbnail" dangerouslySetInnerHTML={{ __html: product.thumbnail }} itemprop="image">
                                        </figure>
                                    
                                        <div class="wps-product-content">
                                            <div itemprop="name" class="wps-product-title">{ product.libelle }</div>
                                            <ul class="wps-product-attributes">
                                                <li class="wps-product-attributes-item">{ __( 'Unit price:', 'wpshop' ) } {product.subprice} €</li>
                                            </ul>
                                            <div class="wps-product-footer">
                                                <div class="wps-product-quantity">
                                                    <span class="qty">{product.qty}</span>
                                                </div>
                    
                                                {product.subprice && (
                                                    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
                                                        <span itemprop="price">{(product.subprice * product.qty).toFixed(2)}</span>
                                                        <span itemprop="priceCurrency" content="EUR">€</span>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ) )
                }
            </div>
        </div>    
    );
}

render(<AccountOrders />, '.wp-block-wpshop-account-orders');
export default AccountOrders;
