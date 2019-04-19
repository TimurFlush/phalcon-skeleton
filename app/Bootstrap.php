<?php

namespace app;

use Phalcon\Cli\Console;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault as WebDi;
use Phalcon\Di\FactoryDefault\Cli as CliDi;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Application;

class bootstrap
{
    protected static $_isLoaded;

    public static function load()
    {
        if (!self::$_isLoaded) {
            self::$_isLoaded = true;
            (new self);
        }
    }

    protected function __construct()
    {
        date_default_timezone_set(getenv('APP_TIMEZONE'));

        if (IS_WEB) {
            $di = new WebDi();
        } else {
            $di = new CliDi();
        }

        $this->autoloader();
        $this->services($di);
        $this->handleErrors($di);
        $this->handleApplication($di);
    }

    protected function handleErrors(Di $di)
    {
        ini_set('display_errors', 'Off');

        if (IS_DEVELOPMENT) {
            ini_set('display_errors', 'On');
        }

        /** @var \Whoops\Run $whoops */
        $whoops = $di->get('whoops');
        $whoops->register();
    }

    protected function autoloader()
    {
        $loader = new \Phalcon\Loader();

        foreach (config('loader') as $regType => $sapis) {
            $regType = 'register' . ucfirst($regType);

            if (method_exists($loader, $regType)) {
                foreach ($sapis as $sapi => $data) {
                    if ($sapi === 'common' || ($sapi === 'cli' && IS_CLI) || ($sapi === 'web' && IS_WEB)) {
                        $loader->{$regType}($data, true);
                    }
                }
            }
        }

        $loader->register();
    }

    protected function services(Di $di)
    {
        foreach (config('services') as $sapi => $data) {
            if ($sapi === 'common' || ($sapi === 'cli' && IS_CLI) || ($sapi === 'web' && IS_WEB)) {
                foreach ($data as $service) {
                    $service = new $service;
                    if ($service instanceof ServiceProviderInterface === false) {
                        throw new \RuntimeException(
                            'Service [' . $service . '] must be implements ' . ServiceProviderInterface::class
                        );
                    }

                    $service->register($di);
                }
            }
        }
    }

    protected function handleApplication(Di $di)
    {
        if (IS_WEB) {
            $app = new Application($di);
            $app->useImplicitView(false);
            $app->registerModules(config()->modules->toArray());

            foreach (config()->defaultHttpHeaders->toArray() as $name => $value) {
                $app->response->setHeader($name, $value);
            }

            $app->handle();
        } else {
            $app = new Console($di);

            $arguments = [];
            foreach ($_SERVER['argv'] as $k => $arg) {
                if ($k === 1) {
                    $arguments['task'] = $arg;
                } elseif ($k === 2) {
                    $arguments['action'] = $arg;
                } elseif ($k >= 3) {
                    $arguments['params'][] = $arg;
                }
            }

            $app->handle($arguments);
        }
    }
}
