<?php
/**
 * The search form, used by `get_search_form()`.
 *
 * A single compact field: the reference design shows no visible submit button,
 * so the button is present for accessibility but visually hidden and the field
 * submits on Enter.
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package Aniacieske_2026
 */

$aniacieske_search_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $aniacieske_search_id ); ?>" class="sr-only">
		<?php esc_html_e( 'Search for:', 'aniacieske-2026' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $aniacieske_search_id ); ?>"
		name="s"
		value="<?php echo get_search_query(); ?>"
		placeholder="<?php esc_attr_e( 'Search …', 'aniacieske-2026' ); ?>"
		class="w-full rounded-sm border border-rule bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-foreground/50 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
	>
	<button type="submit" class="sr-only">
		<?php esc_html_e( 'Search', 'aniacieske-2026' ); ?>
	</button>
</form>
