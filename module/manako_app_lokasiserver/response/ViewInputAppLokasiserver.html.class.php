<?php
/**
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app_lokasiserver/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/AppLokasiserver.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewInputAppLokasiserver extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_app_lokasiserver/template');
      $this->SetTemplateFile('input_app_lokasiserver.html');
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
      $l          = '&';
      if ($urlType == "Simple") {
         $param   = '/';
         $l       = '?';
      }

      if ($identifier == null) {
         $url     = 'AddAppLokasiserver';
         $extend  =  '';
         $title   = 'Tambah';
      } else {
         $url     = 'UpdateAppLokasiserver';
         $extend  =  $param .$identifier;
         $title   = 'Ubah';
      }

      $urlAction        = Dispatcher::Instance()->GetUrl('manako_app_lokasiserver', $url, 'do', 'json') . $extend;
      $urlCancel        = Dispatcher::Instance()->GetUrl('manako_app_lokasiserver', 'appLokasiserver', 'view', 'html');
      $urlValue         = Dispatcher::Instance()->GetUrl('manako_app_lokasiserver', 'dataAppLokasiserver', 'view', 'json') .$param .$identifier;

      $this->mrTemplate->AddVar('content', 'JUDUL', $title);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', $urlAction );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', $urlCancel );
      $this->mrTemplate->AddVar('content', 'URL_VALUE', $urlValue );
      $this->mrTemplate->AddVar('content', 'URL_L', $l );

   }
}
?>
