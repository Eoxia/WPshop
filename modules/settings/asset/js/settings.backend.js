/**
 * Gestion JS des réglages.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.settings = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.settings.init = function() {};

window.eoxiaJS.wpshop.settings.dismiss = function( triggeredElement, response ) {
	jQuery( '.notice-erp' ).fadeOut();
};
