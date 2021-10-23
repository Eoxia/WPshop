<?php
/**
 * La vue affichant la metabox des documents d'un produit.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Product $product                   Les données d'un produit.
 * @var array   $attachments               Le tableau contenant toutes les données des documents.
 * @var array   $attachment                Les données d'un document.
 * @var array   $wp_upload_dir             Le tableau contenant les données du répertoire des médias.
 * @var string  $dolibarr_url              L'url de Dolibarr.
 * @var string  $dolibarr_product_document L'url des documents de Dolibarr.
 * @var string  $upload_link               Le lien vers l'iframe d'ajout d'un média.
 */
?>

<div class="wps-product-document-container" data-id="<?php echo esc_attr( $product->data['id'] ) ?>">
	<ul class="wps-product-document-attachments">
		<?php if ( ! empty ( $attachments ) ) :
			foreach ( $attachments as $attachment ) :
				$pathinfo = pathinfo( $attachment->relativename );
				if ( $pathinfo['extension'] == 'pdf' ) :
 					$path = $attachment->level1name . '/' . $attachment->name;
					$attachment_data = Request_Util::get( 'documents/download?modulepart=product&original_file=' . $path );
					$bin = base64_decode($attachment_data->content, true);
					$dirname = $wp_upload_dir['basedir'] . '/wpshop/documents/produit/';
					if ( ! is_dir( $dirname ) ) {
						mkdir( $dirname, 0777, true);
					}
					$filename = $dirname . $attachment->name;
					if ( ! file_exists( $filename ) ) :
						file_put_contents( $filename, $bin );
					endif; ?>
					<li>
						<a class="wps-product-document-attachment" href="<?php echo esc_attr( $wp_upload_dir['baseurl'] . '/wpshop/documents/produit/' . $attachment->name ) ?>" target="_blank">
							<?php echo esc_attr( $attachment->name ); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach ?>
		<?php endif; ?>
	</ul>

	<p class="hide-if-no-js">
		<?php if ( Settings::g()->dolibarr_is_active() ) : ?>
			<a class="wps-product-gallery-attachment-link" target="_blank" href="<?php echo esc_attr( $dolibarr_url . $dolibarr_product_document . $product->data['external_id'] ) ?>">
				<?php esc_html_e( 'Modify documents on Dolibarr', 'wpshop' ) ?>
			</a>
		<?php else : ?>
			<a class="wps-product-gallery-attachment-link" href="<?php echo $upload_link ?>">
				<?php esc_html_e( 'Add a file to the document', 'wpshop' ) ?>
			</a>
		<?php endif; ?>
	</p>

	<input class="wps-product-document-attachments-hidden-id" name="wps-product-document-attachments-hidden-id" type="hidden" value="" />
</div>
