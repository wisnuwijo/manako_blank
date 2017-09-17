<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/App.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class DoDataApp extends JsonResponse {

   function TemplateModule() {
   }
   
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

      $msg = Messenger::Instance()->Receive(__FILE__); //Kayaknya nanti tetep diperlukan

      $dataStart     = $_REQUEST['start'];
      $dataDisplay   = $_REQUEST['length'];
      $DTdraw        = $_REQUEST['draw'];
      if (isset($_REQUEST['search'])) {
         $DTUniFind = $_REQUEST['search']['value'];
      } else {
         $DTUniFind = '';
      } // used to prevent error when use clientside processing
      

      //experimental not implemented yet
      /*if (isset($_REQUEST['filter'])) {
         $filter = $_REQUEST['filter'];
         foreach ($filter as $key => $value) {
            $filter[$key] = $value;
         }
      } else {
         $filter = '';
      }*/ // used to prevent error when use clientside processing
      
      /*Cook Filter*/
      // $filter           = Array();
      // $filter           = $_POST->AsArray();
      // if (empty($filter)) {
      //    $filter['model'] = '';
      // }
      // if (!empty($filter['model'])) {
      //    $filter['expand']    = 'true';
      //    $filter['collapse']  = 'in';
      // } else {
      //    $filter['expand']    = 'false';
      //    $filter['expand']    = '';
      // }      
      // $return['filter'] = $filter;

      /*Cook list*/
      if ($dataDisplay == '-1') {
         $dataDisplay = '';
      }
      $appObj  = new App();
      if ($identifier == null) {
         $dataAppAll      = $appObj->GetDataApps('',null,null,null);
         $dataAppTotal    = $appObj->GetTotalData();
         $dataApp         = $appObj->GetDataApps($DTUniFind,null,$dataStart,$dataDisplay);
         $dataAppFiltered = $appObj->GetTotalData();
      } elseif ($identifier == 'list') {
         $dataApp         = $appObj->GetDataApps('',null,null,null);
      } else {
         $byNick          = $identifier;
         $dataApp         = $appObj->GetDataApps(null,$byNick,null,null);
      }
      if (!empty($dataApp)) {
         $dataApp = $dataApp;
         $len        = sizeof($dataApp);
         for ($i=0; $i<$len; $i++) {
            if ($identifier == 'list') {
               $listApp[$i]['id']       = $dataApp[$i]['appId'];
               $listApp[$i]['text']     = $dataApp[$i]['appName'];
            } else {
               $listApp[$i]       = $dataApp[$i];
            }
            /*
            if ($identifier == null) {
               $idEnc      = Dispatcher::Instance()->Encrypt($listApp[$i]['appNick']);
               $listApp[$i]['gtfwUrls']['url_edit']    = Dispatcher::Instance()->GetUrl('manako_app', 'inputApp', 'view', 'html') . '&identifier=' . $listApp[$i]['appNick'];
                           
               $urlAccept = 'manako_app|deleteApp|do|json';
               $urlReturn = 'manako_app|app|view|html';
               $label      = 'App';
               $dataName   = $listApp[$i]['appName'];
               $listApp[$i]['gtfwUrls']['url_delete']  = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$listApp[$i]['appNick'].'&label='.$label.'&dataName='.$dataName;
            }
            */
         }         
         $return['status'] = 'OK';
      } else {
         $listApp          = array();
         $return['status'] = 'Not Found';
      }

      header('Content-Type: application/json');
      if ($identifier != null || $identifier == 'list') {
         $return['data'] = $listApp;
         echo json_encode($return);
      } else {
         $return['draw'] = $DTdraw;
         $return['recordsTotal'] = $dataAppTotal[0]['totalData'];
         $return['recordsFiltered'] = $dataAppFiltered[0]['totalData'];
         $return['data'] = $listApp;
         echo json_encode($return);
      }
      exit();      
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
