<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_loginrole/response/ProcessAppLoginrole.proc.class.php';

class DoAddAppLoginrole extends JsonResponse {

   function ProcessRequest() {

      $appLoginroleObj = new ProcessAppLoginrole();
      
      $response = $appLoginroleObj->Add();
      
      return $response;
    }
}
?>
