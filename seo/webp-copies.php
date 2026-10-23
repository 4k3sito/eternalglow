<?php
// Uso: php seo/webp-copies.php <archivo o carpeta>... (en el servidor, desde ~/public_html). Ya no hay plugin que genere
// las copias: el bloque "EG WebP" del .htaccess sirve imagen.png.webp en lugar de imagen.png cuando existe.
// Writes file.png.webp next to each PNG/JPG given (or found in a directory), keeping transparency. Skips existing copies.
if (!function_exists('imagewebp')) { echo "ERROR: GD sin WebP\n"; exit(1); }
$files = array();
foreach (array_slice($argv, 1) as $arg) {
    $files = array_merge($files, is_dir($arg) ? glob(rtrim($arg, '/') . '/*.{png,jpg,jpeg}', GLOB_BRACE) : array($arg));
}
foreach ($files as $f) {
    $out = "$f.webp";
    if (file_exists($out)) { echo "ya existe: " . basename($out) . "\n"; continue; }
    $png = preg_match('/\.png$/i', $f);
    $im = $png ? imagecreatefrompng($f) : imagecreatefromjpeg($f);
    if (!$im) { echo "ERROR: no se pudo leer $f\n"; exit(1); }
    imagepalettetotruecolor($im);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    imagewebp($im, $out, 82);
    imagedestroy($im);
    printf("%s: %d KiB -> %d KiB\n", basename($f), filesize($f) / 1024, filesize($out) / 1024);
}
