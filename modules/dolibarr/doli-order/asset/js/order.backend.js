/**
 * Initialise l'objet "wpshop" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.wpshop.doliOrder = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.wpshop.doliOrder.init = function() {};

window.eoxiaJS.wpshop.doliOrder.markedAsDelivery = function ( triggeredElement, response ) {
	jQuery( '.wps-shipment-tracking' ).replaceWith( response.data.view );
}
