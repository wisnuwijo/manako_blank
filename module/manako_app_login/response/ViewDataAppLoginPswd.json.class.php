<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLogin.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataAppLoginPswd extends JsonResponse {

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
         $dataact    = $urlSegment[2];
      } elseif (isset($_REQUEST['dataact'])) {
         $dataact    = $_REQUEST['dataact'];
      } else {
         $dataact    = null;
      }

      if (isset($_REQUEST['data'])) {
         $receivedData    = $_REQUEST['data'];
      } else {
         $receivedData    = null;
      }

      /*Cook list*/
      $appObj  = new AppLogin();
      if ($identifier != null && ($dataact == 'eye' || $dataact == 'validation')) {
         $dataAppLoginPswd = $appObj->GetDataAppLoginPswd($identifier);
      }
      if ($dataact == 'eye') {
         if (!empty($dataAppLoginPswd)) {
            $listAppLoginPswd = array("pswd" => $dataAppLoginPswd[0]['loginPswd']);
            $return = MessageResult::Instance()->requestSukses($listAppLoginPswd);
         } else {
            $listAppLoginPswd = array();
            $return = MessageResult::Instance()->dataTidakDitemukan($listAppLoginPswd);
         }
      } elseif ($dataact == 'validation') {
         if (!empty($dataAppLoginPswd) && ($receivedData == $dataAppLoginPswd[0]['loginPswd'])) {
            $listAppLoginPswd = array();
            $return = MessageResult::Instance()->requestSukses($listAppLoginPswd);
         } else {
            $listAppLoginPswd = array();
            $return = MessageResult::Instance()->dataTidakDitemukan($listAppLoginPswd);
         }         
      } else {
         $listAppLoginPswd = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listAppLoginPswd);         
      }
      return $return;    
   }
}
?>
