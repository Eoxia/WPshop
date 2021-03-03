<?php
/**
 * La vue affichant la metabox de la gallery d'images d'un produit.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
 * @since     2.4.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product                   Les données d'un produit.
 * @var array   $attachments               Le tableau contenant toutes les données des documents.
 * @var array   $attachment                Les données d'un document.
 * @var string  $dolibarr_url              L'url de Dolibarr.
 * @var string  $dolibarr_product_document L'url des documents de Dolibarr.
 * @var string  $upload_link               Le lien vers l'iframe d'ajout d'un média.
 */
?>

<div class="wps-product-gallery-container" data-id="<?php echo esc_attr( $product->data['id'] ) ?>">
	<ul class="wps-product-gallery-attachments">
		<?php if ( ! empty ( $attachments ) ) :
			foreach ( $attachments as $attachment ) :
				$pathinfo = pathinfo( $attachment->relativename );
				if ( $pathinfo['extension'] != 'pdf' ) : ?>
					<li class="wps-product-gallery-attachment">
						<?php $path = $attachment->level1name . '/' . $attachment->name;
						$attachment_data = Request_Util::get( 'documents/download?modulepart=product&original_file=' . $path );
						print "<img width=150 height=150 src=data:image/png;$attachment_data->encoding,$attachment_data->content />"?>
					</li>
				<?php endif; ?>
			<?php endforeach ?>
		<?php endif; ?>
	</ul>

	<p class="hide-if-no-js">
		<?php if ( Settings::g()->dolibarr_is_active() ) : ?>
			<a class="wps-product-gallery-attachment-link" target="_blank" href="<?php echo esc_attr( $dolibarr_url . $dolibarr_product_document . $product->data['external_id'] ) ?>">
				<?php esc_html_e( 'Modify the gallery on Dolibarr', 'wpshop' ) ?>
			</a>
		<?php else : ?>
			<a class="wps-product-gallery-attachment-link" href="<?php echo $upload_link ?>">
				<?php esc_html_e( 'Add an image to the gallery', 'wpshop' ) ?>
			</a>
		<?php endif; ?>
	</p>

	<input class="wps-product-gallery-attachments-hidden-id" name="wps-product-gallery-attachments-hidden-id" type="hidden" value="" />
</div>
