<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="container header-container">
			<div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                    <?php
                }
                ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
                    'container'      => false, // Remove container div to easier flex control
				) );
				?>
			</nav><!-- #site-navigation -->

            <div class="header-actions">
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
                
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn-login"><?php esc_html_e( 'Çıkış', 'oyunhaber' ); ?></a>
                <?php else : ?>
                    <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn-login"><?php esc_html_e( 'Giriş Yap', 'oyunhaber' ); ?></a>
                <?php endif; ?>
            </div>
		</div>
	</header><!-- #masthead -->
