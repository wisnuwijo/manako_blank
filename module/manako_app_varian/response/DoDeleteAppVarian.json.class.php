<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_varian/response/ProcessAppVarian.proc.class.php';

class DoDeleteAppVarian extends JsonResponse {

   function ProcessRequest() {
      $appVarianObj = new ProcessAppVarian();
      
      $response = $appVarianObj->Delete();

      return $response;
    }

}
?>
