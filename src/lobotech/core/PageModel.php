<?php

namespace lobotech\core;

class PageModel
{

    public $page;
    public $size;
    public $total_records;
    public $total_pages;
    public $pages = [];

    public function buildPages()
    {

        $this->total_pages = ceil(($this->total_records / $this->size));
        $this->pages = range(1, $this->total_pages);
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
