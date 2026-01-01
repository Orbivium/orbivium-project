<?php
/**
 * The template for displaying the footer
 *
 * @package OyunHaber
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer-premium">
        <div class="container">
            <div class="footer-grid">
                
                <!-- 1. Brand & About Link -->
                <div class="footer-col brand-col">
                    <div class="footer-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-logo">Orbi</a>
                        <?php endif; ?>
                    </div>
                    <p class="brand-slogan">Oyun dünyasının nabzını tutan tek adres.</p>
                    
                    <a href="<?php echo home_url('/hakkimizda/'); ?>" class="btn-footer-about">
                        Hakkımızda <span class="dashicons dashicons-arrow-right-alt2"></span>
                    </a>
                </div>

                <!-- 2. Legal Links -->
                <div class="footer-col links-col">
                    <h4 class="footer-heading">Hızlı Erişim</h4>
                    <ul class="footer-links">
                        <?php 
                        // Custom Link Builder Logic
                        $has_links = false;
                        for ($i = 1; $i <= 5; $i++) {
                            $text = get_option("oyunhaber_legal_link_text_$i");
                            $url = get_option("oyunhaber_legal_link_url_$i");
                            if ( !empty($text) ) {
                                $has_links = true;
                                echo '<li><a href="' . esc_url($url) . '">' . esc_html($text) . '</a></li>';
                            }
                        }
                        // Default fallbacks
                        if ( ! $has_links ) {
                            echo '<li><a href="' . home_url('/gizlilik-politikasi/') . '">Gizlilik Politikası</a></li>';
                            echo '<li><a href="' . home_url('/kullanim-sartlari/') . '">Kullanım Şartları</a></li>';
                            echo '<li><a href="' . home_url('/iletisim/') . '">İletişim</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- 3. Social Media -->
                <div class="footer-col social-col">
                    <h4 class="footer-heading">Takipte Kalın</h4>
                    
                    <div class="social-grid">
                         <?php 
                            $socials = array(
                                'twitter' => array( 'icon' => 'dashicons-twitter', 'url' => get_option('oyunhaber_social_twitter') ),
                                'instagram' => array( 'icon' => 'dashicons-instagram', 'url' => get_option('oyunhaber_social_instagram') ),
                                'youtube' => array( 'icon' => 'dashicons-youtube', 'url' => get_option('oyunhaber_social_youtube') ),
                                'twitch' => array( 'icon' => 'dashicons-twitch', 'url' => get_option('oyunhaber_social_twitch') ),
                                'facebook' => array( 'icon' => 'dashicons-facebook-alt', 'url' => get_option('oyunhaber_social_facebook') ),
                            );
                            
                            foreach ($socials as $key => $data) {
                                if ( ! empty( $data['url'] ) ) {
                                    echo '<a href="' . esc_url($data['url']) . '" class="social-item ' . esc_attr($key) . '" target="_blank">';
                                    echo '<span class="dashicons ' . esc_attr($data['icon']) . '"></span>';
                                    echo '</a>';
                                }
                            }
                        ?>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> <strong>Orbi</strong>. Tüm hakları saklıdır.
                </div>
            </div>
        </div>

        <!-- Decorative Glow -->
        <div class="footer-glow"></div>
    </footer>

</div><!-- #page -->

<button id="scroll-to-top" title="<?php esc_attr_e('Yukarı Çık', 'oyunhaber'); ?>">
    <span class="dashicons dashicons-arrow-up-alt2"></span>
</button>

<style>
/* PREMIUM FOOTER STYLES */
.site-footer-premium {
    background-color: #05070a; /* Very dark, almost black */
    color: #a0aec0;
    padding: 80px 0 30px;
    position: relative;
    overflow: hidden;
    border-top: 1px solid rgba(255,255,255,0.05);
    margin-top: auto;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 60px;
    margin-bottom: 60px;
    position: relative;
    z-index: 2;
}

.footer-heading {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 25px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Brand Col */
.footer-logo { margin-bottom: 20px; }
.text-logo { font-size: 2rem; font-weight: 900; color: #fff; text-decoration: none; letter-spacing: -1px; }
.brand-slogan { margin-bottom: 30px; font-size: 1rem; line-height: 1.6; max-width: 300px; }

.btn-footer-about {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 24px;
    background: rgba(255,255,255,0.1);
    color: #fff;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.1);
}
.btn-footer-about:hover {
    background: var(--accent-color, #E11D48);
    border-color: var(--accent-color, #E11D48);
    transform: translateX(5px);
}

/* Links Col */
.footer-links { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 12px; }
.footer-links a {
    color: #a0aec0;
    text-decoration: none;
    transition: color 0.2s;
    font-size: 0.95rem;
    display: inline-block;
}
.footer-links a:hover { color: #fff; transform: translateX(3px); }

/* Social Col */
.social-grid { display: flex; gap: 12px; flex-wrap: wrap; }
.social-item {
    width: 42px; height: 42px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255,255,255,0.05);
}
.social-item:hover {
    background: var(--accent-color, #E11D48);
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(225, 29, 72, 0.3);
}
.social-item .dashicons { font-size: 20px; width: 20px; height: 20px; }

/* Bottom */
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.05);
    padding-top: 30px;
    text-align: center;
    font-size: 0.9rem;
    color: #718096;
}

/* Decor */
.footer-glow {
    position: absolute;
    bottom: -100px; right: -100px;
    width: 400px; height: 400px;
    background: var(--accent-color, #E11D48);
    filter: blur(150px);
    opacity: 0.15;
    z-index: 0;
    pointer-events: none;
}

@media (max-width: 900px) {
    .footer-grid { grid-template-columns: 1fr; gap: 40px; text-align: center; }
    .brand-slogan { margin: 0 auto 30px; }
    .footer-links a:hover { transform: none; }
    .social-grid { justify-content: center; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var scrollButton = document.getElementById('scroll-to-top');
    
    window.onscroll = function() {
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            scrollButton.style.opacity = "1";
            scrollButton.style.visibility = "visible";
        } else {
            scrollButton.style.opacity = "0";
            scrollButton.style.visibility = "hidden";
        }
    };

    scrollButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
