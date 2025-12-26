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
                <ul>
                    <?php
                    // Platformları Ana Menü Olarak Listele
                    $terms = get_terms( array(
                        'taxonomy'   => 'platform',
                        'hide_empty' => false,
                    ) );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $term_link = get_term_link( $term );
                            if ( is_wp_error( $term_link ) ) {
                                continue;
                            }
                            echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
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
