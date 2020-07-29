<?php
/**
 * La vue affichant les données d'un utilisateur relié à un tier.
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
 * @var User $contact Les données d'un utilisateur.
 */
?>

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
