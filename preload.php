<?php

/**
 * Borrowed the preload function implementation from
 * Dmitry Stogov's RFC https://wiki.php.net/rfc/preload
 */
function preload($preload, array $ignore = [], string $pattern = "/\.php$/") {
    if (is_array($preload)) {
        foreach ($preload as $path) {
            preload($path, $ignore, $pattern);
        }
    } else if (is_string($preload)) {
        $path = $preload;
        if (!in_array($path, $ignore)) {
            if (is_dir($path)) {
                if ($dh = opendir($path)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file !== "." && $file !== "..") {
                            preload($path . "/" . $file, $ignore, $pattern);
                        }
                    }
                    closedir($dh);
                }
            } else if (is_file($path) && preg_match($pattern, $path)) {
                if (!opcache_compile_file($path)) {
                    trigger_error("Preloading Failed", E_USER_ERROR);
                }
            }
        }
    }
}

preload([
    __DIR__ . '/vendor/nikic/fast-route/src',
    __DIR__ . '/fast-route_functions.php',
    __DIR__ . '/vendor/pimple/pimple/src',
    __DIR__ . '/vendor/container-interop/container-interop/src',
    __DIR__ . '/vendor/psr/http-message/src',
    __DIR__ . '/vendor/slim/slim/Slim',
    __DIR__ . '/vendor/slim/php-view/src',
    __DIR__ . '/vendor/psr/container/src',
], [
    __DIR__ . '/vendor/pimple/pimple/src/Pimple/Tests',
    __DIR__ . '/vendor/nikic/fast-route/src/bootstrap.php',
    __DIR__ . '/vendor/nikic/fast-route/src/functions.php',
]);
