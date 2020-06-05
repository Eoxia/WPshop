<?php
/**
 * Les termes de la boutique.
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

<div class="form-element terms">
	<label class="form-field-container">
		<div class="form-field-inline">
			<input type="checkbox" id="terms" class="form-field" name="terms">
			<label for="terms"><?php echo apply_filters( 'wps_checkout_terms', $terms_message ); ?></label>
		</div>
	</label>
</div>
