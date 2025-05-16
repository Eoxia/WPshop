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

	const orders = [
		{
			data: {
				datec: '2023-10-01 00:00:00',
				title: 'CO2504-0042',
				status: {
					class: {
						text: 'Validated - Facturée'
					}
				},
				total_ttc: 100,
				download: '#',
				lines: [
					{
						thumbnail: '<img src="https://picsum.photos/id/256/200/300" alt="Product Thumbnail" class="attachment-wps-product-thumbnail">',
						libelle: 'Product 1',
						subprice: 50,
						qty: 2
					}
				]
			}
		},
	]

	const openedOrder = [0];

	return (
        <div className="wp-block-wpshop-account-orders">
            <div class="wps-list-order wps-list-box">
                {
                    orders.map( ( order, index ) => (
                        <div class="wps-order wps-box" key={ index }>
                            <div class="wps-box-resume">
                                <div class="wps-box-primary">
                                    <div class="wps-box-title">{ order.data.datec }</div>
                                    <ul class="wps-box-attributes">
                                        <li class="wps-box-subtitle-item"><i class="wps-box-subtitle-icon fas fa-shopping-cart"></i> { order.data.title }</li>
                                    </ul>
                                    <div class={`${openedOrder.includes( index ) ? 'active wps-box-display-more' : 'wps-box-display-more'}`} onClick={ () => openOrder( index ) }>
                                        <i class="wps-box-display-more-icon fas fa-angle-right"></i>
                                        <span class="wps-box-display-more-text"> { __( 'View details', 'wpshop' ) }</span>
                                    </div>
                                </div>
                                <div class="wps-box-secondary">
                                    <div class="wps-box-status"><span class="wps-box-status-dot"></span> { order.data.status.class.text }</div>
                                    <div class="wps-box-price">{ parseFloat(order.data.total_ttc).toFixed(2) }€</div>
                                </div>
                                <div class="wps-box-action">
                                    <a target="_blank"
                                        href={ order.data.download }
                                        class="wpeo-button button-primary button-square-50 button-rounded">
                                        <i class="button-icon fas fa-file-download"></i>
                                    </a>
                                </div>
                            </div>

                            <div class={`${openedOrder.includes( index ) ? 'wps-list-product' : 'wps-box-detail wps-list-product'}`}>
                                {order.data.lines.map((product) => (
                                    <div itemscope itemtype="https://schema.org/Product" class="wps-product">
                                        <figure class="wps-product-thumbnail" dangerouslySetInnerHTML={{ __html: product.thumbnail }} itemprop="image">
                                        </figure>
                                    
                                        <div class="wps-product-content">
                                            <div itemprop="name" class="wps-product-title">{ product.libelle }</div>
                                            <ul class="wps-product-attributes">
                                                <li class="wps-product-attributes-item">{ __( 'Unit price:', 'wpshop' ) } {product.subprice} €</li>
                                            </ul>
                                            <div class="wps-product-footer">
                                                <div class="wps-product-quantity">
                                                    <span class="qty">{product.qty}</span>
                                                </div>
                    
                                                {product.subprice && (
                                                    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer" class="wps-product-price">
                                                        <span itemprop="price">{(product.subprice * product.qty).toFixed(2)}</span>
                                                        <span itemprop="priceCurrency" content="EUR">€</span>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ) )
                }
            </div>
        </div>    
    );
}
