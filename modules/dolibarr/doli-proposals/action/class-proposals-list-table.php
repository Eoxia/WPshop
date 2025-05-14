<?php
/**
 * Proposals List Table Class
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Proposals_List_Table Class.
 */
class Proposals_List_Table extends \WP_List_Table {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Proposition', 'wpshop' ),
			'plural'   => __( 'Propositions', 'wpshop' ),
			'ajax'     => false
		) );
	}

	/**
	 * Get table columns
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'ref'         => __( 'Référence', 'wpshop' ),
			'date'        => __( 'Date', 'wpshop' ),
			'third_party' => __( 'Client', 'wpshop' ),
			'status'      => __( 'État', 'wpshop' ),
			'price_ht'    => __( 'Prix HT', 'wpshop' ),
			'price_ttc'   => __( 'Prix TTC', 'wpshop' ),
		);
	}

	/**
	 * Prepare items for the table
	 */
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Pagination
		$per_page = $this->get_items_per_page( Doli_Proposals::g()->option_per_page, 10 );
		$current_page = $this->get_pagenum();
		$offset = ( $current_page - 1 ) * $per_page;

		// Search
		$s = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';

		 // Use the search method from Doli_Proposals to get proposals
		$proposals = Doli_Proposals::g()->search($s);
		$total_items = Doli_Proposals::g()->search($s, array(), true);
		
		// Apply pagination manually since search doesn't handle it
		if (!empty($proposals)) {
			$proposals = array_slice($proposals, $offset, $per_page);
		}

		$this->items = $proposals;

		// Pagination args
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'ref'       => array( 'ref', false ),
			'date'      => array( 'date', false ),
			'price_ht'  => array( 'price_ht', false ),
			'price_ttc' => array( 'price_ttc', false ),
		);
	}

	/**
	 * Column default behavior
	 *
	 * @param object $item Item data
	 * @param string $column_name Column name
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'date':
				return $item->data['datec'];
			case 'price_ht':
				return number_format_i18n( $item->data['total_ht'], 2 ) . ' €';
			case 'price_ttc':
				return number_format_i18n( $item->data['total_ttc'], 2 ) . ' €';
			default:
				return isset( $item->data[$column_name] ) ? $item->data[$column_name] : '';
		}
	}

	/**
	 * Checkbox column
	 *
	 * @param object $item Item data
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="proposal[]" value="%s" />',
			$item->data['id']
		);
	}

	/**
	 * Reference column with actions
	 *
	 * @param object $item Item data
	 * @return string
	 */
	public function column_ref( $item ) {

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
        $dolibarr_url    = $dolibarr_option['dolibarr_url'];
        $dolibarr_url    = rtrim( $dolibarr_url, '/' );

		$dolibarr_view_url = $dolibarr_url . '/comm/propal/card.php?id=' . $item->data['external_id'];

		$actions = array(
			'view'   => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				$dolibarr_view_url,
				__( 'Voir dans Dolibarr', 'wpshop' )
			),
			'download' => sprintf(
				'<a href="%s" download>%s</a>',
				wp_nonce_url( admin_url( 'admin-post.php?action=wps_download_proposal&proposal_id=' . $item->data['external_id'] ), 'download_proposal' ),
				__( 'Télécharger', 'wpshop' )
			),
		);

		return sprintf(
			'<a href="%s"><strong>%s</strong></a> %s',
			admin_url( 'admin.php?page=wps-proposal-doli&id=' . $item->data['external_id'] ),
			$item->data['title'],
			$this->row_actions( $actions )
		);
	}

	/**
	 * Third party column
	 *
	 * @param object $item Item data
	 * @return string
	 */
	public function column_third_party( $item ) {
		if (empty( $item->data['parent_id'] ) ) {
			return '-';
		}
		$third_party = Third_Party::g()->get( array( 'id' => $item->data['parent_id'] ), true );
		if ( ! empty( $third_party ) ) {
			return sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=wps-third-party&id=' . $third_party->data['id'] ),
				$third_party->data['title']
			);
		}
		return '';
	}

	/**
	 * Status column
	 *
	 * @param object $item Item data
	 * @return string
	 */
	public function column_status( $item ) {
		return Doli_Statut::g()->display_status( $item );
	}

	/**
	 * Bulk actions
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
		);
	}
}
