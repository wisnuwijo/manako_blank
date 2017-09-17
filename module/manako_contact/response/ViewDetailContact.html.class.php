<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';

class ViewDetailContact extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_contact/template');
      $this->SetTemplateFile('detail_contact.html');
   }
   
   function ProcessRequest() {
      /*
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
         $return['css'] = $msg[0][2];
	      $return['Data'] = $msg[0];
      }else{
      	$return['Pesan'] = null;
         $return['css'] = null;
         $return['Data'] = null;
      }*/
      
      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data']['0']['idd']);

      $contactObj               = new Contact();
      $dataContact              = $contactObj->GetDataContactById($decID);
      // $qrcodeBaseURLTemp        = Dispatcher::Instance()->GetUrl('', '', '', '').'images/qrcode/';
      // $qrcodeBaseURL            = str_replace('index.php?mod=&sub=&act=&typ=', '', $qrcodeBaseURLTemp);
      // $qrcodeBaseURL            = 'images/qrcode/';
      $return['dataContact']    = $dataContact;
      // $return['qrcodeBaseURL']  = $qrcodeBaseURL;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataContact   = $data['dataContact'];
   	// $qrcodeBaseURL = $data['qrcodeBaseURL'];
         if($dataContact){

            $this->mrTemplate->AddVar('content', 'IDD', Dispatcher::Instance()->Encrypt($theId = $dataContact[0]['contactId']));
            $this->mrTemplate->AddVar('content', 'CONTACTNAMEFIRST', $nameFirst = $dataContact[0]['contactNameFirst']);
            $this->mrTemplate->AddVar('content', 'CONTACTNAMELAST', $nameLast = $dataContact[0]['contactNameLast']);
            $this->mrTemplate->AddVar('content', 'CONTACTMAIL', $dataContact[0]['contactMail']);
            $this->mrTemplate->AddVar('content', 'CONTACTMOBILE', $dataContact[0]['contactMobile']);
            $this->mrTemplate->AddVar('content', 'POSISINAME', $dataContact[0]['posisiName']);
            if ($dataContact[0]['contactPosisiDet'] != NULL || $dataContact[0]['contactPosisiDet'] != '') {
               $this->mrTemplate->AddVar('content', 'CONTACTPOSISIDET', '('.$dataContact[0]['contactPosisiDet'].')');
            } else {
               $this->mrTemplate->AddVar('content', 'CONTACTPOSISIDET', $dataContact[0]['contactPosisiDet']);
            }
            $this->mrTemplate->AddVar('content', 'CLIENTNAME', $dataContact[0]['clientName']);
            $this->mrTemplate->AddVar('content', 'CONTACTQRCODE', Dispatcher::Instance()->GetUrl('manako_contact', 'qrcontact', 'view', 'html').'/'.$theId.'?t='.time());

            if ($dataContact[0]['contactDetContactFieldCode'] != NULL) {
               $len = sizeof($dataContact);
               for ($i=0; $i<$len; $i++) {
                  // $listContactDet[$i]['Counter'] = $i;
                  $listContactDet[$i]['contactFieldIcon']           = $dataContact[$i]['contactFieldIcon'];
                  $listContactDet[$i]['contactFieldLabel']          = $dataContact[$i]['contactFieldLabel'].'&nbsp;<span class="pull-right"> :</span>';
                  $listContactDet[$i]['contactDetValue']            = $dataContact[$i]['contactDetValue'];                  
                  /*if ($dataContact[$i]['contactDetValue'] == $dataContact[$i]['contactMobile']) {
                     $listContactDet[$i]['contactDetValueCat'] = '1';
                  } elseif ($dataContact[$i]['contactDetValue'] == $dataContact[$i]['contactMail']) {
                     $listContactDet[$i]['contactDetValueCat'] = '2';
                  } else {
                     $listContactDet[$i]['contactDetValueCat'] = '3';
                  }*/

                  $this->mrTemplate->AddVars('data_contact_det_item', $listContactDet[$i], 'CONTACTDET_');
                  $this->mrTemplate->parseTemplate('data_contact_det_item', 'a');
               }
               
            }
         }

      $idEnc = Dispatcher::Instance()->Encrypt($dataContact[0]['contactId']);
      $idEncClient = Dispatcher::Instance()->Encrypt($dataContact[0]['clientId']);
      $this->mrTemplate->AddVar('content', 'JUDUL', 'Detail');
      $this->mrTemplate->AddVar('content', 'URL_EDIT', Dispatcher::Instance()->GetUrl('manako_contact', 'inputContact', 'view', 'html') . '&idd=' . $idEnc);
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_contact', 'contact', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CLIENT', Dispatcher::Instance()->GetUrl('manako_client', 'detailClient', 'view', 'html') . '&idd=' . $idEncClient);

      // $this->mrTemplate->AddVars('data_contact_item', $listContact[$no], 'CONTACT_');
      // $this->mrTemplate->parseTemplate('data_contact_item', 'a');
   }
}
?>
