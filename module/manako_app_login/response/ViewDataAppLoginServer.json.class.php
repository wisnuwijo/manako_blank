<?php
/**
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppLoginServer extends JsonResponse {

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
         $listAppLoginServer = array();
      } elseif ($identifier == 'list') {
         $listAppLoginServer = array(
                           array("id" => "acs.gt.net Testing", "text" => "acs.gt.net Testing"),
                           array("id" => "acs.gt.net Client", "text" => "acs.gt.net Client"),
                           array("id" => "Client Devel", "text" => "Client Devel"),
                           array("id" => "Client Produksi", "text" => "Client Produksi")
                        );
      } else {
         $listAppLoginServer = array();
      }
      if (!empty($listAppLoginServer)) {
         $return = MessageResult::Instance()->requestSukses($listAppLoginServer);
      } else {
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppLoginServer);
      }
      return $return;
   }
}
?>
