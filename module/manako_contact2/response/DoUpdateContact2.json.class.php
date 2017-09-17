<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact2/response/ProcessContact2.proc.class.php';

class DoUpdateContact2 extends JsonResponse {
   
   function ProcessRequest() {

      $contactObj = new ProcessContact2();
      
      $response = $contactObj->Update();
      
      return $response;       
    }

}
?>
