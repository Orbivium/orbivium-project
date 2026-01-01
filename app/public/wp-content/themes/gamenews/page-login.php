<?php
/**
 * Template Name: Giriş Yap
 *
 * @package OyunHaber
 */

// If logged in, redirect to profile
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profil' ) ); 
    exit;
}

$error_message = '';

// Handle Login Submission
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['oyunhaber_login_nonce'] ) && wp_verify_nonce( $_POST['oyunhaber_login_nonce'], 'oyunhaber_login_action' ) ) {
    
    $creds = array(
        'user_login'    => sanitize_text_field( $_POST['log'] ),
        'user_password' => $_POST['pwd'],
        'remember'      => isset( $_POST['rememberme'] ),
    );

    $user = wp_signon( $creds, is_ssl() );

    if ( is_wp_error( $user ) ) {
        // Translate common errors for better UX
        if ( $user->get_error_code() == 'incorrect_password' || $user->get_error_code() == 'invalid_username' || $user->get_error_code() == 'invalid_email' ) {
            $error_message = 'Kullanıcı adı veya şifre hatalı.';
        } else {
            $error_message = $user->get_error_message();
        }
    } else {
        // Redirection logic
        if ( in_array( 'moderator', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) {
             // Mods/Admins might prefer dashboard or profile. Let's send to profile first as requested context implies frontend usage.
             wp_redirect( home_url( '/profil' ) );
        } else {
             wp_redirect( home_url( '/profil' ) );
        }
        exit;
    }
}

get_header(); 
?>

<div class="login-page-wrapper">
    <div class="login-container">
        
        <!-- Left Side: Login Form -->
        <div class="login-left">
            <div class="glass-form-card">
                <h3>Tekrar Hoş Geldin</h3>
                <p class="sub-text">Hesabına giriş yap ve kaldığın yerden devam et.</p>

                <?php if ( $error_message ) : ?>
                    <div class="msg-box error"><?php echo esc_html( $error_message ); ?></div>
                <?php endif; ?>

                <form method="post" class="login-form">
                    <?php wp_nonce_field( 'oyunhaber_login_action', 'oyunhaber_login_nonce' ); ?>
                    
                    <div class="form-group">
                        <label for="user_login">Kullanıcı Adı veya E-Posta</label>
                        <input type="text" name="log" id="user_login" required placeholder="Kullanıcı adı veya e-posta" value="<?php echo isset($_POST['log']) ? esc_attr($_POST['log']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="user_pass">Şifre</label>
                        <div class="pass-wrapper">
                            <input type="password" name="pwd" id="user_pass" required placeholder="Şifreniz">
                            <span class="dashicons dashicons-visibility toggle-pass"></span>
                        </div>
                    </div>

                    <div class="form-row-actions">
                        <label class="remember-me">
                            <input type="checkbox" name="rememberme" value="forever"> Beni Hatırla
                        </label>
                        <a href="<?php echo home_url('/sifremi-unuttum/'); ?>" class="lost-pass-link">Şifremi Unuttum?</a>
                    </div>

                    <button type="submit" class="btn-login-submit">Giriş Yap</button>

                    <p class="register-link">
                        Hesabın yok mu? <a href="<?php echo home_url('/kayit-ol/'); ?>">Hemen Kayıt Ol</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right Side: Visual/Promo -->
        <div class="login-right">
            <div class="login-intro">
                <h2>Oyun Dünyası Seni Bekliyor</h2>
                <div class="feature-pills">
                    <span>🎮 Son Haberler</span>
                    <span>🔥 Özel İncelemeler</span>
                    <span>💬 Topluluk</span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* VARIABLES (Matches Register & Profile) */
    :root {
        --l-bg: #0B0F14;
        --l-accent: #E11D48;
        --l-text: #E8EEF6;
        --l-text-muted: rgba(232, 238, 246, 0.6);
        --l-border: rgba(255,255,255,0.1);
    }

    /* Layout */
    .login-page-wrapper {
        background-color: var(--l-bg);
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center; /* Center horizontally */
        justify-content: center; /* Center vertically */
        padding: 40px 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .login-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-width: 800px;
        width: 100%;
        background: radial-gradient(circle at bottom left, rgba(30, 30, 30, 0.5), transparent);
        border: 1px solid var(--l-border);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    /* Left Side (Form) */
    .login-left { 
        padding: 50px; 
        background: rgba(0,0,0,0.2); 
        backdrop-filter: blur(10px);
        display: flex; flex-direction: column; justify-content: center;
    }

    .glass-form-card h3 {
        font-size: 2rem; color: #fff; margin-bottom: 5px; font-weight: 800;
        text-align: left;
    }
    .sub-text { color: var(--l-text-muted); font-size: 0.95rem; margin-bottom: 30px; }

    /* Right Side (Visual) */
    .login-right {
        background: linear-gradient(135deg, rgba(225, 29, 72, 0.1), #000);
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        text-align: center;
    }
    
    .login-right::before {
        content: '';
        position: absolute;
        bottom: -50px; right: -50px;
        width: 250px; height: 250px;
        background: var(--l-accent);
        filter: blur(120px);
        opacity: 0.25;
    }

    .login-intro h2 { font-size: 2.2rem; color: #fff; margin-bottom: 25px; font-weight: 800; line-height: 1.2; position: relative; z-index: 2; }
    
    .feature-pills { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; position: relative; z-index: 2; }
    .feature-pills span {
        background: rgba(255,255,255,0.1);
        padding: 6px 14px;
        border-radius: 20px;
        color: #fff;
        font-size: 0.85rem;
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Form Elements */
    .form-group { margin-bottom: 18px; }
    
    .form-group label {
        display: block; color: var(--l-text-muted); font-size: 0.85rem; margin-bottom: 8px; font-weight: 600;
    }

    .form-group input {
        width: 100%;
        box-sizing: border-box;
        background: rgba(11, 15, 20, 0.6);
        border: 1px solid var(--l-border);
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 0.95rem;
        transition: all 0.2s;
        height: 44px;
    }
    
    .form-group input:focus {
        border-color: var(--l-accent);
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15);
        outline: none;
        background: rgba(0,0,0,0.4);
    }

    /* Pass Toggle */
    .pass-wrapper { position: relative; }
    .toggle-pass {
        position: absolute; right: 12px; top: 12px;
        color: var(--l-text-muted); cursor: pointer;
        font-size: 18px;
    }

    /* Actions Row */
    .form-row-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; font-size: 0.85rem;
    }
    .remember-me { color: var(--l-text-muted); display: flex; align-items: center; gap: 6px; cursor: pointer; }
    .lost-pass-link { color: var(--l-text-muted); text-decoration: none; transition: 0.2s; }
    .lost-pass-link:hover { color: #fff; text-decoration: underline; }

    /* Button */
    .btn-login-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--l-accent), #ef4444);
        color: #fff; border: none;
        padding: 12px; font-size: 1rem; font-weight: 700;
        border-radius: 10px; cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-login-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(225, 29, 72, 0.3);
    }

    .register-link { text-align: center; margin-top: 20px; color: var(--l-text-muted); font-size: 0.9rem; }
    .register-link a { color: #fff; text-decoration: none; font-weight: 700; }
    .register-link a:hover { color: var(--l-accent); text-decoration: underline; }

    /* Messages */
    .msg-box { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
    .error { background: rgba(220, 38, 38, 0.2); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.3); }

    /* Responsive */
    @media (max-width: 800px) {
        .login-container { grid-template-columns: 1fr; max-width: 450px; }
        .login-right { display: none; } 
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reuse the password toggle logic
    const toggle = document.querySelector('.toggle-pass');
    const input = document.getElementById('user_pass');
    
    if(toggle && input) {
        toggle.addEventListener('click', function() {
            if(input.type === 'password') {
                input.type = 'text';
                toggle.classList.remove('dashicons-visibility');
                toggle.classList.add('dashicons-hidden');
            } else {
                input.type = 'password';
                toggle.classList.remove('dashicons-hidden');
                toggle.classList.add('dashicons-visibility');
            }
        });
    }
});
</script>

<?php get_footer(); ?>
