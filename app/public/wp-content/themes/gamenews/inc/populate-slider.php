<?php
/**
 * Populate Slider Data
 * 
 * Updates existing slider posts and creates new ones with demo content.
 */

function oyunhaber_populate_slider_data() {
    // Check if we have already run this to avoid infinite loops or overwrites on every refresh
    // We'll use a transient or option.
    if ( get_option( 'oyunhaber_slider_populated_v2' ) ) {
        return;
    }

    // 1. Get existing slider posts
    $args = array(
        'post_type' => 'slider',
        'posts_per_page' => -1,
        'post_status' => 'any'
    );
    $sliders = get_posts( $args );

    // Demo Data Definitions
    $demo_slides = [
        [
            'title'   => 'ELDEN RING: SHADOW OF THE ERDTREE',
            'content' => 'Tarnished, geri dönme vakti geldi. FromSoftware\'in başyapıtı için beklenen devasa genişleme paketi yaklaşıyor.',
            'url'     => home_url('/haberler/elden-ring/'),
            'image'   => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1920&auto=format&fit=crop' // Dark Fantasy Vibe
        ],
        [
            'title'   => 'BALDUR\'S GATE 3: YILIN OYUNU',
            'content' => 'Zindanlar ve Ejderhalar evreninde geçen, seçimlerinizle şekillenen destansı bir RPG deneyimi.',
            'url'     => home_url('/incelemeler/baldurs-gate-3/'),
            'image'   => 'https://images.unsplash.com/photo-1632213702844-1e0615781374?q=80&w=1920&auto=format&fit=crop' // Fantasy Castle
        ],
        [
            'title'   => 'STARFIELD: UZAYIN DERİNLİKLERİ',
            'content' => 'Bethesda\'nın yeni başyapıtında yıldızlararası bir yolculuğa çıkın. Sınırsız keşif ve macera sizi bekliyor.',
            'url'     => home_url('/incelemeler/starfield/'),
            'image'   => 'https://images.unsplash.com/photo-1614726365723-49cfae9f0295?q=80&w=1920&auto=format&fit=crop' // Nebula/Space
        ],
        [
            'title'   => 'CYBERPUNK 2077: PHANTOM LIBERTY',
            'content' => 'Night City\'nin karanlık sırlarını çözmeye hazır mısınız? Yeni casusluk gerilim eklentisi ile aksiyon dorukta.',
            'url'     => home_url('/haberler/cyberpunk-2077/'),
            'image'   => 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?q=80&w=1920&auto=format&fit=crop' // Neon City
        ],
        [
            'title'   => 'GTA VI: BEKLENEN AN GELİYOR',
            'content' => 'Vice City\'nin neon ışıkları altında yeni bir efsane doğuyor. Rockstar Games\'in en büyük projesi hakkında bilmeniz gereken her şey.',
            'url'     => home_url('/haberler/gta-vi/'),
            'image'   => 'https://images.unsplash.com/photo-1533240561655-e7cb3f23a54d?q=80&w=1920&auto=format&fit=crop' // Palm Trees/Sunset
        ],
        [
            'title'   => 'TEKKEN 8: TURNUVA BAŞLIYOR',
            'content' => 'Yumrukların konuştuğu efsanevi dövüş serisi, yeni nesil grafikleri ve agresif oynanışıyla geri dönüyor.',
            'url'     => home_url('/haberler/tekken-8/'),
            'image'   => 'https://images.unsplash.com/photo-1593789197064-54c861d4dc09?q=80&w=1920&auto=format&fit=crop' // Dynamic Action
        ],
        [
            'title'   => 'FINAL FANTASY VII REBIRTH',
            'content' => 'Cloud ve ekibinin Midgar dışındaki maceraları devam ediyor. Yeniden doğuşa tanıklık edin.',
            'url'     => home_url('/haberler/ffvii-rebirth/'),
            'image'   => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=1920&auto=format&fit=crop' // Fantasy landscape
        ]
    ];

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // 2. Update existing posts or Create new ones
    foreach ($demo_slides as $index => $data) {
        
        $post_id = 0;

        if ( isset($sliders[$index]) ) {
            // Update existing
            $post_id = $sliders[$index]->ID;
            $update_args = array(
                'ID'           => $post_id,
                'post_title'   => $data['title'],
                'post_content' => $data['content'],
                'post_status'  => 'publish'
            );
            wp_update_post( $update_args );
        } else {
            // Create new
            $insert_args = array(
                'post_type'    => 'slider',
                'post_title'   => $data['title'],
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'menu_order'   => $index
            );
            $post_id = wp_insert_post( $insert_args );
        }

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            // Update Meta URL
            update_post_meta( $post_id, 'slide_url', $data['url'] );

            // Try to set image if not has one
            if ( ! has_post_thumbnail( $post_id ) ) {
                $image_url = $data['image'];
                $desc      = $data['title'];
                $img_id    = media_sideload_image( $image_url, $post_id, $desc, 'id' );

                if ( ! is_wp_error( $img_id ) ) {
                    set_post_thumbnail( $post_id, $img_id );
                }
            }
        }
    }

    // Mark as populated so we don't run again (unless option deleted)
    update_option( 'oyunhaber_slider_populated_v2', true );
}

add_action( 'init', 'oyunhaber_populate_slider_data' );
