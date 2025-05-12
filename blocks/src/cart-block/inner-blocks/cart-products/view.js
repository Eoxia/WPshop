import { render } from '../../../utils';
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';

import { useSelect, useDispatch } from '@wordpress/data';
import { storeName } from '../../../store/cart-products-store';

import apiFetch from '@wordpress/api-fetch';

const CartProductsList = () => {
    // Récupérer la valeur du compteur depuis le store
    const products = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getProducts() : 0;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return 0;
        }
    }, []);

    const { setValue } = useDispatch(storeName);

    useEffect(() => {
        apiFetch({
            path: '/wp-shop/v1/cart/'
        }).then((response) => {
            if (response) {
                setValue(response);
            } else {
                console.error('Erreur lors de la récupération des produits:', response);
            }
        });
    }, []);

    const suppressProduct = (productId) => {
        apiFetch({
            path: `/wp-shop/v1/cart/${productId}`,
            method: 'DELETE'
        }).then((response) => {
            if (response) {
                if (!response?.products?.length) {
                    window.location.reload();
                } else {
                    setValue(response);
                }
            } else {
                console.error('Erreur lors de la suppression du produit:', response);
            }
        });
    }

    const incrementProduct = (productId) => {
        apiFetch({
            path: `/wp-shop/v1/cart/${productId}/increment`,
            method: 'POST'
        }).then((response) => {
            if (response) {
                setValue(response);
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
                    window.location.reload();
                } else {
                    setValue(response);
                }
            } else {
                console.error('Erreur lors de l\'incrémentation du produit:', response);
            }
        });
    }


    if (!products) {
        return <div className="loading">{ __('Chargement des produits...', 'wpshop') }</div>;
    }

    return (
        <div className="wps-list-product gridw-2">
            {products.products?.map((product) => (
                <div itemscope itemtype="https://schema.org/Product" class="wps-product">
                    <a class="wps-delete-product" onClick={() => suppressProduct(product.id)}>
                        <i class="wps-delete-product-icon fas fa-times-circle"></i>
                    </a>
                
                    <figure class="wps-product-thumbnail">
                        <img src={product.thumbnail_url} class="attachment-wps-product-thumbnail" itemprop="image" />
                    </figure>
                
                    <div class="wps-product-content">
                        <div itemprop="name" class="wps-product-title">{ product.title }</div>
                        <ul class="wps-product-attributes">
                            <li class="wps-product-attributes-item">{ __( 'Unit price:', 'wpshop' ) } {product.price_ttc} €</li>
                        </ul>
                        <div class="wps-product-footer">
                            <div class="wps-product-quantity" style={{ fontSize: '1.4em' }}>
                                <span class="wps-quantity-minus fas fa-minus-circle" onClick={() => decrementProduct(product.id)}></span>
                                <span class="qty">{product.qty}</span>
                                <span class="wps-quantity-plus fas fa-plus-circle" onClick={() => incrementProduct(product.id)}></span>
                            </div>

                            {product.price_ttc && (
                                <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
                                    <span itemprop="price">{(product.price_ttc * product.qty).toFixed(2)}</span>
                                    <span itemprop="priceCurrency" content="EUR">€</span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};

render(<CartProductsList />, '.wp-block-wpshop-cart-products');
export default CartProductsList;