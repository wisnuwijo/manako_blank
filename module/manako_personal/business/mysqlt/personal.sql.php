<?php
/*---View---*/
$sql['get_data_personal'] = 
   "SELECT 
      personalId,
      personalName
   FROM 
      project_personal
   WHERE
      personalName like '%s' 
   ORDER BY 
      personalName
   ";

$sql['get_data_personal_by_id'] = 
   "SELECT 
      personalId,
      personalName
   FROM 
      project_personal
   WHERE
      personalId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_personal'] =
   "INSERT INTO
      project_personal(personalName)
   VALUES 
      ('%s')
   ";

$sql['get_count_duplicate_name_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_personal
   WHERE
      personalName = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_personal'] =
   "UPDATE
      project_personal
   SET
      personalName = '%s',
      personalUpdateTimestamp = '%s',
      personalUpdateUserId = '%s'
   WHERE
      personalId   = '%s'
   ";

$sql['get_count_duplicate_name'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_personal
   WHERE
      personalName = '%s'
   AND
      personalId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_personal'] =
   "DELETE FROM
      project_personal
   WHERE
      personalId   = '%s'
   ";

?>

