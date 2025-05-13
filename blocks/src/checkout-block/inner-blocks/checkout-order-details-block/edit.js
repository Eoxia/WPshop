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

	const products = [
		{
			id: 1,
			title: 'Product 1',
			price_ttc: 5,
			qty: 2,
			thumbnail_id: 'https://picsum.photos/id/256/200/300',
		},
		{
			id: 2,
			title: 'Product 2',
			price_ttc: 20.00,
			qty: 1,
			thumbnail_id: 'https://picsum.photos/id/237/200/300',
		},
	]

	return (
		<>
		<div class="wps-checkout-subtitle wps-checkout-subtitle-step-2">{ __( 'Your order', 'wpshop' ) }</div>

		<div className="wps-cart-resume">

			<div class="wps-checkout-review-order wps-list-product">
				{
					products.map((product) => (
						<div itemscope itemtype="https://schema.org/Product" class="wps-product">
							<figure class="wps-product-thumbnail">
								<img src={product.thumbnail_id} class="attachment-wps-product-thumbnail" itemprop="image" />
							</figure>
							<div class="wps-product-content">
								<div itemprop="name" class="wps-product-title">{product.title}</div>
								<div class="wps-product-footer">
									<div class="wps-product-quantity">{product.qty}</div>
									<div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
										<span itemprop="price" content={product.price_ttc * product.qty}>{product.price_ttc * product.qty}</span>
										<span itemprop="priceCurrency" content="EUR">€</span>
									</div>
								</div>
							</div>
						</div>
					))
				}
			</div>

			<li class="wps-resume-line">
				<span class="wps-line-content">{ __('Subtotal', 'wpshop') }</span>
				<span class="wps-line-value">25 €</span>
			</li>
			<li class="wps-resume-line">
				<span class="wps-line-content">{ __( 'Taxes', 'wpshop' ) }</span>
				<span class="wps-line-value">5 €</span>
			</li>

			<li class="wps-resume-line featured">
				<span class="wps-line-content">{ __( 'Total TTC', 'wpshop' ) }</span>
				<span class="wps-line-value">30 €</span>
			</li>
		</div>
		</>
	);
}
