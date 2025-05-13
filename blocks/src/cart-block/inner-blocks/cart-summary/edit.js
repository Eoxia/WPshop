/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './scss/editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {

	const products = {
		total: 40,
		taxes: 8,
		total_ttc: 48
	}

	return (
		<ul class="wps-cart-resume">
			<li class="wps-resume-line">
				<span class="wps-line-content">{ __( 'Subtotal', 'wpshop' ) }</span>
				<span class="wps-line-value">{ products.total.toFixed(2) }€</span>
			</li>
			<li class="wps-resume-line">
				<span class="wps-line-content">{ __( 'Taxes', 'wpshop' ) }</span>
				<span class="wps-line-value">{ products.taxes.toFixed(2) }€</span>
			</li>

			<li class="wps-resume-line featured">
				<span class="wps-line-content">{ __( 'Total TTC', 'wpshop' ) }</span>
				<span class="wps-line-value">{ products.total_ttc.toFixed(2) }€</span>
			</li>

		</ul>
	);
}
