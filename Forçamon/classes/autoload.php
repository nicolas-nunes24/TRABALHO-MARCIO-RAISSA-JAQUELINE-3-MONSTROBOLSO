<?php
spl_autoload_register(function (string $classe): void {
    $arquivo = __DIR__ . '/' . $classe . '.php';
    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});