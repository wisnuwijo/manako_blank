<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Hotel.class.php';

class ProcessHotel {

	var $_POST;
	var $hotelObj;
	var $pageView;
	var $pageInput;

	var $cssDone = "alert alert-success";
	var $cssFail = "alert alert-danger";

	var $return;

	function __construct() {
		$this->hotelObj 	= new Hotel();

		$this->_POST 		= $_POST->AsArray();
		$this->PageView 	= Dispatcher::Instance()->GetUrl('manako_hotel', 'Hotel', 'view', 'html');
		$this->PageInput 	=  Dispatcher::Instance()->GetUrl('manako_hotel', 'InputHotel', 'view', 'html');
	}

	function SendAlert($alert, $sub_modul, $css) {
		Messenger::Instance()->Send('manako_hotel', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css), Messenger::NextRequest);
		//var_dump(array($this->_POST, $alert, $css));exit;
	}

	function Update() {
		//echo "<pre>"; var_dump($_POST); echo "</pre>";
		$updateHotel 		= $this->hotelObj->DoUpdateHotel($_POST['hotelId'],  $_POST['hotelNama'], $_POST['kotaKode'], $_POST['hotelAlamat'], $_POST['hotelPhone'], $_POST['hotelHarga'], $_POST['hotelFasilitas'], $_POST['hotelKeterangan']);
		$deleteRelClient 	= $this->hotelObj->DoDeleteRelClient($_POST['hotelId']);

		$processData = $updateHotel && $deleteRelClient;
		if (!empty($_POST['clientId'])) {
			foreach ($_POST['clientId'] as $contact) {
				$addRelClient = $this->hotelObj->DoAddRelClient($_POST['hotelId'], $contact);
			}
			$processData = $processData && $addRelClient;
		}

		if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'Hotel', $this->cssDone);
			return $this->PageView;
			//echo "berhasil"; exit;
		} else {
            $this->SendAlert('Gagal Mengubah Data', 'InputHotel', $this->cssFail);
			return $this->PageInput;
			//echo "gagal"; exit;
		}
	}

	function add() {
		unset ($this->_POST['hotelId']);
		foreach ($this->_POST as $field) {
			if (empty($field)) {
	            $this->SendAlert('Tidak Boleh ada Field yang kosong', 'InputHotel', $this->cssFail);
				return $this->PageInput;
			}
		}

		$addHotel 		= $this->hotelObj->DoAddHotel($_POST['hotelNama'], $_POST['kotaKode'], $_POST['hotelAlamat'], $_POST['hotelPhone'], $_POST['hotelHarga'], $_POST['hotelFasilitas'], $_POST['hotelKeterangan']);
		$processData 	= $addHotel;

		$lastInsertedId = $this->hotelObj->GetMaxId();
		$insertedHotelId= $lastInsertedId[0]['max_id'];
		//echo "<pre>"; echo var_dump($insertedHotelId); echo "</pre>";exit;

		if (!empty($_POST['clientId'])) {
			foreach ($_POST['clientId'] as $contact) {
				$addRelContact = $this->hotelObj->DoAddRelClient($insertedHotelId, $contact);
			}
			$processData = $processData && $addRelContact;
		}

		//echo "<pre>"; echo var_dump($_POST); echo "</pre>";exit;

		if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'Hotel', $this->cssDone);
			return $this->PageView;
			//echo "Penambahan Data Berhasil"; exit;
		} else {
            $this->SendAlert('Gagal Menambah Data', 'InputHotel', $this->cssFail);
			return $this->PageInput;
			//echo "Penambahan Data Gagal"; exit;
		}
	}

	function Delete() {
		$deleteHotel = $this->hotelObj->DoDeleteHotel($_POST['idDelete']);
		$processData = $deleteHotel;
		if ($processData == true) {
		    $this->SendAlert('Data Berhasil Dihapus', 'Hotel', $this->cssDone);
			return $this->PageView;
			//echo "berhasil Menghapus data"; exit;
		} else {
	        $this->SendAlert('Gagal Menghapus Data', 'Hotel', $this->cssFail);
			return $this->PageView;
		}
	}
}

?>