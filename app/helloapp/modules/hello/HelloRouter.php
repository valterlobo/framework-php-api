<?php
namespace helloapp\modules\hello;

use lobotech\core\IRouter;
use \DI\Bridge\Slim\App;

class HelloRouter implements IRouter
{

    public static function builderRouter(App $appSlim)
    {
        $appSlim->group('/hello', function () use ($appSlim) {
            $appSlim->get('/{id}', ['HelloController', 'get']);
            $appSlim->get('/search/{query}', ['HelloController', 'search']);
            $appSlim->post('/', ['HelloController', 'insert']);
            $appSlim->put('/{id}', ['HelloController', 'update']);
            $appSlim->delete('/{id}', ['HelloController', 'delete']);
        });
    }

}
