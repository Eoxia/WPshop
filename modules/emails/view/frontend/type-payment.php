<?php
/**
 * La vue affichant le type de paiement.
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
 * @var Doli_Order $order           Les données d'une commande.
 * @var string     $payment_methods Tableau contenant toutes les données des méthodes de paiements.
 */
?>

<?php if ( 'Cheque' === $order->mode_reglement ) :
	echo stripslashes( nl2br( $payment_methods['cheque']['description'] ) );
endif;

if ( 'payment_in_shop' === $order->mode_reglement ) :
	echo stripslashes( nl2br( $payment_methods['cheque']['description'] ) );
endif; ?>
