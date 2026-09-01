<?php
/**
 * La classe gérant les filtres des produits.
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
 * Product Filter Class.
 */
class Product_Filter {

	/**
	 * Le constructeur.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_wps-product_register_post_type_args', array( $this, 'callback_register_post_type_args' ) );
		add_filter( 'eo_model_wps-product_wps-product-cat', array( $this, 'callback_taxonomy' ) );
		add_filter( 'the_content', array( $this, 'display_content_grid_product' ) );
		add_filter( 'the_content', array( $this, 'display_single_page_product' ) );

		add_filter( 'wps_product_add_to_cart_attr', array( $this, 'button_add_to_cart_tooltip' ), 10, 2 );
		add_filter( 'wps_product_add_to_cart_class', array( $this, 'disable_button_add_to_cart' ), 10, 2 );
		add_filter( 'wps_product_single', array( $this, 'display_stock' ), 10, 2 );

		// Tool to delete empty categories (from Settings > Categories)
		add_action( 'admin_post_tool_delete_empty_categories', array( $this, 'tool_delete_empty_categories' ) );
		add_action( 'admin_post_confirm_delete_empty_categories', array( $this, 'process_confirm_delete_empty_categories' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_delete_empty' ) );

		add_filter( 'eo_model_wps-product_after_get', function( $object, $args ) {
			$object->data['thumbnail'] = wp_get_attachment_image_src( $object->data['thumbnail_id'], 'wps-product-thumbnail' );

			if ( empty( $object->data['thumbnail'] ) ) {
				$object->data['thumbnail'] = array();
				$object->data['thumbnail'][] = home_url( 'wp-content/plugins/wpshop/core/asset/image/default-product-thumbnail.jpg' );
			}

			$object->data['thumbnail_url'] = $object->data['thumbnail'][0];

			return $object;
		}, 10, 2 );
	}

	/**
	 * Permet d'ajouter l'argument public à true pour le register_post_type de EOModel.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $args Les arguments pour le register_post_type.
	 *
	 * @return array       Les arguments pour le register_post_type avec public à true.
	 */
	public function callback_register_post_type_args( $args ) {
		$labels = array(
			'name'               => _x( 'Products', 'post type general name', 'wpshop' ),
			'singular_name'      => _x( 'Product', 'post type singular name', 'wpshop' ),
			'menu_name'          => _x( 'Products', 'admin menu', 'wpshop' ),
			'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'wpshop' ),
			'add_new'            => _x( 'Add New', 'product', 'wpshop' ),
			'add_new_item'       => __( 'Add New Product', 'wpshop' ),
			'new_item'           => __( 'New Product', 'wpshop' ),
			'edit_item'          => __( 'Edit Product', 'wpshop' ),
			'view_item'          => __( 'View Product', 'wpshop' ),
			'all_items'          => __( 'All Products', 'wpshop' ),
			'search_items'       => __( 'Search Products', 'wpshop' ),
			'parent_item_colon'  => __( 'Parent Products:', 'wpshop' ),
			'not_found'          => __( 'No products found.', 'wpshop' ),
			'not_found_in_trash' => __( 'No products found in Trash.', 'wpshop' ),
		);

		$supports = array(
			'thumbnail'
		);

		if ( ! Settings::g()->dolibarr_is_active() ) {
			// If dolibarr is active, it will controle the data of the product.
			$supports[] = 'title';
			$supports[] = 'editor';
		}

		$args['labels']            = $labels;
		$args['supports']          = $supports;
		$args['public']            = true;
		$args['has_archive']       = false;
		$args['show_ui']           = true;
		$args['show_in_nav_menus'] = false;
		$args['show_in_menu']      = false;
		$args['show_in_admin_bar'] = true;
		$args['rewrite']           = array(
			'slug' => __( 'product', 'wpshop' ),
		);

		$args['show_in_rest'] = true;

		if ( Settings::g()->dolibarr_is_active() ) {
			$args['capabilities'] = array(
				'create_posts' => 'do_not_allow',
			);

			$args['map_meta_cap'] = true;
		}

		$args['register_meta_box_cb'] = array( Product::g(), 'callback_register_meta_box' );

		flush_rewrite_rules();
		return $args;
	}

	/**
	 * Entregistre la taxonomy catégorie de produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  array $args Les données à filtrer.
	 *
	 * @return array       Les données filtrées.
	 */
	public function callback_taxonomy( $args ) {
		$labels = array(
			'name'              => _x( 'Product category', 'taxonomy general name', 'wpshop' ),
			'singular_name'     => _x( 'Product category', 'taxonomy singular name', 'wpshop' ),
			'search_items'      => __( 'Search Products category', 'wpshop' ),
			'all_items'         => __( 'All Products category', 'wpshop' ),
			'parent_item'       => __( 'Parent Product category', 'wpshop' ),
			'parent_item_colon' => __( 'Parent Product: category', 'wpshop' ),
			'edit_item'         => __( 'Edit Product category', 'wpshop' ),
			'update_item'       => __( 'Update Product category', 'wpshop' ),
			'add_new_item'      => __( 'Add with Dolibarr', 'wpshop' ),
			'new_item_name'     => __( 'New Product  categoryName', 'wpshop' ),
			'menu_name'         => __( 'Product category', 'wpshop' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => __( 'wps-product-cat', 'wpshop' ),
			),
		);
		
		if ( Settings::g()->dolibarr_is_active() ) {
			$args['capabilities'] = array(
				'manage_terms' => 'manage_categories', // Allow seeing the list
				'edit_terms'   => 'do_not_allow',      // Prevent editing & adding
				'delete_terms' => 'do_not_allow',      // Prevent deleting
				'assign_terms' => 'edit_posts',        // Allow assigning to products
			);
		}

		$args['register_meta_box_cb'] = array( Product::g(), 'callback_register_meta_box' );
		return $args;
	}

	/**
	 * Affiche la grille des produits sur les pages concernées.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $content le contenu de la page.
	 *
	 * @return string          le contenu de la page.
	 */
	public function display_content_grid_product( $content ) {
		if ( ! is_admin() ) {

			global $post;
			global $wp_query;

			$page_ids_options     = get_option( 'wps_page_ids', Pages::g()->default_options );

			if ( is_object( $post ) && $post->ID === $page_ids_options['shop_id'] ) {
				$args = array(
					'post_type'   => 'wps-product',
					'paged'       => get_query_var('paged') ? get_query_var('paged') : 1,
					'post_parent' => 0,
					'post_status' => 'any',
				);

				if ( Settings::g()->dolibarr_is_active() ) {
					$args['meta_key']     = '_external_id';
					$args['meta_compare'] = '!=';
					$args['meta_value']   = 0;
				}

				$wps_query = new \WP_Query( $args );

				foreach ( $wps_query->posts as $key => &$product ) {
					// Sync product
					$product = apply_filters('wps_product_filter_sync', $product);
				}

				$args['post_status'] = 'publish';

				$wps_query = new \WP_Query( $args );

				foreach ( $wps_query->posts as $key => &$product ) {
					$product->price_ttc = get_post_meta($product->ID, '_price_ttc', true);
					$product->manage_stock = get_post_meta($product->ID, '_manage_stock', true);
					$product->stock = get_post_meta($product->ID, '_stock', true);
				}

				ob_start();
				include( Template_Util::get_template_part( 'products', 'wps-product-grid-container' ) );
				$view = ob_get_clean();

				$content .= $view;
			}
		}
		return $content;
	}

	/**
	 * Affiche la page single des produits.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string $content Le contenu de la page.
	 *
	 * @return string          Le contenu de la page.
	 */
	public function display_single_page_product( $content ) {
		global $post;
		global $wp_query;

		if ( is_singular( Product::g()->get_type() ) ) {
			$product = Product::g()->get( array( 'id' => get_the_ID() ), true );

			ob_start();
			include( Template_Util::get_template_part( 'products', 'wps-product-single' ) );
			$view = ob_get_clean();

			$content = $view;
		}

		return $content;
	}

	/**
	 * Ajoute le message "Rupture de stock" sur le produit.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $attr    Attribut du bouton.
	 * @param  Product $product Les données du produit.
	 *
	 * @return string          Attribut du bouton.
	 */
	public function button_add_to_cart_tooltip( $attr, $product ) {
		if ( $product->data['manage_stock'] && 0 >= $product->data['stock'] ) {
			$attr .= 'aria-label="' . __( 'Sold out', 'wpshop' ) . '"';
		}

		return $attr;
	}

	/**
	 * Rend le bouton "Ajouter au panier" grisé.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $class   Les classes du bouton.
	 * @param  Product $product Les données du produit.
	 *
	 * @return string          Les classes du bouton.
	 */
	public function disable_button_add_to_cart( $class, $product ) {
		if ( $product->data['manage_stock'] && 0 >= $product->data['stock'] ) {
			$class = 'button-disable wpeo-tooltip-event button-event';
		}

		return $class;
	}

	/**
	 * Affichage du stock.
	 *
	 * @since   2.0.0
	 * @version 2.0.0
	 *
	 * @param  string  $content Le contenu.
	 * @param  Product $product Les données du produit.
	 *
	 * @return string           Le contenu modifié.
	 */
	public function display_stock( $content, $product ) {
		if ( $product->data['manage_stock'] ) {
			ob_start();
			include( Template_Util::get_template_part( 'products', 'wps-product-stock' ) );
			$content .= ob_get_clean();
		}

		return $content;
	}

	/**
	 * Gère l'action de l'outil de suppression des catégories vides depuis les réglages.
	 */
	public function tool_delete_empty_categories() {
		if ( ! current_user_can( 'manage_categories' ) ) {
			wp_die( __( 'Vous n\'avez pas les droits suffisants.', 'wpshop' ) );
		}

		check_admin_referer( 'wps_tool_delete_empty_categories' );

		$redirect_to = admin_url( 'admin-post.php?action=wps_load_settings_tab&tab=categories' );

		// RÃ©cupÃ©rer TOUTES les catÃ©gories de produits
		$all_terms = get_terms( array(
			'taxonomy'   => 'wps-product-cat',
			'hide_empty' => false,
		) );

		$to_delete = array();
		if ( ! is_wp_error( $all_terms ) ) {
			foreach ( $all_terms as $term ) {
				$objects_in_term = get_objects_in_term( $term->term_id, 'wps-product-cat' );
				$children = get_term_children( $term->term_id, 'wps-product-cat' );
				
				if ( empty( $objects_in_term ) && empty( $children ) ) {
					$to_delete[] = $term;
				}
			}
		}

		if ( empty( $to_delete ) ) {
			wp_redirect( add_query_arg( 'bulk_empty_categories_deleted', 0, $redirect_to ) );
			exit;
		}

		// Affiche l'Ã©cran de confirmation
		$form_action = admin_url( 'admin-post.php' );
		
		$html  = '<h1>' . __( 'Confirmation de suppression', 'wpshop' ) . '</h1>';
		$html .= '<p>' . __( 'Les catÃ©gories suivantes sont vides et vont Ãªtre supprimÃ©es dÃ©finitivement :', 'wpshop' ) . '</p>';
		$html .= '<ul>';
		$term_ids_to_delete = array();
		foreach ( $to_delete as $term ) {
			$html .= '<li><strong>' . esc_html( $term->name ) . '</strong></li>';
			$term_ids_to_delete[] = $term->term_id;
		}
		$html .= '</ul>';

		$html .= '<form id="delete-empty-categories-form" method="post" action="' . esc_url( $form_action ) . '">';
		$html .= '<input type="hidden" name="action" value="confirm_delete_empty_categories">';
		$html .= '<input type="hidden" name="term_ids" value="' . esc_attr( implode( ',', $term_ids_to_delete ) ) . '">';
		$html .= wp_nonce_field( 'delete_empty_categories_nonce', '_wpnonce', true, false );
		$html .= '<input type="hidden" name="redirect_to" value="' . esc_attr( $redirect_to ) . '">';
		
		$html .= '<div style="margin-top: 20px; display: flex; align-items: center; gap: 10px;">';
		$html .= get_submit_button( __( 'Confirmer la suppression', 'wpshop' ), 'primary', 'submit', false );
		$html .= '<a id="delete-cancel-btn" href="' . esc_url( $redirect_to ) . '" class="button">' . __( 'Annuler', 'wpshop' ) . '</a>';
		$html .= '<span id="delete-loader" style="display:none; align-items: center; gap: 5px; color: #666;">';
		$html .= '<img src="' . admin_url( 'images/spinner.gif' ) . '" alt="loading" /> ' . __( 'Suppression en cours...', 'wpshop' );
		$html .= '</span>';
		$html .= '</div>';
		$html .= '</form>';

		$html .= '<script>
			document.getElementById("delete-empty-categories-form").addEventListener("submit", function(e) {
				var btn = document.getElementById("submit");
				btn.disabled = true;
				btn.value = "' . esc_js( __( 'Veuillez patienter...', 'wpshop' ) ) . '";
				document.getElementById("delete-loader").style.display = "inline-flex";
				document.getElementById("delete-cancel-btn").style.display = "none";
			});
		</script>';

		wp_die( $html, __( 'Confirmer la suppression', 'wpshop' ), array( 'back_link' => true ) );
	}

	/**
	 * Traite la soumission du formulaire de confirmation.
	 */
	public function process_confirm_delete_empty_categories() {
		check_admin_referer( 'delete_empty_categories_nonce' );

		if ( ! current_user_can( 'manage_categories' ) ) {
			wp_die( __( 'Vous n\'avez pas les droits suffisants.', 'wpshop' ) );
		}

		$term_ids_str = isset( $_POST['term_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['term_ids'] ) ) : '';
		$redirect_to  = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';
		
		if ( empty( $redirect_to ) ) {
			$redirect_to = admin_url( 'edit-tags.php?taxonomy=wps-product-cat&post_type=wps-product' );
		}

		$deleted = 0;
		if ( ! empty( $term_ids_str ) ) {
			$term_ids = explode( ',', $term_ids_str );
			foreach ( $term_ids as $term_id ) {
				$term_id = intval( $term_id );
				if ( $term_id > 0 ) {
					$term = get_term( $term_id, 'wps-product-cat' );
					if ( ! is_wp_error( $term ) ) {
						error_log( 'WPShop: Catégorie vide supprimée - ID: ' . $term->term_id . ' Nom: ' . $term->name );
						wp_delete_term( $term_id, 'wps-product-cat' );
						$deleted++;
					}
				}
			}
		}

		wp_redirect( add_query_arg( 'bulk_empty_categories_deleted', $deleted, $redirect_to ) );
		exit;
	}

	/**
	 * Affiche une notice d'administration suite à la suppression des catégories vides.
	 */
	public function admin_notice_delete_empty() {
		if ( ! empty( $_REQUEST['bulk_empty_categories_deleted'] ) ) {
			$count = intval( $_REQUEST['bulk_empty_categories_deleted'] );
			printf(
				'<div id="message" class="updated notice is-dismissible"><p>%s</p></div>',
				sprintf(
					/* translators: %s: Number of categories deleted */
					_n( '%s catégorie vide supprimée.', '%s catégories vides supprimées.', $count, 'wpshop' ),
					$count
				)
			);
		}
	}
}

new Product_Filter();
