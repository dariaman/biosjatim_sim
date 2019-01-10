<?php
$userID 	 	= $_POST['user-id'];
$dateStart 	 	= $_POST['dateStart'];
$dateEnd	 	= $_POST['dateEnd'];
$year = date("Y");
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $year."-".$monthly."-01";
$startDate = $year."-01-01";
$usersType	 	= $_POST['type'];
$sortby 	 	= $_POST['sortby'];
$deviceVersion 	= $_POST['deviceVersion'];
$dataStatus 	= $_POST['dataStatus'];
if($dataStatus=="All Status"){
	$statuscart = '';
}else{
	$statuscart = "AND ajkpeserta.statusaktif = 'Inforce'";
}

$cancel =false;
if($userID=="" || $usersType=="" || $dateStart == "" || $dateEnd == "")
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

if($idbroker=="0"){
	$idbroker = $_POST['idbroker'];
}
if($idclient=="0"){
	$idclient = $_POST['idklien'];
}

if($usersType == "Direksi"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
	$filterselect = "ajkregional.name AS regional";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
	}

}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
    if($sortby=="Regional"){
        $filterselect = "ajkregional.name AS regional";
        $filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
    }else{
        $filterselect = "ajkcabang.name AS regional";
        $filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
    }
	//$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
	$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Broker" or $usersType == "Admin"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkdebitnote.idproduk";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
	}


}
//AND fu_ajk_peserta.status_aktif in ('Inforce','Pending','Lapse','Maturnity','Approve') req pak gun dengan hansen per 161003
$querySelect2 = "SELECT regional,
					SUM(peserta) AS total_peserta,
					SUM(kredit) AS total_kredit,
					SUM(premi) AS total_premi
					FROM (
						SELECT
						COUNT(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS peserta,
						SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) BETWEEN '$dateStart'  AND '$dateEnd'  THEN ajkpeserta.plafond END) AS kredit,
						SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) BETWEEN '$dateStart'  AND '$dateEnd'  THEN ajkpeserta.totalpremi END) AS premi,
						ajkpolis.produk AS nmproduk,
						$filterselect
						FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						$filterquery
						AND YEAR(ajkdebitnote.tgldebitnote) BETWEEN '$dateStart' AND '$dateEnd'
						$filterquery2
						ORDER BY premi DESC
						)
				AS summary_report GROUP BY regional";

				$result2 		 = query_db($querySelect2);
				$checkrows2 = mysql_num_rows($result2);
				if ($checkrows2 > 0){
					while ($getData2 = mysql_fetch_assoc($result2))
					{
						$data2['data'][] = array(
											'dataStatus'				=> $dataStatus,
											'querySelect2'				=> $querySelect2,
											'regional'				=> substr($getData2['regional'],0, 18),
											'total_peserta_daily'	=> is_null($getData2['total_peserta']) ? '0' : $getData2['total_peserta'],
											'total_kredit_daily'	=> is_null($getData2['total_kredit']) ? '0' : $getData2['total_kredit'],
											'total_premi_daily' 	=> is_null($getData2['total_premi']) ? '0' : $getData2['total_premi']
											);
					}
				}else{
					$data2 = array();
				}

					echo json_encode($data2);


?>