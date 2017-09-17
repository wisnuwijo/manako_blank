<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_product/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppProduct.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ProcessAppProduct {
   var $appObj;

   var $_POST;
   var $_GET;
   
   function __construct() {
      $this->appObj     = new AppProduct();

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

      $this->importantParams  = array('productName','productNick','productInitial');
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
         $cekNick = $this->appObj->GetCountDuplicateNickAdd($this->post['productNick']);
         if ($cekNick[0]['COUNT'] != 0) {
            $this->error['productNick'] = '"'.$this->post['productNick'].'" sudah ada.';
         }                  
         if (!empty($this->error)) {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         } else {
            $this->appObj->StartTrans();
            $addAppProduct    = $this->appObj->DoAddAppProduct(
               $this->post['productName'],
               $this->post['productNick'], 
               $this->post['productInitial'], 
               $this->post['productLogoIcon'], 
               $this->post['productLogoType'], 
               $this->post['productLogoFull']);

            $processData      = $addAppProduct;
            $this->appObj->EndTrans($processData);

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
            $cekNick = $this->appObj->GetCountDuplicateNick($this->post['productNick'], $this->identifier);
            if ($cekNick[0]['COUNT'] != 0) {
               $this->error['productNick'] = '"'.$this->post['productNick'].'" sudah ada.';
            }
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime    = date('Y-m-d H:i:s');
               $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $this->appObj->StartTrans();
               $updateAppProduct  = $this->appObj->DoUpdateAppProduct(
                  $this->post['productName'],
                  $this->post['productNick'], 
                  $this->post['productInitial'], 
                  $this->post['productLogoIcon'], 
                  $this->post['productLogoType'], 
                  $this->post['productLogoFull'],
                  $UpdateTime, 
                  $UpdateUser, 
                  $this->identifier);

               $processData       = $updateAppProduct;
               $this->appObj->EndTrans($processData);
               
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
         $this->appObj->StartTrans();
         $deleteAppProduct  = $this->appObj->DoDeleteAppProduct($this->identifier);
         $processData       = $deleteAppProduct;
         $this->appObj->EndTrans($processData);
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
