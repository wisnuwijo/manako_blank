<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Gforge extends Database {

   protected $mSqlFile= 'module/manako_gforge/business/mysqlt/gforge.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataGforge($username) {
      
      $result = $this->Open($this->mSqlQueries['get_data_gforge'], array('%'.$username.'%'));
      return $result;
   }

   function GetDataGforgeById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_gforge_by_id'], array($id));
      return $result;
   }
//===CREATE=== 
   function DoAddGforge($username) {
      $result = $this->Execute($this->mSqlQueries['do_add_gforge'], array($username));
      return $result;
   }
   function GetCountDuplicateNicknameAdd($username) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nickname_add'], array($username));
      return $result;
   }
//===UPDATE=== 
   function DoUpdateGforge($username, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_gforge'], array($username, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateNickname($username, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_nickname'], array($username, $id));
      return $result;
   }
//===DELETE=== 
   function DoDeleteGforge($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_gforge'], array($id));
      return $result;
   }
   function DoDelRel($gforgeId) {
      $result = $this->Execute($this->mSqlQueries['do_del_rel'], array($gforgeId));
      return $result;
   }
}