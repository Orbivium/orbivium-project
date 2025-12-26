<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '.JsEgQQAo:{l0A^h0 c2ni0itP-BAThFfh!JkIzb&r09A&fP~3Z(]&u):4?0y@Xz' );
define( 'SECURE_AUTH_KEY',   'Y0=5#4_JS!L:|nQ`DdzYRcVd Q@+EJMxpD-U|TL;95NSi<P%A^mpKMGtu87i- ;X' );
define( 'LOGGED_IN_KEY',     'dXWfSV7bOR97TYY[PD)?g5I_+aeo+ZL=R= $H(AD9a+,Ws,K*6`jVb  +hU|-rnA' );
define( 'NONCE_KEY',         '=&g:JW^[~D?0C]uTdXX8vni{@n9{3A2gh2jhVHUu=?ky]Q4ju[$#N14(u2dmY;X)' );
define( 'AUTH_SALT',         '%jB(^40JRPuiMJKM7*XS0B(3)C$&/.Nn)*??F$4`G=>|wKT6}jZ%oaj%+@<+%aje' );
define( 'SECURE_AUTH_SALT',  'vw+}i1cN{Nx5LL+,umn-wT$%SsYo4~q#${j #!|qadscn609<qTTjlY84O.8[kzE' );
define( 'LOGGED_IN_SALT',    'D?/jAN6u0D/e.92:9kD4.oHJG62}4w(I# g<dN2bIs.^WP$r2EgqJ[{-Rm^m2R)8' );
define( 'NONCE_SALT',        '^F.W08Qlx6,v59L#Ju}a!?Q|S8[x7a*]^;r:q]ELP>u%m`vAVB26L5CPthR/f*0g' );
define( 'WP_CACHE_KEY_SALT', 'ibZ#7)w +t!-)g4H}.yy^D?>64+iY)U)yG*Dx?6~LU9v,+Z8odDyQm.o#$4g]8`-' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
