<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DDE_Dog_Profile {

	/**
	 * Init hooks.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'acf/init', [ __CLASS__, 'register_acf_fields' ] );
	}

	/**
	 * Register custom post type for dog profiles.
	 */
	public static function register_cpt() {
		$labels = [
			'name'               => __( 'Dog Profiles', 'dog-damn-enhancer' ),
			'singular_name'      => __( 'Dog Profile', 'dog-damn-enhancer' ),
			'add_new'            => __( 'Add New', 'dog-damn-enhancer' ),
			'add_new_item'       => __( 'Add New Dog Profile', 'dog-damn-enhancer' ),
			'edit_item'          => __( 'Edit Dog Profile', 'dog-damn-enhancer' ),
			'new_item'           => __( 'New Dog Profile', 'dog-damn-enhancer' ),
			'view_item'          => __( 'View Dog Profile', 'dog-damn-enhancer' ),
			'search_items'       => __( 'Search Dog Profiles', 'dog-damn-enhancer' ),
			'not_found'          => __( 'No dog profiles found.', 'dog-damn-enhancer' ),
			'not_found_in_trash' => __( 'No dog profiles found in Trash.', 'dog-damn-enhancer' ),
			'menu_name'          => __( 'Dog Profiles', 'dog-damn-enhancer' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'supports'           => [ 'title', 'thumbnail' ],
			'capability_type'    => 'post',
			'capabilities'       => [ 'create_posts' => 'edit_posts' ],
			'map_meta_cap'       => true,
			'show_in_rest'       => false,
			'menu_icon'          => 'dashicons-pets',
		];

		register_post_type( 'dog_profile', $args );
	}

	/**
	 * Register ACF fields for dog profile data.
	 */
	public static function register_acf_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( [
			'key' => 'group_dog_profile_fields',
			'title' => __( 'Dog Profile Details', 'dog-damn-enhancer' ),
			'fields' => [

				[
					'key' => 'field_dog_name',
					'label' => __( 'Name', 'dog-damn-enhancer' ),
					'name' => 'dog_name',
					'type' => 'text',
					'required' => 1,
				],
				[
					'key' => 'field_dog_dob',
					'label' => __( 'Date of Birth', 'dog-damn-enhancer' ),
					'name' => 'dog_dob',
					'type' => 'date_picker',
					'return_format' => 'Y-m-d',
				],
				[
					'key' => 'field_dog_sex',
					'label' => __( 'Sex', 'dog-damn-enhancer' ),
					'name' => 'dog_sex',
					'type' => 'select',
					'choices' => [
						'male'   => __( 'Male', 'dog-damn-enhancer' ),
						'female' => __( 'Female', 'dog-damn-enhancer' ),
					],
					'ui' => 1,
				],
				[
					'key' => 'field_dog_neutered',
					'label' => __( 'Neutered?', 'dog-damn-enhancer' ),
					'name' => 'dog_neutered',
					'type' => 'true_false',
					'ui' => 1,
				],
				[
					'key' => 'field_dog_breed_size',
					'label' => __( 'Breed Size', 'dog-damn-enhancer' ),
					'name' => 'dog_breed_size',
					'type' => 'select',
					'choices' => [
						'small'  => __( 'Small', 'dog-damn-enhancer' ),
						'medium' => __( 'Medium', 'dog-damn-enhancer' ),
						'large'  => __( 'Large', 'dog-damn-enhancer' ),
					],
					'ui' => 1,
				],
				[
					'key' => 'field_dog_activity_level',
					'label' => __( 'Activity Level', 'dog-damn-enhancer' ),
					'name' => 'dog_activity_level',
					'type' => 'radio',
					'choices' => [
						'low'    => __( 'Low', 'dog-damn-enhancer' ),
						'medium' => __( 'Medium', 'dog-damn-enhancer' ),
						'high'   => __( 'High', 'dog-damn-enhancer' ),
					],
					'layout' => 'horizontal',
				],
				[
					'key' => 'field_dog_allergies',
					'label' => __( 'Food Allergies', 'dog-damn-enhancer' ),
					'name' => 'dog_allergies',
					'type' => 'text',
				],
				[
					'key' => 'field_dog_flavours',
					'label' => __( 'Favourite Flavours', 'dog-damn-enhancer' ),
					'name' => 'dog_flavours',
					'type' => 'text',
				],
				[
					'key' => 'field_dog_diet',
					'label' => __( 'Special Dietary Needs', 'dog-damn-enhancer' ),
					'name' => 'dog_diet',
					'type' => 'checkbox',
					'choices' => [
						'low_fat'        => __( 'Low Fat', 'dog-damn-enhancer' ),
						'kidney_support' => __( 'Kidney Support', 'dog-damn-enhancer' ),
					],
					'layout' => 'vertical',
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'dog_profile',
					],
				],
			],
		] );
	}
}