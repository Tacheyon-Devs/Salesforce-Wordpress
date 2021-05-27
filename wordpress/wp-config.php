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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         '%!Lf3 5Zq(YU]n9;VL4sb_oj%8+r[M8}E)z;D3i..jU0/S+f!/3Y+l]SSm,Jqr(V' );
define( 'SECURE_AUTH_KEY',  'nk;g6>`L=Wl<^&5*ZZoBC33`}n1n~o(xD)R4f,7aJLzVFpDAE1<g0.PDKV[raob2' );
define( 'LOGGED_IN_KEY',    'A@wr*uB<T+xH_:8^y#Ts1sHDyo0ty2B&[E2vmrww~F(1hgmXfb(CuUF:6 -+0arf' );
define( 'NONCE_KEY',        '3(HVVprR3!Z N@!3Jds%JLzVSn^Yt6|5_DbkN>s;c~KfQj=6Cgb5oN2rDKlFl#z^' );
define( 'AUTH_SALT',        '*isvU=jEzLBC)sX[ W>xPAK-<P;T|:rL5X=%d~H3C~yfXgGMoL`JLg8(.GG*rFOT' );
define( 'SECURE_AUTH_SALT', ':I2*Pz;*x7_q6l5OJ~zxaw0wX[Q,O#)l5QmT3~}!h;!s0Z:IeTX12gX,8,%6%I~!' );
define( 'LOGGED_IN_SALT',   '}UY=B0D=c)lQFx/H)_{Yfe_gE70D^-)lAYwei[) Zy)1 5r[<sMID<rz-QmuU3YG' );
define( 'NONCE_SALT',       'S1Xk$Q2tg>E !i@IeFiUIGn_`Zu!e1(y[N?i)s3^B!L(Ni{]C3o4<Fjk`Q4J3(XT' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
