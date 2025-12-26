<?php
/**
 * OyunHaber functions and definitions
 *
 * @package OyunHaber
 */

if ( ! function_exists( 'oyunhaber_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function oyunhaber_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'oyunhaber', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

        // Custom Logo support
        add_theme_support( 'custom-logo', array(
            'height'      => 80,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'oyunhaber' ),
			'footer'  => esc_html__( 'Footer Menu', 'oyunhaber' ),
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
            'style', 
            'script',
		) );
	}
endif;
add_action( 'after_setup_theme', 'oyunhaber_setup' );

/**
 * Register widget area.
 */
function oyunhaber_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'oyunhaber' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'oyunhaber' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    // Header & Footer Sidebars
    register_sidebar( array(
        'name'          => esc_html__( 'Footer About', 'oyunhaber' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer column 1 (Hakkımızda)', 'oyunhaber' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Sitemap', 'oyunhaber' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer column 2 (Site Haritası)', 'oyunhaber' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Legal', 'oyunhaber' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Footer column 3 (Yasal)', 'oyunhaber' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Social', 'oyunhaber' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Footer column 4 (Sosyal Medya)', 'oyunhaber' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'oyunhaber_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function oyunhaber_scripts() {
	wp_enqueue_style( 'oyunhaber-style', get_stylesheet_uri() );
    wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'oyunhaber_scripts' );

/**
 * Custom Post Types registration.
 */
require get_template_directory() . '/inc/custom-post-types.php';
