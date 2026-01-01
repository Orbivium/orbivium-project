<?php
/**
 * Template Name: Şifre Sıfırlama (Reset Password)
 *
 * @package OyunHaber
 */

// Eğer kullanıcı zaten giriş yapmışsa profile yönlendir
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profil' ) );
    exit;
}

// URL parametrelerini al
$key = isset( $_GET['key'] ) ? $_GET['key'] : '';
$login = isset( $_GET['login'] ) ? $_GET['login'] : '';

$message = '';
$msg_type = '';
$show_form = false;
$user = false;

// POST işlemi (Şifre güncelleme)
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pass1']) && isset($_POST['pass2']) ) {
    // Formdan gelen verileri al (hidden inputlardan key ve login de gelecek)
    $key = isset($_POST['key']) ? $_POST['key'] : '';
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    
    // Tekrar anahtarı doğrula
    $user = check_password_reset_key( $key, $login );

    if ( is_wp_error( $user ) ) {
        $message = 'Geçersiz veya süresi dolmuş anahtar. Lütfen şifre sıfırlama işlemini tekrar başlatın.';
        $msg_type = 'error';
    } else {
        if ( $_POST['pass1'] !== $_POST['pass2'] ) {
            $message = 'Şifreler eşleşmiyor.';
            $msg_type = 'error';
            $show_form = true; // Formu tekrar göster
        } elseif ( empty($_POST['pass1']) ) {
            $message = 'Şifre boş olamaz.';
            $msg_type = 'error';
            $show_form = true;
        } else {
            // Şifreyi güncelle
            reset_password( $user, $_POST['pass1'] );
            $message = 'Şifreniz başarıyla sıfırlandı! Yönlendiriliyorsunuz...';
            $msg_type = 'success';
            // 2 saniye sonra giriş sayfasına at
            header( "refresh:2;url=" . home_url('/giris-yap/') ); 
        }
    }

} else {
    // İlk yüklendiğinde (GET) anahtarı kontrol et
    if ( empty($key) || empty($login) ) {
        $message = 'Hatalı bağlantı. Lütfen e-postanızdaki linke tekrar tıklayın.';
        $msg_type = 'error';
    } else {
        $user = check_password_reset_key( $key, $login );
        if ( is_wp_error( $user ) ) {
            $message = 'Bu şifre sıfırlama bağlantısının süresi dolmuş veya geçersiz.';
            $msg_type = 'error';
        } else {
            $show_form = true;
        }
    }
}

get_header(); 
?>

<div class="auth-page-wrapper">
    <div class="auth-card-centered">
        
        <div class="auth-header">
            <h2>Yeni Şifre Belirle</h2>
            <p>Lütfen hesabınız için yeni ve güvenli bir şifre girin.</p>
        </div>

        <?php if ( $message ) : ?>
            <div class="msg-box <?php echo $msg_type; ?>"><?php echo esc_html( $message ); ?></div>
        <?php endif; ?>

        <?php if ( $show_form ) : ?>
        <form method="post" class="auth-form" autocomplete="off">
            <!-- WP'nin şifre sıfırlaması için key ve login şart -->
            <input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>" />
            <input type="hidden" name="login" value="<?php echo esc_attr( $login ); ?>" />

            <div class="form-group">
                <label for="pass1">Yeni Şifre</label>
                <div class="password-input-wrapper">
                    <input type="password" name="pass1" id="pass1" required autocomplete="new-password">
                    <span class="toggle-password dashicons dashicons-visibility" onclick="togglePassword('pass1', this)"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="pass2">Yeni Şifre (Tekrar)</label>
                <div class="password-input-wrapper">
                    <input type="password" name="pass2" id="pass2" required autocomplete="new-password">
                    <span class="toggle-password dashicons dashicons-visibility" onclick="togglePassword('pass2', this)"></span>
                </div>
            </div>

            <button type="submit" class="btn-auth-submit">Şifreyi Kaydet</button>
        </form>
        <?php endif; ?>

        <?php if ( ! $show_form && $msg_type == 'error' ) : ?>
             <div class="auth-links">
                <a href="<?php echo home_url('/sifremi-unuttum/'); ?>" class="back-link">Şifremi Unuttum sayfasına dön</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('dashicons-visibility');
            icon.classList.add('dashicons-hidden');
        } else {
            input.type = "password";
            icon.classList.remove('dashicons-hidden');
            icon.classList.add('dashicons-visibility');
        }
    }
</script>

<style>
    /* Diğer auth sayfalarıyla aynı CSS temeli */
    :root {
        --auth-bg: #0B0F14;
        --auth-accent: #E11D48;
        --auth-text: #E8EEF6;
        --auth-text-muted: rgba(232, 238, 246, 0.6);
        --auth-border: rgba(255,255,255,0.1);
    }

    .auth-page-wrapper {
        background-color: var(--auth-bg);
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .auth-card-centered {
        max-width: 450px;
        width: 100%;
        background: radial-gradient(circle at top, rgba(30,30,30,0.4), var(--auth-bg));
        border: 1px solid var(--auth-border);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
    }

    /* Ambient Glow */
    .auth-card-centered::before {
        content: '';
        position: absolute;
        top: -100px; left: 50%; width: 300px; height: 300px;
        background: var(--auth-accent);
        transform: translateX(-50%);
        filter: blur(120px);
        opacity: 0.15;
        z-index: 0;
    }

    .auth-header, .auth-form, .msg-box { position: relative; z-index: 1; }

    .auth-header { text-align: center; margin-bottom: 30px; }
    .auth-header h2 { font-size: 1.8rem; margin-bottom: 10px; color: #fff; font-weight: 800; }
    .auth-header p { font-size: 0.95rem; color: var(--auth-text-muted); line-height: 1.5; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--auth-text-muted); font-size: 0.9rem; }
    
    .password-input-wrapper { position: relative; }
    .form-group input { 
        width: 100%; box-sizing: border-box; 
        background: rgba(255,255,255,0.05); 
        border: 1px solid var(--auth-border); 
        border-radius: 10px; padding: 12px; padding-right: 40px;
        color: #fff; font-size: 1rem;
        transition: 0.2s;
    }
    .form-group input:focus { border-color: var(--auth-accent); outline: none; background: rgba(0,0,0,0.3); }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--auth-text-muted);
        cursor: pointer;
    }

    .btn-auth-submit {
        width: 100%; padding: 12px; font-size: 1rem; font-weight: 700;
        border-radius: 10px; border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--auth-accent), #ef4444);
        color: #fff; margin-bottom: 10px;
        transition: transform 0.2s;
        margin-top: 10px;
    }
    .btn-auth-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225,29,72,0.3); }

    .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center; font-size: 0.95rem; }
    .error { background: rgba(220, 38, 38, 0.15); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.2); }
    .success { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.2); }
    
    .auth-links { text-align: center; margin-top: 20px; }
    .back-link { color: var(--auth-accent); text-decoration: none; }
</style>

<?php get_footer(); ?>
