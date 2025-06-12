<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DDE_Profile_Editor {

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_shortcode( 'dog_profile_editor', [ __CLASS__, 'render_shortcode' ] );
		add_action( 'bp_setup_nav', [ __CLASS__, 'add_buddypress_tab' ] );
	}

	/**
	 * Render profile editor via shortcode.
	 */
	public static function render_shortcode( $atts ) {
		if ( ! is_user_logged_in() ) {
			return __( 'You must be logged in to manage your dog profiles.', 'dog-damn-enhancer' );
		}

		ob_start();
		self::render_profile_list();
		return ob_get_clean();
	}

	/**
	 * List dog profiles owned by current user.
	 */
	protected static function render_profile_list() {
		$user_id = get_current_user_id();

		$profiles = get_posts( [
			'post_type'      => 'dog_profile',
			'author'         => $user_id,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		] );

		echo '<div class="dde-profile-list">';

		if ( $profiles ) {
			foreach ( $profiles as $profile ) {
				$edit_url = get_edit_post_link( $profile->ID );
				echo '<div class="dde-profile-item">';
				echo '<strong>' . esc_html( get_the_title( $profile ) ) . '</strong><br>';
				echo '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit Profile', 'dog-damn-enhancer' ) . '</a>';
				echo '</div>';
			}
		} else {
			echo '<p>' . __( 'You haven’t added any dogs yet.', 'dog-damn-enhancer' ) . '</p>';
		}

		echo '<a class="button" href="' . esc_url( admin_url( 'post-new.php?post_type=dog_profile' ) ) . '">' . __( 'Add New Dog Profile', 'dog-damn-enhancer' ) . '</a>';
		echo '</div>';
	}

	/**
	 * Add BuddyPress tab for managing dog profiles.
	 */
	public static function add_buddypress_tab() {
		if ( ! function_exists( 'bp_core_new_nav_item' ) ) {
			return;
		}

		$current_user = wp_get_current_user();

		bp_core_new_nav_item( [
			'name'                => __( 'Dog Profiles', 'dog-damn-enhancer' ),
			'slug'                => 'dog-profiles',
			'default_subnav_slug' => 'my-dogs',
			'position'            => 80,
			'show_for_displayed_user' => true,
			'screen_function'     => [ __CLASS__, 'buddypress_tab_screen' ],
			'user_has_access'     => true,
		] );
	}

	/**
	 * BuddyPress tab screen renderer.
	 */
	public static function buddypress_tab_screen() {
		add_action( 'bp_template_content', [ __CLASS__, 'render_shortcode' ] );
		bp_core_load_template( 'members/single/plugins' );
	}
}