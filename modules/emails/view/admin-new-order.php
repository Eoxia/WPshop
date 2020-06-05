<?php
/**
 * Email reçu par l'administrateur lors d'une nouvelle commande utilisateur.
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
	/* translators: You’ve received the following order from customer name */
	printf( __( 'You’ve received the following order from %s:', 'wpshop' ), $data['third_party']['title'] );

	/* translators: <a href="%s">View order</a> */
	printf( __( '<a href="%s">View order</a>', 'wpshop' ), admin_url( 'admin.php?page=wps-order&id=' . $data['order_id'] ) );
	?>
</p>
<?php

do_action( 'wps_email_order_details', $data['order'] );

do_action( 'wps_email_order_meta' );

do_action( 'wps_email_customer_details' );

do_action( 'wps_email_footer' );
