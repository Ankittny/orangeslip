<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'recrueet_blog' );

/** Database username */
define( 'DB_USER', 'recrueet_app' );

/** Database password */
define( 'DB_PASSWORD', 'VYtr*-KPAt-o' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'S+H?g`^o}d[fy`|.lbAU431;!JP]9gP@|cbp?eaMnzF*-YjY2r9O!WaK:OkVKo9L' );
define( 'SECURE_AUTH_KEY',  '_>jR|[b):lq#B1cTG8AP9(B+l2faS.wZS.%j;T`&`I^2-}}E-a~)[SWn{5|D`8w*' );
define( 'LOGGED_IN_KEY',    'u@i4]cPJP9CM{Y(:vV;EVkq%1BuV|bRH9Luen&h`%I3#&:L}fk-Js(r;O%fZ!Jvj' );
define( 'NONCE_KEY',        '$[7!l[b<88agu0I?bJ(_4OtQq/:vSn]/F61{C>l%$S2-1YMYKdUrPIWw0x>|vhR/' );
define( 'AUTH_SALT',        'm6,cnjoE}4ZJ$=S;x6MbP<L-rNk3WNn.9mN?/ w~(PC{~rHQ,g3(dr-U&[q!%&gz' );
define( 'SECURE_AUTH_SALT', 'Zf8hXXZ,Od&$f5.X)ee8c15]|2h~7!sc~&|ojbpx5%.7iyk4xNU4j~v`D6/+/ePE' );
define( 'LOGGED_IN_SALT',   'Aj&`kB%N;{`Q@f1_E?udcH]lIbL(z7KD5$gtP={/U=N><248%X P]M{pg%o/}p8u' );
define( 'NONCE_SALT',       ']}k}mi<u@BB#~*qwZ5E!9V7X-d$K<E:d;Sz/,i;v!zZUMbN{=:IY4^(6-u_KQ9Z6' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
