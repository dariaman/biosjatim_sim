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
		$query_kt 		= "
			SELECT pr.regional, pr.cabang, po.nmproduk,
			SUM(CASE WHEN cn.confirm_claim = 'Approve(unpaid)' OR cn.confirm_claim = 'Approve(paid)' OR cn.confirm_claim = 'Rejected' OR cn.confirm_claim = 'Investigasi' OR cn.confirm_claim = 'Pending' OR cn.confirm_claim = 'Processing' OR cn.confirm_claim = '' THEN cn.total_claim END) AS total_klaim,
			COUNT(CASE WHEN cn.confirm_claim = 'Approve(unpaid)' OR cn.confirm_claim = 'Approve(paid)' OR cn.confirm_claim = 'Rejected' OR cn.confirm_claim = 'Investigasi' OR cn.confirm_claim = 'Pending' OR cn.confirm_claim = 'Processing' OR cn.confirm_claim = '' THEN pr.nama END) AS jml_peserta
			FROM fu_ajk_peserta pr, fu_ajk_cn cn, fu_ajk_polis po, fu_ajk_grupproduk gp, fu_ajk_klaim kl
			WHERE kl.tgl_document BETWEEN '$dateStart' AND '$dateEnd'
			AND pr.nama_mitra = gp.id
			AND kl.id_peserta = pr.id_peserta
			AND pr.id_polis = po.id
			AND cn.id_peserta = pr.id_peserta
			AND pr.del IS NULL 
			AND cn.type_claim = 'Death'
			AND pr.id_cost = '$groupID'
			AND pr.regional = '$cabang'
			GROUP BY
			po.nmproduk, pr.cabang, gp.nmproduk
			ORDER BY jml_peserta DESC
			";
	}else{
		$query_kt 		= "
			SELECT pr.regional, pr.cabang, po.nmproduk,
			SUM(CASE WHEN cn.confirm_claim = 'Approve(unpaid)' OR cn.confirm_claim = 'Approve(paid)' OR cn.confirm_claim = 'Rejected' OR cn.confirm_claim = 'Investigasi' OR cn.confirm_claim = 'Pending' OR cn.confirm_claim = 'Processing' OR cn.confirm_claim = '' THEN cn.total_claim END) AS total_klaim,
			COUNT(CASE WHEN cn.confirm_claim = 'Approve(unpaid)' OR cn.confirm_claim = 'Approve(paid)' OR cn.confirm_claim = 'Rejected' OR cn.confirm_claim = 'Investigasi' OR cn.confirm_claim = 'Pending' OR cn.confirm_claim = 'Processing' OR cn.confirm_claim = '' THEN pr.nama END) AS jml_peserta
			FROM fu_ajk_peserta pr, fu_ajk_cn cn, fu_ajk_polis po, fu_ajk_grupproduk gp, fu_ajk_klaim kl
			WHERE kl.tgl_document BETWEEN '$dateStart' AND '$dateEnd'
			AND pr.nama_mitra = gp.id
			AND kl.id_peserta = pr.id_peserta
			AND pr.id_polis = po.id
			AND cn.id_peserta = pr.id_peserta
			AND pr.del IS NULL 
			AND cn.type_claim = 'Death'
			AND pr.id_cost = '$groupID'
			AND pr.regional = '$cabang'
			AND gp.nmproduk = '$nmgrup'
			GROUP BY
			po.nmproduk, pr.cabang, gp.nmproduk
			ORDER BY jml_peserta DESC
			";
	}

$query_kt1 		= "
				SELECT 
					cabang,
					SUM(jml_peserta) AS total_peserta,
					SUM(total_klaim) AS total_jumlah_klaim
				FROM(
				$query_kt
				) AS total_all_kredit
				GROUP BY cabang ORDER BY total_jumlah_klaim DESC
				";
				
				$result3 		= query_db($query_kt);
				$result30 		= query_db($query_kt1);
				$checkrows3 = mysql_num_rows($result3);
				$checkrows30 = mysql_num_rows($result30);
				
				if ($checkrows3 > 0){
					while ($getData = mysql_fetch_assoc($result3))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
												'cabang'	=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
												'nmproduk'	=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
												'jml_peserta'	=> is_null($getData['jml_peserta']) ? '0' : $getData['jml_peserta'],
												'kredit'		=> is_null($getData['total_klaim']) ? '0' : $getData['total_klaim']
											);
					}
					
					while ($getData1 = mysql_fetch_assoc($result30))
					{
						//$data1['data'][] = $getData;
						$data1['total'][] = array(
												'cabang'	=> is_null($getData1['cabang']) ? 'LAINNYA' : $getData1['cabang'],
												'total_peserta'	=> is_null($getData1['total_peserta']) ? '0' : $getData1['total_peserta'],
												'total_kredit'		=> is_null($getData1['total_jumlah_klaim']) ? '0' : $getData1['total_jumlah_klaim']
											);
					}
				}else{
					$data1 = array();
				}

				if($result3)
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