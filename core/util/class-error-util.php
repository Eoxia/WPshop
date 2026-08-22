<?php
/**
 * Utilitaires pour la gestion des erreurs du plugin WPshop.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Classe Error_Util
 * Gère le dictionnaire des erreurs et l'intégration avec WP_Error.
 */
class Error_Util {

	/**
	 * Retourne le message d'erreur traduit avec son code.
	 *
	 * @param string $code Le code de l'erreur (ex: WPS-ERP-001).
	 * @return string Le message traduit.
	 */
	public static function get( $code ) {
		$messages = array(
			// Erreurs ERP
			'WPS-ERP-001' => __( '[WPS-ERP-001] Connection to Dolibarr failed. Please check your settings.', 'wpshop' ),
			'WPS-ERP-002' => __( '[WPS-ERP-002] Your ERP is not connected or the configuration is incomplete.', 'wpshop' ),
			'WPS-ERP-003' => __( '[WPS-ERP-003] Dolibarr API responded with an error: %s', 'wpshop' ),

			// Erreurs Auth
			'WPS-AUTH-001' => __( '[WPS-AUTH-001] Login failed. Please check your credentials.', 'wpshop' ),
			'WPS-AUTH-002' => __( '[WPS-AUTH-002] Registration failed. Please try again.', 'wpshop' ),
			'WPS-AUTH-003' => __( '[WPS-AUTH-003] Failed to send reset email. Please try again.', 'wpshop' ),
			'WPS-AUTH-004' => __( '[WPS-AUTH-004] reCAPTCHA validation failed.', 'wpshop' ),
			'WPS-AUTH-005' => __( '[WPS-AUTH-005] Captcha response is required.', 'wpshop' ),
			'WPS-AUTH-006' => __( '[WPS-AUTH-006] Captcha is not configured on this site.', 'wpshop' ),
			'WPS-AUTH-007' => __( '[WPS-AUTH-007] Invalid captcha response. Please try again.', 'wpshop' ),
			'WPS-AUTH-008' => __( '[WPS-AUTH-008] Username or email already exists.', 'wpshop' ),
			'WPS-AUTH-009' => __( '[WPS-AUTH-009] Please provide a valid email address.', 'wpshop' ),
			'WPS-AUTH-010' => __( '[WPS-AUTH-010] No user found with this email address.', 'wpshop' ),
			'WPS-AUTH-011' => __( '[WPS-AUTH-011] Password reset failed. Please try again.', 'wpshop' ),
			'WPS-AUTH-012' => __( '[WPS-AUTH-012] reCAPTCHA expired. Please verify again.', 'wpshop' ),
			'WPS-AUTH-013' => __( '[WPS-AUTH-013] Passwords do not match.', 'wpshop' ),
			'WPS-AUTH-014' => __( '[WPS-AUTH-014] You must agree to the terms and conditions.', 'wpshop' ),
		);

		return isset( $messages[ $code ] ) ? $messages[ $code ] : __( '[WPS-GEN-001] An undefined error occurred.', 'wpshop' );
	}

	/**
	 * Retourne un objet WP_Error standard.
	 * C'est la bonne pratique WordPress pour gérer les erreurs.
	 *
	 * @param string $code Le code de l'erreur.
	 * @param mixed  $data  Données supplémentaires pour l'erreur.
	 * @return \WP_Error
	 */
	public static function get_wp_error( $code, $data = '' ) {
		return new \WP_Error( $code, self::get( $code ), $data );
	}
}

new Error_Util();
