<?php
/**
 * La vue affichant le statut d'un objet.
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
 * @var string $class  La classe de l'object.
 * @var string $text   Le texte à afficher.
 * @var mixed  $object Les données d'un objet ( commande, produit, facture ).
 */
?>

<span class="wps-status <?php echo $class; ?>"><?php echo apply_filters( 'wps_doli_status', $text, $object ); ?></span>
