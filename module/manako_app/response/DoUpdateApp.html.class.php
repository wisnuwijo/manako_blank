<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/response/ProcessApp.proc.class.php';

class DoUpdateApp extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $appObj = new ProcessApp();
      
      $urlRedirect = $appObj->Update();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
