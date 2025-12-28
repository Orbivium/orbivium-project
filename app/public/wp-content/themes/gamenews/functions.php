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
    // Google Fonts for Platforms
    // Mobil: Fredoka, PC/Genel: Exo 2, PlayStation: Saira, Xbox: Roboto, Nintendo: Nunito
    wp_enqueue_style( 'oyunhaber-fonts', 'https://fonts.googleapis.com/css2?family=Anton&family=Exo+2:wght@500;700&family=Fredoka:wght@400;600&family=Nunito:wght@700&family=Roboto:wght@500;700&family=Saira:wght@500;700&display=swap', array(), null );

	wp_enqueue_style( 'oyunhaber-style', get_stylesheet_uri() );
    wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'oyunhaber_scripts' );

/**
 * Custom Post Types registration.
 */
require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/demo-data.php';
require get_template_directory() . '/inc/moderator-role.php';

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

/**
 * Custom Login Page Styles
 */
/**
 * Custom Login Page Styles
 */
function oyunhaber_login_styles() {
    ?>
    <style type="text/css">
        body.login {
            background-color: #0d0d0d !important;
            color: #e0e0e0;
            font-family: 'Segoe UI', 'Roboto', Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        /* Logo Styling */
        body.login div#login h1 a {
            background-image: none !important;
            color: #fff;
            font-size: 0 !important; /* Hide original text */
            width: auto;
            background-size: contain;
            height: auto;
            line-height: 1;
            margin-bottom: 30px;
            text-indent: 0;
            display: block;
            text-align: center;
        }

        /* Show the text "ORBI" cleanly via pseudo-element */
        body.login div#login h1 a::before {
            content: 'ORBI';
            font-size: 3rem; /* Visible size */
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -2px;
            color: #ff4757;
            text-shadow: 0 0 20px rgba(255, 71, 87, 0.4);
        }
        
        /* Container */
        #login {
            width: 400px;
            padding: 0;
            margin: auto;
        }

        /* Form Card */
        .login form {
            background: #1e1e1e !important;
            border: 1px solid #333 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
            border-radius: 16px;
            padding: 40px 30px !important;
            margin-top: 0 !important;
        }

        .login label {
            color: #b0b0b0;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Inputs */
        .login input[type="text"],
        .login input[type="password"],
        .login input[type="email"] {
            background: #2d2d2d !important;
            border: 1px solid #444 !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 12px 15px !important;
            margin-bottom: 20px !important;
            box-shadow: none !important;
            font-size: 1rem !important;
            width: 100%;
            transition: all 0.3s ease;
        }

        .login input[type="text"]:focus,
        .login input[type="password"]:focus,
        .login input[type="email"]:focus {
            border-color: #ff4757 !important;
            background: #333 !important;
            box-shadow: 0 0 0 2px rgba(255, 71, 87, 0.2) !important;
        }

        /* Checkbox */
        .login .forgetmenot {
            float: none !important;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .login input[type="checkbox"] {
            background: #2d2d2d !important;
            border-color: #444 !important;
            border-radius: 4px !important;
        }

        .login input[type="checkbox"]:checked::before {
            color: #ff4757 !important;
        }

        /* Button */
        .wp-core-ui .button-primary {
            background: #ff4757 !important;
            border-color: #ff4757 !important;
            text-shadow: none !important;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4) !important;
            color: #fff !important;
            border-radius: 30px !important;
            padding: 10px 0 !important;
            font-size: 1rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            float: none !important;
            margin-top: 10px !important;
            height: auto !important;
            line-height: normal !important;
        }

        .wp-core-ui .button-primary:hover {
            background: #ff6b81 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.6) !important;
        }

        /* Links */
        .login #nav, .login #backtoblog {
            padding: 0 !important;
            margin: 15px 0 0 0 !important;
            text-align: center;
        }

        .login #nav a, .login #backtoblog a {
            color: #666 !important;
            transition: color 0.3s;
            text-decoration: none !important;
            font-size: 0.9rem !important;
        }

        .login #nav a:hover, .login #backtoblog a:hover {
            color: #ff4757 !important;
        }
        
        /* Messages (Fixing the white box issue) */
        .login .message, 
        .login .success, 
        .login #login_error,
        .login .notice {
            background-color: #1e1e1e !important;
            border: 1px solid #333 !important;
            border-left: 4px solid #ff4757 !important; /* Default Accent */
            color: #e0e0e0 !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
            padding: 15px 20px !important;
            margin-bottom: 20px !important;
            border-radius: 8px !important;
        }

        .login .message {
            border-left-color: #0abde3 !important; /* Info Blue */
        }

        .login .success {
            border-left-color: #107c10 !important; /* Success Green */
        }

        .login #login_error {
            border-left-color: #ff4757 !important; /* Error Red */
        }
        
        /* Links inside messages */
        .login .message a,
        .login .success a,
        .login #login_error a {
            color: #ff4757 !important;
            text-decoration: underline;
        }

        /* Language Switcher */
        .language-switcher { display: none !important; }
        
        /* Dashicons override for password visibility */
        .login .dashicons-visibility { color: #888; }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'oyunhaber_login_styles' );

/**
 * Change Login Logo URL
 */
function oyunhaber_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'oyunhaber_login_logo_url' );

function oyunhaber_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'oyunhaber_login_logo_url_title' );

/**
 * Robust Page Creation Check on Init (Frontend)
 * This ensures the 'profil' page exists for the user immediately.
 */
function oyunhaber_check_pages_frontend() {
    // Only run if not an admin page and if the page doesn't exist
    if ( ! is_admin() && ! get_page_by_path( 'profil' ) ) {
        $page_id = wp_insert_post( array(
            'post_title'    => 'Profil',
            'post_name'     => 'profil',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'page_template' => 'page-profil.php'
        ) );
        
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_post_meta( $page_id, '_wp_page_template', 'page-profil.php' );
            
            // Hard flush rules
            flush_rewrite_rules();
        }
    }
}
add_action( 'init', 'oyunhaber_check_pages_frontend' );
