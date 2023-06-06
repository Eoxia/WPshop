/**
 * Gestion JS du produit.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.product = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since 2.0.0
 * @version 2.6.0
 */
window.eoxiaJS.wpshopFrontend.product.init = function() {
	jQuery( document ).on( 'click', '.wps-quantity-minus, .wps-quantity-plus', window.eoxiaJS.wpshopFrontend.product.updateQty );
};

/**
 * Met à jour la quantité d'un produit.
 *
 * @since   2.0.0
 * @version 2.6.0
 *
 * @param {ClickEvent} event [updateQty].
 */
window.eoxiaJS.wpshopFrontend.product.updateQty = function( event ) {
	let itemQuantity = jQuery('.wps-product-quantity .qty').text();
	var itemPrice    = parseFloat( jQuery( '.wps-product-content .base-price' ).val() );

	if (jQuery(this).hasClass('wps-quantity-plus')) {
		itemQuantity++
	} else {
		itemQuantity--
	}

	itemQuantity = itemQuantity > 1 ? itemQuantity : 1

	itemPrice *= itemQuantity;
	itemPrice = new Intl.NumberFormat( 'fr-FR', { style: 'currency', currency: 'EUR' }).format(itemPrice);

	jQuery('.wps-product-quantity .qty').text(itemQuantity);
	jQuery('.wps-product-content .wps-product-price').html( itemPrice );
	jQuery( '.wps-product-buy' ).attr( 'data-qty', itemQuantity );
};
