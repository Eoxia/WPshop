<?php
/**
 * Ajoutes le type de paiement
 *
 * @todo: Faire contenu mail
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

if ( 'Cheque' === $order->mode_reglement ) :
	echo stripslashes( nl2br( $payment_methods['cheque']['description'] ) );
endif;

if ( 'payment_in_shop' === $order->mode_reglement ) :
	echo stripslashes( nl2br( $payment_methods['cheque']['description'] ) );
endif;
