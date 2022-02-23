<?php
/**
 * Theme functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package Onefold
 */

if ( ! function_exists( 'onefold_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function onefold_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'onefold', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Register nav menu locations.
		register_nav_menus( array(
			'primary'  => esc_html__( 'Primary Menu', 'onefold' ),
			'footer'   => esc_html__( 'Footer Menu', 'onefold' ),
			'social'   => esc_html__( 'Social Menu', 'onefold' ),
			'notfound' => esc_html__( '404 Menu', 'onefold' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'onefold_custom_background_args', array(
			'default-color' => 'f6f6f6',
			'default-image' => get_template_directory_uri() . '/images/body-bg.png',
		) ) );

		// Enable support for selective refresh of widgets in Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Enable support for custom logo.
		add_theme_support( 'custom-logo' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Enable support for footer widgets.
		add_theme_support( 'footer-widgets', 4 );

		// Load Supports.
		require get_template_directory() . '/inc/support.php';

		global $onefold_default_options;
		$onefold_default_options = onefold_get_default_theme_options();

	}
endif;

add_action( 'after_setup_theme', 'onefold_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function onefold_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'onefold_content_width', 680 );
}
add_action( 'after_setup_theme', 'onefold_content_width', 0 );

if ( ! function_exists( 'onefold_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function onefold_template_redirect() {
		$global_layout = onefold_get_option( 'global_layout' );

		if ( 'no-sidebar' === $global_layout ) {
			$GLOBALS['content_width'] = 1010;
		}

		// Three Columns
		elseif ( 'three-columns' == $global_layout ) {
			$GLOBALS['content_width'] = 460;
		}
	}
endif;
add_action( 'template_redirect', 'onefold_template_redirect' );

/**
 * Register widget area.
 */
function onefold_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'onefold' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your Primary Sidebar.', 'onefold' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Secondary Sidebar', 'onefold' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here to appear in your Secondary Sidebar.', 'onefold' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'onefold_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function onefold_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/third-party/font-awesome/css/font-awesome' . $min . '.css', '', '4.7.0' );

	$fonts_url = onefold_fonts_url();
	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'onefold-google-fonts', $fonts_url, array(), null );
	}

	wp_enqueue_style( 'jquery-sidr', get_template_directory_uri() .'/third-party/sidr/css/jquery.sidr.dark' . $min . '.css', '', '2.2.1' );

	wp_enqueue_style( 'jquery-magnific-popup', get_template_directory_uri() .'/third-party/magnific-popup/css/magnific-popup' . $min . '.css', '', '1.1.0' );

	wp_enqueue_style( 'onefold-style', get_stylesheet_uri(), null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );

	$custom_css = '';
	if ( true === onefold_get_option( 'enable_footer_widgets_background_image' ) ) {
		$footer_widgets_background_image = onefold_get_option( 'footer_widgets_background_image' );
		$custom_css .= '#footer-widgets{background-image:url(' . esc_url( $footer_widgets_background_image ) . ');}';
	}
	$portfolio_background_image = onefold_get_option( 'portfolio_background_image' );
	if ( ! empty( $portfolio_background_image ) ) {
		$custom_css .= '.home-section-portfolio{background-image:url(' . esc_url( $portfolio_background_image ) . ');}';
	}
	$testimonials_background_image = onefold_get_option( 'testimonials_background_image' );
	if ( ! empty( $testimonials_background_image ) ) {
		$custom_css .= '.home-section-testimonials{background-image:url(' . esc_url( $testimonials_background_image ) . ');}';
	}
	if ( ! empty( $custom_css ) ) {
		wp_add_inline_style( 'onefold-style', $custom_css );
	}

	wp_enqueue_script( 'onefold-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix' . $min . '.js', array(), '20130115', true );

	wp_enqueue_script( 'jquery-cycle2', get_template_directory_uri() . '/third-party/cycle2/js/jquery.cycle2' . $min . '.js', array( 'jquery' ), '2.1.6', true );

	wp_enqueue_script( 'jquery-sidr', get_template_directory_uri() . '/third-party/sidr/js/jquery.sidr' . $min . '.js', array( 'jquery' ), '2.2.1', true );

	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/third-party/magnific-popup/js/jquery.magnific-popup' . $min . '.js', array( 'jquery' ), '1.1.0', true );

	wp_enqueue_script( 'onefold-custom', get_template_directory_uri() . '/js/custom' . $min . '.js', array( 'jquery' ), '1.0.2', true );
	$custom_args = array(
		'go_to_top_status' => ( true === onefold_get_option( 'go_to_top' ) ) ? 1 : 0,
		);
	wp_localize_script( 'onefold-custom', 'Onefold_Custom_Options', $custom_args );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'onefold_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function onefold_admin_scripts( $hook ) {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_style( 'onefold-metabox', get_template_directory_uri() . '/css/metabox' . $min . '.css', '', '1.0.0' );
		wp_enqueue_script( 'onefold-custom-admin', get_template_directory_uri() . '/js/admin' . $min . '.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), '1.0.0', true );
	}

}
add_action( 'admin_enqueue_scripts', 'onefold_admin_scripts' );

/**
 * Load init.
 */
require_once get_template_directory() . '/inc/init.php';

if ( ! function_exists( 'onefold_blocks_support' ) ) :
	/**
	 * Create add default blocks support
	 */
	function onefold_blocks_support() {
		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Small', 'onefold' ),
					'shortName' => esc_html__( 'S', 'onefold' ),
					'size'      => 14,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Normal', 'onefold' ),
					'shortName' => esc_html__( 'M', 'onefold' ),
					'size'      => 18,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'onefold' ),
					'shortName' => esc_html__( 'L', 'onefold' ),
					'size'      => 42,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'onefold' ),
					'shortName' => esc_html__( 'XL', 'onefold' ),
					'size'      => 54,
					'slug'      => 'huge',
				),
			)
		);

		// Add support for custom color scheme.
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'White', 'onefold' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
			array(
				'name'  => esc_html__( 'Black', 'onefold' ),
				'slug'  => 'black',
				'color' => '#111111',
			),
			array(
				'name'  => esc_html__( 'Gray', 'onefold' ),
				'slug'  => 'gray',
				'color' => '#f4f4f4',
			),
			array(
				'name'  => esc_html__( 'Blue', 'onefold' ),
				'slug'  => 'blue',
				'color' => '#1b8be0',
			),
			array(
				'name'  => esc_html__( 'Yellow', 'onefold' ),
				'slug'  => 'yellow',
				'color' => '#e9c01e',
			),
		) );
	}
	add_action( 'after_setup_theme', 'onefold_blocks_support', 20 );
endif; //onefold_blocks_support

if ( ! function_exists( 'onefold_add_blocks_style' ) ) :
	/**
	 * Add Blocks Style
	 */
	function onefold_add_blocks_style() {
		// Theme block stylesheet.
		wp_enqueue_style( 'onefold-block-style', get_theme_file_uri( '/css/blocks.css' ), array( 'onefold-style' ), date( 'Ymd-Gis', filemtime( get_template_directory() . '/css/blocks.css' ) ) );
	}
	add_action( 'wp_enqueue_scripts', 'onefold_add_blocks_style' );
endif; //onefold_add_blocks_style

if ( ! function_exists( 'onefold_block_editor_styles' ) ) :
	/**
	 * Enqueue editor styles for Blocks
	 */
	function onefold_block_editor_styles() {
		// Block styles.
		wp_enqueue_style( 'onefold-block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ), null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/css/editor-blocks.css' ) ) );

		// Add custom fonts.
		wp_enqueue_style( 'onefold-fonts', onefold_fonts_url(), array(), null );
	}
	add_action( 'enqueue_block_editor_assets', 'onefold_block_editor_styles' );
endif; //onefold_block_editor_styles
