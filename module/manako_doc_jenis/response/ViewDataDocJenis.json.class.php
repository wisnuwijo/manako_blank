<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_doc_jenis/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/DocJenis.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataDocJenis extends JsonResponse {

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
      $appObj  = new DocJenis();
      if ($identifier == null) {
         $dataDocJenisAll      = $appObj->GetDataDocJenises('',null,null,null);
         $dataDocJenisTotal    = $appObj->GetTotalData();
         $dataDocJenis         = $appObj->GetDataDocJenises($DTUniFind,null,$dataStart,$dataDisplay);
         $dataDocJenisFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataDocJenisTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataDocJenisFiltered[0]['totalData'];
      } elseif ($identifier == 'list') {
         $dataDocJenis         = $appObj->GetDataDocJenises('',null,null,null);
      } else {
         $byNick               = $identifier;
         $dataDocJenis         = $appObj->GetDataDocJenises(null,$byNick,null,null);
      }
      if (!empty($dataDocJenis)) {
         $len        = sizeof($dataDocJenis);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listDocJenis[$i]['id']     = $dataDocJenis[$i]['docJenisId'];
               $listDocJenis[$i]['text']   = $dataDocJenis[$i]['docJenisNick'].' ('.$dataDocJenis[$i]['docJenisName'].')';
            } else {
               $listDocJenis[$i]           = $dataDocJenis[$i];
            }
         }         
         $return = MessageResult::Instance()->requestSukses($listDocJenis, $dataTables);
      } else {
         $listDocJenis = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listDocJenis, $dataTables);
      }
      return $return;     
   }
}
?>
