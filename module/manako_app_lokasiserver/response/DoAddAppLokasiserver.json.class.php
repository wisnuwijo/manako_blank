<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_lokasiserver/response/ProcessAppLokasiserver.proc.class.php';

class DoAddAppLokasiserver extends JsonResponse {

   function ProcessRequest() {

      $appLokasiserverObj = new ProcessAppLokasiserver();

      $response = $appLokasiserverObj->Add();

      return $response;
    }
}
?>
