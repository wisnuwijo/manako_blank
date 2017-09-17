<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewLogin extends JsonResponse {

    function ProcessRequest() {

        if (Security::Instance()->IsLoggedIn()) {
            
        }

        $data   = array("salt"=>Security::Instance()->RequestSalt());
        $return = MessageResult::Instance()->requestSukses($data);

        return $return;
    }
}
?>
