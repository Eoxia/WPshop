/**
 * Gestion JS du tunnel de vente.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.product = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.product.init = function() {
	jQuery( document ).on( 'click', '.single-wps-product .wps-product .wps-quantity-minus, .single-wps-product .wps-product .wps-quantity-plus', window.eoxiaJS.wpshopFrontend.product.updateQty );
};

window.eoxiaJS.wpshopFrontend.product.updateQty = function( event ) {
	qty = parseInt( jQuery( '.single-wps-product .wps-product .wps-product-quantity .qty' ).text() );
	var price = parseFloat( jQuery( '.single-wps-product .wps-product .base-price' ).val() );
	if ( jQuery( this ).hasClass( 'wps-quantity-minus' ) ) {
		if ( qty > 1 ) {
			qty--;
		}
	}

	if ( jQuery( this ).hasClass( 'wps-quantity-plus' ) ) {
		qty++;
	}

	price *= qty;

	price = new Intl.NumberFormat( 'fr-FR', { style: 'currency', currency: 'EUR' }).format(price);

	jQuery( '.single-wps-product .wps-product' ).find( '.qty' ).text( qty );

	if ( jQuery( '.single-wps-product .wps-product .wps-product-buy' ).length > 0 ) {
		jQuery( '.single-wps-product .wps-product .wps-product-buy' ).attr( 'data-qty', qty );
	}
	jQuery( '.single-wps-product .wps-product .wps-product-price' ).text( price );
}
