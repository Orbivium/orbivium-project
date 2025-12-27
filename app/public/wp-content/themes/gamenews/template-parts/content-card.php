<?php
/**
 * Template part for displaying posts in a card layout
 *
 * @package OyunHaber
 */

$platforms = get_the_terms( get_the_ID(), 'platform' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="card-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
            <?php if ( $platforms && ! is_wp_error( $platforms ) ) : ?>
                <div class="card-platforms">
                    <?php foreach ( $platforms as $platform ) : ?>
                        <span class="platform-badge platform-<?php echo esc_attr( $platform->slug ); ?>"><?php echo esc_html( $platform->name ); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="card-content">
        <header class="entry-header">
            <?php
            if ( 'post' === get_post_type() ) {
                echo '<div class="entry-meta">';
                echo '<span class="posted-on">' . get_the_date() . '</span>';
                echo '</div>';
            }
            
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            ?>
        </header>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
    </div>
</article>
