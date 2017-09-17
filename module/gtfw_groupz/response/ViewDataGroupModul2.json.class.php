<?php
/** 
* WEB SERVICE RESPONSE
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_groupz/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Group2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/gtfw_rest_result/business/MessageResult.class.php';

class ViewDataGroupModul2 extends JsonResponse {

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
         $selection    = $urlSegment[2];
      } elseif (isset($_REQUEST['selection'])) {
         $selection    = $_REQUEST['selection'];
      } else {
         $selection    = null;
      }

      /*Cook list*/
      $appObj  = new Group2();
      if ($identifier == null) {
         $dataGroupModulSubmenu  = Array();
         $dataGroupModulMenu     = Array();
      } else {
         $byIdentifier           = $identifier;
         $dataGroupModulMenu     = $appObj->GetDataGroupModul('menu',$byIdentifier);
         $dataGroupModulSubmenu  = $appObj->GetDataGroupModul('submenu',$byIdentifier);
         $dataGroupModul         = array_merge($dataGroupModulMenu, $dataGroupModulSubmenu);
         if ($selection == 'complete') {
            $len1 = sizeof($dataGroupModulSubmenu);
            for ($i=0; $i < $len1; $i++) { 
               $j = $dataGroupModulSubmenu[$i]['menu_menuid'];
               $dataGroupModulSelected[$j] = true;
            }

            $dataRegisteredModulMenu    = $appObj->GetDataRegisteredModul('menu');
            $dataRegisteredModulSubmenu = $appObj->GetDataRegisteredModul('submenu');
            $dataGroupModul             = array_merge($dataRegisteredModulMenu, $dataRegisteredModulSubmenu);
         }
      }
      if (!empty($dataGroupModul) AND ($selection == 'active' || $selection == 'complete')) {
         $lenA        = sizeof($dataGroupModul);
         for ($i=0; $i<$lenA; $i++) {
            $listGroupModul[$i]['id']     = $dataGroupModul[$i]['menu_menuid'];
            $listGroupModul[$i]['parent'] = $dataGroupModul[$i]['menu_parent'];
            $listGroupModul[$i]['text']   = $dataGroupModul[$i]['menu_submenu'];   
            $listGroupModul[$i]['icon']   = 'fa '.$dataGroupModul[$i]['menu_icon'];
            if ($listGroupModul[$i]['parent'] == 0) {
               $listGroupModul[$i]['parent'] = '#';
            }
            if ($selection == 'complete') {
               $j = $dataGroupModul[$i]['menu_menuid'];
               if (isset($dataGroupModulSelected[$j])) {
                  $listGroupModul[$i]['state']['checked'] = 'true';
               }
            }           
         }
         $return = MessageResult::Instance()->requestSukses($listGroupModul);
      } else {
         $listGroupModul = array();
         $return = MessageResult::Instance()->dataTidakDitemukan($listGroupModul);
      }
      return $return;  
   }
}
?>