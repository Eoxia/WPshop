<?php
/**
 * Email reçu par l'utilisateur lors de la création de son nouveau compte.
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

do_action( 'wps_email_header' );  ?>

<p>
	<?php
	/* translators: %s Customer username */
	printf( esc_html__( 'Hi %s,', 'wpshop' ), esc_html( $data['contact']['login'] ) );
	?>
</p>
<p>
	<?php
	/* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */
	printf( __( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: <a href="%3$s">%4$s</a>', 'wpshop' ),
		get_bloginfo(),
		'<strong>' . esc_html( $data['contact']['login'] ) . '</strong>',
		esc_url( Pages::g()->get_account_link() ),
		get_bloginfo()
	);
	?>
</p>

<p>
	<?php
	/* translators: Click here to create your password: <strong><a href="createpassword">Create my password</a></strong> */
	printf( __( 'Click here to create your password: <strong><a href="%s">Create my password</a></strong>', 'wpshop' ), $data['url'] );
	?>
</p>

<p><?php esc_html_e( 'We look forward to seeing you soon.', 'wpshop' ); ?></p>

<?php

do_action( 'wps_email_footer' );
