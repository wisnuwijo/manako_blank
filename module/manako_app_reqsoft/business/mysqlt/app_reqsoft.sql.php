<?php
/*---View---*/
$sql['get_data_reqsofts'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     reqsoftId,
     reqsoftNick,
     reqsoftDesc
   FROM 
      project_app_ref_reqsoft
   -- byNick --
   -- finds --
   ORDER BY 
     reqsoftNick
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_reqsoft'] =
   "INSERT INTO
      project_app_ref_reqsoft(
        reqsoftNick,
        reqsoftDesc)
    VALUES
      ('%s','%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_ref_reqsoft
   WHERE
      reqsoftNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_reqsoft'] =
   "UPDATE
      project_app_ref_reqsoft
    SET
        reqsoftNick = '%s',
        reqsoftDesc = '%s',
        reqsoftUpdateTimestamp = '%s',
        reqsoftUpdateUserId = '%s'
    WHERE
      reqsoftNick = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_ref_reqsoft
   WHERE
      reqsoftNick = '%s'
   AND
      reqsoftNick != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_reqsoft'] =
   "DELETE FROM
      project_app_ref_reqsoft
   WHERE
     reqsoftNick   = '%s'
   ";

?>

