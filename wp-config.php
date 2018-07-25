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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'prod-jo-16jul18-updates');

/** MySQL database username */
define('DB_USER', 'current_jo_user');

/** MySQL database password */
define('DB_PASSWORD', 'Il2eaGH!!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('JO_COMMUNITY_LIST', array(19, 20, 24, 41, 42, 100, 287));



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '2mL)8dQmH@Kq5i`w6)kVE`lcGRD>Fdh/gWBS7Q04:}-;p?t?NY,yvmF2,Bf@|>Do');
define('SECURE_AUTH_KEY',  'GBUuxz`,/i;i~KRq/+d#qy2TD$9lp=wdBG4N/;f+Rx>Qyt=%v=~cnP%yx;c6tClP');
define('LOGGED_IN_KEY',    '#u Nq5_xx(}ZjOg1sw7Wxr&`WR;JKU$(/+*x%^);GjLH2Pb@2(*O,G6^_,c/.LT4');
define('NONCE_KEY',        ':HgXaR%Pmnp5k/M*Bf`@g+uq*]9yELn[^aBaz/,J2wRy3HC?Q*%O~zU^P-~CU+y1');
define('AUTH_SALT',        'd=vy|{salO%Ql`g?c|w9=!#4DVCCIE]@b@kbKvwG#RNSM,g-}<QVL$/Z` _PXFZi');
define('SECURE_AUTH_SALT', '~$R</H::}*Fz}>_3u`w1Pqhr/:g|J{nT~j}y{*/!?~(^xSIF>.[dj#@b1T}>d{^2');
define('LOGGED_IN_SALT',   '3~;fEKSY6*ji<u1RPE1A[DR^MP=Lz_^i<3L@~tPK,xH0m*9ed&7r`zp|kx-gZOM4');
define('NONCE_SALT',       '72jVPY aq .:8#H*cW{NU9*|i&T^FL|7Qi=+bDng,n!hi1=xF;[@3iUWtnDU_C4C');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'jo_wp_prod_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);
define('IS-DEVELOPMENT', true);

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

@ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
@ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
@ini_set('error_log',ABSPATH . '/logs/php-errors-prod-jo-16jul18.log'); // path to server-writable log file

if ( !defined('WP_TEMP_DIR') ) {
    define('WP_TEMP_DIR', dirname(__FILE__) . '/wp-content/temp/');
    //define('WP_TEMP_DIR', ABSPATH . '../wp_temp/');
}
define('FS_METHOD', 'direct');
/* That's all, stop editing! Happy blogging. */

