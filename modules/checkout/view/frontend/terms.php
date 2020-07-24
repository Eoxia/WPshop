<?php
/**
 * Les termes de la boutique.
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
 * @var string $terms_message Le message des termes.
 */
?>

<div class="form-element terms">
	<label class="form-field-container">
		<div class="form-field-inline">
			<input type="checkbox" id="terms" class="form-field" name="terms">
			<label for="terms"><?php echo apply_filters( 'wps_checkout_terms', $terms_message ); ?></label>
		</div>
	</label>
</div>
