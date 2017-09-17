<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Posisi2 extends Database {

   protected $mSqlFile= 'module/manako_posisi2/business/mysqlt/posisi2.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataPosisis($byId=null, $uniFind='', $start=null, $display=null) {      
      $query  = $this->mSqlQueries['get_data_posisis'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE posisiCode LIKE '%$uniFind%' OR posisiName LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE posisiId = '$byId' OR posisiCode = '$byId'";
         $query  = str_replace('-- byId --', $byId, $query);
      }            
      $result = $this->Open($query, array());
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result[0]['totalData'];
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