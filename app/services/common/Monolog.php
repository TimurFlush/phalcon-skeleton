<?php declare(strict_types = 1);

namespace app\services\common;

use Bugsnag\Client as BsClient;
use Bugsnag\Configuration as BsConf;
use MeadSteve\MonoSnag\BugsnagHandler as MsHandler;
use Monolog\Logger;

class Monolog implements \Phalcon\Di\ServiceProviderInterface
{
    public function register(\Phalcon\DiInterface $di)
    {
        $di->setShared('monolog', function () {
            $monolog = new Logger(getenv('APP_NAME'));

            if (getenv('LOGGER_TELEGRAM') === 'On') {
                $monolog->pushHandler(
                    new \rahimi\TelegramHandler\TelegramHandler(
                        getenv('LOGGER_TELEGRAM_BOT_TOKEN'),
                        getenv('LOGGER_TELEGRAM_CHAT_ID'),
                        getenv('APP_TIMEZONE'),
                        '[d.m.Y H:i:s]',
                        10
                    )
                );
            }

            if (getenv('LOGGER_BUGSNAG') === 'On') {
                $bsHandler = new BsClient(new BsConf(getenv('LOGGER_BUGSNAG_KEY')));
                $bsHandler->setReleaseStage(getenv('APP_ENV'));

                $msHandler = new MsHandler($bsHandler);

                $monolog->pushHandler($msHandler);
            }

            return $monolog;
        });
    }
}
