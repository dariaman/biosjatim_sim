<?php
$userID 	 		= $_POST['user-id'];
//$date 		 	= $_POST['date'];
$dateStart			= $_POST['dateStart'];
$dateEnd			= $_POST['dateEnd'];
$usersType	 		= $_POST['type']; // Privilege user
$privilege 	 		= $_POST['privilege'];
$groupID			= $_POST['groupID']; // buat nyari berdasarkan group produk
$nmgrup				= $_POST['nmgrup']; // buat nyari berdasarkan group produk
$cabang 	 		= $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)

$deviceVersion  	= $_POST['deviceVersion'];
$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

$cancel =false;
if($userID=="" || $usersType=="" || $groupID=="" || $dateStart=="" || $dateEnd=="" || $nmgrup == "")
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
if($deviceVersion < 1.0)
{
	$json['err_no']  = '20';
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
		$querySelect = "
SELECT
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote = '$dateEnd' THEN ajkpeserta.nama END) AS daily_peserta,
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS monthly_peserta,
						COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startDate'  AND '$dateEnd' THEN ajkpeserta.nama END) AS yearly_peserta,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote = '$dateEnd'  THEN ajkpeserta.plafond END) AS daily_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.plafond END) AS monthly_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startDate' AND '$dateEnd' THEN ajkpeserta.plafond END) AS yearly_kredit,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote = '$dateEnd'  THEN ajkpeserta.totalpremi END) AS daily_premi,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart'  AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS monthly_premi,
						SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startDate'  AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS yearly_premi,
						ajkpolis.produk AS nmproduk,
						$filterselect
						FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						AND ajkpeserta.idclient = '$groupID'
						AND ajkpolis.del is null
						AND ajkpeserta.del is null
						AND ajkregional.del is null
						AND ajkregional.`name` not in ('PUSAT')
						$filterquery
						AND ajkpeserta.statusaktif in ('Inforce','Lapse','Maturnity')
						$filterquery2

						ORDER BY yearly_premi DESC
		";

	$querySelect1 = "
	SELECT nmproduk,
	SUM(daily_peserta) AS total_daily_peserta,
	SUM(monthly_peserta) AS total_monthly_peserta,
	SUM(yearly_peserta) AS total_yearly_peserta,
	SUM(daily_kredit) AS total_daily_kredit,
	SUM(monthly_kredit) AS total_monthly_kredit,
	SUM(yearly_kredit) AS total_yearly_kredit,
	SUM(daily_premi) AS total_daily_premi,
	SUM(monthly_premi) AS total_monthly_premi,
	SUM(yearly_premi) AS total_yearly_premi
	FROM(
		$querySelect
	) AS total_produk
	GROUP BY nmproduk";

	$result 		 = query_db($querySelect);
	$result1 		 = query_db($querySelect1);
	if(!$result)
	{
		echo json_encode(array(
			'err_no' 	=> 1,
			'err_msg' => 'Error Occured. Please try again'
		));
	}
	else
	{
		$data = array();
		while ($row=mysql_fetch_assoc($result)) {
			$nmproduk = $row['nmproduk'];
			$data['data'][] =
											array(
											'regional'	=> is_null($row['regional']) ? 'Lainnya' : $row['regional'],
											'nama produk'		=> $row['nmproduk'],
											'daily peserta'		=> is_null($row['daily_peserta']) ? '0' : $row['daily_peserta'],
											'daily kredit' 	=> is_null($row['daily_kredit']) ? '0' : $row['daily_kredit'],
											'daily premi' 	=> is_null($row['daily_premi']) ? '0' : $row['daily_premi'],
											'monthly peserta'	=> is_null($row['monthly_peserta']) ? '0' : $row['monthly_peserta'],
											'monthly kredit'	=> is_null($row['monthly_kredit']) ? '0' : $row['monthly_kredit'],
											'monthly premi'	=> is_null($row['monthly_premi']) ? '0' : $row['monthly_premi'],
											'yearly peserta'	=> is_null($row['yearly_peserta']) ? '0' : $row['yearly_peserta'],
											'yearly kredit'	=> is_null($row['yearly_kredit']) ? '0' : $row['yearly_kredit'],
											'yearly premi'	=> is_null($row['yearly_premi']) ? '0' : $row['yearly_premi']
											);
		}

		while ($row1=mysql_fetch_assoc($result1)) {
			$data['total'][] =
							array(
							'nama produk'	=> $row1['nmproduk'],
							'total daily peserta'=> is_null($row1['total_daily_peserta']) ? '0' : $row1['total_daily_peserta'],
							'total monthly peserta'=> is_null($row1['total_monthly_peserta']) ? '0' : $row1['total_monthly_peserta'],
							'total yearly peserta'=> is_null($row1['total_yearly_peserta']) ? '0' : $row1['total_yearly_peserta'],
							'total daily kredit'=> is_null($row1['total_daily_kredit']) ? '0' : $row1['total_daily_kredit'],
							'total monthly kredit'=> is_null($row1['total_monthly_kredit']) ? '0' : $row1['total_monthly_kredit'],
							'total yearly kredit'=> is_null($row1['total_yearly_kredit']) ? '0' : $row1['total_yearly_kredit'],
							'total daily premi'=> is_null($row1['total_daily_premi']) ? '0' : $row1['total_daily_premi'],
							'total monthly premi'=> is_null($row1['total_monthly_premi']) ? '0' : $row1['total_monthly_premi'],
							'total yearly premi'=> is_null($row1['total_yearly_premi']) ? '0' : $row1['total_yearly_premi']
							);
		}
		echo json_encode($data);
	}
?>