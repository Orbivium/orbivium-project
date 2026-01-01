<?php
/**
 * Moderator Role & Experience Customization
 *
 * @package OyunHaber
 */

/**
 * 1. Configure Roles & Capabilities (Moderator & Author)
 */
function oyunhaber_setup_roles_and_caps() {
    // --- 1. MODERATOR SETUP ---
    $role = get_role( 'moderator' );
    
    if ( ! $role ) {
        // Create role if it doesn't exist
        add_role(
            'moderator',
            __( 'Moderatör', 'oyunhaber' ),
            array(
                'read'                   => true,
                'upload_files'           => true,
                'edit_posts'             => true,
                'edit_others_posts'      => true,
                'edit_published_posts'   => true,
                'publish_posts'          => true, // Can publish
                'delete_posts'           => true,
                'delete_others_posts'    => true,
                'delete_published_posts' => true,
                'moderate_comments'      => true,
                'manage_categories'      => true,
            )
        );
    } else {
        // Ensure capabilities are correct if role exists
        $role->add_cap( 'read' );
        $role->add_cap( 'upload_files' );
        
        // Content Management
        $role->add_cap( 'edit_posts' );
        $role->add_cap( 'edit_others_posts' );
        $role->add_cap( 'edit_published_posts' );
        $role->add_cap( 'publish_posts' ); // CRITICAL: Moderator CAN publish
        
        // Delete Capabilities
        $role->add_cap( 'delete_posts' );
        $role->add_cap( 'delete_others_posts' );
        $role->add_cap( 'delete_published_posts' );
        
        // Management
        $role->add_cap( 'moderate_comments' );
        $role->add_cap( 'manage_categories' );
        
        // Explicitly remove sensitive caps
        $role->remove_cap( 'edit_theme_options' );
        $role->remove_cap( 'install_plugins' );
        $role->remove_cap( 'activate_plugins' );
        $role->remove_cap( 'edit_users' );
        $role->remove_cap( 'create_users' );
        $role->remove_cap( 'delete_users' );
        $role->remove_cap( 'manage_options' );
    }

    // --- 2. AUTHOR SETUP (RESTRICTIONS) ---
    // Author should NOT be able to publish. Only draft/pending.
    $author = get_role( 'author' );
    if ( $author ) {
        $author->remove_cap( 'publish_posts' ); // Remove publish capability
        $author->add_cap( 'edit_posts' );       // Can edit own
        $author->add_cap( 'delete_posts' );     // Can delete own drafts
        
        // Ensure they can't edit others
        $author->remove_cap( 'edit_others_posts' );
        $author->remove_cap( 'delete_others_posts' );
    }
}
add_action( 'init', 'oyunhaber_setup_roles_and_caps' );

/**
 * 2. Login Redirect for Moderator
 */
function oyunhaber_moderator_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'moderator', $user->roles ) ) {
            return admin_url( 'index.php' );
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'oyunhaber_moderator_login_redirect', 10, 3 );

/**
 * 3. Clean up Admin Menu for Moderator
 */
function oyunhaber_moderator_admin_menu_cleanup() {
    $user = wp_get_current_user();
    if ( in_array( 'moderator', (array) $user->roles ) ) {
        remove_menu_page( 'themes.php' );             
        remove_menu_page( 'plugins.php' );            
        remove_menu_page( 'users.php' );              
        remove_menu_page( 'tools.php' );              
        remove_menu_page( 'options-general.php' );    
        remove_menu_page( 'edit.php?post_type=page' ); 
        remove_menu_page( 'edit.php' ); // Remove Standard Posts (Yazılar) 
        
        // Rename Dashboard
        global $menu;
        foreach ( $menu as $key => $val ) {
            if ( $val[2] == 'index.php' ) {
                $menu[$key][0] = __( 'Moderatör Paneli', 'oyunhaber' );
            }
        }
    }
}
add_action( 'admin_menu', 'oyunhaber_moderator_admin_menu_cleanup', 999 );

/**
 * 4. Security: Block Access to Restricted Pages via URL
 */
function oyunhaber_moderator_restrict_access() {
    if ( ! is_user_logged_in() ) {
        return;
    }

    $user = wp_get_current_user();
    if ( ! in_array( 'moderator', (array) $user->roles ) ) {
        return;
    }

    global $pagenow;
    
    // List of restricted pages
    $restricted_pages = array(
        'themes.php',
        'customize.php',
        'plugins.php',
        'users.php',
        'user-new.php',
        'user-edit.php',
        'tools.php',
        'options-general.php',
        'options-writing.php',
        'options-reading.php',
        'options-discussion.php',
        'options-media.php',
        'options-permalink.php',
        'options-privacy.php',
        'import.php',
        'export.php'
    );

    $is_restricted = in_array( $pagenow, $restricted_pages ) || strpos( $pagenow, 'options-' ) === 0;

    if ( $is_restricted ) {
        wp_safe_redirect( admin_url() );
        exit;
    }
}
add_action( 'admin_init', 'oyunhaber_moderator_restrict_access' );

/**
 * 5. Hide Screen Options & Help Tabs
 */
function oyunhaber_moderator_remove_help_tabs() {
    $user = wp_get_current_user();
    if ( in_array( 'moderator', (array) $user->roles ) ) {
        $screen = get_current_screen();
        if ( $screen ) {
            $screen->remove_help_tabs();
        }
    }
}
add_action( 'admin_head', 'oyunhaber_moderator_remove_help_tabs' );

/**
 * 6. Custom Moderator Dashboard Widget
 */
function oyunhaber_moderator_dashboard_setup() {
    if ( ! current_user_can( 'moderator' ) ) {
        return;
    }

    // Remove default widgets to clean up the view
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress News
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // At a Glance (Replaced by our custom panel)
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' ); // Site Health
    
    // We KEEP 'dashboard_activity' as per request for "Recent content / recent comments"
    // remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); 

    // Remove Welcome Panel
    remove_action( 'welcome_panel', 'wp_welcome_panel' );

    // Add Custom Moderator Widget
    wp_add_dashboard_widget(
        'oyunhaber_moderator_panel',
        __( 'Moderatör Kontrol Paneli', 'oyunhaber' ),
        'oyunhaber_render_moderator_panel',
        null, 
        null, 
        'normal', 
        'high' // Position at top
    );
}
add_action( 'wp_dashboard_setup', 'oyunhaber_moderator_dashboard_setup' );

/**
 * Render the Custom Dashboard Widget
 */
function oyunhaber_render_moderator_panel() {
    // Get Stats
    $news_count    = wp_count_posts( 'news' );
    $reviews_count = wp_count_posts( 'reviews' );
    $comments_count = wp_count_comments();
    
    $pending_news = isset($news_count->pending) ? $news_count->pending : 0;
    $pending_reviews = isset($reviews_count->pending) ? $reviews_count->pending : 0;
    $pending_comments = isset($comments_count->moderated) ? $comments_count->moderated : 0;
    
    ?>
    <div class="mod-panel-wrapper">
        <!-- Background decoration -->
        <div class="mod-glow-bg"></div>

        <!-- Stats Section -->
        <div class="mod-section-title">DURUM ÖZETİ</div>
        <div class="mod-stats-grid">
            <div class="mod-stat-card">
                <div class="mod-stat-icon"><span class="dashicons dashicons-megaphone"></span></div>
                <div class="mod-stat-info">
                    <span class="mod-stat-number <?php echo $pending_news > 0 ? 'stat-alert' : ''; ?>">
                        <?php echo esc_html( $pending_news ); ?>
                    </span>
                    <span class="mod-stat-label">Bekleyen Haber</span>
                </div>
            </div>
            <div class="mod-stat-card">
                <div class="mod-stat-icon"><span class="dashicons dashicons-star-half"></span></div>
                <div class="mod-stat-info">
                    <span class="mod-stat-number <?php echo $pending_reviews > 0 ? 'stat-alert' : ''; ?>">
                        <?php echo esc_html( $pending_reviews ); ?>
                    </span>
                    <span class="mod-stat-label">Bekleyen İnceleme</span>
                </div>
            </div>
            <div class="mod-stat-card">
                <div class="mod-stat-icon"><span class="dashicons dashicons-admin-comments"></span></div>
                <div class="mod-stat-info">
                    <span class="mod-stat-number <?php echo $pending_comments > 0 ? 'stat-urgent' : ''; ?>">
                        <?php echo esc_html( $pending_comments ); ?>
                    </span>
                    <span class="mod-stat-label">Yorum Onayı</span>
                </div>
            </div>
        </div>

        <!-- Actions Section -->
        <div class="mod-section-title">HIZLI İŞLEMLER</div>
        <div class="mod-actions-grid">
            <a href="<?php echo admin_url( 'edit.php?post_status=pending&post_type=news' ); ?>" class="mod-btn">
                <span>Bekleyen Haberler</span> <span class="dashicons dashicons-arrow-right-alt2"></span>
            </a>
            <a href="<?php echo admin_url( 'edit.php?post_status=pending&post_type=reviews' ); ?>" class="mod-btn">
                <span>Bekleyen İncelemeler</span> <span class="dashicons dashicons-arrow-right-alt2"></span>
            </a>
            <a href="<?php echo admin_url( 'edit-comments.php?comment_status=moderated' ); ?>" class="mod-btn">
                <span>Yorumları Yönet</span> <span class="dashicons dashicons-arrow-right-alt2"></span>
            </a>
            <a href="<?php echo admin_url( 'post-new.php?post_type=news' ); ?>" class="mod-btn mod-btn-secondary">
                <span class="dashicons dashicons-plus"></span> Haber Ekle
            </a>
            <a href="<?php echo admin_url( 'post-new.php?post_type=reviews' ); ?>" class="mod-btn mod-btn-secondary">
                <span class="dashicons dashicons-plus"></span> İnceleme Ekle
            </a>
        </div>
    </div>
    <?php
}


