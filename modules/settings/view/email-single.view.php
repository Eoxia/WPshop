<?php
/**
 * La vue principale d'un single d'un email
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

defined( 'ABSPATH' ) || exit; ?>

<h3><a href="<?php echo admin_url( 'admin.php?page=wps-settings&tab=emails' ); ?>">Emails</a> -> <?php echo $email['title']; ?></h3>

<p>
	<?php
	if ( $is_override ) :
		?>
		Déjà override
		<?php
	else :
		?>
		Pour écraser et modifier ce modèle d’e-mail, copiez wpshop/modules/emails/view/<?php echo $email['filename_template']; ?> dans le dossier de votre thème : <?php echo get_template(); ?>/wpshop/emails/<?php echo $email['filename_template']; ?>.
		<?php
	endif;
	?>
</p>

<a href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_copy_email_template&_wpnonce=' . wp_create_nonce( 'callback_copy_email_template' ) . '&section=' . $section ) ); ?>" class="wpeo-button button-light">
	<span>Copié le modèle</span>
</a>

<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="wpeo-form">
	<input type="hidden" name="action" value="wps_update_email" />
	<input type="hidden" name="tab" value="emails" />
	<input type="hidden" name="section" value="<?php echo $section; ?>" />

	<div class="form-element">
		<textarea name="content" cols="25" <?php echo ! $is_override ? 'readonly="readonly"' : ''; ?> rows="20" class="form-field"><?php echo $content; ?></textarea>
	</div>

	<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
</div>
