<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_req/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppReq.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppReq extends JsonResponse {

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
      $appObj  = new AppReq();
      if ($identifier == null) {
         $dataAppReqAll      = $appObj->GetDataAppReqs('',null,null,null);
         $dataAppReqTotal    = $appObj->GetTotalData();
         $dataAppReq         = $appObj->GetDataAppReqs($DTUniFind,null,$dataStart,$dataDisplay);
         $dataAppReqFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataAppReqTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataAppReqFiltered[0]['totalData'];
      } else {
         $byId                   = $identifier;
         $dataAppReq             = $appObj->GetDataAppReqs(null,$byId,null,null);
      }
      if (!empty($dataAppReq)) {
         $len        = sizeof($dataAppReq);
         for ($i=0; $i<$len; $i++) {
            $listAppReq[$i]           = $dataAppReq[$i];
         }         
         $return = MessageResult::Instance()->requestSukses($listAppReq, $dataTables);
      } else {
         $listAppReq = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppReq, $dataTables);
      }
      return $return;    
   }
}
?>
