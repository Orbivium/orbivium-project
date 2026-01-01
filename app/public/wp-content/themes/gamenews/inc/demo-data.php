<?php
/**
 * Advanced Demo Data Importer for OyunHaber
 */

function oyunhaber_import_demo_data() {
    // IMPORT STATUS CHECK
    // To force re-import, you can delete this option from database or comment this check out once.
    // For this specific user request, we will check a different option key to allow a "V3" import.
    if ( get_option( 'oyunhaber_demo_v3_imported' ) ) {
        return;
    }

    // Required for media_sideload_image
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $user_id = get_current_user_id();

    // Large Content Array
    $demo_posts = array(
        // NEWS
        array('title' => 'GTA VI: Vice City Haritası Sızdırıldı mı?', 'type' => 'news', 'platform' => 'Genel', 'keyword' => 'gta,city', 'video_url' => 'https://www.youtube.com/watch?v=QdBZY2fkU-0'),
        array('title' => 'PlayStation 5 Pro için Çıkış Tarihi Netleşiyor', 'type' => 'news', 'platform' => 'PlayStation', 'keyword' => 'playstation,console'),
        array('title' => 'Xbox Game Pass Fiyatlarına Zam Geliyor', 'type' => 'news', 'platform' => 'XBOX', 'keyword' => 'xbox,game'),
        array('title' => 'Nintendo Switch 2 Ekran Özellikleri Belli Oldu', 'type' => 'news', 'platform' => 'Nintendo', 'keyword' => 'nintendo,switch'),
        array('title' => 'Steam Yaz İndirimleri Başladı: İşte Fırsatlar', 'type' => 'news', 'platform' => 'PC', 'keyword' => 'steam,pc'),
        array('title' => 'Valorant Mobile Test Aşamasına Geçti', 'type' => 'news', 'platform' => 'Mobil', 'keyword' => 'mobile,gaming', 'video_url' => 'https://www.youtube.com/watch?v=e_E9W2vsRbQ'),
        array('title' => 'Cyberpunk 2077 İçin Yeni DLC Duyuruldu', 'type' => 'news', 'platform' => 'PC', 'keyword' => 'cyberpunk,neon', 'video_url' => 'https://www.youtube.com/watch?v=KfX9nDKkI_8'),
        array('title' => 'The Elder Scrolls VI: Bekleyiş Sürüyor', 'type' => 'news', 'platform' => 'Xbox', 'keyword' => 'fantasy,rpg'),
        array('title' => 'Sony, Yeni PSVR 2 Oyunlarını Tanıttı', 'type' => 'news', 'platform' => 'PlayStation', 'keyword' => 'vr,gaming'),
        array('title' => 'League of Legends Yeni Şampiyon Yetenekleri', 'type' => 'news', 'platform' => 'PC', 'keyword' => 'esports,league'),
        
        // REVIEWS
        array('title' => 'Alan Wake 2 İnceleme: Korkunun Yeni Yüzü', 'type' => 'reviews', 'platform' => 'PC', 'keyword' => 'horror,forest', 'video_url' => 'https://www.youtube.com/watch?v=dlQ3FeNuCk0'),
        array('title' => 'Marvel\'s Spider-Man 2 İnceleme', 'type' => 'reviews', 'platform' => 'PlayStation', 'keyword' => 'spiderman,city', 'video_url' => 'https://www.youtube.com/watch?v=bgqGdIoa52s'),
        array('title' => 'Starfield: Uzayda Bir Yıl Sonra', 'type' => 'reviews', 'platform' => 'Xbox', 'keyword' => 'space,planet', 'video_url' => 'https://www.youtube.com/watch?v=kfYEiTdsyas'),
        array('title' => 'Super Mario Bros. Wonder Detaylı Bakış', 'type' => 'reviews', 'platform' => 'Nintendo', 'keyword' => 'mario,colorful'),
        array('title' => 'Final Fantasy VII Rebirth: Efsane Devam Ediyor', 'type' => 'reviews', 'platform' => 'PlayStation', 'keyword' => 'fantasy,sword', 'video_url' => 'https://www.youtube.com/watch?v=H74t1dM-o2U'),
        array('title' => 'Genshin Impact: Fontaine Güncellemesi', 'type' => 'reviews', 'platform' => 'Mobil', 'keyword' => 'anime,landscape'),
        array('title' => 'Diablo IV: Sezon 4 Değerlendirmesi', 'type' => 'reviews', 'platform' => 'PC', 'keyword' => 'dark,demon'),
        array('title' => 'Forza Motorsport Grafik Analizi', 'type' => 'reviews', 'platform' => 'Xbox', 'keyword' => 'car,racing'),
        array('title' => 'Zelda: Tears of the Kingdom - Bir Başyapıt', 'type' => 'reviews', 'platform' => 'Nintendo', 'keyword' => 'zelda,adventure', 'video_url' => 'https://www.youtube.com/watch?v=uHGShqcAHlQ'),
        array('title' => 'PUBG Mobile Yeni Harita Rehberi', 'type' => 'reviews', 'platform' => 'Mobil', 'keyword' => 'battle,royale'),
    );

    foreach ( $demo_posts as $item ) {
        // Prevent Duplicates
        $existing = get_page_by_title( $item['title'], OBJECT, $item['type'] );
        if ( $existing ) {
            // Update existing post with video url if present in definition
            if ( isset($item['video_url']) ) {
                update_post_meta( $existing->ID, '_oyunhaber_video_url', $item['video_url'] );
            }
            continue;
        }

        // Create Post
        $post_data = array(
            'post_title'    => $item['title'],
            'post_content'  => '<!-- wp:paragraph --><p><strong>' . $item['title'] . '</strong> hakkında detaylar gelmeye devam ediyor. Oyun dünyasının merakla beklediği bu gelişme, oyuncuları heyecanlandırdı.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p><!-- /wp:paragraph -->',
            'post_status'   => 'publish',
            'post_type'     => $item['type'],
            'post_author'   => $user_id,
        );

        $post_id = wp_insert_post( $post_data );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            // 1. Set Platform
            $term_name = $item['platform'];
            if($term_name === 'Xbox') $term_name = 'XBOX';
            
            $term = get_term_by( 'name', $term_name, 'platform' );
            if ( $term ) {
                wp_set_object_terms( $post_id, $term->term_id, 'platform' );
            }
            
            // Set Video URL
            if ( isset($item['video_url']) ) {
                update_post_meta( $post_id, '_oyunhaber_video_url', $item['video_url'] );
            }

            // 2. Fetch and Attach Image (This is the heavy part)
            // Using loremflickr with keywords
            $image_url = 'https://loremflickr.com/800/600/' . $item['keyword'] . '/all'; 
            // Adding /all to avoid caching same image for same keyword sometimes

            $desc = "Gamenews Demo Image for " . $item['title'];
            
            // Sideload image
            $image_id = media_sideload_image( $image_url, $post_id, $desc, 'id' );

            if ( ! is_wp_error( $image_id ) ) {
                set_post_thumbnail( $post_id, $image_id );
            }
        }
    }

    // 3. Create Profile Page if not exists (Skipped, already done usually)

    // Mark as V3 imported
    update_option( 'oyunhaber_demo_v3_imported', '1' );
    
    // Flush rewrite rules to ensure the new page and routes work
    flush_rewrite_rules();
}
add_action( 'admin_init', 'oyunhaber_import_demo_data' );
