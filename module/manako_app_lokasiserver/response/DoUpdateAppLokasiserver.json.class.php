<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_lokasiserver/response/ProcessAppLokasiserver.proc.class.php';

class DoUpdateAppLokasiserver extends JsonResponse {

   function ProcessRequest() {

      $appLokasiserverObj = new ProcessAppLokasiserver();

      $response = $appLokasiserverObj->Update();

      return $response;
    }

}
?>
