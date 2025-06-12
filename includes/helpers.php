<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculate dog life stage based on date of birth.
 *
 * @param string $dob Date in Y-m-d format.
 * @return string One of: puppy, adult, senior
 */
function dde_get_dog_life_stage( $dob ) {
	if ( empty( $dob ) ) {
		return 'unknown';
	}

	$dob_time = strtotime( $dob );
	$age_years = ( time() - $dob_time ) / (365.25 * DAY_IN_SECONDS);

	if ( $age_years < 1 ) {
		return 'puppy';
	} elseif ( $age_years < 7 ) {
		return 'adult';
	} else {
		return 'senior';
	}
}

/**
 * Escape and optionally truncate text output.
 *
 * @param string $text
 * @param int    $max_length
 * @return string
 */
function dde_esc_summary( $text, $max_length = 100 ) {
	$text = wp_strip_all_tags( $text );
	if ( strlen( $text ) > $max_length ) {
		$text = substr( $text, 0, $max_length ) . '...';
	}
	return esc_html( $text );
}