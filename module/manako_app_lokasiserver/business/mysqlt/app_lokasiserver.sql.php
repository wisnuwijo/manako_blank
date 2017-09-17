<?php
/*---View---*/
$sql['get_data_app_lokasiservers'] =
   "SELECT SQL_CALC_FOUND_ROWS
     lokasiserverId,
     lokasiserverName
   FROM 
      project_app_ref_lokasiserver
   -- byId --
   -- finds --
   ORDER BY
     lokasiserverName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_app_lokasiserver'] =
   "INSERT INTO
      project_app_ref_lokasiserver(
        lokasiserverName)
    VALUES
      ('%s')
   ";

$sql['get_count_duplicate_name_add'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app_ref_lokasiserver
   WHERE
      lokasiserverName = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app_lokasiserver'] =
   "UPDATE
      project_app_ref_lokasiserver
    SET
        lokasiserverName = '%s',
        lokasiserverUpdateTimestamp = '%s',
        lokasiserverUpdateUserId = '%s'
    WHERE
      lokasiserverId = '%s'
   ";

$sql['get_count_duplicate_name'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app_ref_lokasiserver
   WHERE
      lokasiserverName = '%s'
   AND
      lokasiserverId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app_lokasiserver'] =
   "DELETE FROM
      project_app_ref_lokasiserver
   WHERE
     lokasiserverId   = '%s'
   ";

?>
