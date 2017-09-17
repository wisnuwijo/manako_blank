<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataOperandi extends JsonResponse {

   function ProcessRequest() {
      $baseDir    = Configuration::Instance()->GetValue('application', 'basedir');
      $urlSegment = urlHelper::Instance()->segments($_SERVER['REQUEST_URI'], $baseDir);

      if (isset($urlSegment[1])) {
         $identifier    = $urlSegment[1];
      } elseif (isset($_REQUEST['identifier'])) {
         $identifier    = $_REQUEST['identifier'];
      } else {
         $identifier    = null;
      }

      /*Cook list*/
      if ($identifier == null) {
         $listOperandi = array();
      } elseif ($identifier == 'list') {
         $listOperandi = array(
                           array("id" => "<", "text" => "(<) Lebih kecil"),
                           array("id" => ">", "text" => "(>) Lebih besar"),
                           array("id" => "=", "text" => "(=) Sama dengan/harus"),
                           array("id" => "<=", "text" => "(<=) Lebih kecil sama dengan"),
                           array("id" => ">=", "text" => "(>=) Lebih besar sama dengan")
                        );         
      } else {
         $listOperandi = array();
      }
      if (!empty($listOperandi)) {       
         $return = MessageResult::Instance()->requestSukses($listOperandi);
      } else {
         $return = MessageResult::Instance()->dataTidakDitemukan($listOperandi);
      }
      return $return;    
   }
}
?>
