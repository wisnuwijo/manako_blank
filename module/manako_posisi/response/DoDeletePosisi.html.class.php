<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi/response/ProcessPosisi.proc.class.php';

class DoDeletePosisi extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {

      $posisiObj = new ProcessPosisi();
      
      $urlRedirect = $posisiObj->Delete();     
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
