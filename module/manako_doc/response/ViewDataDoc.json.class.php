<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_doc/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Doc.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataDoc extends JsonResponse {

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
      $appObj  = new Doc();
      if ($identifier == null) {
         $dataDocAll      = $appObj->GetDataDocs('',null,null);
         $dataDocTotal    = $appObj->GetTotalData();
         $dataDoc         = $appObj->GetDataDocs($DTUniFind,$dataStart,$dataDisplay);
         $dataDocFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataDocTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataDocFiltered[0]['totalData'];
      } else {
         $byId            = $identifier;
         $dataDoc         = $appObj->GetDataDoc($byId);
      }
      if (!empty($dataDoc)) {
         $len        = sizeof($dataDoc);
         for ($i=0; $i<$len; $i++) {
            $listDoc[$i]           = $dataDoc[$i];
         }         
         $return = MessageResult::Instance()->requestSukses($listDoc, $dataTables);
      } else {
         $listDoc = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listDoc, $dataTables);
      }
      return $return;    
   }
}
?>
