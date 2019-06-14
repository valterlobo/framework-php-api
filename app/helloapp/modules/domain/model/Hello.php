<?php

namespace helloapp\modules\domain\model;

use lobotech\core\BaseModel;

class Hello extends BaseModel
{
    public $cod;
    public $name;
    public $date_hour;

    public function getId()
    {
        return $this->cod;
    }

    public function setId($id)
    {

        $this->cod = $id;
    }

    /**
     * Dica de implementação para subclasses:
     * { return get_object_vars($this); }
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
