
-- Objetos atributos 
SELECT CONCAT('public ',
  
  ' ' ,
  '$' , 
  LOWER (REPLACE(COLUMN_NAME , 'NOT_','') )
  ,
  ' ' , ';' )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'data_example' AND TABLE_NAME = 'data_example';
  
 
 -- 
 --   $water->cod = $row['COD'];
 SELECT CONCAT('$soilLayer',       
  '->' ,
  '' , 
  LOWER (REPLACE(COLUMN_NAME , 'NOT_','') )
  ,
  ' ' , '= $row[\'' ,  COLUMN_NAME , '\']' ';' )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'data_example' AND TABLE_NAME = 'data_example';
 
 --  $newObj->cod = $stdClass->cod;
 
  SELECT CONCAT('$soil ',       
  '->' ,
  '' , 
  LOWER (REPLACE(COLUMN_NAME , 'NOT_','') )
  ,
  ' = $stdClass->' ,    COLUMN_NAME , ';' )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'data_example' AND TABLE_NAME = 'data_example';
 
 
  --  'field';
 
  SELECT CONCAT(
  '\'' , 
  LOWER (COLUMN_NAME) 
  ,
  '\' ,'  )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'data_example' AND TABLE_NAME = 'data_example';
 
 
 --$model->name, $model->date_hour, $model->cod
 
   SELECT CONCAT('$model',       
  '->' ,
  '' , 
  LOWER (COLUMN_NAME) 
  ,  ',' )
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'data_example' AND TABLE_NAME = 'data_example';

 
