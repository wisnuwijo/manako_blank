<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppReq extends Database {

   protected $mSqlFile= 'module/manako_app_req/business/mysqlt/app_req.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataAppReqs($uniFind, $byId, $start, $display) {
      $query  = $this->mSqlQueries['get_data_reqs'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE appName LIKE '%$uniFind%' OR reqsoftNick LIKE '%$uniFind%' OR reqOperandi LIKE '%$uniFind%' OR reqValue LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE reqId = '$byId'";
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
   function DoAddAppReq($reqAppId, $reqAppReqsoftId, $reqOperandi, $reqValue) {
      $result = $this->Execute($this->mSqlQueries['do_add_req'], array($reqAppId, $reqAppReqsoftId, $reqOperandi, $reqValue));
      return $result;
   }
   function GetCountDuplicateReqAdd($reqAppReqsoftId, $reqAppId) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_req_add'], array($reqAppReqsoftId, $reqAppId));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppReq($reqAppId, $reqAppReqsoftId, $reqOperandi, $reqValue, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_req'], array($reqAppId, $reqAppReqsoftId, $reqOperandi, $reqValue, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateReq($reqAppReqsoftId, $reqAppId, $identifier) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_req'], array($reqAppReqsoftId, $reqAppId, $identifier));
      return $result;
   }
//===DELETE===
   function DoDeleteAppReq($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_req'], array($id));
      return $result;
   }
}