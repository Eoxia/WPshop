<?php
/**
 * La vue affichant le stock d'un produit.
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
 * @var Product $product Les données d'un produit.
 */
?>

<p>
	<?php
	// translators: %s en stock.
	printf( __( '%s in stock', 'wpshop' ), $product->data['stock'] );
	?>
</p>
