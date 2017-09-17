<?php
/*---View---*/
$sql['get_data_group2'] =
   "SELECT
      GroupId AS group_id,
      GroupName AS group_name,
      g.Description AS group_description,
      g.UnitappId AS group_unit_app_id,
      gu.UnitName AS group_unit_kerja
   FROM
      gtfw_group g
      JOIN gtfw_unit_application ut ON ut.unitappId=g.UnitappId
    LEFT JOIN gtfw_unit gu ON unitappUnitId = UnitId
   -- finds --
   -- byId --
   /*AND
      GroupId != '2'*/
   ORDER BY
      GroupName
   -- limit --
   ";

$sql['get_data_group_modul_submenu'] =
   "SELECT
      gm.MenuMenuId AS `menu_menuid`,
      gm.MenuName AS `menu_name`,
      gm.subMenuName AS menu_submenu,
      gm.IconPath AS menu_icon,
      gm.MenuParentId AS menu_parent
   FROM
      gtfw_group g
      JOIN gtfw_unit_application ut ON ut.unitappId=g.unitappId
      JOIN gtfw_unit gu ON ut.unitappUnitId = gu.unitId
      LEFT JOIN(
  SELECT
    a.GroupId,
    c.MenuName,
    d.MenuName AS subMenuName,
    b.MenuMenuId,
    d.IconPath,
    d.MenuParentId
  FROM gtfw_group_menu a
  LEFT JOIN gtfw_menu c ON a.MenuMenuId = c.MenuId
  LEFT JOIN gtfw_group_menu b ON a.MenuId = b.ParentMenuId
  LEFT JOIN gtfw_menu d ON b.MenuMenuId = d.MenuId
  WHERE a.ParentMenuId = 0
  ORDER BY a.MenuName, b.MenuName
      ) gm ON g.GroupId=gm.GroupId
   -- byId --
   ORDER BY GroupName, MenuName, subMenuName
   ";

$sql['get_data_group_modul_menu'] =
   "SELECT
      gm.MenuMenuId AS `menu_menuid`,
      '#root#' AS `menu_name`,
      gm.subMenuName AS menu_submenu,
      gm.IconPath AS menu_icon,
      gm.MenuParentId AS menu_parent
   FROM
      gtfw_group g
      JOIN gtfw_unit_application ut ON ut.unitappId=g.unitappId
      JOIN gtfw_unit gu ON ut.unitappUnitId = gu.unitId
      LEFT JOIN(
  SELECT
    a.GroupId,
    b.MenuName AS subMenuName,
    a.MenuMenuId,
    b.IconPath,
    b.MenuParentId
  FROM gtfw_group_menu a
  LEFT JOIN gtfw_menu b ON a.MenuMenuId = b.MenuId
  WHERE a.ParentMenuId = 0
  ORDER BY a.MenuName, b.MenuName
      ) gm ON g.GroupId=gm.GroupId
   -- byId --
   ORDER BY GroupName, subMenuName
   ";


$sql['get_data_registered_modul_submenu'] =
  "SELECT
    b.MenuId AS menu_menuid,
    a.MenuName AS menu_name,
    b.MenuName AS menu_submenu,
    b.IconPath AS menu_icon,
    b.MenuParentId AS menu_parent
  FROM gtfw_menu a
  LEFT JOIN gtfw_menu b ON a.MenuId = b.MenuParentId
  WHERE a.MenuParentId = 0
  ORDER BY a.MenuName, b.MenuName;
  ";

$sql['get_data_registered_modul_menu'] =
  "SELECT
    a.MenuId AS menu_menuid,
    '#root#' AS menu_name,
    a.MenuName AS menu_submenu,
    a.IconPath AS menu_icon,
    a.MenuParentId AS menu_parent
  FROM gtfw_menu a
  WHERE a.MenuParentId = 0
  ORDER BY a.MenuName;
  ";

$sql['get_group_app'] =
  "SELECT
    groupProjectAppGroupId,
    groupProjectAppAppId,
    appName
  FROM
    gtfw_group_project_app
    LEFT JOIN project_app ON groupProjectAppAppId = appId
  WHERE
    groupProjectAppGroupId = '%s'
  ";

$sql['get_total_data'] =
   "SELECT FOUND_ROWS() AS totalData
   ";

/*---DoAdd---*/

/*---DoUpdate---*/

  /* UpdateGroupApp */
  $sql['do_update_group_app_clear'] =
  "DELETE FROM
    gtfw_group_project_app
  WHERE
    groupProjectAppGroupId = '%s'
  ";

  $sql['do_update_group_app_add'] =
  "INSERT INTO
  gtfw_group_project_app(
    groupProjectAppGroupId,
    groupProjectAppAppId
  )
  VALUES (
    '%s',
    '%s'
  )
  ";

/*---DoDelete---*/

?>
