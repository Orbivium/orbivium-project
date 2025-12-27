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
                        $order = array( 'genel', 'mobil', 'pc', 'playstation', 'xbox', 'nintendo' );
                        $sorted_terms = array();
                        $term_map = array();

                        foreach ( $terms as $term ) {
                            $term_map[ $term->slug ] = $term;
                        }

                        foreach ( $order as $slug ) {
                            if ( isset( $term_map[ $slug ] ) ) {
                                $sorted_terms[] = $term_map[ $slug ];
                                unset( $term_map[ $slug ] );
                            }
                        }

                        // Add remaining terms (like 'genel')
                        foreach ( $term_map as $term ) {
                            $sorted_terms[] = $term;
                        }

                        foreach ( $sorted_terms as $term ) {
                            $term_link = get_term_link( $term );
                            if ( is_wp_error( $term_link ) ) {
                                continue;
                            }

                            // Check for logo
                            $logo_html = '';
                            $theme_dir = get_template_directory();
                            $logo_path_rel = '/assets/images/platforms/' . $term->slug;
                            
                            if ( file_exists( $theme_dir . $logo_path_rel . '.svg' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.svg';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="platform-menu-logo" />';
                            } elseif ( file_exists( $theme_dir . $logo_path_rel . '.png' ) ) {
                                $logo_url = get_template_directory_uri() . $logo_path_rel . '.png';
                                $logo_html = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $term->name ) . '" class="platform-menu-logo" />';
                            }

                            $active_class = '';
                            if ( is_tax( 'platform', $term->slug ) || ( is_single() && has_term( $term->term_id, 'platform' ) ) ) {
                                $active_class = 'current-platform';
                            }

                            echo '<li class="' . esc_attr( $active_class ) . ' platform-item-' . esc_attr( $term->slug ) . '"><a href="' . esc_url( $term_link ) . '" class="platform-menu-link">' . $logo_html . '<span>' . esc_html( $term->name ) . '</span></a></li>';
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
                    <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="btn-login btn-profile"><span class="dashicons dashicons-admin-users" style="margin-right:5px; vertical-align:middle;"></span><?php esc_html_e( 'Profil', 'oyunhaber' ); ?></a>
                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn-login btn-logout"><?php esc_html_e( 'Çıkış', 'oyunhaber' ); ?></a>
                <?php else : ?>
                    <a href="<?php echo esc_url( wp_login_url( home_url() ) ); ?>" class="btn-login"><?php esc_html_e( 'Giriş', 'oyunhaber' ); ?></a>
                    <?php if ( get_option( 'users_can_register' ) ) : ?>
                        <a href="<?php echo esc_url( wp_registration_url() ); ?>" class="btn-login btn-register"><?php esc_html_e( 'Kayıt Ol', 'oyunhaber' ); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
		</div>
	</header><!-- #masthead -->
