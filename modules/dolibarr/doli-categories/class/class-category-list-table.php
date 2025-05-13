<?php
/**
 * La classe gérant le tableau des catégories avec WP_List_Table.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.1.0
 * @version   2.1.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

// Include WordPress table class
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Category List Table Class.
 */
class Category_List_Table extends \WP_List_Table {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Category', 'wpshop' ),
			'plural'   => __( 'Categories', 'wpshop' ),
			'ajax'     => false,
		) );
        
		// Add custom CSS for table styling
		add_action('admin_head', array($this, 'add_custom_styles'));
	}
	
	/**
	 * Add custom styles for the table
	 */
	public function add_custom_styles() {
		echo '<style>
			.wps-category-table th, 
			.wps-category-table td {
				padding: 12px 10px !important;
				vertical-align: middle !important;
			}
		</style>';
	}
	
	/**
	 * Get table classes
	 *
	 * @return array
	 */
	public function get_table_classes() {
		$classes = parent::get_table_classes();
		$classes[] = 'wps-category-table';
		return $classes;
	}

	/**
	 * Get columns
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'name'        => __( 'Name', 'wpshop' ),
			'description' => __( 'Description', 'wpshop' ),
		);
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'name' => array( 'name', true ),
		);
	}

	/**
	 * Prepare items
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		
		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		// Search term
		$search_term = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		
		// Pagination
		$per_page = get_user_meta( get_current_user_id(), Doli_Category::g()->option_per_page, true );
		if ( empty( $per_page ) || 1 > $per_page ) {
			$per_page = Doli_Category::g()->limit;
		}
		
		$current_page = $this->get_pagenum();
		
		// Retrieve all categories first
		$all_categories = Doli_Category::g()->search( $search_term, array(), false );
		$count = count($all_categories);
		
		// Sort the categories manually
		$orderby = isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'name';
		$order = isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'asc';
		
		 // Manual sorting
		usort( $all_categories, function( $a, $b ) use ( $orderby, $order ) {
			$result = 0;
			
			if ( $orderby === 'name' ) {
				$result = strcmp( $a->data['name'], $b->data['name'] );
			} else {
				// Fallback to name if orderby isn't explicitly handled
				$result = strcmp( $a->data['name'], $b->data['name'] );
			}
			
			return ( $order === 'asc' ) ? $result : -$result;
		});
		
		// Slice for pagination
		$offset = ( $current_page - 1 ) * $per_page;
		$categories = array_slice( $all_categories, $offset, $per_page );

		// Set pagination args
		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count / $per_page ),
		) );
		
		$this->items = $categories;
	}

	/**
	 * Checkbox column
	 *
	 * @param object $item Category object
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="category[]" value="%s" />',
			$item->data['id']
		);
	}

	/**
	 * Name column
	 *
	 * @param object $item Category object
	 * @return string
	 */
	public function column_name( $item ) {
		$edit_url = admin_url( 'admin.php?page=wps-product-cat&id=' . $item->data['id'] );
		
		$actions = array(
			'edit'   => sprintf( '<a href="%s">%s</a>', $edit_url, __( 'Edit', 'wpshop' ) ),
			'delete' => sprintf( 
				'<a href="%s" class="submitdelete" onclick="return confirm(\'%s\');">%s</a>', 
				wp_nonce_url( admin_url( 'admin-post.php?action=delete_category&id=' . $item->data['id'] ), 'delete_category_' . $item->data['id'] ), 
				__( 'Are you sure you want to delete this category?', 'wpshop' ), 
				__( 'Delete', 'wpshop' ) 
			),
		);
		
		return sprintf( 
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s',
			$edit_url,
			$item->data['name'],
			$this->row_actions( $actions )
		);
	}

	/**
	 * Description column
	 *
	 * @param object $item Category object
	 * @return string
	 */
	public function column_description( $item ) {
		return !empty($item->data['description']) ? wp_trim_words($item->data['description'], 10) : '—';
	}

	/**
	 * Default column renderer
	 *
	 * @param object $item Category object
	 * @param string $column_name Column name
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		return isset( $item->data[$column_name] ) ? $item->data[$column_name] : '—';
	}

	/**
	 * Define bulk actions
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'wpshop' ),
			'sync'   => __( 'Sync with Dolibarr', 'wpshop' ),
		);
	}
}
