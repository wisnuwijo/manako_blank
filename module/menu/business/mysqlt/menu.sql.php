<?php
$sql['nav'] = "
	SELECT DISTINCT 
		m.MenuId,
      m.IconPath,
		gm.MenuName, 
		gmod.Module,
		gmod.SubModule,
		gmod.Action,
		gmod.Type,
		CONCAT('&mid=',gm.MenuId, '&dmmid=',m.MenuId) AS url,
		a.IconPath AS subIcon,
      a.MenuName AS subMenu,
		a.Module AS subMenuModule,
		a.SubModule AS subMenuSubModule,
		a.Action AS subMenuAction,
		a.Type AS subMenuType
	FROM
      gtfw_group_menu gm
   INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_group_module ggm ON m.MenuDefaultModuleId = ggm.ModuleId
	LEFT JOIN gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
	LEFT JOIN gtfw_user gu ON gudg.UserId = gu.UserId
	LEFT JOIN (
   	SELECT DISTINCT
         gm.ParentMenuId,
   		gm.MenuName, 
   		gmod.Module,
   		gmod.SubModule,
   		gmod.Action,
   		gmod.Type,
         m.IconPath,
   		m.MenuOrder,
         m.IsShow
   	FROM gtfw_group_menu gm
      INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId 
   	LEFT JOIN gtfw_module gmod ON (m.MenuDefaultModuleId = gmod.ModuleId) 
   	LEFT JOIN gtfw_group_module ggm ON (m.MenuDefaultModuleId = ggm.ModuleId) 
   	WHERE m.IsShow='Yes' 
      ORDER by m.MenuOrder ASC
   ) a ON a.ParentMenuId = gm.MenuId
	WHERE (gm.ParentMenuId = 0) 
		AND gu.userName = '%s'
		AND m.IsShow = '%s'
      AND a.IsShow = '%s'
	ORDER BY m.MenuOrder,  m.MenuName, a.MenuOrder ASC
";

$sql['list_available_menu_with_flag_show'] = "
   SELECT DISTINCT 
      gm.MenuId, 
      gm.MenuName, 
      gmod.Module, 
      gmod.SubModule, 
      gmod.Action,
      gmod.Type, 
      gmod.Description, 
      gm.ParentMenuId,
      m.MenuId
   FROM
      gtfw_group_menu gm 
   INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_group_module ggm ON m.MenuDefaultModuleId = ggm.ModuleId
   LEFT JOIN gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
   LEFT JOIN gtfw_user gu ON gudg.UserId = gu.UserId
   WHERE (gm.ParentMenuId = 0) 
      AND gu.userName = '%s'
      AND m.IsShow = '%s'
   ORDER BY m.MenuOrder
"; 

$sql['list_available_menu'] = "
   SELECT DISTINCT 
      gm.MenuId, 
      gm.MenuName,
      m.IconPath, 
      gmod.Module, 
      gmod.SubModule, 
      gmod.Action,
      gmod.Type, 
      gmod.Description, 
      gm.ParentMenuId
   FROM 
      gtfw_group_menu gm
   INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
   LEFT JOIN gtfw_user gu ON gudg.UserId = gu.UserId
   WHERE (gm.ParentMenuId = 0) AND gu.UserName= '%s'
   ORDER BY m.MenuOrder
"; 
// where (gm.ParentMenuId = 0) and gm.GroupId= '%s'";

$sql['list_all_available_submenu_for_group'] = "
   SELECT DISTINCT
      gm.MenuId, 
      gm.MenuName, 
      gmod.Module, 
      gmod.SubModule, 
      gmod.Action, 
      gmod.Type, 
      gmod.Description, 
      gm.ParentMenuId,
      m.IconPath,
      m.MenuId
   FROM 
      gtfw_group_menu gm
   LEFT JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_group_module ggm ON m.MenuDefaultModuleId = ggm.ModuleId
   LEFT JOIN gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId 
   LEFT JOIN gtfw_user gu ON gudg.UserId = gu.UserId
   WHERE gu.UserName= '%s' AND m.MenuParentId = '%s' AND m.IsShow='Yes'
   ORDER BY m.MenuOrder, MenuName ASC
"; 

$sql['list_available_submenu'] = "
   SELECT DISTINCT 
      gm.MenuId, 
      gm.MenuName, 
      gmod.Module, 
      gmod.SubModule, 
      gmod.Action, 
      gmod.Type, 
      gmod.Description
   FROM 
      gtfw_group_menu gm
   INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_group_module ggm ON m.MenuDefaultModuleId = ggm.ModuleId
   WHERE (gm.ParentMenuId = '%s')
   ORDER BY gm.MenuName ASC
";

$sql['list_available_submenu_with_flag_show'] = "
   SELECT DISTINCT 
      gm.MenuId, 
      gm.MenuName, 
      gmod.Module, 
      gmod.SubModule, 
      gmod.Action, 
      gmod.Type, 
      gmod.Description, 
      gm.ParentMenuId
   FROM 
      gtfw_group_menu gm 
   INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
   LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
   LEFT JOIN gtfw_group_module ggm ON m.MenuDefaultModuleId = ggm.ModuleId
   WHERE gm.ParentMenuId = '%s'
   AND m.IsShow='Yes'
   ORDER BY gm.MenuOrder ASC
";

$sql['dhtmlx_menu'] = "
SELECT
parent.`MenuId` AS parentMenuId,
parent.`MenuName` AS parentMenuName,
m.MenuId AS MenuId,
m.MenuName AS MenuName,
gmod.Module,
gmod.SubModule,
gmod.Action,
gmod.Type
FROM
gtfw_group_menu gm
INNER JOIN gtfw_menu m ON gm.MenuMenuId = m.MenuId
LEFT JOIN gtfw_menu parent ON m.`MenuParentId` = parent.`MenuId`
LEFT JOIN gtfw_module gmod ON m.MenuDefaultModuleId = gmod.ModuleId
LEFT JOIN gtfw_user_def_group gudg ON gudg.GroupId = gm.GroupId
LEFT JOIN gtfw_user gu ON gudg.UserId = gu.UserId
WHERE gu.UserName= '%s'
ORDER BY parent.`MenuOrder`, m.`MenuOrder`, parent.`MenuId`
";

?>    
