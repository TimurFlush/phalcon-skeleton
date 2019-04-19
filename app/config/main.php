<?php

return new Phalcon\Config(
    [
        'uriSource' => \Phalcon\Mvc\Router::URI_SOURCE_GET_URL,

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
                'groups' => [
                    \app\routes\SiteRoutes::class
                ]
            ]
        ]
    ]
);
