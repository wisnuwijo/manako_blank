<?php

/*
	@ClassName : Message
	@Copyright : PT. Gamatechno Indonesia
	@Analyzed By : Dyan Galih Nugroho Wicaksi
	@Author By : Dyan Galih Nugroho Wicaksi
	@Modified By : Abdul Rohman Wahid
	@Version : 02
	@StartDate : 2013-01-01
	@LastUpdate : 2015-11-04
	@Description : Format Message Return
*/

class MessageResult
{
	
	private static $mrInstance=null;
	
	function __construct (){

	}
	
   public static function instance(){
      if(self::$mrInstance === null){
         $class_name = __CLASS__;
         self::$mrInstance = new $class_name();
      }
      return self::$mrInstance;
   }
	
	public function formatMessage($status, $data='', $extData=array()){
		header('Content-Type: application/json');
	   $msg["status"] 	= $status;
	   $msg["message"] 	= Configuration::Instance()->getValue('message','msg'.$status);
	   $msg["data"] 		= $data;
	   $msg 					= Array("status"=>$msg["status"], "message"=>$msg["message"], "data" => $msg["data"]);
	   return array_merge($msg, $extData);
	}
	
	public function requestSukses($data='', $extData=array()){
	   $status = '200';
	   return $this->formatMessage($status, $data, $extData);
	}
	
	public function penyimpananSukses($data='', $extData=array()){
	   $status = '201';
		return $this->formatMessage($status, $data, $extData);  
	}
	
	public function dataTidakLengkap($data='', $extData=array()){
	   $status = '204';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function dataDitemukan($data='', $extData=array()){
	   $status = '302';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function requestTidakSesuai($data='', $extData=array()){
	   $status = '400';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function aksesDenied($data='', $extData=array()){
	   $status = '401';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function tidakDiperbolehkan($data='', $extData=array()){
	   $status = '403';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function dataTidakDitemukan($data='', $extData=array()){
	   $status = '404';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function methodDitolak($data='', $extData=array()){
	   $status = '405';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function penyimpananGagal($data='', $extData=array()){
	   $status = '406';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function butuhOtentikasiProxy($data='', $extData=array()){
	   $status = '407';
		return $this->formatMessage($status, $data, $extData); 
	}
	
	public function urlTidakTersedia($data='', $extData=array()){
	   $status = '410';
		return $this->formatMessage($status, $data, $extData); 
	}
}

?>
