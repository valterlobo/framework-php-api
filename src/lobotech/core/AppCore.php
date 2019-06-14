<?php



namespace lobotech\core;

use DI\ContainerBuilder;


class AppCore extends \DI\Bridge\Slim\App
{
    private $modules;
    private $routers;

    private $configs = [];

    public function __construct($modules, $routers)
    {
        $this->modules = $modules;
        $this->routers = $routers;
        $this->configs = $this->builderConfig();

       
        //DEIXAR ESTA CHAMADA AQUI ANTES DE CONSTRUIR AS ROTAS
        parent::__construct();
       
        $this->builderRouter();
       
      
      

    }

    protected function configureContainer(ContainerBuilder $builder)
    {

        $builder->addDefinitions($this->configs);
        //PROD
      
        //$builder->enableDefinitionCache();
        //$builder->enableCompilation(__DIR__ . '/var/cache');


    }

    private function builderRouter()
    {
        foreach ($this->routers as $r) {
            call_user_func($r . '::builderRouter', $this);
        }
    }

    private function builderConfig()
    {
        $config = [];
        foreach ($this->modules as $m) {

            $config = array_merge($config, call_user_func($m . '::getContainerConfig'));

        }

        return $config;

    }
}
