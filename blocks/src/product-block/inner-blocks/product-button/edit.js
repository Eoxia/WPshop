import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<button type="button" className="wpeo-button wps-button-add-to-cart" disabled>
				<i className="wps-button icon fas fa-shopping-cart"></i>
				<span className="wps-button-text">{ __( 'Add to cart', 'wpshop' ) }</span>
			</button>
		</div>
	);
}
