<?php

namespace lobotech\core;

use lobotech\modules\core\ConfigService;

abstract class BaseService
{

    /**
     * @var CrudRepository
     */
    private $repository;

    /**
     * BaseService constructor.
     * @param CrudRepository $repository
     */
    public function __construct(CrudRepository $repository)
    {
        $this->repository = $repository;

    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * @param BaseModel $model
     * @return BaseModel
     * @throws \Exception
     */
    public function insert(BaseModel $model): BaseModel
    {
        $this->repository->begin();
        $newEntity = $this->repository->persist($model);
        $this->repository->commit();
        return $newEntity;
    }

    /**
     * @param $id
     * @return int nRows
     */
    public function delete($id)
    {

        $this->repository->begin();
        $nRows = $this->repository->deleteById($id);
        if ($nRows > 1) {
            throw new \RuntimeException('MAX DELETE:' . $nRows, ConfigService::MAX_DELETE_QTD);
        }
        $this->repository->commit();

        return $nRows;
    }

    /**
     * @param BaseModel $model
     * @return BaseModel
     * @throws \RuntimeException
     */
    public function update(BaseModel $model): BaseModel
    {
        $this->repository->begin();

        $nRows = $this->repository->update($model);
        if ($nRows > 1) {
            throw new \RuntimeException('MAX UPDATE:' . $nRows, ConfigService::MAX_UPDATE_QTD);
        }
        $this->repository->commit();

        return $this->repository->getById($model->getId());
    }

}
