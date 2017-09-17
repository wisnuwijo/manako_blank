<?php
/*---View---*/

$sql['get_data_clients'] =
   "SELECT
      clientId,
      clientNick,
      clientName
   FROM 
      project_client
   -- aktif --
   -- cat --
   -- finds --
   -- byId --
   ORDER BY
      clientName
   -- limit --
   ";

$sql['get_data_client'] = 
   "SELECT 
      clientId,
      clientClientCatId,
      clientCatName,
      clientNick,
      clientName,
      clientKotaKode,
      clientStatus,
      gforgeNickname,
      kotaNama,
      provNama,
      kotaProvKode
   FROM 
      project_client
   LEFT OUTER JOIN project_client_gforge
   ON clientId = clientGforgeClientId
   LEFT OUTER JOIN project_gforge
   ON clientGforgeGforgeId = gforgeId
   LEFT OUTER JOIN project_ref_client_category
   ON clientClientCatId = clientCatId
   LEFT OUTER JOIN (pub_ref_kota
      JOIN pub_ref_provinsi
      ON kotaProvKode = provKode)
   ON clientKotaKode = kotaKode   
   WHERE
      clientName LIKE '%s' AND
      clientNick LIKE '%s' AND
      kotaKode LIKE '%s' AND
      provKode LIKE '%s'
   ORDER BY 
      clientName,
      gforgeNickname
   ";

$sql['get_data_client_by_id'] = 
   "SELECT 
      clientId,
      clientClientCatId,
      clientCatName,
      clientNick,
      clientName,
      clientKotaKode,
      clientStatus,
      gforgeId,
      gforgeNickname,
      kotaNama,
      provNama,
      kotaProvKode
   FROM 
      project_client
   LEFT OUTER JOIN project_client_gforge
   ON clientId = clientGforgeClientId
   LEFT OUTER JOIN project_gforge
   ON clientGforgeGforgeId = gforgeId
   LEFT OUTER JOIN project_ref_client_category
   ON clientClientCatId = clientCatId
   LEFT OUTER JOIN (pub_ref_kota
      JOIN pub_ref_provinsi
      ON kotaProvKode = provKode)
   ON clientKotaKode = kotaKode   
   WHERE
      clientId = '%s'
   ORDER BY 
      clientName,
      gforgeNickname
   ";

$sql['get_unrel_gforge'] =
   "SELECT
      gforgeId, 
      gforgeNickname
   FROM 
      project_gforge
   LEFT JOIN project_client_gforge
   ON gforgeId = clientGforgeGforgeId
   WHERE
      clientGforgeGforgeId IS NULL
   ORDER BY
      gforgeNickname
   ";

$sql['get_client_kategori'] =
   "SELECT
      clientCatId, 
      clientCatName
   FROM 
      project_ref_client_category
   ORDER BY
      clientCatName
   ";


$sql['get_prov'] =
   "SELECT
      provKode,
      provNama,
      provNick
   FROM 
      pub_ref_provinsi
   ORDER BY
      provNama
   ";

$sql['get_kota'] =
   "SELECT
      kotaKode,
      kotaNama,
      kotaProvKode
   FROM 
      pub_ref_kota
   ORDER BY
      kotaProvKode
   ";

$sql['get_kota_by_prov'] =
   "SELECT
      kotaKode,
      kotaNama,
      kotaProvKode
   FROM 
      pub_ref_kota
   WHERE
      kotaProvKode LIKE '%s'
   ORDER BY
      kotaNama
   ";

/*---DoAdd---*/
$sql['do_add_client'] =
   "INSERT INTO
      project_client(clientClientCatId, clientNick, clientName, clientKotaKode, clientStatus)
   VALUES 
      ('%s', '%s', '%s', '%s', '%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_client
   WHERE
      clientNick = '%s'
   ";

$sql['do_add_rel'] =
   "INSERT INTO
      project_client_gforge(clientGforgeClientId, clientGforgeGforgeId)
   VALUES
      ('%s', '%s')
   ";

$sql['do_del_rel'] =
   "DELETE FROM
      project_client_gforge
   WHERE
      clientGforgeClientId = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_client'] =
   "UPDATE
      project_client
   SET
      clientClientCatId = '%s',
      clientNick = '%s',
      clientName = '%s',
      clientKotaKode = '%s',
      clientStatus = '%s',
      clientUpdateTimestamp = '%s',
      clientUpdateuserId = '%s'
   WHERE
      clientId   = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_client
   WHERE
      clientNick = '%s'
   AND
      clientId != '%s'
   ";

/*---DoStatus---*/
$sql['do_status_change'] =
   "UPDATE
      project_client
   SET
      clientStatus = '%s'
   WHERE
      clientId   = '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_client'] =
   "DELETE FROM
      project_client
   WHERE
      clientId   = '%s'
   ";

$sql['get_max_id'] =
   "SELECT
      MAX(clientId) AS max_id
   FROM
      project_client
   ";
?>

