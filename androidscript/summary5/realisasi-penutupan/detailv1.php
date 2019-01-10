<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$nmgrup	 	 = $_POST['nmgrup'];
$groupID	 = $_POST['groupID'];
$dateStart	 = $_POST['dateStart'];
$dateEnd	 = $_POST['dateEnd'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

$cancel =false;
if($userID=="" || $usersType=="" || $privilege=="" || $dateStart=="" || $dateEnd=="")
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


if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{

if($nmgrup == 'All Report'){
	$querySelect1 = "
	SELECT
	COUNT(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS peserta,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS kredit,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.totalpremi END) AS premi,
	fu_ajk_peserta.regional, fu_ajk_grupproduk.nmproduk AS nama_mitra, fu_ajk_polis.nmproduk
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
	WHERE fu_ajk_peserta.id_dn !='' 
	AND fu_ajk_dn.del IS NULL 
	AND fu_ajk_peserta.del IS NULL
	AND fu_ajk_costumer.id = '$groupID'
	AND fu_ajk_polis.nmproduk LIKE '%PERCEPATAN%' OR fu_ajk_polis.nmproduk LIKE '%SPK REGULER%'
	AND fu_ajk_peserta.status_aktif = 'Inforce'
	AND fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd'
	GROUP BY fu_ajk_peserta.regional, fu_ajk_grupproduk.nmproduk, fu_ajk_polis.nmproduk
	";
	
}else{
	
	$querySelect1 = "
	SELECT
	COUNT(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS peserta,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS kredit,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.totalpremi END) AS premi,
	fu_ajk_peserta.regional, fu_ajk_grupproduk.nmproduk AS nama_mitra, fu_ajk_polis.nmproduk
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
	WHERE fu_ajk_peserta.id_dn !='' 
	AND fu_ajk_dn.del IS NULL 
	AND fu_ajk_peserta.del IS NULL
	AND fu_ajk_costumer.id = '$groupID'
	AND fu_ajk_grupproduk.nmproduk = '$nmgrup'
	AND fu_ajk_polis.nmproduk LIKE '%PERCEPATAN%' OR fu_ajk_polis.nmproduk LIKE '%SPK REGULER%'
	AND fu_ajk_peserta.status_aktif = 'Inforce'
	AND fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd'
	GROUP BY fu_ajk_peserta.regional, fu_ajk_grupproduk.nmproduk, fu_ajk_polis.nmproduk
	";
}

$querySelect2 = "
				SELECT
				nmproduk,
				SUM(peserta) AS total_peserta,
				SUM(premi) AS total_premi,
				SUM(kredit) AS total_kredit
				FROM
				(
				$querySelect1
				)AS total_realisasi
				GROUP BY nmproduk
				";


				$result1 	= query_db($querySelect1);
				$result2 	= query_db($querySelect2);
				$checkrows 	= mysql_num_rows($result1);
				$checkrows2	= mysql_num_rows($result2);
				if ($checkrows > 0){
					while ($getData1 = mysql_fetch_assoc($result2))
					{
						$persen_total_kredit = @($getData1['total_kredit']/$getData1['total_kredit'])*100;
						$persen_total_premi = @($getData1['total_premi']/$getData1['total_premi'])*100;
						while ($getData = mysql_fetch_assoc($result1))
						{
							if($getData1['nmproduk'] == $getData['nmproduk']){
								if($getData['nmproduk'] ==  'SPK REGULER MPP'){
									$nmproduk = "SPK REGULER";
								}else if($getData['nmproduk'] ==  'PERCEPATAN MPP'){
									$nmproduk = "PERCEPATAN";
								}else{
									$nmproduk = $getData['nmproduk'];
								}
								$persen_kredit = @($getData['kredit']/$getData1['total_kredit'])*100;
								$persen_premi = @($getData['premi']/$getData1['total_premi'])*100;
								//$data1['data'][] = $getData;
								$data1['data'][] = array(
													'regional'			=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
													'nm_produk'			=> is_null($nmproduk) ? 'LAINNYA' : $nmproduk,
													'peserta'			=> is_null($getData['peserta']) ? '0' : $getData['peserta'],
													'kredit'			=> is_null($getData['kredit']) ? '0' : $getData['kredit'],
													'persen_kredit'		=> is_null($persen_kredit) ? '0' : round($persen_kredit,2),
													'premi'				=> is_null($getData['premi']) ? '0' : $getData['premi'],
													'persen_premi'		=> is_null($persen_premi) ? '0' : round($persen_premi,2)
												  );
							}
						}
						if($getData1['nmproduk'] ==  'SPK REGULER MPP'){
							$nmproduk = "SPK REGULER";
						}else if($getData1['nmproduk'] ==  'PERCEPATAN MPP'){
							$nmproduk = "PERCEPATAN";
						}else{
							$nmproduk = $getData1['nmproduk'];
						}
						$data1['total'][] = array(
											'nm_produk'		=> is_null($nmproduk) ? 'LAINNYA' : $nmproduk,
											'total_peserta'	=> is_null($getData1['total_peserta']) ? '0' : $getData1['total_peserta'],
											'total_kredit'	=> is_null($getData1['total_kredit']) ? '0' : $getData1['total_kredit'],
											'persen_total_kredit'=> is_null($persen_total_kredit) ? '0' : round($persen_total_kredit),
											'total_premi'	=> is_null($getData1['total_premi']) ? '0' : $getData1['total_premi'],
											'persen_total_premi'=> is_null($persen_total_premi) ? '0' : round($persen_total_premi)
										  );
						mysql_data_seek($result1, 0);
					}
					mysql_free_result($result1);
				}else{
					$data1 = array();
				}

				if($result1)
				{
					// $test['data'][] = array($data;
					echo json_encode($data1);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}
} else {
		
if($nmgrup == 'All Report'){
	$querySelect1 = "
	SELECT
	COUNT(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS peserta,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS kredit,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.totalpremi END) AS premi,
	fu_ajk_peserta.cabang, fu_ajk_grupproduk.nmproduk AS nama_mitra, fu_ajk_polis.nmproduk
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
	WHERE fu_ajk_peserta.id_dn !='' 
	AND fu_ajk_dn.del IS NULL 
	AND fu_ajk_peserta.del IS NULL
	AND fu_ajk_costumer.id = '$groupID'
	AND fu_ajk_peserta.regional = '$cabang'
	AND fu_ajk_polis.nmproduk LIKE '%PERCEPATAN%' OR fu_ajk_polis.nmproduk LIKE '%SPK REGULER%'
	AND fu_ajk_peserta.status_aktif = 'Inforce'
	AND fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd'
	GROUP BY fu_ajk_peserta.cabang, fu_ajk_grupproduk.nmproduk, fu_ajk_polis.nmproduk
	";
	
}else{
	
	$querySelect1 = "
	SELECT
	COUNT(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS peserta,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS kredit,
	SUM(CASE WHEN fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd' THEN fu_ajk_peserta.totalpremi END) AS premi,
	fu_ajk_peserta.cabang, fu_ajk_grupproduk.nmproduk AS nama_mitra, fu_ajk_polis.nmproduk
	FROM fu_ajk_peserta
	INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
	INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
	WHERE fu_ajk_peserta.id_dn !='' 
	AND fu_ajk_dn.del IS NULL 
	AND fu_ajk_peserta.del IS NULL
	AND fu_ajk_costumer.id = '$groupID'
	AND fu_ajk_grupproduk.nmproduk = '$nmgrup'
	AND fu_ajk_peserta.regional = '$cabang'
	AND fu_ajk_polis.nmproduk LIKE '%PERCEPATAN%' OR fu_ajk_polis.nmproduk LIKE '%SPK REGULER%'
	AND fu_ajk_peserta.status_aktif = 'Inforce'
	AND fu_ajk_dn.tgl_createdn BETWEEN '$startMonth' AND '$dateEnd'
	GROUP BY fu_ajk_peserta.cabang, fu_ajk_grupproduk.nmproduk, fu_ajk_polis.nmproduk
	";
}

$querySelect2 = "
				SELECT
				nmproduk,
				SUM(peserta) AS total_peserta,
				SUM(premi) AS total_premi,
				SUM(kredit) AS total_kredit
				FROM
				(
				$querySelect1
				)AS total_realisasi
				GROUP BY nmproduk
				";

				$result1 	= query_db($querySelect1);
				$result2 	= query_db($querySelect2);
				$checkrows 	= mysql_num_rows($result1);
				$checkrows2	= mysql_num_rows($result2);
				if ($checkrows > 0){
					while ($getData1 = mysql_fetch_assoc($result2))
					{
						$persen_total_kredit = @($getData1['total_kredit']/$getData1['total_kredit'])*100;
						$persen_total_premi = @($getData1['total_premi']/$getData1['total_premi'])*100;
						while ($getData = mysql_fetch_assoc($result1))
						{
							if($getData1['nmproduk'] == $getData['nmproduk']){
								
								if($getData['nmproduk'] ==  'SPK REGULER MPP'){
									$nmproduk = "SPK REGULER";
								}else if($getData['nmproduk'] ==  'PERCEPATAN MPP'){
									$nmproduk = "PERCEPATAN";
								}else{
									$nmproduk = $getData['nmproduk'];
								}
								
								$persen_kredit = @($getData['kredit']/$getData1['total_kredit'])*100;
								$persen_premi = @($getData['premi']/$getData1['total_premi'])*100;
								//$data1['data'][] = $getData;
								$data1['data'][] = array(
													'regional'			=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
													'nm_produk'			=> is_null($nmproduk) ? 'LAINNYA' : $nmproduk,
													'peserta'			=> is_null($getData['peserta']) ? '0' : $getData['peserta'],
													'kredit'			=> is_null($getData['kredit']) ? '0' : $getData['kredit'],
													'persen_kredit'		=> is_null($persen_kredit) ? '0' : round($persen_kredit,2),
													'premi'				=> is_null($getData['premi']) ? '0' : $getData['premi'],
													'persen_premi'		=> is_null($persen_premi) ? '0' : round($persen_premi,2)
												  );
							}
						}
						
						if($getData1['nmproduk'] ==  'SPK REGULER MPP'){
							$nmproduk = "SPK REGULER";
						}else if($getData1['nmproduk'] ==  'PERCEPATAN MPP'){
							$nmproduk = "PERCEPATAN";
						}else{
							$nmproduk = $getData1['nmproduk'];
						}
						
						$data1['total'][] = array(
											'nm_produk'		=> is_null($nmproduk) ? 'LAINNYA' : $nmproduk,
											'total_peserta'	=> is_null($getData1['total_peserta']) ? '0' : $getData1['total_peserta'],
											'total_kredit'	=> is_null($getData1['total_kredit']) ? '0' : $getData1['total_kredit'],
											'persen_total_kredit'=> is_null($persen_total_kredit) ? '0' : round($persen_total_kredit),
											'total_premi'	=> is_null($getData1['total_premi']) ? '0' : $getData1['total_premi'],
											'persen_total_premi'=> is_null($persen_total_premi) ? '0' : round($persen_total_premi)
										  );
						mysql_data_seek($result1, 0);
					}
					mysql_free_result($result1);
				}else{
					$data1 = array();
				}


				if($result1)
				{
					// $test['data'][] = array($data;
					echo json_encode($data1);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}
}

?>