<?php
/**
 * La vue affichant la metabox des documents d'un produit.
 *
 * @package   WPshop
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <dev@eoxia.com>.
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
			foreach ( $attachments as $attachment ) : ?>
				<a class="wps-product-document-attachment" data-attachment-id="<?php echo esc_attr( $attachment['ID'] ) ?>" href="<?php echo esc_attr( $wp_upload_dir['baseurl'] . '/' . $attachment['attached_file'] ) ?>" target="_blank">
					<?php echo esc_attr( $attachment['post_name'] ); ?>
				</a>
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
