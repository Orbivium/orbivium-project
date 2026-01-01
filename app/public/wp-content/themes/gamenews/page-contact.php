<?php
/**
 * Template Name: İletişim
 *
 * @package OyunHaber
 */

$msg_sent = false;
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_nonce']) && wp_verify_nonce($_POST['contact_nonce'], 'send_contact') ) {
    // In a real scenario, you would mail() this.
    // For now, let's simulate success.
    $msg_sent = true;
}

get_header(); 
?>

<div class="contact-page-wrapper">
    <div class="container contact-container">
        
        <div class="contact-header">
            <h1><?php the_title(); ?></h1>
            <div class="contact-intro-text">
                <?php 
                if ( have_posts() ) : 
                    while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                else: 
                ?>
                    <p>Soru, görüş veya iş birliği önerileriniz için aşağıdaki formu doldurabilirsiniz.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="contact-grid">
            <!-- Info Side -->
            <div class="contact-info">
                <div class="info-card">
                    <h3>İletişim Bilgileri</h3>
                    <div class="info-item">
                        <span class="dashicons dashicons-email-alt"></span>
                        <div>
                            <strong>E-Posta</strong>
                            <span><?php echo esc_html(get_option('oyunhaber_contact_email', 'iletisim@orbi.local')); ?></span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="dashicons dashicons-location"></span>
                        <div>
                            <strong>Konum</strong>
                            <span><?php echo esc_html(get_option('oyunhaber_contact_address', 'İstanbul, Türkiye')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="info-card social-card">
                    <h3>Sosyal Medya</h3>
                    <div class="c-social-icons">
                        <?php 
                        $contact_socials = array(
                            'twitter'   => array( 'icon' => 'dashicons-twitter', 'url' => get_option('oyunhaber_social_twitter') ),
                            'instagram' => array( 'icon' => 'dashicons-instagram', 'url' => get_option('oyunhaber_social_instagram') ),
                            'youtube'   => array( 'icon' => 'dashicons-youtube', 'url' => get_option('oyunhaber_social_youtube') ),
                            'twitch'    => array( 'icon' => 'dashicons-twitch', 'url' => get_option('oyunhaber_social_twitch') ),
                            'facebook'  => array( 'icon' => 'dashicons-facebook-alt', 'url' => get_option('oyunhaber_social_facebook') ),
                        );
                        
                        foreach ($contact_socials as $key => $data) {
                            if ( ! empty( $data['url'] ) ) {
                                echo '<a href="' . esc_url($data['url']) . '" class="s-icon" target="_blank" rel="noopener noreferrer"><span class="dashicons ' . esc_attr($data['icon']) . '"></span></a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Form Side -->
            <div class="contact-form-wrapper">
                <div class="glass-card">
                    <?php if ( $msg_sent ) : ?>
                        <div class="success-box">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <h3>Mesajınız iletildi!</h3>
                            <p>En kısa sürede size dönüş yapacağız.</p>
                        </div>
                    <?php else : ?>
                        <form method="post">
                            <?php wp_nonce_field('send_contact', 'contact_nonce'); ?>
                            
                            <div class="form-group">
                                <label>Adınız Soyadınız</label>
                                <input type="text" name="c_name" required placeholder="Adınız">
                            </div>

                            <div class="form-group">
                                <label>E-Posta Adresiniz</label>
                                <input type="email" name="c_email" required placeholder="E-posta">
                            </div>

                            <div class="form-group">
                                <label>Konu</label>
                                <select name="c_subject">
                                    <option>Genel Soru</option>
                                    <option>Reklam / İş Birliği</option>
                                    <option>Hata Bildirimi</option>
                                    <option>Bize Katılın</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Mesajınız</label>
                                <textarea name="c_message" required rows="5" placeholder="Mesajınızı buraya yazın..."></textarea>
                            </div>

                            <button type="submit" class="btn-send">Gönder <span class="dashicons dashicons-paperplane"></span></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Contact CSS */
    .contact-page-wrapper {
        background-color: #0B0F14;
        min-height: 80vh;
        padding: 60px 0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .contact-header { text-align: center; margin-bottom: 50px; }
    .contact-header h1 { font-size: 3rem; color: #fff; font-weight: 800; margin-bottom: 10px; }
    .contact-header p { color: rgba(232, 238, 246, 0.7); font-size: 1.1rem; }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Cards */
    .glass-card, .info-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
    }

    .info-card { margin-bottom: 20px; }
    .info-card h3 { color: #fff; font-size: 1.2rem; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; }

    .info-item { display: flex; gap: 15px; margin-bottom: 20px; color: rgba(232,238,246,0.8); }
    .info-item .dashicons { 
        background: rgba(225,29,72,0.1); color: #E11D48;
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 20px;
    }
    .info-item strong { display: block; color: #fff; margin-bottom: 2px; }

    .c-social-icons { display: flex; gap: 10px; }
    .s-icon {
        width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 10px;
        display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;
        transition: 0.2s;
    }
    .s-icon:hover { background: #E11D48; transform: translateY(-3px); }

    /* Form */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: rgba(232,238,246,0.7); margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; }
    
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; box-sizing: border-box;
        background: rgba(11, 15, 20, 0.6);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 12px; color: #fff; font-size: 1rem;
        transition: 0.2s;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: #E11D48; outline: none; background: rgba(0,0,0,0.3);
    }

    .btn-send {
        width: 100%; padding: 15px; border-radius: 10px; border: none;
        background: linear-gradient(135deg, #E11D48, #ef4444);
        color: #fff; font-weight: 700; font-size: 1rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: 0.2s;
    }
    .btn-send:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(225,29,72,0.3); }

    .success-box { text-align: center; padding: 40px; }
    .success-box .dashicons { font-size: 60px; color: #2ecc71; height: 60px; width: 60px; margin-bottom: 20px; }
    .success-box h3 { color: #fff; font-size: 1.5rem; margin-bottom: 10px; }
    .success-box p { color: rgba(232,238,246,0.7); }

    @media (max-width: 800px) {
        .contact-grid { grid-template-columns: 1fr; }
        .contact-header h1 { font-size: 2.5rem; }
    }
</style>

<?php get_footer(); ?>
