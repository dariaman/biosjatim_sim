<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$nmgrup	 	 = $_POST['nmgrup'];
$groupID	 = $_POST['groupID'];
$dateStart   = $_POST['dateStart'];
$dateEnd   	 = $_POST['dateEnd'];
$deviceVersion = $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

$cancel =false;
if($userID=="" || $usersType=="" || $dateStart=="" || $dateEnd=="")
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

/*
if($deviceVersion <= 1.10)
{
	$json['err_no']  = '2';
	$json['err_msg'] = 'You need to upgrade your device!!!';

	echo json_encode($json);
	die();
}
*/
$queryUserID 	= "SELECT id,branch,idbroker, idclient, regional, level FROM useraccess WHERE id='$userID'";
$resultUserID 	= query_db($queryUserID);
$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
$userCabang 	= $encodedUserID['branch'];
$userRegional 	= $encodedUserID['regional'];
$idbroker 	= $encodedUserID['idbroker'];
$userlevel 	= $encodedUserID['level'];
$idclient 	= $encodedUserID['idclient'];
$encodedUserID 	= $encodedUserID['id'];
if($idbroker=="0"){
	$idbroker = $_POST['idbroker'];
}
if($idclient=="0"){
	$idclient = $_POST['idklien'];
}

//Insert to spak table
$query_last_spak 	= "SELECT MAX(id) as max FROM ajkspk";
$result_last_spak 	= query_db($query_last_spak);
if(mysql_num_rows($result_last_spak)>0)
{
	$row 			=  mysql_fetch_assoc($result_last_spak);
	$next_spak_id 	=$row['max']+1;
}else{
	$next_spak_id	=1;
}
if($usersType == "Direksi"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
		$filterselect = "ajkregional.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
		$filterselect = "ajkregional.name AS regional";
	}
}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}
}elseif($usersType == "Broker" or $usersType == "Admin"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
		$filterselect = "ajkregional.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
		$filterselect = "ajkregional.name AS regional";
	}
}

		$query_select 	= "
SELECT
						SUM(CASE WHEN ajkpeserta.statuslunas = '1' THEN ajkpeserta.totalpremi END) AS premi_paid,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startDate'  AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS total_premi,
						ajkpolis.produk AS nmproduk,
						$filterselect
						FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						AND ajkpeserta.idclient = '$groupID'
						AND ajkpeserta.statusaktif in ('Inforce')
						$filterquery
						AND ajkdebitnote.tgldebitnote BETWEEN '$startDate'  AND '$dateEnd'
						$filterquery2
						ORDER BY total_premi DESC";

		$query_select1 	= "
				SELECT
				nmproduk,
				SUM(total_premi) AS total_jml_premi,
				SUM(premi_paid) AS total_premi_paid
				FROM(
				$query_select
				) AS total_outstanding
				GROUP BY nmproduk ORDER BY total_jml_premi DESC
				";

				$result2 		= query_db($query_select);
				$result20 		= query_db($query_select1);
				$checkrows2 = mysql_num_rows($result2);
				$checkrows20 = mysql_num_rows($result20);

				if (($checkrows2 > 0) || ($checkrows20 > 0)){
					while ($getData1 = mysql_fetch_assoc($result20))
					{
						$total_jml_premi = is_null($getData1['total_jml_premi']) ? '0' : $getData1['total_jml_premi'];
						if($total_jml_premi > 0){
							$persen_total = ($total_jml_premi / $total_jml_premi) * 100;
							$total_premi_unpaid = $getData1['total_jml_premi'] - $getData1['total_premi_paid'];
							while ($getData = mysql_fetch_assoc($result2))
							{
								//$data1['data'][] = $getData;
								$total_premi = is_null($getData['total_premi']) ? '0' : $getData['total_premi'];
								if($total_premi > 0){
									$premi_unpaid = $getData['total_premi']-$getData['premi_paid'];
									if($getData1['nmproduk'] == $getData['nmproduk']){

										$persentase = ($total_premi/$total_jml_premi) * 100;
										$data1['data'][] =	array(
													'regional'	=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
													'nmproduk'	=> is_null($getData['nmproduk']) ? 'Unknown' : $getData['nmproduk'],
													'total_premi'=> is_null($getData['total_premi']) ? '0' : $getData['total_premi'],
													'premi_paid'=> is_null($getData['premi_paid']) ? '0' : $getData['premi_paid'],
													'premi_unpaid'=> is_null($premi_unpaid) ? '0' : $premi_unpaid,
													'persen_premi'=> is_null($persentase) ? '0' : round($persentase,2)
												);
									}
								}else{

								}
							}
						}else{

						}

						$data1['total'][] = array(
									'nmproduk'	=> is_null($getData1['nmproduk']) ? 'Unknown' : $getData1['nmproduk'],
									'total_jml_premi'=> is_null($getData1['total_jml_premi']) ? '0' : $getData1['total_jml_premi'],
									'total_premi_paid'=> is_null($getData1['total_premi_paid']) ? '0' : $getData1['total_premi_paid'],
									'total_premi_unpaid'=> is_null($total_premi_unpaid) ? '0' : $total_premi_unpaid,
									'persen_total_premi'=> is_null($persen_total) ? '0' : $persen_total
											);
					mysql_data_seek($result2, 0);
					}
					mysql_free_result($result2);
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


?>