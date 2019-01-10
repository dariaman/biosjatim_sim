<?php

$userID 	 	= $_POST['user-id'];
$dateStart 		= $_POST['dateStart'];
$dateEnd 		= $_POST['dateEnd'];
$range  	 	= $_POST['range'];
$deviceVersion 	= $_POST['deviceVersion'];
$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

$cancel =false;
if($userID == "" || $dateStart == "" || $dateEnd == ""|| $range == ""  )
{
	$cancel=true;
}

if($cancel)
{
	$json['err_no']  = '1';
	$json['err_msg'] = 'Error occured. Please try again.';

	echo json_encode($json);
	die();
}

if($deviceVersion <= 1.10)
{
	$json['err_no']  = '2';
	$json['err_msg'] = 'You need to upgrade your device!!!';

	echo json_encode($json);
	die();
}

$queryUserID 	= "SELECT id,cabang FROM user_mobile WHERE md5(id)='$userID'";
$resultUserID 	= query_db($queryUserID);
$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
$userCabang 	= $encodedUserID['cabang'];
$encodedUserID 	= $encodedUserID['id'];

//Insert to spak table
$query_last_spak 	= "SELECT MAX(id) as max FROM fu_ajk_spak";
$result_last_spak 	= query_db($query_last_spak);
if(mysql_num_rows($result_last_spak)>0)
{
	$row 			=  mysql_fetch_assoc($result_last_spak);
	$next_spak_id 	=$row['max']+1;
}else{
	$next_spak_id	=1;
}

$querySelect2 	= "SELECT
						SUM(monthly_masuk) 	 AS monthly_masuk_pasien,
						SUM(monthly_diterima)  AS monthly_diterima_pasien,
						SUM(monthly_proses)  AS monthly_proses_pasien,
						SUM(monthly_batal) 	 AS monthly_batal_pasien,
						SUM(monthly_tolak) 	 AS monthly_tolak_pasien,
						SUM(monthly_realisasi) AS monthly_realisasi_pasien
						FROM
						(	
							SELECT
							fu_ajk_cabang.name,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Aktif' OR fu_ajk_spak.`status`='Batal' OR fu_ajk_spak.`status`='Tolak' OR fu_ajk_spak.`status`='Realisasi' OR fu_ajk_spak.`status`='Proses' OR fu_ajk_spak.`status`='Approve' THEN fu_ajk_spak.id END) as monthly_masuk, 
							COUNT(CASE WHEN fu_ajk_spak.`status`='Aktif' OR fu_ajk_spak.`status`='Batal' OR fu_ajk_spak.`status`='Tolak' OR fu_ajk_spak.`status`='Realisasi' OR fu_ajk_spak.`status`='Proses' OR fu_ajk_spak.`status`='Approve' THEN fu_ajk_spak.id END) as monthly_diterima,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Proses' THEN fu_ajk_spak.id END) as monthly_proses,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Batal' THEN fu_ajk_spak.id END) as monthly_batal,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Tolak' THEN fu_ajk_spak.id END) as monthly_tolak,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Approve' THEN fu_ajk_spak.id END) as monthly_approve,
							COUNT(CASE WHEN fu_ajk_spak.`status`='Realisasi' THEN fu_ajk_spak.id END) as monthly_realisasi
							FROM fu_ajk_spak
							INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost
							AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
							INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
							INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
							WHERE fu_ajk_spak.id != ''
							AND fu_ajk_spak.id_cost = '1'
							AND fu_ajk_spak.input_date
							BETWEEN '$startMonth' AND '$dateEnd'
							AND fu_ajk_spak_form.del IS NULL
							GROUP BY fu_ajk_cabang.name
							ORDER BY monthly_realisasi DESC
						) AS summary_report_kesehatan ";


				$result2 		 = query_db($querySelect2);
				$checkrows2 = mysql_num_rows($result2);
				if ($checkrows2 > 0){
					while ($row2 = mysql_fetch_assoc($result2))
					{
						$data2['data'][] = array(
										'monthly_masuk_pasien'		=> is_null($row2['monthly_masuk_pasien']) ? '0' : $row2['monthly_masuk_pasien'],
										'monthly_diterima_pasien'	=> is_null($row2['monthly_diterima_pasien']) ? '0' : $row2['monthly_diterima_pasien'],
										'monthly_proses_pasien'		=> is_null($row2['monthly_proses_pasien']) ? '0' : $row2['monthly_proses_pasien'],
										'monthly_batal_pasien'		=> is_null($row2['monthly_batal_pasien']) ? '0' : $row2['monthly_batal_pasien'],
										'monthly_tolak_pasien' 		=> is_null($row2['monthly_tolak_pasien']) ? '0' : $row2['monthly_tolak_pasien'],
										'monthly_realisasi_pasien' 	=> is_null($row2['monthly_realisasi_pasien']) ? '0' : $row2['monthly_realisasi_pasien']
										);
					}
				}else{
					$data2 = array();
				}
				

				if ($range == 'monthly')
				{
					echo json_encode($data2);
				}
				else 
				{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}

?>