<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppLoginrole extends Database {

   protected $mSqlFile= 'module/manako_app_loginrole/business/mysqlt/app_loginrole.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataAppLoginroles($uniFind, $byId, $start, $display) {
      $query = $this->mSqlQueries['get_data_app_loginroles'];
      $limit = '';
      $finds = '';
      if ($start != '' && $display != ''){
         $limit  = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds  = "WHERE loginroleName LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE loginroleId = '$byId'";
         $query  = str_replace('-- byId --', $byId, $query);
      }
      $result = $this->Open($query, array());
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result;
   }   
//===CREATE===
   function DoAddAppLoginrole($name) {
      $result = $this->Execute($this->mSqlQueries['do_add_app_loginrole'], array($name));
      return $result;
   }
   function GetCountDuplicateNameAdd($name) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name_add'], array($name));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppLoginrole($name, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_loginrole'], array($name, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateName($name, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name'], array($name, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteAppLoginrole($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app_loginrole'], array($id));
      return $result;
   }
}