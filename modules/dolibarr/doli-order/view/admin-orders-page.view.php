<?php
/**
 * La vue de la liste des commandes dans l'admin.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var Order_List_Table $order_list_table L'objet contenant la liste des commandes.
 * @var string $dolibarr_url L'URL de Dolibarr
 * @var string $dolibarr_create_order L'URL pour créer une commande dans Dolibarr
 */
?>

<div class="wrap wpeo-wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Orders', 'wpshop' ); ?></h1>
    <a href="<?php echo esc_url( $dolibarr_url . $dolibarr_create_order ); ?>" target="_blank" class="page-title-action"><?php esc_html_e( 'Add New', 'wpshop' ); ?></a>
    <hr class="wp-header-end">
    
    <?php
    // Afficher les messages de notification éventuels
    if ( isset( $_GET['message'] ) ) {
        $message = sanitize_text_field( $_GET['message'] );
        if ( $message === 'sync_success' ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Orders synchronized with Dolibarr.', 'wpshop' ) . '</p></div>';
        }
    }
    ?>
    
    <form id="orders-filter" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php $order_list_table->display(); ?>
    </form>
</div>
