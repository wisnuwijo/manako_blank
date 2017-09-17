<?php
/*---View---*/
$sql['get_data_project'] =
   "SELECT
      projectId,
      projectNick,
      projectName,
      cl_pete.clientNick AS pete_nick,
      cl_pete.clientName AS pete_name,
      cl_dokcon.clientNick AS dokcon_nick,
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
   LEFT JOIN project_client cl_dokcon
   ON cl_dokcon.clientId = projectClientDokconId
   LEFT JOIN project_ref_bisnis
   ON bisnisId = projectModel
   LEFT JOIN project_personal
   ON personalId = projectAmId
   WHERE
      projectName LIKE '%s' AND
      cl_pete.clientName LIKE '%s' AND
      YEAR(projectDateConStart) LIKE '%s'
   ORDER BY
      projectDateToleEnd DESC,
      projectDateMtcEnd DESC,
      projectDateConEnd DESC
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

/*
$sql['get_data_project'] =
   "SELECT
      projectId,
      projectNick,
      projectName,
      cl_pete.clientNick AS pete_nick,
      cl_pete.clientName AS pete_name,
      cl_dokcon.clientNick AS dokcon_nick,
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
      projectNote,
      gforgeNickname
   FROM
      project_project
   LEFT JOIN project_client cl_pete
   ON cl_pete.clientId = projectClientPtId
   LEFT OUTER JOIN project_client_gforge
   ON projectClientPtId = clientGforgeClientId
   LEFT OUTER JOIN project_gforge
   ON clientGforgeGforgeId = gforgeId
   LEFT JOIN project_client cl_dokcon
   ON cl_dokcon.clientId = projectClientDokconId
   LEFT JOIN project_ref_bisnis
   ON bisnisId = projectModel
   LEFT JOIN project_personal
   ON personalId = projectAmId
   WHERE
      projectName LIKE '%s' AND
      cl_pete.clientName LIKE '%s' AND
      YEAR(projectDateConStart) LIKE '%s'
   ORDER BY
      projectDateToleEnd DESC,
      projectDateMtcEnd DESC,
      projectDateConEnd DESC
   ";
*/
?>
