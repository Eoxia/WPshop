/**
 * Add to cart buttons of the catalogues: one listener for every button of the page.
 */
import apiFetch from '@wordpress/api-fetch';
import { __, sprintf } from '@wordpress/i18n';

const showNotification = ( title, cartUrl ) => {
	const notification = document.createElement( 'div' );
	notification.className = 'wpeo-notification notification-active notification-add-to-cart notification-blue';
	notification.style.opacity = '1';
	notification.style.background = 'rgba(255,255,255,1)';

	const icon = document.createElement( 'i' );
	icon.className = 'notification-icon fas fa-info';

	const content = document.createElement( 'div' );
	content.className = 'notification-title';
	/* translators: %s: product title. */
	content.textContent = sprintf( __( '"%s" added to the cart', 'wpshop' ), title ) + ' ';

	const link = document.createElement( 'a' );
	link.className = 'view-cart wpeo-button button-grey';
	link.href = cartUrl;
	link.textContent = __( 'View cart', 'wpshop' );
	content.appendChild( link );

	const close = document.createElement( 'div' );
	close.className = 'notification-close';
	close.innerHTML = '<i class="fas fa-times"></i>';
	close.addEventListener( 'click', () => notification.remove() );

	notification.append( icon, content, close );
	document.body.appendChild( notification );

	setTimeout( () => notification.remove(), 5000 );
};

document.addEventListener( 'click', ( event ) => {
	const button = event.target.closest( '.wp-block-wpshop-product-button .wps-button-add-to-cart' );

	if ( ! button || button.disabled ) {
		return;
	}

	event.preventDefault();
	button.disabled = true;
	button.classList.add( 'button-load' );

	apiFetch( {
		path: `/wp-shop/v1/cart/${ button.dataset.productId }/increment`,
		method: 'POST',
		data: { qty: 1 },
	} )
		.then( ( cart ) => {
			// The mini cart (if any) opens and cancels the event: no notification then.
			const added = new CustomEvent( 'wpshop:product-added', {
				cancelable: true,
				detail: { cart, title: button.dataset.productTitle, trigger: button },
			} );

			if ( document.dispatchEvent( added ) ) {
				showNotification( button.dataset.productTitle, button.dataset.cartUrl );
			}
		} )
		.catch( ( error ) => console.error( 'WPshop: add to cart failed', error ) ) // eslint-disable-line no-console
		.finally( () => {
			button.disabled = false;
			button.classList.remove( 'button-load' );
		} );
} );
