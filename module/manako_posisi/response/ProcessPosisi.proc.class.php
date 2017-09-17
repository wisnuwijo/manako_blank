<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Posisi.class.php';

class ProcessPosisi {

   
   var $_POST;
   var $posisiObj;
   var $pageView;
   var $pageInput;
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->posisiObj  = new Posisi();
      
      $this->_POST      = $_POST->AsArray();
      $this->pageView   = Dispatcher::Instance()->GetUrl('manako_posisi', 'posisi', 'view', 'html');
      $this->pageInput  = Dispatcher::Instance()->GetUrl('manako_posisi', 'inputPosisi', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_posisi', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      $cekCode = $this->posisiObj->GetCountDuplicateCodeAdd($_POST['posisiCode']);
      if ($cekCode[0]['COUNT'] != 0) {
         $this->SendAlert('Code posisi "'.$_POST['posisiCode'].'" sudah ada.', 'inputPosisi', $this->cssFail);
         return $this->pageInput;
      } else {         
         $addPosisi    = $this->posisiObj->DoAddPosisi($_POST['posisiCode'],$_POST['posisiName']);

         $processData  = $addPosisi;
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'posisi', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputPosisi', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      $cekCode = $this->posisiObj->GetCountDuplicateCode($_POST['posisiCode'], $_POST['idd']);
      if ($cekCode[0]['COUNT'] != 0) {
         $this->SendAlert('Code posisi "'.$_POST['posisiCode'].'" sudah ada.', 'inputPosisi', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updatePosisi  = $this->posisiObj->DoUpdatePosisi($_POST['posisiCode'], $_POST['posisiName'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $processData   = $updatePosisi;
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'posisi', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputPosisi', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      $deletePosisi  = $this->posisiObj->DoDeletePosisi($_POST['idDelete']);

      $processData   = $deletePosisi;
         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'posisi', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'posisi', $this->cssFail);
      }
      return $this->pageView;
   }

}
?>
