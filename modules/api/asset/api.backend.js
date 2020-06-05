window.eoxiaJS.wpshop.API = {}; // Déclaration de mon objet JS qui vas contenir toutes les functions


window.eoxiaJS.wpshop.API.init = function () {
	window.eoxiaJS.wpshop.API.event();
};

window.eoxiaJS.wpshop.API.event = function () {};

window.eoxiaJS.wpshop.API.generatedAPIKey = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpshop-fields' ).replaceWith( response.data.view );
}
