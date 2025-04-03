<?php
// Ngarkon automatikisht klasat nga folderi 'classes' kur përdoren.
spl_autoload_register(function($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});