<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_bisnis/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Bisnis.class.php';

class ViewBisnis extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_bisnis/template');
      $this->SetTemplateFile('view_bisnis.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['model'] = '';
      }
      $return['filter'] = $filter;

      $bisnisObj  = new Bisnis();
      $dataBisnis = $bisnisObj->GetDataBisnis($filter['model']);
	   $return['dataBisnis'] = $dataBisnis;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_bisnis', 'bisnis', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'BISNIS_URL_ADD', Dispatcher::Instance()->GetUrl('manako_bisnis', 'inputBisnis', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['model'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_MODEL', $data['filter']['model']);
      if (empty($data['dataBisnis'])) {
         $this->mrTemplate->AddVar('data_bisnis', 'BISNIS_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_bisnis', 'BISNIS_EMPTY', 'NO');
         $dataBisnis = $data['dataBisnis'];
         $len        = sizeof($dataBisnis);
         $no         = 0;
         for ($i=0; $i<$len; $i++) {
            $no++;
            $listBisnis[$no]       = $dataBisnis[$i];
            $listBisnis[$no]['no'] = $no;
         }
         
         //echo count($listBisnis);exit;
         $no=1;
         for($i=1;$i<count($listBisnis)+1;$i++){            
            $listBisnis[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataBisnis[$i]['class_name'] = 'table-common-even';
            } else {
               $dataBisnis[$i]['class_name'] = '';
            }
            $no++;

            $idEnc      = Dispatcher::Instance()->Encrypt($listBisnis[$i]['bisnisId']);
            $listBisnis[$i]['url_edit']    = Dispatcher::Instance()->GetUrl('manako_bisnis', 'inputBisnis', 'view', 'html') .$l .'idd=' . $idEnc;
                        
            @$urlAccept = 'manako_bisnis|deleteBisnis|do|html';
            @$urlReturn = 'manako_bisnis|bisnis|view|html';
            $label      = 'Bisnis';
            $dataName   = $listBisnis[$i]['bisnisModel'];
            $listBisnis[$i]['url_delete']  = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_bisnis_item', $listBisnis[$i], 'BISNIS_');
            $this->mrTemplate->parseTemplate('data_bisnis_item', 'a');
         }
      }
   }
}
?>
