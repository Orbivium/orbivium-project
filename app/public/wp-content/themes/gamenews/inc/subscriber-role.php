<?php
/**
 * Subscriber Role Restrictions & Enhancements
 * 
 * @package OyunHaber
 */

/**
 * 1. Hide Admin Bar for Subscribers
 * Subscribers (who cannot edit posts) should not see the WP Admin Bar.
 */
function oyunhaber_hide_admin_bar_subscribers() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        show_admin_bar( false );
    }
}
add_action( 'after_setup_theme', 'oyunhaber_hide_admin_bar_subscribers' );

/**
 * 2. Block Admin Access for Subscribers
 * If a subscriber tries to access /wp-admin/, redirect them to the Profile page.
 */
function oyunhaber_block_admin_access_subscribers() {
    // Check if we are in admin, not doing AJAX, and user is logged in
    if ( is_admin() && ! defined( 'DOING_AJAX' ) && is_user_logged_in() ) {
        
        $user = wp_get_current_user();
        
        // If user is Subscriber (or cannot edit posts - usually same thing for subscribers)
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_redirect( home_url( '/profil' ) );
            exit;
        }
    }
}
add_action( 'admin_init', 'oyunhaber_block_admin_access_subscribers' );

/**
 * 3. Login Redirect for Subscribers
 * Redirect subscribers to Homepage/Profile instead of Dashboard after login.
 */
function oyunhaber_subscriber_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'subscriber', $user->roles ) ) {
            // Redirect to Profile Page
            return home_url( '/profil' );
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'oyunhaber_subscriber_login_redirect', 10, 3 );

/**
 * 4. Logout Redirect
 * Redirect to custom login page after logout
 */
function oyunhaber_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
    return home_url( '/giris-yap/' );
}
add_filter( 'logout_redirect', 'oyunhaber_logout_redirect', 10, 3 );
