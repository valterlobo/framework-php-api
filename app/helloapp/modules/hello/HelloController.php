<?php
namespace helloapp\modules\hello;

use helloapp\modules\domain\model\Hello;
use lobotech\core\BaseController;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class HelloController extends BaseController
{
    /**
     * @var HelloService
     */
    private $helloService;

    public function __construct(HelloService $helloService)
    {
        $this->helloService = $helloService;
        $this->setKeyName("KEY_HELLO");
        parent::__construct($this->helloService);

    }

    public function hello($name, Request $request, Response $response)
    {

        $response->getBody()->write("Hello MODULE 2, $name");
        return $response;
    }

    /**
     * @param Request $r
     * @return Hello
     * @throws \Exception
     */
    public function toObject(Request $r)
    {
        $stdClass = json_decode($r->getBody());

        $newObj = new Hello();
        $newObj = $newObj->newFromStdClass($stdClass);

        return $newObj;

    }

    public function search($query, Request $request, Response $response): Response
    {
        try {

            $requestModel = $this->buildRequestModel($request);
            $items = $this->helloService->search($query, $requestModel);
        } catch (\Exception $e) {
            return $this->createErrorResponse($response, $e->getMessage(), $e->getCode(), $e);
        }

        return $this->createSuccessResponse($response, $items);
    }

}
