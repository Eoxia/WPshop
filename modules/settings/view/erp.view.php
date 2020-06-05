<?php
/**
 * La vue principale de la page de réglages
 *
 * @author    Eoxia <dev@eoxia.com>
 *
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

<form class="wpeo-form wpeo-grid grid-2 grid-padding-1" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo esc_attr( 'wps_update_erp_settings' ); ?>" />
	<input type="hidden" name="tab" value="erp" />
	<?php wp_nonce_field( 'callback_update_erp_settings' ); ?>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr URL', 'wpshop' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_url" value="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label">
			<span><?php esc_html_e( 'Dolibarr Secret Key', 'wpshop' ); ?></span>
			<span class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Secret key used for sell with Dolibarr', 'wpshop' ); ?>">?</span>
			<?php
			if (Settings::g()->dolibarr_is_active()):
				?>
				<span class="wpeo-button button-light button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Connected to Dolibarr', 'wpshop' ); ?>">✔</span>
			<?php
			else:
				?>
				<span class="wpeo-button button-light button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Connection to dolibarr failed', 'wpshop' ); ?>">❌</span>
			<?php
			endif;
			?>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_secret" value="<?php echo esc_attr( $dolibarr_option['dolibarr_secret'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label">
			<span><?php esc_html_e( 'Dolibarr Public Key (Optional)', 'wpshop' ); ?></span>
			<span class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Public key used for your theme', 'wpshop' ); ?>">?</span>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_public_key" value="<?php echo esc_attr( $dolibarr_option['dolibarr_public_key'] ); ?>" />
		</label>
	</div>

	<!-- Toutes les box de réglage des liens vers l'ERP -->
	<!-- liens vers les Tiers -->


	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Create Tier', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_create_tier'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_create_tier" value="<?php echo esc_attr( $dolibarr_option['dolibarr_create_tier'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Tiers Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_tiers_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_tiers_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_tiers_lists'] ); ?>" />
		</label>
	</div>
	<!-- Produits | Services -->
	<!-- -Liens Produits -->

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Create Product', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_create_product'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_create_product" value="<?php echo esc_attr( $dolibarr_option['dolibarr_create_product'] ); ?>" />
		</label>
	</div>


	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Products Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_products_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_products_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_products_lists'] ); ?>" />
		</label>
	</div>

	<!-- -Liens Service -->
	<!-- -Liens Service @todo -->

	<!-- -Liens Entrepôts -->
	<!-- -Liens Entrepôts @todo -->

	<!-- -Liens Projet -->
	<!-- -Liens Projet @todo -->

	<!-- -Liens Commerce -->
	<!-- -Liens Propositions commerciales -->

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Create Proposal', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_create_proposal'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_create_proposal" value="<?php echo esc_attr( $dolibarr_option['dolibarr_create_proposal'] ); ?>" />
		</label>
	</div>


	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Proposals Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_proposals_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_proposals_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_proposals_lists'] ); ?>" />
		</label>
	</div>

	<!-- -Liens Commandes -->

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Create Order', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_create_order'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_create_order" value="<?php echo esc_attr( $dolibarr_option['dolibarr_create_order'] ); ?>" />
		</label>
	</div>

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Orders Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_orders_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_orders_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_orders_lists'] ); ?>" />
		</label>
	</div>

	<!-- -Liens Facturation | Paiement -->
	<!-- -Liens Factures clients -->
	<!-- -Liens dolibarr_create_invoices @todo -->

	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Invoices Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_invoices_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_invoices_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_invoices_lists'] ); ?>" />
		</label>
	</div>


	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Dolibarr Payments Lists', 'wpshop' ); ?>
			<a class="wpeo-button button-square-40 button-rounded wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Test the link', 'wpshop' ); ?>" href="<?php echo esc_attr( $dolibarr_option['dolibarr_url'] . $dolibarr_option['dolibarr_payments_lists'] ); ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
		</span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="dolibarr_payments_lists" value="<?php echo esc_attr( $dolibarr_option['dolibarr_payments_lists'] ); ?>" />
		</label>
	</div>

	<div>
		<input type="submit" class="wpeo-button button-main" value="<?php esc_html_e( 'Save Changes', 'wpshop' ); ?>" />
	</div>
</form>

