<?php
/*---View---*/
$sql['get_data_bisnis'] = 
   "SELECT 
     bisnisId,
     bisnisModel
   FROM 
      project_ref_bisnis
   WHERE
     bisnisModel like '%s' 
   ORDER BY 
     bisnisModel
   ";

$sql['get_data_bisnis_by_id'] = 
   "SELECT 
     bisnisId,
     bisnisModel
   FROM 
      project_ref_bisnis
   WHERE
     bisnisId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_bisnis'] =
   "INSERT INTO
      project_ref_bisnis(bisnisModel)
   VALUES 
      ('%s')
   ";

$sql['get_count_duplicate_model_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_bisnis
   WHERE
     bisnisModel = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_bisnis'] =
   "UPDATE
      project_ref_bisnis
   SET
     bisnisModel = '%s',
     bisnisUpdateTimestamp = '%s',
     bisnisUpdateUserId = '%s'
   WHERE
     bisnisId   = '%s'
   ";

$sql['get_count_duplicate_model'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_bisnis
   WHERE
     bisnisModel = '%s'
   AND
     bisnisId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_bisnis'] =
   "DELETE FROM
      project_ref_bisnis
   WHERE
     bisnisId   = '%s'
   ";

?>

