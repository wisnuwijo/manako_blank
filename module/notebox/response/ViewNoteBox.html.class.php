<?php
/** 
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

class ViewNoteBox extends HtmlResponse {
   
   var $mComponentParameters;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(Configuration::Instance()->GetValue( 'application', 'docroot') .
         'module/notebox/template');
      $this->SetTemplateFile('view_notebox.html');
   }
   
   function ProcessRequest() {
      $mainMsg = '';
      $detailMsg = '';
      $type = '';
      
      // implements component messenger
      $msg = Messenger::Instance()->Receive(__FILE__, $this->mComponentName);
      if(!empty($msg)) {
         $codeMainMsg = $msg[0][0];
         Configuration::Instance()->Load('error.conf.php', 'default');
         $mainMsgConf = Configuration::Instance()->GetValue('error', $codeMainMsg);
         $mainMsg = $mainMsgConf['lang'];
         $detailMsg = $msg[0][1];
         $type = $mainMsgConf['type'];
      } else {
         // for compatibility backward, delete me!
         if(isset($this->mComponentParameters['notebox_mainmsg'])) {         
            $codeMainMsg = $this->mComponentParameters['notebox_mainmsg'];
            
            Configuration::Instance()->Load('error.conf.php', 'default');
            $mainMsgConf = Configuration::Instance()->GetValue('error', $codeMainMsg);
            $mainMsg = $mainMsgConf['lang'];
            $detailMsg = $this->mComponentParameters['notebox_detailmsg'];
            $type = $mainMsgConf['type'];
         }
      }
      
      return array('mainMsg' => $mainMsg, 'detailMsg' => $detailMsg, 'type' => $type);
   }

   function ParseTemplate($data = NULL) {
      if(isset($data['type']) && trim($data['type']) != '') {
         $this->mrTemplate->SetAttribute('notebox', 'visibility', 'visible');         
         $this->mrTemplate->AddVar('notebox_type', 'TYPE', strtolower($data['type']));
         $this->mrTemplate->AddVar('notebox_type', 'ERROR_MAIN_MESSAGE', $data['mainMsg']);
         $this->mrTemplate->AddVar('notebox_type', 'ERROR_DETAIL_MESSAGE', $data['detailMsg']);
      }
   }
}
?>
