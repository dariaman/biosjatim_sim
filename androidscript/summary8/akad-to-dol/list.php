<?php

	$usersType	 	= $_POST['type'];
	$privilege 		= $_POST['privilege'];
	$cabang 	 	= $_POST['cabang'];
	$groupID 	 	= $_POST['groupID'];
	$deviceVersion 	= $_POST['deviceVersion'];

	$cancel =false;
	if($usersType=="" || $privilege=="")
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

if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{
	$querySelect = "SELECT  nmproduk
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
										END AS MonthGroup, id_regional, nmproduk
								FROM    (
									SELECT ROUND(
									DATEDIFF(kl.tgl_klaim, dn.tgl_createdn)/12
									) AS bulan, dn.id_regional, po.nmproduk FROM fu_ajk_klaim kl, fu_ajk_dn dn, fu_ajk_polis po 
									WHERE dn.id_cost = '$groupID' 
									AND dn.id = kl.id_dn
									AND dn.id_nopol = po.id
										) AS SubQueryAlias
								) AS SubQueryAlias2
						GROUP BY
								nmproduk";

	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = $getData;
	}


	if($result)
	{

		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}
	
}else{
	
	$querySelect = "SELECT nmproduk
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
										END AS MonthGroup, id_cabang, nmproduk
								FROM    (
									SELECT ROUND(
									DATEDIFF(kl.tgl_klaim, dn.tgl_createdn)/12
									) AS bulan, dn.id_cabang, po.nmproduk FROM fu_ajk_klaim kl, fu_ajk_dn dn, fu_ajk_polis po 
									WHERE dn.id_cost = '$groupID' 
									AND dn.id_regional = '$cabang'
									AND dn.id = kl.id_dn
									AND dn.id_nopol = po.id
										) AS SubQueryAlias
								) AS SubQueryAlias2
						GROUP BY
								nmproduk";

	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = $getData;
	}


	if($result)
	{

		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}
}

?>