<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$nmgrup	 	 = $_POST['nmgrup'];
$groupID	 = $_POST['groupID'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$cancel =false;
if($userID=="" || $usersType=="" || $privilege=="")
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
				SELECT  HariGroup1, HariGroup2, HariGroup3, HariGroup4, HariGroup5, regional, nmproduk
				FROM    (
						SELECT  nmproduk,
						COUNT(CASE WHEN  hari < 14 THEN hari < 14 END) AS HariGroup1,
								COUNT(CASE WHEN  hari >= 14 AND hari <= 28 THEN hari >= 14 AND hari <= 28 END) AS HariGroup2,
								COUNT(CASE WHEN  hari >= 28 AND hari <= 45 THEN hari >= 28 AND hari <= 45 END) AS HariGroup3,
								COUNT(CASE WHEN  hari >= 45 AND hari <= 60 THEN hari >= 45 AND hari <= 60 END) AS HariGroup4,
								COUNT(CASE WHEN  hari >60 THEN hari >60 END) AS HariGroup5,
								regional
						FROM    (
							SELECT ROUND(
							DATEDIFF(cn.tgl_byr_claim, kl.tgl_document_lengkap)
							) AS hari, pr.regional, p.nmproduk, gp.nmproduk AS nama_mitra
							FROM fu_ajk_peserta pr, fu_ajk_grupproduk gp, fu_ajk_cn cn, fu_ajk_klaim kl, fu_ajk_polis p 
							WHERE pr.id_polis = p.id
							AND kl.id_cn = cn.id
							AND pr.nama_mitra = gp.id
							AND pr.id_peserta = cn.id_peserta
							AND pr.id_peserta = kl.id_peserta
							AND pr.del IS NULL 
							AND pr.id_cost = '$groupID'
							AND cn.confirm_claim = 'Approve(paid)'
								) AS SubQueryAlias GROUP BY regional, nmproduk
						) AS SubQueryAlias2
				GROUP BY regional, nmproduk";
}else{
	$querySelect1 = "
				SELECT  HariGroup1, HariGroup2, HariGroup3, HariGroup4, HariGroup5, regional, nmproduk
				FROM    (
						SELECT  nmproduk,
						COUNT(CASE WHEN  hari < 14 THEN hari < 14 END) AS HariGroup1,
								COUNT(CASE WHEN  hari >= 14 AND hari <= 28 THEN hari >= 14 AND hari <= 28 END) AS HariGroup2,
								COUNT(CASE WHEN  hari >= 28 AND hari <= 45 THEN hari >= 28 AND hari <= 45 END) AS HariGroup3,
								COUNT(CASE WHEN  hari >= 45 AND hari <= 60 THEN hari >= 45 AND hari <= 60 END) AS HariGroup4,
								COUNT(CASE WHEN  hari >60 THEN hari >60 END) AS HariGroup5,
								regional
						FROM    (
							SELECT ROUND(
							DATEDIFF(cn.tgl_byr_claim, kl.tgl_document_lengkap)
							) AS hari, pr.regional, p.nmproduk, gp.nmproduk AS nama_mitra
							FROM fu_ajk_peserta pr, fu_ajk_grupproduk gp, fu_ajk_cn cn, fu_ajk_klaim kl, fu_ajk_polis p 
							WHERE cn.id_nopol = p.id
							AND kl.id_cn = cn.id
							AND pr.nama_mitra = gp.id
							AND pr.id_peserta = cn.id_peserta
							AND pr.del IS NULL 
							AND cn.del IS NULL 
							AND pr.id_cost = '$groupID'
							AND gp.nmproduk = '$nmgrup'
							AND cn.confirm_claim = 'Approve(paid)'
								) AS SubQueryAlias GROUP BY regional, nmproduk
						) AS SubQueryAlias2
				GROUP BY regional, nmproduk";
}


				$result1 		= query_db($querySelect1);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'regional'	=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
											'nmproduk'	=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
											'less-14'	=> is_null($getData['HariGroup1']) ? 'LAINNYA' : $getData['HariGroup1'],
											'14-28'		=> is_null($getData['HariGroup2']) ? 'LAINNYA' : $getData['HariGroup2'],
											'28-45'		=> is_null($getData['HariGroup3']) ? 'LAINNYA' : $getData['HariGroup3'],
											'45-60'		=> is_null($getData['HariGroup4']) ? 'LAINNYA' : $getData['HariGroup4'],
											'60-more'	=> is_null($getData['HariGroup5']) ? 'LAINNYA' : $getData['HariGroup5']
											);
					}
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
						SELECT  HariGroup1, HariGroup2, HariGroup3, HariGroup4, HariGroup5, cabang, nmproduk
						FROM    (
								SELECT  nmproduk,
								COUNT(CASE WHEN  hari < 14 THEN hari < 14 END) AS HariGroup1,
										COUNT(CASE WHEN  hari >= 14 AND hari <= 28 THEN hari >= 14 AND hari <= 28 END) AS HariGroup2,
										COUNT(CASE WHEN  hari >= 28 AND hari <= 45 THEN hari >= 28 AND hari <= 45 END) AS HariGroup3,
										COUNT(CASE WHEN  hari >= 45 AND hari <= 60 THEN hari >= 45 AND hari <= 60 END) AS HariGroup4,
										COUNT(CASE WHEN  hari >60 THEN hari >60 END) AS HariGroup5,
										cabang
								FROM    (
									SELECT ROUND(
									DATEDIFF(cn.tgl_byr_claim, kl.tgl_document_lengkap)
									) AS hari, pr.cabang, p.nmproduk, gp.nmproduk AS nama_mitra
									FROM fu_ajk_peserta pr, fu_ajk_grupproduk gp, fu_ajk_cn cn, fu_ajk_klaim kl, fu_ajk_polis p 
									WHERE pr.id_polis = p.id
									AND kl.id_cn = cn.id
									AND pr.nama_mitra = gp.id
									AND pr.id_peserta = cn.id_peserta
									AND pr.id_peserta = kl.id_peserta
									AND pr.del IS NULL 
									AND pr.id_cost = '$groupID'
									AND pr.regional = '$cabang'
									AND cn.confirm_claim = 'Approve(paid)'
										) AS SubQueryAlias GROUP BY cabang, nmproduk
								) AS SubQueryAlias2
						GROUP BY cabang, nmproduk";
	}else{
		$querySelect1 = "
						SELECT  HariGroup1, HariGroup2, HariGroup3, HariGroup4, HariGroup5, cabang, nmproduk
						FROM    (
								SELECT  nmproduk,
								COUNT(CASE WHEN  hari < 14 THEN hari < 14 END) AS HariGroup1,
										COUNT(CASE WHEN  hari >= 14 AND hari <= 28 THEN hari >= 14 AND hari <= 28 END) AS HariGroup2,
										COUNT(CASE WHEN  hari >= 28 AND hari <= 45 THEN hari >= 28 AND hari <= 45 END) AS HariGroup3,
										COUNT(CASE WHEN  hari >= 45 AND hari <= 60 THEN hari >= 45 AND hari <= 60 END) AS HariGroup4,
										COUNT(CASE WHEN  hari >60 THEN hari >60 END) AS HariGroup5,
										cabang
								FROM    (
									SELECT ROUND(
									DATEDIFF(cn.tgl_byr_claim, kl.tgl_document_lengkap)
									) AS hari, pr.cabang, p.nmproduk, gp.nmproduk AS nama_mitra
									FROM fu_ajk_peserta pr, fu_ajk_grupproduk gp, fu_ajk_cn cn, fu_ajk_klaim kl, fu_ajk_polis p 
									WHERE cn.id_nopol = p.id
									AND kl.id_cn = cn.id
									AND pr.nama_mitra = gp.id
									AND pr.id_peserta = cn.id_peserta
									AND pr.del IS NULL 
									AND cn.del IS NULL 
									AND pr.id_cost = '$groupID'
									AND pr.regional = '$cabang'
									AND gp.nmproduk = '$nmgrup'
									AND cn.confirm_claim = 'Approve(paid)'
										) AS SubQueryAlias GROUP BY cabang, nmproduk
								) AS SubQueryAlias2
						GROUP BY cabang, nmproduk";
	}

				$result1 		= query_db($querySelect1);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'regional'	=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
											'nmproduk'	=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
											'less-14'	=> is_null($getData['HariGroup1']) ? 'LAINNYA' : $getData['HariGroup1'],
											'14-28'		=> is_null($getData['HariGroup2']) ? 'LAINNYA' : $getData['HariGroup2'],
											'28-45'		=> is_null($getData['HariGroup3']) ? 'LAINNYA' : $getData['HariGroup3'],
											'45-60'		=> is_null($getData['HariGroup4']) ? 'LAINNYA' : $getData['HariGroup4'],
											'60-more'	=> is_null($getData['HariGroup5']) ? 'LAINNYA' : $getData['HariGroup5']
											);
					}
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