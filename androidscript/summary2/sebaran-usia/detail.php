<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$nmproduk	 = $_POST['nmproduk'];
$groupID	 = $_POST['groupID'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$cancel =false;
if($userID=="" || $usersType=="" )
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

$queryUserID 	= "SELECT id,branch,idbroker, idclient, regional, level FROM useraccess WHERE id='$userID'";
$resultUserID 	= query_db($queryUserID);
$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
$userCabang 	= $encodedUserID['branch'];
$userRegional 	= $encodedUserID['regional'];
$idbroker 	= $encodedUserID['idbroker'];
$userlevel 	= $encodedUserID['level'];
$idclient 	= $encodedUserID['idclient'];
$encodedUserID 	= $encodedUserID['id'];

if($idbroker==0){
	$idbroker = $_POST['idbroker'];
}
if($idclient==0){
	$idclient = $_POST['idklien'];
}

$usersType = 'Direksi_GM';
if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{

	$querySelect1 = "SELECT
				COUNT(CASE WHEN  usia < 45 THEN usia < 45 END) AS umur1,
				COUNT(CASE WHEN  usia >= 45 AND usia <= 50 THEN usia >= 45 AND usia <= 50 END) AS umur2,
				COUNT(CASE WHEN  usia >= 51 AND usia <= 55 THEN usia >= 51 AND usia <= 55 END) AS umur3,
				COUNT(CASE WHEN  usia >= 56 AND usia <= 60 THEN usia >= 56 AND usia <= 60 END) AS umur4,
				COUNT(CASE WHEN  usia >= 61 AND usia <= 65 THEN usia >= 61 AND usia <= 65 END) AS umur5,
				COUNT(CASE WHEN  usia >= 66 AND usia <= 70 THEN usia >= 66 AND usia <= 70 END) AS umur6,
				COUNT(CASE WHEN  usia >= 71 AND usia <= 75 THEN usia >= 71 AND usia <= 75 END) AS umur7,
				COUNT(usia) AS total_umur,
				cabang, regional, nmproduk
				FROM    (
					SELECT pr.usia, ajkcabang.name as cabang, ajkregional.name as regional, po.produk as nmproduk FROM ajkpeserta pr, ajkpolis po,ajkcabang,ajkregional
					WHERE po.produk = '$nmproduk'
					AND pr.idpolicy = po.id
					AND pr.idclient = '$groupID'
					AND pr.cabang = ajkcabang.er
					AND pr.regional = ajkregional.er
						) AS SubQueryAlias GROUP BY regional, nmproduk ORDER BY total_umur DESC";

$querySelect2 = "SELECT
				nmproduk,
				SUM(umur1) AS total_umur1,
				SUM(umur2) AS total_umur2,
				SUM(umur3) AS total_umur3,
				SUM(umur4) AS total_umur4,
				SUM(umur5) AS total_umur5,
				SUM(umur6) AS total_umur6,
				SUM(umur7) AS total_umur7,
				SUM(total_umur) AS total_all_umur
				FROM(
				$querySelect1
				)AS total_all_age GROUP BY nmproduk
				";

				$result1 		= query_db($querySelect1);
				$result2 		= query_db($querySelect2);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
												'regional'		=> is_null($getData['regional']) ? 'Unknown' : $getData['regional'],
												'cabang'		=> is_null($getData['regional']) ? 'Unknown' : $getData['regional'],
												'nm_produk'		=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
												'less-45'		=> is_null($getData['umur1']) ? '0' : $getData['umur1'],
												'45-50'			=> is_null($getData['umur2']) ? '0' : $getData['umur2'],
												'51-55'			=> is_null($getData['umur3']) ? '0' : $getData['umur3'],
												'56-60'			=> is_null($getData['umur4']) ? '0' : $getData['umur4'],
												'61-65'			=> is_null($getData['umur5']) ? '0' : $getData['umur5'],
												'66-70'			=> is_null($getData['umur6']) ? '0' : $getData['umur6'],
												'71-75'			=> is_null($getData['umur7']) ? '0' : $getData['umur7'],
												'total_umur'	=> is_null($getData['total_umur']) ? '0' : $getData['total_umur']
											);
					}

					while ($getData1 = mysql_fetch_assoc($result2))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
												'nm_produk'		=> is_null($getData1['nmproduk']) ? 'Unknown' : $getData1['nmproduk'],
												'total less-45'	=> is_null($getData1['total_umur1']) ? '0' : $getData1['total_umur1'],
												'total 45-50'	=> is_null($getData1['total_umur2']) ? '0' : $getData1['total_umur2'],
												'total 51-55'	=> is_null($getData1['total_umur3']) ? '0' : $getData1['total_umur3'],
												'total 56-60'	=> is_null($getData1['total_umur4']) ? '0' : $getData1['total_umur4'],
												'total 61-65'	=> is_null($getData1['total_umur5']) ? '0' : $getData1['total_umur5'],
												'total 66-70'	=> is_null($getData1['total_umur6']) ? '0' : $getData1['total_umur6'],
												'total 71-75'	=> is_null($getData1['total_umur7']) ? '0' : $getData1['total_umur7'],
												'total total_umur'	=> is_null($getData1['total_all_umur']) ? '0' : $getData1['total_all_umur']
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
		$querySelect = "SELECT
						COUNT(CASE WHEN  usia < 45 THEN usia < 45 END) AS umur1,
						COUNT(CASE WHEN  usia >= 45 AND usia <= 50 THEN usia >= 45 AND usia <= 50 END) AS umur2,
						COUNT(CASE WHEN  usia >= 51 AND usia <= 55 THEN usia >= 51 AND usia <= 55 END) AS umur3,
						COUNT(CASE WHEN  usia >= 56 AND usia <= 60 THEN usia >= 56 AND usia <= 60 END) AS umur4,
						COUNT(CASE WHEN  usia >= 61 AND usia <= 65 THEN usia >= 61 AND usia <= 65 END) AS umur5,
						COUNT(CASE WHEN  usia >= 66 AND usia <= 70 THEN usia >= 66 AND usia <= 70 END) AS umur6,
						COUNT(CASE WHEN  usia >= 71 AND usia <= 75 THEN usia >= 71 AND usia <= 75 END) AS umur7,
						COUNT(usia) AS total_umur,
						cabang, regional, nmproduk
						FROM    (
							SELECT pr.usia, pr.cabang, pr.regional,po.nmproduk FROM fu_ajk_peserta pr, fu_ajk_polis po
							WHERE po.nmproduk = '$nmproduk'
							AND pr.id_polis = po.id
							AND pr.id_cost = '$groupID'
							AND pr.regional = '$cabang'
								) AS SubQueryAlias GROUP BY cabang, nmproduk ORDER BY total_umur DESC";

$querySelect2 = "SELECT
				nmproduk,
				SUM(umur1) AS total_umur1,
				SUM(umur2) AS total_umur2,
				SUM(umur3) AS total_umur3,
				SUM(umur4) AS total_umur4,
				SUM(umur5) AS total_umur5,
				SUM(umur6) AS total_umur6,
				SUM(umur7) AS total_umur7,
				SUM(total_umur) AS total_all_umur
				FROM(
				$querySelect
				) AS total_all_age GROUP BY nmproduk
				";

				$result 		= query_db($querySelect);
				$result2 		= query_db($querySelect2);

				while ($getData = mysql_fetch_assoc($result))
				{
					//$data['data'][] = $getData;
					$data['data'][] = array(
											'regional'		=> is_null($getData['regional']) ? 'Unknown' : $getData['regional'],
											'cabang'		=> is_null($getData['cabang']) ? 'Unknown' : $getData['cabang'],
											'nm_produk'		=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
											'less-45'		=> is_null($getData['umur1']) ? '0' : $getData['umur1'],
											'45-50'			=> is_null($getData['umur2']) ? '0' : $getData['umur2'],
											'51-55'			=> is_null($getData['umur3']) ? '0' : $getData['umur3'],
											'56-60'			=> is_null($getData['umur4']) ? '0' : $getData['umur4'],
											'61-65'			=> is_null($getData['umur5']) ? '0' : $getData['umur5'],
											'66-70'			=> is_null($getData['umur6']) ? '0' : $getData['umur6'],
											'71-75'			=> is_null($getData['umur7']) ? '0' : $getData['umur7'],
											'total_umur'	=> is_null($getData['total_umur']) ? '0' : $getData['total_umur']
									  );
				}

				while ($getData1 = mysql_fetch_assoc($result2))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
												'nm_produk'		=> is_null($getData1['nmproduk']) ? 'Unknown' : $getData1['nmproduk'],
												'total less-45'	=> is_null($getData1['total_umur1']) ? '0' : $getData1['total_umur1'],
												'total 45-50'	=> is_null($getData1['total_umur2']) ? '0' : $getData1['total_umur2'],
												'total 51-55'	=> is_null($getData1['total_umur3']) ? '0' : $getData1['total_umur3'],
												'total 56-60'	=> is_null($getData1['total_umur4']) ? '0' : $getData1['total_umur4'],
												'total 61-65'	=> is_null($getData1['total_umur5']) ? '0' : $getData1['total_umur5'],
												'total 66-70'	=> is_null($getData1['total_umur6']) ? '0' : $getData1['total_umur6'],
												'total 71-75'	=> is_null($getData1['total_umur7']) ? '0' : $getData1['total_umur7'],
												'total total_umur'	=> is_null($getData1['total_all_umur']) ? '0' : $getData1['total_all_umur']
											);
					}


				if($result)
				{
					// $test['data'][] = array($data;
					echo json_encode($data);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}
}

?>