<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact_field/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/ContactField.class.php';

class ViewContactField extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_contact_field/template');
      $this->SetTemplateFile('view_contact_field.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['label'] = '';
      }
      $return['filter'] = $filter;

      $contactFieldObj  = new ContactField();
      $dataContactField = $contactFieldObj->GetDataContactField($filter['label']);
	   $return['dataContactField'] = $dataContactField;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_contact_field', 'contactField', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'CONTACTFIELD_URL_ADD', Dispatcher::Instance()->GetUrl('manako_contact_field', 'inputContactField', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['label'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_MODEL', $data['filter']['label']);
      if (empty($data['dataContactField'])) {
         $this->mrTemplate->AddVar('data_contact_field', 'CONTACTFIELD_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_contact_field', 'CONTACTFIELD_EMPTY', 'NO');
         $dataContactField = $data['dataContactField'];
         $len        = sizeof($dataContactField);
         $no         = 0;
         for ($i=0; $i<$len; $i++) {
            $no++;
            $listContactField[$no]       = $dataContactField[$i];
            $listContactField[$no]['no'] = $no;
         }
         
         //echo count($listContactField);exit;
         $no=1;
         for($i=1;$i<count($listContactField)+1;$i++){            
            $listContactField[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataContactField[$i]['class_name'] = 'table-common-even';
            } else {
               $dataContactField[$i]['class_name'] = '';
            }
            $no++;

            $idEnc      = Dispatcher::Instance()->Encrypt($listContactField[$i]['contactFieldId']);
            $listContactField[$i]['url_edit']    = Dispatcher::Instance()->GetUrl('manako_contact_field', 'inputContactField', 'view', 'html') .$l .'idd=' . $idEnc;
                        
            @$urlAccept = 'manako_contact_field|deleteContactField|do|html';
            @$urlReturn = 'manako_contact_field|contactField|view|html';
            $label      = 'ContactField';
            $dataName   = $listContactField[$i]['contactFieldLabel'];
            $listContactField[$i]['url_delete']  = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_contact_field_item', $listContactField[$i], 'CONTACTFIELD_');
            $this->mrTemplate->parseTemplate('data_contact_field_item', 'a');
         }
      }
   }
}
?>
