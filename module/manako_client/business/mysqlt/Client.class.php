<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Client extends Database {

   protected $mSqlFile= 'module/manako_client/business/mysqlt/client.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataClient($clientName, $clientNick, $kota, $prov) {
      $result = $this->Open($this->mSqlQueries['get_data_client'], array('%'.$clientName.'%','%'.$clientNick.'%','%'.$kota.'%','%'.$prov.'%'));
      return $result;
   }

   function GetDataClientById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_client_by_id'], array($id));
      return $result;
   }

   function GetUnrelGforge() {
      $result  = $this->Open($this->mSqlQueries['get_unrel_gforge'], array());
      return $result;
   }

   function GetClientKategori() {
      $result  = $this->Open($this->mSqlQueries['get_client_kategori'], array());
      return $result;
   }

   function GetProv() {
      $result  = $this->Open($this->mSqlQueries['get_prov'], array());
      return $result;
   }

   function GetKota() {
      $result  = $this->Open($this->mSqlQueries['get_kota'], array());
      return $result;
   }

   function GetKotaByProv($prov) {
      $result  = $this->Open($this->mSqlQueries['get_kota_by_prov'], array($prov));
      return $result;
   }

//===CREATE===
   function DoAddClient($kat, $nick, $name, $kota, $stats) {
      $result = $this->Execute($this->mSqlQueries['do_add_client'], array($kat, $nick, $name, $kota, $stats));
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }
   function DoAddRel($ac, $gf) {
      $result = $this->Execute($this->mSqlQueries['do_add_rel'], array($ac, $gf));
      return $result;
   }
   function DoDelRel($clientId) {
      $result = $this->Execute($this->mSqlQueries['do_del_rel'], array($clientId));
      return $result;
   }
//===UPDATE===
   function DoUpdateClient($kat, $nick, $name, $kota, $stats, $updateTime, $updateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_client'], array($kat, $nick, $name, $kota, $stats, $updateTime, $updateUser, $id));
      return $result;
   }
   function GetCountDuplicateNick($nick, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $id));
      return $result;
   }

   function DoStatusChange($rule,$id) {
      $result = $this->Execute($this->mSqlQueries['do_status_change'], array($rule,$id));
      return $result;
   }
//===DELETE===   
   function DoDeleteClient($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_client'], array($id));
      return $result;
   }

   function GetMaxId() {
      $result  = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $result;
   }
}