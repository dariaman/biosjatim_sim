<?php
if($usersType == 'Direksi_GM' || $privilege == "2" || $privilege == "3"){
	$querySelect3 	= "
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
					BETWEEN '$startDate' AND '$dateEnd'
					AND fu_ajk_spak_form.del IS NULL
					GROUP BY fu_ajk_cabang.name 
					ORDER BY masuk DESC
				";
							
$querySelect30 	= "SELECT name,
						SUM(masuk) 	  	AS total_yearly_masuk,
						SUM(diterima)  	AS total_yearly_diterima,
						SUM(proses)  	AS total_yearly_proses,
						SUM(batal) 	  	AS total_yearly_batal,
						SUM(tolak) 	  	AS total_yearly_tolak,
						SUM(realisasi)	AS total_yearly_realisasi
						FROM
						(
							$querySelect3
						)
							AS summary_report_kesehatan";
							
				$result3 		 = query_db($querySelect3);
				$result30 		 = query_db($querySelect30);
				$checkrows3 = mysql_num_rows($result3);
				$checkrows30 = mysql_num_rows($result30);
				if ($checkrows3 > 0){
					while ($row3 = mysql_fetch_assoc($result3))
					{
						$data3['data'][] = 
							array(
							'cabang'					=> is_null($row3['name']) ? 'Lainnya' : $row3['name'],
							'yearly_masuk_pasien'		=> is_null($row3['masuk']) ? '0' : $row3['masuk'],
							'yearly_diterima_pasien'	=> is_null($row3['diterima']) ? '0' : $row3['diterima'],
							'yearly_proses_pasien'		=> is_null($row3['proses']) ? '0' : $row3['proses'],
							'yearly_batal_pasien'		=> is_null($row3['batal']) ? '0' : $row3['batal'],
							'yearly_tolak_pasien' 		=> is_null($row3['tolak']) ? '0' : $row3['tolak'],
							'yearly_realisasi_pasien' 	=> is_null($row3['realisasi']) ? '0' : $row3['realisasi']
							);
					}
					
					while ($row30 = mysql_fetch_assoc($result30))
					{
						$data3['total'][] = 
							array(
							'total_yearly_masuk'	=> is_null($row30['total_yearly_masuk']) ? '0' : $row30['total_yearly_masuk'],
							'total_yearly_diterima'	=> is_null($row30['total_yearly_diterima']) ? '0' : $row30['total_yearly_diterima'],
							'total_yearly_proses'	=> is_null($row30['total_yearly_proses']) ? '0' : $row30['total_yearly_proses'],
							'total_yearly_batal'	=> is_null($row30['total_yearly_batal']) ? '0' : $row30['total_yearly_batal'],
							'total_yearly_tolak'	=> is_null($row30['total_yearly_tolak']) ? '0' : $row30['total_yearly_tolak'],
							'total_yearly_realisasi'=> is_null($row30['total_yearly_realisasi']) ? '0' : $row30['total_yearly_realisasi']
							);
					}
				}else{
					$data3 = array();
				}
				
			echo json_encode($data3);
}else{
	$querySelect3 	= "
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
					BETWEEN '$startDate' AND '$dateEnd'
					AND fu_ajk_spak_form.del IS NULL
					GROUP BY fu_ajk_cabang.name 
					ORDER BY masuk DESC
				";
						
$querySelect30 	= "SELECT name,
						SUM(masuk) 	  	AS total_yearly_masuk,
						SUM(diterima)  	AS total_yearly_diterima,
						SUM(proses)  	AS total_yearly_proses,
						SUM(batal) 	  	AS total_yearly_batal,
						SUM(tolak) 	  	AS total_yearly_tolak,
						SUM(realisasi)	AS total_yearly_realisasi
						FROM
						(
							$querySelect3
						)
							AS summary_report_kesehatan";
							
				$result3 		 = query_db($querySelect3);
				$result30 		 = query_db($querySelect30);
				$checkrows3 = mysql_num_rows($result3);
				$checkrows30 = mysql_num_rows($result30);
				if ($checkrows3 > 0){
					while ($row3 = mysql_fetch_assoc($result3))
					{
						$data3['data'][] = 
							array(
							'cabang'					=> is_null($row3['name']) ? 'Lainnya' : $row3['name'],
							'yearly_masuk_pasien'		=> is_null($row3['masuk']) ? '0' : $row3['masuk'],
							'yearly_diterima_pasien'	=> is_null($row3['diterima']) ? '0' : $row3['diterima'],
							'yearly_proses_pasien'		=> is_null($row3['proses']) ? '0' : $row3['proses'],
							'yearly_batal_pasien'		=> is_null($row3['batal']) ? '0' : $row3['batal'],
							'yearly_tolak_pasien' 		=> is_null($row3['tolak']) ? '0' : $row3['tolak'],
							'yearly_realisasi_pasien' 	=> is_null($row3['realisasi']) ? '0' : $row3['realisasi']
							);
					}
					
					while ($row30 = mysql_fetch_assoc($result30))
					{
						$data3['total'][] = 
							array(
							'total_yearly_masuk'	=> is_null($row30['total_yearly_masuk']) ? '0' : $row30['total_yearly_masuk'],
							'total_yearly_diterima'	=> is_null($row30['total_yearly_diterima']) ? '0' : $row30['total_yearly_diterima'],
							'total_yearly_proses'	=> is_null($row30['total_yearly_proses']) ? '0' : $row30['total_yearly_proses'],
							'total_yearly_batal'	=> is_null($row30['total_yearly_batal']) ? '0' : $row30['total_yearly_batal'],
							'total_yearly_tolak'	=> is_null($row30['total_yearly_tolak']) ? '0' : $row30['total_yearly_tolak'],
							'total_yearly_realisasi'=> is_null($row30['total_yearly_realisasi']) ? '0' : $row30['total_yearly_realisasi']
							);
					}
				}else{
					$data3 = array();
				}
				
			echo json_encode($data3);
}
?>