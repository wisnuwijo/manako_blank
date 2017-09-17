<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_bisnis/response/ProcessBisnis.proc.class.php';

class DoDeleteBisnis extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $bisnisObj = new ProcessBisnis();
      
      $urlRedirect = $bisnisObj->Delete();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
