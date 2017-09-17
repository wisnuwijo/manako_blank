<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_loginrole/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLoginrole.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ProcessAppLoginrole {
   var $appLoginroleObj;

   var $_POST;
   var $_GET;
   
   function __construct() {
      $this->docObj     = new AppLoginrole();

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

      $this->importantParams = array('loginroleName');
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
         $cekDuplicate = $this->docObj->GetCountDuplicateNameAdd($this->post['loginroleName']);
         if ($cekDuplicate[0]['COUNT'] != 0) {
            $this->error['loginroleName'] = '"'.$this->post['loginroleName'].'" sudah ada.';
         }
         if (!empty($this->error)) {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         } else {         
            $addAppLoginrole    = $this->docObj->DoAddAppLoginrole(
               $this->post['loginroleName']);

            $processData  = $addAppLoginrole;

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
            $cekDuplicate = $this->docObj->GetCountDuplicateName($this->post['loginroleName'], $this->identifier);
            if ($cekDuplicate[0]['COUNT'] != 0) {
               $this->error['loginroleName'] = '"'.$this->post['loginroleName'].'" sudah ada.';
            }
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime    = date('Y-m-d H:i:s');
               $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $updateAppLoginrole  = $this->docObj->DoUpdateAppLoginrole(
                  $this->post['loginroleName'],
                  $UpdateTime, 
                  $UpdateUser, 
                  $this->identifier);

               $processData   = $updateAppLoginrole;
               
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
         $deleteAppLoginrole  = $this->docObj->DoDeleteAppLoginrole($this->identifier);

         $processData   = $deleteAppLoginrole;
            
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
