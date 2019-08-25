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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'taxiBooking' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ';C[)g+PFkKzaKUYa&dL8;6MrhQDf+W8@KU3SS`>7#=u<NTl&M)Rov9=hZ4Sm j!c' );
define( 'SECURE_AUTH_KEY',   'Tkix,QF.OekoIvg9v$p*zeN{at?b^]mO2(oEi*#|K(H(J-~B}oQiefP+%~R?:i*a' );
define( 'LOGGED_IN_KEY',     'X <k@&q$V2gG#_c2JvzeBnjWL}#gga5;>ZOc;JMeCB>)s!wf/mW>Os]HM29f+9jo' );
define( 'NONCE_KEY',         'b+=i[k90YrK,;g/x{dcwijU7x_w=>qV)(6blZ#ItnyT`j%|mchYgtKb{s,nq^hpD' );
define( 'AUTH_SALT',         '}}_uuc;[|xC@,}jw5[*&t#8}*$($NPI|WtD`,ul3,f_1z+x67zQbCr88Z2?[|>wN' );
define( 'SECURE_AUTH_SALT',  'O1_b%;Y,FV({Q# g1xY1D(gJ]o>cMZy{VL3YEtB~Ti{;>$d/Dc<C_.-}X>oCAZ`Y' );
define( 'LOGGED_IN_SALT',    'ASL/zPJNCWj5>ugs;y2cF4Kv1k^7Dt6?#^LlV@G7}qiC,V]&#8OCl]GD(|zLGGL)' );
define( 'NONCE_SALT',        'Kr]0gps(bIo`ckqZ}MK)Xl4@Ld=1nEdJLoki0:T!&}#]1O=ct, Yj%NOJJN_j?98' );
define( 'WP_CACHE_KEY_SALT', 'c3a&VSw?i&1Ndy7F.aKHi/,YZYoZx||m(#V@&,C.mo#f+jvQnq|eKTx}gA#}i<6.' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
