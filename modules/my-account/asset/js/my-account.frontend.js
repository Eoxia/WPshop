/**
 * Gestion JS de My account.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.myAccount = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.myAccount.init = function() {
	window.eoxiaJS.wpshopFrontend.myAccount.event();
};

/**
 * Les évènements de My account.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.myAccount.event = function() {
	jQuery( '.wps-box-display-more' ).click( function() {
		jQuery( this ).toggleClass( 'active' );
		jQuery( this ).closest( '.wps-box' ).find( '.wps-box-detail' ).slideToggle( 'fast' )
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "reoder".
 * Redirection.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshopFrontend.myAccount.reorderSuccess = function( triggeredElement, response ) {
	document.location.href = response.data.redirect_url;
};
