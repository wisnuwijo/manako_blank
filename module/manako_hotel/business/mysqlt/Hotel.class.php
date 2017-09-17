<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class Hotel extends Database {

   protected $mSqlFile= 'module/manako_hotel/business/mysqlt/hotel.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //$this->setDebugOn();
   }
   
   function GetAllHotel($nama, $kodeprov) {
      $result = $this->open($this->mSqlQueries['get_data_hotel'], array('%'.$nama.'%', $kodeprov));
      return $result;
   }

   function GetHotelById($idhotel) {
      $result = $this->open($this->mSqlQueries['get_hotel_by_id'], array($idhotel));
      return $result;
   }

   function GetDetailHotel($idhotel) {
      $result = $this->open($this->mSqlQueries['get_detail_hotel'], array($idhotel));
      return $result;
      //echo"<pre>";var_dump($this->mSqlQueries['get_detail_hotel']);echo"</pre>";exit;
   }

   function GetClient() {
      $result = $this->open($this->mSqlQueries['get_client'], array());
      return $result;
   }

   function GetAllKota() {
      $result = $this->Open($this->mSqlQueries['get_kota'], array());
      return $result;
   }

   function DoAddHotel($nama, $kodeprov, $alamat, $phone, $harga, $fasil, $ket){
      $result = $this->Execute($this->mSqlQueries['do_add_hotel'], array($nama, $kodeprov, $alamat, $phone, $harga, $fasil, $ket));
      return $result;
   }

   function DoAddRelClient($idHotel, $idClient) {
      $result = $this->Execute($this->mSqlQueries['do_add_rel_client'], array($idHotel, $idClient));
      //var_dump ($this->mSqlQueries['do_add_rel_client'], array($idHotel, $idClient)); exit;
      return $result;
   }

   function DoUpdateHotel($idhotel, $nama, $kodeprov, $alamat, $phone, $harga, $fasil, $ket) {
      $result = $this->Execute($this->mSqlQueries['do_update_hotel'], array($nama, $kodeprov, $alamat, $phone, $harga, $fasil, $ket, $idhotel));
      //echo $this->mSqlQueries['do_update_hotel']; exit();
      return $result;
   }

   function DoDeleteHotel($idHotel) {
      $result = $this->Execute($this->mSqlQueries['do_delete_hotel'], array($idHotel));
      return $result;
   }

   function DoDeleteRelClient($idHotel) {
      $result = $this->Execute($this->mSqlQueries['do_delete_rel_client'], array($idHotel));
      return $result;
   }

   function GetMaxId() {
      $result  = $this->Open($this->mSqlQueries['get_max_id'], array());
      return $result;
   }

}