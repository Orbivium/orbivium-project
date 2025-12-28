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

/**
 * 7. Custom Admin Styles for Moderator (Dark Theme & Branding)
 */
function oyunhaber_moderator_admin_styles() {
    if ( ! is_user_logged_in() ) {
        return;
    }
    
    $user = wp_get_current_user();
    if ( in_array( 'moderator', (array) $user->roles ) ) {
        ?>
        <style type="text/css">
            /* =========================================
               1. VARIABLES & SEXY CURVES
               ========================================= */
            :root {
                /* Colors */
                --bg-root: #090a0c;         /* Ultra dark void */
                --bg-sidebar: #0f1115;      /* Sidebar */
                --bg-card: #15181e;         /* Base Card */
                --bg-card-hover: #1c2028;
                --bg-input: #0b0c10;
                
                --brand-primary: #ff3f5e;   /* Hotter Pink/Red */
                --brand-secondary: #ff9f43; /* Accent Orange */
                --brand-gradient: linear-gradient(135deg, #ff3f5e 0%, #ff8e53 100%);
                --brand-glass: rgba(255, 63, 94, 0.15);
                
                --text-main: #ffffff;
                --text-secondary: #94a3b8;
                
                --border-color: rgba(255, 255, 255, 0.06);
                
                /* ROUNDNESS - EXTREME */
                --radius-xs: 12px;
                --radius-sm: 20px;
                --radius-md: 30px;
                --radius-pill: 999px;
                
                /* SHADOWS & GLOWS */
                --shadow-soft: 0 10px 40px -10px rgba(0,0,0,0.5);
                --glow-primary: 0 0 20px rgba(255, 63, 94, 0.4);
                --glow-text: 0 0 10px rgba(255, 63, 94, 0.5);
                
                /* SIDEBAR WIDTH */
                --sidebar-width: 240px; 
            }

            /* Global Polish */
            body, .wp-admin, .wrap, #wpcontent, #wpbody-content {
                background-color: var(--bg-root) !important;
                font-family: 'Inter', system-ui, sans-serif !important;
                color: var(--text-main) !important;
            }

            /* --- 2. SIDEBAR - WIDER & SPACIOUS --- */
            /* Force Sidebar Width */
            #adminmenuback, #adminmenuwrap {
                width: var(--sidebar-width) !important;
                background-color: var(--bg-sidebar) !important;
                border-right: 1px solid var(--border-color);
            }
            
            /* Push Content Right */
            #wpcontent, #wpfooter {
                margin-left: var(--sidebar-width) !important;
            }
            
            /* The Menu Container */
            #adminmenu { 
                margin: 20px 10px !important; 
                width: auto !important;
            }
            
            #adminmenu li.menu-top {
                border-radius: var(--radius-sm);
                margin-bottom: 8px;
                overflow: visible !important; /* Allow submenus to pop out */
                transition: background 0.2s;
                width: 100% !important;
                position: relative;
            }
            
            #adminmenu li.menu-top > a {
                color: var(--text-secondary) !important;
                font-weight: 600 !important;
                padding: 12px 15px !important;
                display: flex !important;
                align-items: center;
                font-size: 14px !important;
            }
            
            /* Fix Icons */
            #adminmenu div.wp-menu-image {
                float: none !important;
                margin-right: 12px !important;
                width: 20px !important;
                height: 20px !important;
            }
            #adminmenu div.wp-menu-image:before {
                padding: 0 !important;
                font-size: 20px !important;
                color: var(--text-secondary);
            }
            
            /* Active & Hover States */
            #adminmenu li.menu-top:hover,
            #adminmenu li.current.menu-top,
            #adminmenu li.wp-has-current-submenu {
                background: var(--bg-card-hover) !important;
            }
            
            #adminmenu li.current.menu-top > a,
            #adminmenu li.wp-has-current-submenu > a {
                 background: var(--brand-gradient) !important;
                 color: #fff !important;
                 box-shadow: var(--shadow-md);
            }
            
            #adminmenu li.current div.wp-menu-image:before,
            #adminmenu li.wp-has-current-submenu div.wp-menu-image:before {
                color: #fff !important;
            }

            /* --- SUBMENUS (INSET / ACCORDION STYLE) --- */
            /* Remove the broken fly-out positioning */
            #adminmenu .wp-submenu {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                bottom: auto !important;
                width: 100% !important;
                margin: 0 !important;
                background: rgba(0,0,0,0.2) !important; /* Darker inset background */
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 0 var(--radius-sm) var(--radius-sm) !important;
                padding: 5px 0 10px 0 !important;
                display: none !important; /* Hidden by default */
            }
            
            /* Show submenu only when active or open */
            #adminmenu li.wp-has-current-submenu .wp-submenu,
            #adminmenu li.opensub .wp-submenu {
                display: block !important;
            }
            
            /* Remove headers */
            #adminmenu .wp-submenu-head { display: none !important; }

            /* Submenu Links */
            #adminmenu .wp-submenu a {
                padding: 8px 15px 8px 50px !important; /* Indented text */
                color: var(--text-secondary) !important;
                font-size: 13px !important;
                font-weight: 500 !important;
                transition: all 0.2s;
            }
            
            /* Submenu Hover */
            #adminmenu .wp-submenu a:hover,
            #adminmenu .wp-submenu a:focus {
                color: #fff !important;
                transform: translateX(5px);
                background: transparent !important;
            }
            
            /* Active Submenu Item */
            #adminmenu .wp-submenu li.current a {
                color: var(--brand-primary) !important;
                font-weight: 700 !important;
            }
            
            /* Fix the rounded corners of the parent item when submenu is open */
            #adminmenu li.wp-has-current-submenu.menu-top,
            #adminmenu li.opensub.menu-top {
                border-radius: var(--radius-sm) var(--radius-sm) 0 0 !important;
                background: var(--bg-card-hover) !important;
            }
            
            /* Hide the little triangle pointers from WP */
            .wp-menu-arrow { display: none !important; }

            /* --- FILTERS & TOP BAR CLEANUP --- */
            .tablenav.top { margin-bottom: 30px !important; }
            
            .tablenav .actions, .tablenav .alignleft {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .tablenav select {
                background: var(--bg-card) !important;
                border: 1px solid var(--border-color) !important;
                color: var(--text-main) !important;
                border-radius: var(--radius-pill) !important;
                height: 36px !important;
                padding: 0 30px 0 15px !important;
                cursor: pointer;
            }
            
            .tablenav .button {
                background: transparent !important;
                border: 1px solid var(--border-color) !important;
                color: var(--text-secondary) !important;
                border-radius: var(--radius-pill) !important;
                height: 36px !important;
                padding: 0 20px !important;
                text-transform: uppercase;
                font-size: 11px !important;
                font-weight: 700;
                letter-spacing: 0.5px;
            }
            .tablenav .button:hover {
                border-color: var(--brand-primary) !important;
                color: var(--brand-primary) !important;
            }
            
            /* --- DATA TABLE HEADERS --- */
            .wp-list-table thead th, 
            .wp-list-table tfoot th {
                color: #fff !important; /* Brighter white */
                font-weight: 700 !important;
                font-size: 12px !important;
                text-transform: uppercase;
                letter-spacing: 1px;
                border-bottom: 2px solid var(--border-color) !important;
                padding: 15px !important;
                opacity: 0.8;
            }
            .wp-list-table thead th a { color: #fff !important; }
            
            /* Checkbox centering */
            .check-column { padding-left: 20px !important; }

            /* --- SEARCH BOX FIX --- */
            p.search-box {
                position: absolute;
                top: -60px; /* Move into title area */
                right: 0;
                margin: 0 !important;
            }
            p.search-box input[type="search"] {
                width: 200px;
                background: var(--bg-input) !important;
                border: 1px solid var(--border-color) !important;
            }
            p.search-box input[type="search"]:focus {
                width: 280px;
            }

            /* Hide Collapse Button */
            #collapse-menu { display: none !important; }
            .mod-panel-wrapper {
                background: rgba(30, 34, 42, 0.4) !important;
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: var(--radius-md) !important;
                padding: 40px !important;
                position: relative;
                overflow: hidden;
                box-shadow: var(--shadow-soft);
            }
            
            /* Background Ambient Glow */
            .mod-glow-bg {
                position: absolute;
                top: -30%; left: -10%;
                width: 60%; height: 60%;
                background: radial-gradient(circle, var(--brand-secondary), transparent 70%);
                filter: blur(80px);
                opacity: 0.15;
                z-index: 0;
                animation: pulseGlow 10s infinite alternate;
            }
            @keyframes pulseGlow { 0% { transform: scale(1); } 100% { transform: scale(1.2); } }

            .mod-section-title {
                font-size: 0.8rem;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 20px;
                position: relative;
                z-index: 1;
                font-weight: 800;
            }

            /* Stats Grid */
            .mod-stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 40px;
                position: relative; 
                z-index: 1;
            }
            
            .mod-stat-card {
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.05);
                border-radius: var(--radius-md);
                padding: 25px;
                display: flex;
                align-items: center;
                gap: 20px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            
            .mod-stat-card:hover {
                transform: translateY(-10px);
                background: rgba(255,255,255,0.06);
                border-color: rgba(255, 63, 94, 0.3);
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }
            
            .mod-stat-icon span {
                font-size: 40px;
                width: 40px; height: 40px;
                background: -webkit-linear-gradient(#eee, #999);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .mod-stat-card:hover .mod-stat-icon span {
                background: var(--brand-gradient);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .mod-stat-number {
                font-size: 2.5rem;
                font-weight: 800;
                line-height: 1;
                color: #fff;
                display: block;
            }
            .mod-stat-label {
                font-size: 0.9rem;
                color: var(--text-muted);
            }
            
            .stat-alert { color: var(--brand-secondary) !important; text-shadow: 0 0 10px rgba(255, 159, 67, 0.5); }
            .stat-urgent { color: var(--brand-primary) !important; text-shadow: 0 0 15px var(--glow-primary); }

            /* Action Buttons - PILLS */
            .mod-actions-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                position: relative; 
                z-index: 1;
            }
            
            .mod-btn {
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                color: #fff;
                padding: 15px 30px;
                border-radius: var(--radius-pill); /* Pill Shape */
                font-weight: 600;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
                transition: all 0.3s;
            }
            
            .mod-btn:hover {
                background: var(--brand-gradient);
                border-color: transparent;
                transform: scale(1.05);
                box-shadow: var(--glow-primary);
                color: #fff;
            }
            
            .mod-btn-secondary {
                background: transparent;
                border: 1px dashed var(--text-muted);
                color: var(--text-muted);
            }
            .mod-btn-secondary:hover {
                border-style: solid;
                background: rgba(255,255,255,0.1);
                box-shadow: none;
                transform: scale(1.05);
            }

            /* --- 4. DATA LISTS (CARDS) --- */
            .wp-list-table { border-spacing: 0 15px !important; border-collapse: separate !important; }
            .wp-list-table thead th { border: none !important; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
            
            .wp-list-table tbody tr {
                background: var(--bg-card) !important;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                transition: all 0.3s;
            }
            .wp-list-table tbody tr:hover {
                transform: scale(1.005);
                background: var(--bg-card-hover) !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                z-index: 10;
                position: relative;
            }
            
            /* Round Corners for Rows */
            .wp-list-table tbody tr td:first-child { border-radius: var(--radius-sm) 0 0 var(--radius-sm); border-left: 5px solid transparent !important; }
            .wp-list-table tbody tr td:last-child { border-radius: 0 var(--radius-sm) var(--radius-sm) 0; }
            
            .wp-list-table tbody tr:hover td:first-child {
                border-left-color: var(--brand-primary) !important;
            }
            
            /* Hide Checkboxes for cleaner look if desired, or style them round */
            input[type=checkbox] {
                border-radius: 50% !important; /* Round checkboxes */
                background: var(--bg-input) !important;
                border-color: var(--text-muted) !important;
                transition: 0.2s;
            }
            input[type=checkbox]:checked {
                background: var(--brand-primary) !important;
                border-color: transparent !important;
                box-shadow: var(--glow-primary);
            }
            
            /* Search & Inputs - Round Pills */
            input[type=search], input[type=text], select {
                border-radius: var(--radius-pill) !important;
                background: var(--bg-card) !important;
                border: 1px solid var(--border-color) !important;
                color: #fff !important;
                padding: 10px 20px !important;
            }
            
            input:focus, select:focus {
                border-color: var(--brand-primary) !important;
                box-shadow: 0 0 0 3px var(--brand-glass) !important;
            }
            
            /* General Elements */
            .postbox, .card, .welcome-panel {
                border-radius: var(--radius-md) !important;
                border: 1px solid var(--border-color) !important;
                background: var(--bg-card) !important;
            }
            
            /* Notices - Bubbles */
            .notice, div.updated {
                border-radius: var(--radius-sm) !important;
                border: none !important;
                background: rgba(16, 185, 129, 0.15) !important;
                color: #6ee7b7 !important;
                margin: 20px 0 !important;
            }
            
            /* Top Bar Glass */
            #wpadminbar {
                margin: 10px;
                width: calc(100% - 20px);
                border-radius: var(--radius-pill);
                top: 5px;
            }
            
            /* Content Padding Adjust for floating top bar */
            #wpbody { padding-top: 20px; }
        </style>
        <?php
    }
}
add_action( 'admin_head', 'oyunhaber_moderator_admin_styles' );
