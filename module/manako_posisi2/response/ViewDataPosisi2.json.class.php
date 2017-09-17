<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Posisi2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataPosisi2 extends JsonResponse {

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
      if ($dataDisplay == '-1') {
         $dataDisplay = '';
      }

      $posisiObj  = new Posisi2();
      if ($identifier == null) {
         $dataPosisiAll      = $posisiObj->GetDataPosisis();
         $dataPosisiTotal    = $posisiObj->GetTotalData();
         $dataPosisi         = $posisiObj->GetDataPosisis(null,$DTUniFind,$dataStart,$dataDisplay);
         $dataPosisiFiltered = $posisiObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataPosisiTotal;
         $dataTables['recordsFiltered'] = $dataPosisiFiltered;
      } elseif ($identifier == 'list') {
         $dataPosisi         = $posisiObj->GetDataPosisis();
      } else {
         $byIdentifier       = $identifier;
         $dataPosisi         = $posisiObj->GetDataPosisis($byIdentifier);
      }

      if (!empty($dataPosisi)) {
         $len        = sizeof($dataPosisi);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listPosisi[$i]['id']     = $dataPosisi[$i]['posisiCode'];
               $listPosisi[$i]['text']   = $dataPosisi[$i]['posisiName'];
            } else {
               $listPosisi[$i]           = $dataPosisi[$i];
            }            
         }         
         $return = MessageResult::Instance()->requestSukses($listPosisi, $dataTables);
      } else {
         $listPosisi = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listPosisi, $dataTables);
      }

      return $return;
   }
}
?>