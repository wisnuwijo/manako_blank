<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_groupz/response/ProcessGroup2.proc.class.php';

class DoUpdateGroupApp2 extends JsonResponse {

   function ProcessRequest() {

      $appProductObj = new ProcessGroup2();
      
      $response = $appProductObj->UpdateGroupApp();
      
      return $response;
    }

}
?>
