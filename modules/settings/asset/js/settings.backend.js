/**
 * Gestion JS des réglages.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.settings = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since   2.0.0
 * @version 2.3.0
 */
window.eoxiaJS.wpshop.settings.init = function() {
	window.eoxiaJS.wpshop.settings.event();
};

window.eoxiaJS.wpshop.settings.event = function() {
	jQuery( document ).on( 'click', '.wpeo-form.payment-method .bloc-activate .button-toggle', window.eoxiaJS.wpshop.settings.buttonToggle );
};

/**
 * Récupère l'état du bouton toggle.
 *
 * @since   2.3.0
 * @version 2.3.0
 *
 * @param  {ClickEvent} event [t]
 *
 * @return {void}
 */
window.eoxiaJS.wpshop.settings.buttonToggle = function( event ) {
	var toggleON = jQuery( this ).hasClass( 'fa-toggle-on' );
	var nextStep = '';
	if (toggleON) {
		nextStep = 'false';
		jQuery( this ).removeClass( "fa-toggle-on" ).addClass( "fa-toggle-off" );
	} else {
		nextStep = 'true';
		jQuery( this ).removeClass( "fa-toggle-off" ).addClass( "fa-toggle-on" );
	}
	jQuery( this ).closest( '.wpeo-form.payment-method' ).find( '.activate' ).attr( 'value' , nextStep );
};

/**
 * Le callback en cas de réussite à la requête Ajax "dismiss".
 * Cache la notice ERP.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.settings.dismiss = function( triggeredElement, response ) {
	jQuery( '.notice-erp' ).fadeOut();
};

