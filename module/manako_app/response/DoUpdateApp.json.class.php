<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/response/ProcessApp.proc.class.php';

class DoUpdateApp extends JsonResponse {

   function ProcessRequest() {

      $appObj = new ProcessApp();
      
      $response = $appObj->Update();

      return $response;
      
      // return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }
}
?>
