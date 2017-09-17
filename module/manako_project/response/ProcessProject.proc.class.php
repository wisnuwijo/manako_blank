<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_project/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Project.class.php';

class ProcessProject {


   var $_POST;
   var $projectObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
   var $cssDone = "alert alert-success";
   var $cssFail = "alert alert-danger";

   var $return;

   function __construct() {
      $this->projectObj = new Project();

      $this->post = $_POST->AsArray();

      $this->pageView = Dispatcher::Instance()->GetUrl('manako_project', 'project', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('manako_project', 'inputProject', 'view', 'html');
   }

   function SendAlert($alert, $sub_modul, $css) {
      Messenger::Instance()->Send('manako_project', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }

   function Add() {

      $cek_nick = $this->projectObj->GetCountDuplicateNickAdd($_POST['projectNick']);
      if ($cek_nick[0]['COUNT'] != 0) {
         $this->SendAlert("Nick project \"".$_POST['projectNick']."\" sudah ada.", 'inputProject', $this->cssFail);
         return $this->pageInput;
      } else {
         if (empty($_POST['pete_id']) || $_POST['pete_id'] == "") {
            $this->SendAlert('Gagal Menambah Data', 'inputProject', $this->cssFail);
            return $this->pageInput;
         }
         //setdate
         $projectDateConStart  = date('Y-m-d', strtotime($_POST['projectDateConStart']));
         $projectDateConEnd    = date('Y-m-d', strtotime($_POST['projectDateConEnd']));
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
         $projectDateToleStart = date('Y-m-d', strtotime($_POST['projectDateToleStart']));
         $projectDateToleEnd   = date('Y-m-d', strtotime($_POST['projectDateToleEnd']));
         //var_dump($projectDateMtcEnd);exit;
         //var_dump($this->post['projectDateMtcEnd']);exit;
         $addProject = $this->projectObj->DoAddProject(
            $_POST['pete_id'],
            $_POST['dokpro_id'],
            $_POST['dokcon_id'],
            $_POST['projectNick'],
            $_POST['projectName'],
            $_POST['projectDes'],
            $projectDateConStart,
            $projectDateConEnd,
            $projectDateMtcEnd,
            $_POST['projectModel'],
            $_POST['projectOrder'],
            $_POST['projectAmId'],
            $projectDateBast,
            $projectDateToleStart,
            $projectDateToleEnd,
            $_POST['projectToleUse'],
            $_POST['projectNote']
            );

         $processData = $addProject;

         if ($processData == true) {
            $this->SendAlert('Penambahan data Berhasil Dilakukan', 'project', $this->cssDone);
            return $this->pageView;
			} else {
			   $this->SendAlert('Gagal Menambah Data', 'inputProject', $this->cssFail);
            return $this->pageInput;
			}
      }
   }

   function Update() {
      //var_dump($_POST);exit;
      $cek_nick = $this->projectObj->GetCountDuplicateNick($_POST['projectNick'], $_POST['idd']);
      if ($cek_nick[0]['COUNT'] != 0) {
         $this->SendAlert("Nick project \"".$_POST['projectNick']."\" sudah ada.", 'inputProject', $this->cssFail);
         return $this->pageInput;
      } else {
         $UpdateTime    = date('Y-m-d H:i:s');
         $UpdateUser    = trim(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());
         //setdate
         $projectDateConStart  = date('Y-m-d', strtotime($_POST['projectDateConStart']));
         $projectDateConEnd    = date('Y-m-d', strtotime($_POST['projectDateConEnd']));
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
         $projectDateToleStart = date('Y-m-d', strtotime($_POST['projectDateToleStart']));
         $projectDateToleEnd   = date('Y-m-d', strtotime($_POST['projectDateToleEnd']));

         $updateProject = $this->projectObj->DoUpdateProject(
            $_POST['pete_id'],
            $_POST['dokpro_id'],
            $_POST['dokcon_id'],
            $_POST['projectNick'],
            $_POST['projectName'],
            $_POST['projectDes'],
            $projectDateConStart,
            $projectDateConEnd,
            $projectDateMtcEnd,
            $_POST['projectModel'],
            $_POST['projectOrder'],
            $_POST['projectAmId'],
            $projectDateBast,
            $projectDateToleStart,
            $projectDateToleEnd,
            $_POST['projectToleUse'],
            $_POST['projectNote'],
            $UpdateTime,
            $UpdateUser,
            $_POST['idd']
            );

         $processData = $updateProject;

         if ($processData == true) {
            $this->SendAlert('Pengubahan data Berhasil Dilakukan', 'project', $this->cssDone);
            return $this->pageView;
         } else {
            $this->SendAlert('Gagal Pengubahan Data', 'inputProject', $this->cssFail);
            return $this->pageInput;
         }
      }
   }

   function Delete() {
      //print_r(var_dump($_POST));exit;
      $deleteProject = $this->projectObj->DoDeleteProject($_POST['idDelete']);

      $processData = $deleteProject;

      if ($processData == true) {
         $this->SendAlert('Data Berhasil Dihapus', 'project', $this->cssDone);
      } else {
         $this->SendAlert('Gagal Menghapus Data', 'project', $this->cssFail);
      }

      return $this->pageView;
   }

}
?>
