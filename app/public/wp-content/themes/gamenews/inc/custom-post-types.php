<?php
/**
 * Register Custom Post Types for OyunHaber
 *
 * @package OyunHaber
 */

if ( ! function_exists( 'oyunhaber_register_cpts' ) ) :
	function oyunhaber_register_cpts() {

		// 1. Haberler (News)
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
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true, // Block Editor support
			'menu_icon'     => 'dashicons-megaphone',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
			'rewrite'       => array( 'slug' => 'haberler' ), // Custom slug
		) );

		// 2. İncelemeler (Reviews)
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
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
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
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
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
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
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

        register_taxonomy( 'platform', array( 'news', 'reviews' ), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'platform' ),
            'show_in_rest'      => true,
        ) );

	}
endif;
add_action( 'init', 'oyunhaber_register_cpts' );

// Populate Default Terms
function oyunhaber_insert_default_terms() {
    if ( ! term_exists( 'PC', 'platform' ) ) {
        wp_insert_term( 'PC', 'platform' );
    }
    if ( ! term_exists( 'PlayStation', 'platform' ) ) {
        wp_insert_term( 'PlayStation', 'platform' );
    }
    if ( ! term_exists( 'Xbox', 'platform' ) ) {
        wp_insert_term( 'Xbox', 'platform' );
    }
    if ( ! term_exists( 'Nintendo', 'platform' ) ) {
        wp_insert_term( 'Nintendo', 'platform' );
    }
    if ( ! term_exists( 'Mobil', 'platform' ) ) {
        wp_insert_term( 'Mobil', 'platform' );
    }
}
add_action( 'init', 'oyunhaber_insert_default_terms' );
