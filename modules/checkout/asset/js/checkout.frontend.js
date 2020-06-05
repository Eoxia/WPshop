/**
 * Gestion JS du tunnel de vente.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.checkout = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.checkout.init = function() {
	window.eoxiaJS.wpshopFrontend.checkout.event();
};

window.eoxiaJS.wpshopFrontend.checkout.event = function() {
	jQuery( '.checkout-login .checkout-login-toggle' ).click( function() {
		jQuery( '.checkout-login .content-login' ).slideToggle();
	} );

	jQuery( document ).on( 'change', '.wps-checkout .wps-payment-list li.wps-payment input', window.eoxiaJS.wpshopFrontend.checkout.makeActive );
};

window.eoxiaJS.wpshopFrontend.checkout.makeActive = function( event ) {
	jQuery( '.wps-checkout .wps-payment-list li.wps-payment.checked' ).removeClass( 'checked' );
	jQuery( this ).closest( 'li.wps-payment' ).addClass( 'checked' );
};

window.eoxiaJS.wpshopFrontend.checkout.loadedEditBillingAddress = function( triggeredElement, response ) {
	jQuery( '.shipping-contact' ).html( response.data.view );
};

window.eoxiaJS.wpshopFrontend.checkout.checkoutErrors = function( triggeredElement, response ) {
	if ( 0 === jQuery( 'form.wps-checkout-step-1 ul.error.notice' ).length ) {
		jQuery( '.wps-checkout .wps-checkout-step-1' ).prepend( response.data.template );
	} else {
		jQuery( '.wps-checkout .wps-checkout-step-1 ul.error.notice' ).replaceWith( response.data.template );
	}

	for ( var key in response.data.errors.error_data ) {
		jQuery( '.wps-checkout .' + response.data.errors.error_data[ key ].input_class ).addClass( 'form-element-error' );
	}
};

window.eoxiaJS.wpshopFrontend.checkout.createdThirdSuccess = function( triggeredElement, response ) {
	document.location.href = response.data.redirect_url;
}

window.eoxiaJS.wpshopFrontend.checkout.redirectToPayment = function( triggeredElement, response ) {
	document.location.href = response.data.url;
};

window.eoxiaJS.wpshopFrontend.checkout.redirect = function( triggeredElement, response ) {
	document.location.href = response.data.url;
};
