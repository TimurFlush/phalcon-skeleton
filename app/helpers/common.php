<?php declare(strict_types = 1);

function config(string $name = 'main') {
    static $config = [];

    if (!isset($config[$name])) {
        $config[$name] = require_once APP_DIR . '/config/' . $name . '.php';
    }

    return $config[$name];
}
