<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact_field/response/ProcessContactField.proc.class.php';

class DoUpdateContactField extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {

      $contactFieldObj = new ProcessContactField();
      
      $urlRedirect = $contactFieldObj->Update();
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
