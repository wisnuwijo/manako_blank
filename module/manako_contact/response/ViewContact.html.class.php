<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';

class ViewContact extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_contact/template');
      $this->SetTemplateFile('view_contact.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['contactName']   = '';
         $filter['contactClient']   = '';
         $filter['contactPosisi']   = '';
      }
      $return['filter'] = $filter;
      
      $contactObj  = new Contact();
      $dataContact = $contactObj->GetDataContact($filter['contactName'], $filter['contactClient'], $filter['contactPosisi']);
      
      $listClient = $contactObj->GetDataClientAktif();
      $listPosisi = $contactObj->GetDataPosisi();

      if ($return['Pesan'] && isset($return['Data'])) {
         $contactData = $return['Data'][0];
      } else {
         if($dataContact){
            $contactData = $dataContact[0];
         }
      }      

      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactClient', 
         array('contactClient',$listClient,$filter['contactClient'],'false','form-control','','','','','-- Semua --'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactPosisi', 
         array('contactPosisi',$listPosisi,$filter['contactPosisi'],'false','form-control','','','','','-- Semua --'), Messenger::CurrentRequest);

	   $return['dataContact'] = $dataContact;

      return $return;
   }

   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_contact', 'contact', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'CONTACT_URL_ADD', Dispatcher::Instance()->GetUrl('manako_contact', 'inputContact', 'view', 'html') );

      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $param      = '&identifier=';
      $l          = '&';
      if ($urlType == "Simple") {
         $param   = '/';
         $l       = '?';
      }

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['contactName']) || !empty($data['filter']['contactClient']) || !empty($data['filter']['contactPosisi'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_NAME', $data['filter']['contactName']);
      //$this->mrTemplate->AddVar('content', 'FILTER_CLIENT', $data['filter']['contactClient']);
      //$this->mrTemplate->AddVar('content', 'FILTER_POSISI', $data['filter']['contactPosisi']);

      if (empty($data['dataContact'])) {
         $this->mrTemplate->AddVar('data_contact', 'CONTACT_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_contact', 'CONTACT_EMPTY', 'NO');
         $dataContact = $data['dataContact'];
         $len        = sizeof($dataContact);
         $idContact   ='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            if($idContact!=$dataContact[$i]['contactId']){
               $no++;
               $listContact[$no]['no']             = $no;
               $listContact[$no]['contactId']       = $dataContact[$i]['contactId'];
               $listContact[$no]['contactNameFirst']     = $dataContact[$i]['contactNameFirst'];
               $listContact[$no]['contactNameLast']     = $dataContact[$i]['contactNameLast'];
               $listContact[$no]['contactMail'] = $dataContact[$i]['contactMail'];
               $listContact[$no]['contactMobile'] = $dataContact[$i]['contactMobile'];
               $listContact[$no]['contactPosisiDet'] = $dataContact[$i]['contactPosisiDet'];
               if ($dataContact[$i]['contactPosisiDet'] != NULL || $dataContact[$i]['contactPosisiDet'] != '') {
                  $listContact[$no]['contactPosisiDet'] = "(".$dataContact[$i]['contactPosisiDet'].")";
               }
               $listContact[$no]['clientId'] = $dataContact[$i]['clientId'];
               $listContact[$no]['clientName'] = $dataContact[$i]['clientName'];
               $listContact[$no]['posisiName'] = $dataContact[$i]['posisiName'];
               $listContact[$no]['contactField'] ='';
               $idContact=$listContact[$no]['contactId'];
            }
            $listContact[$no]['contactField'] .= '<span title="'.$dataContact[$i]['contactFieldLabel'].'"><i class="'.$dataContact[$i]['contactFieldIcon'].' fa-fw"></i></span>';
         }
         
         
         $no=1;
         for($i=1;$i<count($listContact)+1;$i++){
            /*            
            $listContact[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataContact[$i]['class_name'] = 'table-common-even';
            } else {
               $dataContact[$i]['class_name'] = '';
            }
            */
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($listContact[$i]['contactId']);
            $idEncClient = Dispatcher::Instance()->Encrypt($listContact[$i]['clientId']);
            $listContact[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('manako_contact', 'inputContact', 'view', 'html') . '&idd=' . $idEnc;
            $listContact[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('manako_contact', 'detailContact', 'view', 'html') . '&idd=' . $idEnc;
            $listContact[$i]['url_client'] = Dispatcher::Instance()->GetUrl('manako_client', 'detailClient', 'view', 'html') . '&idd=' . $idEncClient;
                        
            @$urlAccept = 'manako_contact|deleteContact|do|json';
            @$urlReturn = 'manako_contact|contact|view|html';
            $label = 'Contact';
            $dataName = $listContact[$i]['contactNameFirst'] .' ' .$listContact[$i]['contactNameLast'];
            $listContact[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_contact_item', $listContact[$i], 'CONTACT_');
            $this->mrTemplate->parseTemplate('data_contact_item', 'a');
         }

         // $listContact = json_encode($listContact);
         // $this->mrTemplate->AddVar('content', 'DATAKONTAK', $listContact);

      }
   }
}
?>
