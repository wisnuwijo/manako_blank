<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';

class ProcessContact {

   
   var $_POST;
   var $contactObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;
   
   function __construct() {
      $this->contactObj = new Contact();
      
      $this->_POST = $_POST->AsArray();
      $this->pageView = Dispatcher::Instance()->GetUrl('manako_contact', 'contact', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('manako_contact', 'inputContact', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_contact', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
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
      //var_dump($_POST);exit();
      // $cek_nick = $this->contactObj->GetCountDuplicateNickAdd($_POST['contactNick']);
      if ($_POST['contactClientNick'] == '' || $_POST['contactPosisiCode'] == '') {
         $this->SendAlert('Gagal Menambah Data, Kolom yang dibutuhkan harus terisi.', 'inputContact', $this->cssFail);
         return $this->pageInput;
      } else {
         
         $addContact = $this->contactObj->DoAddContact($_POST['contactNameFirst'], $_POST['contactNameLast'], $_POST['contactMail'], $_POST['contactMobile'], $_POST['contactClientNick'], $_POST['contactPosisiCode'], $_POST['contactPosisiDet']);
         $lastInsertedId = $this->contactObj->GetMaxId();
         $insertedContactId = $lastInsertedId[0]['max_id'];
         
         $processData = $addContact;

         if (isset($_POST['contactDetValue']) || !empty($_POST['contactDetValue'])) {
            $addContactDet = TRUE;
            $len = sizeof($_POST['contactDetValue']);
            for ($i=0; $i < $len; $i++) {
               if ($_POST['contactDetValue'][$i] != "") {
                  $addContactDet = $this->contactObj->DoAddContactDet($insertedContactId, $_POST['contactDetContactFieldCode'][$i], $_POST['contactDetValue'][$i]);

                  $dataContactField[$i] = $this->contactObj->GetDataContactFieldByCode($_POST['contactDetContactFieldCode'][$i]);
                  $fieldType[$i] = $dataContactField[$i][0]['contactFieldType'];
                  $fieldName[$i] = str_replace($_POST['contactDetContactFieldCode'][$i], $dataContactField[$i][0]['contactFieldLabel'] , $_POST['contactDetContactFieldCode'][$i]);
               }
            }
            $processData = $processData && $addContactDet; //TO:DO Not valid
         }

         $dataClient        = $this->contactObj->GetDataClientAktifByNick($_POST['contactClientNick']);
         $contactClientNick = $dataClient[0]['clientName'];

         $GenerateQrcode = $this->GenerateQrcode($insertedContactId, $_POST['contactNameFirst'], $_POST['contactNameLast'], $_POST['contactMail'], $_POST['contactMobile'], $contactClientNick, $fieldType, $fieldName, $_POST['contactDetValue']);

         $processData = $processData && $GenerateQrcode;

         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'contact', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputContact', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      //var_dump($_POST);exit;
      //$cek_nick = $this->contactObj->GetCountDuplicateNick($_POST['contactNick'], $_POST['idd']);
      if ($_POST['contactClientNick'] == '' || $_POST['contactPosisiCode'] == '') {
         $this->SendAlert('Gagal Menambah Data, Kolom yang dibutuhkan harus terisi.', 'inputContact', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());

         $updateContact = $this->contactObj->DoUpdateContact($_POST['contactNameFirst'], $_POST['contactNameLast'], $_POST['contactMail'], $_POST['contactMobile'], $_POST['contactClientNick'], $_POST['contactPosisiCode'], $_POST['contactPosisiDet'], $UpdateTime, $UpdateUser, $_POST['idd']);

         $deleteContactDet = $this->contactObj->DoDelContactDet($_POST['idd']);
         
         $processData = $updateContact && $deleteContactDet;

         if (isset($_POST['contactDetValue']) || !empty($_POST['contactDetValue'])) {
            $addContactDet = TRUE;
            $len = sizeof($_POST['contactDetValue']);
            for ($i=0; $i < $len; $i++) {
               if ($_POST['contactDetValue'][$i] != "") {
                  $addContactDet = $this->contactObj->DoAddContactDet($_POST['idd'], $_POST['contactDetContactFieldCode'][$i], $_POST['contactDetValue'][$i]);
                  
                  $dataContactField[$i] = $this->contactObj->GetDataContactFieldByCode($_POST['contactDetContactFieldCode'][$i]);
                  $fieldType[$i] = $dataContactField[$i][0]['contactFieldType'];
                  $fieldName[$i] = str_replace($_POST['contactDetContactFieldCode'][$i], $dataContactField[$i][0]['contactFieldLabel'] , $_POST['contactDetContactFieldCode'][$i]);
               }
            }
            $processData = $processData && $addContactDet; //TO:DO Not valid
         }

         $dataClient        = $this->contactObj->GetDataClientAktifByNick($_POST['contactClientNick']);
         $contactClientNick = $dataClient[0]['clientName'];

         $GenerateQrcode = $this->GenerateQrcode($_POST['idd'], $_POST['contactNameFirst'], $_POST['contactNameLast'], $_POST['contactMail'], $_POST['contactMobile'], $contactClientNick, $fieldType, $fieldName, $_POST['contactDetValue']);

         $processData = $processData && $GenerateQrcode;

         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'contact', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputContact', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {       
      //print_r(var_dump($_POST));exit; 
      $deleteContact = $this->contactObj->DoDeleteContact($_POST['idDelete']);

      $deleteContactDet = $this->contactObj->DoDelContactDet($_POST['idDelete']);

      $processData = $deleteContact && $deleteContactDet;

         
      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'contact', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'contact', $this->cssFail);
      }

      return $this->pageView;
   }

}
?>
