<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class ContactField extends Database {

   protected $mSqlFile= 'module/manako_contact_field/business/mysqlt/contact_field.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataContactField($label) {
      
      $result = $this->Open($this->mSqlQueries['get_data_contact_field'], array('%'.$label.'%'));
      return $result;
   }
   function GetDataContactFieldById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_contact_field_by_id'], array($id));
      return $result;
   }
//===CREATE===
   function DoAddContactField($code,$label,$type,$icon) {
      $result = $this->Execute($this->mSqlQueries['do_add_contact_field'], array($code,$label,$type,$icon));
      return $result;
   }
   function GetCountDuplicateCodeAdd($code) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_code_add'], array($code));
      return $result;
   }
//===UPDATE===
   function DoUpdateContactField($code, $label, $type, $icon, $UpdateTime, $UpdateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_contact_field'], array($code, $label, $type, $icon, $UpdateTime, $UpdateUser, $id));
      return $result;
   }
   function GetCountDuplicateCode($code, $id) {
      $result  = $this->Open($this->mSqlQueries['get_count_duplicate_code'], array($code, $id));
      return $result;
   }
//===DELETE===
   function DoDeleteContactField($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_contact_field'], array($id));
      return $result;
   }
}