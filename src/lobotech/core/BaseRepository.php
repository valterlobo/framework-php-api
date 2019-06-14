<?php

namespace lobotech\core;

use PDO;
use Psr\Log\LoggerInterface;

abstract class BaseRepository
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var LoggerInterface
     */

    protected $logService;

    public const COD_NOT_FOUND = 320;

    /**
     * @param PDO $pdo
     * @param LoggerInterface $logService
     */
    public function __construct(PDO $pdo, LoggerInterface $logService)
    {
        $this->pdo = $pdo;
        $this->logService = $logService;
    }

    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->throwIfNotStartedTransaction();
        $this->pdo->commit();
    }

    protected function throwIfNotStartedTransaction(): void
    {
        if (!$this->pdo->inTransaction()) {
            throw new \PDOException('Tried to execute an action without calling begin() first');
        }
    }

    protected function pdoGetOne($sql, $data)
    {
        $this->logService->info("pdoGetOne:" . $sql . ":" . var_export($data, true));

        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
        $data = $statement->fetch();
        $statement->closeCursor();
        return $data;
    }

    /**
     * @param $sql string insert SQL to execute
     * @param $data array the array data
     * @return array returns all arrays objects from the query
     */
    protected function pdoGetAll($sql, $data): array
    {
        $this->logService->info("pdoGetAll:" . $sql . ":" . var_export($data, true));
        $stm = $this->pdo->prepare($sql);
        $stm->execute($data);
        $data = $stm->fetchAll();
        $stm->closeCursor();
        return $data;
    }

    /**
     * @param $sql string insert SQL to execute
     * @param $data array the array data
     * @param $page page
     * @param $size size of page
     * @return array returns all arrays objects from the query
     */
    protected function pdoGetRecordsPage($sql, $data, RequestModel $requestModel): array
    {
        $this->logService->info("pdoGetRecordsPage:" . $sql . ":" . var_export($data, true));

        $sql_sort_str = $this->sqlSort($requestModel);
        $sql_limit_str = $this->sqlLimit($requestModel);

        $sqlPage = $sql . ' ' . $sql_sort_str . ' ' . $sql_limit_str;
        $this->logService->info("pdoGetRecordsPage:" . $sqlPage . ":" . var_export($data, true));
        $stm = $this->pdo->prepare($sqlPage);
        $stm->execute($data);
        $data = $stm->fetchAll();
        $stm->closeCursor();
        return $data;
    }

    /**
     * @param $sql string insert SQL to execute
     * @param $data array the array data
     * @return integer returns total
     */
    protected function pdoGetTotalRecords($sql, $data): int
    {
        $this->logService->info("pdoGetTotalRecords:" . $sql . ":" . var_export($data, true));
        $sqlPageTotal = 'SELECT COUNT(*) as total_rows FROM (' . $sql . ') as total_record';
        $stm = $this->pdo->prepare($sqlPageTotal);
        $stm->execute($data);
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $total_records = (int) $row['total_rows'];
        return $total_records;
    }

    protected function sqlLimit(RequestModel $requestModel): string
    {
        $limit = ($requestModel->page - 1) * $requestModel->size;
        $sql_limit = " LIMIT " . $limit . "," . ($requestModel->size);
        return $sql_limit;

    }

    protected function sqlSort(RequestModel $requestModel): string
    {
        $sort = $requestModel->sort;
        $sql_sort_str = '';
        if (is_array($sort) && count($sort) > 0) {

            $sql_sort_str = ' order by ';
            $t = count($sort);
            $i = 1;
            foreach ($sort as $key => $value) {
                $sql_sort_str = $sql_sort_str . "{$key} {$value}";
                if ($i < $t) {
                    $sql_sort_str = $sql_sort_str . " , ";
                }
                $i++;
            }
        }
        return $sql_sort_str;
    }

    /**
     * @param $sql string insert SQL to execute
     * @param $data array the array data. The prepared data.
     * @return int returns how many rows has been affected by the query
     */
    protected function pdoGetNumRowsAffected($sql, $data): int
    {

        $this->logService->info("pdoGetNumRowsAffected:" . $sql . ":" . var_export($data, true));
        $stm = $this->pdo->prepare($sql);
        $stm->execute($data);
        $numRows = $stm->rowCount();
        $stm->closeCursor();
        return $numRows;
    }

    /**
     * RETORNA 0 SE O ID FOR INSERIDO JUNTO (USE O ID PASSADO PARA A QUERY)
     * @param $sql string insert SQL to execute
     * @param $data array the array data. The prepared data.
     * @return string returns the id of the inserted item.
     */
    protected function pdoInsert($sql, $data): string
    {
        $this->logService->info("pdoInsert:" . $sql . ":" . var_export($data, true));
        $this->throwIfNotStartedTransaction();
        $stm = $this->pdo->prepare($sql);
        $stm->execute($data);
        $id = $this->pdo->lastInsertId();
        $this->logService->info("pdoInsert:" . 'ID INSERT:' . $id);
        $stm->closeCursor();
        return $id;
    }

    /**
     * @param $id
     * @return BaseModel
     * @throws \PDOException
     */
    abstract public function getById($id): BaseModel;

    /**
     * saves an model
     * @param BaseModel $model
     * @throws \PDOException
     */
    abstract public function persist(BaseModel $model);

    /**
     * deletes an model
     * @param BaseModel $model
     * @throws \PDOException
     */
    abstract public function delete(BaseModel $model);

    /**
     * updates an model
     * @param BaseModel $model
     * @throws \PDOException
     */
    abstract public function update(BaseModel $model);

    /**
     * @param $id
     * @return
     */
    abstract public function deleteById($id);

}
