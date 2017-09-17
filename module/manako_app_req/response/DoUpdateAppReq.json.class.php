<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_req/response/ProcessAppReq.proc.class.php';

class DoUpdateAppReq extends JsonResponse {

   function ProcessRequest() {

      $appReqObj = new ProcessAppReq();
      
      $response = $appReqObj->Update();
      
      return $response;
    }

}
?>
