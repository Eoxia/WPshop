<?php
/**
 * Product List Table Class.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

// Include WordPress table class
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Product_List_Table Class.
 */
class Product_List_Table extends \WP_List_Table {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Product', 'wpshop' ),
			'plural'   => __( 'Products', 'wpshop' ),
			'ajax'     => false,
		) );
	}

	/**
	 * Get columns
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'image'       => __( 'Image', 'wpshop' ),
			'title'       => __( 'Title', 'wpshop' ),
			'price'       => __( 'Price', 'wpshop' ),
			'price_ttc'   => __( 'Price TTC', 'wpshop' ),
			'stock'       => __( 'Stock', 'wpshop' ),
			'categories'  => __( 'Categories', 'wpshop' ),
			'sync_status' => __( 'Sync Status', 'wpshop' ),
		);
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'title'       => array( 'title', true ),
			'price'       => array( 'price', false ),
			'price_ttc'   => array( 'price_ttc', false ),
			'stock'       => array( 'stock', false ),
			'sync_status' => array( 'sync_status', false ),
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
		$per_page = 20;
		$current_page = $this->get_pagenum();
		$offset = ( $current_page - 1 ) * $per_page;
		
		// Args for WP_Query
		$args = array(
			'post_type'      => 'wps-product',
			'posts_per_page' => $per_page,
			'offset'         => $offset,
			'orderby'        => isset( $_REQUEST['orderby'] ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'date',
			'order'          => isset( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'DESC',
		);
		
		// Add search query if set
		if ( ! empty( $search_term ) ) {
			$args['s'] = $search_term;
		}
		
		// Get products
		$products_query = new \WP_Query( $args );
		$products = $products_query->posts;
		
		// Process products to add meta data
		foreach ( $products as $product ) {
			$product->price = get_post_meta( $product->ID, '_price', true );
			$product->price_ttc = get_post_meta( $product->ID, '_price_ttc', true );
			$product->stock = get_post_meta( $product->ID, '_stock', true );
			$product->manage_stock = get_post_meta( $product->ID, '_manage_stock', true );
			$product->external_id = get_post_meta( $product->ID, '_external_id', true );
		}
		
		// Set pagination args
		$total_items = $products_query->found_posts;
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );
		
		$this->items = $products;
	}

	/**
	 * Checkbox column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="product[]" value="%s" />',
			$item->ID
		);
	}

	/**
	 * Image column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_image( $item ) {
		$thumbnail = get_the_post_thumbnail( $item->ID, array( 50, 50 ) );
		
		if ( $thumbnail ) {
			return $thumbnail;
		}
		
		return '<img src="' . PLUGIN_WPSHOP_URL . '/core/asset/image/default-product-thumbnail-min.jpg" width="50" height="50" alt="' . esc_attr__( 'Product Image', 'wpshop' ) . '">';
	}

	/**
	 * Title column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_title( $item ) {
		$edit_url = get_edit_post_link( $item->ID );
		$view_url = get_permalink( $item->ID );
		
		$actions = array(
			'edit'   => sprintf( '<a href="%s">%s</a>', $edit_url, __( 'Edit', 'wpshop' ) ),
			'view'   => sprintf( '<a href="%s">%s</a>', $view_url, __( 'View', 'wpshop' ) ),
			'delete' => sprintf( 
				'<a href="%s" class="submitdelete" onclick="return confirm(\'%s\');">%s</a>', 
				get_delete_post_link( $item->ID, '', true ), 
				__( 'Are you sure you want to delete this product?', 'wpshop' ), 
				__( 'Delete', 'wpshop' ) 
			),
		);
		
		return sprintf( 
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s',
			$edit_url,
			$item->post_title,
			$this->row_actions( $actions )
		);
	}

	/**
	 * Price column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_price( $item ) {
		return number_format( (float) $item->price, 2 ) . ' €';
	}

	/**
	 * Price TTC column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_price_ttc( $item ) {
		return number_format( (float) $item->price_ttc, 2 ) . ' €';
	}

	/**
	 * Stock column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_stock( $item ) {
		if ( $item->manage_stock ) {
			return sprintf(
				'<span class="wps-status %s">%s</span>',
				$item->stock > 0 ? 'status-green' : 'status-red',
				$item->stock > 0 ? __('In stock') . '(' . $item->stock . ')' : __( 'Out of stock', 'wpshop' )
			);
		} else {
			return __( 'Stock not configurated', 'wpshop' );
		}
	}

	/**
	 * Categories column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_categories( $item ) {
		$terms = get_the_terms( $item->ID, 'wps-product-cat' );
		
		if ( ! $terms || is_wp_error( $terms ) ) {
			return '—';
		}
		
		$category_links = array();
		
		foreach ( $terms as $term ) {
			$category_links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'edit.php?post_type=wps-product&wps-product-cat=' . $term->slug ) ),
				esc_html( $term->name )
			);
		}
		
		return implode( ', ', $category_links );
	}

	/**
	 * Date column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_date( $item ) {
		$date = new \DateTime( $item->post_date );
		return $date->format( get_option( 'date_format' ) );
	}

	/**
	 * Sync status column
	 *
	 * @param object $item Product post object
	 * @return string
	 */
	public function column_sync_status( $item ) {
		$product_obj = new \stdClass();
		$product_obj->data = array(
			'id' => $item->ID,
			'external_id' => $item->external_id
		);
		
		ob_start();
		Doli_Sync::g()->display_sync_status( $product_obj, 'wps-product' );
		return ob_get_clean();
	}

	/**
	 * Default column renderer
	 *
	 * @param object $item Product post object
	 * @param string $column_name Column name
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		return isset( $item->$column_name ) ? $item->$column_name : '—';
	}

	/**
	 * Define bulk actions
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'wpshop' ),
		);
	}
}
