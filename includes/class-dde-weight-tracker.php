<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DDE_Weight_Tracker {

	/**
	 * Init hooks.
	 */
	public static function init() {
		add_action( 'acf/init', [ __CLASS__, 'register_acf_fields' ] );
		add_shortcode( 'dog_weight_chart', [ __CLASS__, 'render_chart_shortcode' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue Chart.js and confetti JS.
	 */
	public static function enqueue_scripts() {
		wp_register_script(
			'chartjs',
			'https://cdn.jsdelivr.net/npm/chart.js',
			[],
			null,
			true
		);

		wp_enqueue_script(
			'dde-weight-chart',
			DDE_PLUGIN_URL . 'assets/js/weight-chart.js',
			[ 'chartjs' ],
			DDE_VERSION,
			true
		);
	}

	/**
	 * Register weight tracking ACF fields.
	 */
	public static function register_acf_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( [
			'key'    => 'group_dog_weight',
			'title'  => __( 'Weight Tracker', 'dog-damn-enhancer' ),
			'fields' => [
				[
					'key' => 'field_weight_log',
					'label' => __( 'Weight Log', 'dog-damn-enhancer' ),
					'name' => 'dog_weight_log',
					'type' => 'repeater',
					'layout' => 'table',
					'sub_fields' => [
						[
							'key' => 'field_weight_value',
							'label' => __( 'Weight (kg)', 'dog-damn-enhancer' ),
							'name' => 'weight',
							'type' => 'number',
							'step' => 0.1,
						],
						[
							'key' => 'field_weight_date',
							'label' => __( 'Date', 'dog-damn-enhancer' ),
							'name' => 'date',
							'type' => 'date_picker',
							'return_format' => 'Y-m-d',
						],
					],
				],
				[
					'key' => 'field_target_weight',
					'label' => __( 'Target Weight (kg)', 'dog-damn-enhancer' ),
					'name' => 'dog_weight_goal',
					'type' => 'number',
					'step' => 0.1,
				],
			],
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'dog_profile',
					],
				],
			],
		] );
	}

	/**
	 * Shortcode handler: [dog_weight_chart id="123"]
	 */
	public static function render_chart_shortcode( $atts ) {
		$atts = shortcode_atts( [ 'id' => 0 ], $atts );
		$dog_id = absint( $atts['id'] );

		if ( ! $dog_id || get_post_type( $dog_id ) !== 'dog_profile' ) {
			return __( 'Invalid dog profile.', 'dog-damn-enhancer' );
		}

		$log  = get_field( 'dog_weight_log', $dog_id );
		$goal = get_field( 'dog_weight_goal', $dog_id );

		if ( empty( $log ) ) {
			return __( 'No weight entries found.', 'dog-damn-enhancer' );
		}

		wp_enqueue_script( 'dde-weight-chart' );

		$dates   = [];
		$weights = [];

		foreach ( $log as $entry ) {
			$dates[]   = esc_js( $entry['date'] );
			$weights[] = floatval( $entry['weight'] );
		}

		ob_start();
		?>
		<canvas id="dogWeightChart" width="600" height="300"></canvas>
		<script>
		window.ddeWeightData = {
			labels: <?php echo wp_json_encode( $dates ); ?>,
			data: <?php echo wp_json_encode( $weights ); ?>,
			goal: <?php echo esc_js( $goal ); ?>
		};
		</script>
		<?php
		return ob_get_clean();
	}
}