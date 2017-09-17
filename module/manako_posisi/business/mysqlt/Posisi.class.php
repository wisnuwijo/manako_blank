<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Posisi extends Database {

   protected $mSqlFile= 'module/manako_posisi/business/mysqlt/posisi.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataPosisi($name) {
      
      $result = $this->Open($this->mSqlQueries['get_data_posisi'], array('%'.$name.'%'));
      return $result;
   }
   function GetDataPosisiById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_posisi_by_id'], array($id));
      return $result;
   }
//===CREATE===
   function DoAddPosisi($code,$name) {
      $result = $this->Execute($this->mSqlQueries['do_add_posisi'], array($code,$name));
      return $result;
   }
   function GetCountDuplicateCodeAdd($code) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_code_add'], array($code));
      return $result;
   }
//===UPDATE===
   function DoUpdatePosisi($code, $name, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_posisi'], array($code, $name, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateCode($code, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_code'], array($code, $id));
      return $result;
   }
//===DELETE===
   function DoDeletePosisi($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_posisi'], array($id));
      return $result;
   }
}