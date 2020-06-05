<?php
/**
 * Affichage du lien pour télécharger le devis en format PDF.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

?>

<a target="_blank" href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_download_proposal&_wpnonce=' . wp_create_nonce( 'download_proposal' ) . '&proposal_id=' . $proposal->data['external_id'] ) ); ?>"
	class="wpeo-button button-primary button-square-50 button-rounded">
	<i class="button-icon fas fa-file-download"></i>
</a>
