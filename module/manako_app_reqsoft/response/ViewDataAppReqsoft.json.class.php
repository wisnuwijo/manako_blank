<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_reqsoft/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppReqsoft.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppReqsoft extends JsonResponse {

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
      $appObj  = new AppReqsoft();
      if ($identifier == null) {
         $dataAppReqsoftAll      = $appObj->GetDataAppReqsofts('',null,null,null);
         $dataAppReqsoftTotal    = $appObj->GetTotalData();
         $dataAppReqsoft         = $appObj->GetDataAppReqsofts($DTUniFind,null,$dataStart,$dataDisplay);
         $dataAppReqsoftFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataAppReqsoftTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataAppReqsoftFiltered[0]['totalData'];
      } elseif ($identifier == 'list') {
         $dataAppReqsoft         = $appObj->GetDataAppReqsofts('',null,null,null);
      } else {
         $byNick                 = $identifier;
         $dataAppReqsoft         = $appObj->GetDataAppReqsofts(null,$byNick,null,null);
      }
      if (!empty($dataAppReqsoft)) {
         $len        = sizeof($dataAppReqsoft);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listAppReqsoft[$i]['id']     = $dataAppReqsoft[$i]['reqsoftId'];
               $listAppReqsoft[$i]['text']   = $dataAppReqsoft[$i]['reqsoftNick'];
            } else {
               $listAppReqsoft[$i]           = $dataAppReqsoft[$i];
            }            
         }         
         $return = MessageResult::Instance()->requestSukses($listAppReqsoft, $dataTables);
      } else {
         $listAppReqsoft = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppReqsoft, $dataTables);
      }
      return $return;    
   }
}
?>
