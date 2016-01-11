<?php

/**
 * Application Config
 */

// Debug
$app['debug'] = true;

if ($app['debug']) {
    // Show errors
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
} else {
    // Disable Show errors
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Local
$app['locale'] = 'ru-RU';
$app['session.default_locale'] = $app['locale'];

// Cache
$app['cache.path'] = PATH_CACHE;

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';