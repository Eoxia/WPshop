<?php
/**
 * Email reçu par l'utilisateur avec sa facture.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'wps_email_header' ); ?>

<p>
	<?php
	/* translators: %s: Customer first name */
	printf( esc_html__( 'Hi %s,', 'wpshop' ), esc_html( $data['contact']['lastname'] . ' ' . $data['contact']['firstname'] ) );
	?>
</p>

<p>
	<?php
	/* translators: %s: Order number */
	printf( esc_html__( 'Your invoice %1$s for your order %2$s', 'wpshop' ), esc_html( $data['invoice']->data['title'] ), esc_html( $data['order']->data['title'] ) );
	?>
</p>
<?php

do_action( 'wps_email_invoice_details' );

do_action( 'wps_email_invoice_meta' );

do_action( 'wps_email_customer_details' );

do_action( 'wps_email_footer' );
