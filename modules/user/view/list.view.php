<?php
/**
 * La vue affichant la liste des utilisateurs relié à un tier.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

use eoxia\View_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array $contacts Le tableau contenant toutes les données des utilisateurs.
 * @var User  $contact  Les données d'un utilisateur.
 */
?>

<div class="list-contact">
	<?php if ( ! empty( $contacts ) ) :
		foreach ( $contacts as $contact ) :
			View_Util::exec( 'wpshop', 'user', 'item', array(
				'contact' => $contact,
			) );
		endforeach;
	endif; ?>
</div>
