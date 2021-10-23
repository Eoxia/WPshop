<?php
/**
 * La vue affichant le lien pour télécharger la proposition commerciale en format PDF.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Proposals $proposal Les données d'une proposition commerciale.
 */
?>
<a target="_blank" href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_download_proposal&_wpnonce=' . wp_create_nonce( 'download_proposal' ) . '&proposal_id=' . $proposal->data['external_id'] ) ); ?>"
	class="wpeo-button button-primary button-square-50 button-rounded">
	<i class="button-icon fas fa-file-download"></i>
</a>
