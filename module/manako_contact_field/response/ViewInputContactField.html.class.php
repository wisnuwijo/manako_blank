<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact_field/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/ContactField.class.php';

class ViewInputContactField extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_contact_field/template');
      $this->SetTemplateFile('input_contact_field.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      @$return['Pesan'] = $msg[0][1];
         @$return['css']   = $msg[0][2];
	      @$return['Data']  = $msg[0];
      }else{
      	$return['Pesan'] = null;
         $return['css']   = null;
         $return['Data']  = null;
      }
      
      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == ''){
         $decID = Dispatcher::Instance()->Decrypt($return['Data'][0]['idd']);
      }
      
      $contactFieldObj  = new ContactField();
      $dataContactField = $contactFieldObj->GetDataContactFieldById($decID);

      $listContactFieldType  = array(
         array(
            'id' => 'Number',
            'name' => 'Number',
            ),
         array(
            'id' => 'Mail',
            'name' => 'Mail',
            ),
         array(
            'id' => 'Id',
            'name' => 'Id',
            ),         
         );

      if ($return['Data']) {
         $contactFieldTypeData = $return['Data'][0];       
      } else {
         if($dataContactField){
            $contactFieldTypeData = $dataContactField[0];
         }
      }

      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'contactFieldType', 
         array('contactFieldType',$listContactFieldType,isset($contactFieldTypeData['contactFieldType'])?$contactFieldTypeData['contactFieldType']:'','false','form-control'), Messenger::CurrentRequest); 

      $return['dataContactField'] = $dataContactField;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataContactField     = $data['dataContactField'];
      $dataContactFieldUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
         
         $listContactField          = $dataContactFieldUbah[0];
         $listContactField['IDD']   = $dataContactFieldUbah[0]['idd'];
      } else {
   		if($dataContactField){
	         $listContactField        = $dataContactField[0];
            $listContactField['IDD'] = Dispatcher::Instance()->Encrypt($dataContactField[0]['contactFieldId']);
   		}
      }
      if (!empty($listContactField)) {
         $this->mrTemplate->AddVars('data_contact_field_item', $listContactField, 'CONTACTFIELD_');
         $this->mrTemplate->parseTemplate('data_contact_field_item', 'a');
      }

      if (empty($dataContactField)) {
         $url     = 'AddContactField';
         $tambah  = 'Tambah';
      } else {
         $url     = 'UpdateContactField';
         $tambah  = 'Ubah';  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_contact_field', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_contact_field', 'contactField', 'view', 'html') );
   }
}
?>
