<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Client.class.php';

class ViewDetailClient extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_client/template');
      $this->SetTemplateFile('detail_client.html');
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

      $clientObj  = new Client();
      $dataClient = $clientObj->GetDataClientById($decID);
      $gforgeBaseURL  = ' http://gforge2.gamatechno.net/gf/project/';
      $return['dataClient'] = $dataClient;
      $return['gforgeBaseURL'] = $gforgeBaseURL;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataClient = $data['dataClient'];
   	
      if($dataClient){
         $len = sizeof($dataClient);
         $idClient='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            if($idClient!=$dataClient[$i]['clientId']){
               $no++;
               $listClient[$no]                   = $dataClient[$i];
               $listClient[$no]['kotaNama']       = ucwords(strtolower($dataClient[$i]['kotaNama']));
               $listClient[$no]['idd']            = Dispatcher::Instance()->Encrypt($dataClient[$i]['clientId']);
               $listClient[$no]['gforgeNickname'] ='';
               $idClient=$listClient[$no]['clientId'];
               if ($dataClient[$i]['clientStatus'] == 1) {
                  $listClient[$no]['clientStatus'] = 'Aktif';
               } else {
                  $listClient[$no]['clientStatus'] = 'Non-Aktif';
               }
            }
            if ($dataClient[$i]['gforgeNickname'] != null) {
               $listClient[$no]['gforgeNickname'].= '<a href="'.$data['gforgeBaseURL'].$dataClient[$i]['gforgeNickname'].'/" target="_blank" title="Buka '.$dataClient[$i]['gforgeNickname'].' di gforge2 gamatechno">'.$dataClient[$i]['gforgeNickname'].'</a><br>';
            } else {
               $listClient[$no]['gforgeNickname'].= '<i>--Tidak ada project di gforge yang dimiliki--</i>';
            }
            
         }
      }
      $idEnc = Dispatcher::Instance()->Encrypt($listClient[$no]['clientId']);

      $this->mrTemplate->AddVar('content', 'JUDUL', 'Detail');
      $this->mrTemplate->AddVar('content', 'URL_EDIT', Dispatcher::Instance()->GetUrl('manako_client', 'inputClient', 'view', 'html') . '&idd=' . $idEnc);
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_client', 'client', 'view', 'html') );

      $this->mrTemplate->AddVars('data_client_item', $listClient[$no], 'CLIENT_');
      $this->mrTemplate->parseTemplate('data_client_item', 'a');
   }
}
?>
