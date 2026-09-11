<?php

/**
 * Copia encima de vendor/ los archivos guardados en patches/vendor/.
 *
 * vendor/ se reinstala desde cero en cada "composer install" (así construye
 * la imagen de Docker en Railway), así que cualquier edición manual hecha
 * directo en vendor/ se pierde. Este script la reaplica automáticamente:
 * guarda tu versión modificada en patches/vendor/<misma-ruta-relativa> y se
 * copia sola cada vez que corre composer (ver "scripts" en composer.json).
 */

$root = dirname(__DIR__);
$patchesDir = $root . '/patches/vendor';
$vendorDir = $root . '/vendor';

if (!is_dir($patchesDir)) {
    echo "apply-vendor-patches: no hay patches/vendor, nada que aplicar.\n";
    exit(0);
}

function applyPatches(string $dir, string $patchesRoot, string $vendorRoot): void
{
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $dir . '/' . $item;

        if (is_dir($path)) {
            applyPatches($path, $patchesRoot, $vendorRoot);
            continue;
        }

        $relative = substr($path, strlen($patchesRoot) + 1);
        $destination = $vendorRoot . '/' . $relative;

        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0777, true);
        }

        copy($path, $destination);
        echo "apply-vendor-patches: parcheado vendor/{$relative}\n";
    }
}

applyPatches($patchesDir, $patchesDir, $vendorDir);
