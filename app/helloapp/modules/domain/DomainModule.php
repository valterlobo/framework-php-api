<?php

namespace helloapp\modules\domain;

use function DI\create;
use function DI\get;
use helloapp\modules\domain\repository\HelloRepository;
use Monolog\Logger;
use PDO;
use lobotech\core\IModule;

class DomainModule implements IModule
{

    public function __construct()
    {

    }

    public static function getContainerConfig()
    {
        return [

            'db.host' => '67.205.189.154',
            'db.user' => 'root',
            'db.pass' => 'dev@100std1',
            'db.dbname' => 'data_example',

            PDO::class => function ($c) {

                $pdo = new PDO("mysql:host=" . $c->get('db.host') .
                    ";dbname=" . $c->get('db.dbname') . ";charset=UTF8", $c->get('db.user'), $c->get('db.pass'));
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                return $pdo;
            },

            UserRepository::class => create()->constructor(get(PDO::class)),

            HelloRepository::class => create()->constructor(get(PDO::class),
                get(Logger::class))

        ];
    }

}
