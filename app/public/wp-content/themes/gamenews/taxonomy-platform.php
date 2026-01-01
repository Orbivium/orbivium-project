<?php
/**
 * The template for displaying Platform Archive pages (PC, PS, Xbox, etc.)
 *
 * @package OyunHaber
 */

get_header();

// Get current platform object
$term = get_queried_object();
$platform_color = isset($term->slug) ? oyunhaber_get_platform_color($term->slug) : '#ff4757';

// Determine the current filter
$filter_label = ''; // Default empty

$custom_query_args = array(
    'tax_query' => array(
        array(
            'taxonomy' => 'platform',
            'field'    => 'slug',
            'terms'    => $term->slug,
        ),
    ),
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'post__not_in' => isset($exclude_slider_ids) ? $exclude_slider_ids : array(),
);

// Check Query Parameters
if ( isset( $_GET['filter_type'] ) && ! empty( $_GET['filter_type'] ) ) {
    $pt = sanitize_text_field( $_GET['filter_type'] );
    
    // Default valid types for unified 'news' post type
    // Map URL param -> Taxonomy Slug
    $type_map = array(
        'news'    => 'haberler',
        'reviews' => 'incelemeler'
    );

    if ( array_key_exists( $pt, $type_map ) ) {
        $custom_query_args['post_type'] = 'news'; // Always query the unified type
        $custom_query_args['tax_query'][] = array(
            'taxonomy' => 'content_type',
            'field'    => 'slug',
            'terms'    => $type_map[$pt],
        );

        if ( $pt == 'news' ) $filter_label = 'Haberler';
        if ( $pt == 'reviews' ) $filter_label = 'İncelemeler';
    }
}

if ( isset( $_GET['filter_cat'] ) && ! empty( $_GET['filter_cat'] ) ) {
    $cat = sanitize_text_field( $_GET['filter_cat'] );
    $custom_query_args['category_name'] = $cat; // WP_Query uses category_name
    
    if ( $cat == 'rehberler' ) $filter_label = 'Rehberler';
    if ( $cat == 'incelemeler' ) $filter_label = 'İncelemeler';
    if ( $cat == 'haberler' ) $filter_label = 'Haberler';
}

if ( isset( $_GET['filter_tag'] ) && ! empty( $_GET['filter_tag'] ) ) {
    $tag = sanitize_text_field( $_GET['filter_tag'] );
    $custom_query_args['tag'] = $tag; // WP_Query uses tag
    
    // Custom Label mappings
    if ( $tag == 'ps-plus' ) $filter_label = 'PS Plus';
    if ( $tag == 'game-pass' ) $filter_label = 'Game Pass';
    if ( $tag == 'ozel-oyunlar' ) $filter_label = 'Özel Oyunlar';
    if ( $tag == 'ucretsiz-oyunlar' ) $filter_label = 'Ücretsiz Oyunlar';
}

// Add query to main query mainly for pagination, but creating a new one is safer for composite filters
$platform_query = new WP_Query( $custom_query_args );

?>

<main id="primary" class="site-main">

    <?php 
    // Logic for Platform Slider (Only on 'Tümü' / No Filter)
    $exclude_slider_ids = array();
    $show_slider = false;

    if ( empty( $filter_label ) ) {
        $slider_args = array(
            'post_type'      => array('news', 'videos', 'esports'),
            'posts_per_page' => 10,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'platform',
                    'field'    => 'slug',
                    'terms'    => $term->slug,
                ),
            ),
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'   => '_oyunhaber_is_platform_featured',
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
        );
        $slider_query = new WP_Query($slider_args);

        if ( $slider_query->have_posts() ) {
            $show_slider = true;
            $exclude_slider_ids = wp_list_pluck( $slider_query->posts, 'ID' );
        }
    }

    if ( $show_slider ) : 
    ?>
        <!-- PLATFORM SLIDER HERO -->
        <section class="home-hero platform-hero-slider" style="position: relative; overflow: hidden; margin-top:20px;">
            <div class="container">
                <div class="hero-slider-wrapper">
                    <div class="hero-slider-track" id="heroTrack">
                        <?php 
                        $posts = $slider_query->posts;
                        $chunked_posts = array_chunk($posts, 5);
                        
                        foreach ($chunked_posts as $index => $chunk) : 
                            $main_post = $chunk[0];
                            $side_posts = array_slice($chunk, 1);
                        ?>
                            <div class="hero-slide-item" style="min-width: 100%;">
                                <div class="hero-grid">
                                    <!-- Main -->
                                    <?php 
                                    $main_thumb = get_the_post_thumbnail_url( $main_post->ID, 'full' );
                                    $main_pt_label = 'İçerik';
                                    $mt = get_the_terms($main_post->ID, 'content_type');
                                    if ($mt && !is_wp_error($mt)) $main_pt_label = $mt[0]->name;
                                    ?>
                                    <div class="hero-main-card" style="background-image: url('<?php echo esc_url($main_thumb); ?>'); border-color: <?php echo esc_attr($platform_color); ?>;">
                                        <div class="hero-overlay"></div>
                                        <div class="hero-content">
                                            <span class="hero-badge" style="background-color: <?php echo esc_attr($platform_color); ?>"><?php echo esc_html($main_pt_label); ?></span>
                                            <h2 class="hero-title"><a href="<?php echo get_permalink($main_post->ID); ?>"><?php echo get_the_title($main_post->ID); ?></a></h2>
                                            <div class="hero-meta">
                                                <span><i class="dashicons dashicons-calendar-alt"></i> <?php echo get_the_date('', $main_post->ID); ?></span>
                                                <span><i class="dashicons dashicons-admin-users"></i> <?php echo get_the_author_meta('display_name', $main_post->post_author); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Side -->
                                    <div class="hero-side-grid">
                                        <?php foreach ($side_posts as $side_post) : 
                                            $side_thumb = get_the_post_thumbnail_url( $side_post->ID, 'medium_large' );
                                            $side_pt_label = 'İçerik';
                                            $st = get_the_terms($side_post->ID, 'content_type');
                                            if ($st && !is_wp_error($st)) $side_pt_label = $st[0]->name;
                                        ?>
                                        <div class="hero-side-card" style="background-image: url('<?php echo esc_url($side_thumb); ?>');">
                                             <div class="hero-overlay-soft"></div>
                                             <div class="side-content">
                                                <span class="hero-badge-small"><?php echo esc_html($side_pt_label); ?></span>
                                                <h3 class="side-title"><a href="<?php echo get_permalink($side_post->ID); ?>"><?php echo get_the_title($side_post->ID); ?></a></h3>
                                             </div>
                                        </div>
                                        <?php endforeach; ?>
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
                <!-- Controls -->
                <?php if (count($chunked_posts) > 1): ?>
                <div class="hero-nav-controls">
                    <button id="heroPrev" class="hero-nav-btn" style="background-color:<?php echo esc_attr($platform_color); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
                    <button id="heroNext" class="hero-nav-btn" style="background-color:<?php echo esc_attr($platform_color); ?>"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Reuse Slider Scripts/Styles from Index by including them here implicitly or relying on global -->
        <!-- We will add the necessary CSS/JS block here to ensure it works independently on archive pages -->
        <style>
            .hero-slider-wrapper { overflow: hidden; width: 100%; position: relative; }
            .hero-slider-track { display: flex; transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1); width: 100%; }
            .hero-nav-controls { position: absolute; bottom: 18px; right: 0; display: flex; gap: 10px; padding-right: 30px; }
            @media(min-width: 1200px) { .hero-nav-controls { right: calc((100% - 1140px) / 2); padding-right: 0; } }
            .hero-nav-btn { border: none; color: white; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: all 0.2s; z-index: 10; }
            .hero-nav-btn:hover { transform: scale(1.1); filter: brightness(1.2); }
            .hero-nav-btn .dashicons { font-size: 20px; }
            
            /* Reuse index grids */
            .hero-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; height: 500px; }
            .hero-main-card { position: relative; border-radius: 20px; background-size: cover; background-position: center; overflow: hidden; display: flex; align-items: flex-end; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); }
            .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, #111 0%, transparent 80%); }
            .hero-content { position: relative; z-index: 2; padding: 40px; width: 100%; }
            .hero-badge { color: #fff; padding: 5px 12px; border-radius: 6px; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; display: inline-block; margin-bottom: 10px; }
            .hero-title { font-size: 2.5rem; font-weight: 800; margin: 0 0 15px 0; line-height: 1.1; text-shadow: 0 4px 10px rgba(0,0,0,0.5); }
            .hero-title a { color: #fff; text-decoration: none; }
            .hero-meta { color: rgba(255,255,255,0.8); font-size: 0.9rem; display: flex; gap: 20px; }
            .hero-side-grid { display: grid; grid-template-rows: repeat(2, 1fr); grid-template-columns: repeat(2, 1fr); gap: 20px; }
            .hero-side-card { position: relative; border-radius: 12px; background-size: cover; background-position: center; overflow: hidden; display: flex; align-items: flex-end; box-shadow: 0 5px 15px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); transition: transform 0.3s ease; }
            .hero-side-card:hover { transform: translateY(-5px); }
            .hero-overlay-soft { position: absolute; inset: 0; background: linear-gradient(to top, #000 0%, transparent 100%); opacity: 0.8; }
            .side-content { position: relative; z-index: 2; padding: 15px; }
            .hero-badge-small { font-size: 0.65rem; background: rgba(0,0,0,0.6); color: #fff; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; border: 1px solid rgba(255,255,255,0.2); }
            .side-title { font-size: 0.95rem; margin: 5px 0 0 0; line-height: 1.3; }
            .side-title a { color: #fff; text-decoration: none; }
            @media (max-width: 768px) {
                .hero-grid { grid-template-columns: 1fr; height: auto; }
                .hero-main-card { height: 300px; }
                .hero-side-grid { height: auto; grid-template-columns: 1fr; }
                .hero-side-card { height: 150px; }
            }
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
                function update() { track.style.transform = `translateX(-${index * 100}%)`; }
                next.addEventListener('click', () => { index = (index + 1) % count; update(); });
                prev.addEventListener('click', () => { index = (index - 1 + count) % count; update(); });
            });
        </script>
    
    <?php else : ?>

    <!-- Standard Static Hero Section (Fallback) -->
    <div class="platform-hero" style="background: radial-gradient(70% 100% at 50% 110%, <?php echo esc_attr($platform_color); ?>45 0%, #0B0F14 100%);">
        <div class="container hero-content">
            
            <?php 
            // Icon & Logo Setup
            $icon_class = oyunhaber_get_platform_icon( $term->slug );
            
            // Check for SVG Logo
            $logo_rel = '/assets/images/platformslogo/' . $term->slug . '.svg';
            $logo_abs = get_template_directory() . $logo_rel;
            $has_logo = file_exists( $logo_abs );
            $logo_url = get_template_directory_uri() . $logo_rel;
            ?>

            <!-- CASE 1: Has Logo -->
            <?php if ( $has_logo ) : ?>
                <div class="platform-title-logo-wrapper" style="text-align: center; width: 100%;">
                    <!-- 1. Logo First -->
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($term->name); ?>" class="platform-hero-logo platform-logo-<?php echo esc_attr($term->slug); ?>" style="max-height: 80px; width: auto; display: block; margin: 0 auto 20px auto;">
                    
                    <!-- 2. Badge (XBOX, PS etc) -->
                    <div class="platform-badge-large" style="background-color: <?php echo esc_attr($platform_color); ?>; display: inline-flex; align-items: center;">
                        <span class="dashicons <?php echo esc_attr($icon_class); ?>" style="margin-right: 6px; font-size: 18px; width: 18px; height: 18px;"></span>
                        <?php echo esc_html( $term->name ); ?>
                    </div>

                    <!-- 3. Subtitle (Filter) -->
                    <?php if ( $filter_label ) : ?>
                        <h2 class="platform-subtitle" style="font-size: 2rem; margin: 10px 0 0 0; color: #fff; text-transform: uppercase;">
                            <?php echo esc_html( $filter_label ); ?>
                        </h2>
                    <?php endif; ?>
                </div>

            <!-- CASE 2: No Logo (Default) -->
            <?php else : ?>
                <!-- 1. BadgeFirst -->
                <div class="platform-badge-large" style="background-color: <?php echo esc_attr($platform_color); ?>">
                    <span class="dashicons <?php echo esc_attr($icon_class); ?>" style="margin-right: 6px; font-size: 18px; width: 18px; height: 18px; vertical-align: text-bottom;"></span>
                    <?php echo esc_html( $term->name ); ?>
                </div>
                <!-- 2. Title -->
                <h1 class="page-title">
                    <?php echo esc_html( $term->name ) . ( $filter_label ? ' ' . esc_html( $filter_label ) : '' ); ?>
                </h1>
            <?php endif; ?>

            <p class="platform-description">
                <?php echo wp_kses_post( term_description() ); ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <div class="container" style="margin-top: 40px;">
        
        <?php if ( $platform_query->have_posts() ) : ?>

            <div class="card-grid">
                <?php
                while ( $platform_query->have_posts() ) :
                    $platform_query->the_post();
                    get_template_part( 'template-parts/content-card' );
                endwhile;
                ?>
            </div>
            
            <?php
            // Pagination
            $big = 999999999; 
            echo '<div class="pagination">';
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $platform_query->max_num_pages,
                'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
                'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
            ) );
            echo '</div>';
            wp_reset_postdata();
            ?>

        <?php else : ?>

            <div class="no-results-panel">
                <div class="no-results-icon">
                    <span class="dashicons dashicons-info"></span>
                </div>
                <h2>Henüz İçerik Yok</h2>
                <p>Şu anda <strong><?php echo esc_html($term->name); ?> - <?php echo esc_html($filter_label); ?></strong> kategorisinde içerik bulunmuyor.</p>
                <?php if ( current_user_can( 'publish_posts' ) ) : ?>
                    <a href="<?php echo admin_url('post-new.php'); ?>" class="btn-create-content" style="background-color: <?php echo esc_attr($platform_color); ?>">
                        İlk İçeriği Ekle
                    </a>
                <?php endif; ?>
            </div>

        <?php endif; ?>
        
    </div>
</main>

<style>
/* Local Styles for Platform Template */
.platform-logo-genel {
    max-height: 110px !important;
}

.platform-hero {
    padding: 40px 0;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center; /* Center horizontally */
    position: relative;
    background-color: var(--bg-secondary);
    border-bottom: 4px solid <?php echo esc_attr($platform_color); ?>;
    text-align: center; /* Force text center */
}

.platform-hero .hero-content {
    width: 100%;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 2;
    position: relative;
}

.platform-badge-large {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 4px;
    color: #fff;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 0.9rem;
    margin-bottom: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.page-title {
    font-size: 3rem;
    margin: 0 0 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.platform-description {
    font-size: 1.1rem;
    color: #e0e0e0;
    max-width: 600px;
}

.no-results-panel {
    text-align: center;
    padding: 60px;
    background: var(--bg-secondary);
    border-radius: 12px;
    border: 1px dashed var(--border-color);
    margin: 20px 0;
}

.no-results-icon span {
    font-size: 50px;
    width: 50px;
    height: 50px;
    color: var(--text-secondary);
    margin-bottom: 20px;
}

.btn-create-content {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 25px;
    color: #fff;
    border-radius: 30px;
    font-weight: bold;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.pagination {
    margin-top: 40px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.pagination .page-numbers {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.pagination .page-numbers.current,
.pagination .page-numbers:hover {
    background: <?php echo esc_attr($platform_color); ?>;
    color: #fff;
    border-color: <?php echo esc_attr($platform_color); ?>;
}
</style>

<?php
get_footer();
