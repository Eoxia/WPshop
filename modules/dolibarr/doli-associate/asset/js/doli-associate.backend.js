/**
 * Gestion JS des assoications Dolibarr.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliAssociate.init = function() {
	jQuery( document ).on( 'keyup', '.synchro-single .filter-entry', window.eoxiaJS.wpshop.doliAssociate.filter );
	jQuery( document ).on( 'click', '.synchro-single li', window.eoxiaJS.wpshop.doliAssociate.clickEntry );
};

/**
 * Filtre les entités a associer.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {KeyboardEvent} event [filter].
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
 * Clique sur l'entité a associer.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [clickEntry].
 */
window.eoxiaJS.wpshop.doliAssociate.clickEntry = function( event ) {
	jQuery( '.synchro-single li.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
	jQuery( '.synchro-single input[name="entry_id"]' ).val( jQuery( this ).data( 'id' ) );
};

/**
 * Le callback en cas de réussite à la requête Ajax "go_sync".
 * Lance la l'association des entités.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 *
 * @return boolean                         True si tout c'est bien passé.
 */

window.eoxiaJS.wpshop.doliAssociate.goSync = function( triggeredElement ) {
	jQuery( triggeredElement ).closest( '.wpeo-modal' ).addClass( 'modal-force-display' );

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "open_modal_compare".
 * Ouvre la modal de comparaison des entités.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
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
 * Le callback en cas de réussite à la requête Ajax "associate_entry".
 * Associe l'entité.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.doliAssociate.associatedEntrySuccess = function ( triggeredElement, response ) {
	jQuery( '.wpeo-modal.modal-active .modal-content' ).html( response.data.view );
	jQuery( '.wpeo-modal.modal-active .modal-footer' ).html( response.data.modal_footer );
	jQuery( '.table-row[data-id="' + response.data.id + '"]' ).replaceWith( response.data.line_view );
};
