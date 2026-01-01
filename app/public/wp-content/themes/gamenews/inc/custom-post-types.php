<?php
/**
 * Register Custom Post Types for OyunHaber
 *
 * @package OyunHaber
 */

if ( ! function_exists( 'oyunhaber_register_cpts' ) ) :
	function oyunhaber_register_cpts() {

		// 1. Haberler (News) - PARENT MENU ITEM
		register_post_type( 'news', array(
			'labels' => array(
				'name'               => __( 'Haberler', 'oyunhaber' ),
				'singular_name'      => __( 'Haber', 'oyunhaber' ),
				'add_new'            => __( 'Yeni Haber Ekle', 'oyunhaber' ),
				'add_new_item'       => __( 'Yeni Haber Ekle', 'oyunhaber' ),
				'edit_item'          => __( 'Haberi Düzenle', 'oyunhaber' ),
				'new_item'           => __( 'Yeni Haber', 'oyunhaber' ),
				'all_items'          => __( 'Tüm Haberler', 'oyunhaber' ),
				'view_item'          => __( 'Haberi Görüntüle', 'oyunhaber' ),
				'search_items'       => __( 'Haber Ara', 'oyunhaber' ),
				'not_found'          => __( 'Haber Bulunamadı', 'oyunhaber' ),
				'menu_name'          => __( 'İçerik Yönetimi', 'oyunhaber' ), // Renamed Parent
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true, // Block Editor support
			'menu_icon'     => 'dashicons-admin-post', // Generic icon
			'menu_position' => 4, // High priority
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ), 
			'rewrite'       => array( 'slug' => 'haberler' ), // Custom slug
		) );

		// 2. İncelemeler (Reviews) - SUBMENU OF NEWS
		register_post_type( 'reviews', array(
			'labels' => array(
				'name'               => __( 'İncelemeler', 'oyunhaber' ),
				'singular_name'      => __( 'İnceleme', 'oyunhaber' ),
				'add_new'            => __( 'Yeni İnceleme Ekle', 'oyunhaber' ),
				'all_items'          => __( 'Tüm İncelemeler', 'oyunhaber' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-star-half',
            'show_in_menu'  => 'edit.php?post_type=news', // NESTED under News
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ),
			'rewrite'       => array( 'slug' => 'incelemeler' ),
		) );

		// 3. Videolar (Videos)
		register_post_type( 'videos', array(
			'labels' => array(
				'name'               => __( 'Videolar', 'oyunhaber' ),
				'singular_name'      => __( 'Video', 'oyunhaber' ),
				'add_new'            => __( 'Yeni Video Ekle', 'oyunhaber' ),
				'all_items'          => __( 'Tüm Videolar', 'oyunhaber' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-video-alt3',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ),
			'rewrite'       => array( 'slug' => 'videolar' ),
		) );

		// 4. E-Spor (Esports)
		register_post_type( 'esports', array(
			'labels' => array(
				'name'               => __( 'E-Spor', 'oyunhaber' ),
				'singular_name'      => __( 'E-Spor İçeriği', 'oyunhaber' ),
				'add_new'            => __( 'Yeni E-Spor İçeriği Ekle', 'oyunhaber' ),
				'all_items'          => __( 'Tüm E-Spor İçerikleri', 'oyunhaber' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-groups',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ),
			'rewrite'       => array( 'slug' => 'e-spor' ),
		) );

        // Taxonomy: Platform
        $labels = array(
            'name'              => _x( 'Platformlar', 'taxonomy general name', 'oyunhaber' ),
            'singular_name'     => _x( 'Platform', 'taxonomy singular name', 'oyunhaber' ),
            'search_items'      => __( 'Platform Ara', 'oyunhaber' ),
            'all_items'         => __( 'Tüm Platformlar', 'oyunhaber' ),
            'parent_item'       => __( 'Üst Platform', 'oyunhaber' ),
            'parent_item_colon' => __( 'Üst Platform:', 'oyunhaber' ),
            'edit_item'         => __( 'Platformu Düzenle', 'oyunhaber' ),
            'update_item'       => __( 'Platformu Güncelle', 'oyunhaber' ),
            'add_new_item'      => __( 'Yeni Platform Ekle', 'oyunhaber' ),
            'new_item_name'     => __( 'Yeni Platform Adı', 'oyunhaber' ),
            'menu_name'         => __( 'Platform', 'oyunhaber' ),
        );

        register_taxonomy( 'platform', array( 'news', 'reviews', 'videos', 'esports' ), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'platform' ),
            'show_in_rest'      => true,
        ) );

        // 5. Slider (Homepage Slider)
		register_post_type( 'slider', array(
			'labels' => array(
				'name'               => __( 'Slider', 'oyunhaber' ),
				'singular_name'      => __( 'Slide', 'oyunhaber' ),
				'add_new'            => __( 'Yeni Slide Ekle', 'oyunhaber' ),
				'add_new_item'       => __( 'Yeni Slide Ekle', 'oyunhaber' ),
				'edit_item'          => __( 'Slide Düzenle', 'oyunhaber' ),
				'new_item'           => __( 'Yeni Slide', 'oyunhaber' ),
				'all_items'          => __( 'Tüm Slide\'lar', 'oyunhaber' ),
				'menu_name'          => __( 'Slider Ayarları', 'oyunhaber' ), // User requested "Sliderları Kontrol Et" style name
			),
			'public'        => false, // Not searchable/viewable on its own URL
            'show_ui'       => true,  // Show in Admin
			'show_in_rest'  => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
			'menu_icon'     => 'dashicons-images-alt2',
            'menu_position' => 5, // Top of menu
			'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ), // Title=Headline, Thumbnail=Bg Image, Custom Fields=Link
		) );

	}
endif;
add_action( 'init', 'oyunhaber_register_cpts' );

// Populate Default Terms
// Populate Default Terms
function oyunhaber_insert_default_terms() {
    $taxonomy = 'platform';

    // Check if taxonomy exists to avoid errors
    if ( ! taxonomy_exists( $taxonomy ) ) {
        return;
    }

    $terms = array( 'Genel', 'PC', 'PlayStation', 'XBOX', 'Nintendo', 'Mobil' );

    foreach ( $terms as $term ) {
        if ( ! term_exists( $term, $taxonomy ) ) {
            wp_insert_term( $term, $taxonomy );
        }
    }

    // Force update XBOX capitalization if it exists as 'Xbox'
    $xbox = get_term_by( 'slug', 'xbox', $taxonomy );
    if ( $xbox && $xbox->name !== 'XBOX' ) {
        wp_update_term( $xbox->term_id, $taxonomy, array( 'name' => 'XBOX' ) );
    }
}
add_action( 'admin_init', 'oyunhaber_insert_default_terms' );

// 6. Explicitly Add "Add New Review" Submenu
function oyunhaber_add_review_submenu() {
    add_submenu_page(
        'edit.php?post_type=news',          // Parent slug (Content Management)
        'Yeni İnceleme Ekle',               // Page Title
        'Yeni İnceleme Ekle',               // Menu Title
        'edit_posts',                       // Capability
        'post-new.php?post_type=reviews'    // Menu slug (Direct link to add new)
    );
}
add_action( 'admin_menu', 'oyunhaber_add_review_submenu' );
