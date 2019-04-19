<?php declare(strict_types = 1);

namespace app\services\web;

use Phalcon\Mvc\Router as MvcRouter;

class Router implements \Phalcon\Di\ServiceProviderInterface
{
    public function register(\Phalcon\DiInterface $di)
    {
        $di->setShared('router', function () {
            $router = new MvcRouter();

            $router->setUriSource(
                empty(config()->uriSource)
                    ? MvcRouter::URI_SOURCE_GET_URL
                    : config()->uriSource
            );

            foreach (config()->services->router->groups as $group) {
                $router->mount(new $group);
            }

            return $router;
        });
    }
}
