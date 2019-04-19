<?php declare(strict_types = 1);

use app\libraries\HMvcModule;
use Phalcon\Mvc\View\Simple;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\DiInterface;
use app\libraries\volt\CallPhpFunctions;

class Site extends HMvcModule
{
    public function getModuleName(): string
    {
        return 'site';
    }

    public function getModuleDirectory(): string
    {
        return APP_DIR . '/modules/site';
    }

    public function registerServices(\Phalcon\DiInterface $di)
    {
        parent::registerServices($di);

        $directory = $this->getModuleDirectory();
        $name = $this->getModuleName();

        # Volt service.
        $di->setShared('view', function () use ($directory, $name) {
            $view = new Simple();

            $view->setViewsDir($directory . '/views/');
            $view->registerEngines(
                [
                    '.volt' => function (Simple $view, DiInterface $di) use ($directory, $name) {
                        $volt = new Volt($view, $di);
                        $volt->setOptions(
                            [
                                'compileAlways' => IS_DEVELOPMENT,
                                'compiledPath' => CACHE_DIR . '/templates/' . $name . '/',
                                'compiledExtension' => '.php_template',
                                'compiledSeparator' => '_',
                                'stat' => !IS_DEVELOPMENT
                            ]
                        );
                        $compiler = $volt->getCompiler();
                        $compiler->addExtension(new CallPhpFunctions);
                        return $volt;
                    }
                ]
            );

            return $view;
        });
    }
}
