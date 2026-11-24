<?php
/**
 * Hooks of the display mode: page templates, single product, checkout return and my account access.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2026 Eoxia <technique@eoxia.com>.
 * @since     2.7.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Display_Action class.
 */
class Display_Action {

	/**
	 * Prevents appending the page blocks twice (the_content may run several times).
	 *
	 * @var bool
	 */
	private $rendering = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Mode "Nouveaux modèles".
		add_action( 'init', array( $this, 'register_block_templates' ), 20 );
		add_filter( 'theme_page_templates', array( $this, 'add_page_templates' ), 10, 1 );
		add_filter( 'the_content', array( $this, 'append_page_blocks' ), 20 );

		add_action( 'pre_get_posts', array( $this, 'category_main_query' ) );

		// Product catalogue (Query Loop), both modes.
		add_filter( 'get_block_type_variations', array( $this, 'add_catalogue_variation' ), 10, 2 );
		add_filter( 'query_loop_block_query_vars', array( $this, 'catalogue_query_vars' ), 10, 1 );
		add_filter( 'rest_wps-product_query', array( $this, 'catalogue_rest_query' ), 10, 1 );

		// Both modes.
		add_filter( 'render_block_wpshop/checkout-block', array( $this, 'checkout_legacy_steps' ), 10, 1 );
		add_action( 'template_redirect', array( $this, 'redirect_guest_from_my_account' ) );

		// Settings.
		add_action( 'admin_post_wps_update_display_mode', array( $this, 'callback_update_display_mode' ) );
	}

	/**
	 * Registers the WPshop block templates (block themes, WordPress 6.7+).
	 */
	public function register_block_templates() {
		if ( ! Display_Mode::g()->is_templates_mode() || ! Display_Mode::g()->has_block_templates() ) {
			return;
		}

		foreach ( Display_Mode::g()->get_page_templates() as $key => $template ) {
			register_block_template( Display_Mode::TEMPLATE_NAMESPACE . '//' . $template['slug'], array(
				'title'       => $template['title'],
				'description' => $template['description'],
				'content'     => Display_Mode::g()->get_page_block_template_content( $key ),
				'post_types'  => array( 'page' ),
			) );
		}

		register_block_template( Display_Mode::TEMPLATE_NAMESPACE . '//' . Display_Mode::PRODUCT_TEMPLATE, array(
			'title'       => __( 'Single product (WPshop)', 'wpshop' ),
			'description' => __( 'Displays a WPshop product with its image, price and add to cart button.', 'wpshop' ),
			'content'     => Display_Mode::g()->get_product_block_template_content(),
		) );

		register_block_template( Display_Mode::TEMPLATE_NAMESPACE . '//' . Display_Mode::CATEGORY_TEMPLATE, array(
			'title'       => __( 'Product category (WPshop)', 'wpshop' ),
			'description' => __( 'Displays the products of a WPshop category with a Query Loop.', 'wpshop' ),
			'content'     => Display_Mode::g()->get_category_block_template_content(),
		) );
	}

	/**
	 * Category archive of a block theme (mode "Nouveaux modèles"): the main query feeds the
	 * catalogue Query Loop of the template, so it gets the shop rules.
	 *
	 * @param \WP_Query $query The query.
	 */
	public function category_main_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_tax( 'wps-product-cat' ) ) {
			return;
		}

		if ( ! Display_Mode::g()->is_templates_mode() || ! Display_Mode::g()->has_block_templates() ) {
			return;
		}

		$query->set( 'post_type', Product::g()->get_type() );
		$query->set( 'posts_per_page', 12 );

		foreach ( Display_Mode::g()->apply_catalogue_rules( array() ) as $var => $value ) {
			$query->set( $var, $value );
		}
	}

	/**
	 * Adds the "Catalogue de produits (WPshop)" variation to the core Query Loop block.
	 *
	 * @param array          $variations Block variations.
	 * @param \WP_Block_Type $block_type Block type.
	 *
	 * @return array
	 */
	public function add_catalogue_variation( $variations, $block_type ) {
		if ( 'core/query' === $block_type->name ) {
			$variations[] = Display_Mode::g()->get_catalogue_variation();
		}

		return $variations;
	}

	/**
	 * Shop rules on every Query Loop displaying WPshop products (custom queries; loops
	 * inheriting the main query are handled by category_main_query()).
	 *
	 * @param array $query WP_Query arguments built from the Query Loop block.
	 *
	 * @return array
	 */
	public function catalogue_query_vars( $query ) {
		if ( isset( $query['post_type'] ) && Product::g()->get_type() === $query['post_type'] ) {
			$query = Display_Mode::g()->apply_catalogue_rules( $query );
		}

		return $query;
	}

	/**
	 * Same rules for the Query Loop preview in the editor (REST API).
	 *
	 * @param array $args WP_Query arguments of the REST request.
	 *
	 * @return array
	 */
	public function catalogue_rest_query( $args ) {
		return Display_Mode::g()->apply_catalogue_rules( $args );
	}

	/**
	 * Lists the WPshop templates in the page "Template" selector of classic themes.
	 *
	 * Block themes (WordPress 6.7+) list the registered block templates instead.
	 *
	 * @param array $templates Page templates (slug => name).
	 *
	 * @return array
	 */
	public function add_page_templates( $templates ) {
		if ( ! Display_Mode::g()->is_templates_mode() || Display_Mode::g()->has_block_templates() ) {
			return $templates;
		}

		foreach ( Display_Mode::g()->get_page_templates() as $template ) {
			$templates[ $template['slug'] ] = $template['title'];
		}

		return $templates;
	}

	/**
	 * Appends the WPshop blocks to a page using a WPshop template, when the theme cannot
	 * render it as a block template (classic themes, WordPress < 6.7). The theme's own page
	 * template is used, so the shop fits the theme layout.
	 *
	 * @param string $content Page content.
	 *
	 * @return string
	 */
	public function append_page_blocks( $content ) {
		if ( $this->rendering || is_admin() || ! is_page() || Display_Mode::g()->has_block_templates() ) {
			return $content;
		}

		$post = get_post();

		if ( ! $post || (int) $post->ID !== (int) get_queried_object_id() || ! Display_Mode::g()->page_uses_template( $post ) ) {
			return $content;
		}

		$key = Display_Mode::g()->get_page_key_by_template( $post );

		if ( Display_Mode::g()->content_has_page_block( $key, $post ) ) {
			return $content;
		}

		$this->rendering = true;
		$content        .= Display_Mode::g()->render_page_blocks( $key );
		$this->rendering = false;

		return $content;
	}

	/**
	 * The checkout block only handles the form: on the payment return ("received") and on
	 * direct payment links, the legacy views are displayed instead, whatever the mode.
	 *
	 * @param string $block_content Rendered checkout block.
	 *
	 * @return string
	 */
	public function checkout_legacy_steps( $block_content ) {
		global $wp;

		$is_received = array_key_exists( 'type', $wp->query_vars ) && in_array( 'received', $wp->query_vars, true );
		$is_payment  = array_key_exists( 'id', $wp->query_vars );

		if ( ! $is_received && ! $is_payment ) {
			return $block_content;
		}

		ob_start();
		Checkout_Shortcode::g()->callback_checkout( array() );

		return ob_get_clean();
	}

	/**
	 * Sends guests from the my account page to the connection page, before any output
	 * (the account block cannot redirect once the theme has started printing the page).
	 */
	public function redirect_guest_from_my_account() {
		if ( is_user_logged_in() ) {
			return;
		}

		$page_ids      = get_option( 'wps_page_ids', Pages::g()->default_options );
		$account_id    = ! empty( $page_ids['my_account_id'] ) ? (int) $page_ids['my_account_id'] : 0;
		$connection_id = ! empty( $page_ids['connection_id'] ) ? (int) $page_ids['connection_id'] : 0;

		if ( ! $account_id || ! $connection_id || $account_id === $connection_id || ! is_page( $account_id ) ) {
			return;
		}

		if ( 'publish' !== get_post_status( $connection_id ) ) {
			return;
		}

		wp_safe_redirect( get_permalink( $connection_id ) );
		exit;
	}

	/**
	 * Saves the display mode (WPshop > Settings > Pages).
	 */
	public function callback_update_display_mode() {
		check_admin_referer( 'callback_update_display_mode' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}

		$mode   = ! empty( $_POST['display_mode'] ) ? sanitize_key( $_POST['display_mode'] ) : Display_Mode::CLASSIC;
		$report = Display_Mode::g()->set_mode( $mode );

		$message = Display_Mode::TEMPLATES === Display_Mode::g()->get_mode()
			? __( 'Display mode "New templates" enabled.', 'wpshop' )
			: __( 'Display mode "Classic" enabled: the WPshop pages are back to their default template.', 'wpshop' );

		$skipped = array();
		foreach ( $report as $key => $line ) {
			if ( in_array( $line['status'], array( 'custom', 'has_block' ), true ) ) {
				$skipped[] = get_the_title( $line['page_id'] );
			}
		}

		if ( ! empty( $skipped ) ) {
			/* translators: %s: list of page titles. */
			$message .= ' ' . sprintf( __( 'Left untouched (other template or block already in the content): %s.', 'wpshop' ), implode( ', ', $skipped ) );
		}

		set_transient( 'updated_wpshop_option_' . get_current_user_id(), $message, 30 );

		wp_safe_redirect( admin_url( 'admin.php?page=wps-settings&tab=pages' ) );
		exit;
	}
}

new Display_Action();
