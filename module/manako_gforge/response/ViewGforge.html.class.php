<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_gforge/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Gforge.class.php';

class Viewgforge extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_gforge/template');
      $this->SetTemplateFile('view_gforge.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['gforgeNickname']   = '';
      }
      $return['filter'] = $filter;

      $gforgeObj  = new Gforge();
      $dataGforge = $gforgeObj->GetDataGforge($filter['gforgeNickname']);
      $gforgeBaseURL  = ' http://gforge2.gamatechno.net/gf/project/';
	   $return['dataGforge'] = $dataGforge;
      $return['gforgeBaseURL'] = $gforgeBaseURL;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_gforge', 'gforge', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'GFORGE_URL_ADD', Dispatcher::Instance()->GetUrl('manako_gforge', 'inputGforge', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['gforgeNickname'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_NICKNAME', $data['filter']['gforgeNickname']);
      if (empty($data['dataGforge'])) {
         $this->mrTemplate->AddVar('data_gforge', 'GFORGE_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_gforge', 'GFORGE_EMPTY', 'NO');
         $dataGforge = $data['dataGforge'];
         $len        = sizeof($dataGforge);
         $idGforge   ='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            
               $no++;
               $listGforge[$no]['no']             = $no;
               $listGforge[$no]['gforgeId']       = $dataGforge[$i]['gforgeId'];
               $listGforge[$no]['gforgeNickname'] = $dataGforge[$i]['gforgeNickname'];
               $listGforge[$no]['gforgeURL']      = $data['gforgeBaseURL'] .$dataGforge[$i]['gforgeNickname'];
         }
         
         
         $no=1;
         for($i=1;$i<count($listGforge)+1;$i++){            
            $listGforge[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataGforge[$i]['class_name'] = 'table-common-even';
            } else {
               $dataGforge[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($listGforge[$i]['gforgeId']);
            $listGforge[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('manako_gforge', 'inputGforge', 'view', 'html') .$l .'idd=' . $idEnc;
                        
            @$urlAccept = 'manako_gforge|deleteGforge|do|html';
            @$urlReturn = 'manako_gforge|gforge|view|html';
            $label = 'Gforge';
            $dataName = $listGforge[$i]['gforgeNickname'];
            $listGforge[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_gforge_item', $listGforge[$i], 'GFORGE_');
            $this->mrTemplate->parseTemplate('data_gforge_item', 'a');
         }
      }
   }
}
?>