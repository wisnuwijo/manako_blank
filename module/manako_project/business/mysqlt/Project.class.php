<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Project extends Database {

   protected $mSqlFile= 'module/manako_project/business/mysqlt/project.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===GET===
   function GetDataProject($pr, $pete, $dokpro, $tahun) {

      $result = $this->Open($this->mSqlQueries['get_data_project'], array('%'.$pr.'%','%'.$pete.'%','%'.$dokpro.'%', $tahun));
      return $result;
   }

   function GetDataProjectById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_project_by_id'], array($id));
      return $result;
   }

   function DoAddProject($client_pt,$client_dokpro,$client_dokcon,$nick,$name,$des,$con_start,$con_end,$mtc_end,$model,$order,$am_id,$date_bast,$toleran_start,$toleran_end,$toleran_use,$note) {
      $result = $this->Execute($this->mSqlQueries['do_add_project'], array($client_pt,$client_dokpro,$client_dokcon,$nick,$name,$des,$con_start,$con_end,$mtc_end,$model,$order,$am_id,$date_bast,$toleran_start,$toleran_end,$toleran_use,$note));
      echo $this->GetLastError();
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }

   function DoUpdateProject($client_pt,$client_dokpro,$client_dokcon,$nick,$name,$des,$con_start,$con_end,$mtc_end,$model,$order,$am_id,$date_bast,$toleran_start,$toleran_end,$toleran_use,$note,$updateTime,$updateUser,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_project'], array($client_pt,$client_dokpro,$client_dokcon,$nick,$name,$des,$con_start,$con_end,$mtc_end,$model,$order,$am_id,$date_bast,$toleran_start,$toleran_end,$toleran_use,$note,$updateTime,$updateUser,$id));
      return $result;
   }
   function GetCountDuplicateNick($nick, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $id));
      return $result;
   }

   function DoDeleteProject($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_project'], array($id));
      return $result;
   }

   function GetMaxId() {
      $result  = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $result;
   }

   function GetListClientActive() {
      $result  = $this->Open($this->mSqlQueries['get_list_client_active'], array());
      return $result;
   }
   function GetListClientActiveOnly($cat) {
      $result  = $this->Open($this->mSqlQueries['get_list_client_active_only'], array($cat));
      return $result;
   }
   function GetListClientActiveXcpt($cat) {
      $result  = $this->Open($this->mSqlQueries['get_list_client_active_xcpt'], array($cat));
      return $result;
   }
   function GetListBisnis() {
      $result  = $this->Open($this->mSqlQueries['get_list_bisnis'], array());
      return $result;
   }
   function GetListPersonal() {
      $result  = $this->Open($this->mSqlQueries['get_list_personal'], array());
      return $result;
   }
}
