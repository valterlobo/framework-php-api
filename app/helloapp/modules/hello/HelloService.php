<?php

namespace helloapp\modules\hello;

use helloapp\modules\domain\repository\HelloRepository;
use lobotech\core\BaseService;
use lobotech\core\RequestModel;
use lobotech\core\ResultModel;

class HelloService extends BaseService
{

    /**
     * @var HelloRepository
     */
    private $helloRepository;

    public function __construct(
        HelloRepository $helloRepository) {
        $this->helloRepository = $helloRepository;
        parent::__construct($this->helloRepository);
        
    }


    public function search($query,RequestModel $requestModel): ResultModel
    {
        return $this->helloRepository->search($query,$requestModel);
    }



    
    

}
