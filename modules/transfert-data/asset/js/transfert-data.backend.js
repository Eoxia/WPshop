/**
 * Gestion JS des produits.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.transfertData = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.transfertData.init = function() {
	if ( jQuery( '.transfert-data' ).length > 0 ) {
		window.eoxiaJS.wpshop.transfertData.start(0);
	}

	jQuery( document ).on( 'click', '.wrap .toggle', function(e) {
		jQuery( this ).closest( '.form-element' ).find( '.' + jQuery( this ).data( 'bloc' ) ).toggle()
		var value = ( jQuery( this ).closest( '.form-element' ).find( '.' + jQuery( this ).data( 'input' ) ).val() == 'true' ) ? true : false;
		value = !value;
		jQuery( this ).closest( '.form-element' ).find( '.' + jQuery( this ).data( 'input' ) ).val(value);

		if (value) {
			jQuery( this ).removeClass( 'fa-toggle-off' ).addClass( 'fa-toggle-on' );
		} else {
			jQuery( this ).removeClass( 'fa-toggle-on' ).addClass( 'fa-toggle-off' );
		}

		jQuery( this ).trigger( 'wps-change-toggle', value );
	} );
};

window.eoxiaJS.wpshop.transfertData.start = function(index, index_error) {
	var data = {
		action: 'wps_transfert_data',
		_wpnonce: jQuery( '.wrap input#_wpnonce' ).val(),
		number_customers: jQuery( '.wrap input[name=number_customers]' ).val(),
		index: index,
		index_error: index_error,
		key_query: jQuery( '.wrap input[name=key_query]').val()
	};

	jQuery.post( window.ajaxurl, data, function( response ) {
		jQuery( '.wrap ul.output' ).append( response.data.output );
		jQuery( '.wrap ul.errors' ).append( response.data.errors );
		jQuery( '.wrap input[name=index]' ).val( response.data.index );
		jQuery( '.wrap input[name=key_query]' ).val( response.data.key_query );

		if ( ! response.data.done ) {
			window.eoxiaJS.wpshop.transfertData.start( response.data.index, response.data.index_error );
		}
	} );
}
