/**
 * Gestion JS du tunnel de vente.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.cart = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.cart.init = function() {
	window.eoxiaJS.wpshopFrontend.cart.event();
};

window.eoxiaJS.wpshopFrontend.cart.event = function() {
	jQuery( document ).on( 'click', '.wps-cart .wps-product-quantity .wps-quantity-minus', window.eoxiaJS.wpshopFrontend.cart.updateQuantity );
	jQuery( document ).on( 'click', '.wps-cart .wps-product-quantity .wps-quantity-plus', window.eoxiaJS.wpshopFrontend.cart.updateQuantity );
}

window.eoxiaJS.wpshopFrontend.cart.updateQuantity = function() {
	var qty = parseInt( jQuery( this ).closest( '.wps-product-quantity' ).find( 'input[type="hidden"]' ).val() );

	if ( jQuery( this ).hasClass( 'wps-quantity-minus' ) ) {
		if ( qty > 1 ) {
			qty--;
		}
	}

	if ( jQuery( this ).hasClass( 'wps-quantity-plus' ) ) {
		qty++;
	}

	jQuery( this ).closest( '.wps-product-quantity' ).find( '.qty' ).text( qty );
	jQuery( this ).closest( '.wps-product-quantity' ).find( 'input[type="hidden"]' ).val( qty );
	jQuery( '.wps-cart .update-cart' ).click();

	window.eoxiaJS.loader.display( jQuery( '.wps-cart-resume' ) );
};

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

window.eoxiaJS.wpshopFrontend.cart.updatedCart = function ( triggeredElement, response ) {
	jQuery( '.wps-cart' ).replaceWith( response.data.view );

	if ( response.data.qty == 0 ) {
		jQuery( '.cart-button .qty' ).html( '<span class="qty-value"></span>' );

	} else {
		jQuery( '.cart-button .qty' ).html( '(<span class="qty-value">' + response.data.qty + '</span>)' );
	}
};

window.eoxiaJS.wpshopFrontend.cart.deletedProdutFromCart = function ( triggeredElement, response ) {
	jQuery( '.wps-cart' ).replaceWith( response.data.view );

	if ( response.data.qty == 0 ) {
		jQuery( '.cart-button .qty' ).html( '<span class="qty-value"></span>' );

	} else {
		jQuery( '.cart-button .qty' ).html( '(<span class="qty-value">' + response.data.qty + '</span>)' );
	}
};
