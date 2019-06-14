<?php
namespace helloapp\modules\config;

use function DI\create;
use function DI\factory;
use function DI\get;
use helloapp\modules\domain\repository\UserRepository;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use lobotech\core\IModule;
use lobotech\modules\core\ConfiService;

class ConfigModule implements IModule
{

    public function __construct()
    {

    }

    public static function getContainerConfig()
    {

        return [

            "jwt" => [
                'secret' => 'supersecret123',
            ],

            'ConfigService' => create(ConfiService::class)->constructor(
                [
                    'value' => 'key',
                    'db.host' => get('db.host'),
                ]),

            'log.file' => "/home/sdt1/logs/api-log.log",

            Logger::class => factory(function ($c) {

                $logger = new Logger('api-log');

                $rotating = new RotatingFileHandler($c->get('log.file'), 0, Logger::DEBUG);
                $logger->pushHandler($rotating);
                $loggerFormat = "[%datetime%] %level_name% %%extra.request_uri%%  %message% %context% %extra%\n";
                $loggerTimeFormat = "Y-m-d H:i:s";
                $formatter = new LineFormatter($loggerFormat, $loggerTimeFormat);
                $rotating->setFormatter($formatter);
                //->addArgument('[%%datetime%%] [%%extra.token%%] %%channel%%.%%level_name%%: %%message%% %%context%% %%extra%%\n');

                return $logger;
            }),

            AuthController::class => create()
                ->constructor(get(UserRepository::class), 'supersecret123', get(Logger::class)),

            'AuthController' => AuthController::class,

        ];
    }

}
