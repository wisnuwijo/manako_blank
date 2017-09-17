<?php
/**
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_lokasiserver/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLokasiserver.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppLokasiserver extends JsonResponse {

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
      $appObj  = new AppLokasiserver();
      if ($identifier == null) {
         $dataAppLokasiserverAll      = $appObj->GetDataAppLokasiservers('',null,null,null);
         $dataAppLokasiserverTotal    = $appObj->GetTotalData();
         $dataAppLokasiserver         = $appObj->GetDataAppLokasiservers($DTUniFind,null,$dataStart,$dataDisplay);
         $dataAppLokasiserverFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataAppLokasiserverTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataAppLokasiserverFiltered[0]['totalData'];
      } elseif ($identifier == 'list') {
         $dataAppLokasiserver         = $appObj->GetDataAppLokasiservers('',null,null,null);
      } else {
         $byName                 = $identifier;
         $dataAppLokasiserver         = $appObj->GetDataAppLokasiservers(null,$byName,null,null);
      }
      if (!empty($dataAppLokasiserver)) {
         $len        = sizeof($dataAppLokasiserver);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listAppLokasiserver[$i]['id']     = $dataAppLokasiserver[$i]['lokasiserverId'];
               $listAppLokasiserver[$i]['text']   = $dataAppLokasiserver[$i]['lokasiserverName'];
            } else {
               $listAppLokasiserver[$i]           = $dataAppLokasiserver[$i];
            }
         }
         $return = MessageResult::Instance()->requestSukses($listAppLokasiserver, $dataTables);
      } else {
         $listAppLokasiserver = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppLokasiserver, $dataTables);
      }
      return $return;
   }
}
?>
