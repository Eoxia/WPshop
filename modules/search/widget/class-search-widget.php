<?php
/**
 * Le widget pour la recherche.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Search Widget Class.
 */
class Search_Widget extends \WP_Widget {

	/**
	 * Initialise le Widget.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'search',
			'description' => __( 'Search a product', 'wpshop' ),
		);

		parent::__construct( 'wps-search-product', __( 'Search a product', 'wpshop' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @since 2.0.0
	 *
	 * @param array $args     Les paramètres du widget.
	 * @param array $instance Les données du widget.
	 */
	public function widget( $args, $instance ) {
		\eoxia\View_Util::exec( 'wpshop', 'search', 'search-field', array(
			'args'     => $args,
			'instance' => $instance,
		) );
	}

	/**
	 * Le formulaire pour configurer le widget
	 *
	 * @since 2.0.0
	 *
	 * @param  array $instance Les données du widget.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'wpshop' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'wpshop' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @since 2.0.0
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
}

new Search_Widget();
