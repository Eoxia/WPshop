/**
 * Initialise l'objet principale de WPshop.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop = {};
window.eoxiaJS.wpshopFrontend = {};

window.eoxiaJS.wpshop.init = function() {
	if ( jQuery( '.wps-sync' ).length ) {
		jQuery( '.wps-sync' ).each( function() {
			var data = {
				action: 'check_sync_status',
				wp_id: jQuery( this ).find( '.button-synchro' ).data( 'wp-id' ),
				type: jQuery( this ).find( '.button-synchro' ).data( 'type' )
			};

			window.eoxiaJS.loader.display( jQuery( this ) );

			var _this = jQuery( this );

			// @todo: Handle fatal error or no response.
			jQuery.post( ajaxurl, data, function( response ) {
				window.eoxiaJS.loader.remove(_this);
				_this.replaceWith( response.data.view );

				if ( response.data.status && response.data.status.status_code == '0x1' ) {
					jQuery( '.table-row[data-id="' + response.data.id + '"] .reference-id li:last' ).remove();
				}
			} )
				.fail( function() {
					window.eoxiaJS.loader.remove(_this);
					_this.find( ".statut" ).attr( 'aria-label', '500 (Internal Server Error)' );
					_this.find( ".statut" ).addClass( 'statut-red' );
				} );
		} );
	}

	var data = {
		action: 'check_erp_statut',
		_wpnonce: scriptParams.check_erp_statut_nonce
	};

	jQuery.post( ajaxurl, data, function( response ) {
		if ( ! response.data.statut && response.data.view ) {
			jQuery( 'body' ).append( response.data.view );
		}
	} );
};
