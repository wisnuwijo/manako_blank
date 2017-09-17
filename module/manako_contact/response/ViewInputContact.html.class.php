<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';

class ViewInputContact extends HtmlResponse{

   function ParseCombobox($dataList, $nameList, $prefixList) {
      if(!empty($dataList)) {
         $this->mrTemplate->addVar("$nameList", "$prefixList", "-- PILIH --");
         $this->mrTemplate->parseTemplate("$nameList","a");
         for ($i=0;$i<sizeof($dataList);$i++) {
            if (empty($dataList[$i]['id'])) {
               $dataList[$i]['id'] = '';
            }
            if (empty($dataList[$i]['name'])) {
               $dataList[$i]['name'] = '';
            }
            if (empty($dataList[$i]['attribute'])) {
               $dataList[$i]['attribute'] = '';
            }               
            
            $this->mrTemplate->addVar("$nameList", "$prefixList"."_VALUE", $dataList[$i]['id']);
            $this->mrTemplate->addVar("$nameList", "$prefixList", $dataList[$i]['name']);
            $this->mrTemplate->addVar("$nameList", "$prefixList"."_ATTR", $dataList[$i]['attribute']);
   
            $this->mrTemplate->parseTemplate("$nameList","a");
         }
      }      
   }

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_contact/template');
      $this->SetTemplateFile('input_contact.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
         $return['css']   = $msg[0][2];
	      $return['Data']  = $msg[0];
      }else{
      	$return['Pesan'] = null;
         $return['css']   = null;
         $return['Data']  = null;
      }
      //var_dump($return['Data']);
      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data']['0']['idd']);

      $contactObj    = new Contact();
      $dataContact   = $contactObj->GetDataContactById($decID);
      $listClient    = $contactObj->GetDataClientAktif();
      $listPosisi    = $contactObj->GetDataPosisi();
      $listField     = $contactObj->GetDataContactField();
      $listValueCat  = array(
         array(
            'id' => 1,
            'name' => '= Telepon',
            ),
         array(
            'id' => 2,
            'name' => '= E-mail',
            ),
         array(
            'id' => 3,
            'name' => 'Custom',
            ),         
         );
      
      if ($return['Pesan']) {
         $contactData = $return['Data'][0];       
      } else {
         if($dataContact){
            $contactData = $dataContact[0];
         }
      }

      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactClientNick', 
         array('contactClientNick',$listClient,isset($contactData['contactClientNick'])?$contactData['contactClientNick']:'','false','form-control'), Messenger::CurrentRequest);            
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactPosisiCode', 
         array('contactPosisiCode',$listPosisi,isset($contactData['contactPosisiCode'])?$contactData['contactPosisiCode']:'','false','form-control'), Messenger::CurrentRequest); 
      
      //Combobox For contact-info-template
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactDetContactFieldCode', 
         array('contactDetContactFieldCode[]',$listField,'','false','form-control','','','contactDetContactFieldCode','','','collective','contactDetContactFieldCode'), Messenger::CurrentRequest); 
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactDetValueCat', 
         array('contactDetValueCat[]',$listValueCat,'','false','form-control','','','contactDetValueCat','','','collective','contactDetValueCat'), Messenger::CurrentRequest);                         

      $return['dataContact'] = $dataContact;
      $return['listField'] = $listField;
      $return['listValueCat'] = $listValueCat;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataContact = $data['dataContact'];
      $dataContactTambah = $data['Data'];
      $listField = $data['listField'];
      $listValueCat = $data['listValueCat'];

      $this->ParseCombobox($listField, 'listField', 'LISTFIELD');
      $this->ParseCombobox($listValueCat, 'listValueCat', 'LISTVALUECAT');
      
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);

         $this->mrTemplate->AddVar('content', 'IDD', $dataContactTambah[0]['idd']);
         $this->mrTemplate->AddVar('content', 'CONTACTNAMEFIRST', $dataContactTambah[0]['contactNameFirst']);
         $this->mrTemplate->AddVar('content', 'CONTACTNAMELAST', $dataContactTambah[0]['contactNameLast']);
         $this->mrTemplate->AddVar('content', 'CONTACTMAIL', $dataContactTambah[0]['contactMail']);
         $this->mrTemplate->AddVar('content', 'CONTACTMOBILE', $dataContactTambah[0]['contactMobile']);
         $this->mrTemplate->AddVar('content', 'CONTACTPOSISIDET', $dataContactTambah[0]['contactPosisiDet']);

         if ($dataContactTambah[0]['contactDetValue'] != '') {
            $len = sizeof($dataContactTambah[0]['contactDetValue']);
            for ($i=0; $i<$len; $i++) {
               $listContactDet[$i]['Counter'] = $i;
               $listContactDet[$i]['Value']   = $dataContactTambah[0]['contactDetValue'][$i];
               $listContactDet[$i]['ContactFieldCode'] = $dataContactTambah[0]['contactDetContactFieldCode'][$i];
               $listContactDet[$i]['contactDetValueCat'] = $dataContactTambah[0]['contactDetValueCat'][$i];             
               
               $this->mrTemplate->AddVars('data_contact_det_item', $listContactDet[$i], 'CONTACTDET_');
               $this->mrTemplate->parseTemplate('data_contact_det_item', 'a');
            }   
         }         
      } else {
   		if($dataContact){

	         $this->mrTemplate->AddVar('content', 'IDD', Dispatcher::Instance()->Encrypt($dataContact[0]['contactId']));
	         $this->mrTemplate->AddVar('content', 'CONTACTNAMEFIRST', $dataContact[0]['contactNameFirst']);
	         $this->mrTemplate->AddVar('content', 'CONTACTNAMELAST', $dataContact[0]['contactNameLast']);
            $this->mrTemplate->AddVar('content', 'CONTACTMAIL', $dataContact[0]['contactMail']);
            $this->mrTemplate->AddVar('content', 'CONTACTMOBILE', $dataContact[0]['contactMobile']);
            $this->mrTemplate->AddVar('content', 'CONTACTPOSISIDET', $dataContact[0]['contactPosisiDet']);

            if ($dataContact[0]['contactDetContactFieldCode'] != NULL) {
               $len = sizeof($dataContact);
               for ($i=0; $i<$len; $i++) {
                  $listContactDet[$i]['Counter'] = $i;
                  $listContactDet[$i]['Value']   = $dataContact[$i]['contactDetValue'];
                  $listContactDet[$i]['ContactFieldCode'] = $dataContact[$i]['contactDetContactFieldCode'];
                  if ($dataContact[$i]['contactDetValue'] == $dataContact[$i]['contactMobile']) {
                     $listContactDet[$i]['contactDetValueCat'] = '1';
                  } elseif ($dataContact[$i]['contactDetValue'] == $dataContact[$i]['contactMail']) {
                     $listContactDet[$i]['contactDetValueCat'] = '2';
                  } else {
                     $listContactDet[$i]['contactDetValueCat'] = '3';
                  }

                  $this->mrTemplate->AddVars('data_contact_det_item', $listContactDet[$i], 'CONTACTDET_');
                  $this->mrTemplate->parseTemplate('data_contact_det_item', 'a');
               }
               
            }
   		}
      }

      if (empty($dataContact)) {
         $url='AddContact';
         $tambah='Tambah';         
      } else {
         $url='UpdateContact';
         $tambah='Ubah';          
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_contact', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_contact', 'contact', 'view', 'html') );
   }
}
?>
