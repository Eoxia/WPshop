<?php
/**
 * La vue affichant les messages d'erreurs du tunnel de vente.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2020 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var \WP_Error $errors  Tableau contenant toutes les données des erreurs.
 * @var string    $message Le message d'erreur.
 */
?>

<ul class="error notice">
	<?php
	if ( ! empty( $errors->get_error_messages() ) ) :
		foreach ( $errors->get_error_messages() as $message ) :
			?>
			<li><?php echo $message; ?></li>
			<?php
		endforeach;
	endif;
	?>
</ul>
