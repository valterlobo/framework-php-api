<?php

namespace lobotech\core;

class RequestModel
{
    public $page;
    public $size;
    public $sort = [];

    /*
    SORT
    direction -
    properties -
    ex:   properties => direction = name => asc
     */

    //public $query;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
