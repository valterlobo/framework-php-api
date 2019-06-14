<?php

namespace lobotech\core;


class ResultModel
{
   public $page;
   //public $query;
   public $sort;
   public $records; 

   
   public function jsonSerialize()
   {
       return get_object_vars($this);
   }

}
