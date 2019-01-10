<?php

$userID 	 = $_REQUEST['user-id'];
$dateStart 	 = date("Y-m-d",strtotime($_REQUEST['dateStart']));
$dateEnd	 = date("Y-m-d",strtotime($_REQUEST['dateEnd']));
$usersType	 = $_REQUEST['type'];
$cabang 	 	 = $_REQUEST['cabang'];
$sortby 	 	= $_POST['sortby'];
$deviceVersion = $_POST['deviceVersion'];

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
if($usersType == "Direksi"){
	$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
	}
}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
	$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
	$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Broker" or $usersType == "Admin"){
	$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
	}


}

$querySelect1 = "

SELECT regional,
												SUM(jumlah_klaim) AS total_jumlah_kredit,
												SUM(jml_peserta) AS total_jumlah_peserta
									FROM(
											SELECT $filterselect,ajkpolis.produk,
											SUM(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Process' OR ajkcreditnote.status = 'Approve' OR ajkcreditnote.status = 'Approve Paid' OR ajkcreditnote.status = 'Approve Unpaid' OR ajkcreditnote.status = 'Tolak' THEN IFNULL(ajkcreditnote.nilaiclaimclient,ajkcreditnote.nilaiklaimdiajukan) END) AS jumlah_klaim,
											COUNT(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Process' OR ajkcreditnote.status = 'Approve' OR ajkcreditnote.status = 'Approve Paid' OR ajkcreditnote.status = 'Approve Unpaid' OR ajkcreditnote.status = 'Tolak' THEN IFNULL(ajkcreditnote.nilaiclaimclient,ajkcreditnote.nilaiklaimdiajukan) END) AS jml_peserta
											FROM ajkpeserta
													 INNER JOIN ajkcreditnote ON ajkcreditnote.idpeserta = ajkpeserta.idpeserta
													 INNER JOIN ajkpolis ON ajkpolis.id = ajkpeserta.idpolicy
													 INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
													 INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
											WHERE ajkpeserta.del IS NULL
											AND ajkcreditnote.del IS NULL
											AND ajkcreditnote.tipeklaim = 'Claim'
											AND DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') BETWEEN '$dateStart' AND '$dateEnd'
											$filterquery
											$filterquery2
											ORDER BY jml_peserta DESC
										)AS summary_klaim_report GROUP BY regional";

				$result1 		= query_db($querySelect1);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						if($getData['total_jumlah_kredit'] != NULL){
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'regional'				=> substr($getData['regional'],0, 18),
											'total_jumlah_premi'	=> '0',
											'total_jumlah_kredit'	=> is_null($getData['total_jumlah_kredit']) ? '0' : $getData['total_jumlah_kredit'],
											'total_jumlah_peserta' 	=> is_null($getData['total_jumlah_peserta']) ? '0' : $getData['total_jumlah_peserta']
											);
						}else{

						}
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

?>