<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppProduct extends Database {

   protected $mSqlFile= 'module/manako_app_product/business/mysqlt/app_product.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataAppProducts($byId='', $uniFind='', $start='', $display='') {
      $query  = $this->mSqlQueries['get_data_app_products'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE productName LIKE '%$uniFind%' OR productNick LIKE '%$uniFind%' OR productInitial LIKE '%$uniFind%' OR productLogoIcon LIKE '%$uniFind%' OR productLogoType LIKE '%$uniFind%' OR productLogoFull LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE productNick = '$byId' OR productId = '$byId'";
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
   function DoAddAppProduct($name, $nick, $init, $icon, $type, $full) {
      $result = $this->Execute($this->mSqlQueries['do_add_app_product'], array($name, $nick, $init, $icon, $type, $full));
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppProduct($name, $nick, $init, $icon, $type, $full, $UpdateTime, $UpdateUser, $identifier) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_product'], array($name, $nick, $init, $icon, $type, $full, $UpdateTime, $UpdateUser, $identifier));
      return $result;
   }
   function GetCountDuplicateNick($nick, $identifier) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $identifier));
      return $result;
   }
//===DELETE===
   function DoDeleteAppProduct($identifier) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app_product'], array($identifier));
      return $result;
   }
}