<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Hotel.class.php';

class ViewInputHotel extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_hotel/template');
      $this->SetTemplateFile('view_input_hotel.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if ($msg) {
         $return['Pesan']  = $msg[0][1];
         $return['css']    = $msg[0][2];
         $return['Data']   = $msg[0];
      } else {
         $return['Pesan']  = null;
         $return['css']    = null;
         $return['Data']   = null;
      }

      $reqID = Dispatcher::Instance()->Decrypt($_REQUEST['id']);

      $object  = new Hotel();
      $dataKota   = $object->GetAllKota();
      $getHotel   = $object->GetHotelById($reqID);
      $getClient  = $object->GetClient();

      if ($return['Pesan']) {
         var_dump($return['Data'][0]);
      }
      
      $lenProv    = sizeof($dataKota);

      for ($i=0; $i < $lenProv ; $i++) { 
         $listProv[$i]['id']  = $dataKota[$i]['provKode'];
         $listProv[$i]['name']= $dataKota[$i]['provNama'];
         //echo "<pre>";var_dump($kota[$i]['provNama']);echo"</pre>";
      }

      if (!empty($getHotel)) {
         $kodeHotel  = $getHotel[0]['hotelKodeProv'];
         $lenHotel = sizeOf($getHotel);
         for ($i=0; $i < $lenHotel ; $i++) { 
            $kodeClient[$i]  = $getHotel[$i]['hotelClientClientId'];
         }
      } else {
         $kodeHotel = '';
         $kodeClient = '';
      }

      //echo "<pre>";var_dump($getHotel);echo"</pre>";

      $lenClient = sizeof($getClient);
      for ($i=0; $i < $lenClient ; $i++) { 
         $listClient[$i]['id'] = $getClient[$i]['clientId'];
         $listClient[$i]['name'] = $getClient[$i]['clientName'];
      }
      //echo "<pre>";var_dump($listClient);echo"</pre>";

      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'kotaKode', 
         array('kotaKode', $listProv, $kodeHotel, 'false', 'form_control', '', '', '', ''), Messenger::CurrentRequest);


      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'clientId',
         array('clientId[]', $listClient, isset($kodeClient)?$kodeClient:'', 'false', 'form-control', 'multiple', '', 'clientId'), Messenger::CurrentRequest);

      $return['dataHotel'] = $getHotel;

      //echo "<pre>";var_dump($getHotel);echo"</pre>";
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $hotel         = $data['dataHotel'];
      $hotelTambah   = $data['Data'];

      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);

         $this->mrTemplate->addVars('data', $hotelTambah[0], 'DATA_');
      }

      if (empty($hotel)) {
         $judul      = 'Add Hotel';
         $url        = 'AddHotel';

      } else {
         $judul      = 'Ubah Detail Hotel';
         $url        = 'UpdateHotel';

         $this->mrTemplate->addVars('data', $hotel[0], 'DATA_');
      }

      //echo "<pre>";var_dump($dataHotel);echo"</pre>";
      $this->mrTemplate->AddVar('content', 'JUDUL', $judul);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_hotel', $url, 'do', 'html'));
      $this->mrTemplate->AddVar('data', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_hotel', 'hotel', 'view', 'html'));

   }
}
?>
