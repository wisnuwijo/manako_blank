<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_bisnis/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Bisnis.class.php';

class ViewInputBisnis extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_bisnis/template');
      $this->SetTemplateFile('input_bisnis.html');
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
      
      $bisnisObj  = new Bisnis();
      $dataBisnis = $bisnisObj->GetDataBisnisById($decID);
      $return['dataBisnis'] = $dataBisnis;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataBisnis     = $data['dataBisnis'];
      $dataBisnisUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
         
         $listBisnis          = $dataBisnisUbah[0];
         $listBisnis['IDD']   = $dataBisnisUbah[0]['idd'];
      } else {
   		if($dataBisnis){
	         $listBisnis        = $dataBisnis[0];
            $listBisnis['IDD'] = Dispatcher::Instance()->Encrypt($dataBisnis[0]['bisnisId']);
   		}
      }
      if (!empty($listBisnis)) {
         $this->mrTemplate->AddVars('data_bisnis_item', $listBisnis, 'BISNIS_');
         $this->mrTemplate->parseTemplate('data_bisnis_item', 'a');
      }

      if (empty($dataBisnis)) {
         $url     = 'AddBisnis';
         $tambah  = 'Tambah';
      } else {
         $url     = 'UpdateBisnis';
         $tambah  = 'Ubah';  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_bisnis', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_bisnis', 'bisnis', 'view', 'html') );
   }
}
?>
