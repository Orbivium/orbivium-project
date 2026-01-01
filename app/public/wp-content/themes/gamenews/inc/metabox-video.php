<?php
/**
 * Add "Video URL" Meta Box to 'videos' CPT (and others if needed)
 */

function oyunhaber_add_video_meta_box() {
    $screens = ['videos', 'news', 'reviews']; // Allow videos in news/reviews too?
    foreach ( $screens as $screen ) {
        add_meta_box(
            'oyunhaber_video_meta',           // Unique ID
            'Video Ayarları',                 // Box title
            'oyunhaber_video_meta_callback',  // Content callback
            $screen,                          // Post type
            'side'                            // Context
        );
    }
}
add_action( 'add_meta_boxes', 'oyunhaber_add_video_meta_box' );

function oyunhaber_admin_scripts() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'oyunhaber_admin_scripts' );

function oyunhaber_video_meta_callback( $post ) {
    $video_url  = get_post_meta( $post->ID, '_oyunhaber_video_url', true );
    $file_url   = get_post_meta( $post->ID, '_oyunhaber_video_file_url', true );
    
    // Check which one to prioritize for display logic if needed, but we show both inputs
    ?>
    <p><strong>Seçenek 1: Video Bağlantısı (YouTube/Vimeo)</strong></p>
    <input type="text" name="oyunhaber_video_url" id="oyunhaber_video_url" value="<?php echo esc_attr( $video_url ); ?>" style="width:100%;" placeholder="https://www.youtube.com/watch?v=..." />
    
    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #ddd;">

    <p><strong>Seçenek 2: Dosya Yükle (MP4)</strong></p>
    <div style="display: flex; gap: 5px;">
        <input type="text" name="oyunhaber_video_file_url" id="oyunhaber_video_file_url" value="<?php echo esc_attr( $file_url ); ?>" style="width:100%;" placeholder="Veya kütüphaneden video seçin..." />
        <button type="button" class="button" id="upload_video_btn">Dosya Seç</button>
    </div>
    
    <script>
    jQuery(document).ready(function($){
        $('#upload_video_btn').click(function(e) {
            e.preventDefault();
            var image = wp.media({ 
                title: 'Video Yükle',
                multiple: false
            }).open()
            .on('select', function(e){
                var uploaded = image.state().get('selection').first();
                var videoUrl = uploaded.toJSON().url;
                $('#oyunhaber_video_file_url').val(videoUrl);
                // Clear the YouTube url if file selected to avoid confusion? Optional.
                // $('#oyunhaber_video_url').val(''); 
            });
        });
    });
    </script>
    <?php
}

function oyunhaber_save_video_meta( $post_id ) {
    // Save URL
    if ( array_key_exists( 'oyunhaber_video_url', $_POST ) ) {
        update_post_meta(
            $post_id,
            '_oyunhaber_video_url',
            esc_url_raw( $_POST['oyunhaber_video_url'] )
        );
    }
    // Save File URL
    if ( array_key_exists( 'oyunhaber_video_file_url', $_POST ) ) {
        update_post_meta(
            $post_id,
            '_oyunhaber_video_file_url',
            esc_url_raw( $_POST['oyunhaber_video_file_url'] )
        );
    }
}
add_action( 'save_post', 'oyunhaber_save_video_meta' );
