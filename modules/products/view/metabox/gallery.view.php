<?php
/**
 * La vue principale de la page des produits.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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

<!--<div class="test">-->
<!--		<input type="hidden" id="woocommerce_meta_nonce" name="woocommerce_meta_nonce" value="88f690e738"><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=44&amp;action=edit">-->
<!--		<div id="product_images_container">-->
<!--			<ul class="product_images ui-sortable">-->
<!--				--><?php //if ( ! empty ( $attachments ) ) :
//					foreach ( $attachments as $attachment ) : ?>
<!--					--><?php ////echo '<pre>'; print_r($attachment); echo '</pre>'; exit; ?>
<!--					<li class="image" data-attachment_id="--><?php //echo esc_attr( $attachment['ID'] ) ?><!--" >-->
<!--						<img width="150" height="150" src="--><?php //echo esc_attr( $wp_upload_dir['baseurl'] . '/' . $attachment['attached_file'] ) ?><!--" class="attachment-thumbnail size-thumbnail">-->
<!--					</li>-->
<!--					--><?php //endforeach ?>
<!--				--><?php //endif; ?>
<!--			</ul>-->
<!--		</div>-->
<!--	<p class="add_product_images hide-if-no-js">-->
<!--		<a href="http://localhost/wordpress/wp-admin/media-upload.php?post_id=236&type=image&TB_iframe=1" id="set-post-thumbnail" class="thickbox">Test</a>-->
<!--	</p>-->
<!--</div>-->

<?php
global $post;

// Get WordPress' media upload URL
$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

// See if there's a media id already saved as post meta
$your_img_id = get_post_meta( $post->ID, '_thumbnail_id', true );

// Get the image src
$your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

// For convenience, see if the array is valid
$you_have_img = is_array( $your_img_src );
?>

<!-- Your image container, which can be manipulated with js -->
<div class="custom-img-container">
	<ul class="product_images ui-sortable">-->
		<?php if ( ! empty ( $attachments ) ) :
			foreach ( $attachments as $attachment ) : ?>
				<?php //echo '<pre>'; print_r($attachment); echo '</pre>'; exit; ?>
				<li class="image" data-attachment_id="<?php echo esc_attr( $attachment['ID'] ) ?>" >
					<img width="150" height="150" src="<?php echo esc_attr( $wp_upload_dir['baseurl'] . '/' . $attachment['attached_file'] ) ?>" class="attachment-thumbnail size-thumbnail">
				</li>
			<?php endforeach ?>
		<?php endif; ?>
	</ul>
	<?php if ( $you_have_img ) : ?>
		<img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
	<?php endif; ?>
</div>

<!-- Your add & remove image links -->
<p class="hide-if-no-js">
	<a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>"
	   href="<?php echo $upload_link ?>">
		<?php _e('Set custom image') ?>
	</a>
	<a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>"
	   href="#">
		<?php _e('Remove this image') ?>
	</a>
</p>

<!-- A hidden input to set and post the chosen image id -->
<input class="custom-img-id" name="custom-img-id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
