<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class ProjectAktif1 extends Database {

   protected $mSqlFile= 'module/manako_projectaktif1/business/mysqlt/projectAktif1.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===GET===
   function GetDataProject($pr, $pete, $dokpro, $tahun) {

      $result = $this->Open($this->mSqlQueries['get_data_project'], array('%'.$pr.'%','%'.$pete.'%','%'.$dokpro.'%', $tahun));
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
