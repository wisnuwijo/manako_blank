<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class DoLogin extends JsonResponse {

    function ProcessRequest() {

      if (Security::Instance()->Login($_REQUEST['username'].'', $_REQUEST['password'].'', $_REQUEST['hashed'].'' == 1)) {
         // redirect to proper place
         /*
         $module = 'home';
         $submodule = 'home';
         $action = 'view';
         $type = 'html';
         */
         Log::Instance()->SendLog('[REST] Proses Login Sukses');
         $userId     = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
         $userName   = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserName();
         $userReal   = Security::Instance()->mAuthentication->GetCurrentUser()->GetRealName();
         $data       = array("userId"=>$userId, "userName"=>$userName, "userReal"=>$userReal);
         $return     = MessageResult::Instance()->requestSukses($data);
      } else {
         Log::Instance()->SendLog('[REST] Proses Login Gagal');
         $return = MessageResult::Instance()->dataTidakDitemukan();
      }
      return $return;
    }
}
?>
