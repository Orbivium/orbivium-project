<?php
/**
 * The main template file
 *
 * @package OyunHaber
 */

get_header();

// 1. Get Featured Posts
$featured_query = new WP_Query(array(
    'post_type'      => array('news', 'reviews'),
    'meta_key'       => '_oyunhaber_is_featured',
    'meta_value'     => '1',
    'posts_per_page' => 5, // Top 5 featured
    'ignore_sticky_posts' => 1
));

// 2. Get Latest Posts (excluding featured to avoid duplicates)
$exclude_ids = wp_list_pluck( $featured_query->posts, 'ID' );
$latest_query = new WP_Query(array(
    'post_type'      => array('news', 'reviews'),
    'post__not_in'   => $exclude_ids,
    'posts_per_page' => 9,
    'orderby'        => 'date',
    'order'          => 'DESC'
));
?>

<main id="primary" class="site-main homepage-wrapper">
    
    <!-- HOME SLIDER -->
    <?php get_template_part( 'template-parts/home-slider' ); ?>

    <?php if ( $featured_query->have_posts() ) : ?>
    <!-- HERO SECTION -->
    <section class="home-hero">
        <div class="container">
            <div class="hero-grid">
                
                <!-- Main Featured (First Post) -->
                <?php 
                $featured_query->the_post(); 
                $main_thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                ?>
                <div class="hero-main-card" style="background-image: url('<?php echo esc_url($main_thumb); ?>');">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="hero-badge"><?php echo ucfirst(get_post_type()); ?></span>
                        <h2 class="hero-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="hero-meta">
                            <span><i class="dashicons dashicons-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                            <span><i class="dashicons dashicons-admin-users"></i> <?php the_author(); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Side Featured (Next 4 Posts) -->
                <div class="hero-side-grid">
                    <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); 
                        $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
                    ?>
                    <div class="hero-side-card" style="background-image: url('<?php echo esc_url($thumb); ?>');">
                         <div class="hero-overlay-soft"></div>
                         <div class="side-content">
                            <span class="hero-badge-small"><?php echo ucfirst(get_post_type()); ?></span>
                            <h3 class="side-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                         </div>
                    </div>
                    <?php endwhile; ?>
                </div>

            </div>
        </div>
    </section>
    <?php endif; wp_reset_postdata(); ?>

    <!-- CONTENT SECTION -->
    <div class="container home-content-layout">
        <div class="main-column">
            
            <div class="section-header">
                <h2 class="section-heading">Son İçerikler</h2>
                <div class="section-line"></div>
            </div>

            <?php if ( $latest_query->have_posts() ) : ?>
                <div class="latest-grid">
                <?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
                    
                    <div class="latest-card">
                        <div class="card-thumb">
                            <a href="<?php the_permalink(); ?>">
                                <?php if(has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php else: ?>
                                    <div class="empty-thumb"></div>
                                <?php endif; ?>
                            </a>
                            <span class="card-cat-badge"><?php echo ucfirst(get_post_type()); ?></span>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></div>
                            <div class="card-footer-meta">
                                <span class="meta-author"><?php echo get_avatar(get_the_author_meta('ID'), 24); ?> <?php the_author(); ?></span>
                                <span class="meta-date"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' önce'; ?></span>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p>Henüz içerik bulunmuyor.</p>
            <?php endif; wp_reset_postdata(); ?>

        </div>
    </div>

</main>

<style>
/* New Homepage Aesthetics */
.home-hero {
    padding: 30px 0 50px;
    margin-bottom: 30px;
}

.hero-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    height: 500px;
}

/* Main Hero Card */
.hero-main-card {
    position: relative;
    border-radius: 20px;
    background-size: cover;
    background-position: center;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #111 0%, transparent 80%);
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 40px;
    width: 100%;
}

.hero-badge {
    background: var(--accent-color);
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.8rem;
    display: inline-block;
    margin-bottom: 10px;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 15px 0;
    line-height: 1.1;
    text-shadow: 0 4px 10px rgba(0,0,0,0.5);
}
.hero-title a { color: #fff; text-decoration: none; }
.hero-title a:hover { color: #ccc; }

.hero-meta {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    display: flex;
    gap: 20px;
}

/* Side Grid */
.hero-side-grid {
    display: grid;
    grid-template-rows: repeat(2, 1fr); /* Only show 2 items for better height match or need 2 cols */
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* If we got 4 items loop, it fills 2x2. */
.hero-side-card {
    position: relative;
    border-radius: 12px;
    background-size: cover;
    background-position: center;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border: 1px solid rgba(255,255,255,0.05);
    transition: transform 0.3s ease;
}

.hero-side-card:hover { transform: translateY(-5px); border-color: var(--accent-color); }

.hero-overlay-soft {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #000 0%, transparent 100%);
    opacity: 0.8;
}

.side-content {
    position: relative;
    z-index: 2;
    padding: 15px;
}

.hero-badge-small {
    font-size: 0.65rem;
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
    border: 1px solid rgba(255,255,255,0.2);
}

.side-title {
    font-size: 0.95rem;
    margin: 5px 0 0 0;
    line-height: 1.3;
}
.side-title a { color: #fff; text-decoration: none; }

/* Latest Content Section */
.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}
.section-heading {
    font-size: 1.8rem;
    margin: 0;
    white-space: nowrap;
    margin-right: 20px;
}
.section-line {
    width: 100%;
    height: 1px;
    background: rgba(255,255,255,0.1);
}

.latest-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.latest-card {
    background: #1e1e1e;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.latest-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.3); border-color: var(--accent-color); }

.card-thumb {
    height: 200px;
    position: relative;
    overflow: hidden;
}
.card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.latest-card:hover .card-thumb img { transform: scale(1.1); }

.card-cat-badge {
    position: absolute;
    top: 10px; right: 10px;
    background: rgba(0,0,0,0.7);
    color: #fff;
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 4px;
    backdrop-filter: blur(4px);
    text-transform: uppercase;
}

.card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.card-title { font-size: 1.2rem; margin: 0 0 10px 0; line-height: 1.3; }
.card-title a { color: #fff; }
.card-title a:hover { color: var(--accent-color); }
.card-excerpt { color: #aaa; font-size: 0.9rem; margin-bottom: 20px; flex: 1; }

.card-footer-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid rgba(255,255,255,0.05);
    padding-top: 15px;
    font-size: 0.8rem;
    color: #777;
}

.meta-author { display: flex; align-items: center; gap: 8px; }
.meta-author img { border-radius: 50%; }

/* Mobile */
@media (max-width: 768px) {
    .hero-grid { grid-template-columns: 1fr; height: auto; }
    .hero-main-card { height: 300px; }
    .hero-side-grid { height: auto; grid-template-columns: 1fr; }
    .hero-side-card { height: 150px; }
    .hero-title { font-size: 1.8rem; }
}
</style>

<?php
get_footer();
