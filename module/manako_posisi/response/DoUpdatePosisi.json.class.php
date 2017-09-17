<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi/response/ProcessPosisi.proc.class.php';

class DoUpdatePosisi extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $posisiObj = new ProcessPosisi();
      
      $urlRedirect = $posisiObj->Update();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
