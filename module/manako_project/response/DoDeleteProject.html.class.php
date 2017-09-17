<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_project/response/ProcessProject.proc.class.php';

class DoDeleteProject extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $projectObj = new ProcessProject();
      
      $urlRedirect = $projectObj->Delete();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
