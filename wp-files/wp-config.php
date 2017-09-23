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
define('DB_NAME', 'prorokcopuprorok');

/** MySQL database username */
define('DB_USER', 'prorokcopuprorok');

/** MySQL database password */
define('DB_PASSWORD', 'kN8mb9JSzCwB');

/** MySQL hostname */
define('DB_HOST', 'prorokcopuprorok.mysql.db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', 'utf8_general_ci');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'w;i|ox&EX^9lc)zde2/Vo@UiRNBv1ll%>cr~Z1*csAa%%UJY}<pncE-[T@3<-TT[');
define('SECURE_AUTH_KEY',  'OwtRx~&.6Vj6 SUd-q;7n$0s+OG-L>NS$Ux9#Wk%][%]+^?!4sM%}Ax[cg?d8gB$');
define('LOGGED_IN_KEY',    '3!p9};%cSS0W+tghI*li:3x(wc[E~V]6>d7M*-&mCP!s-NCWPd`5O 8^?G4MvKL5');
define('NONCE_KEY',        '1;VVrWGhOiadIN$%Fk{Q_c8djc~IhLiL_+~)kk#mU&Z-wTwx_qa=r9nl6;MJBw9}');
define('AUTH_SALT',        '])klh4%,wC(3L~TVT3?Myy3 K2)qM,.6X0QPYiTS/ci#POiHe+1 :.B.DKG_0ms4');
define('SECURE_AUTH_SALT', 'J$3&,0tL+Sr]u0So(_KLidbN@:14V6&{*uuP }O}R<0>[Fv#-ctiO4_sff}{3ndY');
define('LOGGED_IN_SALT',   '>EKnlQO}]JJ+Jo..]HM)6MWvzm$yDVIOivx#J&U~1n9;.Y|<BIQkQ_#<v`}}hoHX');
define('NONCE_SALT',       '3gdKmOr+G9?}HHpr:lK0K|iWj$*$,.{!O%?cs$!V#33!F=T~H`v/+W/QOTkKYq<e');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define ('WP_DEBUG', false);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
