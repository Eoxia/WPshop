<?php
/**
 * La vue affichant les configurations WordPress du produit.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product          Les données d'un produit.
 * @var string  $sync_status      True si on affiche le statut de la synchronisation.
 * @var string  $doli_url         L'url de Dolibarr.
 * @var boolean $has_selected     True si le produit est selectionné.
 * @var array   $tva              Les types de TVA.
 * @var string  $selected         L'attribut HTML "selected".
 * @var array   $similar_products Le tableau  contenant toutes les données des produits similaires.
 * @var Product $similar_product  Les données d'un produit similaire.
 */
?>

<div class="wpeo-wrap">
	<div class="wpeo-form">
		<?php wp_nonce_field( basename( __FILE__ ), 'wpshop_data_fields' ); ?>
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'Similar products', 'wpshop' ); ?></span>
			<label class="form-field-container">
				<select id="monselect" name="similar_products_id[]" class="form-field similar-product" multiple="true">
					<?php
					if ( ! empty( $similar_products ) ) :
						foreach ( $similar_products as $similar_product ) :
							?>
							<option selected="selected" value="<?php echo $similar_product->data['id']; ?>"><?php echo $similar_product->data['title']; ?></option>
						<?php
						endforeach;
					endif;
					?>
				</select>
			</label>
		</div>

	<!--			<div class="form-element">-->
	<!--				<span class="form-label">--><?php //esc_html_e( 'Parent Product', 'wpshop' ); ?><!--</span>-->
	<!--				<label class="form-field-container">-->
	<!--					--><?php
	//					if ( ! empty( $product->data['parent_post'] ) ) :
	//						?>
	<!--						<a href="--><?php //echo esc_attr( admin_url( 'post.php?post=' . $product->data['parent_post']->ID . '&action=edit' ) ); ?><!--">--><?php //echo esc_html( $product->data['parent_post']->post_title ); ?><!--</a>-->
	<!--					--><?php
	//					else :
	//						esc_html_e( 'No product parent', 'wpshop' );
	//					endif;
	//					?>
	<!--				</label>-->
	<!--			</div>-->

	</div>
</div>
