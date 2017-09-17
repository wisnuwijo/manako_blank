<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppLokasiserver extends Database {

   protected $mSqlFile= 'module/manako_app_lokasiserver/business/mysqlt/app_lokasiserver.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===READ===
   function GetDataAppLokasiservers($uniFind, $byId, $start, $display) {
      $query = $this->mSqlQueries['get_data_app_lokasiservers'];
      $limit = '';
      $finds = '';
      if ($start != '' && $display != ''){
         $limit  = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds  = "WHERE lokasiserverName LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE lokasiserverId = '$byId'";
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
   function DoAddAppLokasiserver($name) {
      $result = $this->Execute($this->mSqlQueries['do_add_app_lokasiserver'], array($name));
      return $result;
   }
   function GetCountDuplicateNameAdd($name) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name_add'], array($name));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppLokasiserver($name, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_lokasiserver'], array($name, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateName($name, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_name'], array($name, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteAppLokasiserver($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app_lokasiserver'], array($id));
      return $result;
   }
}
