<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppVarian extends Database {

   protected $mSqlFile= 'module/manako_app_varian/business/mysqlt/app_varian.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataAppVarians($uniFind, $byNick, $start, $display) {
      $query  = $this->mSqlQueries['get_data_app_varians'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit  = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE varianName LIKE '%$uniFind%' OR varianNick LIKE '%$uniFind%' OR varianInitial LIKE '%$uniFind%' OR varianLogoIcon LIKE '%$uniFind%' OR varianLogoType LIKE '%$uniFind%' OR varianLogoFull LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byNick != '') {
         $byNick = "WHERE varianNick = '$byNick'";
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
   function DoAddAppVarian($name, $nick, $init, $icon, $type, $full) {
      $result = $this->Execute($this->mSqlQueries['do_add_app_varian'], array($name, $nick, $init, $icon, $type, $full));
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppVarian($name, $nick, $init, $icon, $type, $full, $UpdateTime, $UpdateUser, $currentNick) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_varian'], array($name, $nick, $init, $icon, $type, $full, $UpdateTime, $UpdateUser, $currentNick));
      return $result;
   }
   function GetCountDuplicateNick($nick, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteAppVarian($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app_varian'], array($id));
      return $result;
   }
}