<?php
namespace helloapp\modules\hello;

use function DI\create;
use function DI\get;
use helloapp\modules\domain\repository\HelloRepository;
use lobotech\core\IModule;

class HelloModule implements IModule
{

    public function __construct()
    {

    }

    public static function getContainerConfig()
    {
        return [

            HelloService::class => create()->constructor(get(HelloRepository::class)),

            HelloController::class => create()->constructor(get(HelloService::class)),

            'HelloController' => get(HelloController::class),
        ];
    }

}
