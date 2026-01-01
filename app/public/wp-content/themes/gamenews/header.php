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
                    <?php
                    $logo_path = get_template_directory() . '/assets/images/fonts/logo.svg';
                    if ( file_exists( $logo_path ) ) {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="custom-logo-link">
                            <?php echo file_get_contents( $logo_path ); ?>
                        </a>
                        <?php
                    } else {
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Orbi</a></h1>
                        <?php
                    }
                    ?>
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

                            // Sub-menu definitions
                            $sub_menu_items = array();
                            $base_filter_url = $term_link; // Start with the platform URL

                            // Common Filters
                            // Haberler -> Post Type: news
                            $type_haber   = add_query_arg( 'filter_type', 'news', $base_filter_url );
                            // İncelemeler -> Post Type: reviews
                            $type_inceleme= add_query_arg( 'filter_type', 'reviews', $base_filter_url );
                            // Rehberler -> Category: rehberler (Works across all types)
                            $cat_rehber  = add_query_arg( 'filter_cat', 'rehberler', $base_filter_url );

                            // Default sub-tab structure
                            $sub_menu_items = array(
                                'Tümü'        => $base_filter_url,
                                'Haberler'    => $type_haber,
                                'İncelemeler' => $type_inceleme,
                                'Rehberler'   => $cat_rehber,
                            );

                            // Platform specific additions (Tags)
                            if ( $term->slug === 'playstation' ) {
                                $sub_menu_items['PS Plus'] = add_query_arg( 'filter_tag', 'ps-plus', $base_filter_url );
                            } elseif ( $term->slug === 'xbox' ) {
                                $sub_menu_items['Game Pass'] = add_query_arg( 'filter_tag', 'game-pass', $base_filter_url );
                            } elseif ( $term->slug === 'nintendo' ) {
                                $sub_menu_items['Özel Oyunlar'] = add_query_arg( 'filter_tag', 'ozel-oyunlar', $base_filter_url );
                            } elseif ( $term->slug === 'mobil' ) {
                                $sub_menu_items['Ücretsiz Oyunlar'] = add_query_arg( 'filter_tag', 'ucretsiz-oyunlar', $base_filter_url );
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
                            
                            // Render Item
                            echo '<li class="platform-item ' . esc_attr( $active_class ) . ' platform-item-' . esc_attr( $term->slug ) . '">';
                            echo '<a href="' . esc_url( $term_link ) . '" class="platform-menu-link">' . $logo_html . '<span>' . esc_html( $term->name ) . '</span></a>';
                            
                            // Render Sub-menu
                            if ( ! empty( $sub_menu_items ) ) {
                                echo '<ul class="platform-sub-menu">';
                                foreach ( $sub_menu_items as $label => $url ) {
                                    echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
                                }
                                echo '</ul>';
                            }
                            
                            echo '</li>';
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
                    <a href="<?php echo esc_url( home_url('/giris-yap/') ); ?>" class="btn-login"><?php esc_html_e( 'Giriş', 'oyunhaber' ); ?></a>
                    <?php if ( get_option( 'users_can_register' ) ) : ?>
                        <a href="<?php echo esc_url( home_url('/kayit-ol/') ); ?>" class="btn-login btn-register"><?php esc_html_e( 'Kayıt Ol', 'oyunhaber' ); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
		</div>
	</header><!-- #masthead -->
