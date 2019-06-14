<?php

use helloapp\modules\config\ConfigModule;
use helloapp\modules\config\ConfigRouter;
use helloapp\modules\domain\DomainModule;
use helloapp\modules\hello\HelloModule;
use helloapp\modules\hello\HelloRouter;
use lobotech\core\AppCore;
use lobotech\modules\core\CoreModule;


require '../vendor/autoload.php';

$modules = [DomainModule::class, 
            CoreModule::class, 
            DomainModule::class, 
            ConfigModule::class, 
            HelloModule::class,
            ];
           
$routers = [HelloRouter::class, 
            ConfigRouter::class,
           ];
            
$app = new AppCore($modules, $routers);

$app->run();
