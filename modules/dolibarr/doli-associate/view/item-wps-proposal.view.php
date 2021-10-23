<?php
/**
 * La vue affichant l'entité proposition commercile à associer.
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
 * Documentation des variables utilisées dans la vue.
 *
 * @var Doli_Proposals $entry Les données d'une proposition commerciale.
 */
?>

<li data-id="<?php echo esc_attr( $entry->id ); ?>">
	<?php echo apply_filters( 'wps_associate_entry', '#' . $entry->id . ' ' . $entry->ref, $entry ); ?>
</li>
