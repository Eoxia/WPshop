<?php
/**
 * Affichage du status d'un objet: Commande, Propal, Facture.
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

<span class="wps-status <?php echo $class; ?>"><?php echo apply_filters( 'wps_doli_status', $text, $object ); ?></span>
