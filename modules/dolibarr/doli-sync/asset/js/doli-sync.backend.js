/**
 * Gestion JS des synchronisations.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliSync = {};
window.eoxiaJS.wpshop.doliSync.completed = false;

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliSync.init = function() {
	jQuery( document ).on( 'modal-opened', '.modal-sync', function() {
		if ( 0 < jQuery( '.waiting-item' ).length ) {
			window.eoxiaJS.wpshop.doliSync.declareUpdateForm();
			window.eoxiaJS.wpshop.doliSync.requestUpdate();
			window.addEventListener( 'beforeunload', window.eoxiaJS.wpshop.doliSync.safeExit );
		}
	});
};

/**
 * Déclare les formulaires pour les mises à jour et leur fonctionnement.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliSync.declareUpdateForm = function() {
	jQuery( '.item' ).find( 'form' ).ajaxForm({
		dataType: 'json',
		success: function( responseText, statusText, xhr, $form ) {
			if ( ! responseText.data.updateComplete ) {
				$form.find( '.item-stats' ).html( responseText.data.progression );
				$form.find( 'input[name="done_number"]' ).val( responseText.data.doneElementNumber );
				$form.find( '.item-progression' ).css( 'width', responseText.data.progressionPerCent + '%' );

				if ( responseText.data.done ) {
					$form.closest( '.item' ).removeClass( 'waiting-item' );
					$form.closest( '.item' ).removeClass( 'in-progress-item' );
					$form.closest( '.item' ).addClass( 'done-item' );
					$form.find( '.item-stats' ).html( responseText.data.doneDescription );
				}
			} else {
				if ( ! window.eoxiaJS.wpshop.doliSync.completed ) {
					$form.find( '.item-stats' ).html( responseText.data.progression );
					$form.find( 'input[name="done_number"]' ).val( responseText.data.doneElementNumber );
					$form.find( '.item-progression' ).css( 'width', responseText.data.progressionPerCent + '%' );

					if ( responseText.data.done ) {
						$form.closest( '.item' ).removeClass( 'waiting-item' );
						$form.closest( '.item' ).removeClass( 'in-progress-item' );
						$form.closest( '.item' ).addClass( 'done-item' );
						$form.find( '.item-stats' ).html( responseText.data.doneDescription );
					}

					window.eoxiaJS.wpshop.doliSync.completed = true;
					jQuery( '.general-message' ).html( responseText.data.doneDescription );
					window.removeEventListener( 'beforeunload', window.eoxiaJS.wpshop.doliSync.safeExit );

					jQuery( '.wpeo-modal.modal-active' ).removeClass( 'modal-force-display' );
				}
			}

			window.eoxiaJS.wpshop.doliSync.requestUpdate();
		}
	});
};

/**
 * Lancement du processus de mixe à jour: On prend le premier formulaire ayant la classe 'waiting-item'
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshop.doliSync.requestUpdate = function() {
	if ( ! window.eoxiaJS.wpshop.doliSync.completed ) {
		var currentUpdateItemID = '#' + jQuery( '.waiting-item:first' ).attr( 'id' );

		jQuery( currentUpdateItemID ).addClass( 'in-progress-item' );
		jQuery( currentUpdateItemID ).find( 'form' ).submit();

	}
};

/**
 * Vérification avant la fermeture de la page si la mise à jour est terminée.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param  {WindowEventHandlers} event L'évènement de la fenêtre.
 * @return {string}
 */
window.eoxiaJS.wpshop.doliSync.safeExit = function( event ) {
	var confirmationMessage = taskManager.wpshopconfirmExit;
	if ( taskManager.wpshopUrlPage === event.currentTarget.adminpage ) {
		event.returnValue = confirmationMessage;
		return confirmationMessage;
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "sync_entry".
 * Synchronise une entrée.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
window.eoxiaJS.wpshop.doliSync.syncEntrySuccess = function( triggeredElement, response ) {
	var modal = jQuery( '.wpeo-modal.modal-active' );

	// If it is associate action.

	if ( modal.length > 0 ) {
		modal.addClass( 'modal-force-display' );
		modal.find( '.modal-content' ).html( response.data.modal_view );
		modal.find( '.modal-footer' ).html( response.data.modal_footer );
	} else if ( jQuery( triggeredElement ).closest( '.table-row' ).length > 0 ) {
		// If it is sync action per entry.
		jQuery( triggeredElement ).closest( '.table-row' ).replaceWith( response.data.item_view );
	}
	location.reload(); // reloading page
};
