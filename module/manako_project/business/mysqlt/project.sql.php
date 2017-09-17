<?php
/*---View---*/
$sql['get_data_project'] =
   "SELECT
      projectId,
      projectName,
      cl_pete.clientName AS pete_name,
      cl_dokpro.clientName AS dokpro_name,
      projectDateConStart,
      projectDateConEnd,
      projectDateMtcEnd,
      projectToleUse,
      projectDateToleStart,
      projectDateToleEnd
   FROM
      project_project
   LEFT JOIN project_client cl_pete
   ON cl_pete.clientId = projectClientPtId
   LEFT JOIN project_client cl_dokpro
   ON cl_dokpro.clientId = projectClientDokproId
   WHERE
      projectName LIKE '%s' AND
      cl_pete.clientName LIKE '%s' AND
      cl_dokpro.clientName LIKE '%s' AND
      YEAR(projectDateConStart) LIKE '%s'
   ORDER BY
      projectDateToleEnd DESC,
      projectDateMtcEnd DESC,
      projectDateConEnd DESC
   ";

$sql['get_data_project_by_id'] =
   "SELECT
      projectId,
      projectNick,
      projectName,
      cl_pete.clientId AS pete_id,
      cl_dokpro.clientId AS dokpro_id,
      cl_dokcon.clientId AS dokcon_id,
      cl_pete.clientName AS pete_name,
      cl_dokpro.clientName AS dokpro_name,
      cl_dokcon.clientName AS dokcon_name,
      projectDes,
      projectDateConStart,
      projectDateConEnd,
      projectDateMtcEnd,
      projectModel,
      bisnisModel,
      projectOrder,
      projectAmId,
      personalName,
      projectDateBast,
      projectDateToleStart,
      projectDateToleEnd,
      projectToleUse,
      projectNote
   FROM
      project_project
   LEFT JOIN project_client cl_pete
   ON cl_pete.clientId = projectClientPtId
   LEFT JOIN project_client cl_dokpro
   ON cl_dokpro.clientId = projectClientDokproId
   LEFT JOIN project_client cl_dokcon
   ON cl_dokcon.clientId = projectClientDokconId
   LEFT JOIN project_ref_bisnis
   ON bisnisId = projectModel
   LEFT JOIN project_personal
   ON personalId = projectAmId
   WHERE
      projectId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_project'] =
   "INSERT INTO
      project_project
   VALUES
      (
         NULL,
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         '%s',
         NULL,
         NULL
      )
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_project
   WHERE
      projectNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_project'] =
   "UPDATE
      project_project
   SET
      projectClientPtId = '%s',
      projectClientDokproId = '%s',
      projectClientDokconId = '%s',
      projectNick = '%s',
      projectName = '%s',
      projectDes = '%s',
      projectDateConStart = '%s',
      projectDateConEnd = '%s',
      projectDateMtcEnd = '%s',
      projectModel = '%s',
      projectOrder = '%s',
      projectAmId = '%s',
      projectDateBast = '%s',
      projectDateToleStart = '%s',
      projectDateToleEnd = '%s',
      projectToleUse = '%s',
      projectNote = '%s',
      projectUpdateTimestamp = '%s',
      projectUpdateUserId = '%s'
   WHERE
      projectId   = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_project
   WHERE
      projectNick = '%s'
   AND
      projectId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_project'] =
   "DELETE FROM
      project_project
   WHERE
      projectId   = '%s'
   ";

$sql['get_max_id'] =
   "SELECT
      MAX(id) AS max_id
   FROM
      project_project
   ";

$sql['get_list_client_active'] =
   "SELECT
      clientId AS id,
      clientName AS name
   FROM
      project_client
   WHERE
      clientStatus = 1
   ORDER BY
      clientName
   ";


$sql['get_list_client_active_only'] =
   "SELECT
      clientId AS id,
      clientName AS name
   FROM
      project_client
   WHERE
      clientStatus = 1 AND
      clientClientCatId = '%s'
   ORDER BY
      clientName
   ";


$sql['get_list_client_active_xcpt'] =
   "SELECT
      clientId AS id,
      clientName AS name
   FROM
      project_client
   WHERE
      clientStatus = 1 AND
      clientClientCatId != '%s'
   ORDER BY
      clientName
   ";

$sql['get_list_bisnis'] =
   "SELECT
      bisnisId AS id,
      bisnisModel AS name
   FROM
      project_ref_bisnis
   ORDER BY
      bisnisModel
   ";

$sql['get_list_personal'] =
   "SELECT
      personalId AS id,
      personalName AS name
   FROM
      project_personal
   ORDER BY
      personalName
   ";
?>
