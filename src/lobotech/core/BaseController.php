<?php

namespace lobotech\core;

use PDOException;
use lobotech\modules\core\ConfigService;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

abstract class BaseController
{

    public const PAGE = 1;
    public const SIZE = 10;

    /**
     * @var BaseService
     */
    private $baseService;

    public function __construct(BaseService $service)
    {
        $this->baseService = $service;
    }

    public function setKeyName($key)
    {
        $this->keyName = $key;
    }

    /**
     * @param $stdClass
     * @param $attributes
     * @throws \Exception
     */
    public function verifyAtrributes($stdClass, $attributes)
    {
        if (!$stdClass) {
            throw new \RuntimeException('EMPTY', ConfigService::PARAM_EMPTY);
        }

        foreach ($attributes as $i => $attr) {

            if (!property_exists($stdClass, $attr)) {
                throw new \Exception('PARAM_NOT_ATTRIBUTE: ' . $attr, ConfigService::PARAM_NOT_ATTRIBUTE);
            }

        }
    }

    public function get($id, Request $request, Response $response)
    {
        try {
            $obj = $this->baseService->getById($id);
        } catch (PDOException $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        } catch (\Exception $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        }
        return $this->createSuccessResponse($response, $obj);

    }

    /**
     * @param Response $r
     * @param string $errorMsg
     * @param int $errorCode
     * @param \Exception $exception
     * @param int $code
     * @return Response
     */
    protected function createErrorResponse(Response $r, $errorMsg, $errorCode, $exception = null, $code = 400)
    {

        $data = [];

        if ($exception !== null) {
            if (ConfigService::DEBUG) {
                $data['exception'] = @utf8_encode($exception->getMessage());
                $data['file'] = "{$exception->getFile()}:{$exception->getLine()}";
            }
        }
        $data['error_message'] = $errorMsg;
        $data['error_code'] = $errorCode;

        return $r->withJson($data, $code);
    }

    /**
     * @param Response $r
     * @param object|array $data
     * @param int $code
     * @return Response
     */
    protected function createSuccessResponse(Response $r, $data, $code = 200)
    {

        return $r->withJson($data, $code);
    }

    /*************************************************/
    //TEMPLATES METHOD
    /*************************************************/
    public function insert(Request $request, Response $response)
    {
        try {
            $newObj = $this->toObject($request);
            $obj = $this->baseService->insert($newObj);
        } catch (PDOException $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        } catch (\Exception $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        }
        return $this->createSuccessResponse($response, $obj);

    }

    /**
     * @param Request $r
     * @return BaseModel
     */
    abstract protected function toObject(Request $r);

    public function update($id, Request $request, Response $response)
    {
        try {
            $newObj = $this->toObject($request);
            $newObj->setId($id);
            $obj = $this->baseService->update($newObj);
        } catch (PDOException $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        } catch (\Exception $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        }
        return $this->createSuccessResponse($response, $obj);
    }

    public function delete($id, Request $request, Response $response)
    {
        try {

            $qtd = $this->baseService->delete($id);
        } catch (PDOException $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        } catch (\Exception $e) {
            return $this->createErrorResponse($response, $e->getTraceAsString(), $e->getCode(), $e);
        }
        $msg = array('deleted' => $qtd, 'id' => $id);

        return $this->createSuccessResponse($response, $msg);
    }

    /**
     * @param $baseModel
     * @param $stdClass
     * @throws \Exception
     */
    protected function setAttributesBaseModel(BaseModel $baseModel, $stdClass)
    {
        if (property_exists($stdClass, 'id')) {
            $baseModel->setId($stdClass->id);
        }

    }

    protected function buildRequestModel(Request $request): RequestModel
    {
        $requestModel = new RequestModel();
        $page = $request->getQueryParam('page');
        $size = $request->getQueryParam('size');
        $page = is_null($page) ? BaseController::PAGE : $page;
        $size = is_null($size) ? BaseController::SIZE : $size;

        $requestModel->page = $page;
        $requestModel->size = $size;

        //order_by=name:desc,date_hour:asc
        $order_by_str = $request->getQueryParam('order_by');
        $array_sort = [];
        if (!is_null($order_by_str)) {

            $fields = explode(",", $order_by_str);
            foreach ($fields as $field) {
                $key_value = explode(":", $field);
                $array_sort[$key_value[0]] = $key_value[1];
            }
        }
        $requestModel->sort = $array_sort;
        return $requestModel;
    }

}
