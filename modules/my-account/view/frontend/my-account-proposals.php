<?php
/**
 * La vue affichant la liste d'envies.
 *
 * @todo a revoir totalement
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wps-list-quotation wps-list-box">
	<?php
	if ( ! empty( $proposals ) ) :
		foreach ( $proposals as $proposal ) :
			?>
			<div class="wps-order wps-box">
				<div class="wps-box-resume">
					<div class="wps-box-primary">
						<div class="wps-box-title"><?php echo esc_html( $proposal->data['datec']['rendered']['mysql'] ); ?></div>
						<ul class="wps-box-attributes">
							<li class="wps-box-subtitle-item"><i class="wps-box-subtitle-icon fas fa-shopping-cart"></i> <?php echo esc_attr( $proposal->data['title'] ); ?></li>
						</ul>
						<div class="wps-box-display-more">
							<i class="wps-box-display-more-icon fas fa-angle-right"></i>
							<span class="wps-box-display-more-text"><?php esc_html_e( 'View details', 'wpshop' ); ?></span>
						</div>
					</div>
<!--					<div class="wps-box-secondary">-->
<!--						<div class="wps-box-price">--><?php //echo esc_html( number_format( $proposal->data['total_ttc'], 2, ',', '' ) ); ?><!--€</div>-->
<!--					</div>-->
					<div class="wps-box-action">
						<?php do_action( 'wps_my_account_proposals_actions', $proposal ); ?>
					</div>
				</div>

				<div class="wps-box-detail wps-list-product">
					<?php
					if ( ! empty( $proposal->data['lines'] ) ) :
						foreach ( $proposal->data['lines'] as $line ) :
							$qty                  = $line['qty'];

							if ( ! empty( $line['fk_product'] ) ) {
								$data = array(
									'meta_key'   => '_external_id',
									'meta_value' => (int) $line['fk_product'],
								);
							} else {
								$data = array( 'id' => $line['id'] );
							}

							$product              = Product::g()->get( $data, true );
							$product->data['qty'] = $qty;
							$product              = $product->data;
							$product['price_ttc'] = ( $line['total_ttc'] / $qty );

							include( Template_Util::get_template_part( 'products', 'wps-product-list' ) );
						endforeach;
					else :
						esc_html_e( 'No products to display', 'wpshop' );
					endif;
					?>
				</div>
			</div>
			<?php
		endforeach;
	else :
		?>
		<div class="wpeo-notice notice-info">
			<div class="notice-content">
				<div class="notice-title"><?php esc_html_e( 'No wishes', 'wpshop' ); ?></div>
			</div>
		</div>

<!--	<form class="test">-->
<!--		<div class="form-element">-->
<!--			<span class="form-label">--><?php //esc_html_e( 'List name', 'wpshop' ); ?><!--</span>-->
<!--			<label class="form-field-container">-->
<!--				<input type="text" class="form-field" name="name_wishlist"/>-->
<!--			</label>-->
<!--		</div>-->
<!---->
<!--		<div class="wps-wishlist-action">-->
<!--			<div class="wps-wishlist wpeo-button action-input"-->
<!--				 data-parent="test"-->
<!--				 data-action="add_wishlist"-->
<!--				 data-nonce="--><?php //echo wp_create_nonce( 'add_wishlist' ); ?><!--">-->
<!--				<i class="fas fa-plus"></i>-->
<!--			</div>-->
<!--		</div>-->
<!--	</form>-->

<!--	--><?php //Wishlist::g()->display(); ?>
<!---->
<!--		<div class="wps-wishlist-action">-->
<!--			<div class="wps-wishlist wpeo-button wpeo-modal-event"-->
<!--				 data-action="load_add_wishlist"-->
<!--				 data-nonce="--><?php //echo wp_create_nonce( 'load_add_wishlist' ); ?><!--">-->
<!--				<i class="fas fa-plus"></i>-->
<!--			</div>-->
<!--		</div>-->
		<?php
	endif;
	?>
</div>
