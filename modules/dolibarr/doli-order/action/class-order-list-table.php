<?php
/**
 * Order List Table Class
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Order List Table Class
 */
class Order_List_Table extends \WP_List_Table {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct( array(
            'singular' => __( 'Order', 'wpshop' ),
            'plural'   => __( 'Orders', 'wpshop' ),
            'ajax'     => false
        ) );
    }

    /**
     * Get list columns
     * 
     * @return array
     */
    public function get_columns() {
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'title'       => __( 'Reference', 'wpshop' ),
            'date'        => __( 'Date', 'wpshop' ),
            'customer'    => __( 'Customer', 'wpshop' ),
            'status'      => __( 'Status', 'wpshop' ),
            'total_ht'    => __( 'Price HT', 'wpshop' ),
            'total_ttc'   => __( 'Price TTC', 'wpshop' )
        );
        
        return $columns;
    }

    /**
     * Get sortable columns
     * 
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array( 'title', false ),
            'date'      => array( 'date', false ),
            'total_ht'  => array( 'total_ht', false ),
            'total_ttc' => array( 'total_ttc', false ),
        );
        
        return $sortable_columns;
    }

    /**
     * Prepare items for the table
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array( $columns, $hidden, $sortable );
        
        // Handle bulk actions
        $this->process_bulk_action();
        
        // Pagination setup
        $per_page = $this->get_items_per_page( Doli_Order::g()->option_per_page, Doli_Order::g()->limit );
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;
        
        // Search
        $s = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        
        // Ordering
        $orderby = isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'date';
        $order = isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'desc';
        
        // Debug pour vérifier les paramètres de tri
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Ordre demandé: ' . $orderby . ' ' . $order);
        }
        
        // Récupérer tous les éléments sans pagination pour permettre un tri manuel
        // Nous gérerons la pagination manuellement après le tri
        $all_items = Doli_Order::g()->search( $s, array('number' => -1) );
        $total_items = count($all_items);
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Total orders found: ' . $total_items);
        }
        
        // Trier les éléments manuellement
        if (!empty($all_items) && $total_items > 0) {
            usort($all_items, function($a, $b) use ($orderby, $order) {
                // Déterminer quelle valeur utiliser pour le tri en fonction de $orderby
                $a_val = '';
                $b_val = '';
                
                switch ($orderby) {
                    case 'title':
                        $a_val = strtolower($a->data['title']);
                        $b_val = strtolower($b->data['title']);
                        break;
                    case 'date':
                        $a_val = strtotime($a->data['datec']);
                        $b_val = strtotime($b->data['datec']);
                        break;
                    case 'total_ht':
                        $a_val = floatval($a->data['total_ht']);
                        $b_val = floatval($b->data['total_ht']);
                        break;
                    case 'total_ttc':
                        $a_val = floatval($a->data['total_ttc']);
                        $b_val = floatval($b->data['total_ttc']);
                        break;
                    default:
                        // Par défaut on trie par date
                        $a_val = strtotime($a->data['datec']);
                        $b_val = strtotime($b->data['datec']);
                }
                
                // Déterminer l'ordre de tri
                if ($order === 'asc') {
                    return ($a_val < $b_val) ? -1 : 1;
                } else {
                    return ($a_val > $b_val) ? -1 : 1;
                }
            });
            
            // Appliquer la pagination manuellement
            $items = array_slice($all_items, $offset, $per_page);
        } else {
            $items = array();
        }
        
        $this->items = $items;
        
        // Pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ) );
    }
    
    /**
     * Message to show when no items found
     */
    public function no_items() {
        $s = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        
        if (!empty($s)) {
            _e('No orders found matching your search criteria.', 'wpshop');
        } else {
            _e('No orders found. This could be due to a connection issue with Dolibarr or because no orders exist yet.', 'wpshop');
        }
        
        // Afficher un lien pour rafraîchir la page
        echo '<p><a href="' . esc_url(admin_url('admin.php?page=wps-order')) . '" class="button">';
        _e('Refresh List', 'wpshop');
        echo '</a></p>';
        
        // Vérification de la connexion Dolibarr
        if (method_exists('wpshop\Settings', 'g') && method_exists(Settings::g(), 'check_dolibarr_connection')) {
            $connection_status = Settings::g()->check_dolibarr_connection();
            if (!$connection_status['success']) {
                echo '<div class="notice notice-error"><p>';
                _e('Connection to Dolibarr failed. Please check your settings.', 'wpshop');
                echo '</p></div>';
            }
        }
    }

    /**
     * Default column rendering
     * 
     * @param object $item
     * @param string $column_name
     * @return string
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'date':
                return isset($item->data['datec']) ? $item->data['datec'] : '';
            case 'customer':
                $third_party = Third_Party::g()->get( array( 'id' => $item->data['parent_id'] ), true );
                if ( ! empty( $third_party ) ) {
                    return sprintf(
                        '<a href="%s">%s</a>',
                        admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ),
                        $third_party->data['title']
                    );
                }
                return '';
            case 'total_ht':
                return number_format( $item->data['total_ht'], 2, ',', ' ' ) . ' €';
            case 'total_ttc':
                return number_format( $item->data['total_ttc'], 2, ',', ' ' ) . ' €';
            case 'status':
                return Doli_Statut::g()->display_status( $item );
            default:
                return print_r( $item, true );
        }
    }

    /**
     * Checkbox column
     * 
     * @param object $item
     * @return string
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="order_id[]" value="%s" />',
            $item->data['id']
        );
    }

    /**
     * Title column with actions
     * 
     * @param object $item
     * @return string
     */
    public function column_title( $item ) {
        $dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
        $dolibarr_url    = $dolibarr_option['dolibarr_url'];
        $dolibarr_url    = rtrim( $dolibarr_url, '/' );
        $view_url        = admin_url( 'admin.php?page=wps-order&id=' . $item->data['external_id'] );
        $dolibarr_view_url = $dolibarr_url . '/commande/card.php?id=' . $item->data['external_id'];
        
        $actions = array(
            'dolibarr'  => '<a href="' . esc_url( $dolibarr_view_url ) . '" target="_blank">' . __( 'View in Dolibarr', 'wpshop' ) . '</a>',
            'download'  => '<a href="' . esc_url( admin_url( 'admin-post.php?action=wps_download_order&order_id=' . $item->data['external_id'] . '&_wpnonce=' . wp_create_nonce( 'download_order' ) ) ) . '" download>' . __( 'Download', 'wpshop' ) . '</a>',
        );
        
        return sprintf(
            '<a href="%s"><strong>%s</strong></a>%s',
            $view_url,
            $item->data['title'],
            $this->row_actions( $actions )
        );
    }

    /**
     * Get bulk actions
     * 
     * @return array
     */
    public function get_bulk_actions() {
        return array();
    }

    /**
     * Process bulk action
     */
    public function process_bulk_action() {
        // À implémenter si nécessaire
    }    
}
