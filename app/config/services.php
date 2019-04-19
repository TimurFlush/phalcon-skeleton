<?php

return [
    'common' => [
        \app\services\common\Monolog::class,
        \app\services\common\Whoops::class,
    ],
    'web' => [
        \app\services\web\Router::class,
    ],
    'cli' => []
];