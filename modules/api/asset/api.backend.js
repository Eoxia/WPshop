/**
 * Gestion JS de l'api.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.API = {}; // Déclaration de mon objet JS qui vas contenir toutes les functions

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.API.init = function () {
	window.eoxiaJS.wpshop.API.event();
};

window.eoxiaJS.wpshop.API.event = function () {};

/**
 * Le callback en cas de réussite à la requête Ajax "generate_api_key".
 * Génère une clé API pour un utiliseur.
 *
 * @since 2.0.0
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 *
 * @return {void}
 */
window.eoxiaJS.wpshop.API.generatedAPIKey = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpshop-fields' ).replaceWith( response.data.view );
}
