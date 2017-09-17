<?php
/** 
* @author Abdul R. Wahid
* @version 1.0
**/
	class urlHelper{
		static $mrInstance;
		
		public function segments($requestURI, $basedir='') {
			$_SERVER['REQUEST_URI_PATH'] 		= parse_url($requestURI, PHP_URL_PATH);
			$_SERVER['REQUEST_URI_PATH_ONLY'] 	= str_replace($basedir, '', $_SERVER['REQUEST_URI_PATH']);
			$segments 							= explode('/', $_SERVER['REQUEST_URI_PATH_ONLY']);
			return $segments;
		}

		static function Instance() {
			if (!isset(self::$mrInstance)){
				self::$mrInstance = new urlHelper();	
			}
			return self::$mrInstance;
		}		
	}
?>