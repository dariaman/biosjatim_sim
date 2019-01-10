<?php
$userID 	 = $_POST['user-id'];
$dateStart 	 	 = date("Y-m-d",strtotime($_POST['dateStart']));
$dateEnd	 	 = date("Y-m-d",strtotime($_POST['dateEnd']));
$usersType	 	 = $_POST['type'];
$cabang 	 	 	 = $_POST['cabang'];
$sortby 	 	= $_POST['sortby'];
$deviceVersion = $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

$cancel =false;
if($userID=="" ||  $usersType=="")
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

if($usersType == "Direksi"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
	$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkpolis.id";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkpolis.id";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
	}
}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
	$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
	$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
	$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.cabang='$userRegional'";
	$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
	$filterselect = "ajkcabang.name AS regional";

}elseif($usersType == "Broker" or $usersType == "Admin"){
	$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
	$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
	$filterquery2 = "GROUP BY ajkpeserta.regional,ajkpolis.id";
	if($sortby=="Regional"){
		$filterselect = "ajkregional.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkpolis.id";
	}else{
		$filterselect = "ajkcabang.name AS regional";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
	}
}

$querySelect 	= 	"
	SELECT
	produk.regional, produk.nmproduk, produk.jml_peserta_monthly, produk.jumlah_kredit_monthly, produk.total_premi_monthly, klaim.peserta_klaim, klaim.nilai_klaim, klaim.klaim_paid, produk.nmproduk as nmproduk
	FROM
	(
		SELECT
		COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS jml_peserta_monthly,
		SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.tenor END) AS jumlah_kredit_monthly,
		SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS total_premi_monthly,
		ajkpolis.produk as nmproduk,
		$filterselect
		FROM ajkpeserta
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id AND ajkpeserta.idclient = ajkdebitnote.idclient AND ajkpeserta.idpolicy = ajkdebitnote.idproduk
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
		INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
		WHERE ajkpeserta.iddn !=''
		AND ajkdebitnote.del IS NULL
		AND ajkpeserta.del IS NULL
		AND ajkpeserta.statusaktif in ('Inforce','Lapse','Maturnity')
		AND ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd'
		$filterquery
		$filterquery2
		ORDER BY total_premi_monthly DESC
	) AS produk
	LEFT JOIN (
		SELECT $filterselect, ajkpolis.id, ajkpolis.produk as nmproduk,
		COUNT(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Process' OR ajkcreditnote.status = 'Approve' OR ajkcreditnote.status = 'Approve Paid' OR ajkcreditnote.status = 'Approve Unpaid' OR ajkcreditnote.status = 'Tolak' THEN ajkpeserta.nama END) AS peserta_klaim,
		SUM(CASE WHEN ajkcreditnote.status = 'Approve Unpaid' THEN ajkcreditnote.nilaiklaimdiajukan END) AS nilai_klaim,
		SUM(CASE WHEN ajkcreditnote.status = 'Approve Paid' THEN ajkcreditnote.nilaiklaimdiajukan END) AS klaim_paid
		FROM ajkpeserta
		INNER JOIN ajkcreditnote ON ajkcreditnote.idpeserta = ajkpeserta.idpeserta
		INNER JOIN ajkpolis ON ajkpolis.id = ajkpeserta.idpolicy
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
		WHERE ajkcreditnote.tgllengkapdokumen BETWEEN '$dateStart' AND '$dateEnd'
		AND ajkpeserta.del IS NULL
		AND ajkcreditnote.tipeklaim = 'Claim'
		$filterquerycn
		$filterquery2
		ORDER BY peserta_klaim DESC
	) AS klaim ON (produk.regional = klaim.regional AND produk.nmproduk = klaim.nmproduk AND produk.nmproduk = klaim.nmproduk)
	GROUP BY produk.regional
	ORDER BY produk.total_premi_monthly DESC
					";

				$result		= query_db($querySelect);
				$checkrows = mysql_num_rows($result);
				if ($checkrows > 0){
					while ($row = mysql_fetch_assoc($result))
					{
						$data['data'][] = array(
										'regional'			=> substr($row['regional'],0, 18),
										'total premi'		=> is_null($row['total_premi_monthly']) ? '0' : $row['total_premi_monthly'],
										'jumlah kredit' 	=> is_null($row['jumlah_kredit_monthly']) ? '0' : $row['jumlah_kredit_monthly'],
										'jumlah peserta' 	=> is_null($row['jml_peserta_monthly']) ? '0' : $row['jml_peserta_monthly']
										);
					}
				}else{
					$data = array();
				}

				// $result			= query_db($querySelect);
				// while ($getData = mysql_fetch_assoc($result))
				// {
					// $data['data'][] = $getData;
				// }


				if($result)
				{
					// $test['data'][] = array($data;
					echo json_encode($data);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}

?>