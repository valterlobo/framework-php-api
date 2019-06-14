<?php
namespace helloapp\modules\domain\repository;

use helloapp\modules\domain\model\Hello;
use PDO;
use Psr\Log\LoggerInterface;
use lobotech\core\BaseModel;
use lobotech\core\CrudRepository;
use lobotech\core\RequestModel;
use lobotech\core\ResultModel;

class HelloRepository extends CrudRepository
{

    public function __construct(PDO $pdo, LoggerInterface $logService)
    {

        parent::__construct($pdo, $logService);
    }

    /**
     * @param $row
     * @return Hello
     * @throws \Exception
     */
    protected function mapRowToModel($row): Hello
    {
        if (!$row) {
            throw new \PDOException('No Item found');
        }

        $hello = new Hello();
        $hello->cod = $row['COD'];
        $hello->name = $row['NAME'];
        $hello->date_hour = $row['DATE_HOUR'];

        return $hello;
    }

    /**
     * @param BaseModel $model
     * @return array
     */
    public function createParamPersist(BaseModel $model): array
    {
        $hello = $model;

        if ($hello instanceof Hello) {
            return [$model->name, $model->date_hour, $model->cod];
        }

        throw new \RuntimeException('[studio1.tech-api] Object is not of the same type');
    }

    public function createParamUpdate(BaseModel $model)
    {

        if ($model instanceof Hello) {
            return [$model->name, $model->date_hour, $model->cod];

        }
        throw new \RuntimeException('[studio1.tech-api] Object is not of the same type');
    }

    protected function getSqlForPersist(): string
    {
        return 'INSERT INTO HELLO (NAME, DATE_HOUR, COD) VALUES (?,?,?)';
    }

    protected function getSqlForGetById(): string
    {
        return 'SELECT NAME, DATE_HOUR, COD FROM HELLO WHERE COD= ?';
    }

    protected function getSqlForDeleteById(): string
    {
        return 'DELETE FROM HELLO  WHERE COD = ?';
    }

    protected function getSqlForUpdate(): string
    {
        return 'UPDATE HELLO SET NAME=? , DATE_HOUR=? WHERE COD=?';
    }

    public function search($query, RequestModel $requestModel): ResultModel
    {
        $sql = "SELECT NAME, DATE_HOUR, COD FROM HELLO
                WHERE NAME LIKE ?";

        $str_query = '%' . $query . '%';
        $items = $this->pdoGetAllByPage($sql, [$str_query], $requestModel);

        return $items;

    }

}
