<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_gforge/response/ProcessGforge.proc.class.php';

class DoDeleteGforge extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $gforgeObj = new ProcessGforge();
      
      $urlRedirect = $gforgeObj->Delete();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
