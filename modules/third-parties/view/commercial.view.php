<?php
/**
 * La vue affichant les données commerciales d'un tier: proposition commerciale, commande et facture.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Proposals    $proposal    Les données d'une proposition commerciale.
 * @var Doli_Order   $order       Les données d'une commande.
 * @var boolean      $doli_active True si Dolibarr est activé.
 * @var string       $doli_url    L'url de Dolibarr.
 * @var Doli_Invoice $invoice     Les données d'une facture.
 */
?>

<ul class="list-commercial">
<!--	--><?php //if ( ! empty( $propal->data ) ) : ?>
	<!--		<li class="commercial type-propal">-->
	<!--			<i class="fas fa-file-signature"></i>-->
	<!--			<span class="commercial-date">--><?php //echo $propal->data['datec']['rendered']['date']; ?><!--</span>-->
	<!--			<span class="commercial-title"><a href="--><?php //echo esc_attr( admin_url( 'admin.php?page=wps-proposal&id=' . $propal->data['id'] ) ); ?><!--">--><?php //echo $propal->data['title']; ?><!--</a></span>-->
	<!--			<span class="commercial-price">--><?php //echo ! empty( $propal->data['total_ttc'] ) ? number_format( $propal->data['total_ttc'], 2, ',', '' ) . '€ TTC' : ''; ?><!--</span>-->
	<!--			<span class="commercial-status">--><?php //echo Doli_Statut::g()->display_status( $propal ); ?><!--</span>-->
	<!--		</li>-->
	<!--	--><?php //endif; ?>
	<?php if ( ! empty( $proposal->data ) && $doli_active ) : ?>
		<li class="commercial type-propasal">
			<i class="fas fa-file-signature"></i>
			<span class="commercial-date"><?php echo  Date_Util::readable_date( $proposal->data['datec'], 'date' ); ?></span>
			<span class="commercial-title"><a href="<?php echo esc_attr(  $doli_url . '/comm/propal/card.php?id=' . $proposal->data['external_id'] ); ?>"><?php echo $proposal->data['title']; ?></a></span>
			<span class="commercial-price"><?php echo ! empty( $proposal->data['total_ttc'] ) ? number_format( $proposal->data['total_ttc'], 2, ',', '' ) . '€ TTC' : ''; ?></span>
			<span class="commercial-status"><?php echo Doli_Statut::g()->display_status( $proposal ); ?></span>
		</li>
	<?php endif; ?>
	<?php if ( ! empty( $order->data ) && $doli_active ) : ?>
		<li class="commercial type-order">
			<i class="fas fa-shopping-cart"></i>
			<span class="commercial-date"><?php echo Date_Util::readable_date( $order->data['datec'], 'date' ); ?></span>
			<span class="commercial-title"><a href="<?php echo esc_attr( $doli_url . '/commande/card.php?id=' . $order->data['external_id'] ); ?>"><?php echo $order->data['title']; ?></a></span>
			<span class="commercial-price"><?php echo ! empty( $order->data['total_ttc'] ) ? number_format( $order->data['total_ttc'], 2, ',', '' ) . '€ TTC' : ''; ?></span>
			<span class="commercial-status"><?php echo Doli_Statut::g()->display_status( $order ); ?></span>
		</li>
	<?php endif; ?>
	<?php if ( ! empty( $invoice->data ) && $doli_active ) : ?>
		<li class="commercial type-invoice">
			<i class="fas fa-file-invoice-dollar"></i>
			<span class="commercial-date"><?php echo Date_Util::readable_date( $invoice->data['datec'], 'date' ); ?></span>
			<span class="commercial-title"><a href="<?php echo esc_attr( $doli_url . '/compta/facture/card.php?id=' . $invoice->data['external_id'] ); ?>"><?php echo $invoice->data['title']; ?></a></span>
			<span class="commercial-price"><?php echo ! empty( $invoice->data['total_ttc'] ) ? number_format( $invoice->data['total_ttc'], 2, ',', '' ) . '€ TTC' : ''; ?></span>
			<span class="commercial-status"><?php echo Doli_Statut::g()->display_status( $invoice ); ?></span>
		</li>
	<?php endif; ?>
</ul>
