<?php
get_header();
?>

	<main id="primary" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();
            
            $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <!-- Hero Section -->
                <header class="entry-header single-hero <?php echo $thumb_url ? 'has-bg' : ''; ?>">
                    <?php if ( $thumb_url ) : ?>
                        <div class="single-hero-bg" style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
                        <div class="single-hero-overlay"></div>
                    <?php endif; ?>

                    <div class="container hero-container">
                        <div class="hero-content">
                            <?php 
                            $platforms = get_the_terms( get_the_ID(), 'platform' );
                            if ( $platforms && ! is_wp_error( $platforms ) ) : 
                                echo '<div class="single-platforms">';
                                foreach ( $platforms as $platform ) {
                                    echo '<span class="platform-badge platform-' . esc_attr( $platform->slug ) . '">' . esc_html( $platform->name ) . '</span> ';
                                }
                                echo '</div>';
                            endif;
                            ?>
                            
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                            
                            <div class="entry-meta">
                                <span class="posted-by">
                                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                                    <span class="author-name"><?php the_author(); ?></span>
                                </span>
                                <span class="posted-on">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="comment-count">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                    <?php comments_number( '0 Yorum', '1 Yorum', '% Yorum' ); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Content Section -->
                <div class="container container-narrow">
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
                                echo '<div class="cat-links"><span class="meta-label">Kategori:</span> ' . get_the_category_list(', ') . '</div>';
                            }
                            if(has_tag()) {
                                echo '<div class="tags-links"><span class="meta-label">Etiketler:</span> ' . get_the_tag_list('', ', ') . '</div>';
                            }
                        ?>
                    </footer>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </div>

            </article>

		<?php endwhile; // End of the loop.
		?>
	</main><!-- #primary -->

<?php
get_footer();
