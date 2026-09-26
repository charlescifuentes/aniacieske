<?php
/**
 * WooCommerce integration.
 *
 * The store is themed without copying a single WooCommerce template. Instead
 * the theme (a) declares support in `functions.php` so WooCommerce renders
 * inside this theme's header and footer, (b) swaps WooCommerce's own content
 * wrapper for a container that matches the page card's gutters, and (c) styles
 * WooCommerce's class names in
 * `tailwind/custom/components/woocommerce.css`.
 *
 * Keeping the canonical templates means the store keeps working through
 * WooCommerce updates instead of drifting against overrides.
 *
 * @package Aniacieske_2026
 */

defined( 'ABSPATH' ) || exit;

// Everything below is a no-op without WooCommerce; bail so the theme still
// works if the plugin is deactivated.
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/*
 * WooCommerce's archive and single templates open a `#primary`/`#main` wrapper
 * and a sidebar through these hooks. The store here is a single column inside
 * the page card, so both are replaced.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'aniacieske_wc_wrapper_start' ) ) :
	/**
	 * Open the store container.
	 */
	function aniacieske_wc_wrapper_start() {
		echo '<div id="primary" class="wc-page not-prose"><div class="wc-page__inner">';
	}
endif;
add_action( 'woocommerce_before_main_content', 'aniacieske_wc_wrapper_start', 10 );

if ( ! function_exists( 'aniacieske_wc_wrapper_end' ) ) :
	/**
	 * Close the store container.
	 */
	function aniacieske_wc_wrapper_end() {
		echo '</div></div>';
	}
endif;
add_action( 'woocommerce_after_main_content', 'aniacieske_wc_wrapper_end', 10 );

/**
 * Show four products per row on the shop archive.
 *
 * @return int
 */
function aniacieske_wc_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'aniacieske_wc_loop_columns' );

/*
 * Show product categories on the shop even while they hold no products.
 *
 * WooCommerce filters empty terms out of the shop loop, which is sensible for a
 * running store but wrong here: the categories stand in for the catalogue until
 * the actual products exist, so an empty one still needs to appear.
 *
 * This affects only the category tiles in the shop loop — widgets and menus
 * keep their own hide-empty behaviour.
 */
add_filter( 'woocommerce_product_subcategories_hide_empty', '__return_false' );

/**
 * Use a theme-supplied placeholder for products and categories with no image.
 *
 * WooCommerce's own placeholder is generated into the uploads folder on
 * install, and is frequently missing after a migration — which renders as a
 * broken image. This ships with the theme, so it is always there, and it is an
 * SVG so it stays crisp at any tile size.
 *
 * @return string
 */
function aniacieske_wc_placeholder_img_src() {
	return get_template_directory_uri() . '/images/placeholder.svg';
}
add_filter( 'woocommerce_placeholder_img_src', 'aniacieske_wc_placeholder_img_src' );

/**
 * Drop WooCommerce's breadcrumb.
 *
 * The store is three pages deep at most and the primary menu already says
 * where you are, so the breadcrumb is noise against a clean masthead.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/*
 * Give the shop archive a heading. Interior pages take their `<h1>` from a
 * block, but the shop is a post-type archive with no block content behind it.
 */
add_filter( 'woocommerce_show_page_title', '__return_true' );
