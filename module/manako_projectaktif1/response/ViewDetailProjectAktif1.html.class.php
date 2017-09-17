<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_project/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Project.class.php';

class ViewDetailProject extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_project/template');
      $this->SetTemplateFile('detail_project.html');
   }

   function ProcessRequest() {
      /*
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      $return['Pesan'] = $msg[0][1];
         $return['css'] = $msg[0][2];
	      $return['Data'] = $msg[0];
      }else{
      	$return['Pesan'] = null;
         $return['css'] = null;
         $return['Data'] = null;
      }
      */
      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);

      $projectObj = new Project();

      $dataProject   = $projectObj->GetDataProjectById($decID);

      $return['dataProject'] = $dataProject;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataProject = $data['dataProject'];
   	if($dataProject){
         $listProject[0]        = $dataProject[0];
         $listProject[0]['idd'] = Dispatcher::Instance()->Encrypt($dataProject[0]['projectId']);
         if ($dataProject[0]['projectToleUse'] == 1) {
            $listProject[0]['projectToleUse'] = 'Pakai';
         } else {
            $listProject[0]['projectToleUse'] = 'Tidak Pakai';
         }
		}

      //Status
      $now    = date('Y-m-d');
      $listProject[0]['projectDateConEnd']=date('Y-m-d', strtotime($listProject[0]['projectDateConEnd']));
      $listProject[0]['projectDateMtcEnd']=date('Y-m-d', strtotime($listProject[0]['projectDateMtcEnd']));
      $listProject[0]['projectDateToleStart']=date('Y-m-d', strtotime($dataProject[0]['projectDateToleStart']));
      $listProject[0]['projectDateToleEnd']=date('Y-m-d', strtotime($dataProject[0]['projectDateToleEnd']));
      $listProject[0]['status_detail']='';
      if ($now <= $listProject[0]['projectDateConEnd']) {
         $listProject[0]['date_end']=$listProject[0]['projectDateConEnd'];
         $listProject[0]['status_group']='Aktif';
         $listProject[0]['status_css'] = 'success';
      } else {
         $listProject[0]['status_css'] = 'danger';
         if ($now <= $listProject[0]['projectDateMtcEnd']) {
            $listProject[0]['date_end']=$listProject[0]['projectDateMtcEnd'];
            $listProject[0]['status_group']='Aktif';
            $listProject[0]['status_detail']='(Maintenance)';
            $listProject[0]['status_css'] = 'info';
         } elseif ($now > $listProject[0]['projectDateMtcEnd'] && $dataProject[0]['projectToleUse'] == 1 && $now >= $listProject[0]['projectDateToleStart']) {
            $listProject[0]['date_end']=$listProject[0]['projectDateToleEnd'];
            $listProject[0]['status_group']='Aktif';
            $listProject[0]['status_detail']='(Toleransi)';
            $listProject[0]['status_css'] = 'warning';
            if ($now > $listProject[0]['projectDateToleEnd']) {
               $listProject[0]['status_group']='Non-Aktif';
               $listProject[0]['status_css'] = 'danger';
            }
         } else {
            $listProject[0]['date_end']=$listProject[0]['projectDateConEnd'];
            $listProject[0]['status_group']='Non-Aktif';
         }
      }

      //Reformat
      $listProject[0]['projectDateConStart']=date('d-m-Y', strtotime($listProject[0]['projectDateConStart']));
      $listProject[0]['projectDateConEnd']=date('d-m-Y', strtotime($listProject[0]['projectDateConEnd']));
      if (!empty($this->post['projectDateMtcEnd'])) {
         $projectDateMtcEnd    = date('Y-m-d', strtotime($this->post['projectDateMtcEnd']));
      } else {
        $projectDateMtcEnd = NULL;
      }
      if (!empty($this->post['projectDateBast'])) {
         $projectDateBast    = date('Y-m-d', strtotime($this->post['projectDateBast']));
      } else {
        $projectDateBast = NULL;
      }
      $listProject[0]['date_end']=date('d-m-Y', strtotime($listProject[0]['date_end']));

      if ($listProject[0]['projectToleUse']=='Pakai') {
         $listProject[0]['projectDateToleStart']=date('d-m-Y', strtotime($listProject[0]['projectDateToleStart']));
         $listProject[0]['projectDateToleEnd']=date('d-m-Y', strtotime($listProject[0]['projectDateToleEnd']));
      } else {
         $listProject[0]['projectDateToleStart']='<i>--Tidak menggunakan toleransi--</i>';
         $listProject[0]['projectDateToleEnd']='<i>--Tidak menggunakan toleransi--</i>';
      }

      $idEnc = Dispatcher::Instance()->Encrypt($listProject[0]['projectId']);

      $this->mrTemplate->AddVar('content', 'JUDUL', 'Detail');
      $this->mrTemplate->AddVar('content', 'URL_EDIT', Dispatcher::Instance()->GetUrl('manako_project', 'inputProject', 'view', 'html') . '&idd=' . $idEnc);
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_project', 'project', 'view', 'html') );

      $this->mrTemplate->AddVars('data_project_item', $listProject[0], 'PROJECT_');
      $this->mrTemplate->parseTemplate('data_project_item', 'a');
   }
}
?>
