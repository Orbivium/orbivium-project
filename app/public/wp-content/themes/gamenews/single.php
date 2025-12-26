<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php 
                    $platforms = get_the_terms( get_the_ID(), 'platform' );
                    if ( $platforms && ! is_wp_error( $platforms ) ) : 
                        echo '<div class="single-platforms">';
                        foreach ( $platforms as $platform ) {
                            echo '<span class="platform-badge">' . esc_html( $platform->name ) . '</span> ';
                        }
                        echo '</div>';
                    endif;
                    ?>
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <div class="entry-meta">
                        <?php echo get_the_date(); ?> by <?php the_author(); ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'oyunhaber' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php 
                        if(has_category()) {
                            echo '<div class="cat-links">Posted in: ' . get_the_category_list(', ') . '</div>';
                        }
                        if(has_tag()) {
                            echo '<div class="tags-links">Tags: ' . get_the_tag_list('', ', ') . '</div>';
                        }
                    ?>
                </footer>
            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
        </div>
	</main><!-- #primary -->

<?php
get_footer();
