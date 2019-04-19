<?php declare(strict_types = 1);

namespace app\libraries;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

abstract class HMvcModule implements ModuleDefinitionInterface
{
    abstract public function getModuleName(): string;
    abstract public function getModuleDirectory(): string;

    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
    {
        $name = $this->getModuleName();
        $directory = $this->getModuleDirectory();

        if (!is_dir($directory)) {
            throw new \RuntimeException('Path "' . $directory . '" is not directory.');
        }

        $loader = new Loader();

        $loader->registerNamespaces(
            [
                $name . '\controllers' => $directory . '/controllers',
                $name . '\forms'       => $directory . '/forms',
                $name . '\models'      => $directory . '/models'
            ]
        );

        $loader->register();
    }

    public function registerServices(\Phalcon\DiInterface $dependencyInjector)
    {
        $name = $this->getModuleName();

        /** @var Dispatcher $dispatcher */
        $dispatcher = $dependencyInjector->get('dispatcher');
        $dispatcher->setDefaultNamespace($name . '\controllers');
        $dependencyInjector->setShared('dispatcher', $dispatcher);
    }
}
