<?php
/**
 * Email reçu par l'utilisateur lors d'une commande en cours.
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

do_action( 'wps_email_header' ); ?>

<p>
	<?php
	/* translators: %s: Customer first name */
	printf( esc_html__( 'Hi %s,', 'wpshop' ), esc_html( $data['third_party']['title'] ) );
	?>
</p>
<p>
	<?php
	/* translators: %s: Order number */
	printf( esc_html__( 'Just to let you know — we\'ve received your order #%s, and it is now being processed:', 'wpshop' ), esc_html( $data['order']->ref ) );
	?>
</p>

<?php

do_action( 'wps_email_order_details', $data['order'] );

do_action( 'wps_email_order_meta' );

do_action( 'wps_email_customer_details' );

do_action( 'wps_email_footer' );
