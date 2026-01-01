<?php
/**
 * Simple Local Avatar Implementation
 * 
 * @package OyunHaber
 */

/**
 * Filter get_avatar_url to use local avatar if available.
 */
function oyunhaber_get_avatar_url( $url, $id_or_email, $args ) {
    $user_id = 0;

    if ( is_numeric( $id_or_email ) ) {
        $user_id = $id_or_email;
    } elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
        $user = get_user_by( 'email', $id_or_email );
        if ( $user ) {
            $user_id = $user->ID;
        }
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = $id_or_email->user_id;
    } elseif ( $id_or_email instanceof WP_User ) {
        $user_id = $id_or_email->ID;
    }

    if ( $user_id ) {
        $local_avatar_id = get_user_meta( $user_id, 'simple_local_avatar', true );
        if ( $local_avatar_id ) {
            $img_url = wp_get_attachment_image_url( $local_avatar_id, 'thumbnail' ); // use thumbnail size or full
            if ( $img_url ) {
                return $img_url;
            }
        }
    }

    return $url;
}
add_filter( 'get_avatar_url', 'oyunhaber_get_avatar_url', 10, 3 );

// Also filter main get_avatar to catch <img tags if used elsewhere
function oyunhaber_get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user_id = 0;

    if ( is_numeric( $id_or_email ) ) {
        $user_id = $id_or_email;
    } elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
        $user = get_user_by( 'email', $id_or_email );
        if ( $user ) {
            $user_id = $user->ID;
        }
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = $id_or_email->user_id;
    } elseif ( $id_or_email instanceof WP_User ) {
        $user_id = $id_or_email->ID;
    }

    if ( $user_id ) {
        $local_avatar_id = get_user_meta( $user_id, 'simple_local_avatar', true );
        if ( $local_avatar_id ) {
            $img_url = wp_get_attachment_image_src( $local_avatar_id, array( $size, $size ) );
            if ( $img_url ) {
                return "<img alt='{$alt}' src='{$img_url[0]}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
            }
        }
    }

    return $avatar;
}
add_filter( 'get_avatar', 'oyunhaber_get_avatar', 10, 5 );
