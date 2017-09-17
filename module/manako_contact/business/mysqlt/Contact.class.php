<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Contact extends Database {

   protected $mSqlFile= 'module/manako_contact/business/mysqlt/contact.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataContact($name, $client, $posisi) {
      
      $result = $this->Open($this->mSqlQueries['get_data_contact'], array('%'.$name.'%','%'.$client.'%','%'.$posisi.'%'));
      return $result;
   }

   function GetDataContactById($id) {
      $result = $this->Open($this->mSqlQueries['get_data_contact_by_id'], array($id));
      return $result;
   }

   function GetDataPosisi() {
      $result  = $this->Open($this->mSqlQueries['get_data_posisi'], array());
      return $result;
   }

   function GetDataClientAktif() {
      $result  = $this->Open($this->mSqlQueries['get_data_client_aktif'], array());
      return $result;
   }

   function GetDataClientAktifByNick($nick) {
      $result  = $this->Open($this->mSqlQueries['get_data_client_aktif_by_nick'], array($nick));
      return $result;
   }

   function GetDataContactField() {
      $result  = $this->Open($this->mSqlQueries['get_data_contact_field'], array());
      return $result;
   }

   function GetDataContactFieldByCode($code) {
      $result  = $this->Open($this->mSqlQueries['get_data_contact_field_by_code'], array($code));
      return $result;
   }

//===CREATE===
   function DoAddContact($first, $last, $mail, $mobile, $client, $posisi, $posisiDet) {
      $result = $this->Execute($this->mSqlQueries['do_add_contact'], array($first, $last, $mail, $mobile, $client, $posisi, $posisiDet));
      return $result;
   }
   function DoAddContactDet($contact, $field, $value) {
      $result = $this->Execute($this->mSqlQueries['do_add_contact_det'], array($contact, $field, $value));
      return $result;
   }
   function DoDelContactDet($contactId) {
      $result = $this->Execute($this->mSqlQueries['do_del_contact_det'], array($contactId));
      return $result;
   }
//===UPDATE===
   function DoUpdateContact($first, $last, $mail, $mobile, $client, $posisi, $posisiDet, $updateTime, $updateUser, $id) {
      $result = $this->Execute($this->mSqlQueries['do_update_contact'], array($first, $last, $mail, $mobile, $client, $posisi, $posisiDet, $updateTime, $updateUser, $id));
      return $result;
   }

//===DELETE===   
   function DoDeleteContact($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_contact'], array($id));
      return $result;
   }

   function GetMaxId() {
      $result  = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $result;
   }
}