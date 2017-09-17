<?php 
$sql['get_data_hotel']=
"SELECT
	hotelId,
   	hotelNama,
   	hotelAlamat,
   	provNama,
   	hotelPhone,
   	hotelHarga,
   	hotelFasilitas,
   	hotelKeterangan,
    clientName,
    hotelClientClientId
FROM 
	project_hotel
LEFT JOIN 
	pub_ref_provinsi
ON
	hotelKodeProv = provKode
LEFT JOIN
	project_hotel_client 
ON
	hotelId = hotelClientHotelId
LEFT JOIN
	project_client 
ON 
	hotelClientClientId = clientId
WHERE
	hotelNama LIKE '%s' AND
	provKode LIKE '%s'
ORDER BY
	hotelId
";

$sql['get_detail_hotel']=
"SELECT 
	hotelId,
	hotelNama,
    hotelAlamat,
    hotelPhone,
    hotelHarga,
    hotelFasilitas,
    hotelKeterangan,
    provNama,
    clientId,
    clientName
FROM
	project_hotel
LEFT JOIN 
	pub_ref_provinsi
ON
	hotelKodeProv = provKode
LEFT JOIN
	project_hotel_client
ON
	hotelClientHotelId = hotelId
LEFT JOIN
	project_client
ON
	hotelClientClientId = clientId
WHERE
	hotelId = '%s'
";

$sql['get_kota']=
"SELECT
	provKode,
	provNama
FROM
	pub_ref_provinsi
ORDER BY
	provKode
";

$sql['get_client']=
"SELECT 
    clientId,
    clientName
FROM project_client
";

$sql['get_hotel_by_id']=
"SELECT
	hotelId,
   hotelNama,
   hotelAlamat,
   hotelKodeProv,
   provNama,
   hotelPhone,
   hotelHarga,
   hotelFasilitas,
   hotelKeterangan,
   clientName,
   hotelClientClientId,
   hotelClientHotelId
FROM 
	project_hotel
LEFT JOIN 
	pub_ref_provinsi
ON
	hotelKodeProv = provKode
LEFT JOIN
	project_hotel_client
ON
	hotelId = hotelClientHotelId
LEFT JOIN
	project_client 
ON 
	hotelClientClientId = clientId
WHERE
	hotelId = '%s'
ORDER BY
	hotelId
";

$sql['get_kota']=
"SELECT
	provKode,
	provNama
FROM
	pub_ref_provinsi
ORDER BY
	provKode
";

$sql['do_add_hotel']=
"INSERT INTO
	project_hotel(
		hotelId,
	   hotelNama,
	   hotelKodeProv,
	   hotelAlamat,
	   hotelPhone,
	   hotelHarga,
	   hotelFasilitas,
	   hotelKeterangan
	)
VALUES
	(null, '%s', '%s', '%s', '%s', '%s', '%s', '%s')
";

$sql['do_add_rel_client']=
"INSERT INTO
	project_hotel_client(
		hotelClientHotelId,
		hotelClientClientId
		)
VALUES
	('%s','%s')
";

$sql['do_update_hotel']=
"UPDATE
	project_hotel
SET
   hotelNama = '%s',
   hotelKodeProv = '%s',
   hotelAlamat = '%s',
   hotelPhone = '%s',
   hotelHarga = '%s',
   hotelFasilitas = '%s',
   hotelKeterangan = '%s'
WHERE
	hotelId = '%s'
";

$sql['do_delete_hotel']=
"DELETE FROM
	project_hotel
WHERE
	hotelId = '%s'
";

$sql['do_delete_rel_client']=
"DELETE FROM
	project_hotel_client
WHERE
	hotelClientHotelId = '%s'
";

$sql['get_max_id'] =
   "SELECT
      MAX(hotelId) AS max_id
   FROM
      project_hotel
   ";
?>