<?php

	$usersType	 = $_POST['type'];
	$cabang 	 = $_POST['cabang'];
	$groupID 	 = $_POST['groupID'];
	$deviceVersion 	= $_POST['deviceVersion'];

	$cancel =false;
	if($usersType=="")
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

$usersType = 'Direksi_GM';
if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{
	$querySelect = "SELECT nmproduk
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
								SELECT pr.usia, pr.regional, po.produk as nmproduk FROM ajkpeserta pr, ajkpolis po
								WHERE po.idcost = '$groupID' /*'$groupID'*/
								AND pr.idpolicy = po.idp
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
							WHEN  usia < 45 THEN 'under_45'
							WHEN  usia >= 45 AND usia <= 50 THEN '45-50'
							WHEN  usia >= 51 AND usia <= 55 THEN '51-55'
							WHEN  usia >= 56 AND usia <= 60 THEN '56-60'
							WHEN  usia >= 61 AND usia <= 65 THEN '61-65'
							WHEN  usia >= 66 AND usia <= 70 THEN '66-70'
							WHEN  usia >= 71 AND usia <= 75 THEN '71-75'
							END AS AgeGroup, cabang, nmproduk
							FROM    (
								SELECT pr.usia, pr.cabang, po.nmproduk FROM fu_ajk_peserta pr, fu_ajk_polis po
								WHERE pr.id_cost = '$groupID'
								AND pr.id_polis = po.id
								AND pr.regional = '$cabang'
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