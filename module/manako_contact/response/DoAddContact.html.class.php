<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact/response/ProcessContact.proc.class.php';

class DoAddContact extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {

      $contactObj = new ProcessContact();
      
      $urlRedirect = $contactObj->Add();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
