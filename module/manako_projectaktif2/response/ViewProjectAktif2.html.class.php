<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_projectaktif2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/ProjectAktif2.class.php';

class ViewProjectAktif2 extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_projectaktif2/template');
      $this->SetTemplateFile('view_projectaktif2.html');
   }

   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      @$return['Pesan'] = $msg[0][1];
      @$return['css']   = $msg[0][2];

      $filter           = Array();
      $filter           = $_POST->AsArray();
      if (empty($filter)) {
         $filter['pr']     = '';
         $filter['pete']   = '';
         $filter['tahun']  = '';
         $filter['status']  = 'FilterStatusAktif';
      }
      if ($filter['tahun'] == '') {
         $filterTahun = '%';
      } else {
         $filterTahun = $filter['tahun'];
      }
      $return['filter'] = $filter;

      $listStatus   = array(
         array(
            'id' => 'FilterStatusSemua',
            'name' => 'Semua',
            ),
         array(
            'id' => 'FilterStatusAktif',
            'name' => 'Aktif',
            ),
         array(
            'id' => 'FilterStatusNonAktif',
            'name' => 'Non-Aktif',
            ),
         );
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'status',
         array('status',$listStatus,isset($filter['status'])?$filter['status']:'FilterStatusSemua','false','form-control'), Messenger::CurrentRequest);

      $projectObj  = new ProjectAktif2();
      $dataProject = $projectObj->GetDataProject($filter['pr'], $filter['pete'], $filterTahun);
	   $return['dataProject'] = $dataProject;

      return $return;
   }

   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manako_projectaktif2', 'projectAktif2', 'view', 'html') );
      //$this->mrTemplate->AddVar('content', 'PROJECT_URL_ADD', Dispatcher::Instance()->GetUrl('manako_projectaktif2', 'inputProject', 'view', 'html') );

      if ($data['Pesan']){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      $data['filter']['status']  = 'FilterStatusAktif';
      $filterField = !empty($data['filter']['pr']) || !empty($data['filter']['pete']) || !empty($data['filter']['tahun']) || $data['filter']['status'] != 'FilterStatusAktif';
      if ($filterField) {
         $filter_expand    = 'true';
         $filter_collpase  = 'in';
      } else {
         $filter_expand    = 'false';
         $filter_collpase  = '';
      }

      $this->mrTemplate->AddVar('content', 'EXPAND', $filter_expand);
      $this->mrTemplate->AddVar('content', 'COLLAPSE', $filter_collpase);
      $this->mrTemplate->AddVar('content', 'FILTER_PR', $data['filter']['pr']);
      $this->mrTemplate->AddVar('content', 'FILTER_PETE', $data['filter']['pete']);
      $this->mrTemplate->AddVar('content', 'FILTER_TAHUN', $data['filter']['tahun']);

      if (empty($data['dataProject'])) {
         $this->mrTemplate->AddVar('data_project', 'PROJECT_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_project', 'PROJECT_EMPTY', 'NO');
         $dataProject = $data['dataProject'];
         $len = sizeof($dataProject);
         $no=0;
         for ($i=0; $i<$len; $i++) {
            $listProject[$no]['no']=$no;
            $listProject[$no]=$dataProject[$i];

            //Status
               //Atur Tanggal Untuk Percobaan
               //$setNow = '2015-9-4';
               //$getNow = getdate();
               //$setNow = $getNow['mday'] .'-' .$getNow['mon'] .'-' .$getNow['year'];
               //$now    = date('Y-m-d', strtotime($setNow));
            $now    = date('Y-m-d');
            $listProject[$no]['date_start'] = date('d-m-Y', strtotime($dataProject[$i]['projectDateConStart']));
            $listProject[$no]['projectDateConEnd']=date('Y-m-d', strtotime($dataProject[$i]['projectDateConEnd']));
            $listProject[$no]['projectDateMtcEnd']=date('Y-m-d', strtotime($dataProject[$i]['projectDateMtcEnd']));
            $listProject[$no]['projectDateToleStart']=date('Y-m-d', strtotime($dataProject[$i]['projectDateToleStart']));
            $listProject[$no]['projectDateToleEnd']=date('Y-m-d', strtotime($dataProject[$i]['projectDateToleEnd']));
            $listProject[$no]['status_detail']='';
            if ($now <= $listProject[$no]['projectDateConEnd']) {
               $listProject[$no]['date_end']=date('d-m-Y', strtotime($listProject[$no]['projectDateConEnd']));
               $listProject[$no]['status_group']='Aktif';
            } else {
               if ($now <= $listProject[$no]['projectDateMtcEnd']) {
                  $listProject[$no]['date_end']=date('d-m-Y', strtotime($listProject[$no]['projectDateMtcEnd']));
                  $listProject[$no]['status_group']='Aktif';
                  $listProject[$no]['status_detail']='(Maintenance)';
               } elseif ($now > $listProject[$no]['projectDateMtcEnd'] && $dataProject[$i]['projectToleUse'] == 1 && $now >= $listProject[$no]['projectDateToleStart']) {
                  $listProject[$no]['date_end']=date('d-m-Y', strtotime($listProject[$no]['projectDateToleEnd']));
                  $listProject[$no]['status_group']='Aktif';
                  $listProject[$no]['status_detail']='(Toleransi)';
                  if ($now > $listProject[$no]['projectDateToleEnd']) {
                     $listProject[$no]['status_group']='Non-Aktif';
                  }
               } else {
                  $listProject[$no]['date_end']=date('d-m-Y', strtotime($listProject[$no]['projectDateConEnd']));
                  $listProject[$no]['status_group']='Non-Aktif';
               }
            }

            //Toleransi
            if ($dataProject[$no]['projectToleUse'] == 1) {
               $listProject[$no]['projectToleUse'] = 'Pakai';
               $listProject[$no]['projectDateToleStart']=date('d-m-Y', strtotime($listProject[$no]['projectDateToleStart']));
               $listProject[$no]['projectDateToleEnd']=date('d-m-Y', strtotime($listProject[$no]['projectDateToleEnd']));
            } else {
               $listProject[$no]['projectToleUse'] = 'Tidak Pakai';
               $listProject[$no]['projectDateToleStart']='-';
               $listProject[$no]['projectDateToleEnd']='-';
            }

            //Reformat
            $listProject[$no]['projectDateConEnd']=date('d-m-Y', strtotime($dataProject[$i]['projectDateConEnd']));
            if ($dataProject[$i]['projectDateMtcEnd']==NULL) {
              $listProject[$no]['projectDateMtcEnd']='-';
            } else {
              $listProject[$no]['projectDateMtcEnd']=date('d-m-Y', strtotime($dataProject[$i]['projectDateMtcEnd']));
            }
            if ($dataProject[$i]['projectDateBast']==NULL) {
              $listProject[$no]['projectDateBast']='-';
            } else {
              $listProject[$no]['projectDateBast']=date('d-m-Y', strtotime($dataProject[$i]['projectDateBast']));
            }

            $no++;
         }

         function FilterStatusSemua($var) {
            return (is_array($var) && $var['status_group'] == 'Aktif' || 'Non-Aktif');
         }
         function FilterStatusAktif($var) {
            return (is_array($var) && $var['status_group'] == 'Aktif');
         }
         function FilterStatusNonAktif($var) {
            return (is_array($var) && $var['status_group'] == 'Non-Aktif');
         }
         $listProject = array_values(array_filter($listProject, $data['filter']['status']));
         if (empty($listProject)) {
            $this->mrTemplate->AddVar('data_project', 'PROJECT_EMPTY', 'YES');
         }

         $no=1;
         for($i=0;$i<count($listProject);$i++){
            $listProject[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataProject[$i]['class_name'] = 'table-common-even';
            } else {
               $dataProject[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($listProject[$i]['projectId']);
            //$listProject[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('manako_projectaktif2', 'inputProject', 'view', 'html') . '&idd=' . $idEnc;
            $listProject[$i]['url_detail'] = Dispatcher::Instance()->GetUrl('manako_projectaktif2', 'detailProjectAktif2', 'view', 'html') . '&idd=' . $idEnc;
            /*
            @$urlAccept = 'manako_projectaktif2|deleteProject|do|';
            @$urlReturn = 'manako_projectaktif2|project|view|';
            $label = 'Project';
            $dataName = $listProject[$i]['projectName'];
            $listProject[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            */

            $this->mrTemplate->AddVars('data_project_item', $listProject[$i], 'PROJECT_');
            $this->mrTemplate->parseTemplate('data_project_item', 'a');
         }
      }
   }
}
?>
