<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Client.class.php';

class ViewInputClient extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_client/template');
      $this->SetTemplateFile('input_client.html');
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
      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data']['0']['idd']);

      $clientObj    = new Client();
      $dataClient   = $clientObj->GetDataClientById($decID);
      $dataGforge   = $clientObj->GetUnrelGforge();
      $dataKategori = $clientObj->GetClientKategori();
      $dataProv     = $clientObj->GetProv();
      $dataKota     = $clientObj->GetKota();

      if ($return['Pesan']) {
         $dataCurrent = $return['Data'][0];
         $len = sizeof($return['Data'][0]['gforgeNickname']);
         for ($i=0; $i<$len; $i++) {
            $clientData[$i] = $return['Data'][0]['gforgeNickname'][$i];
         }
      } else {
         if($dataClient){
            $dataCurrent = $dataClient[0];
            $len = sizeof($dataClient);
            for ($i=0; $i<$len; $i++) {
               $clientData[$i] = $dataClient[$i]['gforgeId'];
            }
         }
      }
      $len = sizeof($dataGforge);
      for ($i=0; $i<$len; $i++) {
         $unrelGforge[$i]['id']   = $dataGforge[$i]['gforgeId'];
         $unrelGforge[$i]['name'] = $dataGforge[$i]['gforgeNickname'];
      }
      $len = sizeof($dataClient);
      for ($i=0; $i<$len; $i++) {
         $selectedGforge[$i]['id']   = $dataClient[$i]['gforgeId'];
         $selectedGforge[$i]['name'] = $dataClient[$i]['gforgeNickname'];
      }
      if (empty($dataClient) || ($dataClient[0]['gforgeId'] == null) && ($dataClient[0]['gforgeNickname'] == null)) {
         if ($dataGforge) {
            $listGforge = $unrelGforge;
         } else {
            $listGforge = array(
         array(
            'id'   => '',
            'name' => 'Tidak ada data, tambah data dahulu.',
            'attribute' => 'disabled="disabled"'
            )         
         );
         }
      } else {
         if ($dataGforge) {
            $listGforge = array_merge($selectedGforge,$unrelGforge);
         } else {
            $listGforge = $selectedGforge;
         }
      }
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'gforgeNickname',
         array('gforgeNickname[]',$listGforge,isset($clientData)?$clientData:'','false','form-control','multiple','','gforgeNickname'), Messenger::CurrentRequest);

      $listStatus   = array(
         array(
            'id'   => '1',
            'name' => 'Aktif',
            ),
         array(
            'id'   => '2',
            'name' => 'Nonaktif',
            ),         
         );
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'clientStatus',
         array('clientStatus',$listStatus,isset($dataCurrent['clientStatus'])?$dataCurrent['clientStatus']:'1','false','form-control','',''), Messenger::CurrentRequest);

      $len = sizeof($dataKategori);
      for ($i=0; $i<$len; $i++) {
         $listKategori[$i]['id'] = $dataKategori[$i]['clientCatId'];
         $listKategori[$i]['name'] = $dataKategori[$i]['clientCatName'];
      }
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'clientClientCatId',
         array('clientClientCatId',$listKategori,isset($dataCurrent['clientClientCatId'])?$dataCurrent['clientClientCatId']:'2','false','form-control','',''), Messenger::CurrentRequest);
 
      $len = sizeof($dataProv);
      for ($i=0; $i<$len; $i++) {
         $listProv[$i]['id']   = $dataProv[$i]['provKode'];
         $listProv[$i]['name'] = $dataProv[$i]['provNama'];
      }
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'kotaProvKode',
         array('kotaProvKode',$listProv,isset($dataCurrent['kotaProvKode'])?$dataCurrent['kotaProvKode']:'','false','form-control','',''), Messenger::CurrentRequest);

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

      if (isset($dataCurrent['clientKotaKode'])) {
         $dataCurrent['clientKotaKode'] = $dataCurrent['clientKotaKode'];
      } else {
         $dataCurrent['clientKotaKode'] = '';
      }

      $return['dataClient']   = $dataClient;
      $return['dataGforge']   = $dataGforge;
      $return['dataKota']     = $kota;
      $return['currentKota']  = $dataCurrent['clientKotaKode'];
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataClient       = $data['dataClient'];
      $dataGforge       = $data['dataGforge'];
      $dataClientTambah = $data['Data'];
      $dataKota         = $data['dataKota'];
      $currentKota      = $data['currentKota'];
      
      $this->mrTemplate->AddVar('content', 'DATAKOTA', $dataKota);
      $this->mrTemplate->AddVar('content', 'CURRENTKOTA', $currentKota); 

      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);

         $this->mrTemplate->AddVar('content', 'IDD', $dataClientTambah[0]['idd']);
         $this->mrTemplate->AddVar('content', 'CLIENTNAME', $dataClientTambah[0]['clientName']);
         $this->mrTemplate->AddVar('content', 'CLIENTNICK', $dataClientTambah[0]['clientNick']);
      } else {
   		if($dataClient){

	         $this->mrTemplate->AddVar('content', 'IDD', Dispatcher::Instance()->Encrypt($dataClient[0]['clientId']));
	         $this->mrTemplate->AddVar('content', 'CLIENTNAME', $dataClient[0]['clientName']);
	         $this->mrTemplate->AddVar('content', 'CLIENTNICK', $dataClient[0]['clientNick']);
   		}
      }

      if (empty($dataClient)) {
         $url='AddClient';
         $tambah='Tambah';         
      } else {
         $url='UpdateClient';
         $tambah='Ubah';          
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVar('gforge_checkbox', 'RULE', $tambah);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_client', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_client', 'client', 'view', 'html') );
   }
}
?>
