<?php
/*---View---*/
$sql['get_data_contact_field'] = 
   "SELECT 
     contactFieldId,
     contactFieldCode,
     contactFieldLabel,
     contactFieldType,
     contactFieldIcon
   FROM 
      project_ref_contact_field
   WHERE
     contactFieldLabel like '%s' 
   ORDER BY 
     contactFieldLabel
   ";

$sql['get_data_contact_field_by_id'] = 
   "SELECT 
     contactFieldId,
     contactFieldCode,
     contactFieldLabel,
     contactFieldType,
     contactFieldIcon
   FROM 
      project_ref_contact_field
   WHERE
     contactFieldId = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_contact_field'] =
   "INSERT INTO
      project_ref_contact_field(contactFieldCode,contactFieldLabel,contactFieldType,contactFieldIcon)
   VALUES 
      ('%s','%s','%s','%s')
   ";

$sql['get_count_duplicate_code_add'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_contact_field
   WHERE
     contactFieldCode = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_contact_field'] =
   "UPDATE
      project_ref_contact_field
   SET
     contactFieldCode = '%s',
     contactFieldLabel = '%s',
     contactFieldType = '%s',
     contactFieldIcon = '%s',
     contactFieldUpdateTimestamp = '%s',
     contactFieldUpdateUserId = '%s'
   WHERE
     contactFieldId   = '%s'
   ";

$sql['get_count_duplicate_code'] =
   "SELECT 
      COUNT(*) AS COUNT
   FROM
      project_ref_contact_field
   WHERE
     contactFieldCode = '%s'
   AND
     contactFieldId != '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_contact_field'] =
   "DELETE FROM
      project_ref_contact_field
   WHERE
     contactFieldId   = '%s'
   ";

?>

