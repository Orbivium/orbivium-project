<?php
/**
 * Register Custom Post Types for OyunHaber
 *
 * @package OyunHaber
 */

if ( ! function_exists( 'oyunhaber_register_cpts' ) ) :
	function oyunhaber_register_cpts() {

		// 1. İçerik (Formerly News) - MAIN CONTENT TYPE
		register_post_type( 'news', array(
			'labels' => array(
				'name'               => __( 'İçerikler', 'oyunhaber' ),
				'singular_name'      => __( 'İçerik', 'oyunhaber' ),
				'add_new'            => __( 'Yeni İçerik Ekle', 'oyunhaber' ),
				'add_new_item'       => __( 'Yeni İçerik Ekle', 'oyunhaber' ),
				'edit_item'          => __( 'İçeriği Düzenle', 'oyunhaber' ),
				'new_item'           => __( 'Yeni İçerik', 'oyunhaber' ),
				'all_items'          => __( 'Tüm İçerikler', 'oyunhaber' ),
				'view_item'          => __( 'İçeriği Görüntüle', 'oyunhaber' ),
				'search_items'       => __( 'İçerik Ara', 'oyunhaber' ),
				'not_found'          => __( 'İçerik Bulunamadı', 'oyunhaber' ),
				'menu_name'          => __( 'İçerik Yönetimi', 'oyunhaber' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true, // Block Editor support
			'menu_icon'     => 'dashicons-admin-post',
			'menu_position' => 4, // High priority
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ), 
			'rewrite'       => array( 'slug' => 'icerik' ), // Generic slug
		) );

		// 2. İncelemeler REMOVED (Merged into News)
        /*
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
            'show_in_menu'  => 'edit.php?post_type=news',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'author' ),
			'taxonomies'    => array( 'category', 'post_tag' ),
			'rewrite'       => array( 'slug' => 'incelemeler' ),
		) );
        */

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

        // Taxonomy: Content Type (To distinguish Haber vs İnceleme)
        register_taxonomy( 'content_type', array( 'news' ), array(
            'hierarchical'      => true,
            'labels'            => array(
                'name' => 'İçerik Türü',
                'singular_name' => 'İçerik Türü',
                'menu_name' => 'İçerik Türü'
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tur' ),
            'show_in_rest'      => true,
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

        register_taxonomy( 'platform', array( 'news', 'videos', 'esports' ), array(
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
function oyunhaber_add_slider_meta_box() {
    add_meta_box(
        'oyunhaber_slider_expiry_box',
        'Yayınlanma Süresi Ayarları',
        'oyunhaber_slider_expiry_html',
        'slider',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'oyunhaber_add_slider_meta_box' );

function oyunhaber_slider_expiry_html( $post ) {
    $expiry_date = get_post_meta( $post->ID, '_oyunhaber_slider_expiry', true );
    ?>
    <div>
        <label style="display:block; font-weight:600; margin-bottom:5px;">Yayından Kaldırılma Tarihi (Opsiyonel)</label>
        <p class="description" style="margin-bottom:8px;">Bu tarihten sonra slider otomatik olarak gizlenir. Boş bırakırsanız süresiz kalır.</p>
        
        <input type="date" name="oyunhaber_slider_expiry" id="oyunhaber_slider_expiry" value="<?php echo esc_attr($expiry_date); ?>" style="width:100%; margin-bottom:10px;">
        
        <div style="display:flex; gap:5px; flex-wrap:wrap;">
            <button type="button" class="button button-small date-preset-slider" data-days="2">+2 Gün</button>
            <button type="button" class="button button-small date-preset-slider" data-days="7">+1 Hafta</button>
            <button type="button" class="button button-small date-preset-slider" data-days="30">+1 Ay</button>
            <button type="button" class="button button-small date-preset-slider" data-clear="true" style="color:#b32d2e;">Süresiz</button>
        </div>

        <script>
            jQuery(document).ready(function($){
                $('.date-preset-slider').on('click', function(){
                    if($(this).data('clear')) {
                        $('#oyunhaber_slider_expiry').val('');
                        return;
                    }
                    var days = $(this).data('days');
                    var date = new Date();
                    date.setDate(date.getDate() + parseInt(days));
                    var dateString = date.toISOString().split('T')[0];
                    $('#oyunhaber_slider_expiry').val(dateString);
                });
            });
        </script>
    </div>
    <?php
}

function oyunhaber_save_slider_meta_box( $post_id ) {
    if ( isset( $_POST['oyunhaber_slider_expiry'] ) ) {
        update_post_meta( $post_id, '_oyunhaber_slider_expiry', sanitize_text_field( $_POST['oyunhaber_slider_expiry'] ) );
    }
}
add_action( 'save_post', 'oyunhaber_save_slider_meta_box' );


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

// 6. Explicitly Add "Add New Review" Submenu REMOVED
/*
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
*/
