/**
 * Gestion JS du panier.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.cart = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.cart.init = function() {
	window.eoxiaJS.wpshopFrontend.cart.event();
};

window.eoxiaJS.wpshopFrontend.cart.event = function() {
	jQuery( document ).on( 'click', '.wps-cart .wps-product-quantity .wps-quantity-minus', window.eoxiaJS.wpshopFrontend.cart.updateQuantity );
	jQuery( document ).on( 'click', '.wps-cart .wps-product-quantity .wps-quantity-plus', window.eoxiaJS.wpshopFrontend.cart.updateQuantity );
};

/**
 * Met à jour la quantité d'un produit.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [updateQuantity].
 */
window.eoxiaJS.wpshopFrontend.cart.updateQuantity = function( event ) {
	var qty = parseInt( jQuery( this ).closest( '.wps-product-quantity' ).find( 'input[type="hidden"]' ).val() );

	if ( jQuery( this ).hasClass( 'wps-quantity-minus' ) ) {
		qty--;
	} else {
		qty++;
	}

	jQuery( this ).closest( '.wps-product-quantity' ).find( 'input[type="hidden"]' ).val( qty );
	jQuery( '.wps-cart .update-cart' ).click();

	window.eoxiaJS.loader.display( jQuery( '.wps-cart-resume' ) );
};

/**
 * Le callback en cas de réussite à la requête Ajax "add_to_cart".
 * Ajoute un produit au panier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshopFrontend.cart.addedToCart = function ( triggeredElement, response ) {
	if ( response.data.added ) {
		var tmp = jQuery( response.data.view );
		jQuery( 'body' ).append( tmp );

		tmp.timeout = setTimeout( function() {
			tmp.remove();
		}, 5000 );
	}

	if ( response.data.qty ) {
		jQuery( '.cart-button .qty' ).html( '(<span class="qty-value">' + response.data.qty + '</span>)' );
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "update_cart".
 * Met à jour le panier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshopFrontend.cart.updatedCart = function ( triggeredElement, response ) {
	jQuery( '.wps-cart' ).replaceWith( response.data.view );

	if ( response.data.qty == 0 ) {
		jQuery( '.cart-button .qty' ).html( '<span class="qty-value"></span>' );

	} else {
		jQuery( '.cart-button .qty' ).html( '(<span class="qty-value">' + response.data.qty + '</span>)' );
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_product_form_cart".
 * Supprime un produit du panier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshopFrontend.cart.deletedProdutFromCart = function ( triggeredElement, response ) {
	jQuery( '.wps-cart' ).replaceWith( response.data.view );

	if ( response.data.qty == 0 ) {
		jQuery( '.cart-button .qty' ).html( '<span class="qty-value"></span>' );

	} else {
		jQuery( '.cart-button .qty' ).html( '(<span class="qty-value">' + response.data.qty + '</span>)' );
	}
};
