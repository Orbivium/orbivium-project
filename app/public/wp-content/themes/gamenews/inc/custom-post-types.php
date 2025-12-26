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
// Populate Default Terms
function oyunhaber_insert_default_terms() {
    $taxonomy = 'platform';

    // Check if taxonomy exists to avoid errors
    if ( ! taxonomy_exists( $taxonomy ) ) {
        return;
    }

    $terms = array( 'Genel', 'PC', 'PlayStation', 'Xbox', 'Nintendo', 'Mobil' );

    foreach ( $terms as $term ) {
        if ( ! term_exists( $term, $taxonomy ) ) {
            wp_insert_term( $term, $taxonomy );
        }
    }
}
add_action( 'admin_init', 'oyunhaber_insert_default_terms' );
