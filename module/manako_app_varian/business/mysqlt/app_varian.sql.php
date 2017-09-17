<?php
/*---View---*/
$sql['get_data_app_varians'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     varianId,
     varianName,
     varianNick,
     varianInitial,
     varianLogoIcon,
     varianLogoType,
     varianLogoFull
   FROM 
      project_app_varian
   -- byNick --
   -- finds --
   ORDER BY 
     varianName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

//*---DoAdd---*/
$sql['do_add_app_varian'] =
   "INSERT INTO
      project_app_varian(
        varianName,
        varianNick,
        varianInitial,
        varianLogoIcon,
        varianLogoType,
        varianLogoFull)
    VALUES
      ('%s','%s','%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_varian
   WHERE
      varianNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app_varian'] =
   "UPDATE
      project_app_varian
    SET
        varianName = '%s',
        varianNick = '%s',
        varianInitial = '%s',
        varianLogoIcon = '%s',
        varianLogoType = '%s',
        varianLogoFull = '%s',
        varianUpdateTimestamp = '%s',
        varianUpdateUserId = '%s'
    WHERE
      varianNick = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_varian
   WHERE
      varianNick = '%s'
   AND
      varianNick != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app_varian'] =
   "DELETE FROM
      project_app_varian
   WHERE
     varianNick   = '%s'
   ";

?>

