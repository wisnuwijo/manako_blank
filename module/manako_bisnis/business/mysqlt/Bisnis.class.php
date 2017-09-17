<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Bisnis extends Database {

   protected $mSqlFile= 'module/manako_bisnis/business/mysqlt/bisnis.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataBisnis($model) {
      
      $result = $this->Open($this->mSqlQueries['get_data_bisnis'], array('%'.$model.'%'));
      return $result;
   }
   function GetDataBisnisById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_bisnis_by_id'], array($id));
      return $result;
   }
//===CREATE===
   function DoAddBisnis($model) {
      $result = $this->Execute($this->mSqlQueries['do_add_bisnis'], array($model));
      return $result;
   }
   function GetCountDuplicateModelAdd($model) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_model_add'], array($model));
      return $result;
   }
//===UPDATE===
   function DoUpdateBisnis($model, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_bisnis'], array($model, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateModel($model, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_model'], array($model, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteBisnis($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_bisnis'], array($id));
      return $result;
   }
}