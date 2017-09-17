<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Hotel.class.php';

class ViewHotel extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_hotel/template');
      $this->SetTemplateFile('view_hotel.html');
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

      $filter  = array();
      $filter  = $_POST->AsArray();
      if(empty($filter)) {
         $filter['hotelNama'] = '';
         $filter['provKode']  = '%';
      }
      if ($filter['provKode'] == '') {
         $filter['provKode']  = '%';
      }
      $return['filter']  = $filter;

      $object  = new Hotel();

      $dataKota   = $object->GetAllKota();
      $lenProv    = sizeof($dataKota);

      for ($i=0; $i < $lenProv ; $i++) { 
         $listProv[$i]['id']  = $dataKota[$i]['provKode'];
         $listProv[$i]['name']= $dataKota[$i]['provNama'];
         //echo "<pre>";var_dump($kota[$i]['provNama']);echo"</pre>";
      }
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'provNama',
         array('provKode', $listProv, $filter['provKode'], 'false', 'form-control', '', '', '', '', '--- SEMUA ---'), Messenger::CurrentRequest);

      $data    = $object->GetAllHotel($filter['hotelNama'], $filter['provKode']);

      //echo "<pre>";var_dump($data);echo"</pre>";

      $return['data'] = $data;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }

      if (!empty($data['filter']['hotelNama']) || $data['filter']['provKode'] != '%') {
         $filter_expand    = 'true';
         $filter_collapse  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collapse  = '';
      }
      $this->mrTemplate->addVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_hotel', 'hotel', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collapse);
      $this->mrTemplate->AddVar('content', 'FILTER_NAME', $data['filter']['hotelNama']);

      $this->mrTemplate->addVar('content', 'HOTEL_URL_ADD', Dispatcher::Instance()->GetUrl('manako_hotel', 'inputHotel', 'view', 'html'));
      $hotel   = $data['data'];
      //echo "<pre>";var_dump($hotel);echo "<pre>";
      if (empty($hotel)) {
         $this->mrTemplate->AddVar('data_hotel', 'HOTEL_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_hotel', 'HOTEL_EMPTY', 'NO');
         $len     = sizeof($hotel);
         $idHotels= '';
         $no      = 0;
         for ($i=0; $i < $len; $i++) { 
            if ($idHotels!=$hotel[$i]['hotelId']) {
               $no++;
               //echo 'ini nomor:'.$no.',ini Urut =>'.$i.'<br />';
               $listhotel[$no]['no']                     = $no;
               $listhotel[$no]['hotelId']                = $hotel[$i]['hotelId'];
               $listhotel[$no]['hotelNama']              = $hotel[$i]['hotelNama'];
               $listhotel[$no]['hotelAlamat']            = $hotel[$i]['hotelAlamat'];
               $listhotel[$no]['provNama']               = $hotel[$i]['provNama'];
               $listhotel[$no]['hotelPhone']             = $hotel[$i]['hotelPhone'];
               $listhotel[$no]['hotelHarga']             = $hotel[$i]['hotelHarga'];
               $listhotel[$no]['hotelFasilitas']         = $hotel[$i]['hotelFasilitas'];
               $listhotel[$no]['hotelKeterangan']        = $hotel[$i]['hotelKeterangan'];
               $listhotel[$no]['clientName']             = '';

               @$urlAccept    = 'manako_hotel|deleteHotel|do|html';
               @$urlReturn    = 'manako_hotel|hotel|view|html';
               $label         = 'Hotel';
               $dataNameLabel = $listhotel[$no]['hotelNama'];

               $idHotel                   = Dispatcher::Instance()->Encrypt($hotel[$i]['hotelId']);
               $listhotel[$no]['EDIT']    = Dispatcher::Instance()->GetUrl('manako_hotel', 'inputHotel', 'view', 'html').'&id='.$idHotel;
               $listhotel[$no]['DETAIL']  = Dispatcher::Instance()->GetUrl('manako_hotel', 'detailHotel', 'view', 'html').'&id='.$idHotel;
               $listhotel[$no]['HAPUS']   = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='. $urlReturn.'&id='. $idHotel.'&label='. $label.'&dataName='.$dataNameLabel;
               $idHotels   = $listhotel[$no]['hotelId'];
            }
            $linkcontact   = Dispatcher::Instance()->GetUrl('manako_client', 'DetailClient', 'view', 'html').'&idd=';

            $listhotel[$no]['clientName'] .= '<a href="'.$linkcontact.$hotel[$i]['hotelClientClientId'].'" class="xhr dest_subcontent-element">'.$hotel[$i]['clientName'].'</a><br/>';

            //$this->mrTemplate->AddVars('table', $listhotel[$no], 'HOTEL_');
            //$this->mrTemplate->parseTemplate('table', 'a');
         }
         $lensize = sizeof($listhotel);
         for ($l=1; $l <= $lensize ; $l++) { 
            $this->mrTemplate->AddVars('table', $listhotel[$l], 'HOTEL_');
            $this->mrTemplate->parseTemplate('table', 'a');
         }
      }
   }
}
?>
