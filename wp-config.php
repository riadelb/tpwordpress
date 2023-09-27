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
define( 'DB_NAME', 'master' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

/** Database hostname */
define( 'DB_HOST', 'database' );

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
define( 'AUTH_KEY',          '|H}`uwdXIa>i,OCI&i7u;.@V_AQAR([;sdR^~+EQ+^!<imKCq^~6|ZyR[N2%L}Ju' );
define( 'SECURE_AUTH_KEY',   ')T4|5+qFnx@~6!rmhuuJ{5{)%|1VL^@FN,gO=bT6?#7ChymxkI?O*}>]s.g]WR&r' );
define( 'LOGGED_IN_KEY',     'A3~W0`NAFOo{9g1uyR~]-gt{GL b*r0aUlyU(S@IO<U|>cQknk9rQ_?L{A@H%-h[' );
define( 'NONCE_KEY',         '?O=g?5CgCk{8m{[ZG8oL+fbRdC0Q5JC`4IYJF`}biu>*_.a^pePxkzh7`Z]is=Zf' );
define( 'AUTH_SALT',         '+5[m^_a-:*f8v8mY1&Cic3-GJ,rN9CbFP^-h=)0x&W{Jq+UJF0BSkS #)g>|@SKx' );
define( 'SECURE_AUTH_SALT',  '+~ePF[&=Vs,<` F;d7hgKjwbilkw=r)D7qSEHo1?DJF?|:.<iS35|4~[Ul9M[H=F' );
define( 'LOGGED_IN_SALT',    'HT9y=-CRe54qx&W-20UtI~RqP@m<kZ0k~&G7fs49);(pyZO|vn`x$R4~9fw,~6H*' );
define( 'NONCE_SALT',        '0pP[R?1J|(AtP+y$N|6UYU?:,o rA>p?Fx_AClKNgdfy0^,5B.K^5?*/}0FUGCu_' );
define( 'WP_CACHE_KEY_SALT', 'rBQo-4);NUFYNv^Hiy.k~,h`5.<*FnhRv?pZ!H?a@q yy=%@}XW*2ZWr)(n!/x0%' );


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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
