<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLogin.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ProcessAppLogin {
   var $appLoginObj;

   var $_POST;
   var $_GET;

   function __construct() {
      $this->appLoginObj     = new AppLogin();

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

      $this->importantParams = array('loginClientId','loginLokasiserverId','loginAppId','loginUrl','loginLoginroleId','loginUser');
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

   function Add() {
      $cekParams = $this->AreSet($this->importantParams);
      if ($cekParams == FALSE) {
         $this->return = MessageResult::Instance()->dataTidakLengkap();
      } else {
         $this->AreEmpty($this->importantParams);
         $cekDuplicate = $this->appLoginObj->GetCountDuplicateAdd($this->post['loginClientId'],$this->post['loginLokasiserverId'],$this->post['loginAppId'],$this->post['loginLoginroleId'],$this->post['loginUser']);
         if ($cekDuplicate[0]['COUNT'] != 0) {
            $this->error['loginUser'] = '"'.$this->post['loginUser'].'" sudah ada.';
         }
         if (!empty($this->error)) {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         } else {
            $addAppLogin    = $this->appLoginObj->DoAddAppLogin(
               $this->post['loginClientId'],
               $this->post['loginLokasiserverId'],
               $this->post['loginAppId'],
               $this->post['loginUrl'],
               $this->post['loginLoginroleId'],
               $this->post['loginUser'],
               $this->post['loginPswd'],
               $this->post['loginNote']);

            $processData  = $addAppLogin;

            if ($processData == true) {
               $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
            } else {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            }
         }
      }
      return $this->return;
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
            $cekDuplicate = $this->appLoginObj->GetCountDuplicate($this->post['loginClientId'],$this->post['loginLokasiserverId'],$this->post['loginAppId'],$this->post['loginLoginroleId'],$this->post['loginUser'],$this->identifier);
            if ($cekDuplicate[0]['COUNT'] != 0) {
               $this->error['loginUser'] = '"'.$this->post['loginUser'].'" sudah ada.';
            }
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime    = date('Y-m-d H:i:s');
               $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $updateAppLogin  = $this->appLoginObj->DoUpdateAppLogin(
                  $this->post['loginClientId'],
                  $this->post['loginLokasiserverId'],
                  $this->post['loginAppId'],
                  $this->post['loginUrl'],
                  $this->post['loginLoginroleId'],
                  $this->post['loginUser'],
                  $this->post['loginNote'],
                  $UpdateTime,
                  $UpdateUser,
                  $this->identifier);

               $processData   = $updateAppLogin;

               if ($processData == true) {
                  $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
               } else {
                  $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
               }
            }
         }
      }
      return $this->return;
   }

   function ChangePswd() {
      if (empty($this->identifier) || $this->identifier == '') {
         $this->return = MessageResult::Instance()->dataTidakDitemukan();
      } else {
         $cekParams = $this->AreSet(array('loginPswdOld','loginPswd','loginPswdValid'));
         if ($cekParams == FALSE) {
            $this->return = MessageResult::Instance()->dataTidakLengkap();
         } else {
            $this->AreEmpty(array('loginPswd','loginPswdValid'));
            $dataAppLoginPswd = $this->appLoginObj->GetDataAppLoginPswd($this->identifier);
            if ($this->post['loginPswdOld'] != $dataAppLoginPswd[0]['loginPswd']) {
               $this->error['loginPswdOld'] = 'salah';
            }
            if ($this->post['loginPswdValid'] != $this->post['loginPswd']) {
               $this->error['loginPswdValid'] = 'tidak sama';
            }
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime          = date('Y-m-d H:i:s');
               $UpdateUser          = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $changePswdAppLogin  = $this->appLoginObj->DoChangePswdAppLogin(
                  $this->post['loginPswd'],
                  $UpdateTime,
                  $UpdateUser,
                  $this->identifier);

               $processData         = $changePswdAppLogin;

               if ($processData == true) {
                  $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
               } else {
                  $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
               }
            }
         }
      }
      return $this->return;
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
         $deleteAppLogin  = $this->appLoginObj->DoDeleteAppLogin($this->identifier);

         $processData   = $deleteAppLogin;

         if ($processData == true) {
            $this->return = MessageResult::Instance()->penyimpananSukses($this->error);
         } else {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         }
      }
      return $this->return;
   }

}
?>
