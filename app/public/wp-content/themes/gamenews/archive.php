<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
            echo '<div class="card-grid">';
            /* Start the Loop */
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
