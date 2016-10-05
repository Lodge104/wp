<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache


/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp2_lodge104_net');

/** MySQL database username */
define('DB_USER', 'wp2lodge104net');

/** MySQL database password */
define('DB_PASSWORD', 'KopOCTn0TxC20d5Ny');

/** MySQL hostname */
define('DB_HOST', 'mysql.wp.lodge104.net');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'aY_e$8"Ih/AT^ZH?mZ/3d/@7KXA"6Q&3N:HKIcbUjbP3T3vdI~K/rNv%jxWA(GM"');
define('SECURE_AUTH_KEY',  'DTFHJ87rVt1p+Z9P4R6yb8$)"Dp^Pq+"clu!oEgsTs&:0a`0^(bvrGi&pY9I(P1l');
define('LOGGED_IN_KEY',    'v2@#^|*dC5vL7eZokP04nk@^|BEvp"!MVEydh7RNXJ86zX%3hOOMSqxfjpO?K19e');
define('NONCE_KEY',        'f@L@3#v4"otEd?qP2)kE?#Mh@4hK80vmTLG?`/0b*T~@untfuLFz~&(Ek?KJs6ab');
define('AUTH_SALT',        'r0nZ%/3_ilxqBx%s@J#?qQ|lUK(^sbkmyhfS4CWtgA1SKb4qiD3oQ)0w"I@/0U@F');
define('SECURE_AUTH_SALT', '2HhQvldf2Cqw4n9&p:KE(yt|3U!b7u5%st1s26|IDYxp`+DMIxx`/36Bw(IWnZkp');
define('LOGGED_IN_SALT',   '~I;gpbY|feLb*@3v0Heo^T3WN`#+$IY_m@H@6W^GSnx0_3B6r!UpFe7D/Ej/G(cV');
define('NONCE_SALT',       'r8sl"IdzjoExElZTo00gi1TPrlY(`xs`6(K:Pvl#gw"lIg4w~&aM:FJOnbt0U^0f');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = "wp_2f8mwf_";

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

