<?php
namespace lobotech\core;

use \DI\Bridge\Slim\App;

interface IRouter
{
    public static function builderRouter( App $appSlim );
}