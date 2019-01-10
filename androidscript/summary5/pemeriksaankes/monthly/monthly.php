<?php
if($usersType == 'Direksi_GM' || $privilege == "2" || $privilege == "3"){
	$querySelect2 	= "
					SELECT
					fu_ajk_cabang.name,
					COUNT(fu_ajk_spak.id) as masuk, 
					COUNT(CASE WHEN fu_ajk_spak.`status`='Aktif' THEN fu_ajk_spak.id END) as diterima,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Proses' OR fu_ajk_spak.`status`='Approve' OR fu_ajk_spak.`status`='Pending' OR fu_ajk_spak.`status`='Preapproval' THEN fu_ajk_spak.id END) as proses,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Batal' THEN fu_ajk_spak.id END) as batal,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Tolak' THEN fu_ajk_spak.id END) as tolak,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Approve' THEN fu_ajk_spak.id END) as monthly_approve,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Realisasi' THEN fu_ajk_spak.id END) as realisasi
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
					ORDER BY masuk DESC
				";
						
$querySelect20 	= "SELECT name,
					SUM(masuk) 	 AS total_monthly_masuk,
					SUM(diterima)  AS total_monthly_diterima,
					SUM(proses)  AS total_monthly_proses,
					SUM(batal) 	 AS total_monthly_batal,
					SUM(tolak) 	 AS total_monthly_tolak,
					SUM(realisasi) AS total_monthly_realisasi
					FROM
					(
						$querySelect2
					) AS summary_report_kesehatan";
						
				$result2 		 = query_db($querySelect2);
				$result20 		 = query_db($querySelect20);
				$checkrows2 = mysql_num_rows($result2);
				$checkrows20 = mysql_num_rows($result20);
				if ($checkrows2 > 0){
					while ($row2 = mysql_fetch_assoc($result2))
					{
						$data2['data'][] = 
						array(
						'cabang'					=> is_null($row2['name']) ? 'Lainnya' : $row2['name'],
						'monthly_masuk_pasien'		=> is_null($row2['masuk']) ? '0' : $row2['masuk'],
						'monthly_diterima_pasien'	=> is_null($row2['diterima']) ? '0' : $row2['diterima'],
						'monthly_proses_pasien'		=> is_null($row2['proses']) ? '0' : $row2['proses'],
						'monthly_batal_pasien'		=> is_null($row2['batal']) ? '0' : $row2['batal'],
						'monthly_tolak_pasien'		=> is_null($row2['tolak']) ? '0' : $row2['tolak'],
						'monthly_realisasi_pasien'	=> is_null($row2['realisasi']) ? '0' : $row2['realisasi']
						);
					}
					
					while ($row20 = mysql_fetch_assoc($result20))
					{
						$data2['total'][] = 
							array(
							'total_monthly_masuk'	=> is_null($row20['total_monthly_masuk']) ? '0' : $row20['total_monthly_masuk'],
							'total_monthly_diterima'=> is_null($row20['total_monthly_diterima']) ? '0' : $row20['total_monthly_diterima'],
							'total_monthly_proses'	=> is_null($row20['total_monthly_proses']) ? '0' : $row20['total_monthly_proses'],
							'total_monthly_batal'	=> is_null($row20['total_monthly_batal']) ? '0' : $row20['total_monthly_batal'],
							'total_monthly_tolak'	=> is_null($row20['total_monthly_tolak']) ? '0' : $row20['total_monthly_tolak'],
							'total_monthly_realisasi'=> is_null($row20['total_monthly_realisasi']) ? '0' : $row20['total_monthly_realisasi']
							);
					}
				}else{
					$data2 = array();
				}
		echo json_encode($data2);
}else{
	$querySelect2 	= "
					SELECT
					fu_ajk_cabang.name,
					COUNT(fu_ajk_spak.id) as masuk, 
					COUNT(CASE WHEN fu_ajk_spak.`status`='Aktif' THEN fu_ajk_spak.id END) as diterima,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Proses' OR fu_ajk_spak.`status`='Approve' OR fu_ajk_spak.`status`='Pending' OR fu_ajk_spak.`status`='Preapproval' THEN fu_ajk_spak.id END) as proses,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Batal' THEN fu_ajk_spak.id END) as batal,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Tolak' THEN fu_ajk_spak.id END) as tolak,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Approve' THEN fu_ajk_spak.id END) as monthly_approve,
					COUNT(CASE WHEN fu_ajk_spak.`status`='Realisasi' THEN fu_ajk_spak.id END) as realisasi
					FROM fu_ajk_spak
					INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost
					AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
					INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
					INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
					INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					WHERE fu_ajk_spak.id != ''
					AND fu_ajk_spak.id_cost = '$groupID'
					AND fu_ajk_regional.name = '$cabang'
					AND fu_ajk_spak.input_date
					BETWEEN '$startMonth' AND '$dateEnd'
					AND fu_ajk_spak_form.del IS NULL
					GROUP BY fu_ajk_cabang.name 
					ORDER BY masuk DESC
				";
						
$querySelect20 	= "SELECT name,
					SUM(masuk) 	 AS total_monthly_masuk,
					SUM(diterima)  AS total_monthly_diterima,
					SUM(proses)  AS total_monthly_proses,
					SUM(batal) 	 AS total_monthly_batal,
					SUM(tolak) 	 AS total_monthly_tolak,
					SUM(realisasi) AS total_monthly_realisasi
					FROM
					(
						$querySelect2
					) AS summary_report_kesehatan";
						
				$result2 		 = query_db($querySelect2);
				$result20 		 = query_db($querySelect20);
				$checkrows2 = mysql_num_rows($result2);
				$checkrows20 = mysql_num_rows($result20);
				if ($checkrows2 > 0){
					while ($row2 = mysql_fetch_assoc($result2))
					{
						$data2['data'][] = 
						array(
						'cabang'					=> is_null($row2['name']) ? 'Lainnya' : $row2['name'],
						'monthly_masuk_pasien'		=> is_null($row2['masuk']) ? '0' : $row2['masuk'],
						'monthly_diterima_pasien'	=> is_null($row2['diterima']) ? '0' : $row2['diterima'],
						'monthly_proses_pasien'		=> is_null($row2['proses']) ? '0' : $row2['proses'],
						'monthly_batal_pasien'		=> is_null($row2['batal']) ? '0' : $row2['batal'],
						'monthly_tolak_pasien'		=> is_null($row2['tolak']) ? '0' : $row2['tolak'],
						'monthly_realisasi_pasien'	=> is_null($row2['realisasi']) ? '0' : $row2['realisasi']
						);
					}
					
					while ($row20 = mysql_fetch_assoc($result20))
					{
						$data2['total'][] = 
							array(
							'total_monthly_masuk'	=> is_null($row20['total_monthly_masuk']) ? '0' : $row20['total_monthly_masuk'],
							'total_monthly_diterima'=> is_null($row20['total_monthly_diterima']) ? '0' : $row20['total_monthly_diterima'],
							'total_monthly_proses'	=> is_null($row20['total_monthly_proses']) ? '0' : $row20['total_monthly_proses'],
							'total_monthly_batal'	=> is_null($row20['total_monthly_batal']) ? '0' : $row20['total_monthly_batal'],
							'total_monthly_tolak'	=> is_null($row20['total_monthly_tolak']) ? '0' : $row20['total_monthly_tolak'],
							'total_monthly_realisasi'=> is_null($row20['total_monthly_realisasi']) ? '0' : $row20['total_monthly_realisasi']
							);
					}
				}else{
					$data2 = array();
				}
		echo json_encode($data2);
}

?>