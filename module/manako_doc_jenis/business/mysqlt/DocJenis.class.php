<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class DocJenis extends Database {

   protected $mSqlFile= 'module/manako_doc_jenis/business/mysqlt/doc_jenis.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataDocJenises($uniFind, $byNick, $start, $display) {
      $query  = $this->mSqlQueries['get_data_doc_jenises'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE docJenisName LIKE '%$uniFind%' OR docJenisNick LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byNick != '') {
         $byNick = "WHERE docJenisNick = '$byNick'";
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
   function DoAddDocJenis($nick, $name) {
      $result = $this->Execute($this->mSqlQueries['do_add_doc_jenis'], array($nick, $name));
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }
//===UPDATE===
   function DoUpdateDocJenis($nick, $name, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_doc_jenis'], array($nick, $name, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateNick($nick, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteDocJenis($nick) {
      $result = $this->Execute($this->mSqlQueries['do_delete_doc_jenis'], array($nick));
      return $result;
   }
}