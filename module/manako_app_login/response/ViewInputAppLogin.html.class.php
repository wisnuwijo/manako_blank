<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_login/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLogin.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewInputAppLogin extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_app_login/template');
      $this->SetTemplateFile('input_app_login.html');
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
      $coderef    = '&coderef=';
      $l          = '&';
      if ($urlType == "Simple") {
         $param = $coderef            = '/';
         $l                           = '?';
      }

      if ($identifier == null) {
         $url     = 'AddAppLogin';
         $extend  =  '';
         $title   = 'Tambah';
      } else {
         $url     = 'UpdateAppLogin';
         $extend  =  $param .$identifier;
         $title   = 'Ubah';
      }

      $urlAction        = Dispatcher::Instance()->GetUrl('manako_app_login', $url, 'do', 'json') . $extend;
      $urlCancel        = Dispatcher::Instance()->GetUrl('manako_app_login', 'appLogin', 'view', 'html');
      $urlValue         = Dispatcher::Instance()->GetUrl('manako_app_login', 'dataAppLogin', 'view', 'json') .$param .$identifier;
      $urlListClient    = Dispatcher::Instance()->GetUrl('manako_client2', 'dataClient2', 'view', 'json') .$param .'list' .$coderef .'id' .$l .'cat=2';
      $urlListServer    = Dispatcher::Instance()->GetUrl('manako_app_lokasiserver', 'dataAppLokasiserver', 'view', 'json') .$param .'list';
      $urlListApp       = Dispatcher::Instance()->GetUrl('manako_app', 'dataApp', 'view', 'json') .$param .'list';
      $urlListLoginrole = Dispatcher::Instance()->GetUrl('manako_app_loginrole', 'dataAppLoginrole', 'view', 'json') .$param .'list';

      $this->mrTemplate->AddVar('content', 'JUDUL', $title);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', $urlAction );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', $urlCancel );
      $this->mrTemplate->AddVar('content', 'URL_VALUE', $urlValue );
      $this->mrTemplate->AddVar('content', 'URL_LISTCLIENT', $urlListClient );
      $this->mrTemplate->AddVar('content', 'URL_LISTSERVER', $urlListServer );
      $this->mrTemplate->AddVar('content', 'URL_LISTAPP', $urlListApp );
      $this->mrTemplate->AddVar('content', 'URL_LISTLOGINROLE', $urlListLoginrole );
      $this->mrTemplate->AddVar('content', 'URL_L', $l );

   }
}
?>
