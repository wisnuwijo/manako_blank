<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLogin.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewChangePswdAppLogin extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_app_login/template');
      $this->SetTemplateFile('change_pswd_app_login.html');
   }
   
   function ProcessRequest() {
      $baseDir    = Configuration::Instance()->GetValue('application', 'basedir');
      $urlSegment = urlHelper::Instance()->segments($_SERVER['REQUEST_URI'], $baseDir);
      
      if (isset($urlSegment[1])) {
         $identifier    = $urlSegment[1];
      } elseif (isset($_REQUEST['identifier'])) {
         $identifier    = $_REQUEST['identifier'];
      } else {
         $identifier    = null;
      }
      
      $return['identifier'] = $identifier;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $identifier     = $data['identifier'];

      $urlType    = Configuration::Instance()->GetValue( 'application', 'url_type');
      $param      = '&identifier=';
      $dataact    = '&dataact=';
      $l          = '&';
      if ($urlType == "Simple") {
         $param = $dataact = '/';
         $l                = '?';
      }

      $urlAction        = Dispatcher::Instance()->GetUrl('manako_app_login', 'changePswdAppLogin', 'do', 'json') . $param . $identifier;
      $urlCancel        = Dispatcher::Instance()->GetUrl('manako_app_login', 'appLogin', 'view', 'html');
      $urlAuth          = Dispatcher::Instance()->GetUrl('manako_app_login', 'dataAppLoginPswd', 'view', 'json') .$param .$identifier .$dataact .'validation';

      $this->mrTemplate->AddVar('content', 'URL_ACTION', $urlAction );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', $urlCancel );
      $this->mrTemplate->AddVar('content', 'URL_AUTH', $urlAuth );
      $this->mrTemplate->AddVar('content', 'URL_L', $l );

   }
}
?>
