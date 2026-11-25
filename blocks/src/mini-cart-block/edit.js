import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';

import { CART_ICON_PATH } from './icon';
import { formatPrice } from '../utils/price';

export default function Edit( { attributes, setAttributes } ) {
	const { showTotal, openOnAdd, side } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'wpshop' ) }>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Show the cart total', 'wpshop' ) }
						checked={ showTotal }
						onChange={ ( value ) => setAttributes( { showTotal: value } ) }
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Open the panel when a product is added', 'wpshop' ) }
						checked={ openOnAdd }
						onChange={ ( value ) => setAttributes( { openOnAdd: value } ) }
					/>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Panel side', 'wpshop' ) }
						value={ side }
						options={ [
							{ label: __( 'Right', 'wpshop' ), value: 'right' },
							{ label: __( 'Left', 'wpshop' ), value: 'left' },
						] }
						onChange={ ( value ) => setAttributes( { side: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<button type="button" className="wps-mini-cart__toggle" disabled>
					<svg className="wps-mini-cart__icon" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
						<path d={ CART_ICON_PATH } fill="currentColor" />
					</svg>
					<span className="wps-mini-cart__count">2</span>
					{ showTotal && <span className="wps-mini-cart__total">{ formatPrice( 69.8 ) }</span> }
				</button>
			</div>
		</>
	);
}
