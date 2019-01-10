<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$groupID 	 = $_POST['groupID'];
$nmproduk 	 = $_POST['nmproduk'];
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

$querySelect1 = "
				SELECT  MonthGroup, COUNT(*) AS JmlPeserta, id_regional
				FROM    (
						SELECT  CASE
						WHEN  bulan >= 0 AND bulan <= 1 THEN '0-1'
								WHEN  bulan >= 2 AND bulan <= 3 THEN '2-3'
								WHEN  bulan >= 4 AND bulan <= 5 THEN '4-5'
								WHEN  bulan >= 6 AND bulan <= 7 THEN '6-7'
								WHEN  bulan >= 8 AND bulan <= 9 THEN '8-9'
								WHEN  bulan >= 10 AND bulan <= 11 THEN '10-11'
								WHEN  bulan >= 12 AND bulan <= 13 THEN '12-13'
								WHEN  bulan >= 14 AND bulan <= 15 THEN '14-15'
								WHEN  bulan >= 16 AND bulan <= 17 THEN '16-17'
								WHEN  bulan >= 18 AND bulan <= 19 THEN '18-19'
								WHEN  bulan >= 20 AND bulan <= 21 THEN '20-21'
								WHEN  bulan >= 22 AND bulan <= 24 THEN '22-24'
								WHEN  bulan > 24 THEN '24-high'
								END AS MonthGroup, id_regional
						FROM    (
							SELECT ROUND(
							DATEDIFF(kl.tgl_klaim, dn.tgl_createdn)/12
							) AS bulan, dn.id_regional FROM fu_ajk_klaim kl, fu_ajk_dn dn, fu_ajk_polis po 
							WHERE dn.id_cost = '$groupID' 
							AND po.nmproduk = '$nmproduk'
							AND dn.id = kl.id_dn
							AND dn.id_nopol = po.id
								) AS SubQueryAlias
						) AS SubQueryAlias2
				GROUP BY
						MonthGroup";


				$result1 		= query_db($querySelect1);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'month'			=> is_null($getData['MonthGroup']) ? 'LAINNYA' : $getData['MonthGroup'],
											'jml_peserta'	=> is_null($getData['JmlPeserta']) ? '0' : $getData['JmlPeserta']
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
		$querySelect = "SELECT  MonthGroup, COUNT(*) AS JmlPeserta, id_cabang
						FROM    (
								SELECT  CASE
								WHEN  bulan >= 0 AND bulan <= 1 THEN '0-1'
										WHEN  bulan >= 2 AND bulan <= 3 THEN '2-3'
										WHEN  bulan >= 4 AND bulan <= 5 THEN '4-5'
										WHEN  bulan >= 6 AND bulan <= 7 THEN '6-7'
										WHEN  bulan >= 8 AND bulan <= 9 THEN '8-9'
										WHEN  bulan >= 10 AND bulan <= 11 THEN '10-11'
										WHEN  bulan >= 12 AND bulan <= 13 THEN '12-13'
										WHEN  bulan >= 14 AND bulan <= 15 THEN '14-15'
										WHEN  bulan >= 16 AND bulan <= 17 THEN '16-17'
										WHEN  bulan >= 18 AND bulan <= 19 THEN '18-19'
										WHEN  bulan >= 20 AND bulan <= 21 THEN '20-21'
										WHEN  bulan >= 22 AND bulan <= 24 THEN '22-24'
										WHEN  bulan > 24 THEN '24-high'
										END AS MonthGroup, id_cabang
								FROM    (
									SELECT ROUND(
									DATEDIFF(kl.tgl_klaim, dn.tgl_createdn)/12
									) AS bulan, dn.id_cabang FROM fu_ajk_klaim kl, fu_ajk_dn dn, fu_ajk_polis po 
									WHERE dn.id = kl.id_dn
									AND dn.id_cost = '$groupID' 
									AND po.nmproduk = '$nmproduk'
									AND dn.id_regional = '$cabang'
									AND dn.id_nopol = po.id
										) AS SubQueryAlias
								) AS SubQueryAlias2
						GROUP BY
								MonthGroup";

				$result 		= query_db($querySelect);
				while ($getData = mysql_fetch_assoc($result))
				{
					//$data['data'][] = $getData;
					$data['data'][] = array(
										'month'			=> is_null($getData['MonthGroup']) ? 'LAINNYA' : $getData['MonthGroup'],
										'jml_peserta'	=> is_null($getData['JmlPeserta']) ? '0' : $getData['JmlPeserta']
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