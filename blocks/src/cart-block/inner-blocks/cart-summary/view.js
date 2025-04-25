import { render } from '../../../utils';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

// Import nécessaire pour enregistrer le store si ce n'est pas déjà fait
import { register, select, useSelect, useDispatch } from '@wordpress/data';
import { storeName } from '../../../store/cart-products-store';

const CartProductsList = () => {
    const products = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getProducts() : null;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return null;
        }
    }, []);

    if (!products) {
        return <div className="loading">{ __('Chargement des produits...', 'wpshop') }</div>;
    }

    return (
        <ul class="wps-cart-resume">
            <li class="wps-resume-line">
                <span class="wps-line-content">{ __( 'Subtotal', 'wpshop' ) }</span>
                <span class="wps-line-value">{ products.total.toFixed(2) }€</span>
            </li>
            <li class="wps-resume-line">
                <span class="wps-line-content">{ __( 'Taxes', 'wpshop' ) }</span>
                <span class="wps-line-value">{ products.taxes.toFixed(2) }€</span>
            </li>

            <li class="wps-resume-line featured">
                <span class="wps-line-content">{ __( 'Total TTC', 'wpshop' ) }</span>
                <span class="wps-line-value">{ products.total_ttc.toFixed(2) }€</span>
            </li>

        </ul>
    )
};

render(<CartProductsList />, '.wp-block-wpshop-cart-summary');
export default CartProductsList;