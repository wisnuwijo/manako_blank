<?php
/*---View---*/
$sql['get_data_app_products'] = 
   "SELECT SQL_CALC_FOUND_ROWS
     productId,
     productName,
     productNick,
     productInitial,
     productLogoIcon,
     productLogoType,
     productLogoFull
   FROM 
      project_app_product
   -- byId --
   -- finds --
   ORDER BY 
     productName
   -- limit --
   ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/
$sql['do_add_app_product'] =
   "INSERT INTO
      project_app_product(
        productName,
        productNick,
        productInitial,
        productLogoIcon,
        productLogoType,
        productLogoFull)
    VALUES
      ('%s','%s','%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_nick_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_product
   WHERE
      productNick = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_app_product'] =
   "UPDATE
      project_app_product
    SET
        productName = '%s',
        productNick = '%s',
        productInitial = '%s',
        productLogoIcon = '%s',
        productLogoType = '%s',
        productLogoFull = '%s',
        productUpdateTimestamp = '%s',
        productUpdateUserId = '%s'
    WHERE
      productNick = '%s'
   ";

$sql['get_count_duplicate_nick'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_app_product
   WHERE
      productNick = '%s'
   AND
      productNick != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_app_product'] =
   "DELETE FROM
      project_app_product
   WHERE
     productNick   = '%s'
   ";

?>

