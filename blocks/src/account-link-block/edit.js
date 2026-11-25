import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

import { USER_ICON_PATH } from './icon';

export default function Edit( { attributes, setAttributes } ) {
	const { showLabel } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'wpshop' ) }>
					<ToggleControl
						__nextHasNoMarginBottom
						label={ __( 'Show the label', 'wpshop' ) }
						checked={ showLabel }
						onChange={ ( value ) => setAttributes( { showLabel: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<span className="wps-account-link__link">
					<svg className="wps-account-link__icon" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
						<path d={ USER_ICON_PATH } fill="currentColor" />
					</svg>
					{ showLabel && <span className="wps-account-link__label">{ __( 'My account', 'wpshop' ) }</span> }
				</span>
			</div>
		</>
	);
}
