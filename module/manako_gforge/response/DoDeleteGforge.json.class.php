<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_gforge/response/ProcessGforge.proc.class.php';

class DoDeleteGforge extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $gforgeObj = new ProcessGforge();
      
      $urlRedirect = $gforgeObj->Delete();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
