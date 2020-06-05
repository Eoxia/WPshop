/**
 * Gestion JS du tunnel de vente.
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.stripe = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework
 *
 * @since 2.0.0
 */
window.eoxiaJS.wpshopFrontend.stripe.init = function() {};

window.eoxiaJS.wpshopFrontend.stripe.redirectToPayment = function(triggeredElement, response) {
	var stripe = Stripe(
	  stripe_key,
	  {
	    betas: ['checkout_beta_4'],
	  }
	);

	stripe.redirectToCheckout({
 		sessionId: response.data.id
	}).then(function (result) {
	  console.log(result);
	});
}
