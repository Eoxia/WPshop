<?php
/**
 * Third Party List Table.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.4.0
 * @version   2.4.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class for handling third parties display in admin area using WP_List_Table.
 */
class Third_Party_List_Table extends \WP_List_Table {

    /**
     * Constructor.
     *
     * @since 2.4.0
     */
    public function __construct() {
        parent::__construct( array(
            'singular' => __( 'Third Party', 'wpshop' ),
            'plural'   => __( 'Third Parties', 'wpshop' ),
            'ajax'     => false,
        ) );
    }

    /**
     * Get list columns.
     *
     * @since 2.4.0
     *
     * @return array
     */
    public function get_columns() {
        return array(
            'cb'           => '<input type="checkbox" />',
            'title'        => __( 'Title', 'wpshop' ),
            'email'        => __( 'Email', 'wpshop' ),
            'phone'        => __( 'Phone', 'wpshop' ),
            'full_address' => __( 'Address', 'wpshop' ),
            'country'      => __( 'Country', 'wpshop' ),
            'date_created' => __( 'Date', 'wpshop' ),
        );
    }

    /**
     * Get sortable columns.
     *
     * @since 2.4.0
     *
     * @return array
     */
    public function get_sortable_columns() {
        return array(
            'title'        => array( 'title', true ),
            'email'        => array( 'email', false ),
            'phone'        => array( 'phone', false ),
            'full_address' => array( 'address', false ),
            'country'      => array( 'country', false ),
            'date_created' => array( 'date_created', false ),
        );
    }

    /**
     * Prepare items for table.
     *
     * @since 2.4.0
     */
    public function prepare_items() {
        // Set up columns
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        // Pagination
        $per_page = $this->get_items_per_page( Third_Party::g()->option_per_page, Third_Party::g()->limit );
        $current_page = $this->get_pagenum();
        
        $search = ( ! empty( $_REQUEST['s'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
        
        $args = array(
            'offset'     => ( $current_page - 1 ) * $per_page,
            'limit'      => $per_page,
            'orderby'    => ! empty( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date_created',
            'order'      => ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'desc',
        );

        // Recherche directe (contournement temporaire)
        if (empty($search)) {
            $this->items = Third_Party::g()->get(array('limit' => $per_page));
            $total_items = Third_Party::g()->get(array('count' => true));
        } else {
            $this->items = Third_Party::g()->search($search, $args);
            $total_items = Third_Party::g()->search($search, array(), true);
        }
        
        $this->set_pagination_args( array(
            'total_items' => count( $total_items ),
            'per_page'    => $per_page,
            'total_pages' => ceil( count($total_items) / $per_page ),
        ) );
    }

    /**
     * Default column rendering.
     *
     * @since 2.4.0
     *
     * @param object $item        The current item.
     * @param string $column_name The current column name.
     *
     * @return string
     */
    public function column_default( $item, $column_name ) {
        // Vérifier si $item est un objet et s'il a la propriété data
        if (!is_object($item) || !isset($item->data)) {
            return 'Invalid item structure';
        }

        switch ( $column_name ) {
            case 'email':
                return ! empty( $item->data['email'] ) ? '<a href="mailto:' . esc_attr( $item->data['email'] ) . '">' . esc_html( $item->data['email'] ) . '</a>' : '—';
            case 'phone':
                return ! empty( $item->data['phone'] ) ? '<a href="tel:' . esc_attr( $item->data['phone'] ) . '">' . esc_html( $item->data['phone'] ) . '</a>' : '—';
            case 'full_address':
                $address_parts = array();
                if (!empty($item->data['address'])) {
                    $address_parts[] = esc_html($item->data['address']);
                }
                if (!empty($item->data['zip']) && !empty($item->data['town'])) {
                    $address_parts[] = esc_html($item->data['zip'] . ', ' . $item->data['town']);
                } else {
                    if (!empty($item->data['zip'])) {
                        $address_parts[] = esc_html($item->data['zip']);
                    }
                    if (!empty($item->data['town'])) {
                        $address_parts[] = esc_html($item->data['town']);
                    }
                }
                return !empty($address_parts) ? implode('<br />', $address_parts) : '—';
            case 'country':
                return ! empty( $item->data['country'] ) ? get_from_id( $item->data['country'] )['label'] : '—';
            case 'date_created':
                return ! empty( $item->data['date']['raw'] ) ? $item->data['date']['raw'] : '—';
            default:
                return isset( $item->data[ $column_name ] ) ? $item->data[ $column_name ] : '—';
        }
    }

    /**
     * Checkbox column rendering.
     *
     * @since 2.4.0
     *
     * @param object $item The current item.
     *
     * @return string
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="third_party_ids[]" value="%s" />',
            $item->data['id']
        );
    }

    /**
     * Title column rendering.
     *
     * @since 2.4.0
     *
     * @param object $item The current item.
     *
     * @return string
     */
    public function column_title( $item ) {
        $title = ! empty( $item->data['title'] ) ? $item->data['title'] : __( '(no title)', 'wpshop' );
        $edit_link = admin_url( 'admin.php?page=wps-third-party&id=' . $item->data['id'] );
        
        $actions = array(
            'edit' => '<a href="' . esc_url( $edit_link ) . '">' . __( 'Edit', 'wpshop' ) . '</a>',
        );
        
        return '<a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>' . $this->row_actions( $actions );
    }

    /**
     * Extra controls to be displayed between bulk actions and pagination.
     *
     * @since 2.4.0
     *
     * @param string $which Position (top or bottom).
     */
    protected function extra_tablenav( $which ) {
    }

    /**
     * Message to show when no items found.
     *
     * @since 2.4.0
     */
    public function no_items() {
        esc_html_e( 'No third parties found. Verify that your database has third party records.', 'wpshop' );
    }

    /**
     * Display the table.
     * Adds a wrapper div around the table.
     *
     * @since 2.4.0
     */
    public function display() {
        echo '<div class="wrap wp-list-table-wrap">';
        // Afficher le tableau
        parent::display();
        echo '</div>';
    }

    /**
     * Single row output.
     * 
     * @param object $item Item to display.
     */
    public function single_row($item) {
        // Ajout d'un try-catch pour capturer les erreurs potentielles
        try {
            echo '<tr>';
            $this->single_row_columns($item);
            echo '</tr>';
        } catch (\Exception $e) {
            echo '<tr><td colspan="' . count($this->get_columns()) . '">Error displaying row: ' . esc_html($e->getMessage()) . '</td></tr>';
        }
    }
}
