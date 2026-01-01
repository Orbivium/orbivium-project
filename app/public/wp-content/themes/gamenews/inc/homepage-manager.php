<?php
/**
 * Homepage Manager & Featured Posts
 *
 * @package OyunHaber
 */

// 1. Add Meta Box for "Featured" status
function oyunhaber_add_featured_meta_box() {
    $screens = [ 'news', 'reviews' ];
    foreach ( $screens as $screen ) {
        add_meta_box(
            'oyunhaber_featured_box',           // Unique ID
            'Anasayfa Seçenekleri',             // Box title
            'oyunhaber_featured_meta_box_html', // Content callback
            $screen,                            // Post type
            'side',                             // Context
            'high'                              // Priority
        );
    }
}
add_action( 'add_meta_boxes', 'oyunhaber_add_featured_meta_box' );

function oyunhaber_featured_meta_box_html( $post ) {
    $value = get_post_meta( $post->ID, '_oyunhaber_is_featured', true );
    ?>
    <label for="oyunhaber_is_featured">
        <input type="checkbox" name="oyunhaber_is_featured" id="oyunhaber_is_featured" value="1" <?php checked( $value, 1 ); ?> />
        Bu içeriği <strong>Anasayfa Manşet</strong> alanında göster
    </label>
    <?php
}

function oyunhaber_save_featured_meta_box( $post_id ) {
    if ( array_key_exists( 'oyunhaber_is_featured', $_POST ) ) {
        update_post_meta( $post_id, '_oyunhaber_is_featured', 1 );
    } else {
        delete_post_meta( $post_id, '_oyunhaber_is_featured' );
    }
}
add_action( 'save_post', 'oyunhaber_save_featured_meta_box' );

// 2. Admin Menu for Homepage
function oyunhaber_register_homepage_admin() {
    add_menu_page(
        'Anasayfa Yönetimi',
        'Anasayfa',
        'edit_posts', // Moderators can access
        'oyunhaber-homepage',
        'oyunhaber_render_homepage_admin',
        'dashicons-house',
        2
    );
}
add_action( 'admin_menu', 'oyunhaber_register_homepage_admin' );

function oyunhaber_render_homepage_admin() {
    // Save Settings
    if ( isset($_POST['oyunhaber_home_save']) && check_admin_referer('oyunhaber_home_options') ) {
        update_option('oyunhaber_home_hero_title', sanitize_text_field($_POST['hero_title']));
        echo '<div class="updated notice"><p>Ayarlar kaydedildi.</p></div>';
    }

    $hero_posts = new WP_Query(array(
        'post_type' => array('news', 'reviews'),
        'meta_key'   => '_oyunhaber_is_featured',
        'meta_value' => '1',
        'posts_per_page' => -1
    ));
    ?>
    <div class="wrap">
        <h1>Anasayfa Yönetimi</h1>
        <p>Buradan anasayfa manşet alanını ve öne çıkan içerikleri yönetebilirsiniz.</p>
        
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
            
            <!-- Left: Featured List -->
            <div class="card" style="padding:20px;">
                <h2>Manşetteki İçerikler</h2>
                <p class="description">İçerik eklemek için normal yazı düzenleme ekranında "Anasayfa Manşet" kutucuğunu işaretleyin.</p>
                
                <?php if ( $hero_posts->have_posts() ) : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Görsel</th>
                                <th>Başlık</th>
                                <th>Tür</th>
                                <th>Ayarlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ( $hero_posts->have_posts() ) : $hero_posts->the_post(); ?>
                            <tr>
                                <td style="width:60px;">
                                    <?php if(has_post_thumbnail()) the_post_thumbnail('thumbnail', array('style'=>'width:50px;height:50px;object-fit:cover;border-radius:4px;')); ?>
                                </td>
                                <td><strong><?php the_title(); ?></strong></td>
                                <td><?php echo ucfirst(get_post_type()); ?></td>
                                <td><a href="<?php echo get_edit_post_link(); ?>" class="button button-small">Düzenle</a></td>
                            </tr>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>Henüz manşete eklenmiş bir içerik yok.</p>
                <?php endif; ?>
            </div>

            <!-- Right: Settings -->
            <div class="card" style="padding:20px; height:fit-content;">
                <h2>Görünüm Ayarları</h2>
                <form method="post">
                    <?php wp_nonce_field('oyunhaber_home_options'); ?>
                    <p>
                        <label>Manşet Alanı Başlığı (Opsiyonel):</label><br>
                        <input type="text" name="hero_title" value="<?php echo esc_attr(get_option('oyunhaber_home_hero_title')); ?>" class="widefat">
                    </p>
                    <input type="submit" name="oyunhaber_home_save" class="button button-primary" value="Kaydet">
                </form>
            </div>
        </div>
    </div>
    <?php
}
