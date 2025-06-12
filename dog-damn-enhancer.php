<?php
/**
 * Plugin Name:       Dog Damn Enhancer
 * Description:       Extend WooCommerce and BuddyPress with personalized dog profiles, diet filters, activity tracking and more.
 * Version:           1.0.0
 * Author:            James Tonner
 * Author URI:        https://jaydigital.co.uk/
 * Text Domain:       dog-damn-enhancer
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DDE_VERSION', '1.0.0' );
define( 'DDE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DDE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DDE_TEXT_DOMAIN', 'dog-damn-enhancer' );

// Load translations
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( DDE_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

// Include required files
require_once DDE_PLUGIN_DIR . 'includes/class-dde-dependency-checker.php';
require_once DDE_PLUGIN_DIR . 'includes/helpers.php';

if ( ! DDE_Dependency_Checker::is_valid() ) {
	add_action( 'admin_notices', [ 'DDE_Dependency_Checker', 'admin_notice' ] );
	return;
}

require_once DDE_PLUGIN_DIR . 'includes/class-dde-dog-profile.php';
require_once DDE_PLUGIN_DIR . 'includes/class-dde-profile-editor.php';
require_once DDE_PLUGIN_DIR . 'includes/class-dde-weight-tracker.php';
require_once DDE_PLUGIN_DIR . 'includes/class-dde-woocommerce.php';
require_once DDE_PLUGIN_DIR . 'includes/class-dde-buddypress.php';
require_once DDE_PLUGIN_DIR . 'includes/class-dde-admin.php';

add_action( 'init', function() {
	DDE_Dog_Profile::init();
	DDE_Profile_Editor::init();
	DDE_Weight_Tracker::init();
	DDE_WooCommerce::init();
	DDE_BuddyPress::init();
	DDE_Admin::init();
} );