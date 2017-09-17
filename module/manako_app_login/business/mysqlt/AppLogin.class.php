<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class AppLogin extends Database {

   protected $mSqlFile= 'module/manako_app_login/business/mysqlt/app_login.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }

//===READ===
   function GetDataAppLogins($group=null, $byId=null, $uniFind='', $start=null, $display=null) {
      $query  = $this->mSqlQueries['get_data_app_logins'];
      $limit = '';
      $finds = '';
      if ($group != '') {
         $qgroup = "WHERE groupProjectAppGroupId = '$group'";
         $query  = str_replace('-- group --', $qgroup, $query);
      }
      if ($start != '' && $display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "AND (clientName LIKE '%$uniFind%' OR lokasiserverName LIKE '%$uniFind%' OR appName LIKE '%$uniFind%' OR loginUrl LIKE '%$uniFind%' OR loginroleName LIKE '%$uniFind%' OR loginUser LIKE '%$uniFind%' OR loginNote LIKE '%$uniFind%')";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "AND loginId = '$byId'";
         $query  = str_replace('-- byId --', $byId, $query);
      }
      $result = $this->Open($query, array());
      return $result;
   }
   function GetDataAppLoginPswd($id) {
      $result = $this->Open($this->mSqlQueries['get_data_app_login_pswd'], array($id));
      return $result;
   }
   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result;
   }
//===CREATE===
   function DoAddAppLogin($loginClientId, $loginLokasiserverId, $loginAppId, $loginUrl, $loginLoginroleId, $loginUser, $loginPswd, $loginNote) {
      $result = $this->Execute($this->mSqlQueries['do_add_app_login'], array($loginClientId, $loginLokasiserverId, $loginAppId, $loginUrl, $loginLoginroleId, $loginUser, $loginPswd, $loginNote));
      return $result;
   }
   function GetCountDuplicateAdd($loginClientId, $loginLokasiserverId, $loginAppId, $loginLoginroleId, $loginUser) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_add'], array($loginClientId, $loginLokasiserverId, $loginAppId, $loginLoginroleId, $loginUser));
      return $result;
   }
//===UPDATE===
   function DoUpdateAppLogin($loginClientId, $loginLokasiserverId, $loginAppId, $loginUrl, $loginLoginroleId, $loginUser, $loginNote, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_login'], array($loginClientId, $loginLokasiserverId, $loginAppId, $loginUrl, $loginLoginroleId, $loginUser, $loginNote, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicate($loginClientId, $loginLokasiserverId, $loginAppId, $loginLoginroleId, $loginUser, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate'], array($loginClientId, $loginLokasiserverId, $loginAppId, $loginLoginroleId, $loginUser, $id));
      return $result;
   }
   function DoChangePswdAppLogin($loginPswd, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_change_pswd_app_login'], array($loginPswd, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteAppLogin($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_app_login'], array($id));
      return $result;
   }
}
