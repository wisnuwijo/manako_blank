<?php
/*---View---*/
$sql['get_data_gforge'] = 
   "SELECT 
      gforgeId,
      gforgeNickname
   FROM 
      project_gforge
   WHERE
      gforgeNickname like '%s' 
   ORDER BY 
      gforgeNickname
   ";

$sql['get_data_gforge_by_id'] = 
   "SELECT 
      gforgeId,
      gforgeNickname
   FROM 
      project_gforge
   WHERE
      gforgeId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_gforge'] =
   "INSERT INTO
      project_gforge(gforgeNickname)
   VALUES 
      ('%s')
   ";

$sql['get_count_duplicate_nickname_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_gforge
   WHERE
      gforgeNickname = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_gforge'] =
   "UPDATE
      project_gforge
   SET
      gforgeNickname = '%s',
      gforgeUpdateTimestamp = '%s',
      gforgeUpdateUserId = '%s'
   WHERE
      gforgeId   = '%s'
   ";

$sql['get_count_duplicate_nickname'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_gforge
   WHERE
      gforgeNickname = '%s'
   AND
      gforgeId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_gforge'] =
   "DELETE FROM
      project_gforge
   WHERE
      gforgeId   = '%s'
   ";

$sql['do_del_rel'] =
   "DELETE FROM
      project_client_gforge
   WHERE
      clientGforgeGforgeId = '%s'
   ";

?>

