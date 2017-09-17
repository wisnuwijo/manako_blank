<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechj Indonesia
* @license http://gtfw.gamatechj.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataContact2 extends JsonResponse {

   function formatContact($dataContact=array()) {
      $listContact = array();
      $idContact   = '';
      $j           = 0;
      $k           = 0;
      for ($i=0; $i<sizeof($dataContact); $i++) {
         if($idContact!=$dataContact[$i]['contactId']){
            $listContact[$j]['contactId']             = $dataContact[$i]['contactId'];
            $listContact[$j]['contactNameFirst']      = $dataContact[$i]['contactNameFirst'];
            $listContact[$j]['contactNameLast']       = $dataContact[$i]['contactNameLast'];
            $listContact[$j]['contactMail']           = $dataContact[$i]['contactMail'];
            $listContact[$j]['contactMobile']         = $dataContact[$i]['contactMobile'];
            $listContact[$j]['contactClientNick']     = $dataContact[$i]['contactClientNick'];
            $listContact[$j]['clientName']            = $dataContact[$i]['clientName'];            
            $listContact[$j]['contactPosisiCode']     = $dataContact[$i]['contactPosisiCode'];
            $listContact[$j]['posisiName']            = $dataContact[$i]['posisiName'];            
            $listContact[$j]['contactPosisiDet']      = $dataContact[$i]['contactPosisiDet'];
            /*
            $qrCodeFormula    = $dataContact[$i]['contactNameLast'].$dataContact[$i]['contactNameFirst'].$dataContact[$i]['contactId'].'.png';
            $qrCodeFilename   = urlencode(urlencode(strtolower($qrCodeFormula)));
            $listContact[$j]['qrCode']                = $qrCodeFilename;
            */
            $idContact                                = $listContact[$j]['contactId'];
            $j++;
            $k                                        = 0;
         }
         $J = $j-1;
         $listContact[$J]['contactField'][$k]['contactDetContactFieldCode'] = $dataContact[$i]['contactDetContactFieldCode'];
         $listContact[$J]['contactField'][$k]['contactFieldIcon']           = $dataContact[$i]['contactFieldIcon'];
         $listContact[$J]['contactField'][$k]['contactFieldLabel']          = $dataContact[$i]['contactFieldLabel'];
         $listContact[$J]['contactField'][$k]['contactDetValue']            = $dataContact[$i]['contactDetValue'];
         $k++;
         if ($dataContact[$i]['contactDetContactFieldCode'] == null) {
            $listContact[$J]['contactField'] = array();
         }         
      }
      $return['listContact']     = $listContact;
      $return['recordsFiltered'] = $j;
      return $return;
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
      $appObj  = new Contact();
      if ($identifier == null) {
         $dataContactAll      = $appObj->GetDataContacts('',null,null,null,FALSE);
         $dataContactTotal    = $appObj->GetTotalData();
         $dataContact         = $appObj->GetDataContacts($DTUniFind,null,$dataStart,$dataDisplay);

         $formatedContact               = $this->formatContact($dataContact);
         $listContact                   = $formatedContact['listContact'];

         $dataTables['draw']            = $DTdraw;
         $dataTables['recordsTotal']    = $dataContactTotal[0]['totalData'];
         $dataTables['recordsFiltered'] = $formatedContact['recordsFiltered'];
      } else {
         $byNick                        = $identifier;
         $dataContact                   = $appObj->GetDataContacts('',$byNick,null,null);
         $formatedContact               = $this->formatContact($dataContact);
         $listContact                   = $formatedContact['listContact'];
      }
      if (!empty($dataContact)) {
         $listContactFinal = $listContact;
         $return = MessageResult::Instance()->requestSukses($listContactFinal, $dataTables);
      } else {
         $listContactFinal = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listContactFinal, $dataTables);
      }
      return $return;    
   }
}
?>
