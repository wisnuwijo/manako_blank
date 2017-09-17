<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_client2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Client2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataClient2 extends JsonResponse {

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

      if (isset($_REQUEST['cat'])) {
         $clientCat     = $_REQUEST['cat'];
      } else {
         $clientCat     = null;
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

      $posisiObj  = new Client2();
      if ($identifier == null) {
         $dataClientAll      = $posisiObj->GetDataClients($clientCat,TRUE);
         $dataClientTotal    = $posisiObj->GetTotalData();
         $dataClient         = $posisiObj->GetDataClients($clientCat,TRUE,null,$DTUniFind,$dataStart,$dataDisplay);
         $dataClientFiltered = $posisiObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataClientTotal;
         $dataTables['recordsFiltered'] = $dataClientFiltered;
      } elseif ($identifier == 'list') {
         $dataClient         = $posisiObj->GetDataClients($clientCat,TRUE);
      } else {
         $byIdentifier       = $identifier;
         $dataClient         = $posisiObj->GetDataClients($clientCat,TRUE,$byIdentifier);
      }

      if (!empty($dataClient) AND ($coderef == 'id' OR $coderef == NULL OR $coderef == 'nick')) {
         $len        = sizeof($dataClient);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               if ($coderef == 'id') {
                  $listClient[$i]['id']     = $dataClient[$i]['clientId'];
               } elseif ($coderef == NULL OR $coderef == 'nick') {
                  $listClient[$i]['id']     = $dataClient[$i]['clientNick'];
               }
               $listClient[$i]['text']   = $dataClient[$i]['clientName'];
            } else {
               $listClient[$i]           = $dataClient[$i];
            }            
         }         
         $return = MessageResult::Instance()->requestSukses($listClient, $dataTables);
      } else {
         $listClient = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listClient, $dataTables);
      }

      return $return;
   }
}
?>