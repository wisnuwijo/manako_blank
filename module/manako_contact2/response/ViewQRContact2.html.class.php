<?php
/** 
* @author Abdul R. Wahid
* @copyright Copyright (c) 2015, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'module/manako_contact2/business/'.Configuration::Instance()->GetValue( 'application','db_conn',0,'db_type').'/Contact.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/smartRead/smartReadFile.class.php';
require_once Configuration::Instance()->GetValue( 'application', 'docroot') . 'main/lib/urlHelper/urlHelper.class.php';

class ViewQRContact2 extends HtmlResponse{

   function TemplateModule() {
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

      $location         = Configuration::Instance()->GetValue( 'application', 'upload_path').'/qrcode/';
      $qrFilename       = 'manako-qrcode.png';
      $mimeType         = 'image/png';

      if ($identifier != "") {
         $contactObj               = new Contact();
         $dataContact              = $contactObj->GetDataContacts('',$identifier);
         
         if (!empty($dataContact)) {
            $filename            = $dataContact[0]['contactNameLast'].$dataContact[0]['contactNameFirst'].$identifier.'.png';
            if (file_exists($location.$filename)) {
               $qrFilename       = $filename;
            }
         }         
      }
      
      $qrImage               = SmartReadFile::Instance()->readFile($location.$qrFilename, $qrFilename/*, $mimeType*/);
      echo $qrImage;
      exit;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
