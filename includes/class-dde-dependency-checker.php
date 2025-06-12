<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DDE_Dependency_Checker {

	/**
	 * Check if all required plugins are active.
	 *
	 * @return bool
	 */
	public static function is_valid() {
		return (
			self::is_woocommerce_active() &&
			self::is_acf_active() &&
			self::is_buddypress_active()
		);
	}

	/**
	 * Check if WooCommerce is active.
	 */
	protected static function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Check if ACF (free) is active.
	 */
	protected static function is_acf_active() {
		return function_exists( 'acf_add_local_field_group' );
	}

	/**
	 * Check if BuddyPress is active.
	 */
	protected static function is_buddypress_active() {
		return function_exists( 'buddypress' );
	}

	/**
	 * Admin notice for missing dependencies.
	 */
	public static function admin_notice() {
		echo '<div class="notice notice-error"><p>';
		_e( 'Dog Damn Enhancer requires WooCommerce, Advanced Custom Fields, and BuddyPress. Please activate all required plugins.', 'dog-damn-enhancer' );
		echo '</p></div>';
	}
}