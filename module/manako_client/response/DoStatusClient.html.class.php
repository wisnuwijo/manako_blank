<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client/response/ProcessClient.proc.class.php';

class DoStatusClient extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $clientObj = new ProcessClient();
            
      $urlRedirect = $clientObj->StatusChange();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
