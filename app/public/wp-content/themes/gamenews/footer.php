	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-widgets">
                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php else : ?>
                        <h3 class="widget-title"><?php esc_html_e('Hakkımızda', 'oyunhaber'); ?></h3>
                        <p><?php bloginfo('description'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <h3 class="widget-title"><?php esc_html_e('Site Haritası', 'oyunhaber'); ?></h3>
                        <ul>
                            <?php wp_list_pages(array('title_li' => '', 'depth' => 1)); ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php else : ?>
                        <h3 class="widget-title"><?php esc_html_e('Yasal', 'oyunhaber'); ?></h3>
                        <ul>
                            <li><a href="#"><?php esc_html_e('Gizlilik Politikası', 'oyunhaber'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Kullanım Şartları', 'oyunhaber'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Künye', 'oyunhaber'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="footer-column">
                     <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-4' ); ?>
                    <?php else : ?>
                        <h3 class="widget-title"><?php esc_html_e('Bizi Takip Edin', 'oyunhaber'); ?></h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><span class="dashicons dashicons-twitter"></span></a>
                            <a href="#" class="social-icon"><span class="dashicons dashicons-instagram"></span></a>
                            <a href="#" class="social-icon"><span class="dashicons dashicons-youtube"></span></a>
                            <a href="#" class="social-icon"><span class="dashicons dashicons-twitch"></span></a>
                            <a href="#" class="social-icon"><span class="dashicons dashicons-facebook-alt"></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

			<div class="site-info">
                <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'oyunhaber' ); ?></p>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<button id="scroll-to-top" title="<?php esc_attr_e('Yukarı Çık', 'oyunhaber'); ?>">
    <span class="dashicons dashicons-arrow-up-alt2"></span>
</button>

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
