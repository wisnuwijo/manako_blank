<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_gforge/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Gforge.class.php';

class ViewInputGforge extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_gforge/template');
      $this->SetTemplateFile('input_gforge.html');
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
      
      $decID      = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data'][0]['idd']);
      
      $gforgeObj  = new Gforge();

      $dataGforge = $gforgeObj->GetDataGforgeById($decID);

      $return['dataGforge'] = $dataGforge;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataGforge = $data['dataGforge'];
      $dataGforgeUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
         
         $this->mrTemplate->AddVar('content', 'IDD', $dataGforgeUbah[0]['idd']);
         $this->mrTemplate->AddVar('content', 'GFORGENICKNAME', $dataGforgeUbah[0]['gforgeNickname']);
      } else {
   		if($dataGforge){
	         $this->mrTemplate->AddVar('content', 'IDD', Dispatcher::Instance()->Encrypt($dataGforge[0]['gforgeId']));
	         $this->mrTemplate->AddVar('content', 'GFORGENICKNAME', $dataGforge[0]['gforgeNickname']);
   		}
      }
      if (empty($dataGforge)) {
         $url='AddGforge';
         $tambah='Tambah';
      } else {
         $url='UpdateGforge';
         $tambah='Ubah';  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_gforge', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_gforge', 'gforge', 'view', 'html') );
   }
}
?>
