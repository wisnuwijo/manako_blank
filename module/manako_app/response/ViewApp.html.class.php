<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/App.class.php';

class ViewApp extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot').
         'module/manako_app/template');
      $this->SetTemplateFile('view_app.html');
   }
   
   function ProcessRequest() {
   }

   function ParseTemplate($data = NULL) {
      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $param      = '&identifier=';
      if ($urlType == "Simple") {
         $param   = '/';
      }

      $urlData    = Dispatcher::Instance()->GetUrl('manako_app', 'dataApp', 'view', 'json');
      $urlDelete  = Dispatcher::Instance()->GetUrl('manako_app', 'deleteApp', 'do', 'json');
      $urlAdd     = Dispatcher::Instance()->GetUrl('manako_app', 'inputApp', 'view', 'html');
      $urlEdit    = $urlAdd . $param;

      $this->mrTemplate->AddVar('content', 'URL_DATA', $urlData );
      $this->mrTemplate->AddVar('content', 'URL_DELETE', $urlDelete );
      $this->mrTemplate->AddVar('content', 'URL_ADD', $urlAdd );
      $this->mrTemplate->AddVar('content', 'URL_EDIT', $urlEdit );
   }
}
?>
