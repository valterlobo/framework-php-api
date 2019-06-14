<?php
namespace helloapp\modules\config;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use lobotech\core\IRouter;
use lobotech\modules\core\ConfigService;
use Tuupola\Middleware\CorsMiddleware;
use Tuupola\Middleware\JwtAuthentication;
use \DI\Bridge\Slim\App;

class ConfigRouter implements IRouter
{

    public static function builderRouter(App $appSlim)
    {
        $appSlim->post('/auth', ['AuthController', 'auth']);

        ConfigService::$LOGGER_SECURITY_FILE = "/home/std1dev/PROJETOS/studio1.tech-php-api/logs/security.log";

        $logger = new Logger("api_security");
        $rotating = new RotatingFileHandler(ConfigService::$LOGGER_SECURITY_FILE, 0, Logger::DEBUG);
        $logger->pushHandler($rotating);

        $appSlim->add(new JwtAuthentication([

            "ignore" => ["/auth", "/water" , '/soil' , '/hello' ],
            "regexp" => "/(.*)/",
            "secret" => "supersecret123",
            "algorithm" => ["HS256"],
            "logger" => $logger,
            "secure" => true,
            "error" => function ($response, $arguments) {
                $data["status"] = "error";
                $data["message"] = $arguments["message"];
                return $response
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            },
        ]));

        $appSlim->add(new CorsMiddleware([
            "origin" => ["*"],
            "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
            "headers.allow" => ["Authorization", "Origin", "Content-Type", "Acess-Control-Allow-Origin", 'X_TOKEN'],
            "headers.expose" => ["Etag"],
            "credentials" => true,
            "cache" => 86400,
            "logger" => $logger,
        ]));

    }

}
