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
);

// Check Query Parameters
if ( isset( $_GET['filter_type'] ) && ! empty( $_GET['filter_type'] ) ) {
    $pt = sanitize_text_field( $_GET['filter_type'] );
    // Ensure we only query safe post types
    if ( in_array( $pt, array('news', 'reviews') ) ) {
        $custom_query_args['post_type'] = $pt;
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

    <!-- Platform Hero Section -->
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
