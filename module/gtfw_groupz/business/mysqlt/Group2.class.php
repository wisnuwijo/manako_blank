<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Group2 extends Database {

   protected $mSqlFile= 'module/gtfw_groupz/business/mysqlt/group2.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===READ===
   function GetDataGroup2($byId='', $uniFind='', $start='', $display='') {
      $query  = $this->mSqlQueries['get_data_group2'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE GroupName LIKE '%$uniFind%' OR Description LIKE '%$uniFind%' OR UnitName LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
         $connector = 'AND ';
      } else {
         $connector = 'WHERE ';
      }
      if ($byId != '') {
         $id = $connector."g.GroupId = '$byId'";
         $query  = str_replace('-- byId --', $id, $query);
      }
      $result = $this->Open($query, array());
      return $result;
   }
   function GetDataGroupModul($menuLevel='submenu', $byId='') {
      if ($menuLevel == '' || $menuLevel == 'submenu' || $menuLevel == 'menu') {
         $query     = $this->mSqlQueries['get_data_group_modul_submenu'];
         if ($menuLevel == 'menu') {
            $query  = $this->mSqlQueries['get_data_group_modul_menu'];
         }
         if ($byId != '') {
            $byId   = "WHERE g.GroupId = '$byId'";
            $query  = str_replace('-- byId --', $byId, $query);
         }
         $result = $this->Open($query, array());
      } else {
         $result = false;
      }
      return $result;
   }
   function GetDataRegisteredModul($menuLevel='submenu') {
      if ($menuLevel == '' || $menuLevel == 'submenu' || $menuLevel == 'menu') {
         $query     = $this->mSqlQueries['get_data_registered_modul_submenu'];
         if ($menuLevel == 'menu') {
            $query  = $this->mSqlQueries['get_data_registered_modul_menu'];
         }
         $result = $this->Open($query, array());
      } else {
         $result = false;
      }
      return $result;
   }
   function GetDataGroupApp2($byIdentifier='') {
      $query  = $this->mSqlQueries['get_group_app'];
      $result = $this->Open($query, array($byIdentifier));
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result[0]['totalData'];
   }
//===CREATE===

//===UPDATE===
   function DoUpdateGroupAppClear($identifier) {
      $result = $this->Execute($this->mSqlQueries['do_update_group_app_clear'], array($identifier));
      return $result;
   }
   function DoUpdateGroupAppAdd($identifier, $app) {
      $result = $this->Execute($this->mSqlQueries['do_update_group_app_add'], array($identifier, $app));
      return $result;
   }
//===DELETE===

}
