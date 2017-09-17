<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class DoLogout extends JsonResponse {

   function ProcessRequest() {
      Security::Instance()->Logout(TRUE);
      /*
      $module = Configuration::Instance()->GetValue( 'application', 'default_module');
      $submodule = Configuration::Instance()->GetValue( 'application', 'default_submodule');
      $action = Configuration::Instance()->GetValue( 'application', 'default_action');
      $type = Configuration::Instance()->GetValue( 'application', 'default_type');
      $urlRedirect = Dispatcher::Instance()->GetUrl($module, $submodule, $action, $type);
      */
      Log::Instance()->SendLog('[REST] Proses Logout Sukses');
      $return = MessageResult::Instance()->requestSukses();
      return $return;      
   }
}
?>
