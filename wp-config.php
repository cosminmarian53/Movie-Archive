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


define('AUTH_KEY',         '5ZxTcTFqMpKNcYfmlZzhwkPH6oxR5VMOyMvL6avFryqkysCS/cLVm4CQEUQaAMDlqf7D0KhT52mvUZvWjP87Ig==');
define('SECURE_AUTH_KEY',  'T4qxr3xRUVBtoXjfpA/g+CeCRiXl/YxyDjAwN+XsfmjfJPM4OE5WuNKQJJrIFidSxd2iBqPT8y5ePJTZDBUDrw==');
define('LOGGED_IN_KEY',    'Ghx3sjhUIgUm6NbrALaClWqxxzA/DTeNZL93iigvTggLWBb89F73iChzZsdgLf+aGqU/RtvML7JUBNhmkc2cIw==');
define('NONCE_KEY',        'lkQjzFiA6Y3C04PaRdBiIycWbUtflFPHruLFNXMOWy+8g6BuL4cB3FK8g03X/l991oj9oY+0uytTVZso98+h5g==');
define('AUTH_SALT',        'WG3Pi8+qND8doJzFS2lNmtw6g/WNRKQpF/UP4RGcDEJMilyTPxR1kOqN+O33B9hug5pzIPDvkV4/2c5zzc8HAA==');
define('SECURE_AUTH_SALT', 'TX3CqzkhOo9yK4RVE92FvE4JdM431sVlRaYBqZfaCHIJ++Aa0p0Q+XcJLrl58CcasovM13c96ry9Hwh5CRqj6Q==');
define('LOGGED_IN_SALT',   'EIddbskTiA8UtBCKyZb6Hs48QXtTXRivmK8pp3r1h9NpbShBfQKAuYlrFSQC8sYOuyOx7h7DaClBRoTT5iwhxA==');
define('NONCE_SALT',       'TzqrBs32xVU4ua4Mxx0DBqIzvX9y9VbBEjFg4ToW/LIRCQYZEWjB+WwacOCzEQPtgS7PQ6574hvIrwjm0j/7fg==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
