<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_varian/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppVarian.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppVarian extends JsonResponse {

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
      $appObj  = new AppVarian();
      if ($identifier == null) {
         $dataAppVarianAll      = $appObj->GetDataAppVarians('',null,null,null);
         $dataAppVarianTotal    = $appObj->GetTotalData();
         $dataAppVarian         = $appObj->GetDataAppVarians($DTUniFind,null,$dataStart,$dataDisplay);
         $dataAppVarianFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataAppVarianTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataAppVarianFiltered[0]['totalData'];
      } elseif ($identifier == 'list') {
         $dataAppVarian         = $appObj->GetDataAppVarians('',null,null,null);
      } else {
         $byNick                = $identifier;
         $dataAppVarian         = $appObj->GetDataAppVarians(null,$byNick,null,null);
      }
      if (!empty($dataAppVarian)) {
         $len        = sizeof($dataAppVarian);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listAppVarian[$i]['id']      = $dataAppVarian[$i]['varianId'];
               $listAppVarian[$i]['text']    = $dataAppVarian[$i]['varianName'];
            } else {
               $listAppVarian[$i]            = $dataAppVarian[$i];
            }
         }
         $return = MessageResult::Instance()->requestSukses($listAppVarian, $dataTables);         
      } else {
         $listAppVarian = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppVarian, $dataTables);
      }  
      return $return;   
   }
}
?>
