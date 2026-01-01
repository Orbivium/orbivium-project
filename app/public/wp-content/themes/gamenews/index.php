<?php
/**
 * The main template file
 *
 * @package OyunHaber
 */

get_header();

// 1. Get Featured Posts
// 1. Get Featured Posts
$featured_query = new WP_Query(array(
    'post_type'      => array('news', 'videos', 'esports'),
    'posts_per_page' => 20, // Allow up to 20 featured items for slider
    'ignore_sticky_posts' => 1,
    'meta_query'     => array(
        'relation' => 'AND',
        array(
            'key'   => '_oyunhaber_is_featured',
            'value' => '1'
        ),
        array(
            'relation' => 'OR',
            array(
                'key'     => '_oyunhaber_featured_expiry',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key'     => '_oyunhaber_featured_expiry',
                'value'   => '',
                'compare' => '='
            ),
            array(
                'key'     => '_oyunhaber_featured_expiry',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE'
            )
        )
    )
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
    <!-- HERO SECTION (Slider Version) -->
    <section class="home-hero" style="position: relative; overflow: hidden;">
        <div class="container">
            
            <div class="hero-slider-wrapper">
                <div class="hero-slider-track" id="heroTrack">
                    
                    <?php 
                    $posts = $featured_query->posts;
                    $chunked_posts = array_chunk($posts, 5); // Group by 5
                    
                    foreach ($chunked_posts as $index => $chunk) : 
                        // First post is Main, others are Side
                        $main_post = $chunk[0];
                        $side_posts = array_slice($chunk, 1);
                    ?>
                        <div class="hero-slide-item" style="min-width: 100%;">
                            <div class="hero-grid">
                                
                                <!-- Main Featured -->
                                <?php 
                                $main_thumb = get_the_post_thumbnail_url( $main_post->ID, 'full' );
                                $main_pt = get_post_type($main_post->ID);
                                $main_label = ($main_pt == 'news') ? 'Haber' : (($main_pt == 'reviews') ? 'İnceleme' : 'İçerik');
                                
                                // Check Content Type Taxonomy for precise label
                                if ($main_pt == 'news') {
                                    $terms = get_the_terms($main_post->ID, 'content_type');
                                    if ($terms && !is_wp_error($terms)) $main_label = $terms[0]->name;
                                }
                                ?>
                                <div class="hero-main-card" style="background-image: url('<?php echo esc_url($main_thumb); ?>');">
                                    <div class="hero-overlay"></div>
                                    <div class="hero-content">
                                        <span class="hero-badge"><?php echo esc_html($main_label); ?></span>
                                        <h2 class="hero-title"><a href="<?php echo get_permalink($main_post->ID); ?>"><?php echo get_the_title($main_post->ID); ?></a></h2>
                                        <div class="hero-meta">
                                            <span><i class="dashicons dashicons-calendar-alt"></i> <?php echo get_the_date('', $main_post->ID); ?></span>
                                            <span><i class="dashicons dashicons-admin-users"></i> <?php echo get_the_author_meta('display_name', $main_post->post_author); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Side Featured -->
                                <div class="hero-side-grid">
                                    <?php foreach ($side_posts as $side_post) : 
                                        $side_thumb = get_the_post_thumbnail_url( $side_post->ID, 'medium_large' );
                                        $side_pt = get_post_type($side_post->ID);
                                        $side_label = ($side_pt == 'news') ? 'Haber' : (($side_pt == 'reviews') ? 'İnceleme' : 'İçerik');
                                        if ($side_pt == 'news') {
                                            $t = get_the_terms($side_post->ID, 'content_type');
                                            if ($t && !is_wp_error($t)) $side_label = $t[0]->name;
                                        }
                                    ?>
                                    <div class="hero-side-card" style="background-image: url('<?php echo esc_url($side_thumb); ?>');">
                                         <div class="hero-overlay-soft"></div>
                                         <div class="side-content">
                                            <span class="hero-badge-small"><?php echo esc_html($side_label); ?></span>
                                            <h3 class="side-title"><a href="<?php echo get_permalink($side_post->ID); ?>"><?php echo get_the_title($side_post->ID); ?></a></h3>
                                         </div>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <!-- Empty Fillers to maintain grid if < 4 side posts -->
                                    <?php 
                                    $missing = 4 - count($side_posts); 
                                    for($k=0; $k<$missing; $k++): ?>
                                        <div class="hero-side-card empty" style="background: #1a1a1a; display:flex; align-items:center; justify-content:center;">
                                            <span class="dashicons dashicons-format-image" style="color:#333; font-size:30px;"></span>
                                        </div>
                                    <?php endfor; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                </div>
            </div>

            <!-- Controls (Only if > 1 slide) -->
            <?php if (count($chunked_posts) > 1): ?>
            <div class="hero-nav-controls">
                <button id="heroPrev" class="hero-nav-btn"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
                <button id="heroNext" class="hero-nav-btn"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
            </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- Hero Slider Script & Styles -->
    <style>
        .hero-slider-wrapper { overflow: hidden; width: 100%; position: relative; }
        .hero-slider-track { display: flex; transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1); width: 100%; }
        .hero-nav-controls {
            position: absolute;
            bottom: 18px; /* Moved down further */
            right: 0; /* Center or right? User screenshot shows blank right area, let's put it there */
            display: flex;
            gap: 10px;
            padding-right: 30px; /* Container padding adjustment */
        }
        @media(min-width: 1200px) {
             .hero-nav-controls { right: calc((100% - 1140px) / 2); padding-right: 0; }
        }
        
        .hero-nav-btn {
            background: var(--accent-color);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex; 
            align-items: center; 
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: all 0.2s;
            z-index: 10;
        }
        .hero-nav-btn:hover { transform: scale(1.1); background: #fff; color: var(--accent-color); }
        .hero-nav-btn .dashicons { font-size: 20px; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('heroTrack');
        const prev = document.getElementById('heroPrev');
        const next = document.getElementById('heroNext');
        if(!track || !prev || !next) return;

        let index = 0;
        const slides = document.querySelectorAll('.hero-slide-item');
        const count = slides.length;

        function update() {
            track.style.transform = `translateX(-${index * 100}%)`;
        }

        next.addEventListener('click', () => {
            index = (index + 1) % count;
            update();
        });

        prev.addEventListener('click', () => {
            index = (index - 1 + count) % count;
            update();
        });
    });
    </script>

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
                            <?php 
                            $l_pt = get_post_type();
                            $l_badge = 'İçerik';
                            if($l_pt == 'news') {
                                $l_badge = 'Haber';
                                $cts = get_the_terms(get_the_ID(), 'content_type');
                                if($cts && !is_wp_error($cts)) $l_badge = $cts[0]->name;
                            } elseif($l_pt == 'reviews') {
                                $l_badge = 'İnceleme';
                            } elseif($l_pt == 'videos') {
                                $l_badge = 'Video';
                            }
                            ?>
                            <span class="card-cat-badge"><?php echo esc_html($l_badge); ?></span>
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
