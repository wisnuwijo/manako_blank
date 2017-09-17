<?php
/*---View---*/
$sql['get_data_apps'] =
   "SELECT SQL_CALC_FOUND_ROWS
     appId,
     appProductId,
     productName,
     appVarianId,
     varianName,
     appName,
     appNick,
     appDirInstall,
     appPathDev,
     appPathDocRepo,
     appPathDocFile
   FROM
      project_app
   LEFT JOIN project_app_product
   ON appProductId = productId
   LEFT JOIN project_app_varian
   ON appVarianId = varianId
   -- group --
   -- byNick --
   -- finds --
   ORDER BY
     appName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_app'] =
   "INSERT INTO
      project_app(
        appProductId,
        appVarianId,
        appName,
        appNick,
        appDirInstall,
        appPathDev,
        appPathDocRepo,
        appPathDocFile)
    VALUES
      ('%s','%s','%s','%s','%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app
   WHERE
      appNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app'] =
   "UPDATE
      project_app
    SET
        appProductId = '%s',
        appVarianId = '%s',
        appName = '%s',
        appNick = '%s',
        appDirInstall = '%s',
        appPathDev = '%s',
        appPathDocRepo = '%s',
        appPathDocFile = '%s',
        appUpdateTimestamp = '%s',
        appUpdateUserId = '%s'
    WHERE
      appNick = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app
   WHERE
      appNick = '%s'
   AND
      appNick != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app'] =
   "DELETE FROM
      project_app
   WHERE
     appNick   = '%s'
   ";

?>
