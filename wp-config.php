<?php
define( 'WP_CACHE', true ); // Added by WP Rocket
define( 'WP_DEBUG', true ); // Added by WP Rocket

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );
/** Database username */
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );
/** Database password */
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );
/** Database hostname */
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );

define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '6b14af37d62baca1e634162b201bc9597db2237a') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '969b9a286a8b8d23bee613ecc70b86a1c783abdb') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    '7bd3821fc3f6af3210aafd25f0f9d809b30f99cd') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '2507c00f43a187e7c16123b8672b55ce99f3d302') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        'a52f8561dfcb425db346a61a5ecd18f141daecc3') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'e8c72ea1d478604d95cdb843e1a38dc8782d2e3c') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   '81d68eb9657e4fef56bd16f8ffc5acf3125048ec') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'd596e4ae0f39b42a6ee172d6c4df0918537bf98d') );

$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . 'wp-config.php/');
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
