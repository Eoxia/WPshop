jQuery(function($){

	// Set all variables to be used in scope
	var frame,
		metaBox = $('#wps_product_gallery.postbox'), // Your meta box id here
		addImgLink = metaBox.find('.upload-custom-img'),
		delImgLink = metaBox.find( '.delete-custom-img'),
		imgContainer = metaBox.find( '.wps-product-gallery-container' ),
		imgIdInput = metaBox.find( '.wps-product-gallery-attachments-hidden-id' );

	// ADD IMAGE LINK
	addImgLink.on( 'click', function( event ){

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create a new media frame
		frame = wp.media({
			title: 'Select or Upload Media Of Your Chosen Persuasion',
			button: {
				text: 'Use this media'
			},
			multiple: true  // Set to true to allow multiple files to be selected
		});


		// When an image is selected in the media frame...
		frame.on( 'select', function() {

			// Get media attachment details from the frame state
			var attachments = frame.state().get('selection').map( function ( attachment ) {
				attachment.toJSON();
				return attachment;
				//imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );
				//imgIdInput.val( attachment.id );
			});

			var i;

			for (i = 0; i < attachments.length; ++i) {

				//sample function 1: add image preview
				imgContainer.append(
					'<div class="myplugin-image-preview"><img src="' +
					attachments[i].attributes.url + '" alt="" style="max-width:100%;"></div>'
				);

				//sample function 2: add hidden input for each image
				imgIdInput.after(
					'<input type="hidden" name="myplugin_attachment_id_array[]" value="' +
					attachments[i].id + '" id="myplugin-image-input' + attachments[i].id + '">'
				);
			}

			// Send the attachment URL to our custom image input field.

			// Send the attachment id to our hidden input

			// Hide the add image link
			//addImgLink.addClass( 'hidden' );

			// Unhide the remove image link
			//delImgLink.removeClass( 'hidden' );
		});

		// Finally, open the modal on click
		frame.open();
	});

	// DELETE IMAGE LINK
	delImgLink.on( 'click', function( event ){

		event.preventDefault();

		// Clear out the preview image
		imgContainer.html( '' );

		// Un-hide the add image link
		addImgLink.removeClass( 'hidden' );

		// Hide the delete image link
		delImgLink.addClass( 'hidden' );

		// Delete the image id from the hidden input
		imgIdInput.val( '' );

	});

});

/**
 * Gestion JS des assoications Dolibarr.
 *
 * @since   2.1.0
 * @version 2.1.0
 */
window.eoxiaJS.wpshop.doliDocument = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.1.0
 * @version 2.1.0
 */
window.eoxiaJS.wpshop.doliDocument.init = function() {

};
