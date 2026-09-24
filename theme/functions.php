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
		/*
		 * Story cards crop with CSS (`aspect-[4/3]` + `object-cover`) and draw
		 * on core image sizes, so no custom crops are registered here. Custom
		 * sizes would be missing from the ~109 attachments that predate this
		 * theme and would need a thumbnail regeneration pass to work.
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in three locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'aniacieske-2026' ),
				'menu-2' => __( 'Footer Legal', 'aniacieske-2026' ),
				'social' => __( 'Social Icons', 'aniacieske-2026' ),
			)
		);

		/*
		 * The page is a white card floating over a full-bleed ground. Exposing
		 * that ground through the Customizer lets the client swap the flat
		 * colour for a photograph, as the reference site does, without code.
		 * The default matches the `canvas` swatch in theme.json.
		 */
		add_theme_support(
			'custom-background',
			array(
				'default-color'      => '8a9aa6',
				'default-attachment' => 'fixed',
				'default-size'       => 'cover',
				'default-position-x' => 'center',
				'default-position-y' => 'center',
				'default-repeat'     => 'no-repeat',
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

		/*
		 * Declare WooCommerce support so the store renders inside this theme's
		 * header and footer, and enable the gallery features the single product
		 * page expects. The store is themed by styling WooCommerce's own class
		 * names rather than by copying its templates — see `inc/woocommerce.php`.
		 */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

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
 * `_tw` ships this without `<h1>`, on the assumption that the theme renders the
 * post title as the page's only `<h1>`. This theme builds pages entirely from
 * blocks and renders no title of its own, so `<h1>` has to be available here or
 * interior pages would have no top-level heading at all.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function aniacieske_modify_heading_levels( $args, $block_type ) {
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Offer <h1> through <h4>; drop <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array( 1, 2, 3, 4 );

	return $args;
}
add_filter( 'register_block_type_args', 'aniacieske_modify_heading_levels', 10, 2 );

/**
 * Register a block category for the theme's own blocks.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function aniacieske_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'aniacieske',
				'title' => __( 'Aniacieske', 'aniacieske-2026' ),
				'icon'  => null,
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'aniacieske_block_categories' );

/**
 * Register all built theme blocks.
 *
 * Each block lives in its own folder under `/blocks`, built from `/src/blocks`
 * by `@wordpress/scripts`. Any folder containing a `block.json` is registered.
 */
function aniacieske_register_blocks() {
	$aniacieske_blocks_dir = get_template_directory() . '/blocks';

	if ( ! is_dir( $aniacieske_blocks_dir ) ) {
		return;
	}

	$aniacieske_block_folders = glob( $aniacieske_blocks_dir . '/*', GLOB_ONLYDIR );

	if ( empty( $aniacieske_block_folders ) ) {
		return;
	}

	foreach ( $aniacieske_block_folders as $aniacieske_block_folder ) {
		if ( file_exists( $aniacieske_block_folder . '/block.json' ) ) {
			register_block_type( $aniacieske_block_folder );
		}
	}
}
add_action( 'init', 'aniacieske_register_blocks' );

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
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Template tags specific to the Aniacieske design.
 */
require get_template_directory() . '/inc/aniacieske-tags.php';

/**
 * WooCommerce integration.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
