<?php
/**
 * Template Name: Hakkımızda
 *
 * @package OyunHaber
 */

get_header(); 
?>

<div class="about-page-wrapper">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container hero-content">
            <h1 class="hero-title"><?php the_title(); ?> <span class="highlight">Nabzı</span></h1>
            <p class="hero-subtitle">
                <?php echo has_excerpt() ? get_the_excerpt() : 'Biz sadece haber yapmıyoruz; oyun kültürünü yaşıyoruz.'; ?>
            </p>
        </div>
        <div class="hero-bg-glow"></div>
    </section>

    <!-- Main Content -->
    <div class="container about-container">
        <div class="about-grid">
            
            <!-- Left: Text Content -->
            <div class="about-text-col">
                <div class="glass-card about-card">
                    <?php 
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                    else : 
                    ?>
                        <h2>Biz Kimiz?</h2>
                        <p>Orbi, 2025 yılında tutkulu bir grup oyuncu tarafından kuruldu...</p>
                    <?php endif; ?>
                </div>

                <div class="glass-card stats-card">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Yazar & Editör</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">İnceleme</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10K</span>
                        <span class="stat-label">Aylık Okuyucu</span>
                    </div>
                </div>
            </div>

            <!-- Right: Visual/Team -->
            <div class="about-visual-col">
                <div class="visual-wrapper">
                    <div class="image-card">
                        <!-- Placeholder for team or office image -->
                        <div class="placeholder-icon"><span class="dashicons dashicons-groups"></span></div>
                    </div>
                    <div class="join-us-box">
                        <h4>Aramıza Katıl</h4>
                        <p>Sen de oyunlar hakkında yazmak istiyor musun? Moderatör başvurularımız açık!</p>
                        <a href="<?php echo home_url('/kayit-ol/'); ?>" class="btn-join">Başvuru Yap</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Variables */
    :root {
        --a-bg: #0B0F14;
        --a-accent: #E11D48;
        --a-text: #E8EEF6;
        --a-text-muted: rgba(232, 238, 246, 0.7);
        --a-card-bg: rgba(255, 255, 255, 0.03);
        --a-border: rgba(255, 255, 255, 0.1);
    }

    .about-page-wrapper {
        background-color: var(--a-bg);
        color: var(--a-text);
        font-family: 'Segoe UI', system-ui, sans-serif;
        padding-bottom: 80px;
    }

    /* Hero */
    .about-hero {
        position: relative;
        padding: 100px 0;
        text-align: center;
        overflow: hidden;
        border-bottom: 1px solid var(--a-border);
        margin-bottom: 60px;
    }

    .hero-content { position: relative; z-index: 2; }

    .hero-title {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 20px;
        line-height: 1.1;
    }
    .hero-title .highlight {
        color: transparent;
        background: linear-gradient(to right, var(--a-accent), #ff6b6b);
        -webkit-background-clip: text;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: var(--a-text-muted);
        max-width: 700px;
        margin: 0 auto;
    }

    .hero-bg-glow {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 600px; height: 300px;
        background: var(--a-accent);
        filter: blur(150px);
        opacity: 0.15;
        z-index: 1;
        pointer-events: none;
    }

    /* Grid */
    .about-container { max-width: 1100px; }
    .about-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 40px;
    }

    /* Text Col */
    .glass-card {
        background: var(--a-card-bg);
        border: 1px solid var(--a-border);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
    }

    .about-card h2 { font-size: 2rem; margin-bottom: 20px; color: #fff; font-weight: 700; }
    .about-card h3 { font-size: 1.5rem; margin: 30px 0 15px; color: #fff; }
    .about-card p { line-height: 1.7; color: var(--a-text-muted); margin-bottom: 20px; font-size: 1.05rem; }

    .mission-list { list-style: none; padding: 0; }
    .mission-list li {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 15px;
        font-size: 1.05rem;
        color: #fff;
    }
    .mission-list li .dashicons {
        color: var(--a-accent);
        background: rgba(225, 29, 72, 0.1);
        border-radius: 50%;
        padding: 5px;
        width: 24px; height: 24px;
        display: flex; align-items: center; justify-content: center;
    }

    /* Stats */
    .stats-card {
        display: flex;
        justify-content: space-around;
        padding: 30px;
        text-align: center;
    }
    .stat-number { display: block; font-size: 2.5rem; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 5px; }
    .stat-label { font-size: 0.9rem; color: var(--a-text-muted); text-transform: uppercase; letter-spacing: 1px; }

    /* Visual Col */
    .image-card {
        height: 300px;
        background: #1e1e24;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--a-border);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .image-card::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top right, rgba(0,0,0,0.8), transparent);
    }
    .placeholder-icon .dashicons { font-size: 64px; color: rgba(255,255,255,0.1); width: 64px; height: 64px; }

    .join-us-box {
        background: linear-gradient(135deg, var(--a-accent), #ef4444);
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        color: #fff;
        box-shadow: 0 10px 30px rgba(225, 29, 72, 0.3);
    }
    .join-us-box h4 { font-size: 1.5rem; margin-bottom: 10px; font-weight: 800; }
    .join-us-box p { margin-bottom: 20px; font-size: 0.95rem; opacity: 0.9; }

    .btn-join {
        display: inline-block;
        background: #fff;
        color: var(--a-accent);
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s;
    }
    .btn-join:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

    @media (max-width: 900px) {
        .hero-title { font-size: 2.5rem; }
        .about-grid { grid-template-columns: 1fr; }
        .stats-card { flex-direction: column; gap: 30px; }
    }
</style>

<?php get_footer(); ?>
