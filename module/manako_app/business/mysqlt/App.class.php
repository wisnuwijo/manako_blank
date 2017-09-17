<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class App extends Database {

   protected $mSqlFile= 'module/manako_app/business/mysqlt/app.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===READ===
   function GetDataApps($group=null, $byNick=null, $uniFind='', $start=null, $display=null) {
      $query  = $this->mSqlQueries['get_data_apps'];
      $limit = '';
      $finds = '';
      if ($group != '') {
         $qgroup = "LEFT JOIN gtfw_group_project_app
                     ON appId = groupProjectAppAppId
                     WHERE groupProjectAppGroupId = '$group' ";
         $query  = str_replace('-- group --', $qgroup, $query);
      }
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "AND (appName LIKE '%$uniFind%' OR productName LIKE '%$uniFind%' OR varianName LIKE '%$uniFind%' OR appDirInstall LIKE '%$uniFind%' OR appPathDev LIKE '%$uniFind%' OR appPathDocRepo LIKE '%$uniFind%' OR appPathDocFile LIKE '%$uniFind%') ";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byNick != '') {
         $byNick = "AND appNick = '$byNick'";
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
   function DoAddApp($appProductId, $appVarianId, $appName, $appNick, $appDirInstall, $appPathDev, $appPathDocRepo, $appPathDocFile) {
      $result = $this->Execute($this->mSqlQueries['do_add_app'], array($appProductId, $appVarianId, $appName, $appNick, $appDirInstall, $appPathDev, $appPathDocRepo, $appPathDocFile));
      return $result;
   }
   function GetCountDuplicateNickAdd($nick) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick_add'], array($nick));
      return $result;
   }
//===UPDATE===
   function DoUpdateApp($appProductId, $appVarianId, $appName, $appNick, $appDirInstall, $appPathDev, $appPathDocRepo, $appPathDocFile, $updateTime, $updateUser, $nick) {
      $result = $this->Execute($this->mSqlQueries['do_update_app'], array($appProductId, $appVarianId, $appName, $appNick, $appDirInstall, $appPathDev, $appPathDocRepo, $appPathDocFile, $updateTime, $updateUser, $nick));
      return $result;
   }
   function GetCountDuplicateNick($nick, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nick'], array($nick, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteApp($nick) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app'], array($nick));
      return $result;
   }
}
