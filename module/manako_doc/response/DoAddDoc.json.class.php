<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_doc/response/ProcessDoc.proc.class.php';

class DoAddDoc extends JsonResponse {

   function ProcessRequest() {

      $docObj = new ProcessDoc();
      
      $response = $docObj->Add();
      
      return $response;
    }
}
?>
