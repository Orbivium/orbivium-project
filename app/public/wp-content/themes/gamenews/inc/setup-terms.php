<?php
/**
 * Setup Default Terms (Categories & Tags) for Navigation logic
 *
 * @package OyunHaber
 */

function oyunhaber_ensure_default_terms() {
    // 1. Ensure 'Rehberler' Category exists
    if ( ! term_exists( 'rehberler', 'category' ) ) {
        wp_insert_term(
            'Rehberler',
            'category',
            array(
                'slug'        => 'rehberler',
                'description' => 'Oyun rehberleri, ipuçları ve taktikler.'
            )
        );
    }
    
    // 2. Ensure Special Tags exist
    $tags = array(
        'ps-plus'          => 'PS Plus',
        'game-pass'        => 'Game Pass',
        'ozel-oyunlar'     => 'Özel Oyunlar',
        'ucretsiz-oyunlar' => 'Ücretsiz Oyunlar'
    );

    foreach ( $tags as $slug => $name ) {
        if ( ! term_exists( $slug, 'post_tag' ) ) {
            wp_insert_term(
                $name,
                'post_tag',
                array(
                    'slug' => $slug
                )
            );
        }
    }
}
add_action( 'admin_init', 'oyunhaber_ensure_default_terms' );
