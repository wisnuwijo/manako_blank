<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/response/ProcessHotel.proc.class.php';

class DoAddHotel extends HtmlResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {
      $hotelObj   = new ProcessHotel();
      
      $urlRedirect = $hotelObj->Add();
            
      $this->RedirectTo($urlRedirect) ;      
      
      return NULL;
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
