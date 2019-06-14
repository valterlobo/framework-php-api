<?php

namespace helloapp\modules\domain\repository;

use PDO;

class UserRepository
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email)
    {

        $sql = "SELECT * FROM users WHERE email=:email";
        $sth = $this->pdo->prepare($sql);
        $sth->bindParam("email", $email);
        $sth->execute();
        $user = $sth->fetchObject();
        return $user; 
    }

}
