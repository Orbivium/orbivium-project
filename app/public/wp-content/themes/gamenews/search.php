<?php
get_header();
?>

	<main id="primary" class="site-main">
        <div class="container">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'oyunhaber' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
            echo '<div class="card-grid">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
                get_template_part( 'template-parts/content-card' );
			endwhile;
            echo '</div>';

			the_posts_navigation();

		else :
            echo '<h2>' . esc_html__( 'Nothing Found', 'oyunhaber' ) . '</h2>';
            echo '<p>' . esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'oyunhaber' ) . '</p>';
            get_search_form();
		endif;
		?>
        </div>
	</main><!-- #primary -->

<?php
get_footer();
