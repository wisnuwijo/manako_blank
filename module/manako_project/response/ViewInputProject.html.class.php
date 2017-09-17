<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_project/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Project.class.php';

class ViewInputProject extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_project/template');
      $this->SetTemplateFile('input_project.html');
   }

   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      if($msg){
	      @$return['Pesan'] = $msg[0][1];
         @$return['css']   = $msg[0][2];
	      @$return['Data']  = $msg[0];
      }else{
      	$return['Pesan'] = null;
         $return['css']   = null;
         $return['Data']  = null;
      }

      $decID = Dispatcher::Instance()->Decrypt($_REQUEST['idd']);
      if ($decID == '')
         $decID = Dispatcher::Instance()->Decrypt($return['Data']['0']['idd']);

      $projectObj = new Project();

      $dataProject   = $projectObj->GetDataProjectById($decID);

      $listClientPt  = $projectObj->GetListClientActiveOnly(2);
      $listClient    = $projectObj->GetListClientActiveXcpt(2);
      $listBisnis    = $projectObj->GetListBisnis();
      $listPersonal  = $projectObj->GetListPersonal();
      $listToleran   = array(
         array(
            'id' => 1,
            'name' => 'Pakai',
            ),
         array(
            'id' => 2,
            'name' => 'Tidak Pakai',
            ),
         );

      if ($return['Pesan']) {
         $projectData = $return['Data'][0];
      } else {
         if($dataProject){
            $projectData = $dataProject[0];
         }
      }
      //var_dump($dataProject);exit;
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'pete_id',
         array('pete_id',$listClientPt,isset($projectData['pete_id'])?$projectData['pete_id']:'','false','form-control'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'dokpro_id',
         array('dokpro_id',$listClient,isset($projectData['dokpro_id'])?$projectData['dokpro_id']:'1','false','form-control'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'dokcon_id',
         array('dokcon_id',$listClient,isset($projectData['dokcon_id'])?$projectData['dokcon_id']:'1','false','form-control'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'projectModel',
         array('projectModel',$listBisnis,isset($projectData['projectModel'])?$projectData['projectModel']:'','false','form-control'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'projectAmId',
         array('projectAmId',$listPersonal,isset($projectData['projectAmId'])?$projectData['projectAmId']:'','false','form-control'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox2', 'combobox2', 'view', 'html', 'projectToleUse',
         array('projectToleUse',$listToleran,isset($projectData['projectToleUse'])?$projectData['projectToleUse']:'','false','form-control'), Messenger::CurrentRequest);

      $return['dataProject'] = $dataProject;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataProject = $data['dataProject'];
      $dataProjectTambah = $data['Data'];
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);

         $listProject[0] = $dataProjectTambah[0];
         $listProject[0]['idd'] = $dataProjectTambah[0]['idd'];

      } else {
         //Set Date for Vanila Add
         //$getNow = getdate();
         //$setNow = $getNow['mday'] .'-' .$getNow['mon'] .'-' .$getNow['year'];
         //$now    = date('d-m-Y', strtotime($setNow));
         $now    = date('d-m-Y');
         $listProject[0]['projectDateConStart'] = $now;
         $listProject[0]['projectDateConEnd'] = $now;
         $listProject[0]['projectDateMtcEnd'] = $now;
         $listProject[0]['projectDateBast'] = $now;
         $listProject[0]['projectDateToleStart'] = $now;
         $listProject[0]['projectDateToleEnd'] = $now;
   		if($dataProject){
            $listProject[0] = $dataProject[0];
	         $listProject[0]['idd'] = Dispatcher::Instance()->Encrypt($dataProject[0]['projectId']);
   		}
      }

      if (empty($dataProject)) {
         $url='AddProject';
         $tambah='Tambah';
      } else {
         $url='UpdateProject';
         $tambah='Ubah';
      }

      $listProject[0]['projectDateConStart']=date('d-m-Y', strtotime($listProject[0]['projectDateConStart']));
      $listProject[0]['projectDateConEnd']=date('d-m-Y', strtotime($listProject[0]['projectDateConEnd']));
      if (strtotime($listProject[0]['projectDateMtcEnd'])==NULL) {
        $listProject[0]['projectDateMtcEnd'] ='';
      } else {
        $listProject[0]['projectDateMtcEnd']=date('d-m-Y', strtotime($listProject[0]['projectDateMtcEnd']));
      }
      if (strtotime($listProject[0]['projectDateBast'])==NULL) {
        $listProject[0]['projectDateBast'] ='';
      } else {
        $listProject[0]['projectDateBast']=date('d-m-Y', strtotime($listProject[0]['projectDateBast']));
      }
      if (strtotime($listProject[0]['projectDateToleStart'])==NULL) {
        $listProject[0]['projectDateToleStart'] ='';
      } else {
        $listProject[0]['projectDateToleStart']=date('d-m-Y', strtotime($listProject[0]['projectDateToleStart']));
      }
      if (strtotime($listProject[0]['projectDateToleEnd'])==NULL) {
        $listProject[0]['projectDateToleEnd'] ='';
      } else {
        $listProject[0]['projectDateToleEnd']=date('d-m-Y', strtotime($listProject[0]['projectDateToleEnd']));
      }

      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);

      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('manako_project', $url, 'do', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl('manako_project', 'project', 'view', 'html') );

      $this->mrTemplate->AddVars('data_project_item', $listProject[0], 'PROJECT_');
      $this->mrTemplate->parseTemplate('data_project_item', 'a');
   }
}
?>
