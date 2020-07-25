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

/**
 * Le callback en cas de réussite à la requête Ajax "dismiss".
 * Efface la notice ERP.
 *
 * @since 2.0.0
 * @version 2.0.0
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.settings.dismiss = function( triggeredElement, response ) {
	jQuery( '.notice-erp' ).fadeOut();
};

