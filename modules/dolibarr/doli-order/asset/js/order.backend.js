/**
 * Gestion JS des commandes.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliOrder = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliOrder.init = function() {};

/**
 * Le callback en cas de réussite à la requête Ajax "mark_as_delivery".
 * Marque la commande comme livrée.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.doliOrder.markedAsDelivery = function ( triggeredElement, response ) {
	jQuery( '.wps-shipment-tracking' ).replaceWith( response.data.view );
};
