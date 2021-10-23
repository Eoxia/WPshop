<?php
/**
 * La vue affichant le résultat de l'association.
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
 * @var array  $notice  Le tableau contenant toutes les données d'une notice.
 * @var string $message Le message de la notice.
 */
?>

<?php if ( ! empty( $notice['messages'] ) ) : ?>
	<div class="wpeo-notice notice-success">
		<ul class="notice-content">
			<?php foreach ( $notice['messages'] as $message ) : ?>
				<li><?php echo $message; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif;

if ( ! empty( $notice['errors'] ) ) : ?>
	<div class="wpeo-notice notice-warning">
		<ul class="notice-content">
			<?php foreach ( $notice['errors'] as $message ) : ?>
				<li><?php echo $message; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
