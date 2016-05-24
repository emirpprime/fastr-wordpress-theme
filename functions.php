<?php
/**
 * fastr functions and definitions
 *
 * @package fastr
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 700; /* pixels */
}

if ( ! function_exists( 'fastr_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fastr_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on fastr, use a find and replace
	 * to change 'fastr' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fastr', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	//add_image_size( 'home-lg', 360, 200, true );

	// Declare support for Title Tag function.
	add_theme_support( 'title-tag' );

	// Declare support for Custom Logo
	add_theme_support( 'custom-logo', array(
		'height' 		=> 120,
		'width' 		=> 120,
		'flex-width' 	=> false,
		'flex-height' 	=> false,
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'fastr' ),
		'secondary' => __( 'Secondary Menu', 'fastr' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'fastr_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // fastr_setup
add_action( 'after_setup_theme', 'fastr_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function fastr_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'BottomBar', 'fastr' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'fastr_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fastr_scripts() {
	wp_enqueue_style( 'fastr-style', get_stylesheet_uri(), false, filemtime( get_stylesheet_directory() . '/style.css' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fastr_scripts' );

/**
 * Disable emoji / remove all scripts
 */
function fastr_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_tinymce_emoji' );
}
add_action( 'init', 'fastr_disable_emoji', 1 );
// filter function used to remove the tinymce emoji plugin
function disable_tinymce_emoji( $plugins ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
}

if ( ! function_exists( 'fastr_excerpt_more' ) ) :
/**
 * Changes the default excerpt trailing content
 *
 * Replaces the default [...] trailing text from excerpts
 * to a more pleasant ...
 *
 * @since fastr 1.0
 */
function fastr_excerpt_more($more) {
	return ' &#8230;';
	//global $post;
	//return '&#8230;<br/><a class="moretag" href="'. get_permalink($post->ID) . '"> read more</a>';
}
endif;

add_filter( 'excerpt_more', 'fastr_excerpt_more' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
