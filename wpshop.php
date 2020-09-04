<?php
/**
 * Plugin Name: WPshop 2
 * Plugin URI:  https://wpshop.fr/
 * Description: Simple, fast, efficient it will transform your WordPress into an internet sales site
 * Version:     2.1.0
 * Author:      Eoxia <dev@eoxia.com>
 * Author URI:  http://www.eoxia.com/
 * License:     GPLv3
 * License URI: https://spdx.org/licenses/GPL-3.0-or-later.html
 * Domain Path: /core/assets/languages
 * Text Domain: wpshop
 *
 * @package WPshop
 */

namespace wpshop;

use eoxia\Init_Util;

defined( 'ABSPATH' ) || exit;

DEFINE( 'PLUGIN_WPSHOP_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_WPSHOP_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_WPSHOP_DIR', basename( __DIR__ ) );
DEFINE( 'PLUGIN_WPSHOP_DEV_MODE', false );

if ( ! PLUGIN_WPSHOP_DEV_MODE ) {
	require_once 'core/external/eo-framework/eo-framework.php';
}

// Include composer component.
require_once 'vendor/autoload.php';

// Boot your plugin.
Init_Util::g()->exec( PLUGIN_WPSHOP_PATH, basename( __FILE__, '.php' ) );

add_filter('site_transient_update_plugins',
	function ($value) {
		if ( $value->checked[plugin_basename(__FILE__)] <= "1.6.4" ) {
			unset( $value->response[plugin_basename(__FILE__)] );
		}
			return $value;
	}
);
