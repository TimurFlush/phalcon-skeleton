<?php

use Phalcon\Mvc\Router;

return new Phalcon\Config(
    [
        'defaultHttpHeaders' => [
            'X-XSS-Protection' => '1; mode=block'
        ],

        'modules' => [
            'site' => [
                'className' => Site::class,
                'path' => APP_DIR . '/modules/Site.php'
            ]
        ],

        'services' => [
            'router' => [
                'uriSource' => Router::URI_SOURCE_GET_URL,
                'groups' => [
                    \app\routes\SiteRoutes::class
                ]
            ]
        ]
    ]
);
