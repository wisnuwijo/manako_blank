<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact_field/response/ProcessContactField.proc.class.php';

class DoAddContactField extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {

      $contactFieldObj = new ProcessContactField();
      
      $urlRedirect = $contactFieldObj->Add();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
