<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../vendor/autoload.php';

define('PUBLIC_DIR', __DIR__);
define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', ROOT_DIR . '/app');
define('CACHE_DIR', ROOT_DIR . '/cache');
define('IS_CLI', PHP_SAPI === 'cli');
define('IS_WEB', !IS_CLI);
define('CF_COUNTRY', $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null);

\Dotenv\Dotenv::create(ROOT_DIR)->load();

define('ENV', getenv('APP_ENV'));

$envs = [
    'staging',
    'development',
    'production'
];

if (!in_array(ENV, $envs)) {
    http_response_code(500);
    exit('Invalid environment. Acceptable values: ' . join(', ', $envs));
}

define('IS_STAGING', ENV === 'staging');
define('IS_DEVELOPMENT', ENV === 'development');
define('IS_PRODUCTION', ENV === 'production');

app\bootstrap::load();