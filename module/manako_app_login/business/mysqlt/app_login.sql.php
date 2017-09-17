<?php
/*---View---*/
$sql['get_data_app_logins'] =
   "SELECT SQL_CALC_FOUND_ROWS
     loginId,
     loginClientId,
     clientName,
     clientNick,
     lokasiserverName,
     loginAppId,
     appName,
     appNick,
     loginUrl,
     loginLoginroleId,
     loginroleName,
     loginUser,
     loginNote
   FROM
      project_app_login
   LEFT JOIN project_client
      ON clientId = loginClientId
   LEFT JOIN project_app
      ON appId = loginAppId
   LEFT JOIN project_app_ref_lokasiserver
      ON lokasiserverId = loginLokasiserverId
   LEFT JOIN project_app_ref_loginrole
      ON loginroleId = loginLoginroleId
   LEFT JOIN gtfw_group_project_app
      ON appId = groupProjectAppAppId
   -- group --
   -- byId --
   -- finds --
   ORDER BY
     clientName,
     appName,
     lokasiserverName,
     loginroleName
   -- limit --
   ";

$sql['get_data_app_login_pswd'] =
   "SELECT
     loginPswd
   FROM
     project_app_login
   WHERE
     loginId = '%s'
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_app_login'] =
   "INSERT INTO
      project_app_login(
       loginClientId,
       loginLokasiserverId,
       loginAppId,
       loginUrl,
       loginLoginroleId,
       loginUser,
       loginPswd,
       loginNote)
    VALUES
      ('%s','%s','%s','%s','%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_add'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app_login
   WHERE
      loginClientId = '%s'
   AND
      loginLokasiserverId = '%s'
   AND
      loginAppId = '%s'
   AND
      loginLoginroleId = '%s'
   AND
      loginUser = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app_login'] =
   "UPDATE
      project_app_login
    SET
        loginClientId = '%s',
        loginLokasiserverId = '%s',
        loginAppId = '%s',
        loginUrl = '%s',
        loginLoginroleId = '%s',
        loginUser = '%s',
        loginNote = '%s',
        loginUpdateTimestamp = '%s',
        loginUpdateUserId = '%s'
    WHERE
      loginId = '%s'
   ";

$sql['get_count_duplicate'] =
   "SELECT
      COUNT(*) AS COUNT
   FROM
      project_app_login
   WHERE
      loginClientId = '%s'
   AND
      loginLokasiserverId = '%s'
   AND
      loginAppId = '%s'
   AND
      loginLoginroleId = '%s'
   AND
      loginUser = '%s'
   AND
      loginId != '%s'
   ";

$sql['do_change_pswd_app_login'] =
   "UPDATE
      project_app_login
    SET
        loginPswd = '%s',
        loginUpdateTimestamp = '%s',
        loginUpdateUserId = '%s'
    WHERE
      loginId = '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app_login'] =
   "DELETE FROM
      project_app_login
   WHERE
     loginId   = '%s'
   ";

?>
