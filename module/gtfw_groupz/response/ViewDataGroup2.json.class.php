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

class ViewDataGroup2 extends JsonResponse {

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

      $dataTables    = Array();
      $dataStart     = $_REQUEST['start'];
      $dataDisplay   = $_REQUEST['length'];
      $DTdraw        = $_REQUEST['draw'];
      if (isset($_REQUEST['search'])) {
         $DTUniFind = $_REQUEST['search']['value'];
      } else {
         $DTUniFind = '';
      } // used to prevent error when use clientside processing

      if (isset($_REQUEST['page'])) {
         if (is_object($_REQUEST)) {
            $req = $_REQUEST->AsArray();
         }
         $page          = $req['page'];
         $disp          = $req['length'];
         $dataStart     = $page*$disp;
      }

      /*Cook list*/
      if ($dataDisplay == '-1') {
         $dataDisplay = '';
      }
      $appObj  = new Group2();
      if ($identifier == null) {
         $dataGroup2All      = $appObj->GetDataGroup2();
         $dataGroup2Total    = $appObj->GetTotalData();
         $dataGroup2         = $appObj->GetDataGroup2(null,$DTUniFind,$dataStart,$dataDisplay);
         $dataGroup2Filtered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataGroup2Total;
         $dataTables['recordsFiltered'] = $dataGroup2Filtered;         
      } elseif ($identifier == 'list') {
         $dataGroup2         = $appObj->GetDataGroup2();
      } else {
         $byIdentifier       = $identifier;
         $dataGroup2         = $appObj->GetDataGroup2($byIdentifier);
      }
      if (!empty($dataGroup2)) {
         $len        = sizeof($dataGroup2);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listGroup2[$i]['id']     = $dataGroup2[$i]['group_id'];
               $listGroup2[$i]['text']   = $dataGroup2[$i]['group_name'];
            } else {
               $listGroup2[$i]           = $dataGroup2[$i];
            }
         }
         $return = MessageResult::Instance()->requestSukses($listGroup2, $dataTables);
      } else {
         $listGroup2 = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listGroup2, $dataTables);
      }
      return $return;  
   }
}
?>
