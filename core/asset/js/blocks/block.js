/**
 * BLOCK: wpshop-block-products
 *
 * Products block.
 */

//  Import CSS.
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Register: Products block
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'wpshop/block-products', {
	title: __( 'Products' ),
	icon: 'screenoptions',
	category: 'common',
	keywords: [
		__( 'product' ),
	],
	attributes: {
		items: {
			type: 'array'
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( { attributes, setAttributes, className } ) => {
		if ( ! attributes.items ) {
			let fetchObject = {
				credentials: 'same-origin',
				method: 'GET',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				}
			};

			fetch( wpshop.homeUrl + '/wp-json/wpshop/v1/product' )
				.then( response => {
					return response.json().then( json => {
						if ( json.code == "rest_forbidden" ) {
							setAttributes( { err: json.message } );
						} else {
							setAttributes( { items: json } );
						}
					} );
				} );
		}

		if ( attributes.err ) {
			return <p>{ attributes.err }</p>;
		}

		if ( ! attributes.items ) {
			return <div>Loading...</div>;
		}

		return (
			<div className="wps-product-grid wpeo-gridlayout grid-4">
				{attributes.items.map((item, key) => (
					<div itemScope itemType="https://schema.org/Product" className="wps-product">
						<figure className="wps-product-thumbnail">
							<img width="360" height="460" src={item.data.thumbnail_url} alt="Image"
								 className="attachment-wps-product-thumbnail wp-post-image" itemProp="image" />

							<div className="wps-product-action">
								<a itemProp="url" href={item.data.link}
								   className="wpeo-button button-square-40 button-rounded button-light">
									<i className="button-icon fas fa-eye"></i>
								</a>

								<div
									className="wps-product-buy wpeo-button button-square-40 button-rounded button-light action-attribute"
									data-action="add_to_cart" data-nonce={wpshop.addToCartNonce}
									data-id={item.data.id}>
									<i className="button-icon fas fa-cart-arrow-down"></i>
								</div>
							</div>
						</figure>

						<div className="wps-product-content">
							<div itemProp="name" className="wps-product-title">{ item.data.title }</div>
							<div itemProp="offers" itemScope="" itemType="https://schema.org/Offer"
								 className="wps-product-price">
								<span itemProp="price" content={ item.data.price_ttc }>{ item.data.price_ttc }</span>
								<span itemProp="priceCurrency" content="EUR">€</span>
							</div>
						</div>
					</div>
				))}
			</div>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: ( { attributes } ) => {
		if ( ! attributes.items ) {
			return <div>Loading...</div>;
		}

		return (
			<div className="wps-product-grid wpeo-gridlayout grid-4">
				{attributes.items.map( ( item, key ) => (
					<div itemscope itemtype="https://schema.org/Product" className="wps-product">
						<figure className="wps-product-thumbnail">
							<img width="360" height="460" src={ item.data.thumbnail_url } alt="Image" className="attachment-wps-product-thumbnail wp-post-image" itemprop="image" />

							<div class="wps-product-action">
								<a itemprop="url" href={ item.data.link } className="wpeo-button button-square-40 button-rounded button-light">
									<i className="button-icon fas fa-eye"></i>
								</a>

								<div
									className="wps-product-buy wpeo-button button-square-40 button-rounded button-light action-attribute"
									data-action="add_to_cart" data-nonce={ wpshop.addToCartNonce } data-id={ item.data.id }>
									<i className="button-icon fas fa-cart-arrow-down"></i>
								</div>
							</div>
						</figure>

						<div className="wps-product-content">
							<div itemProp="name" className="wps-product-title">{ item.data.title }</div>
							<div itemProp="offers" itemScope="" itemType="https://schema.org/Offer"
								 className="wps-product-price">
								<span itemProp="price" content={ item.data.price_ttc }>{ item.data.price_ttc }</span>
								<span itemProp="priceCurrency" content="EUR">€</span>
							</div>
						</div>
					</div>
				))}
			</div>
		);
	},
} );
