<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Posisi.class.php';

class ViewInputPosisi extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_posisi/template');
      $this->SetTemplateFile('input_posisi.html');
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
      
      $posisiObj  = new Posisi();
      $dataPosisi = $posisiObj->GetDataPosisiById($decID);
      $return['dataPosisi'] = $dataPosisi;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataPosisi     = $data['dataPosisi'];
      $dataPosisiUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
         
         $listPosisi          = $dataPosisiUbah[0];
         $listPosisi['IDD']   = $dataPosisiUbah[0]['idd'];
      } else {
   		if($dataPosisi){
	         $listPosisi        = $dataPosisi[0];
            $listPosisi['IDD'] = Dispatcher::Instance()->Encrypt($dataPosisi[0]['posisiId']);
   		}
      }
      if (!empty($listPosisi)) {
         $this->mrTemplate->AddVars('data_posisi_item', $listPosisi, 'POSISI_');
         $this->mrTemplate->parseTemplate('data_posisi_item', 'a');
      }

      if (empty($dataPosisi)) {
         $url     = 'AddPosisi';
         $tambah  = 'Tambah';
      } else {
         $url     = 'UpdatePosisi';
         $tambah  = 'Ubah';  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_posisi', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_posisi', 'posisi', 'view', 'html') );
   }
}
?>
