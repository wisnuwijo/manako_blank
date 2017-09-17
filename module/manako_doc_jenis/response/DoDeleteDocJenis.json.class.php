<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_doc_jenis/response/ProcessDocJenis.proc.class.php';

class DoDeleteDocJenis extends JsonResponse {

   function ProcessRequest() {

      $docJenisObj = new ProcessDocJenis();
      
      $response = $docJenisObj->Delete();
      
      return $response;
    }

}
?>
