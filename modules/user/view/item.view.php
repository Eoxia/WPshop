<?php
/**
 * Affichage d'un contact de le tableau des tiers dans le backend.
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

<div class="contact">
	<div class="contact-title">
		<?php if ( ! empty( $contact->data['displayname'] ) ) : ?>
			<a href="<?php echo esc_attr( admin_url( 'user-edit.php?user_id=' . $contact->data['id'] . '&action=edit' ) ); ?>"><?php echo esc_html( $contact->data['displayname'] ); ?></a>
		<?php endif; ?>
		<?php if ( ! empty( $contact->data['email'] ) ) : ?>
			<a href="mailto:<?php echo esc_html( $contact->data['email'] ); ?>" class="wpeo-tooltip-event" aria-label="<?php echo esc_html( $contact->data['email'] ); ?>"><i class="fas fa-envelope"></i></a>
		<?php endif; ?>

		<?php do_action( 'wps_contact_item_contact_title_after', $contact ); ?>
	</div>
	<ul class="contact-list-data">
		<?php if ( ! empty( $contact->data['phone'] ) ) : ?>
			<li class="contact-data"><i class="fas fa-phone"></i> <?php echo esc_html( $contact->data['phone'] ); ?></li>
		<?php endif; ?>
		<?php if ( ! empty( $contact->data['phone_mobile'] ) ) : ?>
			<li class="contact-data"><i class="fas fa-mobile"></i> <?php echo esc_html( $contact->data['phone_mobile'] ); ?></li>
		<?php endif; ?>
	</ul>
</div>
