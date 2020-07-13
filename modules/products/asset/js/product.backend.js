/**
 * Gestion JS des produits.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.product = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshop.product.init = function() {
	window.eoxiaJS.wpshop.product.event();
};

window.eoxiaJS.wpshop.product.event = function() {
	jQuery( document ).on( 'wps-change-toggle', '.stock-field .toggle', window.eoxiaJS.wpshop.product.displayBlockStock );
	jQuery( document ).on( 'click', '.wps-list-product .table-header input[type="checkbox"]', window.eoxiaJS.wpshop.product.checkAll );
	jQuery( document ).on( 'click', '.button-apply', window.eoxiaJS.wpshop.product.apply );
	jQuery( document ).ready( window.eoxiaJS.wpshop.product.autoSynchro );
	jQuery( '.similar-product' ).select2({
		ajax: {
			url: scriptParams.url + '/wp-json/wpshop/v2/product/search',
			data: function (params) {
				var query = {
					s: params.term,
				};

				// Query parameters will be ?search=[term]&type=public
				return query;
			},
			processResults: function( data ) {
				var items = [];

				for ( var key in data ) {
					var item = {
						id: data[key].id,
						text: data[key].title
					};

					items.push( item );
				}

				return {
					results: items
				};
			},
			cache: true
		},
		minimumInputLength: 1
	});
};

window.eoxiaJS.wpshop.product.displayBlockStock = function( event, toggleState ) {
	if ( toggleState ) {
		jQuery( '.stock-block' ).fadeIn();
	} else {
		jQuery( '.stock-block' ).fadeOut();
	}
};

window.eoxiaJS.wpshop.product.changeMode = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'div.table-row' ).replaceWith( response.data.view );
};

window.eoxiaJS.wpshop.product.checkAll = function() {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( '.wps-list-product .table-row:not(.table-header) input[type="checkbox"]' ).attr( 'checked', true );
	} else {
		jQuery( '.wps-list-product .table-row:not(.table-header) input[type="checkbox"]' ).attr( 'checked', false );
	}
};

window.eoxiaJS.wpshop.product.apply = function() {
	var val = jQuery( '.select-apply' ).val();

	if ( 'quick-edit' === val ) {
		jQuery( '.wps-list-product .table-row:not(.table-header)' ).each( function() {
			if ( jQuery( this ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
				jQuery( this ).find( '.action-attribute[data-action="change_mode"]' ).click();
			}
		} );
	}
};

window.eoxiaJS.wpshop.product.autoSynchro = function ( event ) {
	var element = jQuery( this ).find( '.button-synchro[data-entry-id="1"]' );

	//element.click();
	console.log('test');
};
