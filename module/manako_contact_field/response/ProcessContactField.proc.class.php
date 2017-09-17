<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact_field/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/ContactField.class.php';

class ProcessContactField {

   
   var $_POST;
   var $contactFieldObj;
   var $pageView;
   var $pageInput;
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->contactFieldObj  = new ContactField();
      
      $this->_POST      = $_POST->AsArray();
      $this->pageView   = Dispatcher::Instance()->GetUrl('manako_contact_field', 'contactField', 'view', 'html');
      $this->pageInput  = Dispatcher::Instance()->GetUrl('manako_contact_field', 'inputContactField', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_contact_field', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      $cekCode = $this->contactFieldObj->GetCountDuplicateCodeAdd($_POST['contactFieldCode']);
      if ($cekCode[0]['COUNT'] != 0) {
         $this->SendAlert('Code contactField "'.$_POST['contactFieldCode'].'" sudah ada.', 'inputContactField', $this->cssFail);
         return $this->pageInput;
      } else {         
         $addContactField    = $this->contactFieldObj->DoAddContactField($_POST['contactFieldCode'],$_POST['contactFieldLabel'],$_POST['contactFieldType'],$_POST['contactFieldIcon']);

         $processData  = $addContactField;
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'contactField', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputContactField', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      #var_dump($_POST);exit;
      $cekCode = $this->contactFieldObj->GetCountDuplicateCode($_POST['contactFieldCode'], $_POST['idd']);
      if ($cekCode[0]['COUNT'] != 0) {
         $this->SendAlert('Code contactField "'.$_POST['contactFieldCode'].'" sudah ada.', 'inputContactField', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updateContactField  = $this->contactFieldObj->DoUpdateContactField($_POST['contactFieldCode'], $_POST['contactFieldLabel'], $_POST['contactFieldType'], $_POST['contactFieldIcon'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $processData   = $updateContactField;
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'contactField', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputContactField', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      $deleteContactField  = $this->contactFieldObj->DoDeleteContactField($_POST['idDelete']);

      $processData   = $deleteContactField;
         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'contactField', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'contactField', $this->cssFail);
      }
      return $this->pageView;
   }

}
?>
