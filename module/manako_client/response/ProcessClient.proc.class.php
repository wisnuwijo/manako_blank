<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Client.class.php';

class ProcessClient {

   
   var $_POST;
   var $clientObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->clientObj = new Client();
      
      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('manako_client', 'client', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('manako_client', 'inputClient', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_client', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {
      //var_dump($_POST);exit;
      $cek_nick = $this->clientObj->GetCountDuplicateNickAdd($_POST['clientNick']);
      if ($cek_nick[0]['COUNT'] != 0) {
         $this->SendAlert("Nick client ".$_POST['clientNick']." sudah ada.", 'inputClient', $this->cssFail);
         return $this->pageInput;
      } else {
         
         $addClient = $this->clientObj->DoAddClient($_POST['clientClientCatId'], $_POST['clientNick'], $_POST['clientName'], $_POST['clientKotaKode'], $_POST['clientStatus']);
         $lastInsertedId = $this->clientObj->GetMaxId();
         $insertedClientId = $lastInsertedId[0]['max_id'];
         
         $processData = $addClient;

         if (isset($_POST['gforgeNickname'])) {
            foreach ($_POST['gforgeNickname'] as $gforgeId) {
               $addRel = $this->clientObj->DoAddRel($insertedClientId, $gforgeId);
            }
            $processData = $addClient && $addRel;
         }
         
         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'client', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputClient', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      //var_dump($_POST);exit;
      $cek_nick = $this->clientObj->GetCountDuplicateNick($_POST['clientNick'], $_POST['idd']);
      if ($cek_nick[0]['COUNT'] != 0) {
         $this->SendAlert("Nick client ".$_POST['clientNick']." sudah ada.", 'inputClient', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updateClient = $this->clientObj->DoUpdateClient($_POST['clientClientCatId'], $_POST['clientNick'], $_POST['clientName'], $_POST['clientKotaKode'], $_POST['clientStatus'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $delRel = $this->clientObj->DoDelRel($_POST['idd']);
         
         $processData = $updateClient && $delRel;

         if (!empty($_POST['gforgeNickname'])) {
            foreach ($_POST['gforgeNickname'] as $gforgeId) {
               $addRel = $this->clientObj->DoAddRel($_POST['idd'], $gforgeId);
            }
            $processData = $processData && $addRel;
         }
         
         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'client', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputClient', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      //print_r(var_dump($_POST));exit; 
      $deleteClient = $this->clientObj->DoDeleteClient($_POST['idDelete']);

      $delRel = $this->clientObj->DoDelRel($_POST['idDelete']);

      $processData = $deleteClient && $delRel;

         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'client', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'client', $this->cssFail);
      }

      return $this->pageView;
   }

   function StatusChange() {
      //print_r(var_dump($_GET));exit; 
      $statusRule = $_GET['rules'];
      if ($statusRule == '1') {
         $statusCurrent = 'Aktif';
      } else {
         $statusCurrent = 'Non-aktif';
      }

      $statusChange = $this->clientObj->DoStatusChange($statusRule,$_GET['idd']);

      $processData = $statusChange;
         
      if ($processData == true) {
         $this->SendAlert('Perubahan status berhasil, Client "'.$_GET['clientName'] .'" ' .$statusCurrent, 'client', $this->cssDone);
      } else {
         $this->SendAlert('Perubahan status gagal', 'client', $this->cssFail);
      }

      return $this->pageView;
   }

}
?>
