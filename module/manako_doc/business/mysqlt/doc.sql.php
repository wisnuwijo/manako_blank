<?php
/*---View---*/
$sql['get_data_docs'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     docId,
     docAppId,
     appName,
     docDocJenisId,
     docJenisNick,
     docJenisName,
     docUrl
   FROM 
      project_doc
   JOIN project_app
   ON docAppId = appId
   JOIN project_doc_ref_jenis
   ON docDocJenisId = docJenisId
   -- finds --
   ORDER BY 
     docUrl
   -- limit --
   
   ";

$sql['get_data_doc'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     docId,
     docAppId,
     docDocJenisId,
     docUrl
   FROM 
      project_doc
   WHERE
     docId = '%s';
   
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_doc'] =
   "INSERT INTO
      project_doc(
        docAppId,
        docDocJenisId,
        docUrl)
    VALUES
      ('%s','%s','%s')
   ";

$sql['get_count_duplicate_url_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_doc
   WHERE
      docUrl = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_doc'] =
   "UPDATE
      project_doc
    SET
        docAppId = '%s',
        docDocJenisId = '%s',
        docUrl = '%s',
        docUpdateTimestamp = '%s',
        docUpdateUserId = '%s'
    WHERE
      docId = '%s'
   ";

$sql['get_count_duplicate_url'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_doc
   WHERE
      docUrl = '%s'
   AND
      docId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_doc'] =
   "DELETE FROM
      project_doc
   WHERE
     docId   = '%s'
   ";

?>

