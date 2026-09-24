<?php
/**
 * Router para el servidor embebido de PHP (php -S): emula las reglas de
 * .htaccess de WordPress. Archivos y carpetas reales se sirven tal cual;
 * todo lo demás pasa por index.php (enlaces permanentes).
 */
$root = rtrim( $_SERVER['DOCUMENT_ROOT'], '/' . DIRECTORY_SEPARATOR );
$path = urldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

if ( '/' !== $path && file_exists( $root . $path ) ) {
	return false;
}

$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['PHP_SELF']        = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root . '/index.php';
chdir( $root );
require $root . '/index.php';
