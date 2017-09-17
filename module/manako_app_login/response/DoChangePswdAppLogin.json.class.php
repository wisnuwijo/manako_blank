<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/response/ProcessAppLogin.proc.class.php';

class DoChangePswdAppLogin extends JsonResponse {

   function ProcessRequest() {

      $appLoginObj = new ProcessAppLogin();
      
      $response = $appLoginObj->ChangePswd();
      
      return $response;
    }

}
?>
