<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Hotel.class.php';

class ViewDetailHotel extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_hotel/template');
      $this->SetTemplateFile('view_detail_hotel.html');
   }
   
   function ProcessRequest() {
      /*$msg = Messenger::Instance()->Receive(__FILE__);
      if ($msg) {
         $return['Pesan']  = $msg[0][1];
         $return['css']    = $msg[0][2];
         $return['Data']   = $msg[0];
      } else {
         $return['Pesan']  = null;
         $return['css']    = null;
         $return['Data']   = null;
      }*/

      $reqID = Dispatcher::Instance()->Decrypt($_REQUEST['id']);
      $object  = new Hotel();
      $getHotel   = $object->GetDetailHotel($reqID);

      $return['data'] = $getHotel;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataHotel  = $data['data'];
      $linkclient = Dispatcher::Instance()->GetUrl('manako_client', 'DetailClient', 'view', 'html').'&idd=';
      $len        = sizeof($dataHotel);
      $idHotel    = '';
      $no         = 0;
      for ($i=0; $i < $len ; $i++) { 
         if ($idHotel!=$dataHotel[$i]['hotelId']) {
            $no++;
            $listHotel[$no]                     = $dataHotel[$i];
            $listHotel[$no]['hotelNama']        = $dataHotel[0]['hotelNama'];
            $listHotel[$no]['hotelAlamat']      = $dataHotel[0]['hotelAlamat'];
            $listHotel[$no]['hotelPhone']       = $dataHotel[0]['hotelPhone'];
            $listHotel[$no]['hotelHarga']       = $dataHotel[0]['hotelHarga'];
            $listHotel[$no]['hotelFasilitas']   = $dataHotel[0]['hotelFasilitas'];
            $listHotel[$no]['hotelKeterangan']  = $dataHotel[0]['hotelKeterangan'];
            $listHotel[$no]['provNama']         = $dataHotel[0]['provNama'];
            $listHotel[$no]['clientName']       = '';

            $idHotel    = $listHotel[$no]['hotelId'];
         }
         if ($dataHotel[$i]['clientName'] != '') {
            $listHotel[$no]['clientName'].= '<a href="'.$linkclient.$dataHotel[$i]['clientId'].'" class="xhr dest_subcontent-element">'.$dataHotel[$i]['clientName'].'</a><br/>';
            //$dataHotel[$i]['clientName'];
            //'<a href="'.$linkclient.$dataHotel[$i]['clientId'].'" class="xhr dest_subcontent-element">'.$listHotel[$i]['clientName'].'</a><br/>';
         } else {
            $listHotel[$no]['clientName'].= '<i>---Tidak ada Client yang terkait dengan hotel ini---</i>';
         }
      }
      //echo "<pre>";var_dump($dataHotel);echo"</pre>";

      $idEnc = Dispatcher::Instance()->Encrypt($listHotel[$no]['hotelId']);
      $this->mrTemplate->AddVar('content', 'JUDUL', 'Detail');
      $this->mrTemplate->AddVar('content', 'URL_EDIT', Dispatcher::Instance()->GetUrl('manako_hotel', 'InputHotel', 'view', 'html').'&id='.$idEnc);
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_hotel', 'hotel', 'view', 'html'));

      $this->mrTemplate->AddVars('data_hotel_item', $listHotel[$no], 'HOTEL_');
      $this->mrTemplate->ParseTemplate('data_hotel_item', 'a');
   }
}
?>
