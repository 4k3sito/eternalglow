<?php
/**
 * Plugin Name: Entorno local (eternalglow)
 * Description: SOLO para la copia local. Reescribe las URLs de producción a localhost y bloquea el envío de correos. No subir a producción.
 */

if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) || 'local' !== WP_ENVIRONMENT_TYPE || ! defined( 'WP_HOME' ) ) {
	return;
}

function eg_local_url_map() {
	static $map = null;
	if ( null === $map ) {
		$local = untrailingslashit( WP_HOME );
		$map   = array();
		foreach ( array( 'https://www.eternalglow.com.mx', 'http://www.eternalglow.com.mx', 'https://eternalglow.com.mx', 'http://eternalglow.com.mx' ) as $prod ) {
			$map[ $prod ]                          = $local;
			$map[ str_replace( '/', '\/', $prod ) ] = str_replace( '/', '\/', $local );
		}
	}
	return $map;
}

// Reescribe URLs absolutas de producción en todo el HTML/JSON que genera WordPress.
ob_start(
	function ( $output ) {
		return strtr( $output, eg_local_url_map() );
	}
);

add_filter(
	'wp_redirect',
	function ( $location ) {
		return strtr( $location, eg_local_url_map() );
	}
);

// Nunca enviar correos desde la copia local (formularios, notificaciones, etc.).
add_filter( 'pre_wp_mail', '__return_false' );
