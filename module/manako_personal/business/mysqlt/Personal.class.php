<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Personal extends Database {

   protected $mSqlFile= 'module/manako_personal/business/mysqlt/personal.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataPersonal($name) {
      
      $result = $this->Open($this->mSqlQueries['get_data_personal'], array('%'.$name.'%'));
      return $result;
   }

   function GetDataPersonalById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_personal_by_id'], array($id));
      return $result;
   }
//===CREATE===
   function DoAddPersonal($name) {
      $result = $this->Execute($this->mSqlQueries['do_add_personal'], array($name));
      return $result;
   }
   function GetCountDuplicateNameAdd($name) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name_add'], array($name));
      return $result;
   }
//===UPDATE===
   function DoUpdatePersonal($name, $updateTime, $updateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_personal'], array($name, $updateTime, $updateUser, $id));
      return $result;
   }
   function GetCountDuplicateName($name, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name'], array($name, $id));
      return $result;
   }
//===DELETE===
   function DoDeletePersonal($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_personal'], array($id));
      return $result;
   }
}