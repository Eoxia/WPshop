<?php
/**
 * La box contenant les contact du tier. (wps-third-party)
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

<div class="table-row">
	<div class="table-cell">
		<?php echo esc_html( $contact->data['lastname'] ); ?>
	</div>
	<div class="table-cell">
		<?php echo esc_html( $contact->data['firstname'] ); ?>
	</div>
	<div class="table-cell"><?php echo esc_html( $contact->data['email'] ); ?></div>
	<div class="table-cell"><?php echo esc_html( $contact->data['phone'] ); ?></div>
	<div class="table-cell table-end">
		<a target="_blank" href="<?php echo esc_attr( admin_url( 'user-edit.php?user_id=' . $contact->data['id'] . '&action=edit' ) ); ?>">
			<i class="fa far fa-pencil"></i>
		</a>
		<?php do_action( 'wps_tier_single_contact_item_contact_title_after', $contact ); ?>
	</div>
</div>
