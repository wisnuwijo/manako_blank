<?php
/*---View---*/
$sql['get_data_doc_jenises'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     docJenisId,
     docJenisNick,
     docJenisName
   FROM 
      project_doc_ref_jenis
   -- byNick --
   -- finds --
   ORDER BY 
     docJenisName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_doc_jenis'] =
   "INSERT INTO
      project_doc_ref_jenis(
        docJenisNick,
        docJenisName)
    VALUES
      ('%s','%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_doc_ref_jenis
   WHERE
      docJenisNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_doc_jenis'] =
   "UPDATE
      project_doc_ref_jenis
    SET
        docJenisNick = '%s',
        docJenisName = '%s',
        docJenisUpdateTimestamp = '%s',
        docJenisUpdateUserId = '%s'
    WHERE
      docJenisNick = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_doc_ref_jenis
   WHERE
      docJenisNick = '%s'
   AND
      docJenisNick != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_doc_jenis'] =
   "DELETE FROM
      project_doc_ref_jenis
   WHERE
     docJenisNick   = '%s'
   ";

?>

