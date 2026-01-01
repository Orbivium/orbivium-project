<?php
/**
 * Template Name: User Profile
 * The template for displaying and editing the user profile page.
 * Post Type: page
 * Slug: profil
 *
 * @package OyunHaber
 */

// Redirect if not logged in
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$current_phone = get_user_meta( $user_id, 'phone_number', true );
$messages     = array();

// --- Handle Form Submission ---
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'update_profile' ) {
    
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'oyunhaber_update_profile' ) ) {
        $messages[] = array( 'type' => 'error', 'text' => 'Güvenlik doğrulaması başarısız.' );
    } else {
        
        $error = false;
        
        // 1. Update Basic Info
        $display_name = sanitize_text_field( $_POST['display_name'] );
        $email        = sanitize_email( $_POST['email'] );
        $phone        = sanitize_text_field( $_POST['phone_number'] );

        if ( ! is_email( $email ) ) {
            $messages[] = array( 'type' => 'error', 'text' => 'Geçersiz e-posta adresi.' );
            $error = true;
        } elseif ( email_exists( $email ) && email_exists( $email ) != $user_id ) {
            $messages[] = array( 'type' => 'error', 'text' => 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.' );
            $error = true;
        }

        // Check Duplicate Phone
        if ( ! empty( $phone ) ) {
            $phone_users = get_users(array(
                'meta_key'   => 'phone_number',
                'meta_value' => $phone,
                'exclude'    => array( $user_id ), // Exclude current user
                'number'     => 1,
                'fields'     => 'ID'
            ));
            
            if ( ! empty( $phone_users ) ) {
                $messages[] = array( 'type' => 'error', 'text' => 'Bu telefon numarası başka bir kullanıcı tarafından kullanılıyor.' );
                $error = true;
            }
        }

        // Update Phone Meta
        update_user_meta( $user_id, 'phone_number', $phone );

        // 2. Update Password (if provided)
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        
        if ( ! empty( $pass1 ) ) {
            if ( $pass1 !== $pass2 ) {
                $messages[] = array( 'type' => 'error', 'text' => 'Şifreler eşleşmiyor.' );
                $error = true;
            } else {
                wp_set_password( $pass1, $user_id );
                $messages[] = array( 'type' => 'success', 'text' => 'Şifreniz değiştirildi. Lütfen tekrar giriş yapın.' );
            }
        }

        if ( ! $error ) {
            $user_data = array(
                'ID'           => $user_id,
                'display_name' => $display_name,
                'user_email'   => $email,
            );
            
            $update = wp_update_user( $user_data );
            
            if ( is_wp_error( $update ) ) {
                $messages[] = array( 'type' => 'error', 'text' => $update->get_error_message() );
            } else {
                if ( empty( $pass1 ) ) {
                     $messages[] = array( 'type' => 'success', 'text' => 'Profil bilgileriniz güncellendi.' );
                }
                // Refresh object
                $current_user = wp_get_current_user();
            }
        }
    }
}

// --- Handle Avatar Upload ---
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'update_avatar' ) {
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'oyunhaber_update_avatar' ) ) {
         $messages[] = array( 'type' => 'error', 'text' => 'Güvenlik hatası.' );
    } else {
        if ( ! empty( $_FILES['profile_avatar']['name'] ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'profile_avatar', 0 );

            if ( is_wp_error( $attachment_id ) ) {
                $messages[] = array( 'type' => 'error', 'text' => 'Yükleme hatası: ' . $attachment_id->get_error_message() );
            } else {
                update_user_meta( $user_id, 'simple_local_avatar', $attachment_id );
                $messages[] = array( 'type' => 'success', 'text' => 'Profil resmi güncellendi.' );
                // Force refresh current user to show new avatar immediately
                /* We don't need to refresh current_user object, just the avatar variable below. */
            }
        }
    }
}

get_header();

$user_registered = date_i18n( 'F Y', strtotime( $current_user->user_registered ) );
$comments_count  = get_comments( array( 'user_id' => $user_id, 'count' => true ) );
// Fetch avatar again here to get the updated one if it just changed
$avatar_url      = get_avatar_url( $user_id, array( 'size' => 224 ) ); // 112px * 2 for retina
?>

<main id="primary" class="site-main profile-page-wrapper">
    
    <!-- 1. PROFILE HEADER (HERO) -->
    <header class="profile-header-hero">
        <div class="profile-banner">
            <div class="profile-banner-overlay"></div>
        </div>
        
        <div class="container header-content-layer">
            <div class="profile-identity-row">
                <!-- Avatar -->
                <!-- Avatar -->
                <div class="profile-avatar-container">
                    <form id="avatar_form" method="post" enctype="multipart/form-data" action="">
                        <?php wp_nonce_field( 'oyunhaber_update_avatar' ); ?>
                        <input type="hidden" name="action" value="update_avatar">
                        
                        <label for="profile_avatar" class="avatar-label" title="Profil Resmini Değiştir">
                            <img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $current_user->display_name ); ?>">
                            
                            <div class="avatar-overlay">
                                <span class="dashicons dashicons-camera"></span>
                                <span class="overlay-text">DEĞİŞTİR</span>
                            </div>
                            
                            <input type="file" name="profile_avatar" id="profile_avatar" class="avatar-input" accept="image/*" onchange="document.getElementById('avatar_form').submit();">
                        </label>
                    </form>
                    <div class="avatar-glow"></div>
                </div>

                <!-- Info Group -->
                <div class="profile-identity-info">
                    <h1 class="profile-real-name"><?php echo esc_html( $current_user->display_name ); ?></h1>
                    
                    <div class="profile-badges-row">
                        <!-- Role Badge -->
                        <span class="role-pill">
                            <span class="dashicons dashicons-awards"></span>
                            <?php 
                            $user_roles = $current_user->roles;
                            if ( ! empty( $user_roles ) ) {
                                $role = $user_roles[0];
                                if ( $role === 'subscriber' ) { echo 'ABONE'; }
                                elseif ( $role === 'moderator' ) { echo 'MODERATÖR'; }
                                elseif ( $role === 'administrator' ) { echo 'YÖNETİCİ'; }
                                else { echo strtoupper( ucfirst( $role ) ); }
                            } else {
                                echo 'ÜYE';
                            }
                            ?>
                        </span>
                    </div>

                    <!-- Stat Chips -->
                    <div class="profile-stats-row">
                        <div class="stat-chip">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <span>Üyelik: <?php echo $user_registered; ?></span>
                        </div>
                        <div class="stat-chip">
                            <span class="dashicons dashicons-admin-comments"></span>
                            <span>Yorumlar: <?php echo $comments_count; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. MAIN CONTENT GRID -->
    <div class="container profile-main-grid">
        
        <!-- Messages Area -->
        <?php if ( ! empty( $messages ) ) : ?>
            <div class="profile-messages-area">
                <?php foreach ( $messages as $msg ) : ?>
                    <div class="msg-alert msg-<?php echo esc_attr( $msg['type'] ); ?>">
                        <?php echo esc_html( $msg['text'] ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- LEFT COLUMN: SETTINGS -->
        <div class="grid-col-left">
            <div class="glass-card settings-card">
                <div class="card-header">
                    <span class="header-icon dashicons dashicons-edit"></span>
                    <div class="header-text">
                        <h3>Profil Ayarları</h3>
                        <p class="text-muted">Hesap bilgilerinizi güncelleyin.</p>
                    </div>
                </div>

                <form method="post" action="" class="profile-form">
                    <?php wp_nonce_field( 'oyunhaber_update_profile' ); ?>
                    <input type="hidden" name="action" value="update_profile">
                    
                    <!-- Display Name -->
                    <div class="form-group">
                        <label for="display_name" class="form-label">Görünen İsim</label>
                        <input type="text" name="display_name" id="display_name" class="form-input" value="<?php echo esc_attr( $current_user->display_name ); ?>" required>
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" name="email" id="email" class="form-input" value="<?php echo esc_attr( $current_user->user_email ); ?>" required>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone_number" class="form-label">Telefon Numarası</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-input" value="<?php echo esc_attr( $current_phone ); ?>" placeholder="05XX XXX XX XX">
                    </div>

                    <div class="form-divider"></div>

                    <!-- Password 1 -->
                    <div class="form-group has-icon">
                        <label for="pass1" class="form-label">Yeni Şifre</label>
                        <div class="input-wrapper">
                            <input type="password" name="pass1" id="pass1" class="form-input password-field" autocomplete="new-password">
                            <button type="button" class="toggle-password" tabindex="-1"><span class="dashicons dashicons-visibility"></span></button>
                        </div>
                        <span class="field-hint">Değiştirmek istemiyorsanız boş bırakın.</span>
                    </div>

                    <!-- Password 2 -->
                    <div class="form-group has-icon">
                        <label for="pass2" class="form-label">Yeni Şifre (Tekrar)</label>
                        <div class="input-wrapper">
                            <input type="password" name="pass2" id="pass2" class="form-input password-field" autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-save btn-primary-glow">
                        <span>Kaydet ve Güncelle</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: CONTENT & ACTIVITIES -->
        <div class="grid-col-right">
            
            <?php 
            // --- MODERATOR/ADMIN ONLY: MY CONTENT SECTION ---
            if ( current_user_can('edit_posts') ) : 
                $author_posts = new WP_Query( array(
                    'author'         => $user_id,
                    'post_type'      => array( 'news', 'reviews' ),
                    'post_status'    => array( 'publish', 'pending', 'draft' ),
                    'posts_per_page' => 5,
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                ));

                if ( $author_posts->have_posts() ) :
            ?>
            <div class="glass-card content-card" style="margin-bottom: 30px;">
                <div class="card-title-row">
                    <div class="accent-line"></div>
                    <h3>İçeriklerim</h3>
                </div>

                <div class="content-list-compact">
                    <?php while ( $author_posts->have_posts() ) : $author_posts->the_post(); 
                        $status = get_post_status();
                        $status_label = 'Yayında';
                        $status_class = 'status-published';
                        $status_icon  = 'dashicons-yes';
                        
                        if ( $status == 'pending' ) { 
                            $status_label = 'İncelemede'; 
                            $status_class = 'status-pending'; 
                            $status_icon  = 'dashicons-clock';
                        }
                        if ( $status == 'draft' ) { 
                            $status_label = 'Taslak'; 
                            $status_class = 'status-draft'; 
                            $status_icon  = 'dashicons-edit';
                        }
                    ?>
                    <div class="content-item-row">
                        <div class="content-thumb-wrapper">
                            <?php if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'thumbnail' );
                            } else {
                                echo '<div class="empty-thumb-placeholder"><span class="dashicons dashicons-format-image"></span></div>';
                            } ?>
                        </div>
                        
                        <div class="content-info-col">
                            <h4 class="content-item-title">
                                <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="content-meta-row">
                                <div class="meta-pill <?php echo esc_attr($status_class); ?>">
                                    <span class="dashicons <?php echo esc_attr($status_icon); ?>"></span>
                                    <?php echo esc_html($status_label); ?>
                                </div>
                                <span class="meta-dot">&bull;</span>
                                <span class="meta-date"><?php echo get_the_date(); ?></span>
                                <span class="meta-dot">&bull;</span>
                                <span class="meta-type"><?php echo ucfirst( get_post_type() ); ?></span>
                            </div>
                        </div>

                        <div class="content-action-col">
                            <a href="<?php echo get_edit_post_link(); ?>" class="btn-action-edit" title="Düzenle">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            
            <style>
                .content-list-compact { display: flex; flex-direction: column; gap: 16px; }
                
                .content-item-row { 
                    display: flex; 
                    gap: 20px; 
                    align-items: center; 
                    background: linear-gradient(to right, rgba(255,255,255,0.03), rgba(255,255,255,0.01)); 
                    padding: 12px; 
                    border-radius: 16px; 
                    border: 1px solid rgba(255,255,255,0.05);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }
                
                .content-item-row::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; bottom: 0; width: 3px;
                    background: var(--p-accent-red);
                    opacity: 0;
                    transition: opacity 0.3s;
                }

                .content-item-row:hover { 
                    background: rgba(255,255,255,0.06); 
                    border-color: rgba(255,255,255,0.1); 
                    transform: translateX(5px);
                }
                
                .content-item-row:hover::before { opacity: 1; }

                /* Thumbnail */
                .content-thumb-wrapper { 
                    width: 70px; 
                    height: 50px; 
                    flex-shrink: 0; 
                    border-radius: 8px; 
                    overflow: hidden; 
                    background: #111; 
                    position: relative;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                }
                
                .content-thumb-wrapper img { width: 100%; height: 100%; object-fit: cover; }
                
                .empty-thumb-placeholder { 
                    width: 100%; height: 100%; 
                    display: flex; align-items: center; justify-content: center; 
                    background: #1a1a1a; color: #333; 
                }
                .empty-thumb-placeholder .dashicons { font-size: 24px; width: 24px; height: 24px; }

                /* Info */
                .content-info-col { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 6px; }
                
                .content-item-title { 
                    margin: 0; 
                    font-size: 1.05rem; 
                    font-weight: 600; 
                    line-height: 1.3;
                    white-space: nowrap; 
                    overflow: hidden; 
                    text-overflow: ellipsis; 
                }
                
                .content-item-title a { color: #fff; text-decoration: none; transition: color 0.2s; }
                .content-item-title a:hover { color: var(--p-accent-red); }

                /* Meta Row */
                .content-meta-row { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: var(--p-text-muted); }
                .meta-dot { font-size: 10px; opacity: 0.5; }

                /* Status Pills */
                .meta-pill { 
                    display: inline-flex; align-items: center; gap: 4px; 
                    padding: 2px 8px; border-radius: 6px; 
                    font-weight: 700; font-size: 0.7rem; 
                    text-transform: uppercase; letter-spacing: 0.5px;
                }
                .meta-pill .dashicons { font-size: 12px; width: 12px; height: 12px; }

                .status-published { background: rgba(46, 204, 113, 0.15); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.2); }
                .status-pending { background: rgba(243, 156, 18, 0.15); color: #f39c12; border: 1px solid rgba(243, 156, 18, 0.2); }
                .status-draft { background: rgba(149, 165, 166, 0.15); color: #bdc3c7; border: 1px solid rgba(149, 165, 166, 0.2); }

                /* Edit Button */
                .btn-action-edit { 
                    width: 36px; height: 36px; 
                    display: flex; align-items: center; justify-content: center; 
                    border-radius: 50%; 
                    background: rgba(255,255,255,0.05); 
                    color: var(--p-text-muted); 
                    transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                    border: 1px solid transparent;
                }
                
                .content-item-row:hover .btn-action-edit {
                    background: var(--p-accent-red);
                    color: #fff;
                    box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4);
                    transform: scale(1.1);
                }
            </style>
            <?php 
                endif; // have_posts
            endif; // current_user_can 
            ?>

            <div class="glass-card activity-card">
                <div class="card-title-row">
                    <div class="accent-line"></div>
                    <h3>Son Aktiviteler</h3>
                </div>

                <div class="activity-feed">
                    <?php
                    $args = array(
                        'user_id' => $user_id,
                        'number'  => 5,
                        'status'  => 'approve',
                        'post_type' => 'any', // In case we extend later
                    );
                    $comments = get_comments( $args );

                    if ( $comments ) :
                        foreach ( $comments as $comment ) :
                        ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <span class="dashicons dashicons-format-chat"></span>
                            </div>
                            <div class="activity-content">
                                <div class="activity-meta">
                                    <span class="activity-date"><?php echo human_time_diff( strtotime( $comment->comment_date ), current_time('timestamp') ) . ' önce'; ?></span>
                                    <span class="activity-context">
                                        <a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>" class="post-link">
                                            <?php echo get_the_title( $comment->comment_post_ID ); ?>
                                        </a> içeriğine yorum yaptı
                                    </span>
                                </div>
                                <div class="activity-bubble">
                                    "<?php echo wp_trim_words( $comment->comment_content, 25, '...' ); ?>"
                                </div>
                                <a href="<?php echo get_comment_link( $comment ); ?>" class="activity-link-action">Görüntüle &rarr;</a>
                            </div>
                        </div>
                        <?php
                        endforeach;
                    else :
                        // EMPTY STATE
                        ?>
                        <div class="empty-state-panel">
                            <div class="empty-icon-circle">
                                <span class="dashicons dashicons-marker"></span> 
                                <!-- Note: 'marker' or 'awards' or 'edit' looks decent. Using marker as placeholder for 'start here' -->
                            </div>
                            <h4>Henüz aktivite yok</h4>
                            <p>Oyun dünyasına katıl! İlk incelemeni yaz veya bir habere yorum bırak.</p>
                            <a href="<?php echo home_url('/'); ?>" class="btn-outline-action">Ana Sayfaya Git</a>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>

            </div>
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone Input Formatting
    const phoneInput = document.getElementById('phone_number');
    if(phoneInput) {
        phoneInput.placeholder = "05XXXXXXXXX";
        phoneInput.addEventListener('input', function(e) {
            let val = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (val.length > 11) val = val.slice(0, 11); // Max 11 digits
            e.target.value = val;
        });
    }

    // Password Toggle Logic
    const toggles = document.querySelectorAll('.toggle-password');
    toggles.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('.dashicons');
            if(input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('dashicons-visibility');
                icon.classList.add('dashicons-hidden');
            } else {
                input.type = 'password';
                icon.classList.remove('dashicons-hidden');
                icon.classList.add('dashicons-visibility');
            }
        });
    });

    // Auto-hide success messages after 3 seconds
    const successMsgs = document.querySelectorAll('.msg-success');
    if(successMsgs.length > 0) {
        setTimeout(() => {
            successMsgs.forEach(msg => {
                msg.style.transition = "opacity 0.5s ease";
                msg.style.opacity = "0";
                
                setTimeout(() => {
                    const parent = msg.parentElement;
                    msg.remove();
                    
                    // If parent container is empty, remove it too to fix layout gaps
                    if (parent && parent.children.length === 0) {
                         parent.remove();
                    }
                }, 500);
            });
        }, 3000);
    }
});
</script>

<style>
/* --- VISUAL TOKENS --- */
:root {
    --p-bg-color: #0B0F14;
    --p-card-bg: rgba(255, 255, 255, 0.03);
    --p-card-border: rgba(255, 255, 255, 0.08);
    --p-text-primary: #E8EEF6;
    --p-text-muted: rgba(232, 238, 246, 0.65);
    --p-accent-red: #E11D48;
    --p-accent-glow: rgba(225, 29, 72, 0.5);
    --p-radius: 16px;
    --p-font: 'Segoe UI', system-ui, sans-serif;
}

.profile-page-wrapper {
    background-color: var(--p-bg-color);
    font-family: var(--p-font);
    color: var(--p-text-primary);
    min-height: 100vh;
}

/* --- 1. HERO HEADER --- */
.profile-header-hero {
    position: relative;
    margin-bottom: 10px;
}

.profile-banner {
    height: 80px;
    width: 100%;
    background: radial-gradient(circle at 70% 30%, #1e1e24 0%, #0B0F14 90%);
    position: relative;
    overflow: hidden;
}

/* Subtle noise or pattern */
.profile-banner::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0.1;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415-.828-.828-.828.828-1.415-1.415.828-.828-.828-.828 1.415-1.415.828.828.828-.828 1.415 1.415-.828.828zM22.485 0l.83.828-1.415 1.415-.828-.828-.828.828-1.415-1.415.828-.828-.828-.828 1.415-1.415.828.828.828-.828 1.415 1.415-.828.828zM0 22.485l.828.83-1.415 1.415-.828-.828-.828.828L-2.83 22.485l.828-.828-.828-.828 1.415-1.415.828.828.828-.828 1.415 1.415-.828.828zM0 54.627l.828.83-1.415 1.415-.828-.828-.828.828L-2.83 54.627l.828-.828-.828-.828 1.415-1.415.828.828.828-.828 1.415 1.415-.828.828zM54.627 60l.83-.828-1.415-1.415-.828.828-.828-.828-1.415 1.415.828.828-.828.828 1.415 1.415-.828-.828.828.828 1.415-1.415.828-.828zM22.485 60l.83-.828-1.415-1.415-.828.828-.828-.828-1.415 1.415.828.828-.828.828 1.415 1.415-.828-.828.828.828 1.415-1.415.828-.828z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.profile-banner-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, var(--p-bg-color), transparent);
}

.header-content-layer {
    position: relative;
    /* Negative margin to pull content up over banner */
    margin-top: -60px; 
    z-index: 5;
    padding-bottom: 20px;
}

.profile-identity-row {
    display: flex;
    align-items: flex-end;
    gap: 30px;
    flex-wrap: wrap;
}

.profile-avatar-container {
    width: 140px;
    height: 140px;
    flex-shrink: 0;
    position: relative;
    border-radius: 50%;
    /* The ring */
    padding: 3px; 
    background: linear-gradient(135deg, var(--p-accent-red), #333);
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}

.profile-avatar-container img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--p-bg-color); /* Creates the gap */
    display: block;
    background: #000;
}

/* Avatar Overlay */
.avatar-label {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    cursor: pointer;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-input {
    display: none;
}

.avatar-overlay {
    position: absolute;
    inset: 4px; /* Match border gap */
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    color: #fff;
    pointer-events: none; /* Let clicks pass to label */
}

.profile-avatar-container:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
    margin-bottom: 4px;
}

.overlay-text {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.profile-identity-info {
    padding-bottom: 10px;
    flex: 1;
}

.profile-real-name {
    font-size: 2.5rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 10px 0;
    line-height: 1;
    letter-spacing: -0.5px;
    text-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.profile-badges-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
}

.role-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    border: 1px solid var(--p-accent-glow);
    background: rgba(225, 29, 72, 0.1);
    color: #ff8ea1;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    box-shadow: 0 0 15px var(--p-accent-glow);
}

.profile-stats-row {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    font-size: 0.85rem;
    color: var(--p-text-muted);
}

/* --- 2. LAYOUT GRID --- */
.profile-main-grid {
    display: grid;
    grid-template-columns: 12fr; /* Mobile Default */
    gap: 30px;
}

@media (min-width: 900px) {
    .profile-main-grid {
        grid-template-columns: 4fr 8fr; /* 4 cols left, 8 cols right */
        align-items: start;
    }
}

/* Glass Card Shared Style */
.glass-card {
    background: var(--p-card-bg);
    border: 1px solid var(--p-card-border);
    border-radius: var(--p-radius);
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.glass-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    border-color: rgba(255, 255, 255, 0.15);
}

/* --- 3. LEFT COLUMN (FORM) --- */
.card-header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 25px;
}

.header-icon {
    font-size: 24px;
    width: 24px;
    height: 24px;
    color: var(--p-accent-red);
    margin-top: 4px;
}

.header-text h3 {
    margin: 0 0 5px 0;
    font-size: 1.25rem;
    color: #fff;
}

.text-muted {
    font-size: 0.85rem;
    color: var(--p-text-muted);
    margin: 0;
}

/* Clean Form Styles */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--p-text-muted);
}

.input-wrapper {
    position: relative;
}

.form-input {
    width: 100%;
    box-sizing: border-box; /* Fixes overflow */
    height: 50px;
    padding: 0 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: #fff;
    font-size: 1rem;
    outline: none;
    transition: all 0.2s ease;
}

.form-input:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--p-accent-red);
    box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
}

.form-input::placeholder {
    color: rgba(255, 255, 255, 0.2);
}

.form-divider {
    height: 1px;
    background: rgba(255,255,255,0.1);
    margin: 25px 0;
}

.has-icon {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 12px;
    background: none;
    border: none;
    color: var(--p-text-muted);
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0;
}

.toggle-password:hover { color: #fff; }

.field-hint {
    display: block;
    font-size: 0.75rem;
    color: var(--p-text-muted);
    margin-top: 5px;
}

.btn-save {
    width: 100%;
    padding: 14px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, var(--p-accent-red), #ff4d6d);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--p-accent-glow);
}

/* Alerts */
.profile-messages-area { margin-bottom: 20px; }
.msg-alert {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
.msg-error { background: rgba(231, 76, 60, 0.2); border: 1px solid #e74c3c; color: #ff8a8a; }
.msg-success { background: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; color: #7dffae; }


/* --- 4. RIGHT COLUMN (ACTIVITIES) --- */
.card-title-row {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.accent-line {
    width: 4px;
    height: 24px;
    background: var(--p-accent-red);
    border-radius: 4px;
}

.card-title-row h3 {
    margin: 0;
    font-size: 1.4rem;
    color: #fff;
}

/* Activity Items */
.activity-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    position: relative;
}

/* Connecting line */
.activity-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 20px; /* center of icon (40px) */
    top: 40px;
    bottom: -20px;
    width: 2px;
    background: rgba(255,255,255,0.05);
}

.activity-icon {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    background: #1a1a20;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--p-accent-red);
    z-index: 2;
}

.activity-content {
    flex: 1;
}

.activity-meta {
    font-size: 0.85rem;
    margin-bottom: 8px;
    color: var(--p-text-muted);
}

.activity-date {
    display: inline-block;
    padding-right: 10px;
    border-right: 1px solid rgba(255,255,255,0.1);
    margin-right: 10px;
    font-weight: 600;
}

.post-link {
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}
.post-link:hover { color: var(--p-accent-red); }

.activity-bubble {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    padding: 12px 16px;
    border-radius: 12px;
    border-top-left-radius: 2px;
    color: #e0e0e0;
    font-style: italic;
    line-height: 1.5;
    margin-bottom: 8px;
}

.activity-link-action {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--p-accent-red);
    text-transform: uppercase;
}

/* Empty State */
.empty-state-panel {
    text-align: center;
    padding: 60px 40px;
    background: rgba(0,0,0,0.2);
    border-radius: 16px;
    border: 1px dashed rgba(255,255,255,0.1);
}

.empty-icon-circle {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
    margin: 0 auto 20px auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon-circle span {
    font-size: 32px;
    color: var(--p-text-muted);
    width: 32px;
    height: 32px;
}

.empty-state-panel h4 {
    font-size: 1.2rem;
    color: #fff;
    margin: 0 0 10px 0;
}

.empty-state-panel p {
    color: var(--p-text-muted);
    margin-bottom: 25px;
    font-size: 0.95rem;
}

.btn-outline-action {
    display: inline-block;
    padding: 10px 24px;
    border: 1px solid var(--p-card-border);
    border-radius: 50px;
    color: #fff;
    transition: all 0.3s;
}

.btn-outline-action:hover {
    border-color: var(--p-accent-red);
    color: var(--p-accent-red);
    background: rgba(225, 29, 72, 0.05);
}

/* Mobile Adjustments */
@media (max-width: 768px) {
    .profile-banner { height: 180px; }
    .profile-avatar-container { width: 100px; height: 100px; }
    .header-content-layer { margin-top: -50px; }
    .profile-real-name { font-size: 1.8rem; }
    .profile-identity-info { text-align: center; }
    .profile-identity-row { flex-direction: column; align-items: center; gap: 15px; }
    .profile-badges-row, .profile-stats-row { justify-content: center; }
    .profile-main-grid { gap: 20px; }
}
</style>

<?php
get_footer();
