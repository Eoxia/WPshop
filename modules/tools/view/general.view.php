<?php
/**
 * La vue affichant la page général dans les outils.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;  ?>


<div class="wpeo-gridlayout grid-2">
	<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" class="import-third-party" >
		<h3 style="text-align: center;"><?php esc_html_e( 'Import third party CSV', 'wpshop' ); ?></h3>
		<input type="hidden" name="action" value="import_third_party" />
		<?php wp_nonce_field( 'import_third_party' ); ?>

		<div>
			<progress style="padding: 15px; width: 100%" value="0" max="100">0%</progress>
		</div>

		<div style="text-align: right;">
			<input type="file" name="file" id="file" />
			<label class="wpeo-button button-primary" ><?php esc_html_e( 'Import', 'wpshop' ); ?></label><br />
			<span class="import-detail"></span>
		</div>
	</form>

	<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" class="import-product" >
		<h3 style="text-align: center;"><?php esc_html_e( 'Import Product CSV', 'wpshop' ); ?></h3>
		<input type="hidden" name="action" value="import_product" />
		<?php wp_nonce_field( 'import_product' ); ?>

		<div>
			<progress style="padding: 15px; width: 100%" value="0" max="100">0%</progress>
		</div>

		<div style="text-align: right;">
			<input type="file" name="file" id="file" />
			<label class="wpeo-button button-primary" ><?php esc_html_e( 'Import', 'wpshop' ); ?></label><br />
			<span class="import-detail"></span>
		</div>
	</form>
</div>
