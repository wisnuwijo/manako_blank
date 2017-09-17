<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_product/response/ProcessAppProduct.proc.class.php';

class DoDeleteAppProduct extends JsonResponse {

   function ProcessRequest() {

      $appProductObj = new ProcessAppProduct();
      
      $response = $appProductObj->Delete();
      
      return $response;
    }

}
?>
