<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLogin.class.php';

class ViewAppLogin extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_app_login/template');
      $this->SetTemplateFile('view_app_login.html');
   }
   
   function ProcessRequest() {   
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $param      = '&identifier=';
      if ($urlType == "Simple") {
         $param   = '/';
      }

      $urlData       = Dispatcher::Instance()->GetUrl('manako_app_login', 'dataAppLogin', 'view', 'json');
      $urlDelete     = Dispatcher::Instance()->GetUrl('manako_app_login', 'deleteAppLogin', 'do', 'json');
      $urlAdd        = Dispatcher::Instance()->GetUrl('manako_app_login', 'inputAppLogin', 'view', 'html');
      $urlEdit       = $urlAdd . $param;
      $urlPass       = Dispatcher::Instance()->GetUrl('manako_app_login', 'dataAppLoginPswd', 'view', 'json');
      $urlChangePass = Dispatcher::Instance()->GetUrl('manako_app_login', 'changePswdAppLogin', 'view', 'html') . $param;

      $this->mrTemplate->AddVar('content', 'URL_DATA', $urlData );
      $this->mrTemplate->AddVar('content', 'URL_DELETE', $urlDelete );
      $this->mrTemplate->AddVar('content', 'URL_ADD', $urlAdd );
      $this->mrTemplate->AddVar('content', 'URL_EDIT', $urlEdit );
      $this->mrTemplate->AddVar('content', 'URL_PASS', $urlPass );
      $this->mrTemplate->AddVar('content', 'URL_CHANGEPASS', $urlChangePass );
   }
}
?>
