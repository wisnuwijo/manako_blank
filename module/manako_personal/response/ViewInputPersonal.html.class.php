<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_personal/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Personal.class.php';

class ViewInputPersonal extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_personal/template');
      $this->SetTemplateFile('input_personal.html');
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
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data']['0']['idd']);
      
      $personalObj = new Personal();

      $dataPersonal = $personalObj->GetDataPersonalById($decID);

      $return['dataPersonal'] = $dataPersonal;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataPersonal = $data['dataPersonal'];
      $dataPersonalUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
         
         $this->mrTemplate->AddVar('content', 'IDD', $dataPersonalUbah[0]['idd']);
         $this->mrTemplate->AddVar('content', 'PERSONALNAME', $dataPersonalUbah[0]['personalName']);
      } else {
   		if($dataPersonal){
	         $this->mrTemplate->AddVar('content', 'IDD', Dispatcher::Instance()->Encrypt($dataPersonal[0]['personalId']));
	         $this->mrTemplate->AddVar('content', 'PERSONALNAME', $dataPersonal[0]['personalName']);
   		}
      }
      if (empty($dataPersonal)) {
         $url='AddPersonal';
         $tambah='Tambah';
      } else {
         $url='UpdatePersonal';
         $tambah='Ubah';  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_personal', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_personal', 'personal', 'view', 'html') );
   }
}
?>
