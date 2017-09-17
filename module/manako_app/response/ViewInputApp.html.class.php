<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_app/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/App.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewInputApp extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/manako_app/template');
      $this->SetTemplateFile('input_app.html');
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
         $url     = 'AddApp';
         $extend  =  '';
         $title   = 'Tambah';
      } else {
         $url     = 'UpdateApp';
         $extend  =  $param .$identifier;
         $title   = 'Ubah';
      }

      $urlAction        = Dispatcher::Instance()->GetUrl('manako_app', $url, 'do', 'json') . $extend;
      $urlCancel        = Dispatcher::Instance()->GetUrl('manako_app', 'app', 'view', 'html');
      $urlValue         = Dispatcher::Instance()->GetUrl('manako_app', 'dataApp', 'view', 'json') .$param .$identifier;
      $urlListProduct   = Dispatcher::Instance()->GetUrl('manako_app_product', 'dataAppProduct', 'view', 'json') .$param .'list';
      $urlListVarian    = Dispatcher::Instance()->GetUrl('manako_app_varian', 'dataAppVarian', 'view', 'json') .$param .'list';

      $this->mrTemplate->AddVar('content', 'JUDUL', $title);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', $urlAction );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', $urlCancel );
      $this->mrTemplate->AddVar('content', 'URL_VALUE', $urlValue );
      $this->mrTemplate->AddVar('content', 'URL_LISTPRODUCT', $urlListProduct );
      $this->mrTemplate->AddVar('content', 'URL_LISTVARIAN', $urlListVarian );
      $this->mrTemplate->AddVar('content', 'URL_L', $l );

   }
}
?>
