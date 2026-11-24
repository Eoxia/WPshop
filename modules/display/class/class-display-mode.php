<?php
/**
 * Display mode of the WPshop front: "Classique" (legacy PHP views) or "Nouveaux modèles"
 * (page templates containing the WPshop blocks, for block and classic themes).
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2026 Eoxia <technique@eoxia.com>.
 * @since     2.7.0
 */

namespace wpshop;

use eoxia\Singleton_Util;

defined( 'ABSPATH' ) || exit;

/**
 * Display_Mode class.
 */
class Display_Mode extends Singleton_Util {

	/**
	 * Option storing the display mode.
	 *
	 * @var string
	 */
	const OPTION = 'wps_display_mode';

	/**
	 * Legacy display: PHP views injected in the pages (default on existing sites).
	 *
	 * @var string
	 */
	const CLASSIC = 'classic';

	/**
	 * New display: page templates containing the WPshop blocks (default on new installs).
	 *
	 * @var string
	 */
	const TEMPLATES = 'templates';

	/**
	 * Prefix of every WPshop page template slug.
	 *
	 * @var string
	 */
	const TEMPLATE_PREFIX = 'wps-';

	/**
	 * Plugin namespace of the block templates (WordPress 6.7+).
	 *
	 * @var string
	 */
	const TEMPLATE_NAMESPACE = 'wpshop';

	/**
	 * Slug of the single product block template.
	 *
	 * @var string
	 */
	const PRODUCT_TEMPLATE = 'single-wps-product';

	/**
	 * Constructor.
	 */
	protected function construct() {}

	/**
	 * Current display mode.
	 *
	 * @return string self::CLASSIC or self::TEMPLATES.
	 */
	public function get_mode() {
		$mode = apply_filters( 'wps_display_mode', get_option( self::OPTION, self::CLASSIC ) );

		return in_array( $mode, array( self::CLASSIC, self::TEMPLATES ), true ) ? $mode : self::CLASSIC;
	}

	/**
	 * Whether the "Nouveaux modèles" mode is active.
	 *
	 * @return bool
	 */
	public function is_templates_mode() {
		return self::TEMPLATES === $this->get_mode();
	}

	/**
	 * Labels and descriptions of the modes, for the settings screen.
	 *
	 * @return array
	 */
	public function get_modes() {
		return array(
			self::CLASSIC   => array(
				'label'       => __( 'Classic', 'wpshop' ),
				'description' => __( 'The shop is displayed by the historical WPshop views, injected in the content of the WPshop pages. Nothing changes on your site.', 'wpshop' ),
			),
			self::TEMPLATES => array(
				'label'       => __( 'New templates', 'wpshop' ),
				'description' => __( 'The WPshop pages use a WPshop template that contains the shop blocks (cart, checkout, my account, connection, single product). Works with every theme; with a block theme, the templates can be customised in the Site Editor.', 'wpshop' ),
			),
		);
	}

	/**
	 * Whether the active theme renders the WPshop templates as block templates.
	 *
	 * Needs a block theme and WordPress 6.7+ (register_block_template()). Otherwise the
	 * blocks are appended to the page content (classic themes, older WordPress).
	 *
	 * @return bool
	 */
	public function has_block_templates() {
		return function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && function_exists( 'register_block_template' );
	}

	/**
	 * WPshop page templates, keyed by the WPshop page option key (see Pages::$default_options).
	 *
	 * @return array {
	 *     @type string $slug        Template slug, stored in the page "_wp_page_template" meta.
	 *     @type string $title       Template name, shown in the page editor.
	 *     @type string $description Template description.
	 *     @type string $block       Main WPshop block displayed by the template.
	 * }
	 */
	public function get_page_templates() {
		$templates = array(
			'cart_id'       => array(
				'slug'        => 'wps-cart',
				'title'       => __( 'Cart (WPshop)', 'wpshop' ),
				'description' => __( 'Displays the WPshop cart below the page content.', 'wpshop' ),
				'block'       => 'wpshop/cart-block',
			),
			'checkout_id'   => array(
				'slug'        => 'wps-checkout',
				'title'       => __( 'Checkout (WPshop)', 'wpshop' ),
				'description' => __( 'Displays the WPshop checkout below the page content.', 'wpshop' ),
				'block'       => 'wpshop/checkout-block',
			),
			'my_account_id' => array(
				'slug'        => 'wps-my-account',
				'title'       => __( 'My account (WPshop)', 'wpshop' ),
				'description' => __( 'Displays the WPshop customer account below the page content.', 'wpshop' ),
				'block'       => 'wpshop/my-account-block',
			),
			'connection_id' => array(
				'slug'        => 'wps-connection',
				'title'       => __( 'Connection (WPshop)', 'wpshop' ),
				'description' => __( 'Displays the WPshop login and registration forms below the page content.', 'wpshop' ),
				'block'       => 'wpshop/connection-block',
			),
		);

		return apply_filters( 'wps_page_templates', $templates );
	}

	/**
	 * Whether a template slug belongs to WPshop.
	 *
	 * @param string $slug Template slug.
	 *
	 * @return bool
	 */
	public function is_wpshop_template( $slug ) {
		return in_array( $slug, wp_list_pluck( $this->get_page_templates(), 'slug' ), true );
	}

	/**
	 * WPshop page key (cart_id, checkout_id…) of the template used by a page.
	 *
	 * @param int|\WP_Post $post The page.
	 *
	 * @return string The page key, or '' if the page does not use a WPshop template.
	 */
	public function get_page_key_by_template( $post ) {
		$slug = get_page_template_slug( $post );

		foreach ( $this->get_page_templates() as $key => $template ) {
			if ( $template['slug'] === $slug ) {
				return $key;
			}
		}

		return '';
	}

	/**
	 * Whether a page is displayed by a WPshop template (mode "Nouveaux modèles" only).
	 *
	 * @param int|\WP_Post $post The page.
	 *
	 * @return bool
	 */
	public function page_uses_template( $post ) {
		return $this->is_templates_mode() && '' !== $this->get_page_key_by_template( $post );
	}

	/**
	 * Blocks markup displayed for a WPshop page (same structure as the block editor templates).
	 *
	 * @param string $page_key WPshop page key (cart_id, checkout_id, my_account_id, connection_id).
	 *
	 * @return string Serialized blocks.
	 */
	public function get_page_blocks( $page_key ) {
		switch ( $page_key ) {
			case 'cart_id':
				$markup = '<!-- wp:wpshop/cart-block -->'
					. $this->columns(
						array( '66.66%', '<!-- wp:wpshop/cart-products /-->' ),
						array( '33.33%', '<!-- wp:wpshop/cart-summary /-->' . "\n\n" . '<!-- wp:wpshop/cart-checkout-button /-->' )
					)
					. "\n\n" . '<!-- wp:wpshop/cart-empty -->' . "\n"
					. '<!-- wp:paragraph -->' . "\n" . '<p>' . esc_html__( 'Empty cart', 'wpshop' ) . '</p>' . "\n" . '<!-- /wp:paragraph -->' . "\n"
					. '<!-- /wp:wpshop/cart-empty -->'
					. '<!-- /wp:wpshop/cart-block -->';
				break;
			case 'checkout_id':
				$markup = '<!-- wp:wpshop/checkout-block -->'
					. $this->columns(
						array( '66.66%', '<!-- wp:wpshop/checkout-form-block /-->' ),
						array( '33.33%', '<!-- wp:wpshop/checkout-order-details-block /-->' . "\n\n" . '<!-- wp:wpshop/checkout-acceptance-block /-->' . "\n\n" . '<!-- wp:wpshop/checkout-button-block /-->' )
					)
					. '<!-- /wp:wpshop/checkout-block -->';
				break;
			case 'my_account_id':
				$markup = '<!-- wp:wpshop/my-account-block -->'
					. $this->columns(
						array( '33.33%', '<!-- wp:wpshop/account-navigation /-->' ),
						array( '66.66%', '<!-- wp:wpshop/account-details /-->' . "\n\n" . '<!-- wp:wpshop/account-orders /-->' )
					)
					. '<!-- /wp:wpshop/my-account-block -->';
				break;
			case 'connection_id':
				$markup = '<!-- wp:wpshop/connection-block /-->';
				break;
			default:
				$markup = '';
				break;
		}

		/**
		 * Filters the blocks displayed by a WPshop page template.
		 *
		 * @param string $markup   Serialized blocks.
		 * @param string $page_key WPshop page key.
		 */
		return apply_filters( 'wps_page_template_blocks', $markup, $page_key );
	}

	/**
	 * Blocks markup of the single product.
	 *
	 * @param bool $with_title Include the product title block (not in classic themes, which already print it).
	 *
	 * @return string Serialized blocks.
	 */
	public function get_product_blocks( $with_title = true ) {
		$inner = array();

		if ( $with_title ) {
			$inner[] = '<!-- wp:wpshop/product-title /-->';
		}

		$inner[] = '<!-- wp:wpshop/product-description /-->';
		$inner[] = '<!-- wp:wpshop/product-image /-->';
		$inner[] = '<!-- wp:wpshop/product-price /-->';
		$inner[] = '<!-- wp:wpshop/product-add-cart /-->';

		$markup = '<!-- wp:wpshop/product-block -->'
			. '<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group alignwide">' . implode( "\n\n", $inner ) . '</div>' . "\n"
			. '<!-- /wp:group -->'
			. '<!-- /wp:wpshop/product-block -->';

		/**
		 * Filters the blocks displayed on the single product.
		 *
		 * @param string $markup     Serialized blocks.
		 * @param bool   $with_title Whether the title block is included.
		 */
		return apply_filters( 'wps_product_template_blocks', $markup, $with_title );
	}

	/**
	 * Content of a page block template (header, title, page content, WPshop blocks, footer).
	 *
	 * @param string $page_key WPshop page key.
	 *
	 * @return string
	 */
	public function get_page_block_template_content( $page_key ) {
		return $this->wrap_block_template(
			'<!-- wp:post-title {"level":1} /-->' . "\n\n"
			. '<!-- wp:post-content {"layout":{"type":"constrained"}} /-->' . "\n\n"
			. '<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->' . "\n"
			. '<div class="wp-block-group alignwide">' . $this->get_page_blocks( $page_key ) . '</div>' . "\n"
			. '<!-- /wp:group -->'
		);
	}

	/**
	 * Content of the single product block template.
	 *
	 * @return string
	 */
	public function get_product_block_template_content() {
		return $this->wrap_block_template( $this->get_product_blocks( true ) );
	}

	/**
	 * Renders the blocks of a WPshop page.
	 *
	 * @param string $page_key WPshop page key.
	 *
	 * @return string HTML.
	 */
	public function render_page_blocks( $page_key ) {
		$markup = $this->get_page_blocks( $page_key );

		return '' !== $markup ? '<div class="wps-page-blocks">' . do_blocks( $markup ) . '</div>' : '';
	}

	/**
	 * Assigns the WPshop templates to the WPshop pages.
	 *
	 * A page is left untouched when it uses another template chosen by the admin, or when its
	 * content already contains the WPshop block (it would be displayed twice). Pages that are
	 * no longer WPshop pages lose their WPshop template.
	 *
	 * @return array Report keyed by page key: array( 'status' => applied|already|custom|has_block|missing, 'page_id' => int ).
	 */
	public function apply_page_templates() {
		$page_ids = get_option( 'wps_page_ids', Pages::g()->default_options );
		$report   = array();
		$kept     = array();

		foreach ( $this->get_page_templates() as $key => $template ) {
			$page_id = ! empty( $page_ids[ $key ] ) ? (int) $page_ids[ $key ] : 0;
			$page    = $page_id ? get_post( $page_id ) : null;

			if ( ! $page || 'page' !== $page->post_type || 'trash' === $page->post_status ) {
				$report[ $key ] = array( 'status' => 'missing', 'page_id' => $page_id );
				continue;
			}

			$current = get_page_template_slug( $page );

			if ( $template['slug'] === $current ) {
				$status = 'already';
			} elseif ( '' !== $current && 'default' !== $current && ! $this->is_wpshop_template( $current ) ) {
				$status = 'custom';
			} elseif ( has_block( $template['block'], $page ) ) {
				$status = 'has_block';
			} else {
				update_post_meta( $page->ID, '_wp_page_template', $template['slug'] );
				$status = 'applied';
			}

			if ( in_array( $status, array( 'already', 'applied' ), true ) ) {
				$kept[] = $page->ID;
			}

			$report[ $key ] = array( 'status' => $status, 'page_id' => $page->ID );
		}

		$this->remove_page_templates( $kept );

		return $report;
	}

	/**
	 * Removes the WPshop templates from the pages (they go back to the default template).
	 *
	 * @param int[] $except_ids Pages to leave untouched.
	 *
	 * @return int Number of pages changed.
	 */
	public function remove_page_templates( $except_ids = array() ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => wp_list_pluck( $this->get_page_templates(), 'slug' ),
			'meta_compare'   => 'IN',
			'post__not_in'   => array_map( 'intval', $except_ids ),
		) );

		foreach ( $pages as $page_id ) {
			delete_post_meta( $page_id, '_wp_page_template' );
		}

		return count( $pages );
	}

	/**
	 * Changes the display mode and assigns or removes the page templates accordingly.
	 *
	 * @param string $mode self::CLASSIC or self::TEMPLATES.
	 *
	 * @return array Report of apply_page_templates() (empty when switching to classic).
	 */
	public function set_mode( $mode ) {
		$mode = self::TEMPLATES === $mode ? self::TEMPLATES : self::CLASSIC;

		update_option( self::OPTION, $mode );

		if ( self::TEMPLATES === $mode ) {
			return $this->apply_page_templates();
		}

		$this->remove_page_templates();

		return array();
	}

	/**
	 * Core columns block markup (as saved by the block editor).
	 *
	 * @param array ...$columns Each column: array( width, inner blocks markup ).
	 *
	 * @return string
	 */
	private function columns( ...$columns ) {
		$markup = '<!-- wp:columns -->' . "\n" . '<div class="wp-block-columns">';

		foreach ( $columns as $column ) {
			$markup .= '<!-- wp:column {"width":"' . $column[0] . '"} -->' . "\n"
				. '<div class="wp-block-column" style="flex-basis:' . $column[0] . '">' . $column[1] . '</div>' . "\n"
				. '<!-- /wp:column -->' . "\n\n";
		}

		return rtrim( $markup ) . '</div>' . "\n" . '<!-- /wp:columns -->' . "\n\n";
	}

	/**
	 * Wraps blocks with the theme header and footer template parts.
	 *
	 * @param string $inner Blocks of the main area.
	 *
	 * @return string
	 */
	private function wrap_block_template( $inner ) {
		return '<!-- wp:template-part {"slug":"header","tagName":"header"} /-->' . "\n\n"
			. '<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->' . "\n"
			. '<main class="wp-block-group">' . $inner . '</main>' . "\n"
			. '<!-- /wp:group -->' . "\n\n"
			. '<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->';
	}
}

Display_Mode::g();
