<?php

namespace lobotech\core;

abstract class CrudRepository extends BaseRepository
{

    /**
     * @param BaseModel $model
     * @return BaseModel
     * @throws \PDOException
     * @throws \Exception
     */
    public function persist(BaseModel $model): BaseModel
    {
        $values = $this->createParamPersist($model);

        $id = $this->pdoInsert($this->getSqlForPersist(), $values);
        $model->setId($id);
        return $this->getById($model->getId());
    }

    abstract protected function createParamPersist(BaseModel $model);

    abstract protected function getSqlForPersist(): string;

    /**
     * @param $id
     * @return BaseModel
     * @throws \Exception
     */
    public function getById($id): BaseModel
    {
        $row = $this->pdoGetOne($this->getSqlForGetById(), [$id]);

        if (!$row) {
            throw new \PDOException('No Item found', self::COD_NOT_FOUND);
        }

        return $this->mapRowToModel($row);
    }

    abstract protected function getSqlForGetById(): string;

    abstract protected function mapRowToModel($row);

    /**
     * @param BaseModel $model
     * @return int number of rows updated
     * @throws \PDOException
     */
    public function delete(BaseModel $model): int
    {
        return $this->deleteById($model->getId());
    }

    /**
     * @param $id
     * @return int number of rows updated
     */
    public function deleteById($id): int
    {
        return $this->pdoGetNumRowsAffected($this->getSqlForDeleteById(), [$id]);
    }

    abstract protected function getSqlForDeleteById(): string;

    /**
     * updates an model
     * @param BaseModel $model
     * @return int number of rows updated
     * @throws \PDOException
     */
    public function update(BaseModel $model): int
    {

        $paramUpdate = $this->createParamUpdate($model);
        return $this->pdoGetNumRowsAffected($this->getSqlForUpdate(), $paramUpdate);
    }

    abstract protected function createParamUpdate(BaseModel $model);

    abstract protected function getSqlForUpdate(): string;

    protected function mapMultipleRowsToEntities($multipleRows)
    {
        $entities = [];
        foreach ($multipleRows as $row) {
            $entities[] = $this->mapRowToModel($row);
        }
        return $entities;
    }

    /**
     * @param $sql string insert SQL to execute
     * @param $data array the array data
     * @param $page page
     * @param $size size of page
     * @return array returns all arrays objects (Object)
     */
    protected function pdoGetAllByPage($sql, $data, RequestModel $requestModel): ResultModel 
    {
        $items = $this->pdoGetRecordsPage($sql, $data, $requestModel);

        if (is_array($items)) {

            $records = $this->mapMultipleRowsToEntities($items);
            $result = new ResultModel();
            $result->records = $records;
            $pageModel = new PageModel();
            $pageModel->page = $requestModel->page;
            $pageModel->size = $requestModel->size;
            $pageModel->total_records = $this->pdoGetTotalRecords($sql, $data);
            $pageModel->buildPages();
            $result->page = $pageModel; 
            $result->sort = $requestModel->sort;

            return $result;
        }
        $result = new ResultModel();
        return  $result;
    }

    protected function replaceNull($value)
    {
        return (is_null($value)) ? "" : $value;
    }

}
