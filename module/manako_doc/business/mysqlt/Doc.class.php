<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Doc extends Database {

   protected $mSqlFile= 'module/manako_doc/business/mysqlt/doc.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataDocs($uniFind, $start, $display) {
      $query  = $this->mSqlQueries['get_data_docs'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE appName LIKE '%$uniFind%' OR docJenisNick LIKE '%$uniFind%' OR docJenisName LIKE '%$uniFind%' OR docUrl LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      $result = $this->Open($query, array());
      return $result;
   }
   function GetDataDoc($byId) {
      $result = $this->Open($this->mSqlQueries['get_data_doc'], array($byId));
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result;
   }   
//===CREATE===
   function DoAddDoc($appId, $jenisId, $url) {
      $result = $this->Execute($this->mSqlQueries['do_add_doc'], array($appId, $jenisId, $url));
      return $result;
   }
   function GetCountDuplicateUrlAdd($url) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_url_add'], array($url));
      return $result;
   }
//===UPDATE===
   function DoUpdateDoc($appId, $jenisId, $url, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_doc'], array($appId, $jenisId, $url, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateUrl($url, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_url'], array($url, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteDoc($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_doc'], array($id));
      return $result;
   }
}