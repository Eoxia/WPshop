import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

import './scss/editor.scss';

/**
 * Product image: option to link the image to the product (enabled by default).
 *
 * @param {Object}   props               Block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attributes setter.
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { isLink } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'wpshop' ) }>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Link to the product', 'wpshop' ) }
						help={ __( 'Clicking the image opens the product page (not on the product page itself).', 'wpshop' ) }
						checked={ !! isLink }
						onChange={ ( value ) => setAttributes( { isLink: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="wps-product-image">
					<div className="wps-product-image-container">
						<img src="https://picsum.photos/id/23/200/300" alt={ __( 'Product image', 'wpshop' ) } className="wps-product-image-src" />
					</div>
				</div>
			</div>
		</>
	);
}
