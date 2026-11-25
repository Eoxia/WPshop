/**
 * Formats a date ("2026-11-24 15:54:45" or ISO) in the language of the page: "24 novembre 2026".
 *
 * @param {string} value Date.
 *
 * @return {string} Formatted date, or the value itself if it cannot be parsed.
 */
export function formatDate( value ) {
	const date = new Date( String( value ).replace( ' ', 'T' ) );

	if ( Number.isNaN( date.getTime() ) ) {
		return value;
	}

	return new Intl.DateTimeFormat( document.documentElement.lang || undefined, { day: 'numeric', month: 'long', year: 'numeric' } ).format( date );
}
