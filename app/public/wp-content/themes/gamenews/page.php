<?php
/**
 * The template for displaying all single pages
 *
 * @package OyunHaber
 */

get_header();
?>

<div class="standard-page-wrapper">
    <div class="container">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('glass-card page-content'); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <div class="header-line"></div>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Sayfalar:', 'oyunhaber' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>

    </div>
</div>

<style>
    .standard-page-wrapper {
        padding: 60px 0;
        min-height: 60vh;
    }

    .page-content {
        padding: 50px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        max-width: 900px;
        margin: 0 auto;
    }

    .entry-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .entry-title {
        font-size: 2.5rem;
        color: #fff;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .header-line {
        width: 60px;
        height: 4px;
        background: var(--accent-color, #E11D48);
        margin: 0 auto;
        border-radius: 2px;
    }

    .entry-content {
        color: rgba(232, 238, 246, 0.8);
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .entry-content h2 { color: #fff; margin-top: 40px; margin-bottom: 20px; font-size: 1.8rem; }
    .entry-content h3 { color: #fff; margin-top: 30px; margin-bottom: 15px; font-size: 1.4rem; }
    .entry-content p { margin-bottom: 20px; }
    .entry-content ul, .entry-content ol { margin-bottom: 20px; padding-left: 20px; }
    .entry-content li { margin-bottom: 10px; }
    
    @media (max-width: 768px) {
        .page-content { padding: 30px; }
        .entry-title { font-size: 2rem; }
    }
</style>

<?php get_footer(); ?>
