/**
 * Gestion JS du tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.thirdParties = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.thirdParties.init = function () {
	window.eoxiaJS.wpshop.thirdParties.event();
};

/**
 * Les évènements du tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.thirdParties.event = function () {
	jQuery( document ).on( 'click', '#wps-third-party-contact .add-contact', window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew );
	jQuery( document ).on( 'click', '.wpeo-autocomplete.search-contact .autocomplete-search-list .autocomplete-result', window.eoxiaJS.wpshop.thirdParties.putContactID );
};

/**
 * Change l'état du toggle.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [toggleContactFormNew].
 */
window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew = function() {
	jQuery( '#wps-third-party-contact .row.new' ).toggle();
};

/**
 * Ajoute l'id du contact lors de l'association d'un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [putContactID].
 */
window.eoxiaJS.wpshop.thirdParties.putContactID = function() {
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( '.button-associate-contact' ).attr( 'data-contact-id', jQuery( this ).data( 'id' ) );
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( 'input#search-contact' ).val( jQuery( this ).data( 'result' ) );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_title_edit".
 * Charge l'édition du titre.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.loaddedTitleEdit = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "save_third".
 * Sauvegarde les données d'un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.savedThird = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_billing_address".
 * Charge l'addresse de facturation d'un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.loaddedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "save_billing_adress".
 * Sauvegarde l'adresse de facturation d'un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.savedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_contact".
 * Charge toutes les contacts d'un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.loaddedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( 'tr' ).replaceWith( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "save_contact".
 * Sauvegarde les données d'un contact.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.savedContact = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "associate_contact".
 * Associe un contact à un tier.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.thirdParties.associatedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
};
