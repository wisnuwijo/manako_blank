<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_personal/response/ProcessPersonal.proc.class.php';

class DoAddPersonal extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {

      $personalObj = new ProcessPersonal();
      
      $urlRedirect = $personalObj->Add();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
