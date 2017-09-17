<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppReqsoft extends Database {

   protected $mSqlFile= 'module/manako_app_reqsoft/business/mysqlt/app_reqsoft.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataAppReqsofts($uniFind, $byNick, $start, $display) {
      $query  = $this->mSqlQueries['get_data_reqsofts'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE reqsoftNick LIKE '%$uniFind%' OR reqsoftDesc LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byNick != '') {
         $byNick = "WHERE reqsoftNick = '$byNick'";
         $query  = str_replace('-- byNick --', $byNick, $query);
      }      
      $result = $this->Open($query, array());
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result;
   }   
//===CREATE===
   function DoAddAppReqsoft($reqsoftNick, $reqsoftDesc) {
      $result = $this->Execute($this->mSqlQueries['do_add_reqsoft'], array($reqsoftNick, $reqsoftDesc));
      return $result;
   }
   function GetCountDuplicateNickAdd($identifier) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($identifier));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppReqsoft($reqsoftNick, $reqsoftDesc, $UpdateTime, $UpdateUser, $identifier) {
      $result = $this->Execute($this->mSqlQueries['do_update_reqsoft'], array($reqsoftNick, $reqsoftDesc, $UpdateTime, $UpdateUser, $identifier));
      return $result;
   }
   function GetCountDuplicateNick($reqsoftNick, $identifier) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($reqsoftNick, $identifier));
      return $result;
   }
//===DELETE===
   function DoDeleteAppReqsoft($identifier) {
      $result = $this->Execute($this->mSqlQueries['do_delete_reqsoft'], array($identifier));
      return $result;
   }
}