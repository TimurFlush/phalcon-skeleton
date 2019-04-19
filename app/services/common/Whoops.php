<?php declare(strict_types = 1);

namespace app\services\common;

use Monolog\Logger;
use Whoops\Handler\CallbackHandler;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\PlainTextHandler;

class whoops implements \Phalcon\Di\ServiceProviderInterface
{
    public function register(\Phalcon\DiInterface $di)
    {
        $di->setShared('whoops', function () {
            /** @var Logger $monolog */
            $monolog = $this->get('monolog');

            $whoops = new Run;

            if (IS_WEB && IS_DEVELOPMENT) {
                $whoops->pushHandler(new PrettyPageHandler);
            } elseif (IS_CLI) {
                $whoops->pushHandler(new PlainTextHandler);
            }

            $whoops->pushHandler(
                new CallbackHandler(function (\Throwable $throwable) use ($monolog) {
                    $monolog->addError(
                        $throwable->getMessage(),
                        [
                            'exception' => $throwable
                        ]
                    );
                })
            );

            return $whoops;
        });
    }
}
