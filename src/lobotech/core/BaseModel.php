<?php

namespace lobotech\core;

use Swaggest\JsonSchema\Structure\ClassStructure;

abstract class BaseModel  //implements \JsonSerializable
{

    abstract public function getId();

    abstract public function setId($id);

    /**
     * Dica de implementação para subclasses:
     * { return get_object_vars($this); }
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    //abstract public function jsonSerialize();


  

    public function newFromStdClass(\stdClass $std)
    {
        $instance = new static;

        foreach ( (array) $std as $attribute => $value)
        {
            if ( $this->fillableIsSetAndContainsAttribute($attribute) or $this->fillableNotSet())
                $instance->{$attribute} = $value;
        }

        return $instance;
    }

    /**
     * Returns if the fillable array exists and contains
     * the attributes requested.
     * 
     * @param $attribute
     * @return bool
     */
    protected function fillableIsSetAndContainsAttribute($attribute)
    {
        return (isset($this->fillable) && count($this->fillable) > 0 && in_array($attribute, $this->fillable));
    }

    /**
     * Returns whether fillable attribute is not set.
     * 
     * @return bool
     */
    protected function fillableNotSet()
    {
        return ! isset($this->fillable);
    }

}
