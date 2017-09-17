<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_personal/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Personal.class.php';

class ProcessPersonal {

   
   var $_POST;
   var $personalObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->personalObj = new Personal();

      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('manako_personal', 'personal', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('manako_personal', 'inputPersonal', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_personal', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      $cek_name = $this->personalObj->GetCountDuplicateNameAdd($_POST['personalName']);
      if ($cek_name[0]['COUNT'] != 0) {
         $this->SendAlert("Nama personal ".$_POST['personalName']." sudah ada.", 'inputPersonal', $this->cssFail);
         return $this->pageInput;
      } else {
         
         $addPersonal = $this->personalObj->DoAddPersonal($_POST['personalName']);

         $processData = $addPersonal;
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'personal', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputPersonal', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      $cek_name = $this->personalObj->GetCountDuplicateName($_POST['personalName'], $_POST['idd']);
      if ($cek_name[0]['COUNT'] != 0) {
         $this->SendAlert("Nama personal ".$_POST['personalName']." sudah ada.", 'inputPersonal', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updatePersonal = $this->personalObj->DoUpdatePersonal($_POST['personalName'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $processData = $updatePersonal;
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'personal', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputPersonal', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      $deletePersonal = $this->personalObj->DoDeletePersonal($_POST['idDelete']);

      $processData = $deletePersonal;
         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'personal', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'personal', $this->cssFail);
      }
      return $this->pageView;
   }

}
?>
