<?php
/**
 * Paid Memberships Pro integration.
 *
 * Everything here degrades to "nothing is paid" when the plugin is inactive.
 *
 * @package Aniacieske_2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'aniacieske_paid_category_ids' ) ) :
	/**
	 * IDs of the categories that require a membership level.
	 *
	 * Read from the categories checked under each level's Content Settings, so
	 * the free and paid lists follow the real restriction rather than a second
	 * setting that could drift out of step with it.
	 *
	 * @return int[] Category term IDs.
	 */
	function aniacieske_paid_category_ids() {
		if ( ! function_exists( 'pmpro_getAllLevels' ) || ! function_exists( 'pmpro_getMembershipCategories' ) ) {
			return array();
		}

		$ids = array();

		// Hidden levels included: they still restrict their categories.
		foreach ( (array) pmpro_getAllLevels( true ) as $level ) {
			$ids = array_merge( $ids, array_map( 'intval', pmpro_getMembershipCategories( $level->id ) ) );
		}

		return array_values( array_unique( $ids ) );
	}
endif;

/**
 * Expose the paid flag on categories so the block editor preview can sort them
 * the same way the front end does.
 */
function aniacieske_register_category_paid_field() {
	register_rest_field(
		'category',
		'aniacieske_is_paid',
		array(
			'get_callback' => static function ( $term ) {
				return in_array( (int) $term['id'], aniacieske_paid_category_ids(), true );
			},
			'schema'       => array(
				'description' => __( 'Whether the category requires a membership.', 'aniacieske-2026' ),
				'type'        => 'boolean',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
			),
		)
	);
}
add_action( 'rest_api_init', 'aniacieske_register_category_paid_field' );

if ( ! function_exists( 'aniacieske_category_no_access_message' ) ) :
	/**
	 * PMPro's message box for the current category archive.
	 *
	 * PMPro attaches its box to posts, so a paid category with nothing to list
	 * would otherwise fall through to "Nothing Found" without mentioning that
	 * a subscription exists.
	 *
	 * @return string Message HTML; empty when the category is free or the
	 *                visitor already has one of its levels.
	 */
	function aniacieske_category_no_access_message() {
		if ( ! is_category() || ! function_exists( 'pmpro_hasMembershipLevel' ) || ! function_exists( 'pmpro_get_no_access_message' ) ) {
			return '';
		}

		$term_id   = get_queried_object_id();
		$level_ids = array();

		foreach ( (array) pmpro_getAllLevels( true ) as $level ) {
			if ( in_array( $term_id, array_map( 'intval', pmpro_getMembershipCategories( $level->id ) ), true ) ) {
				$level_ids[] = (int) $level->id;
			}
		}

		if ( empty( $level_ids ) || pmpro_hasMembershipLevel( $level_ids ) ) {
			return '';
		}

		return pmpro_get_no_access_message( '', $level_ids );
	}
endif;

/**
 * Heading of PMPro's message box on restricted content.
 *
 * PMPro builds it as "{level name} Membership Required" with no setting for
 * the wording, and renaming the level would also change checkout and receipts.
 *
 * @return string
 */
function aniacieske_no_access_message_header() {
	return esc_html__( 'Subscription Required (provides access to the year’s current content)', 'aniacieske-2026' );
}
add_filter( 'pmpro_no_access_message_header', 'aniacieske_no_access_message_header' );

/**
 * First line of the same message box, which PMPro words around the level name.
 *
 * Only that paragraph is swapped, so PMPro's Join Now button and its checkout
 * link stay its own.
 *
 * @param string $body Message body HTML.
 * @return string
 */
function aniacieske_no_access_message_body( $body ) {
	$line = '<p>' . esc_html__( 'You must be a subscriber to access this content.', 'aniacieske-2026' ) . '</p>';

	return preg_replace( '~<p>.*?</p>~s', $line, $body, 1 );
}
add_filter( 'pmpro_no_access_message_body', 'aniacieske_no_access_message_body' );
