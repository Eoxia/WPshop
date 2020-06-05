window.eoxiaJS.wpshop.thirdParties = {}; // Déclaration de mon objet JS qui vas contenir toutes les functions


window.eoxiaJS.wpshop.thirdParties.init = function () {
	window.eoxiaJS.wpshop.thirdParties.event();
};

window.eoxiaJS.wpshop.thirdParties.event = function () {
	jQuery( document ).on( 'click', '#wps-third-party-contact .add-contact', window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew );
	jQuery( document ).on( 'click', '.wpeo-autocomplete.search-contact .autocomplete-search-list .autocomplete-result', window.eoxiaJS.wpshop.thirdParties.putContactID );
};

window.eoxiaJS.wpshop.thirdParties.toggleContactFormNew = function() {
	jQuery( '#wps-third-party-contact .row.new' ).toggle();
}


window.eoxiaJS.wpshop.thirdParties.putContactID = function() {
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( '.button-associate-contact' ).attr( 'data-contact-id', jQuery( this ).data( 'id' ) );
	jQuery( this ).closest( '.wpeo-autocomplete' ).find( 'input#search-contact' ).val( jQuery( this ).data( 'result' ) );
};

window.eoxiaJS.wpshop.thirdParties.loaddedTitleEdit = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedThird = function ( triggeredElement, response ) {
	triggeredElement.closest( 'h2' ).html( response.data.view );
}


window.eoxiaJS.wpshop.thirdParties.loaddedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedBillingAddressSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.loaddedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( 'tr' ).replaceWith( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.savedContact = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}

window.eoxiaJS.wpshop.thirdParties.associatedContactSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.inside' ).html( response.data.view );
}
