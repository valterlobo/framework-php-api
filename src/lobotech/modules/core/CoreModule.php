<?php
namespace lobotech\modules\core;

use lobotech\core\IModule;

class CoreModule implements IModule
{

    public function __construct()
    {

    }

    public static function getContainerConfig()
    {
        return [
            'settings.displayErrorDetails' => true,
            'settings.responseChunkSize' => 4096,
            'settings.outputBuffering' => 'append',
            'settings.determineRouteBeforeAppMiddleware' => true,
            'displayErrorDetails' => true,
        ];
    }
}
