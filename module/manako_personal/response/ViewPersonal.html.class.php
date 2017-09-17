<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_personal/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Personal.class.php';

class ViewPersonal extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_personal/template');
      $this->SetTemplateFile('view_personal.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];
		
      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['personalName']   = '';
      }
      $return['filter'] = $filter;

      $personalObj  = new Personal();
      $dataPersonal = $personalObj->GetDataPersonal($filter['personalName']);
	   $return['dataPersonal'] = $dataPersonal;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $l          = '&';
      if ($urlType == "Simple") {
         $l       = '?';
      }

      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_personal', 'personal', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'PERSONAL_URL_ADD', Dispatcher::Instance()->GetUrl('manako_personal', 'inputPersonal', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (!empty($data['filter']['personalName'])) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }
      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_NAME', $data['filter']['personalName']);

      if (empty($data['dataPersonal'])) {
         $this->mrTemplate->AddVar('data_personal', 'PERSONAL_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_personal', 'PERSONAL_EMPTY', 'NO');
         $dataPersonal = $data['dataPersonal'];
         $len = sizeof($dataPersonal);
         $menuName='';
         $idPersonal='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            
               $no++;
               $listPersonal[$no]['no']=$no;
               $listPersonal[$no]['personalId']=$dataPersonal[$i]['personalId'];
               $listPersonal[$no]['personalName']=$dataPersonal[$i]['personalName'];
               $menuName='';
            
         }
         
         
         $no=1;
         for($i=1;$i<count($listPersonal)+1;$i++){            
            $listPersonal[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataPersonal[$i]['class_name'] = 'table-common-even';
            } else {
               $dataPersonal[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($listPersonal[$i]['personalId']);
            $listPersonal[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('manako_personal', 'inputPersonal', 'view', 'html') .$l . 'idd=' . $idEnc;

            $idEnc = Dispatcher::Instance()->Encrypt($listPersonal[$i]['personalId']);
                        
            @$urlAccept = 'manako_personal|deletePersonal|do|html';
            @$urlReturn = 'manako_personal|personal|view|html';
            $label = 'Personal';
            $dataName = $listPersonal[$i]['personalName'];
            $listPersonal[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').$l.'urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

            $this->mrTemplate->AddVars('data_personal_item', $listPersonal[$i], 'PERSONAL_');
            $this->mrTemplate->parseTemplate('data_personal_item', 'a');
         }
      }
   }
}
?>
