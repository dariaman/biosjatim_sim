<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$nmproduk 	 = $_POST['nmproduk'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$cancel =false;
if($userID=="" || $usersType=="")
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

$querySelect1 = "


SELECT  AgeGroup, COUNT(*) AS JmlPeserta, regional, nmproduk
				FROM    (
						SELECT  CASE
						WHEN  usia < 45 THEN 'under_45'
						WHEN  usia >= 45 AND usia <= 50 THEN '45-50'
						WHEN  usia >= 51 AND usia <= 55 THEN '51-55'
						WHEN  usia >= 56 AND usia <= 60 THEN '56-60'
						WHEN  usia >= 61 AND usia <= 65 THEN '61-65'
						WHEN  usia >= 66 AND usia <= 70 THEN '66-70'
						WHEN  usia >= 71 AND usia <= 75 THEN '71-75'
						END AS AgeGroup, regional, nmproduk
						FROM    (
							SELECT pr.usia, pr.regional,   po.produk as nmproduk FROM ajkpeserta pr, ajkpolis po WHERE pr.idpolicy = po.id
							AND po.produk = '$nmproduk'

								) AS SubQueryAlias
						) AS SubQueryAlias2
				GROUP BY
						AgeGroup";


				$result1 		= query_db($querySelect1);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'jml_peserta'	=> is_null($getData['JmlPeserta']) ? '0' : $getData['JmlPeserta'],
											'range_usia'	=> is_null($getData['AgeGroup']) ? 'LAINNYA' : $getData['AgeGroup']
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
		$querySelect = "SELECT  AgeGroup, COUNT(*) AS JmlPeserta, cabang, nmproduk
						FROM    (
								SELECT  CASE
								WHEN  usia < 45 THEN 'under_45'
								WHEN  usia >= 45 AND usia <= 50 THEN '45-50'
								WHEN  usia >= 51 AND usia <= 55 THEN '51-55'
								WHEN  usia >= 56 AND usia <= 60 THEN '56-60'
								WHEN  usia >= 61 AND usia <= 65 THEN '61-65'
								WHEN  usia >= 66 AND usia <= 70 THEN '66-70'
								WHEN  usia >= 71 AND usia <= 75 THEN '71-75'
								END AS AgeGroup, cabang, nmproduk
								FROM    (
									SELECT pr.usia, pr.cabang, po.nmproduk FROM fu_ajk_peserta pr, fu_ajk_polis po WHERE pr.id_polis = po.id
									AND pr.regional = '$cabang'
									AND po.nmproduk = '$nmproduk'
										) AS SubQueryAlias
								) AS SubQueryAlias2
						GROUP BY
								AgeGroup";

				$result 		= query_db($querySelect);
				while ($getData = mysql_fetch_assoc($result))
				{
					//$data['data'][] = $getData;
					$data['data'][] = array(
										'jml_peserta'	=> is_null($getData['JmlPeserta']) ? '0' : $getData['JmlPeserta'],
										'range_usia'	=> is_null($getData['AgeGroup']) ? 'LAINNYA' : $getData['AgeGroup']
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