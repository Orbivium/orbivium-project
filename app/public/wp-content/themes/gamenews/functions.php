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

/**
 * Dynamic Platform Colors
 * 
 * Changes the accent color and tints the whole page based on the current platform.
 */
function oyunhaber_dynamic_platform_colors() {
    $current_color = '';
    
    // Platform Colors Definition
    // Format: 'slug' => 'color_hex'
    $platform_colors = array(
        'genel'       => '#9b59b6', // Purple for General
        'pc'          => '#0abde3', // Cyan/Blue
        'playstation' => '#003791', // Dark Blue
        'xbox'        => '#107c10', // Green
        'nintendo'    => '#e60012', // Red
        'mobil'       => '#ff9f43', // Orange
    );

    // 1. Is it a Platform Archive Page?
    if ( is_tax( 'platform' ) ) {
        $term = get_queried_object();
        if ( isset( $term->slug ) && isset( $platform_colors[ $term->slug ] ) ) {
            $current_color = $platform_colors[ $term->slug ];
        }
    } 
    // 2. Is it a Single Post?
    elseif ( is_single() ) {
        $terms = get_the_terms( get_the_ID(), 'platform' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = reset( $terms ); 
            if ( isset( $term->slug ) && isset( $platform_colors[ $term->slug ] ) ) {
                $current_color = $platform_colors[ $term->slug ];
            }
        }
    }

    if ( $current_color ) {
        // Convert Hex to RGB for CSS rgba() usage
        list($r, $g, $b) = sscanf($current_color, "#%02x%02x%02x");
        $rgb_val = "$r, $g, $b";

        ?>
        <style type="text/css">
            :root {
                --accent-color: <?php echo esc_attr( $current_color ); ?> !important;
                --accent-hover: rgba(<?php echo $rgb_val; ?>, 0.8) !important;
                --platform-rgb: <?php echo $rgb_val; ?>;
            }

            /* --- Immersive Background Tinting --- */
            
            /* Body Gradient Background */
            body {
                background-color: #0d0d0d; /* Darker base */
                background-image: 
                    radial-gradient(circle at 50% 0%, rgba(var(--platform-rgb), 0.25) 0%, transparent 60%),
                    linear-gradient(to bottom, rgba(var(--platform-rgb), 0.05) 0%, transparent 100%);
                background-attachment: fixed;
            }

            /* Tinted Containers */
            .site-header, 
            .site-footer, 
            .card, 
            .post, 
            .single-hero,
            .comments-area,
            .comment-respond,
            .widget {
                background-color: rgba(var(--platform-rgb), 0.08) !important;
                border-color: rgba(var(--platform-rgb), 0.2) !important;
            }

            /* Inner Elements darker tint */
            .header-search form, 
            .card-image, 
            .comment-body, 
            .platform-nav-bar,
            .cat-links a, 
            .tags-links a {
                background-color: rgba(var(--platform-rgb), 0.15) !important;
            }

            /* Text Selection */
            ::selection {
                background: var(--accent-color);
                color: #fff;
            }
            
            /* Scrollbar Thumb */
            ::-webkit-scrollbar-thumb {
                background-color: var(--accent-color);
            }
        </style>
        <?php
    }
}
add_action( 'wp_head', 'oyunhaber_dynamic_platform_colors' );
