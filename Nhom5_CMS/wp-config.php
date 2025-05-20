<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'data' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'm&Ihz~GsWGq/l@oea+L,)#4q^DpbBV?Fj>l-fCRFKY@IU8^!/J3b%v,Cyf4NM40/' );
define( 'SECURE_AUTH_KEY',  '}$L5^^s^gvN COx1cIK|[{~-&sqHbyO(~MtiYR;_~v4UjJ:Nkg|q!8({qh8C;Fqe' );
define( 'LOGGED_IN_KEY',    '!]l#49gxZcG1-Z<7:/4^rX)Lj qv{H)dq]S7.a$GOm16.l-23n$?u3,IM~DQ((2x' );
define( 'NONCE_KEY',        '[/AW_GUSBn P8z9^+_g3~4s=Yd}Bs$WF|x$?~H/&Xc,0xU/2sXMK%OhfltR6f|YZ' );
define( 'AUTH_SALT',        ']/tM/qD`WQxLFpEF+S#!<^`uxi1n-p,SQb+qajl*4}eh<IqgzP5l-+RLYfE.91W[' );
define( 'SECURE_AUTH_SALT', '4p1fnt]qAbI0{4avHaZdbRr9 cO(UgB0?zBr=rd))1IC0&aT??zhFz2[#2vT}9E|' );
define( 'LOGGED_IN_SALT',   '%F*/3!& KG5;w;O,HCjED:6vbtc1jV0A?s/sO)lm1C}v$a9hnr@3s0-{YR0u!%0t' );
define( 'NONCE_SALT',       'g!L+F`;:15QiA(VaG:^ K;Z?Rjs]1vEn&728&-t+.jWZYDU%AJ]Fb2m|17t^;u!I' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'sw_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
