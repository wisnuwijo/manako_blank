<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_posisi/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Posisi.class.php';

class ViewPosisi extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_posisi/template');
      $this->SetTemplateFile('view_posisi.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['name'] = '';
      }
      $return['filter'] = $filter;

      $posisiObj  = new Posisi();
      $dataPosisi = $posisiObj->GetDataPosisi($filter['name']);
	   $return['dataPosisi'] = $dataPosisi;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_posisi', 'posisi', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'POSISI_URL_ADD', Dispatcher::Instance()->GetUrl('manako_posisi', 'inputPosisi', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['name'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_MODEL', $data['filter']['name']);
      if (empty($data['dataPosisi'])) {
         $this->mrTemplate->AddVar('data_posisi', 'POSISI_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_posisi', 'POSISI_EMPTY', 'NO');
         $dataPosisi = $data['dataPosisi'];
         $len        = sizeof($dataPosisi);
         $no         = 0;
         for ($i=0; $i<$len; $i++) {
            $no++;
            $listPosisi[$no]       = $dataPosisi[$i];
            $listPosisi[$no]['no'] = $no;
         }
         
         //echo count($listPosisi);exit;
         $no=1;
         for($i=1;$i<count($listPosisi)+1;$i++){            
            $listPosisi[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataPosisi[$i]['class_name'] = 'table-common-even';
            } else {
               $dataPosisi[$i]['class_name'] = '';
            }
            $no++;

            $idEnc      = Dispatcher::Instance()->Encrypt($listPosisi[$i]['posisiId']);
            $listPosisi[$i]['url_edit']    = Dispatcher::Instance()->GetUrl('manako_posisi', 'inputPosisi', 'view', 'html') .$l .'idd=' . $idEnc;
                        
            @$urlAccept = 'manako_posisi|deletePosisi|do|html';
            @$urlReturn = 'manako_posisi|posisi|view|html';
            $label      = 'Posisi';
            $dataName   = $listPosisi[$i]['posisiName'];
            $listPosisi[$i]['url_delete']  = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_posisi_item', $listPosisi[$i], 'POSISI_');
            $this->mrTemplate->parseTemplate('data_posisi_item', 'a');
         }
      }
   }
}
?>
