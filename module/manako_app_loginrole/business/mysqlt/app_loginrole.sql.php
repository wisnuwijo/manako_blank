<?php
/*---View---*/
$sql['get_data_app_loginroles'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     loginroleId,
     loginroleName
   FROM 
      project_app_ref_loginrole
   -- byId --
   -- finds --
   ORDER BY 
     loginroleName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_app_loginrole'] =
   "INSERT INTO
      project_app_ref_loginrole(
        loginroleName)
    VALUES
      ('%s')
   ";

$sql['get_count_duplicate_name_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_ref_loginrole
   WHERE
      loginroleName = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app_loginrole'] =
   "UPDATE
      project_app_ref_loginrole
    SET
        loginroleName = '%s',
        loginroleUpdateTimestamp = '%s',
        loginroleUpdateUserId = '%s'
    WHERE
      loginroleId = '%s'
   ";

$sql['get_count_duplicate_name'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_ref_loginrole
   WHERE
      loginroleName = '%s'
   AND
      loginroleId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app_loginrole'] =
   "DELETE FROM
      project_app_ref_loginrole
   WHERE
     loginroleId   = '%s'
   ";

?>

