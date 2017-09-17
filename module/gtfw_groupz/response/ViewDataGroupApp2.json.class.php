<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_groupz/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Group2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataGroupApp2 extends JsonResponse {

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

      if (isset($urlSegment[2])) {
         $coderef    = $urlSegment[2];
      } elseif (isset($_REQUEST['coderef'])) {
         $coderef    = $_REQUEST['coderef'];
      } else {
         $coderef    = null;
      }

      /*Cook list*/
      $appObj  = new Group2();
      if ($identifier == null) {
         $dataGroupApp2         = Array();
      } else {
         $byIdentifier          = $identifier;
         $dataGroupApp2         = $appObj->GetDataGroupApp2($byIdentifier);
      }
      if (!empty($dataGroupApp2) AND ($coderef == 'id' OR $coderef == NULL OR $coderef == 'name')) {
         $len        = sizeof($dataGroupApp2);
         for ($i=0; $i<$len; $i++) {
            if ($coderef == NULL || $coderef == 'id') {
               $listGroupApp2[$i]['gpa_app']     = $dataGroupApp2[$i]['groupProjectAppAppId'];
            } elseif ($coderef == 'name') {
               $listGroupApp2[$i]['gpa_app']     = $dataGroupApp2[$i]['appName'];
            }
         }
         $return = MessageResult::Instance()->requestSukses($listGroupApp2);
      } else {
         $listGroupApp2 = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listGroupApp2);
      }
      return $return;  
   }
}
?>
