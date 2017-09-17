<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_gforge/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Gforge.class.php';

class ProcessGforge {

   
   var $_POST;
   var $gforgeObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;

   function __construct() {
      $this->gforgeObj = new Gforge();
      
      $this->_POST = $_POST->AsArray();

      $this->pageView = Dispatcher::Instance()->GetUrl('manako_gforge', 'gforge', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('manako_gforge', 'inputGforge', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_gforge', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      $cek_username = $this->gforgeObj->GetCountDuplicateNicknameAdd($_POST['gforgeNickname']);
      if ($cek_username[0]['COUNT'] != 0) {
         $this->SendAlert("Nickname gforge ".$_POST['gforgeNickname']." sudah ada.", 'inputGforge', $this->cssFail);
         return $this->pageInput;
      } else {
         
         $addGforge = $this->gforgeObj->DoAddGforge($_POST['gforgeNickname']);

         $processData = $addGforge;
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'gforge', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputGforge', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      $cek_username = $this->gforgeObj->GetCountDuplicateNickname($_POST['gforgeNickname'], $_POST['idd']);
      if ($cek_username[0]['COUNT'] != 0) {
         $this->SendAlert("Nickname gforge ".$_POST['gforgeNickname']." sudah ada.", 'inputGforge', $this->cssFail);
         return $this->pageInput;
      } else {
         echo $UpdateTime    = date('Y-m-d H:i:s');
         echo $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updateGforge = $this->gforgeObj->DoUpdateGforge($_POST['gforgeNickname'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $processData = $updateGforge;
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'gforge', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputGforge', $this->cssFail);
            return $this->pageInput;
         }

         return $this->pageView;
      }
   }

   function Delete() {       
      //print_r(var_dump($_POST));exit; 
      $deleteGforge = $this->gforgeObj->DoDeleteGforge($_POST['idDelete']);

      $delRel = $this->gforgeObj->DoDelRel($_POST['idDelete']);
      
      $processData = $deleteGforge && $delRel;
         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'gforge', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'gforge', $this->cssFail);
      }

      return $this->pageView;
   }

}
?>
