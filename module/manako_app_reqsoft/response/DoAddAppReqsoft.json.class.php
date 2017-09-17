<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_reqsoft/response/ProcessAppReqsoft.proc.class.php';

class DoAddAppReqsoft extends JsonResponse {

   function ProcessRequest() {

      $appReqsoftObj = new ProcessAppReqsoft();
      
      $response = $appReqsoftObj->Add();
      
      return $response;
    }
}
?>
