<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_bisnis/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Bisnis.class.php';

class ProcessBisnis {

   
   var $_POST;
   var $bisnisObj;
   var $pageView;
   var $pageInput;
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->bisnisObj  = new Bisnis();
      
      $this->_POST      = $_POST->AsArray();
      $this->pageView   = Dispatcher::Instance()->GetUrl('manako_bisnis', 'bisnis', 'view', 'html');
      $this->pageInput  = Dispatcher::Instance()->GetUrl('manako_bisnis', 'inputBisnis', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_bisnis', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      $cekModel = $this->bisnisObj->GetCountDuplicateModelAdd($_POST['bisnisModel']);
      if ($cekModel[0]['COUNT'] != 0) {
         $this->SendAlert('Model bisnis '.$_POST['bisnisModel'].' sudah ada.', 'inputBisnis', $this->cssFail);
         return $this->pageInput;
      } else {         
         $addBisnis    = $this->bisnisObj->DoAddBisnis($_POST['bisnisModel']);

         $processData  = $addBisnis;
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'bisnis', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputBisnis', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      $cekModel = $this->bisnisObj->GetCountDuplicateModel($_POST['bisnisModel'], $_POST['idd']);
      if ($cekModel[0]['COUNT'] != 0) {
         $this->SendAlert('Model bisnis '.$_POST['bisnisModel'].' sudah ada.', 'inputBisnis', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updateBisnis  = $this->bisnisObj->DoUpdateBisnis($_POST['bisnisModel'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $processData   = $updateBisnis;
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'bisnis', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputBisnis', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      $deleteBisnis  = $this->bisnisObj->DoDeleteBisnis($_POST['idDelete']);

      $processData   = $deleteBisnis;
         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'bisnis', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'bisnis', $this->cssFail);
      }
      return $this->pageView;
   }

}
?>
