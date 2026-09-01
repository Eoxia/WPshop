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
		add_filter( 'register_taxonomy_args', array( $this, 'force_taxonomy_capabilities' ), 99, 2 );
		add_filter( 'the_content', array( $this, 'display_content_grid_product' ) );
		add_filter( 'the_content', array( $this, 'display_single_page_product' ) );

		add_filter( 'wps_product_add_to_cart_attr', array( $this, 'button_add_to_cart_tooltip' ), 10, 2 );
		add_filter( 'wps_product_add_to_cart_class', array( $this, 'disable_button_add_to_cart' ), 10, 2 );
		add_filter( 'wps_product_single', array( $this, 'display_stock' ), 10, 2 );

		// Tool to delete empty categories (from Settings > Categories)
		add_action( 'admin_post_tool_delete_empty_categories', array( $this, 'tool_delete_empty_categories' ) );
		add_action( 'admin_post_confirm_delete_empty_categories', array( $this, 'process_confirm_delete_empty_categories' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_delete_empty' ) );

		// Taxonomy UI customization
		add_filter( 'wps-product-cat_row_actions', array( $this, 'custom_category_row_actions' ), 10, 2 );
		add_action( 'admin_head-edit-tags.php', array( $this, 'hide_add_category_form' ) );
		add_filter( 'pre_insert_term', array( $this, 'prevent_manual_category_creation' ), 10, 2 );
		add_filter( 'manage_edit-wps-product-cat_columns', array( $this, 'add_ids_column' ) );
		add_action( 'manage_wps-product-cat_custom_column', array( $this, 'render_ids_column' ), 10, 3 );

		// AJAX action to update slugs inline
		add_action( 'wp_ajax_wps_update_category_slug', array( $this, 'ajax_update_category_slug' ) );

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
				'edit_terms'   => 'manage_categories', // Allow editing the slug
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

	/**
	 * Customizes the row actions for the categories.
	 */
	public function custom_category_row_actions( $actions, $tag ) {
		if ( ! Settings::g()->dolibarr_is_active() ) {
			return $actions;
		}

		$new_actions = array();

		// "Voir"
		$view_link = get_term_link( $tag );
		if ( ! is_wp_error( $view_link ) ) {
			$new_actions['view'] = sprintf( '<a href="%s">%s</a>', esc_url( $view_link ), __( 'Voir', 'wpshop' ) );
		}

		// "Voir sur Dolibarr"
		$external_id = get_term_meta( $tag->term_id, '_external_id', true );
		if ( ! empty( $external_id ) ) {
			$wps_dolibarr = get_option( 'wps_dolibarr' );
			$dolibarr_url = ! empty( $wps_dolibarr['dolibarr_url'] ) ? rtrim( $wps_dolibarr['dolibarr_url'], '/' ) : '';
			if ( $dolibarr_url ) {
				$doli_link = $dolibarr_url . '/categories/viewcat.php?id=' . $external_id . '&type=product';
				$new_actions['view_dolibarr'] = sprintf( '<a href="%s" target="_blank" style="color: #d63638;">%s</a>', esc_url( $doli_link ), __( 'Voir sur Dolibarr', 'wpshop' ) );
			}
		}

		return $new_actions;
	}

	/**
	 * Hides the "Add New Category" form and replaces Slugs with input fields.
	 */
	public function hide_add_category_form() {
		global $current_screen;
		if ( isset( $current_screen->id ) && 'edit-wps-product-cat' === $current_screen->id && Settings::g()->dolibarr_is_active() ) {
			echo '<style>
				#col-left { display: none !important; }
				#col-right { width: 100% !important; }
				.wp-list-table .column-slug { width: 25%; }
				.wps-slug-input { width: 100%; box-sizing: border-box; }
			</style>';
			echo '<script>
				jQuery(document).ready(function($) {
					// Transform text slugs into input fields
					$(".wp-list-table tbody tr").each(function() {
						var $row = $(this);
						var termId = $row.attr("id");
						if (!termId) return;
						termId = termId.replace("tag-", "");
						var $slugCol = $row.find(".column-slug");
						var slugText = $slugCol.text().trim();
						if (slugText.length > 0 && slugText !== "-") {
							$slugCol.html("<input type=\'text\' value=\'" + slugText + "\' class=\'wps-slug-input\' data-term-id=\'" + termId + "\'>");
						}
					});

					// Handle inline save on blur or enter
					$(document).on("blur keyup", ".wps-slug-input", function(e) {
						if (e.type === "keyup" && e.keyCode !== 13) return; // Only trigger on Enter key

						var $input = $(this);
						var termId = $input.data("term-id");
						var newSlug = $input.val().trim();
						var oldSlug = $input.attr("data-old-slug") || $input.prop("defaultValue");

						if (newSlug === oldSlug) return;

						$input.prop("disabled", true).css("opacity", "0.5");

						$.post(ajaxurl, {
							action: "wps_update_category_slug",
							term_id: termId,
							slug: newSlug,
							_ajax_nonce: "' . wp_create_nonce( 'wps_update_slug' ) . '"
						}, function(response) {
							$input.prop("disabled", false).css("opacity", "1");
							if (response.success) {
								$input.val(response.data.slug);
								$input.attr("data-old-slug", response.data.slug);
								// Flash green
								$input.css({"transition": "border 0.3s", "border": "2px solid #46b450", "box-shadow": "0 0 5px #46b450"});
								setTimeout(function() {
									$input.css({"border": "", "box-shadow": ""});
								}, 1500);
							} else {
								alert(response.data || "Erreur lors de la sauvegarde du slug.");
								$input.val(oldSlug); // revert
							}
						}).fail(function() {
							$input.prop("disabled", false).css("opacity", "1");
							alert("Erreur serveur lors de la sauvegarde.");
							$input.val(oldSlug);
						});
					});
				});
			</script>';
		}
	}

	/**
	 * AJAX handler to update category slug inline.
	 */
	public function ajax_update_category_slug() {
		check_ajax_referer( 'wps_update_slug' );

		if ( ! current_user_can( 'manage_categories' ) ) {
			wp_send_json_error( __( 'Droits insuffisants.', 'wpshop' ) );
		}

		$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
		$new_slug = isset( $_POST['slug'] ) ? sanitize_title( wp_unslash( $_POST['slug'] ) ) : '';

		if ( $term_id <= 0 || empty( $new_slug ) ) {
			wp_send_json_error( __( 'DonnÃ©es invalides.', 'wpshop' ) );
		}

		$term = get_term( $term_id, 'wps-product-cat' );
		if ( is_wp_error( $term ) || ! $term ) {
			wp_send_json_error( __( 'CatÃ©gorie introuvable.', 'wpshop' ) );
		}

		if ( $term->slug === $new_slug ) {
			wp_send_json_success( array( 'slug' => $term->slug ) );
		}

		$result = wp_update_term( $term_id, 'wps-product-cat', array(
			'slug' => $new_slug,
		) );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		$updated_term = get_term( $term_id, 'wps-product-cat' );
		wp_send_json_success( array( 'slug' => $updated_term->slug ) );
	}

	/**
	 * Prevents manual creation of categories from the WP UI.
	 */
	public function prevent_manual_category_creation( $term, $taxonomy ) {
		if ( 'wps-product-cat' === $taxonomy && Settings::g()->dolibarr_is_active() ) {
			if ( isset( $_POST['action'] ) && 'add-tag' === $_POST['action'] ) {
				return new \WP_Error( 'not_allowed', __( 'La crÃ©ation de catÃ©gories est pilotÃ©e par Dolibarr.', 'wpshop' ) );
			}
		}
		return $term;
	}

	/**
	 * Adds the IDs column to the categories table.
	 */
	public function add_ids_column( $columns ) {
		// Insert before the 'posts' (Total) column if it exists
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			if ( 'posts' === $key ) {
				$new_columns['wps_doli_ids'] = __( 'IDs (WP / Doli)', 'wpshop' );
			}
			$new_columns[ $key ] = $value;
		}
		if ( ! isset( $new_columns['wps_doli_ids'] ) ) {
			$new_columns['wps_doli_ids'] = __( 'IDs (WP / Doli)', 'wpshop' );
		}
		return $new_columns;
	}

	/**
	 * Renders the IDs column content.
	 */
	public function render_ids_column( $content, $column_name, $term_id ) {
		if ( 'wps_doli_ids' === $column_name ) {
			$html = '<div style="display: flex; gap: 5px; flex-direction: column;">';
			
			// WPShop ID
			$html .= sprintf( '<span style="display:inline-block; padding: 2px 6px; background: #0073aa; color: #fff; border-radius: 3px; font-size: 11px;">WP: %d</span>', $term_id );
			
			// Dolibarr ID
			$external_id = get_term_meta( $term_id, '_external_id', true );
			if ( ! empty( $external_id ) ) {
				$wps_dolibarr = get_option( 'wps_dolibarr' );
				$dolibarr_url = ! empty( $wps_dolibarr['dolibarr_url'] ) ? rtrim( $wps_dolibarr['dolibarr_url'], '/' ) : '';
				if ( $dolibarr_url ) {
					$doli_link = $dolibarr_url . '/categories/viewcat.php?id=' . $external_id . '&type=product';
					$html .= sprintf( '<a href="%s" target="_blank" style="display:inline-block; padding: 2px 6px; background: #d63638; color: #fff; border-radius: 3px; font-size: 11px; text-decoration: none;">Doli: %s</a>', esc_url( $doli_link ), esc_html( $external_id ) );
				} else {
					$html .= sprintf( '<span style="display:inline-block; padding: 2px 6px; background: #d63638; color: #fff; border-radius: 3px; font-size: 11px;">Doli: %s</span>', esc_html( $external_id ) );
				}
			}
			$html .= '</div>';
			return $html;
		}
		return $content;
	}

	/**
	 * Force les capacités de la taxonomie après son enregistrement (évite l'écrasement par le framework)
	 *
	 * @param array  $args     Les arguments de la taxonomie.
	 * @param string $taxonomy Le nom de la taxonomie.
	 * @return array
	 */
	public function force_taxonomy_capabilities( $args, $taxonomy ) {
		if ( 'wps-product-cat' === $taxonomy && Settings::g()->dolibarr_is_active() ) {
			$args['capabilities'] = array(
				'manage_terms' => 'manage_categories', // Permet de voir la liste
				'edit_terms'   => 'manage_categories', // Rétabli pour modifier le slug
				'delete_terms' => 'do_not_allow',      // Interdit suppression
				'assign_terms' => 'edit_posts',        // Permet l'assignation
			);
		}
		return $args;
	}
}

new Product_Filter();
