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
		products: [
			{
				id: 1,
				title: 'Product 1',
				price_ttc: 10.00,
				qty: 1,
				thumbnail_url: 'https://picsum.photos/id/256/200/300'
			},
			{
				id: 2,
				title: 'Product 2',
				price_ttc: 20.00,
				qty: 2,
				thumbnail_url: 'https://picsum.photos/id/237/200/300'
			}
		]
	};

	return (
		<div className="wps-list-product gridw-2">
            {products.products?.map((product) => (
                <div itemscope itemtype="https://schema.org/Product" class="wps-product">
                    <a class="wps-delete-product">

                        <i class="wps-delete-product-icon fas fa-times-circle"></i>
                    </a>
                
                    <figure class="wps-product-thumbnail">
                        <img src={product.thumbnail_url} class="attachment-wps-product-thumbnail" itemprop="image" />
                    </figure>
                
                    <div class="wps-product-content">
                        <div itemprop="name" class="wps-product-title">{ product.title }</div>
                        <ul class="wps-product-attributes">
                            <li class="wps-product-attributes-item">{ __( 'Unit price:', 'wpshop' ) } {product.price_ttc} €</li>
                        </ul>
                        <div class="wps-product-footer">
                            <div class="wps-product-quantity" style={{ fontSize: '1.4em' }}>
                                <span class="wps-quantity-minus fas fa-minus-circle"></span>
                                <span class="qty">{product.qty}</span>
                                <span class="wps-quantity-plus fas fa-plus-circle"></span>
                            </div>

							<div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
								<span itemprop="price">{(product.price_ttc * product.qty).toFixed(2)}</span>
								<span itemprop="priceCurrency" content="EUR">€</span>
							</div>
                        </div>
                    </div>
                </div>
            ))}
        </div>
	);
}
