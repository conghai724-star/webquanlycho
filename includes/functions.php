<?php
/**
 * Models in the adminmaster module use names such as `stallModel`, while the
 * source files use the `stallModel.php` convention.  The old autoloader only
 * searched for lower-case `*.class.php`, so every new model failed to load.
 */
spl_autoload_register(function ($className) {
    $candidates = [
        __SITE_PATH . '/model/' . $className . '.php',
        __SITE_PATH . '/model/' . strtolower($className) . '.php',
        __SITE_PATH . '/model/' . strtolower($className) . '.class.php',
        __SITE_PATH . '/application/' . strtolower($className) . '.class.php',
    ];

    foreach ($candidates as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});
