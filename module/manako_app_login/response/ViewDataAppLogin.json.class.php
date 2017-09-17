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

class ViewDataAppLogin extends JsonResponse {

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
      $appObj        = new AppLogin();
      $userGroup     = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetActiveUserGroupId());
      if ($identifier == null) {
         $dataAppLoginAll      = $appObj->GetDataAppLogins($userGroup);
         $dataAppLoginTotal    = $appObj->GetTotalData();
         $dataAppLogin         = $appObj->GetDataAppLogins($userGroup,null,$DTUniFind,$dataStart,$dataDisplay);
         $dataAppLoginFiltered = $appObj->GetTotalData();

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataAppLoginTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $dataAppLoginFiltered[0]['totalData'];
      } elseif ($identifier == 'list') {
         $dataAppLogin         = null;
      } else {
         $byId                 = $identifier;
         $dataAppLogin         = $appObj->GetDataAppLogins($userGroup,$byId);
      }
      if (!empty($dataAppLogin)) {
         $len           = sizeof($dataAppLogin);
         $listAppLogin  = $dataAppLogin;
         $return        = MessageResult::Instance()->requestSukses($listAppLogin, $dataTables);
      } else {
         $listAppLogin  = array();
         $return        = MessageResult::Instance()->dataTidakDitemukan($listAppLogin, $dataTables);
      }
      return $return;    
   }
}
?>
