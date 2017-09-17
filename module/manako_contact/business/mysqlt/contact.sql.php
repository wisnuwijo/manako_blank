<?php
/*---View---*/
$sql['get_data_contact'] = 
   "SELECT 
      contactId,
      contactNameFirst,
      contactNameLast,
      contactMail,
      contactMobile,
      contactClientNick,
      clientId,
      clientName,
      contactPosisiCode,
      contactPosisiDet,
      posisiName,
      contactDetContactFieldCode,
      contactFieldIcon,
      contactFieldLabel,
      contactDetValue
   FROM 
      project_contact
   LEFT OUTER JOIN (project_contact_det
      JOIN project_ref_contact_field
      ON contactDetContactFieldCode = contactFieldCode)
   ON contactId = contactDetContactId
   LEFT OUTER JOIN project_client
   ON contactClientNick = clientNick
   LEFT OUTER JOIN project_ref_posisi
   ON contactPosisiCode = posisiCode
   WHERE
      (contactNameFirst OR contactNameLast LIKE '%s') AND
      contactClientNick LIKE '%s' AND
      contactPosisiCode LIKE '%s'
   ORDER BY 
      contactNameFirst
   ";

$sql['get_data_contact_by_id'] = 
   "SELECT 
      contactId,
      contactNameFirst,
      contactNameLast,
      contactMail,
      contactMobile,
      contactClientNick,
      clientId,
      clientName,
      contactPosisiCode,
      contactPosisiDet,
      posisiName,
      contactDetContactFieldCode,
      contactFieldIcon,
      contactFieldLabel,
      contactDetValue
   FROM 
      project_contact
   LEFT OUTER JOIN (project_contact_det
      JOIN project_ref_contact_field
      ON contactDetContactFieldCode = contactFieldCode)
   ON contactId = contactDetContactId
   LEFT OUTER JOIN project_client
   ON contactClientNick = clientNick
   LEFT OUTER JOIN project_ref_posisi
   ON contactPosisiCode = posisiCode
   WHERE
      contactId = '%s'
   ";




$sql['get_data_posisi'] =
   "SELECT
      posisiId, 
      posisiCode as id,
      posisiName as name
   FROM 
      project_ref_posisi
   ORDER BY
      posisiName
   ";

$sql['get_data_client_aktif'] =
   "SELECT
      clientId,
      clientNick AS id,
      clientName AS name
   FROM 
      project_client
   WHERE
      clientStatus = 1
   ORDER BY
      clientName
   ";

$sql['get_data_client_aktif_by_nick'] =
   "SELECT
      clientId,
      clientNick,
      clientName
   FROM 
      project_client
   WHERE
      clientStatus = 1 AND
      clientNick = '%s'
   ";

$sql['get_data_contact_field'] =
   "SELECT
      contactFieldId, 
      contactFieldCode as id,
      contactFieldLabel as name,
      contactFieldIcon
   FROM 
      project_ref_contact_field
   ORDER BY
      contactFieldLabel
   ";

$sql['get_data_contact_field_by_code'] =
   "SELECT
      contactFieldId, 
      contactFieldCode,
      contactFieldLabel,
      contactFieldType,
      contactFieldIcon
   FROM 
      project_ref_contact_field
   WHERE
      contactFieldCode = '%s'
   ";

/*---DoAdd---*/
$sql['do_add_contact'] =
   "INSERT INTO
      project_contact(contactNameFirst, contactNameLast, contactMail, contactMobile, contactClientNick, contactPosisiCode, contactPosisiDet)
   VALUES 
      ('%s', '%s', '%s', '%s', '%s', '%s', '%s')
   ";

$sql['do_add_contact_det'] =
   "INSERT INTO
      project_contact_det(contactDetContactId, contactDetContactFieldCode, contactDetValue)
   VALUES
      ('%s', '%s', '%s')
   ";

$sql['do_del_contact_det'] =
   "DELETE FROM
      project_contact_det
   WHERE
      contactDetContactId = '%s'
   ";

/*---DoUpdate---*/
$sql['do_update_contact'] =
   "UPDATE
      project_contact
   SET
      contactNameFirst = '%s',
      contactNameLast = '%s',
      contactMail = '%s',
      contactMobile = '%s',
      contactClientNick = '%s',
      contactPosisiCode = '%s',
      contactPosisiDet = '%s',
      contactUpdateTimestamp = '%s',
      contactUpdateUserId = '%s'      
   WHERE
      contactId   = '%s'
   ";

/*---DoDelete---*/
$sql['do_delete_contact'] =
   "DELETE FROM
      project_contact
   WHERE
      contactId   = '%s'
   ";

$sql['get_max_id'] =
   "SELECT
      MAX(contactId) AS max_id
   FROM
      project_contact
   ";
?>