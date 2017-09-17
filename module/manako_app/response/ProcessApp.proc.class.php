<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/App.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_groupz/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Group2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ProcessApp {
   var $appObj;

   var $_POST;
   var $_GET;

   function __construct() {
      $this->appObj     = new App();
      $this->group      = new Group2();

      $this->post       = Array();
      $this->get        = Array();
      $this->post       = $_POST->AsArray();
      $this->get        = $_GET->AsArray();

      $this->error      = Array();
      $this->return     = Array();

      $this->urlSegment = urlHelper::Instance()->segments($_SERVER['REQUEST_URI'], Configuration::Instance()->GetValue('application', 'basedir'));
      if (isset($this->urlSegment[1])) {
         $this->identifier    = $this->urlSegment[1];
      } elseif (isset($this->get['identifier'])) {
         $this->identifier    = $this->get['identifier'];
      } else {
         $this->identifier    = null;
      }

      $this->importantParams = array('appNick', 'appProductId', 'appVarianId', 'appName', 'appNick');
   }

   function AreSet($needed=array()) {
      $received = array_keys($this->post);
      $diff     = array_diff($needed, $received);
      if (count($diff) == 0){
         return TRUE;
      }
   }

   function AreEmpty($fields=array()) {
      foreach ($fields as $fields) {
         if (empty($this->post[$fields])) {
            $this->error[$fields] = 'harus diisi.';
         }
      }
   }

   function IsEmpty($field, $fieldkey) {
      if (empty($field)) {
         $this->error[$fieldkey] = 'harus diisi.';
      }
   }
/*
   function Failed($action) {
      $this->return['status']['cond']     = FALSE;
      $this->return['status']['message']  = 'Gagal '.$action.' Data !';
   }

   function Success($action) {
      $this->return['status']['cond']     = TRUE;
      $this->return['status']['message']  = 'Berhasil '.$action.' Data';
   }
*/
   function Add() {
      $cekParams = $this->AreSet($this->importantParams);
      if ($cekParams == FALSE) {
         $this->return = MessageResult::Instance()->dataTidakLengkap();
      } else {
         $this->AreEmpty($this->importantParams);
         $cekNick = $this->appObj->GetCountDuplicateNickAdd($this->post['appNick']);
         if ($cekNick[0]['COUNT'] != 0) {
            $this->error['appNick']   = '"'.$this->post['appNick'].'" sudah ada.';
         }
         if (!empty($this->error)) {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         } else {
            $this->appObj->StartTrans();
            $this->group->StartTrans();
            $userGroup = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetActiveUserGroupId());

            $addApp    = $this->appObj->DoAddApp(
               $this->post['appProductId'],
               $this->post['appVarianId'],
               $this->post['appName'],
               $this->post['appNick'],
               $this->post['appDirInstall'],
               $this->post['appPathDev'],
               $this->post['appPathDocRepo'],
               $this->post['appPathDocFile']);

            $groupAppAdd   = $this->group->DoUpdateGroupAppAdd($userGroup,$this->appObj->LastInsertId());
            $processData  = $addApp && $groupAppAdd;

            $this->group->EndTrans($processData);
            $this->appObj->EndTrans($processData);

            if ($processData == true) {
               $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
            } else {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            }
         }
      }
      return $this->return;

      // echo json_encode($this->return);
      // exit;
   }

   function Update() {
      if (empty($this->identifier) || $this->identifier == '') {
         $this->return = MessageResult::Instance()->dataTidakDitemukan();
      } else {
         $cekParams = $this->AreSet($this->importantParams);
         if ($cekParams == FALSE) {
            $this->return = MessageResult::Instance()->dataTidakLengkap();
         } else {
            $this->AreEmpty($this->importantParams);
            $cekNick = $this->appObj->GetCountDuplicateNick($this->post['appNick'],$this->identifier);
            if ($cekNick[0]['COUNT'] != 0) {
               $this->error['appNick']   = '"'.$this->post['appNick'].'" sudah ada.';
            }
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime    = date('Y-m-d H:i:s');
               $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $updateApp  = $this->appObj->DoUpdateApp(
                  $this->post['appProductId'],
                  $this->post['appVarianId'],
                  $this->post['appName'],
                  $this->post['appNick'],
                  $this->post['appDirInstall'],
                  $this->post['appPathDev'],
                  $this->post['appPathDocRepo'],
                  $this->post['appPathDocFile'],
                  $UpdateTime,
                  $UpdateUser,
                  $this->identifier);

               $processData   = $updateApp;

               if ($processData == true) {
                  $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
               } else {
                  $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
               }
            }
         }
      }
      return $this->return;

      // echo json_encode($this->return);
      // exit;
   }

   function Delete() {
      if (isset($this->post['identifier'])) {
         $this->identifier = $this->post['identifier'];
      } else {
         $this->identifier = '';
      }
      if (empty($this->identifier) || $this->identifier == '') {
         $this->return = MessageResult::Instance()->dataTidakDitemukan();
      } else {
         $deleteApp  = $this->appObj->DoDeleteApp($this->identifier);

         $processData   = $deleteApp;

         if ($processData == true) {
            $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
         } else {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         }
      }
      return $this->return;

      // echo json_encode($this->return);
      // exit;
   }

}
?>
