/**
 * Gestion JS de la méthode de paiement Stripe.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.stripe = {};

/**
 * La méthode "init" est appelé automatiquement par la lib JS de Eo-Framework.
 *
 * @since   2.0.0
 * @version 2.0.0
 */
window.eoxiaJS.wpshopFrontend.stripe.init = function() {};

/**
 * Le callback en cas de réussite à la requête Ajax "redirect_to_payment".
 * Redirection sur la page de paiement Stripe.
 *
 * @since   2.0.0
 * @version 2.0.0
 *
 * @param {HTMLDivElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param {Object}         response         Les données renvoyées par la requête Ajax.
 */
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
};
