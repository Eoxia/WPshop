/**
 * Initialise l'objet "wpshop" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 * @todo: Translate to english.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.init = function() {
	jQuery( document ).on( 'keyup', '.synchro-single .filter-entry', window.eoxiaJS.wpshop.doliAssociate.filter );
	jQuery( document ).on( 'click', '.synchro-single li', window.eoxiaJS.wpshop.doliAssociate.clickEntry );
};

/**
 * @todo: Comment
 * @param event
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.filter = function( event ) {
	var entries = jQuery( '.synchro-single ul.select li' );
	entries.show();

	var val = jQuery( this ).val().toLowerCase();

	for ( var i = 0; i < entries.length; i++ ) {
		if ( jQuery( entries[i] ).text().toLowerCase().indexOf( val ) == -1 ) {
			jQuery( entries[i] ).hide();
		}
	}
};

/**
 * @todo: Comment
 *
 * @param event
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.clickEntry = function( event ) {
	jQuery( '.synchro-single li.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
	jQuery( '.synchro-single input[name="entry_id"]' ).val( jQuery( this ).data( 'id' ) );
};

/**
 * @todo: Comment
 *
 * @param triggeredElement
 * @returns {boolean}
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.goSync = function (triggeredElement) {
	jQuery( triggeredElement ).closest( '.wpeo-modal' ).addClass( 'modal-force-display' );

	return true;
};

/**
 * @todo: Comment
 * @param triggeredElement
 * @param response
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.openModalCompareSuccess = function ( triggeredElement, response ) {
	if ( response.data.error ) {
		jQuery( '.wpeo-modal.modal-active ul.select' ).css( 'border-color', 'red' );
	} else {
		jQuery( '.wpeo-modal.modal-active .modal-content' ).html( response.data.view );
		jQuery( '.wpeo-modal.modal-active .modal-footer' ).html( response.data.footer_view );
	}
};

/**
 * @todo: Comment
 * @param triggeredElement
 * @param response
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.associatedEntrySuccess = function ( triggeredElement, response ) {
	jQuery( '.wpeo-modal.modal-active .modal-content' ).html( response.data.view );
	jQuery( '.wpeo-modal.modal-active .modal-footer' ).html( response.data.modal_footer );
	jQuery( '.table-row[data-id="' + response.data.id + '"]' ).replaceWith( response.data.line_view );
};
