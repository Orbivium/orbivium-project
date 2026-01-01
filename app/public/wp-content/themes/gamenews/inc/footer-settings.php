<?php
/**
 * Footer & General Settings Page
 *
 * @package OyunHaber
 */

function oyunhaber_footer_settings_init() {
    
    // --- About Us Settings ---
    register_setting('oyunhaber_footer_group', 'oyunhaber_about_text');
    
    add_settings_section(
        'oyunhaber_about_section',
        'Hakkımızda Ayarları (Footer)',
        null,
        'oyunhaber-footer-settings'
    );
    
    add_settings_field(
        'oyunhaber_about_text',
        'Hakkımızda Metni',
        'oyunhaber_about_text_render',
        'oyunhaber-footer-settings',
        'oyunhaber_about_section'
    );

    // --- Legal Links Settings ---
    add_settings_section(
        'oyunhaber_legal_section',
        'Footer Linkleri',
        'oyunhaber_legal_section_callback',
        'oyunhaber-footer-settings'
    );

    for ($i = 1; $i <= 5; $i++) {
        register_setting('oyunhaber_footer_group', "oyunhaber_legal_link_text_$i");
        register_setting('oyunhaber_footer_group', "oyunhaber_legal_link_url_$i");
        
        add_settings_field(
            "oyunhaber_legal_link_$i",
            "Link $i",
            'oyunhaber_legal_link_render',
            'oyunhaber-footer-settings',
            'oyunhaber_legal_section',
            array('index' => $i)
        );
    }

    // --- Social Media Settings ---
    add_settings_section(
        'oyunhaber_social_section',
        'Sosyal Medya Linkleri',
        null,
        'oyunhaber-footer-settings'
    );

    $socials = ['twitter', 'instagram', 'youtube', 'twitch', 'facebook'];
    foreach ($socials as $social) {
        register_setting('oyunhaber_footer_group', "oyunhaber_social_$social");
        add_settings_field(
            "oyunhaber_social_$social",
            ucfirst($social) . ' URL',
            'oyunhaber_social_render',
            'oyunhaber-footer-settings',
            'oyunhaber_social_section',
            array('key' => $social)
        );
    }

    // --- Contact Settings ---
    add_settings_section(
        'oyunhaber_contact_section',
        'İletişim Bilgileri',
        null,
        'oyunhaber-footer-settings'
    );

    register_setting('oyunhaber_footer_group', 'oyunhaber_contact_email');
    add_settings_field(
        'oyunhaber_contact_email',
        'İletişim E-Posta',
        'oyunhaber_contact_email_render',
        'oyunhaber-footer-settings',
        'oyunhaber_contact_section'
    );

    register_setting('oyunhaber_footer_group', 'oyunhaber_contact_address');
    add_settings_field(
        'oyunhaber_contact_address',
        'Adres / Konum',
        'oyunhaber_contact_address_render',
        'oyunhaber-footer-settings',
        'oyunhaber_contact_section'
    );
}
add_action('admin_init', 'oyunhaber_footer_settings_init');

function oyunhaber_footer_settings_menu() {
    add_menu_page(
        'Site Ayarları',
        'Site Ayarları',
        'manage_options',
        'oyunhaber-footer-settings',
        'oyunhaber_footer_settings_page',
        'dashicons-admin-generic',
        60
    );
}
add_action('admin_menu', 'oyunhaber_footer_settings_menu');

// --- Render Functions ---

function oyunhaber_about_text_render() {
    $value = get_option('oyunhaber_about_text');
    echo '<textarea name="oyunhaber_about_text" rows="5" cols="50" class="large-text code">' . esc_textarea($value) . '</textarea>';
}

function oyunhaber_legal_section_callback() {
    echo '<p>Footer\'da görünecek hızlı erişim linklerini buradan yönetebilirsiniz.</p>';
}

function oyunhaber_legal_link_render($args) {
    $i = $args['index'];
    $text_val = get_option("oyunhaber_legal_link_text_$i");
    $url_val = get_option("oyunhaber_legal_link_url_$i");
    ?>
    <input type="text" name="oyunhaber_legal_link_text_<?php echo $i; ?>" value="<?php echo esc_attr($text_val); ?>" placeholder="Link Metni" style="width: 150px;">
    <input type="url" name="oyunhaber_legal_link_url_<?php echo $i; ?>" value="<?php echo esc_attr($url_val); ?>" placeholder="https://..." style="width: 250px;">
    <?php
}

function oyunhaber_social_render($args) {
    $key = $args['key'];
    $value = get_option("oyunhaber_social_$key");
    echo '<input type="url" name="oyunhaber_social_' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';
}

function oyunhaber_contact_email_render() {
    $value = get_option('oyunhaber_contact_email');
    echo '<input type="email" name="oyunhaber_contact_email" value="' . esc_attr($value) . '" class="regular-text" placeholder="iletisim@orbi.local">';
}

function oyunhaber_contact_address_render() {
    $value = get_option('oyunhaber_contact_address');
    echo '<input type="text" name="oyunhaber_contact_address" value="' . esc_attr($value) . '" class="regular-text" placeholder="İstanbul, Türkiye">';
}

function oyunhaber_footer_settings_page() {
    ?>
    <div class="wrap">
        <h1>Site Genel Ayarları</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('oyunhaber_footer_group');
            do_settings_sections('oyunhaber-footer-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
