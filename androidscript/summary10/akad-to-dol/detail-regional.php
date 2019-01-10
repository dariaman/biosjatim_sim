<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$nmproduk	 = $_POST['nmproduk'];
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

$querySelect = "SELECT  
		COUNT(CASE WHEN  bulan >= 0 AND bulan <= 1 THEN bulan >= 0 AND bulan <= 1 END) AS BulanGroup1,
		COUNT(CASE WHEN  bulan >= 2 AND bulan <= 3 THEN bulan >= 2 AND bulan <= 3 END) AS BulanGroup2,
		COUNT(CASE WHEN  bulan >= 4 AND bulan <= 5 THEN bulan >= 4 AND bulan <= 5 END) AS BulanGroup3,
		COUNT(CASE WHEN  bulan >= 6 AND bulan <= 7 THEN bulan >= 6 AND bulan <= 7 END) AS BulanGroup4,
		COUNT(CASE WHEN  bulan >= 8 AND bulan <= 9 THEN bulan >= 8 AND bulan <= 9 END) AS BulanGroup5,
		COUNT(CASE WHEN  bulan >= 10 AND bulan <= 11 THEN bulan >= 10 AND bulan <= 11 END) AS BulanGroup6,
		COUNT(CASE WHEN  bulan >= 12 AND bulan <= 13 THEN bulan >= 12 AND bulan <= 13 END) AS BulanGroup7,
		COUNT(CASE WHEN  bulan >= 14 AND bulan <= 15 THEN bulan >= 14 AND bulan <= 15 END) AS BulanGroup8,
		COUNT(CASE WHEN  bulan >= 16 AND bulan <= 17 THEN bulan >= 16 AND bulan <= 17 END) AS BulanGroup9,
		COUNT(CASE WHEN  bulan >= 18 AND bulan <= 19 THEN bulan >= 18 AND bulan <= 19 END) AS BulanGroup10,
		COUNT(CASE WHEN  bulan >= 20 AND bulan <= 21 THEN bulan >= 20 AND bulan <= 21 END) AS BulanGroup11,
		COUNT(CASE WHEN  bulan >= 22 AND bulan <= 24 THEN bulan >= 22 AND bulan <= 24 END) AS BulanGroup12,
		COUNT(CASE WHEN  bulan > 24 THEN bulan > 24 END) AS BulanGroup13,
		COUNT(CASE WHEN  bulan < 0 THEN bulan < 0 END) AS BulanGroup14,
		COUNT(bulan) AS total_akad,
                cabang, regional, nmproduk
        FROM    (
			SELECT ROUND(
			DATEDIFF(kl.tgl_klaim, dn.tgl_createdn)/12
			) AS bulan, pr.cabang, pr.regional, po.nmproduk FROM fu_ajk_klaim kl, fu_ajk_dn dn, fu_ajk_polis po, fu_ajk_peserta pr
			WHERE po.nmproduk = '$nmproduk'
			AND dn.id_cost = '$groupID'
			AND pr.regional = '$cabang'
			AND pr.id_peserta = kl.id_peserta
			AND pr.id_dn = dn.id
			AND dn.id = kl.id_dn
			AND pr.id_polis = po.id
                ) AS akad_dol GROUP BY cabang, regional ORDER BY total_akad DESC LIMIT 0, 10";
		
		$querySelect1 = "
		SELECT 
		nmproduk,
		SUM(BulanGroup1) AS total_bulan1,
		SUM(BulanGroup2) AS total_bulan2,
		SUM(BulanGroup3) AS total_bulan3,
		SUM(BulanGroup4) AS total_bulan4,
		SUM(BulanGroup5) AS total_bulan5,
		SUM(BulanGroup6) AS total_bulan6,
		SUM(BulanGroup7) AS total_bulan7,
		SUM(BulanGroup8) AS total_bulan8,
		SUM(BulanGroup9) AS total_bulan9,
		SUM(BulanGroup10) AS total_bulan10,
		SUM(BulanGroup11) AS total_bulan11,
		SUM(BulanGroup12) AS total_bulan12,
		SUM(BulanGroup13) AS total_bulan13,
		SUM(BulanGroup14) AS total_bulan14,
		SUM(total_akad) AS total_all_bulan
		FROM(
		$querySelect
		) AS total_all_akad GROUP BY nmproduk ORDER BY total_akad
		";

				$result 		= query_db($querySelect);
				$result1 		= query_db($querySelect1);
				while ($getData = mysql_fetch_assoc($result))
				{
					//$data['data'][] = $getData;
					$data['data'][] = array(
											'regional'		=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
											'cabang'		=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
											'nm_produk'		=> is_null($getData['nmproduk']) ? 'LAINNYA' : $getData['nmproduk'],
											'0-1'			=> is_null($getData['BulanGroup1']) ? '0' : $getData['BulanGroup1'],
											'2-3'			=> is_null($getData['BulanGroup2']) ? '0' : $getData['BulanGroup2'],
											'4-5'			=> is_null($getData['BulanGroup3']) ? '0' : $getData['BulanGroup3'],
											'6-7'			=> is_null($getData['BulanGroup4']) ? '0' : $getData['BulanGroup4'],
											'8-9'			=> is_null($getData['BulanGroup5']) ? '0' : $getData['BulanGroup5'],
											'10-11'			=> is_null($getData['BulanGroup6']) ? '0' : $getData['BulanGroup6'],
											'12-13'			=> is_null($getData['BulanGroup7']) ? '0' : $getData['BulanGroup7'],
											'14-16'			=> is_null($getData['BulanGroup8']) ? '0' : $getData['BulanGroup8'],
											'16-17'			=> is_null($getData['BulanGroup9']) ? '0' : $getData['BulanGroup9'],
											'18-19'			=> is_null($getData['BulanGroup10']) ? '0' : $getData['BulanGroup10'],
											'20-21'			=> is_null($getData['BulanGroup11']) ? '0' : $getData['BulanGroup11'],
											'22-24'			=> is_null($getData['BulanGroup12']) ? '0' : $getData['BulanGroup12'],
											'24-more'		=> is_null($getData['BulanGroup13']) ? '0' : $getData['BulanGroup13'],
											'other'			=> is_null($getData['BulanGroup14']) ? '0' : $getData['BulanGroup14'],
											'total_akad'	=> is_null($getData['total_akad']) ? '0' : $getData['total_akad']
									   );
									   
					
				}
				
				while ($getData1 = mysql_fetch_assoc($result1))
				{
					$data['total'][] = array(
											'nm_produk'		=> is_null($getData1['nmproduk']) ? 'LAINNYA' : $getData1['nmproduk'],
											'total 0-1'		=> is_null($getData1['total_bulan1']) ? '0' : $getData1['total_bulan1'],
											'total 2-3'		=> is_null($getData1['total_bulan2']) ? '0' : $getData1['total_bulan2'],
											'total 4-5'		=> is_null($getData1['total_bulan3']) ? '0' : $getData1['total_bulan3'],
											'total 6-7'		=> is_null($getData1['total_bulan4']) ? '0' : $getData1['total_bulan4'],
											'total 8-9'		=> is_null($getData1['total_bulan5']) ? '0' : $getData1['total_bulan5'],
											'total 10-11'	=> is_null($getData1['total_bulan6']) ? '0' : $getData1['total_bulan6'],
											'total 12-13'	=> is_null($getData1['total_bulan7']) ? '0' : $getData1['total_bulan7'],
											'total 14-16'	=> is_null($getData1['total_bulan8']) ? '0' : $getData1['total_bulan8'],
											'total 16-17'	=> is_null($getData1['total_bulan9']) ? '0' : $getData1['total_bulan9'],
											'total 18-19'	=> is_null($getData1['total_bulan10']) ? '0' : $getData1['total_bulan10'],
											'total 20-21'	=> is_null($getData1['total_bulan11']) ? '0' : $getData1['total_bulan11'],
											'total 22-24'	=> is_null($getData1['total_bulan12']) ? '0' : $getData1['total_bulan12'],
											'total 24-more'	=> is_null($getData1['total_bulan13']) ? '0' : $getData1['total_bulan13'],
											'total other'	=> is_null($getData1['total_bulan14']) ? '0' : $getData1['total_bulan14'],
											'total total_akad'	=> is_null($getData1['total_all_bulan']) ? '0' : $getData1['total_all_bulan']
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