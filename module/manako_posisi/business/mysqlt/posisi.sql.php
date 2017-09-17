<?php
/*---View---*/
$sql['get_data_posisi'] = 
   "SELECT 
     posisiId,
     posisiCode,
     posisiName
   FROM 
      project_ref_posisi
   WHERE
     posisiName like '%s' 
   ORDER BY 
     posisiName
   ";

$sql['get_data_posisi_by_id'] = 
   "SELECT 
     posisiId,
     posisiCode,
     posisiName
   FROM 
      project_ref_posisi
   WHERE
     posisiId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_posisi'] =
   "INSERT INTO
      project_ref_posisi(posisiCode,posisiName)
   VALUES 
      ('%s','%s')
   ";

$sql['get_count_duplicate_code_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_posisi
   WHERE
     posisiCode = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_posisi'] =
   "UPDATE
      project_ref_posisi
   SET
     posisiCode = '%s',
     posisiName = '%s',
     posisiUpdateTimestamp = '%s',
     posisiUpdateUserId = '%s'
   WHERE
     posisiId   = '%s'
   ";

$sql['get_count_duplicate_code'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_posisi
   WHERE
     posisiCode = '%s'
   AND
     posisiId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_posisi'] =
   "DELETE FROM
      project_ref_posisi
   WHERE
     posisiId   = '%s'
   ";

?>

