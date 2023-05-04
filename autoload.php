<?php

require_once './vendor/phpunit.phar';

spl_autoload_register(function ($class_name) {
    $project_classes = 'classes/'; // Dossier contenant vos classes
    $test_classes = 'tests/'; // Dossier contenant vos fichiers de test

    if (file_exists($project_classes . $class_name . '.php')) {
        require_once $project_classes . $class_name . '.php';
    } elseif (file_exists($test_classes . $class_name . '.php')) {
        require_once $test_classes . $class_name . '.php';
    }
});
