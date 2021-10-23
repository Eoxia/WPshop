<?php
/**
 * La classe gérant les filtres des utilisateurs.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * User Filter Class.
 */
class User_Filter {

	/**
	 * Initialise les filtres.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'wps_contact_item_contact_title_after', array( $this, 'add_user_switching' ) );
		add_filter( 'wps_tier_single_contact_item_contact_title_after', array( $this, 'add_user_switching' ) );
	}

	/**
	 * Affiche le lien pour switcher vers l'utilisateur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param User $contact Les données du contact.
	 */
	public function add_user_switching( $contact ) {
		if ( class_exists( '\user_switching' ) ) {
			$user = get_user_by( 'id', $contact->data['id'] );

			$link = \user_switching::maybe_switch_url( $user );

			?>
			<a href="<?php echo esc_attr( $link ); ?>"><i class="fas fa-random"></i></a>
			<?php
		}
	}
}

new User_Filter();
