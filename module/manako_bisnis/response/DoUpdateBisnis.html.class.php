<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_bisnis/response/ProcessBisnis.proc.class.php';

class DoUpdateBisnis extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $bisnisObj = new ProcessBisnis();
      
      $urlRedirect = $bisnisObj->Update();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
