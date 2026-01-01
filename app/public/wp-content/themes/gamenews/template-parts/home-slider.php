<?php
/**
 * Template part for displaying the Home Slider
 *
 * @package OyunHaber
 */

$slider_query = new WP_Query( array(
    'post_type'      => 'slider',
    'posts_per_page' => 5,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC'
) );

if ( $slider_query->have_posts() ) :
?>

<div class="home-main-slider-wrapper">
    <div class="main-slider-container">
        <?php 
        $i = 0;
        while ( $slider_query->have_posts() ) : $slider_query->the_post(); 
            $slide_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            $slide_url   = get_post_meta( get_the_ID(), 'slide_url', true );
            $active_class = ($i === 0) ? 'active' : '';
        ?>
            <div class="slide-item <?php echo esc_attr($active_class); ?>" style="background-image: url('<?php echo esc_url($slide_image); ?>');">
                <div class="slide-overlay"></div>
                <div class="slide-content container">
                    <div class="slide-text-box">
                        <h2 class="slide-title"><?php the_title(); ?></h2>
                        <div class="slide-desc"><?php the_content(); ?></div>
                        <?php if ( ! empty( $slide_url ) ) : ?>
                            <a href="<?php echo esc_url( $slide_url ); ?>" class="btn-slide-action">İncele <span class="dashicons dashicons-arrow-right-alt"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
        $i++;
        endwhile; 
        ?>

        <!-- Slider Navigation -->
        <div class="slider-nav">
            <button class="nav-btn prev-btn" onclick="moveSlide(-1)"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
            <div class="slider-dots">
                <?php for($j=0; $j<$i; $j++): ?>
                    <span class="dot <?php echo ($j===0) ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $j; ?>)"></span>
                <?php endfor; ?>
            </div>
            <button class="nav-btn next-btn" onclick="moveSlide(1)"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
        </div>
    </div>
</div>

<style>
/* Slider CSS */
.home-main-slider-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 40px;
    background: #000;
}

.main-slider-container {
    position: relative;
    width: 100%;
    height: 500px; /* Adjust height as needed */
    overflow: hidden;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease-in-out, visibility 0.6s;
    display: flex;
    align-items: center;
}

.slide-item.active {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}

.slide-overlay {
    position: absolute;
    inset: 0;
    /* Cleaner, more modern gradient: Dark bottom-left, lighter top-right */
    background: linear-gradient(45deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 100%);
    z-index: 1;
}

.slide-content {
    position: relative;
    z-index: 3;
    width: 100%;
    padding: 0 40px; /* More side padding */
    height: 100%;
    display: flex;
    align-items: center; /* Center vertically */
}

.slide-text-box {
    max-width: 700px;
    padding: 40px;
    border-radius: 20px;
    /* Glassmorphism Backdrop */
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.slide-item.active .slide-text-box {
    opacity: 1;
    transform: translateY(0);
}

.slide-title {
    font-size: 3.5rem; /* Larger */
    font-weight: 800; /* Extra Bold */
    color: #fff;
    margin: 0 0 20px 0;
    line-height: 1.1;
    letter-spacing: -1px;
    text-shadow: 0 4px 10px rgba(0,0,0,0.5);
    text-transform: uppercase; /* Powerful Look */
}

.slide-desc {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin-bottom: 30px;
    line-height: 1.6;
    font-weight: 400;
}

.slide-desc p { margin: 0; }

.btn-slide-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--accent-color, #E11D48);
    color: #fff;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4);
}

.btn-slide-action:hover {
    transform: translateY(-2px);
    background: #fff;
    color: var(--accent-color, #E11D48);
}

/* Navigation Styles */
.slider-nav {
    position: absolute;
    bottom: 40px;
    left: 0;
    width: 100%;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    pointer-events: none; /* Let clicks pass through empty space */
}

.nav-btn {
    pointer-events: auto;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    width: 50px; /* Larger hit area */
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.nav-btn:hover {
    background: #fff;
    color: #000;
    transform: scale(1.1);
    border-color: #fff;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
}

.nav-btn .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}

.slider-dots {
    display: flex;
    gap: 8px;
}

.dot {
    width: 10px;
    height: 10px;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
}

.dot.active {
    background: #fff;
    transform: scale(1.2);
}

@media (max-width: 768px) {
    .main-slider-container { height: 400px; }
    .slide-title { font-size: 2rem; }
    .slide-desc { font-size: 1rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
}
</style>

<script>
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide-item');
    const dots = document.querySelectorAll('.dot');
    let autoPlayInterval;

    function showSlide(index) {
        if (index >= slides.length) slideIndex = 0;
        if (index < 0) slideIndex = slides.length - 1;

        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        dots.forEach(dot => {
            dot.classList.remove('active');
        });

        if(slides[slideIndex]) {
            slides[slideIndex].classList.add('active');
            if(dots[slideIndex]) dots[slideIndex].classList.add('active');
        }
    }

    function moveSlide(n) {
        slideIndex += n;
        showSlide(slideIndex);
        resetTimer();
    }

    function currentSlide(n) {
        slideIndex = n;
        showSlide(slideIndex);
        resetTimer();
    }

    function resetTimer() {
        clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(() => {
            slideIndex++;
            showSlide(slideIndex);
        }, 5000);
    }

    // Init
    if(slides.length > 0) {
        autoPlayInterval = setInterval(() => {
            slideIndex++;
            showSlide(slideIndex);
        }, 5000);
    }
</script>

<?php 
endif; wp_reset_postdata(); 
?>
