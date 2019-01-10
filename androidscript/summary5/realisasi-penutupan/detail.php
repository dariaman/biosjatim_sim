<?php

$userID 	 = $_REQUEST['user-id'];
$usersType	 = $_REQUEST['type']; // Privilege user
$nmgrup	 	 = $_REQUEST['nmgrup'];
$groupID	 = $_REQUEST['groupID'];
$dateStart	 = $_REQUEST['dateStart'];
$dateEnd	 = $_REQUEST['dateEnd'];
$cabang 	 = $_REQUEST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";




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
	$idbroker = $_REQUEST['idbroker'];
}
if($idclient=="0"){
	$idclient = $_REQUEST['idklien'];
}
if($usersType == "Direksi"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional";
		$filterselect = "ajkregional.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional";
		$filterselect = "ajkregional.name AS regional";
	}
}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang";
		$filterselect = "ajkcabang.name AS regional";
	}

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang";
		$filterselect = "ajkcabang.name AS regional";
	}
}elseif($usersType == "Broker" or $usersType == "Admin"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional";
		$filterselect = "ajkregional.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional";
		$filterselect = "ajkregional.name AS regional";
	}
}

	$querySelect1 = "
	SELECT
	COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SPK%' THEN ajkpeserta.nama END) AS peserta,
	SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SPK%' THEN ajkpeserta.tenor END) AS kredit,
	SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SPK%' THEN ajkpeserta.totalpremi END) AS premi,
	COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SKKT%' THEN ajkpeserta.nama END) AS peserta_cpt,
	SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SKKT%' THEN ajkpeserta.tenor END) AS kredit_cpt,
	SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' AND ajkpolis.typemedical LIKE '%SKKT%' THEN ajkpeserta.totalpremi END) AS premi_cpt,
						ajkpolis.produk AS nmproduk,
						$filterselect
						FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						$filterquery
						AND ajkdebitnote.tgldebitnote BETWEEN '$dateStart'  AND '$dateEnd'
						$filterquery2
	";


//echo $querySelect1;
$querySelect2 = "
				SELECT
				nmproduk,
				SUM(peserta) AS total_peserta,
				SUM(premi) AS total_premi,
				SUM(kredit) AS total_kredit,
				SUM(peserta_cpt) AS total_peserta_cpt,
				SUM(premi_cpt) AS total_premi_cpt,
				SUM(kredit_cpt) AS total_kredit_cpt
				FROM
				(
				$querySelect1
				)AS total_realisasi
				GROUP BY nmproduk
				";


				$result1 	= query_db($querySelect1);
				$result2 	= query_db($querySelect2);
				$checkrows 	= mysql_num_rows($result1);
				$checkrows2	= mysql_num_rows($result2);
				if ($checkrows > 0){
					while ($getData1 = mysql_fetch_assoc($result2))
					{
						$persen_total_kredit = @($getData1['total_kredit']/$getData1['total_kredit'])*100;
						$persen_total_premi = @($getData1['total_premi']/$getData1['total_premi'])*100;
						$persen_total_kredit_cpt = @($getData1['total_kredit_cpt']/$getData1['total_kredit_cpt'])*100;
						$persen_total_premi_cpt = @($getData1['total_premi_cpt']/$getData1['total_premi_cpt'])*100;
						while ($getData = mysql_fetch_assoc($result1))
						{
							if($getData1['nmproduk'] == $getData['nmproduk']){

								$nmproduk = $getData['nmproduk'];
								$persen_kredit = @($getData['kredit']/$getData1['total_kredit'])*100;
								$persen_premi = @($getData['premi']/$getData1['total_premi'])*100;
								$persen_kredit_cpt = @($getData['kredit_cpt']/$getData1['total_kredit_cpt'])*100;
								$persen_premi_cpt = @($getData['premi_cpt']/$getData1['total_premi_cpt'])*100;
								//$data1['data'][] = $getData;
								$data1['data'][] = array(
													'regional'			=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
													'peserta'			=> is_null($getData['peserta']) ? '0' : $getData['peserta'],
													'kredit'			=> is_null($getData['kredit']) ? '0' : $getData['kredit'],
													'persen_kredit'		=> is_null($persen_kredit) ? '0' : round($persen_kredit,2),
													'premi'				=> is_null($getData['premi']) ? '0' : $getData['premi'],
													'persen_premi'		=> is_null($persen_premi) ? '0' : round($persen_premi,2),
													'peserta_cpt'		=> is_null($getData['peserta_cpt']) ? '0' : $getData['peserta_cpt'],
													'kredit_cpt'		=> is_null($getData['kredit_cpt']) ? '0' : $getData['kredit_cpt'],
													'persen_kredit_cpt'	=> is_null($persen_kredit_cpt) ? '0' : round($persen_kredit_cpt,2),
													'premi_cpt'			=> is_null($getData['premi_cpt']) ? '0' : $getData['premi_cpt'],
													'persen_premi_cpt'	=> is_null($persen_premi_cpt) ? '0' : round($persen_premi_cpt,2)
												  );
							}
						}
							$nmproduk = $getData1['nmproduk'];

						$data1['total'][] = array(
											'nm_produk'		=> is_null($nmproduk) ? 'LAINNYA' : $nmproduk,
											'total_peserta'	=> is_null($getData1['total_peserta']) ? '0' : $getData1['total_peserta'],
											'total_kredit'	=> is_null($getData1['total_kredit']) ? '0' : $getData1['total_kredit'],
											'persen_total_kredit'=> is_null($persen_total_kredit) ? '0' : round($persen_total_kredit),
											'total_premi'	=> is_null($getData1['total_premi']) ? '0' : $getData1['total_premi'],
											'persen_total_premi'=> is_null($persen_total_premi) ? '0' : round($persen_total_premi),
											'total_peserta_cpt'	=> is_null($getData1['total_peserta_cpt']) ? '0' : $getData1['total_peserta_cpt'],
											'total_kredit_cpt'	=> is_null($getData1['total_kredit_cpt']) ? '0' : $getData1['total_kredit_cpt'],
											'persen_total_kredit_cpt'=> is_null($persen_total_kredit_cpt) ? '0' : round($persen_total_kredit_cpt),
											'total_premi_cpt'	=> is_null($getData1['total_premi_cpt']) ? '0' : $getData1['total_premi_cpt'],
											'persen_total_premi_cpt'=> 0
										  );
						mysql_data_seek($result1, 0);
					}
					mysql_free_result($result1);
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


?>