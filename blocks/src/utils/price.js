/**
 * Formats an amount like the PHP helper wpshop\format_price(): "34,90 €",
 * followed by an optional suffix ("HT", "TTC").
 *
 * @param {number|string} amount Amount.
 * @param {string}        suffix Optional text after the currency.
 *
 * @return {string} Formatted amount.
 */
export function formatPrice( amount, suffix = '' ) {
	const value     = Number.parseFloat( amount );
	const formatted = ( Number.isFinite( value ) ? value : 0 ).toFixed( 2 ).replace( '.', ',' ) + ' €';

	return suffix ? formatted + ' ' + suffix : formatted;
}

/**
 * Formats an amount without the currency ("34,90"), for markup where the currency is a
 * separate element (schema.org price / priceCurrency).
 *
 * @param {number|string} amount Amount.
 *
 * @return {string} Formatted amount.
 */
export function formatAmount( amount ) {
	const value = Number.parseFloat( amount );

	return ( Number.isFinite( value ) ? value : 0 ).toFixed( 2 ).replace( '.', ',' );
}
