<?php
/**
 * Template Name: Şifremi Unuttum
 *
 * @package OyunHaber
 */

if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profil' ) ); 
    exit;
}

$message = '';
$msg_type = ''; // error or success

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['user_login'] ) ) {
    $login = trim($_POST['user_login']);
    
    if ( empty( $login ) ) {
        $message = 'Lütfen kullanıcı adı veya e-posta adresinizi girin.';
        $msg_type = 'error';
    } else {
        // WordPress core function to handle lost password logic
        $errors = retrieve_password();
        
        if ( is_wp_error( $errors ) ) {
            $message = $errors->get_error_message();
            $msg_type = 'error';
        } else {
            $message = 'E-posta adresinize şifre sıfırlama bağlantısı gönderildi. Lütfen gelen kutunuzu (ve spam klasörünü) kontrol edin.';
            $msg_type = 'success';
        }
    }
}

get_header(); 
?>

<div class="auth-page-wrapper">
    <div class="auth-card-centered">
        
        <div class="auth-header">
            <h2>Şifremi Unuttum</h2>
            <p>Hesabınıza erişimi kaybettiyseniz endişelenmeyin. Aşağıdaki kutucuğa kayıtlı e-posta adresinizi veya kullanıcı adınızı yazın.</p>
        </div>

        <?php if ( $message ) : ?>
            <div class="msg-box <?php echo $msg_type; ?>"><?php echo esc_html( $message ); ?></div>
        <?php endif; ?>

        <form method="post" class="auth-form">
            <div class="form-group">
                <label for="user_login">Kullanıcı Adı veya E-Posta</label>
                <input type="text" name="user_login" id="user_login" required placeholder="ornek@email.com">
            </div>

            <!-- Hook for WP to handle specific actions if needed, though retrieve_password uses $_POST directly -->
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>?checkemail=confirm" />

            <button type="submit" class="btn-auth-submit">Şifre Sıfırla</button>

            <div class="auth-links">
                <a href="<?php echo home_url('/giris-yap/'); ?>" class="back-link"><span class="dashicons dashicons-arrow-left-alt2"></span> Giriş Yap'a Dön</a>
            </div>
        </form>

    </div>
</div>

<style>
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
        max-width: 500px;
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
    .auth-header h2 { font-size: 2rem; margin-bottom: 10px; color: #fff; font-weight: 800; }
    .auth-header p { font-size: 0.95rem; color: var(--auth-text-muted); line-height: 1.5; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--auth-text-muted); font-size: 0.9rem; }
    .form-group input { 
        width: 100%; box-sizing: border-box; 
        background: rgba(255,255,255,0.05); 
        border: 1px solid var(--auth-border); 
        border-radius: 10px; padding: 12px; 
        color: #fff; font-size: 1rem;
        transition: 0.2s;
    }
    .form-group input:focus { border-color: var(--auth-accent); outline: none; background: rgba(0,0,0,0.3); }

    .btn-auth-submit {
        width: 100%; padding: 12px; font-size: 1rem; font-weight: 700;
        border-radius: 10px; border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--auth-accent), #ef4444);
        color: #fff; margin-bottom: 20px;
        transition: transform 0.2s;
    }
    .btn-auth-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225,29,72,0.3); }

    .auth-links { text-align: center; }
    .back-link { color: var(--auth-text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; }
    .back-link:hover { color: #fff; }

    .msg-box { padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center; font-size: 0.95rem; }
    .error { background: rgba(220, 38, 38, 0.15); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.2); }
    .success { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.2); }
</style>

<?php get_footer(); ?>
