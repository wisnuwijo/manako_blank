<?php
/** 
* @author Zanuarestu Ramadhani
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_hotel/response/ProcessHotel.proc.class.php';

class DoDeleteHotel extends JsonResponse {

   function TemplateModule() {
   }
      
   function ProcessRequest() {
      $hotelObj   = new ProcessHotel();
      
      $urlRedirect = $hotelObj->Delete();
            
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');       
    }

   function ParseTemplate($data = NULL) {
   }    
}
?>
