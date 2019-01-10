<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$nmgrup	 	 = $_POST['nmgrup'];
$groupID	 = $_POST['groupID'];
$dateStart   = $_POST['dateStart'];
$dateEnd   	 = $_POST['dateEnd'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

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
		$query_jdt 		= "
			SELECT
			COUNT(CASE WHEN fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS jml_peserta,
			SUM(CASE WHEN fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS total_kredit,
			fu_ajk_polis.nmproduk, 
			fu_ajk_peserta.regional,
			fu_ajk_peserta.cabang
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
			AND fu_ajk_peserta.status_aktif = 'Inforce' 
			AND fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd'
			GROUP BY fu_ajk_peserta.cabang, fu_ajk_polis.nmproduk
			ORDER BY jml_peserta DESC
			";
	}else{
		$query_jdt 		= "
			SELECT
			COUNT(CASE WHEN fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd' THEN fu_ajk_costumer.name END) AS jml_peserta,
			SUM(CASE WHEN fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd' THEN fu_ajk_peserta.kredit_jumlah END) AS total_kredit,
			fu_ajk_polis.nmproduk, 
			fu_ajk_peserta.regional,
			fu_ajk_peserta.cabang
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
			AND fu_ajk_grupproduk.nmproduk = '$nmproduk'
			AND fu_ajk_peserta.status_aktif = 'Inforce' 
			AND fu_ajk_dn.tgltransaksi BETWEEN '$dateStart' AND '$dateEnd'
			GROUP BY fu_ajk_peserta.cabang, fu_ajk_polis.nmproduk
			ORDER BY jml_peserta DESC
			";
	}

		$query_jdt1 	= "
				SELECT
				cabang,
				SUM(jml_peserta) AS total_peserta,
				SUM(total_kredit) AS total_jml_kredit
				FROM(
				 $query_jdt
				) AS total_debitur
				GROUP BY cabang ORDER BY total_peserta DESC
				";
				
				$result2 		= query_db($query_jdt);
				$result20 		= query_db($query_jdt1);
				$checkrows2 = mysql_num_rows($result2);
				$checkrows20 = mysql_num_rows($result20);
				
				if ($checkrows2 > 0){
					while ($getData = mysql_fetch_assoc($result2))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] =	array(
												'cabang'	=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
												'nmproduk'	=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
												'jml_peserta'	=> is_null($getData['jml_peserta']) ? '0' : $getData['jml_peserta'],
												'kredit'		=> is_null($getData['total_kredit']) ? '0' : $getData['total_kredit']
											);
					}
					
					while ($getData1 = mysql_fetch_assoc($result20))
					{
						//$data1['data'][] = $getData;
						$data1['total'][] = array(
												'cabang'	=> is_null($getData1['cabang']) ? 'LAINNYA' : $getData1['cabang'],
												'total_peserta'=> is_null($getData1['total_peserta']) ? '0' : $getData1['total_peserta'],
												'total_kredit'	=> is_null($getData1['total_jml_kredit']) ? '0' : $getData1['total_jml_kredit']
											);
					}
				}else{
					$data1 = array();
				}

				if($result2)
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