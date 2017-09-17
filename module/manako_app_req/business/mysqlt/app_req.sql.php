<?php
/*---View---*/
$sql['get_data_reqs'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     reqId,
     reqAppId,
     appName,
     reqReqsoftId,
     reqsoftNick,
     reqOperandi,
     reqValue
   FROM 
      project_app_req
   JOIN project_app
   ON appId = reqAppId
   JOIN project_app_ref_reqsoft
   ON reqsoftId = reqReqsoftId      
   -- byId --
   -- finds --
   ORDER BY 
     appName ASC,
     reqsoftNick ASC
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_req'] =
   "INSERT INTO
      project_app_req(
        reqAppId,
        reqReqsoftId,
        reqOperandi,
        reqValue)
    VALUES
      ('%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_req_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_req
   WHERE
      reqReqsoftId = '%s'
   AND
      reqAppId = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_req'] =
   "UPDATE
      project_app_req
    SET
        reqAppId = '%s',
        reqReqsoftId = '%s',
        reqOperandi = '%s',
        reqValue = '%s',
        reqUpdateTimestamp = '%s',
        reqUpdateUserId = '%s'
    WHERE
      reqId = '%s'
   ";

$sql['get_count_duplicate_req'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_req
   WHERE
      reqReqsoftId = '%s'
   AND
      reqAppId = '%s'
   AND
      reqId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_req'] =
   "DELETE FROM
      project_app_req
   WHERE
      reqId   = '%s'
   ";

?>

