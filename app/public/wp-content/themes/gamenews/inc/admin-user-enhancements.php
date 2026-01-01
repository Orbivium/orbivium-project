<?php
/**
 * Admin User Table Enhancements
 * 
 * @package OyunHaber
 */

/**
 * 1. Add Custom Columns to User Table
 */
function oyunhaber_add_user_columns( $columns ) {
    $columns['registration_date'] = 'Kayıt Tarihi';
    $columns['user_ip']           = 'Kayıt IP';
    $columns['phone_number']      = 'Telefon';
    return $columns;
}
add_filter( 'manage_users_columns', 'oyunhaber_add_user_columns' );

/**
 * 2. Populate Custom Columns
 */
function oyunhaber_show_user_columns_content( $value, $column_name, $user_id ) {
    switch ( $column_name ) {
        case 'registration_date':
            $user_info = get_userdata( $user_id );
            // Format example: 01 Jan 2026 14:30
            return date_i18n( 'd M Y H:i', strtotime( $user_info->user_registered ) );
        
        case 'user_ip':
            // Try getting our custom meta first
            $ip = get_user_meta( $user_id, 'signup_ip', true );
            if ( ! $ip ) {
                // If existing user doesn't have signup_ip meta, we can't show it accurately.
                return '<span style="color:#aaa;">-</span>';
            }
            return $ip;

        case 'phone_number':
            $phone = get_user_meta( $user_id, 'phone_number', true );
            if ( empty( $phone ) ) {
                $phone = get_user_meta( $user_id, 'mobile_phone', true );
            }
            return $phone ? $phone : '<span style="color:#aaa;">-</span>';

        default:
            return $value;
    }
}
add_filter( 'manage_users_custom_column', 'oyunhaber_show_user_columns_content', 10, 3 );

/**
 * 3. Make Registration Date Sortable
 */
function oyunhaber_user_sortable_columns( $columns ) {
    $columns['registration_date'] = 'user_registered';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'oyunhaber_user_sortable_columns' );

/**
 * 4. Add "Export CSV" to Bulk Actions
 */
function oyunhaber_register_export_bulk_action( $bulk_actions ) {
    $bulk_actions['export_csv'] = 'CSV Olarak İndir';
    return $bulk_actions;
}
add_filter( 'bulk_actions-users', 'oyunhaber_register_export_bulk_action' );

/**
 * 5. Handle CSV Export Logic
 */
function oyunhaber_handle_export_bulk_action( $redirect_to, $doaction, $user_ids ) {
    if ( $doaction !== 'export_csv' ) {
        return $redirect_to;
    }

    // Headers for CSV download
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=users-export-' . date('Y-m-d') . '.csv' );

    // Open output stream
    $output = fopen( 'php://output', 'w' );

    // Add Byte Order Mark (BOM) for Excel UTF-8 compatibility
    fputs( $output, "\xEF\xBB\xBF" );

    // CSV Headers
    fputcsv( $output, array( 'ID', 'Kullanıcı Adı', 'E-Posta', 'Ad', 'Soyad', 'Rol', 'Telefon', 'Kayıt Tarihi', 'Kayıt IP' ) );

    // Fetch and Process Users
    foreach ( $user_ids as $user_id ) {
        $user = get_userdata( $user_id );
        
        // Get Meta Data
        $phone_new = get_user_meta( $user_id, 'phone_number', true );
        $phone_old = get_user_meta( $user_id, 'mobile_phone', true );
        $phone     = $phone_new ? $phone_new : $phone_old;

        $ip = get_user_meta( $user_id, 'signup_ip', true );
        
        // Role (First one)
        $role = ! empty( $user->roles ) ? $user->roles[0] : '';
        
        // Write Row
        fputcsv( $output, array(
            $user->ID,
            $user->user_login,
            $user->user_email,
            $user->first_name,
            $user->last_name,
            $role,
            $phone,
            $user->user_registered,
            $ip
        ));
    }

    fclose( $output );
    exit;
}
add_filter( 'handle_bulk_actions-users', 'oyunhaber_handle_export_bulk_action', 10, 3 );
