<?php
/**
 * Template Name: Kayıt Ol
 *
 * @package OyunHaber
 */

// If logged in, redirect to profile
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profil' ) ); 
    exit;
}

$error_message = '';
$success_message = '';

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['oyunhaber_register_nonce'] ) && wp_verify_nonce( $_POST['oyunhaber_register_nonce'], 'oyunhaber_register_action' ) ) {
    
    $username   = sanitize_user( $_POST['username'] );
    $email      = sanitize_email( $_POST['email'] );
    $password   = $_POST['password']; // wp_create_user handles hashing, but we validate strictly
    $first_name = sanitize_text_field( $_POST['first_name'] );
    $last_name  = sanitize_text_field( $_POST['last_name'] );
    $phone      = sanitize_text_field( $_POST['phone'] );

    // Validation
    if ( empty( $username ) || empty( $email ) || empty( $password ) || empty( $first_name ) || empty( $last_name ) ) {
        $error_message = 'Lütfen zorunlu alanları doldurunuz.';
    } elseif ( username_exists( $username ) ) {
        $error_message = 'Bu kullanıcı adı zaten alınmış.';
    } elseif ( email_exists( $email ) ) {
        $error_message = 'Bu e-posta adresi zaten kayıtlı.';
    } elseif ( !is_email( $email ) ) {
        $error_message = 'Geçersiz e-posta adresi.';
    } else {
        // Check for duplicate phone number
        if ( ! empty( $phone ) ) {
             $existing_users = get_users(array(
                'meta_key' => 'phone_number',
                'meta_value' => $phone,
                'number' => 1,
                'count_total' => false
            ));
            if ( ! empty( $existing_users ) ) {
                $error_message = 'Bu telefon numarası zaten başka bir hesaba kayıtlı.';
            }
        }
    }

    if ( empty( $error_message ) ) {
        // Create User
        $user_id = wp_create_user( $username, $password, $email );

        if ( ! is_wp_error( $user_id ) ) {
            // Update User Meta
            update_user_meta( $user_id, 'first_name', $first_name );
            update_user_meta( $user_id, 'last_name', $last_name );
            update_user_meta( $user_id, 'signup_ip', $_SERVER['REMOTE_ADDR'] ); // Save IP
            
            if ( ! empty( $phone ) ) {
                update_user_meta( $user_id, 'phone_number', $phone );
            }

            // Determine Role (Standard User)
            $user_id_role = new WP_User( $user_id );
            $user_id_role->set_role( 'subscriber' );

            // Auto Login? Or Redirect? Let's redirect to login for security/simplicity or show success.
            // User requested "Kayit ol sayfası" - usually better to login immediately or say "Registration Successful".
            // Let's sign them in automatically for better UX.
            
            wp_set_current_user( $user_id, $username );
            wp_set_auth_cookie( $user_id );
            
            // Redirect to Profile
            wp_redirect( home_url( '/profil' ) );
            exit;

        } else {
            $error_message = $user_id->get_error_message();
        }
    }
}

get_header(); 
?>

<div class="register-page-wrapper">
    <div class="register-container">
        
        <div class="register-left">
            <div class="register-intro">
                <h2>Aramıza Katıl</h2>
                <p>Oyun dünyasının nabzını tutmak, incelemeler yazmak ve topluluğun bir parçası olmak için hemen üye ol.</p>
                
                <div class="feature-list">
                    <div class="f-item"><span class="dashicons dashicons-edit"></span> İnceleme ve Haber Paylaş</div>
                    <div class="f-item"><span class="dashicons dashicons-groups"></span> Toplulukla Etkileşime Geç</div>
                    <div class="f-item"><span class="dashicons dashicons-awards"></span> Rozetler Kazan</div>
                </div>
            </div>
        </div>

        <div class="register-right">
            <div class="glass-form-card">
                <h3>Hesap Oluştur</h3>
                
                <?php if ( $error_message ) : ?>
                    <div class="msg-box error"><?php echo esc_html( $error_message ); ?></div>
                <?php endif; ?>

                <form method="post" class="register-form">
                    <?php wp_nonce_field( 'oyunhaber_register_action', 'oyunhaber_register_nonce' ); ?>
                    
                    <div class="form-row-split">
                        <div class="form-group">
                            <label for="first_name">Ad <span class="req">*</span></label>
                            <input type="text" name="first_name" id="first_name" required placeholder="Adınız" value="<?php echo isset($_POST['first_name']) ? esc_attr($_POST['first_name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Soyad <span class="req">*</span></label>
                            <input type="text" name="last_name" id="last_name" required placeholder="Soyadınız" value="<?php echo isset($_POST['last_name']) ? esc_attr($_POST['last_name']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Kullanıcı Adı <span class="req">*</span></label>
                        <input type="text" name="username" id="username" required placeholder="Kullanıcı adı seçin" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">E-Posta <span class="req">*</span></label>
                        <input type="email" name="email" id="email" required placeholder="ornek@email.com" value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Cep Telefonu (Opsiyonel)</label>
                        <input type="text" name="phone" id="phone" placeholder="05XX XXX XX XX" value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Şifre <span class="req">*</span></label>
                        <div class="pass-wrapper">
                            <input type="password" name="password" id="password" required placeholder="Güçlü bir şifre oluşturun">
                            <span class="dashicons dashicons-visibility toggle-pass"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-register-submit">Kayıt Ol</button>

                    <p class="login-link">
                        Zaten hesabın var mı? <a href="<?php echo home_url('/giris-yap/'); ?>">Giriş Yap</a>
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    /* VARIABLES (Matches Profile Page) */
    :root {
        --r-bg: #0B0F14;
        --r-accent: #E11D48;
        --r-text: #E8EEF6;
        --r-text-muted: rgba(232, 238, 246, 0.6);
        --r-card-bg: rgba(255,255,255,0.03);
        --r-border: rgba(255,255,255,0.1);
    }

    /* Layout */
    .register-page-wrapper {
        background-color: var(--r-bg);
        min-height: calc(100vh - 80px); /* Adjust for header */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .register-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-width: 860px; /* Reduced from 1000px */
        width: 100%;
        background: radial-gradient(circle at top right, rgba(30, 30, 30, 0.5), transparent);
        border: 1px solid var(--r-border);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    /* Left Side */
    .register-left {
        background: linear-gradient(135deg, rgba(225, 29, 72, 0.1), transparent);
        padding: 50px; /* Reduced padding */
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }
    
    .register-left::before {
        content: '';
        position: absolute;
        top: -50px; left: -50px;
        width: 200px; height: 200px;
        background: var(--r-accent);
        filter: blur(100px);
        opacity: 0.2;
    }

    .register-intro h2 { font-size: 2.2rem; color: #fff; margin-bottom: 15px; font-weight: 800; line-height: 1.1; }
    .register-intro p { font-size: 1rem; color: var(--r-text-muted); margin-bottom: 30px; line-height: 1.6; }

    .f-item {
        display: flex; align-items: center; gap: 12px;
        color: #fff; font-weight: 600; margin-bottom: 15px;
        font-size: 0.95rem;
    }
    .f-item .dashicons {
        background: rgba(255,255,255,0.1);
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--r-accent);
        font-size: 18px;
    }

    /* Right Side */
    .register-right { padding: 40px; background: rgba(0,0,0,0.2); backdrop-filter: blur(10px); }

    .glass-form-card h3 {
        font-size: 1.6rem; color: #fff; margin-bottom: 25px; font-weight: 700;
        text-align: center;
    }

    /* Form Elements */
    .form-row-split { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-group { margin-bottom: 16px; }
    
    .form-group label {
        display: block; color: var(--r-text-muted); font-size: 0.85rem; margin-bottom: 6px; font-weight: 600;
    }
    .req { color: var(--r-accent); }

    .form-group input {
        width: 100%;
        box-sizing: border-box; /* Fixes overlap issue */
        background: rgba(11, 15, 20, 0.6);
        border: 1px solid var(--r-border);
        border-radius: 10px; 
        padding: 10px 14px;
        color: #fff;
        font-size: 0.95rem;
        transition: all 0.2s;
        height: 44px; 
    }
    
    .form-group input:focus {
        border-color: var(--r-accent);
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15);
        outline: none;
        background: rgba(0,0,0,0.4);
    }
    
    .form-group input::placeholder { color: rgba(255,255,255,0.2); }

    /* Pass Toggle */
    .pass-wrapper { position: relative; }
    .toggle-pass {
        position: absolute; right: 12px; top: 12px;
        color: var(--r-text-muted); cursor: pointer;
        font-size: 18px;
    }

    /* Button */
    .btn-register-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--r-accent), #ef4444);
        color: #fff; border: none;
        padding: 12px; font-size: 1rem; font-weight: 700;
        border-radius: 10px; cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-top: 5px;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(225, 29, 72, 0.3);
    }

    .login-link { text-align: center; margin-top: 15px; color: var(--r-text-muted); font-size: 0.85rem; }
    .login-link a { color: #fff; text-decoration: none; font-weight: 700; }
    .login-link a:hover { color: var(--r-accent); text-decoration: underline; }

    /* Messages */
    .msg-box { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
    .error { background: rgba(220, 38, 38, 0.2); color: #fca5a5; border: 1px solid rgba(220, 38, 38, 0.3); }

    /* Responsive */
    @media (max-width: 900px) {
        .register-container { grid-template-columns: 1fr; max-width: 450px; }
        .register-left { display: none; } 
        .register-right { padding: 30px; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone Input Formatting (Numbers only, max 11)
    const phoneInput = document.getElementById('phone');
    if(phoneInput) {
        phoneInput.placeholder = "05XXXXXXXXX";
        phoneInput.addEventListener('input', function(e) {
            let val = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (val.length > 11) val = val.slice(0, 11); // Max 11 digits
            e.target.value = val;
        });
    }

    const toggle = document.querySelector('.toggle-pass');
    const input = document.getElementById('password');
    
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
