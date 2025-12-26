<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                echo '<div class="card-grid">';
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content-card' );
                endwhile;
                echo '</div>'; // .card-grid

                the_posts_navigation();

            else :

                echo '<p>' . esc_html__( 'No posts found.', 'oyunhaber' ) . '</p>';

            endif;
            ?>
        </div>
	</main><!-- #primary -->

<?php
get_footer();
