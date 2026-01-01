<?php
/**
 * Custom Admin Editor Enhancements
 * Provides a modern, unified UI for selecting Platforms, Categories, and Tags.
 *
 * @package OyunHaber
 */

/**
 * 1. Register the Meta Box
 */
function oyunhaber_register_classification_metabox() {
    $screens = array( 'news', 'reviews', 'videos', 'esports' );
    
    foreach ( $screens as $screen ) {
        add_meta_box(
            'oyunhaber_classification_box',
            __( '🚀 İçerik Sınıflandırma (Platform & Kategori)', 'oyunhaber' ),
            'oyunhaber_render_classification_metabox',
            $screen,
            'side',
            'core'
        );
    }
}
add_action( 'add_meta_boxes', 'oyunhaber_register_classification_metabox' );

/**
 * 2. Render the Meta Box Content
 */
function oyunhaber_render_classification_metabox( $post ) {
    // Security Nonce
    wp_nonce_field( 'oyunhaber_classification_save', 'oyunhaber_classification_nonce' );

    // Get current values
    $assigned_platforms = wp_get_object_terms( $post->ID, 'platform', array( 'fields' => 'slugs' ) );
    $assigned_tags      = wp_get_object_terms( $post->ID, 'post_tag', array( 'fields' => 'slugs' ) );
    $assigned_cats      = wp_get_object_terms( $post->ID, 'category', array( 'fields' => 'slugs' ) );

    $is_rehber = in_array( 'rehberler', $assigned_cats );
    
    // Determine Current "Sub-Category" Selection
    // Default is 'standard'
    $current_sub_selection = 'standard';
    
    if ( $is_rehber ) {
        $current_sub_selection = 'rehberler';
    }
    
    // Check for special tags (Priority over Rehber if multiple? Let's assume tags are specific)
    $all_special_tags = array('ps-plus', 'game-pass', 'ozel-oyunlar', 'ucretsiz-oyunlar');
    foreach( $all_special_tags as $tag ) {
        if ( in_array( $tag, $assigned_tags ) ) {
            $current_sub_selection = $tag;
            break; 
        }
    }

    // Taxonomy Data
    $platforms = array(
        'pc'          => array( 'label' => 'PC', 'icon' => 'dashicons-desktop' ),
        'playstation' => array( 'label' => 'PlayStation', 'icon' => 'dashicons-games' ),
        'xbox'        => array( 'label' => 'XBOX', 'icon' => 'dashicons-cloud' ),
        'nintendo'    => array( 'label' => 'Nintendo', 'icon' => 'dashicons-smiley' ),
        'mobil'       => array( 'label' => 'Mobil', 'icon' => 'dashicons-smartphone' ),
        'genel'       => array( 'label' => 'Genel', 'icon' => 'dashicons-admin-site' )
    );

    // Special Options Mapping
    $special_options = array(
        'playstation' => array( 'slug' => 'ps-plus', 'label' => 'PS Plus' ),
        'xbox'        => array( 'slug' => 'game-pass', 'label' => 'Game Pass' ),
        'nintendo'    => array( 'slug' => 'ozel-oyunlar', 'label' => 'Özel Oyunlar' ),
        'mobil'       => array( 'slug' => 'ucretsiz-oyunlar', 'label' => 'Ücretsiz Oyunlar' )
    );

    // Get Post Type Label
    $pt = get_post_type( $post );
    $pt_obj = get_post_type_object( $pt );
    $pt_label = ( $pt == 'news' ) ? 'Haberler' : ( ($pt == 'reviews') ? 'İncelemeler' : $pt_obj->labels->name );

    ?>

    <div class="oh-editor-panel">
        
        <!-- SECTION: PLATFORM & SUB-CATEGORY -->
        <h4 class="oh-section-title">1. Platform ve Alt Kategori Seçimi</h4>
        <p class="oh-hint-text">İçeriğin hangi platformda ve hangi sekmede görüneceğini seçin.</p>

        <div class="oh-platform-list">
            <?php foreach ( $platforms as $p_slug => $data ) : 
                $is_active = in_array( $p_slug, $assigned_platforms );
                // Has special option?
                $special = isset( $special_options[$p_slug] ) ? $special_options[$p_slug] : false;
            ?>
                <div class="oh-platform-item <?php echo $is_active ? 'active' : ''; ?>">
                    
                    <!-- Header: Platform Selection -->
                    <label class="oh-platform-header">
                        <input type="checkbox" name="oh_platform[]" value="<?php echo esc_attr( $p_slug ); ?>" <?php checked( $is_active ); ?> class="oh-platform-check" data-slug="<?php echo esc_attr( $p_slug ); ?>">
                        <span class="dashicons <?php echo esc_attr( $data['icon'] ); ?>"></span>
                        <span class="platform-name"><?php echo esc_html( $data['label'] ); ?></span>
                    </label>

                    <!-- Body: Sub-Category Selection (Only visible if active) -->
                    <div class="oh-platform-body" id="body-<?php echo esc_attr( $p_slug ); ?>" style="<?php echo $is_active ? '' : 'display:none;'; ?>">
                        
                        <!-- Option: Standard (News/Review) -->
                        <label class="oh-sub-option">
                            <input type="radio" name="oh_sub_cat" value="standard" <?php checked( $current_sub_selection, 'standard' ); ?>>
                            <span class="oh-radio-fake"></span>
                            <span class="oh-sub-label">
                                <?php echo esc_html( $pt_label ); ?> (Standart)
                            </span>
                        </label>

                        <!-- Option: Rehberler (Common) -->
                        <label class="oh-sub-option">
                            <input type="radio" name="oh_sub_cat" value="rehberler" <?php checked( $current_sub_selection, 'rehberler' ); ?>>
                            <span class="oh-radio-fake"></span>
                            <span class="oh-sub-label">Rehberler</span>
                        </label>

                        <!-- Option: Special (If exists) -->
                        <?php if ( $special ) : ?>
                        <label class="oh-sub-option special-option">
                            <input type="radio" name="oh_sub_cat" value="<?php echo esc_attr( $special['slug'] ); ?>" <?php checked( $current_sub_selection, $special['slug'] ); ?>>
                            <span class="oh-radio-fake"></span>
                            <span class="oh-sub-label"><?php echo esc_html( $special['label'] ); ?></span>
                        </label>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- SECTION: GENERAL TAGS -->
        <div class="oh-panel-section" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #444;">
            <h4 class="oh-section-title">2. Diğer Etiketler</h4>
            <?php
            // Filter out special tags to show only general ones
            $general_tags = array_diff( $assigned_tags, $all_special_tags );
            $general_tags_str = implode( ', ', $general_tags );
            ?>
            <textarea name="oh_general_tags" rows="2" class="oh-text-input" placeholder="Örn: aksiyon, fps, hikaye..."><?php echo esc_textarea( $general_tags_str ); ?></textarea>
        </div>

    </div>

    <!-- STYLES & SCRIPTS -->
    <style>
        .oh-editor-panel { background: #1e1e1e; padding: 15px; border-radius: 8px; color: #ddd; }
        
        .oh-section-title { margin: 0 0 5px 0; font-size: 13px; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #ff4757; display: inline-block; padding-bottom: 3px; }
        .oh-hint-text { font-size: 12px; color: #888; margin: 0 0 15px 0; }

        .oh-platform-list { display: flex; flex-direction: column; gap: 10px; }
        
        .oh-platform-item {
            background: #252525;
            border: 1px solid #333;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.2s;
        }
        .oh-platform-item.active { border-color: #666; background: #2a2a2a; }

        /* Header */
        .oh-platform-header {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            user-select: none;
        }
        .oh-platform-header:hover { background: #333; }
        .oh-platform-check { margin-right: 10px !important; transform: scale(1.2); }
        .platform-name { font-weight: 700; margin-left: 8px; font-size: 14px; }
        .oh-platform-item .dashicons { color: #888; }
        .oh-platform-item.active .dashicons { color: #ff4757; }

        /* Body (Sub Options) */
        .oh-platform-body {
            background: #1a1a1a;
            padding: 10px 15px 15px 45px; /* Indent */
            border-top: 1px solid #333;
        }
        
        .oh-sub-option {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .oh-sub-option:last-child { margin-bottom: 0; }
        .oh-sub-option input[type="radio"] { display: none; }
        
        /* Custom Radio */
        .oh-radio-fake {
            width: 16px; height: 16px;
            border: 2px solid #555;
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
        }
        .oh-sub-option input:checked + .oh-radio-fake {
            border-color: #ff4757;
        }
        .oh-sub-option input:checked + .oh-radio-fake::after {
            content: '';
            position: absolute;
            top: 2px; left: 2px;
            width: 8px; height: 8px;
            background: #ff4757;
            border-radius: 50%;
        }
        
        .oh-sub-label { font-size: 13px; color: #aaa; }
        .oh-sub-option:hover .oh-sub-label { color: #fff; }
        .oh-sub-option input:checked ~ .oh-sub-label { color: #fff; font-weight: 600; }
        
        .special-option .oh-sub-label { color: #ff9f43; }
        
        .oh-text-input {
            width: 100%;
            background: #111;
            border: 1px solid #333;
            color: #ddd;
            padding: 8px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Toggle Platform Body
        $('.oh-platform-check').on('change', function() {
            var slug = $(this).data('slug');
            var body = $('#body-' + slug);
            var item = $(this).closest('.oh-platform-item');
            
            if($(this).is(':checked')) {
                body.slideDown(200);
                item.addClass('active');
            } else {
                body.slideUp(200);
                item.removeClass('active');
            }
        });
        
        // Ensure checked platforms are open on load
        $('.oh-platform-check:checked').closest('.oh-platform-item').addClass('active');
    });
    </script>
    <?php
}

/**
 * 3. Save Data (Updated for Sub-Category Logic)
 */
function oyunhaber_save_classification_meta( $post_id ) {
    if ( ! isset( $_POST['oyunhaber_classification_nonce'] ) || ! wp_verify_nonce( $_POST['oyunhaber_classification_nonce'], 'oyunhaber_classification_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // 1. Save Platforms
    $platforms = isset( $_POST['oh_platform'] ) ? array_map( 'sanitize_text_field', $_POST['oh_platform'] ) : array();
    wp_set_object_terms( $post_id, $platforms, 'platform' );

    // 2. Handle Sub-Category (Radio Selection)
    $sub_cat = isset( $_POST['oh_sub_cat'] ) ? sanitize_text_field( $_POST['oh_sub_cat'] ) : 'standard';

    // Reset managed terms/tags first
    $managed_tags = array('ps-plus', 'game-pass', 'ozel-oyunlar', 'ucretsiz-oyunlar');
    $current_tags = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'slugs' ) );
    $tags_to_keep = array_diff( $current_tags, $managed_tags ); // Remove old managed tags
    
    // Manage 'Rehberler' category
    $current_cats = wp_get_object_terms( $post_id, 'category', array( 'fields' => 'slugs' ) );
    $cats_to_keep = array_diff( $current_cats, array( 'rehberler' ) ); // Default remove rehberler

    // Apply new selection
    if ( $sub_cat === 'rehberler' ) {
        // Add Rehber Category
        $cats_to_keep[] = 'rehberler';
    } elseif ( in_array( $sub_cat, $managed_tags ) ) {
        // Add Special Tag
        $tags_to_keep[] = $sub_cat;
    }
    // If 'standard', we just don't add rehber or special tags. Maintained pure post type.

    // 3. Save General Tags
    $general_tags_input = isset( $_POST['oh_general_tags'] ) ? sanitize_text_field( $_POST['oh_general_tags'] ) : '';
    if ( ! empty( $general_tags_input ) ) {
        $gen_tags = array_map( 'trim', explode( ',', $general_tags_input ) );
        $tags_to_keep = array_merge( $tags_to_keep, $gen_tags );
    }

    // Commit changes
    wp_set_object_terms( $post_id, $cats_to_keep, 'category' );
    wp_set_object_terms( $post_id, $tags_to_keep, 'post_tag' );
}
add_action( 'save_post', 'oyunhaber_save_classification_meta' );

/**
 * 4. Hide Default Meta Boxes
 */
function oyunhaber_remove_default_metaboxes() {
    $screens = array( 'news', 'reviews', 'videos', 'esports' );
    foreach ( $screens as $screen ) {
        remove_meta_box( 'platformdiv', $screen, 'side' );      
        remove_meta_box( 'tagsdiv-post_tag', $screen, 'side' ); 
        remove_meta_box( 'categorydiv', $screen, 'side' );
    }
}
add_action( 'admin_menu', 'oyunhaber_remove_default_metaboxes', 999 );


