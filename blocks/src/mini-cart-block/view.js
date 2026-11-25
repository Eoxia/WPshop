/**
 * Mini cart: header toggle(s) + one side panel (native <dialog>) for the whole page.
 *
 * The cart is read from the REST API after the page load, so the HTML stays cacheable.
 * Other WPshop blocks talk to it through two document events:
 * - `wpshop:product-added` (cancelable, detail { cart, title }): a product was added. The mini
 *   cart opens and cancels the event, so the sender skips its own notification.
 * - `wpshop:cart-updated` (detail { cart, source }): the cart changed somewhere on the page.
 */
import apiFetch from '@wordpress/api-fetch';
import { __, _n, sprintf } from '@wordpress/i18n';

import { formatPrice } from '../utils/price';
import { CART_ICON_PATH } from './icon';

const EVENT_ADDED = 'wpshop:product-added';
const EVENT_UPDATED = 'wpshop:cart-updated';
const SOURCE = 'mini-cart';

const roots = Array.from( document.querySelectorAll( '[data-wps-mini-cart]' ) );

/**
 * Small DOM helper: el( 'a', { class: 'x', href: '#' }, 'text', otherNode ).
 *
 * @param {string} tag        Tag name.
 * @param {Object} attributes Attributes (on* keys are listeners).
 * @param {...*}   children   Text or nodes.
 *
 * @return {HTMLElement} Element.
 */
const el = ( tag, attributes = {}, ...children ) => {
	const node = document.createElement( tag );

	Object.entries( attributes ).forEach( ( [ key, value ] ) => {
		if ( value === false || value === null || value === undefined ) {
			return;
		}
		if ( key.startsWith( 'on' ) ) {
			node.addEventListener( key.slice( 2 ).toLowerCase(), value );
		} else {
			node.setAttribute( key, value === true ? '' : value );
		}
	} );

	children.flat().forEach( ( child ) => {
		if ( child !== null && child !== undefined && child !== false ) {
			node.append( child );
		}
	} );

	return node;
};

const svg = ( path, className ) => {
	const ns = 'http://www.w3.org/2000/svg';
	const icon = document.createElementNS( ns, 'svg' );
	icon.setAttribute( 'viewBox', '0 0 24 24' );
	icon.setAttribute( 'width', '20' );
	icon.setAttribute( 'height', '20' );
	icon.setAttribute( 'aria-hidden', 'true' );
	icon.setAttribute( 'focusable', 'false' );
	icon.setAttribute( 'class', className );

	const shape = document.createElementNS( ns, 'path' );
	shape.setAttribute( 'd', path );
	shape.setAttribute( 'fill', 'currentColor' );
	icon.append( shape );

	return icon;
};

const ICON_CLOSE = 'M6.4 5 5 6.4l5.6 5.6L5 17.6 6.4 19l5.6-5.6 5.6 5.6 1.4-1.4-5.6-5.6L19 6.4 17.6 5 12 10.6 6.4 5Z';
const ICON_MINUS = 'M5 11h14v2H5z';
const ICON_PLUS = 'M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6z';
const ICON_TRASH = 'M9 3h6l1 1h4v2H4V4h4l1-1Zm-3 5h12l-1 12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 8Zm4 2v9h2v-9h-2Zm-3 0 .7 9h1.3V10H7Zm7 0v9h1.3L16 10h-2Z';

/**
 * The cart API answers [] when the cart is empty.
 *
 * @param {*} response API response.
 *
 * @return {{products: Array, total_ttc: number}} Cart.
 */
const normalize = ( response ) => ( {
	products: Array.isArray( response?.products ) ? response.products : [],
	total_ttc: Number.parseFloat( response?.total_ttc ) || 0,
} );

const countItems = ( cart ) => cart.products.reduce( ( total, product ) => total + ( Number.parseInt( product.qty, 10 ) || 0 ), 0 );

const reducedMotion = () => window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

if ( roots.length ) {
	const config = JSON.parse( roots[ 0 ].dataset.wpsMiniCart || '{}' );

	let cart = null;
	let lastToggle = null;
	const busy = new Set();

	// Panel.
	const title = el( 'h2', { class: 'wps-mini-cart-panel__title', id: 'wps-mini-cart-title' }, __( 'Cart', 'wpshop' ) );
	const titleCount = el( 'span', { class: 'wps-mini-cart-panel__title-count' } );
	title.append( ' ', titleCount );

	const closeButton = el(
		'button',
		{ type: 'button', class: 'wps-mini-cart-panel__close', 'aria-label': __( 'Close the cart', 'wpshop' ), onClick: () => closePanel() },
		svg( ICON_CLOSE, 'wps-mini-cart-panel__close-icon' )
	);

	const body = el( 'div', { class: 'wps-mini-cart-panel__body' } );
	const footer = el( 'div', { class: 'wps-mini-cart-panel__footer' } );

	const panel = el(
		'dialog',
		{ class: `wps-mini-cart-panel is-${ config.side === 'left' ? 'left' : 'right' }`, 'aria-labelledby': 'wps-mini-cart-title' },
		el(
			'div',
			{ class: 'wps-mini-cart-panel__inner' },
			el( 'div', { class: 'wps-mini-cart-panel__header' }, title, closeButton ),
			body,
			footer
		)
	);

	// Out of the header: it would inherit its styles (uppercase menus, colours...).
	document.body.append( panel );

	/**
	 * Updates the toggles: item count, total and accessible name.
	 */
	const renderToggles = () => {
		const count = cart ? countItems( cart ) : 0;

		roots.forEach( ( root ) => {
			const toggle = root.querySelector( '.wps-mini-cart__toggle' );
			const badge = root.querySelector( '.wps-mini-cart__count' );
			const total = root.querySelector( '.wps-mini-cart__total' );

			badge.textContent = String( count );
			badge.hidden = count === 0;

			if ( total ) {
				total.textContent = cart ? formatPrice( cart.total_ttc ) : '';
			}

			toggle.setAttribute(
				'aria-label',
				/* translators: %d: number of items in the cart. */
				sprintf( _n( 'Cart, %d item', 'Cart, %d items', count, 'wpshop' ), count )
			);
		} );

		titleCount.textContent = count ? `(${ count })` : '';
	};

	const renderEmpty = () => {
		body.replaceChildren(
			el(
				'div',
				{ class: 'wps-mini-cart-panel__empty' },
				svg( CART_ICON_PATH, 'wps-mini-cart-panel__empty-icon' ),
				el( 'p', {}, __( 'Your cart is empty.', 'wpshop' ) ),
				el( 'a', { class: 'wps-mini-cart-panel__button is-primary', href: config.shopUrl }, __( 'Browse the shop', 'wpshop' ) )
			)
		);
		footer.replaceChildren();
		footer.hidden = true;
	};

	const renderItem = ( product ) => {
		const id = product.id;
		const qty = Number.parseInt( product.qty, 10 ) || 0;
		const name = product.url
			? el( 'a', { class: 'wps-mini-cart-item__title', href: product.url }, product.title )
			: el( 'span', { class: 'wps-mini-cart-item__title' }, product.title );

		return el(
			'li',
			{ class: `wps-mini-cart-item${ busy.has( id ) ? ' is-busy' : '' }`, 'data-product-id': id, 'aria-busy': busy.has( id ) ? 'true' : false },
			el(
				'div',
				{ class: 'wps-mini-cart-item__thumbnail' },
				product.thumbnail_url ? el( 'img', { src: product.thumbnail_url, alt: '', loading: 'lazy' } ) : null
			),
			el(
				'div',
				{ class: 'wps-mini-cart-item__content' },
				name,
				el( 'span', { class: 'wps-mini-cart-item__unit-price' }, formatPrice( product.price_ttc ) ),
				el(
					'div',
					{ class: 'wps-mini-cart-item__quantity' },
					el(
						'button',
						{
							type: 'button',
							'data-action': 'decrement',
							/* translators: %s: product title. */
							'aria-label': sprintf( __( 'Decrease the quantity of %s', 'wpshop' ), product.title ),
							disabled: busy.has( id ),
							onClick: () => update( id, 'decrement' ),
						},
						svg( ICON_MINUS, 'wps-mini-cart-item__quantity-icon' )
					),
					el( 'span', { class: 'wps-mini-cart-item__qty', 'aria-live': 'polite' }, String( qty ) ),
					el(
						'button',
						{
							type: 'button',
							'data-action': 'increment',
							/* translators: %s: product title. */
							'aria-label': sprintf( __( 'Increase the quantity of %s', 'wpshop' ), product.title ),
							disabled: busy.has( id ),
							onClick: () => update( id, 'increment' ),
						},
						svg( ICON_PLUS, 'wps-mini-cart-item__quantity-icon' )
					)
				)
			),
			el(
				'div',
				{ class: 'wps-mini-cart-item__aside' },
				el( 'span', { class: 'wps-mini-cart-item__total' }, formatPrice( ( Number.parseFloat( product.price_ttc ) || 0 ) * qty ) ),
				el(
					'button',
					{
						type: 'button',
						class: 'wps-mini-cart-item__remove',
						'data-action': 'remove',
						/* translators: %s: product title. */
						'aria-label': sprintf( __( 'Remove %s from the cart', 'wpshop' ), product.title ),
						disabled: busy.has( id ),
						onClick: () => update( id, 'remove' ),
					},
					svg( ICON_TRASH, 'wps-mini-cart-item__remove-icon' )
				)
			)
		);
	};

	/**
	 * Renders the panel content, keeping the focus on the same control after the update.
	 */
	const renderPanel = () => {
		const active = document.activeElement;
		const focusItem = panel.contains( active ) ? active.closest( '[data-product-id]' )?.dataset.productId : null;
		const focusAction = focusItem ? active.dataset.action : null;

		if ( ! cart ) {
			body.replaceChildren( el( 'div', { class: 'wps-mini-cart-panel__loading', role: 'status' }, __( 'Loading…', 'wpshop' ) ) );
			footer.hidden = true;
			return;
		}

		if ( ! cart.products.length ) {
			renderEmpty();
			if ( focusItem ) {
				closeButton.focus();
			}
			return;
		}

		body.replaceChildren( el( 'ul', { class: 'wps-mini-cart-panel__items' }, cart.products.map( renderItem ) ) );

		footer.hidden = false;
		footer.replaceChildren(
			el(
				'div',
				{ class: 'wps-mini-cart-panel__subtotal' },
				el( 'span', {}, __( 'Subtotal', 'wpshop' ) ),
				el( 'strong', {}, formatPrice( cart.total_ttc, 'TTC' ) )
			),
			el(
				'div',
				{ class: 'wps-mini-cart-panel__actions' },
				el( 'a', { class: 'wps-mini-cart-panel__button is-secondary', href: config.cartUrl }, __( 'View cart', 'wpshop' ) ),
				config.checkoutUrl ? el( 'a', { class: 'wps-mini-cart-panel__button is-primary', href: config.checkoutUrl }, __( 'Checkout', 'wpshop' ) ) : null
			)
		);

		if ( focusItem ) {
			const item = body.querySelector( `[data-product-id="${ CSS.escape( focusItem ) }"]` );
			const target = item?.querySelector( `[data-action="${ focusAction }"]` ) || item?.querySelector( 'button' ) || closeButton;
			target.focus();
		}
	};

	/**
	 * Stores a cart answer and refreshes everything.
	 *
	 * @param {*}       response  API response.
	 * @param {boolean} broadcast Tell the other blocks (cart page...).
	 */
	const setCart = ( response, broadcast = true ) => {
		cart = normalize( response );
		renderToggles();

		if ( panel.open ) {
			renderPanel();
		}

		if ( broadcast ) {
			document.dispatchEvent( new CustomEvent( EVENT_UPDATED, { detail: { cart: response, source: SOURCE } } ) );
		}
	};

	const update = ( id, action ) => {
		if ( busy.has( id ) ) {
			return;
		}

		const request = {
			increment: { path: `/wp-shop/v1/cart/${ id }/increment`, method: 'POST', data: { qty: 1 } },
			decrement: { path: `/wp-shop/v1/cart/${ id }/decrement`, method: 'POST' },
			remove: { path: `/wp-shop/v1/cart/${ id }`, method: 'DELETE' },
		}[ action ];

		busy.add( id );
		renderPanel();

		apiFetch( request )
			.then( ( response ) => {
				busy.delete( id );
				setCart( response );
			} )
			.catch( ( error ) => {
				busy.delete( id );
				renderPanel();
				console.error( 'WPshop: cart update failed', error ); // eslint-disable-line no-console
			} );
	};

	const openPanel = ( toggle = null ) => {
		lastToggle = toggle || document.activeElement;

		if ( panel.open ) {
			return;
		}

		renderPanel();
		panel.showModal();
		document.documentElement.classList.add( 'wps-mini-cart-is-open' );

		// Next frame: the closed position is applied, so the slide-in transition runs.
		window.requestAnimationFrame( () => window.requestAnimationFrame( () => panel.classList.add( 'is-open' ) ) );
		closeButton.focus();
	};

	const closePanel = () => {
		if ( ! panel.open ) {
			return;
		}

		const finish = () => {
			panel.close();
			document.documentElement.classList.remove( 'wps-mini-cart-is-open' );
			if ( lastToggle && document.contains( lastToggle ) ) {
				lastToggle.focus();
			}
		};

		panel.classList.remove( 'is-open' );

		if ( reducedMotion() ) {
			finish();
			return;
		}

		let done = false;
		const once = () => {
			if ( ! done ) {
				done = true;
				finish();
			}
		};
		panel.addEventListener( 'transitionend', once, { once: true } );
		window.setTimeout( once, 400 );
	};

	// Escape: animated close instead of the instant native one.
	panel.addEventListener( 'cancel', ( event ) => {
		event.preventDefault();
		closePanel();
	} );

	// The inner box fills the dialog: a click on the dialog itself is a click on the backdrop.
	panel.addEventListener( 'click', ( event ) => {
		if ( event.target === panel ) {
			closePanel();
		}
	} );

	roots.forEach( ( root ) => {
		root.querySelector( '.wps-mini-cart__toggle' ).addEventListener( 'click', ( event ) => openPanel( event.currentTarget ) );
	} );

	document.addEventListener( EVENT_ADDED, ( event ) => {
		if ( event.detail?.cart ) {
			setCart( event.detail.cart );
		}

		if ( config.openOnAdd ) {
			event.preventDefault();
			openPanel();
		}
	} );

	document.addEventListener( EVENT_UPDATED, ( event ) => {
		if ( event.detail?.source !== SOURCE && event.detail?.cart ) {
			setCart( event.detail.cart, false );
		}
	} );

	renderToggles();

	apiFetch( { path: '/wp-shop/v1/cart/' } )
		.then( ( response ) => setCart( response, false ) )
		.catch( ( error ) => console.error( 'WPshop: cart loading failed', error ) ); // eslint-disable-line no-console
}
