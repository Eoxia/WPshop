/**
 * Gestion JS des outils.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.tools = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.tools.init = function() {
	jQuery( document ).on( 'click', '.import-third-party .wpeo-button.button-primary', window.eoxiaJS.wpshop.tools.importThirdParty )
	jQuery( document ).on( 'click', '.import-product .wpeo-button.button-primary', window.eoxiaJS.wpshop.tools.importProduct )
};

/**
 * Importe les tiers.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [importThirdParty].
 */
window.eoxiaJS.wpshop.tools.importThirdParty = function( event ) {
	var data = new FormData();

	event.preventDefault();

	data.append( 'file', jQuery( '.import-third-party input[type=file]' )[0].files[0] );
	data.append( 'action', 'import_third_party' );
	data.append( '_wpnonce', jQuery( this ).closest( 'form' ).find( 'input[name="_wpnonce"]' ).val() );
	data.append( 'index_element', 0 );

	window.eoxiaJS.wpshop.tools.requestImportThirdParty( data );
};

/**
 * Importe les produits.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {ClickEvent} event [importProduct].
 */
window.eoxiaJS.wpshop.tools.importProduct = function( event ) {
	var data = new FormData();

	event.preventDefault();

	data.append( 'file', jQuery( '.import-product input[type=file]' )[0].files[0] );
	data.append( 'action', 'import_third_party' );
	data.append( '_wpnonce', jQuery( this ).closest( 'form' ).find( 'input[name="_wpnonce"]' ).val() );
	data.append( 'index_element', 0 );

	window.eoxiaJS.wpshop.tools.requestImportProduct( data );
};
/**
 * Lances la requête pour importer les données des tiers.
 * Modifie la barre de progression.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param  {object} data Les données pour la requête
 */
window.eoxiaJS.wpshop.tools.requestImportThirdParty = function( data ) {
	jQuery.ajax( {
		url: ajaxurl,
		data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		beforeSend: function() {
			window.eoxiaJS.loader.display(  jQuery( '.import-third-party .wpeo-button' ) );
			jQuery( '.import-details' ).html( 'In progress' );
		},
		success: function( response ) {
			var data = new FormData();

			if ( response.success ) {
				jQuery( '.import-third-party progress' ).attr( 'max', response.data.count_element );
				jQuery( '.import-third-party progress' ).val( ( response.data.index_element / response.data.count_element ) * response.data.count_element );

				if ( ! response.data.end ) {
					data.append( 'action', 'import_third_party' );
					data.append( '_wpnonce', jQuery( '.import-third-party' ).find( 'input[name="_wpnonce"]' ).val() );
					data.append( 'path_to_json', response.data.path_to_json );
					data.append( 'index_element', response.data.index_element );
					data.append( 'count_element', response.data.count_element );
					jQuery( '.import-detail' ).html( 'Progress' );
					window.eoxiaJS.wpshop.tools.requestImport( data );
				} else {
					jQuery( '.import-detail' ).html( 'Importation terminé' );
					window.eoxiaJS.loader.remove(  jQuery( '.import-third-party .wpeo-button' ) );

				}
			} else {
			}
		}
	} );
};


/**
 * Lances la requête pour importer les données des produits.
 * Modifie la barre de progression.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {object} data Les données pour la requête.
 */
window.eoxiaJS.wpshop.tools.requestImportProduct = function( data ) {
	jQuery.ajax( {
		url: ajaxurl,
		data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		beforeSend: function() {
			window.eoxiaJS.loader.display(  jQuery( '.import-product .wpeo-button' ) );
			jQuery( '.import-details' ).html( 'In progress' );
		},
		success: function( response ) {
			var data = new FormData();

			if ( response.success ) {
				jQuery( '.import-product progress' ).attr( 'max', response.data.count_element );
				jQuery( '.import-product progress' ).val( ( response.data.index_element / response.data.count_element ) * response.data.count_element );

				if ( ! response.data.end ) {
					data.append( 'action', 'import_product' );
					data.append( '_wpnonce', jQuery( '.import-product' ).find( 'input[name="_wpnonce"]' ).val() );
					data.append( 'path_to_json', response.data.path_to_json );
					data.append( 'index_element', response.data.index_element );
					data.append( 'count_element', response.data.count_element );
					jQuery( '.import-detail' ).html( 'Progress' );
					window.eoxiaJS.wpshop.tools.requestImportProduct( data );
				} else {
					jQuery( '.import-detail' ).html( 'Importation terminé' );
					window.eoxiaJS.loader.remove(  jQuery( '.import-product .wpeo-button' ) );

				}
			} else {
			}
		}
	} );
};
