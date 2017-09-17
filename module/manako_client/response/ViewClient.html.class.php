<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Client.class.php';

class ViewClient extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_client/template');
      $this->SetTemplateFile('view_client.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['clientName']   = '';
         $filter['clientNick']   = '';
         $filter['kotaNama']     = '';
         $filter['provNama']     = '';
      }
      $return['filter'] = $filter;
      
      $clientObj  = new Client();
      $dataClient = $clientObj->GetDataClient($filter['clientName'], $filter['clientNick'], $filter['kotaNama'], $filter['provNama']);
      $dataProv     = $clientObj->GetProv();
      $dataKota     = $clientObj->GetKota();
      $gforgeBaseURL  = ' http://gforge2.gamatechno.net/gf/project/';

      $len = sizeof($dataProv);
      for ($i=0; $i<$len; $i++) {
         $listProv[$i]['id']   = $dataProv[$i]['provKode'];
         $listProv[$i]['name'] = $dataProv[$i]['provNama'];
      }
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'provNama',
         array('provNama',$listProv,$filter['provNama'],'false','form-control','','','','','-- SEMUA --'), Messenger::CurrentRequest);

      $len = sizeof($dataKota);
      $provId = "";
      for ($i=0; $i < $len; $i++) { 
         if ($provId != $dataKota[$i]['kotaProvKode']) {
            $j = 0;
         } else {
            $j++;
         }
            $provId   = $dataKota[$i]['kotaProvKode'];
            $kotaId   = $dataKota[$i]['kotaKode'];
            $kotaNama = $dataKota[$i]['kotaNama'];
            $kota[$provId][$j]["kota_id"]   = $kotaId;
            $kota[$provId][$j]["kota_nama"] = $kotaNama;
      }
      $kota = json_encode($kota);      

      $return['dataClient'] = $dataClient;
      $return['gforgeBaseURL'] = $gforgeBaseURL;
      $return['dataKota']     = $kota;
      $return['currentKota']  = $filter['kotaNama'];      
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }      

      $dataKota         = $data['dataKota'];
      $currentKota      = $data['currentKota'];

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_client', 'client', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'CLIENT_URL_ADD', Dispatcher::Instance()->GetUrl('manako_client', 'inputClient', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'DATAKOTA', $dataKota);
      $this->mrTemplate->AddVar('content', 'CURRENTKOTA', $currentKota); 

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['clientName']) || !empty($data['filter']['clientNick']) || !empty($data['filter']['provNama']) || !empty($data['filter']['kotaNama'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_NAME', $data['filter']['clientName']);
      $this->mrTemplate->AddVar('content', 'FILTER_NICK', $data['filter']['clientNick']);

      if (empty($data['dataClient'])) {
         $this->mrTemplate->AddVar('data_client', 'CLIENT_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_client', 'CLIENT_EMPTY', 'NO');
         $dataClient = $data['dataClient'];
         $len        = sizeof($dataClient);
         $idClient   ='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            if($idClient!=$dataClient[$i]['clientId']){
               $no++;
               $listClient[$no]['no']             = $no;
               $listClient[$no]['clientId']       = $dataClient[$i]['clientId'];
               $listClient[$no]['clientName']     = $dataClient[$i]['clientName'];
               $listClient[$no]['clientNick']     = $dataClient[$i]['clientNick'];
               $listClient[$no]['clientCatName']  = $dataClient[$i]['clientCatName'];
               $listClient[$no]['kotaNama']       = ucwords(strtolower($dataClient[$i]['kotaNama']));
               $listClient[$no]['provNama']       = $dataClient[$i]['provNama'];
               $listClient[$no]['gforgeNickname'] ='';
               $idClient=$listClient[$no]['clientId'];
               if ($dataClient[$i]['clientStatus'] == 1) {
                  $listClient[$no]['clientStatus'] = 'Aktif';
                  $listClient[$no]['status_icon']  = 'on';
                  $listClient[$no]['status_rule']  = 2;
                  $listClient[$no]['status_title'] = 'Non-aktifkan';
               } else {
                  $listClient[$no]['clientStatus'] = 'Non-Aktif';
                  $listClient[$no]['status_icon']  = 'off';
                  $listClient[$no]['status_rule']  = 1;
                  $listClient[$no]['status_title'] = 'Aktifkan';
               }
            }
            $listClient[$no]['gforgeNickname'] .= '<a href="'.$data['gforgeBaseURL'].$dataClient[$i]['gforgeNickname'].'/" target="_blank" title="Buka '.$dataClient[$i]['gforgeNickname'].' di gforge2 gamatechno">'.$dataClient[$i]['gforgeNickname'].'</a><br>';
         }
         
         
         $no=1;
         for($i=1;$i<count($listClient)+1;$i++){            
            $listClient[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataClient[$i]['class_name'] = 'table-common-even';
            } else {
               $dataClient[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($listClient[$i]['clientId']);
            $listClient[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('manako_client', 'inputClient', 'view', 'html') . '&idd=' . $idEnc;
            $listClient[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('manako_client', 'detailClient', 'view', 'html') . '&idd=' . $idEnc;

            $idEnc = Dispatcher::Instance()->Encrypt($listClient[$i]['clientId']);
                        
            @$urlAccept = 'manako_client|deleteClient|do|html';
            @$urlReturn = 'manako_client|client|view|html';
            $label = 'Client';
            $dataName = $listClient[$i]['clientName'];
            $statusRule = $listClient[$i]['status_rule'];
            $listClient[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $listClient[$i]['url_status'] = Dispatcher::Instance()->GetUrl('manako_client', 'statusClient', 'do', 'html').$l.'idd='. $idEnc.'&name='.$dataName.'&rules='.$statusRule;

            $this->mrTemplate->AddVars('data_client_item', $listClient[$i], 'CLIENT_');
            $this->mrTemplate->parseTemplate('data_client_item', 'a');
         }
      }
   }
}
?>
