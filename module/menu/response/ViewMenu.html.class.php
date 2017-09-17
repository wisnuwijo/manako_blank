<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/ 

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/menu/business/'.Configuration::Instance()->GetValue( 'application',array('db_conn',0,'db_type')).'/Menu.class.php';

class ViewMenu extends HtmlResponse {
   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/menu/template');
      if(Configuration::Instance()->GetValue( 'application', 'menu_version')=='2')
	      $this->SetTemplateFile('view_menu_2.html');
	   else
	   	$this->SetTemplateFile('view_menu_1.html');
   }

  
   function ProcessRequest() {

      $menuObj = new Menu();
      // $menuObj->LoadSql('module/menu/business/'.Configuration::Instance()->GetValue( 'application',array('db_conn',0,'db_type')).'/menu.sql.php');
      $return['userRealName'] 	= Security::Instance()->mAuthentication->GetCurrentUser()->GetRealName();
      $return['menu'] 			= $menuObj->ListAvailableMenu($_SESSION['username'], 'Yes');
      return $return;
   }

   function ParseTemplate($data = NULL) {
   	#print_r($data['menu']);
		if (!empty($data['menu'])) {
         $len = sizeof($data['menu']);
         if ($len>0){
				$number = 0;
				$menuName = '';
            for ($i=0; $i<$len; $i++) {
					if($menuName != $data['menu'][$i]['MenuName']){
						$this->mrTemplate->addVar('nav_left', 'UL_ID', $data['menu'][$i]['MenuId']);
						$idMenu[] = $data['menu'][$i]['MenuId'];
						$this->mrTemplate->addVar('nav_left', 'LEFT_NAV_VALUE', $data['menu'][$i]['MenuName']);
						$this->mrTemplate->addVar('nav_left', 'LEFT_NAV_ICON', $data['menu'][$i]['IconPath']);
						$url = Dispatcher::Instance()->GetUrl($data['menu'][$i]['Module'],$data['menu'][$i]['SubModule'],$data['menu'][$i]['Action'],$data['menu'][$i]['Type']).$data['menu'][$i]['url'];
						$this->mrTemplate->addVar('nav_left', 'LEFT_NAV', $url);
						$menuName = $data['menu'][$i]['MenuName'];
						
						$this->mrTemplate->clearTemplate('sub_nav');
						$this->mrTemplate->clearTemplate('sub_nav_item');
						
						for ($j=0; $j<$len; $j++) {
							if($menuName == $data['menu'][$j]['MenuName']){
								
									$this->mrTemplate->setAttribute('sub_nav', 'visibility', 'visible');
									
									$this->mrTemplate->addVar('sub_nav', 'UL_ID', $data['menu'][$j]['MenuId']);

									$this->mrTemplate->addVar('sub_nav_item', 'LEFT_SUB_NAV_VALUE', $data['menu'][$j]['subMenu']);
									$this->mrTemplate->addVar('sub_nav_item', 'LEFT_SUB_NAV_ICON', $data['menu'][$j]['subIcon']);
									$url = Dispatcher::Instance()->GetUrl($data['menu'][$j]['subMenuModule'],$data['menu'][$j]['subMenuSubModule'],$data['menu'][$j]['subMenuAction'],$data['menu'][$j]['subMenuType']);
									$this->mrTemplate->addVar('sub_nav_item', 'LEFT_SUB_NAV', $url);
									$this->mrTemplate->parseTemplate('sub_nav_item', 'a');
								
							}
						}
						$this->mrTemplate->parseTemplate('nav_left', 'a');
						
					}

            }
				$idMenu = implode('|',$idMenu);
				$this->mrTemplate->addVar('content', 'ID_MENU', $idMenu);
				
         }
         $this->mrTemplate->addVar('content', 'USER_REALNAME', $data['userRealName']);
      } else {
      		// $this->mrTemplate->addVar('menu_atas', 'ATAS_MAINNAME', $_SESSION['active_user_group_id']);
      }
   }
}
?>
