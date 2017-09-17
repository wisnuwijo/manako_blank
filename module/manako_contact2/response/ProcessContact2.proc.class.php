<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ProcessContact2 {
   var $contactObj;

   var $_POST;
   var $_GET;
   
   function __construct() {
      $this->contactObj = new Contact();
      
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

      $this->importantParams = array('contactClientNick','contactPosisiCode');
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

   function GenerateQrcode($id, $nameFirst, $nameLast, $cMail, $cMobile, $client, $fieldType, $fieldName, $fieldValue) {
      include('main/lib/phpqrcode/qrlib.php');
      include('main/lib/phpqrcode/qrconfig.php');

      // how to build raw content - QRCode with detailed Business Card (VCard)

      $saveDir = Configuration::Instance()->GetValue( 'application', 'upload_path').'/qrcode/';

      // here our data
      $name         = $nameFirst.' '.$nameLast;
      $sortName     = $nameLast.';'.$nameFirst;
      $phoneCell    = $cMobile;
      $email        = $cMail;

      $phone        = '';
      $phonePrivate = '';
      $orgName      = $client;

      // if not used - leave blank!
      $addressLabel     = '';
      $addressPobox     = '';
      $addressExt       = '';
      $addressStreet    = '';
      $addressTown      = '';
      $addressRegion    = '';
      $addressPostCode  = '';
      $addressCountry   = '';

       // we building raw data
      $codeContents  = 'BEGIN:VCARD'."\n";
      $codeContents .= 'VERSION:2.1'."\n";
      $codeContents .= 'N:'.$sortName."\n";
      $codeContents .= 'FN:'.$name."\n";
      $codeContents .= 'ORG:'.$orgName."\n";

      $codeContents .= 'TEL;WORK;VOICE:'.$phone."\n";
      $codeContents .= 'TEL;HOME;VOICE:'.$phonePrivate."\n";
      $codeContents .= 'TEL;TYPE=cell:'.$phoneCell."\n";

      $codeContents .= 'ADR;TYPE=work;'.
           'LABEL="'.$addressLabel.'":'
           .$addressPobox.';'
           .$addressExt.';'
           .$addressStreet.';'
           .$addressTown.';'
           .$addressPostCode.';'
           .$addressCountry
      ."\n";

      $codeContents .= 'EMAIL:'.$email."\n";

      $fieldVal = Array();
      $fieldVal = $fieldValue->AsArray();
      $fieldVal = array_keys(array_flip($fieldVal));
      
      $len = sizeof($fieldVal);
      for ($i=0; $i < $len; $i++) { 
         if ($fieldType[$i] == 'Number') {
            $type = 'TEL;TYPE=cell:';           
            if ($fieldVal[$i] != $phoneCell) {
               $codeContents .= $type.$fieldVal[$i]."\n";
            }
         } elseif ($fieldType[$i] == 'Mail'){
            $type = 'EMAIL:';
            if ($fieldVal[$i] != $email) {
               $codeContents .= $type.$fieldVal[$i]."\n";
            }            
         }  else {
            $type = 'OTHER:';
            $codeContents .= $type.$fieldVal[$i]."\n";
         }
      }

      $codeContents .= 'END:VCARD';
      
      // generating
      // $fileName      = urlencode(strtolower($nameLast.$nameFirst.$id.'.png'));
      $fileName      = $nameLast.$nameFirst.$id.'.png';
      $saveFile      = $saveDir.$fileName;
      QRcode::png($codeContents, $saveFile, QR_ECLEVEL_L, 3);

      // displaying
      return $saveFile; 
   }

   function Add() {
      $cekParams = $this->AreSet($this->importantParams);
      if ($cekParams == FALSE) {
         $this->return = MessageResult::Instance()->dataTidakLengkap();
      } else {      
         $this->AreEmpty($this->importantParams);  
         if (!empty($this->error)) {
            $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
         } else {    
            $addContact = $this->contactObj->DoAddContact($this->post['contactNameFirst'], $this->post['contactNameLast'], $this->post['contactMail'], $this->post['contactMobile'], $this->post['contactClientNick'], $this->post['contactPosisiCode'], $this->post['contactPosisiDet']);
            $lastInsertedId = $this->contactObj->GetMaxId();
            $insertedContactId = $lastInsertedId[0]['max_id'];
            
            $processData = $addContact;

            if (isset($this->post['contactDetValue']) || !empty($this->post['contactDetValue'])) {
               $addContactDet = TRUE;
               $len = sizeof($this->post['contactDetValue']);
               for ($i=0; $i < $len; $i++) {
                  if ($this->post['contactDetValue'][$i] != "") {
                     $addContactDet = $this->contactObj->DoAddContactDet($insertedContactId, $this->post['contactDetContactFieldCode'][$i], $this->post['contactDetValue'][$i]);

                     $dataContactField[$i] = $this->contactObj->GetDataContactFieldByCode($this->post['contactDetContactFieldCode'][$i]);
                     $fieldType[$i] = $dataContactField[$i][0]['contactFieldType'];
                     $fieldName[$i] = str_replace($this->post['contactDetContactFieldCode'][$i], $dataContactField[$i][0]['contactFieldLabel'] , $this->post['contactDetContactFieldCode'][$i]);
                  }
               }
               $processData = $processData && $addContactDet; //TO:DO Not valid
            }

            $dataClient        = $this->contactObj->GetDataClientAktifByNick($this->post['contactClientNick']);
            $contactClientNick = $dataClient[0]['clientName'];

            $GenerateQrcode = $this->GenerateQrcode($insertedContactId, $this->post['contactNameFirst'], $this->post['contactNameLast'], $this->post['contactMail'], $this->post['contactMobile'], $contactClientNick, $fieldType, $fieldName, $this->post['contactDetValue']);

            $processData = $processData && $GenerateQrcode;

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
            if (!empty($this->error)) {
               $this->return = MessageResult::Instance()->penyimpananGagal($this->error);
            } else {
               $UpdateTime    = date('Y-m-d H:i:s');
               $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

               $updateContact = $this->contactObj->DoUpdateContact($this->post['contactNameFirst'], $this->post['contactNameLast'], $this->post['contactMail'], $this->post['contactMobile'], $this->post['contactClientNick'], $this->post['contactPosisiCode'], $this->post['contactPosisiDet'], $UpdateTime, $UpdateUser, $this->identifier);

               $deleteContactDet = $this->contactObj->DoDelContactDet($this->identifier);
               
               $processData = $updateContact && $deleteContactDet;

               if (isset($this->post['contactDetValue']) || !empty($this->post['contactDetValue'])) {
                  $addContactDet = TRUE;
                  $len = sizeof($this->post['contactDetValue']);
                  for ($i=0; $i < $len; $i++) {
                     if ($this->post['contactDetValue'][$i] != "") {
                        $addContactDet = $this->contactObj->DoAddContactDet($this->identifier, $this->post['contactDetContactFieldCode'][$i], $this->post['contactDetValue'][$i]);
                        
                        $dataContactField[$i] = $this->contactObj->GetDataContactFieldByCode($this->post['contactDetContactFieldCode'][$i]);
                        $fieldType[$i] = $dataContactField[$i][0]['contactFieldType'];
                        $fieldName[$i] = str_replace($this->post['contactDetContactFieldCode'][$i], $dataContactField[$i][0]['contactFieldLabel'] , $this->post['contactDetContactFieldCode'][$i]);
                     }
                  }
                  $processData = $processData && $addContactDet; //TO:DO Not valid
               }

               $dataClient        = $this->contactObj->GetDataClientAktifByNick($this->post['contactClientNick']);
               $contactClientNick = $dataClient[0]['clientName'];

               $GenerateQrcode = $this->GenerateQrcode($this->identifier, $this->post['contactNameFirst'], $this->post['contactNameLast'], $this->post['contactMail'], $this->post['contactMobile'], $contactClientNick, $fieldType, $fieldName, $this->post['contactDetValue']);

               $processData = $processData && $GenerateQrcode;

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
         $deleteContact = $this->contactObj->DoDeleteContact($this->identifier);
         $deleteContactDet = $this->contactObj->DoDelContactDet($this->identifier);

         $processData = $deleteContact && $deleteContactDet;
            
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
