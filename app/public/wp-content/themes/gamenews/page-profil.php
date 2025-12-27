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
        
        if ( ! is_email( $email ) ) {
            $messages[] = array( 'type' => 'error', 'text' => 'Geçersiz e-posta adresi.' );
            $error = true;
        } elseif ( email_exists( $email ) && email_exists( $email ) != $user_id ) {
            $messages[] = array( 'type' => 'error', 'text' => 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.' );
            $error = true;
        }

        // 2. Update Password (if provided)
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        
        if ( ! empty( $pass1 ) ) {
            if ( $pass1 !== $pass2 ) {
                $messages[] = array( 'type' => 'error', 'text' => 'Şifreler eşleşmiyor.' );
                $error = true;
            } else {
                wp_set_password( $pass1, $user_id );
                // Note: This logs the user out. We need to re-login or warn them.
                // Re-login is tricky securely. Easier to redirect to login with message or just note it.
                // For better UX, let's keep them logged in if possible using `wp_signon` logic but that requires plaintext pass.
                // Standard WP way: Password change requires re-login usually.
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

get_header();

$user_registered = date_i18n( 'F Y', strtotime( $current_user->user_registered ) );
$comments_count  = get_comments( array( 'user_id' => $user_id, 'count' => true ) );
$avatar_url      = get_avatar_url( $user_id, array( 'size' => 200 ) );
?>

<main id="primary" class="site-main">
    
    <!-- Profile Hero -->
    <div class="profile-hero">
        <div class="profile-hero-bg"></div>
        <div class="profile-hero-content container">
            <div class="profile-header">
                <div class="profile-avatar">
                   <img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $current_user->display_name ); ?>">
                </div>
                <div class="profile-info">
                    <h1 class="profile-name"><?php echo esc_html( $current_user->display_name ); ?></h1>
                    <span class="profile-role">
                        <?php 
                        $user_roles = $current_user->roles;
                        echo !empty($user_roles) ? ucfirst($user_roles[0]) : 'Member'; 
                        ?>
                    </span>
                    <div class="profile-meta">
                        <span><i class="dashicons dashicons-calendar"></i> Üyelik: <?php echo $user_registered; ?></span>
                        <span><i class="dashicons dashicons-admin-comments"></i> Yorumlar: <?php echo $comments_count; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container container-medium">
        
        <?php foreach ( $messages as $msg ) : ?>
            <div class="profile-message message-<?php echo $msg['type']; ?>">
                <?php echo esc_html( $msg['text'] ); ?>
            </div>
        <?php endforeach; ?>

        <div class="profile-grid">
            
            <!-- LEFT: Settings Form -->
            <div class="profile-sidebar">
                <div class="profile-card form-card">
                    <h3><i class="dashicons dashicons-edit"></i> Bilgileri Düzenle</h3>
                    
                    <form method="post" action="" class="profile-edit-form">
                        <?php wp_nonce_field( 'oyunhaber_update_profile' ); ?>
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="display_name">Görünen İsim</label>
                            <input type="text" name="display_name" id="display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-posta Adresi</label>
                            <input type="email" name="email" id="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" required>
                        </div>

                        <hr class="form-divider">
                        
                        <div class="form-group">
                            <label for="pass1">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" name="pass1" id="pass1" autocomplete="off">
                        </div>
                        
                        <div class="form-group">
                            <label for="pass2">Yeni Şifre Tekrar</label>
                            <input type="password" name="pass2" id="pass2" autocomplete="off">
                        </div>

                        <button type="submit" class="btn-save-profile">Kaydet ve Güncelle</button>
                    </form>
                </div>
            </div>
            
            <!-- RIGHT: User Activity -->
            <div class="profile-content">
                <h2 class="section-title">Son Aktiviteler</h2>
                
                <?php
                $args = array(
                    'user_id' => $user_id,
                    'number'  => 5,
                    'status'  => 'approve',
                );
                $comments = get_comments( $args );

                if ( $comments ) :
                    echo '<div class="profile-comments-list">';
                    foreach ( $comments as $comment ) :
                        ?>
                        <div class="profile-comment-item">
                            <div class="comment-header">
                                <span class="comment-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $comment->comment_date ) ); ?></span>
                                <span class="comment-post-link">
                                    <a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>
                                    içeriğine:
                                </span>
                            </div>
                            <div class="comment-text">
                                "<?php echo wp_trim_words( $comment->comment_content, 20, '...' ); ?>"
                            </div>
                            <a href="<?php echo get_comment_link( $comment ); ?>" class="view-comment-link">Yoruma Git &rarr;</a>
                        </div>
                        <?php
                    endforeach;
                    echo '</div>';
                else :
                    echo '<p class="no-activity">Henüz bir yorum aktivitesi bulunmuyor.</p>';
                endif;
                ?>
            </div>

        </div>
    </div>

</main>

<style>
/* Additional Inline Styles for the Form */
.profile-message {
    padding: 15px;
    margin-bottom: 30px;
    border-radius: 8px;
    font-weight: 600;
}
.message-error { background: rgba(231, 76, 60, 0.2); border: 1px solid #e74c3c; color: #e74c3c; }
.message-success { background: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; color: #2ecc71; }

.form-card {
    background: #1e1e1e; 
    border: 1px solid #333;
}
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #ccc; font-size: 0.9rem;}
.form-group input {
    width: 100%;
    background: #2d2d2d;
    border: 1px solid #444;
    padding: 10px;
    border-radius: 6px;
    color: #fff;
    box-sizing: border-box; /* Fix overflow */
}
.form-group input:focus { border-color: var(--accent-color); outline: none; }
.form-divider { border: 0; border-top: 1px solid #333; margin: 25px 0; }
.btn-save-profile {
    width: 100%;
    background: var(--accent-color);
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.3s;
}
.btn-save-profile:hover { background: var(--accent-hover); box-shadow: 0 4px 15px rgba(var(--platform-rgb), 0.4); }
</style>

<?php
get_footer();
