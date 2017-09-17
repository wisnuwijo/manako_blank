<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/gtfw_groupz/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Group2.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewDetailGroup2 extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/gtfw_groupz/template');
      $this->SetTemplateFile('detail_group.html');
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
      $selection  = '&selection=';
      $coderef    = '&coderef=';
      $l          = '&';
      if ($urlType == "Simple") {
         $param = $selection = $coderef = '/';
         $l       = '?';
      }

      $extend  =  $param .$identifier;
      $title   = 'Atur';

      $urlAction        = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'updateGroupApp2', 'do', 'json') . $extend;
      $urlCancel        = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'group', 'view', 'html');
      $urlInfoGen       = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'dataGroup2', 'view', 'json') . $extend;
      $urlInfoMod       = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'dataGroupModul2', 'view', 'json') . $extend . $selection .'active';
      $urlInfoApp       = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'dataGroupApp2', 'view', 'json') . $extend . $coderef . 'name';
      $urlEdit          = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'inputGroup', 'view', 'html') .$l. 'grp=' . $identifier;
      $urlEditApp       = Dispatcher::Instance()->GetUrl('gtfw_groupz', 'inputGroupApp2', 'view', 'html') .$extend;

      $this->mrTemplate->AddVar('content', 'JUDUL', $title);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', $urlAction );
      $this->mrTemplate->AddVar('content', 'URL_CANCEL', $urlCancel );
      $this->mrTemplate->AddVar('content', 'URL_INFO_GEN', $urlInfoGen );
      $this->mrTemplate->AddVar('content', 'URL_INFO_MOD', $urlInfoMod );
      $this->mrTemplate->AddVar('content', 'URL_INFO_APP', $urlInfoApp );
      $this->mrTemplate->AddVar('content', 'URL_EDIT', $urlEdit );
      $this->mrTemplate->AddVar('content', 'URL_EDIT_APP', $urlEditApp );
      $this->mrTemplate->AddVar('content', 'URL_L', $l );

   }
}
?>
