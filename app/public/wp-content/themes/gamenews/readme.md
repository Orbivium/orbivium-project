# OyunHaber WordPress Theme

OyunHaber is a modern, dark-mode focused WordPress theme designed specifically for game news, reviews, and platform-based content.

## 🌟 Key Features

*   **Dark Mode Aesthetic**: A sleek, high-contrast dark design tailored for gamers.
*   **Custom Post Types**: Native support for **News** and **Reviews** content types.
*   **Platform Taxonomy**: Content organized by gaming platforms (PC, PlayStation, Xbox, Nintendo, Mobile) with distinctive color-coded badges.
*   **Responsive Design**: Fully optimized for mobile, tablet, and desktop devices.
*   **Custom User Profile**: Dedicated 'Profil' page template.
*   **Dynamic Navigation**: Custom menu styling with platform icons.

## 🚀 Installation & Setup

1.  **Download/Clone**: Place the `gamenews` folder into your WordPress themes directory (`/wp-content/themes/`).
2.  **Activate**: Go to your WordPress Admin Dashboard -> Appearance -> Themes and activate **OyunHaber**.
3.  **Automatic Configuration**:
    *   Upon activation (or the first visit to the Admin panel), the theme will automatically:
        *   Register the necessary **Platform** taxonomies.
        *   Create the **Profil** page.
        *   Import **Demo Data** (sample News and Reviews with images) to populate your site immediately.

## 📁 Directory Structure

*   `style.css` - Main stylesheet and theme metadata.
*   `functions.php` - Theme setup and core functionality.
*   `inc/`
    *   `custom-post-types.php` - Definitions for News, Reviews post types and Platform taxonomy.
    *   `demo-data.php` - Script for auto-importing demo content and images.
*   `header.php` / `footer.php` - Global header and footer templates.
*   `page-profil.php` - Custom template for the user profile page.
*   `assets/` - Images, icons, and other static resources.

## 🛠 Features in Detail

### Platform System
The theme categorizes content by five main platforms:
*   **Genel** (General)
*   **PC**
*   **PlayStation**
*   **XBOX**
*   **Nintendo**
*   **Mobil**

Each platform has its own dedicated color and styling in the UI.

### Demo Importer
The built-in importer (`inc/demo-data.php`) fetches placeholder images from LoremFlickr and generates dummy content so you can visualize the theme structure immediately without manual entry.

## 👨‍💻 Authors

*   Ferat
*   Antigravity

---
*Version 1.0.0*
