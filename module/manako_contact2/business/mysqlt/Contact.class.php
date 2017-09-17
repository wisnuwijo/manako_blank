<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Contact extends Database {

   protected $mSqlFile= 'module/manako_contact2/business/mysqlt/contact.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
//===READ===  
   function GetDataContacts($uniFind='', $byId=null, $start=null, $display=null, $withDetail=TRUE, $name='', $client='', $posisi='') {
      $query  = $this->mSqlQueries['get_data_contacts'];
      $limit = '';
      $finds = '';
      if ($display != ''){
         $limit = "LIMIT $start, $display";
         $query  = str_replace('-- limit --', $limit, $query);
      }
      if ($uniFind != '') {
         $finds = "WHERE contactNameFirst LIKE '%$uniFind%' OR contactNameLast LIKE '%$uniFind%'";
         $query  = str_replace('-- finds --', $finds, $query);
      }
      if ($byId != '') {
         $byId = "WHERE contactId = '$byId'";
         $query  = str_replace('-- byId --', $byId, $query);
      }      
      if ($withDetail == TRUE) {
         $detailField = $this->mSqlQueries['contact_detail_field'];
         $query  = str_replace('-- detailField --', $detailField, $query);

         $detailJoin = $this->mSqlQueries['contact_detail_join'];
         $query  = str_replace('-- detailJoin --', $detailJoin, $query);
      }
      
      $result = $this->Open($query, array(/*'%'.$name.'%','%'.$client.'%','%'.$posisi.'%'*/));
      return $result;
   }

   function GetTotalData() {
      $result = $this->Open($this->mSqlQueries['get_total_data'], array());
      return $result;
   }

   // function GetDataContactById($id) {
   //    $result = $this->Open($this->mSqlQueries['get_data_contact_by_id'], array($id));
   //    return $result;
   // }

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
?>