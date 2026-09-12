<?php
/**
 * Aniacieske 2026 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Aniacieske_2026
 */

if ( ! defined( 'ANIACIESKE_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( 'ANIACIESKE_VERSION', '0.1.0' );
}

if ( ! defined( 'ANIACIESKE_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `aniacieske_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'ANIACIESKE_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( 'aniacieske_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function aniacieske_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Aniacieske 2026, use a find and replace
		 * to change 'aniacieske-2026' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'aniacieske-2026', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * The homepage runs a 60/20/20 story grid inside a 1140px card, so the
		 * lead image renders about 684px wide and each secondary image about
		 * 340px. These sizes are doubled for high-density displays and cropped
		 * hard to 4:3 so the grid keeps its rhythm regardless of what the
		 * client uploads.
		 */
		add_image_size( 'aniacieske-lead', 1368, 1026, true );
		add_image_size( 'aniacieske-thumb', 680, 510, true );

		// This theme uses wp_nav_menu() in three locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'aniacieske-2026' ),
				'menu-2' => __( 'Footer Legal', 'aniacieske-2026' ),
				'social' => __( 'Social Icons', 'aniacieske-2026' ),
			)
		);

		// The masthead pairs a text wordmark with an uploadable mark.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 160,
				'width'       => 160,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'aniacieske_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function aniacieske_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'aniacieske-2026' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'aniacieske-2026' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'aniacieske_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aniacieske_scripts() {
	wp_enqueue_style( 'aniacieske-2026-style', get_stylesheet_uri(), array(), ANIACIESKE_VERSION );
	wp_enqueue_script( 'aniacieske-2026-script', get_template_directory_uri() . '/js/script.min.js', array(), ANIACIESKE_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aniacieske_scripts' );

/**
 * Enqueue the block editor script.
 */
function aniacieske_enqueue_block_editor_script() {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'aniacieske-2026-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			ANIACIESKE_VERSION,
			true
		);
		wp_add_inline_script( 'aniacieske-2026-editor', "tailwindTypographyClasses = '" . esc_attr( ANIACIESKE_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', 'aniacieske_enqueue_block_editor_script' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function aniacieske_tinymce_add_class( $settings ) {
	$settings['body_class'] = ANIACIESKE_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'aniacieske_tinymce_add_class' );

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function aniacieske_modify_heading_levels( $args, $block_type ) {
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove <h1>, <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );

	return $args;
}
add_filter( 'register_block_type_args', 'aniacieske_modify_heading_levels', 10, 2 );

/**
 * Register Customizer settings for the masthead and navigation bar.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function aniacieske_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'aniacieske_social',
		array(
			'title'       => __( 'Social Links', 'aniacieske-2026' ),
			'description' => __( 'The three badges shown at the left of the navigation bar. Leave a field empty to hide that badge.', 'aniacieske-2026' ),
			'priority'    => 40,
		)
	);

	$networks = array(
		'x'         => __( 'X (Twitter) URL', 'aniacieske-2026' ),
		'instagram' => __( 'Instagram URL', 'aniacieske-2026' ),
		'tiktok'    => __( 'TikTok URL', 'aniacieske-2026' ),
	);

	foreach ( $networks as $slug => $label ) {
		$wp_customize->add_setting(
			"aniacieske_social_{$slug}",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			"aniacieske_social_{$slug}",
			array(
				'label'   => $label,
				'section' => 'aniacieske_social',
				'type'    => 'url',
			)
		);
	}

	// The tagline sits under the wordmark, so let it refresh live.
	if ( isset( $wp_customize->get_setting( 'blogdescription' )->transport ) ) {
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'aniacieske_customize_register' );

/**
 * Clear the cached accreditation lookup when the media library changes.
 */
function aniacieske_flush_accreditation_cache() {
	delete_transient( 'aniacieske_accreditation_ids' );
}
add_action( 'add_attachment', 'aniacieske_flush_accreditation_cache' );
add_action( 'delete_attachment', 'aniacieske_flush_accreditation_cache' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Template tags specific to the Aniacieske design.
 */
require get_template_directory() . '/inc/aniacieske-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
