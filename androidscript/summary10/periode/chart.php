<?php
$userID 	 	= $_POST['user-id'];
$dateStart 	 	= date("Y-m-d",strtotime($_POST['dateStart']));
$dateEnd	 	= date("Y-m-d",strtotime($_POST['dateEnd']));
$year = date("Y");
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";
//$range  	 	= $_POST['range'];
$usersType	 	= $_POST['type'];
$privilege 	 	= $_POST['privilege'];
$sortby 	 	= $_POST['sortby'];
$deviceVersion 	= $_POST['deviceVersion'];

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

if($idbroker==0){
	$idbroker = $_POST['idbroker'];
}
if($idclient==0){
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
if($usersType == "Direksi" AND $userlevel == "15"){
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
elseif($usersType == "Direksi" AND $userlevel = "14"){
    $filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
    if($sortby=="Regional"){
        $filterselect = "ajkarea.name AS regional";
        $filterquery2 = "GROUP BY ajkpeserta.area,ajkdebitnote.idproduk";
    }else{
        $filterselect = "ajkcabang.name AS regional";
        $filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
    }
}
elseif($usersType == "Kadiv" AND $userlevel =="13"){
    if ($userCabang == "1")
    {
        $filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
    }
    else{
        $filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
    }
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
	$filterselect = "ajkcabang.name AS regional";

}
elseif($usersType == "Kadiv" AND $userlevel =="12"){
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
	$querySelect2 = "SELECT regional,
					SUM(monthly_peserta) AS total_peserta_montly,
					SUM(monthly_kredit)  AS total_kredit_montly,
					SUM(monthly_premi) 	 AS total_premi_montly
					FROM (
						SELECT
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS daily_peserta,
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS monthly_peserta,
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS yearly_peserta,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd'  THEN ajkpeserta.plafond END) AS daily_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.plafond END) AS monthly_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.plafond END) AS yearly_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart'  AND '$dateEnd'  THEN ajkpeserta.totalpremi END) AS daily_premi,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart'  AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS monthly_premi,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart'  AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS yearly_premi,
						ajkpolis.produk AS nmproduk,
						$filterselect
						FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						INNER JOIN ajkarea ON ajkpeserta.area = ajkarea.er
						WHERE ajkpeserta.id !=''
						$filterquery
						AND ajkpeserta.statusaktif in ('Inforce','Pending','Lapse','Maturnity','Approve')
						AND ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd'
						$filterquery2
						ORDER BY yearly_premi DESC
						)
				AS summary_report GROUP BY regional";

				$result2 		 = query_db($querySelect2);
				$checkrows2 = mysql_num_rows($result2);
				if ($checkrows2 > 0){
					while ($getData2 = mysql_fetch_assoc($result2))
					{
						$data2['data'][] = array(
											'querySelect2'				=> $querySelect2,
											'regional'				=> $getData2['regional'],
											'total_peserta_daily'	=> is_null($getData2['total_peserta_montly']) ? '0' : $getData2['total_peserta_montly'],
											'total_kredit_daily'	=> is_null($getData2['total_kredit_montly']) ? '0' : $getData2['total_kredit_montly'],
											'total_premi_daily' 	=> is_null($getData2['total_premi_montly']) ? '0' : $getData2['total_premi_montly']
											);
					}
				}else{
					$data2 = array();
				}

					echo json_encode($data2);

?>