<?php
$userID 	 		= $_POST['user-id'];
$dateStart			= $_POST['dateStart'];
$dateEnd			= $_POST['dateEnd'];
$usersType	 		= $_POST['type']; // Privilege user
$privilege 	 		= $_POST['privilege'];
$groupID			= $_POST['groupID']; // buat nyari berdasarkan group produk
$nmgrup				= $_POST['nmgrup']; // buat nyari berdasarkan group produk
$regional			= $_POST['regional'];
$deviceVersion  	= $_POST['deviceVersion'];
$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$dateStart2 = $dateStart+1;
$dataStatus 	= $_POST['dataStatus'];
if($dataStatus=="All Status"){
	$statusdetail = '';
}else{
	$statusdetail = "AND ajkpeserta.statusaktif = 'Inforce'";
}
$cancel =false;
if($userID=="" || $usersType=="" || $groupID=="" || $dateStart=="" || $dateEnd=="" || $regional == "")
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

if($usersType == "Direksi" or $usersType == "Admin" or $usersType == "Broker"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup' ";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}
}
		$querySelect = "
		SELECT
		COUNT(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart' $statusdetail THEN ajkpeserta.nama END) AS year1_peserta,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart' $statusdetail THEN ajkpeserta.plafond END) AS year1_kredit,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart' $statusdetail THEN ajkpeserta.totalpremi END) AS year1_premi,

		COUNT(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart2' $statusdetail THEN ajkpeserta.nama END) AS year2_peserta,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart2' $statusdetail THEN ajkpeserta.plafond END) AS year2_kredit,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateStart2' $statusdetail THEN ajkpeserta.totalpremi END) AS year2_premi,

		COUNT(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateEnd' $statusdetail THEN ajkpeserta.nama END) AS year3_peserta,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateEnd' $statusdetail THEN ajkpeserta.plafond END) AS year3_kredit,
		SUM(CASE WHEN YEAR(ajkdebitnote.tgldebitnote) = '$dateEnd' $statusdetail THEN ajkpeserta.totalpremi END) AS year3_premi,
		ajkpolis.produk AS nmproduk,
		$filterselect
		FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						AND ajkpeserta.idclient = '$groupID'
						AND ajkregional.name = '$regional'
						$filterquery
						$statusdetail
						$filterquery2
		ORDER BY year1_premi DESC";

$querySelect1 = "
	SELECT nmproduk,
	SUM(year1_peserta) AS total_daily_peserta,
	SUM(year1_kredit) AS total_monthly_peserta,
	SUM(year1_premi) AS total_yearly_peserta,
	SUM(year2_peserta) AS total_daily_kredit,
	SUM(year2_kredit) AS total_monthly_kredit,
	SUM(year2_premi) AS total_yearly_kredit,
	SUM(year3_peserta) AS total_daily_premi,
	SUM(year3_kredit) AS total_monthly_premi,
	SUM(year3_premi) AS total_yearly_premi
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
											'daily peserta'		=> is_null($row['year1_peserta']) ? '0' : $row['year1_peserta'],
											'daily kredit' 	=> is_null($row['year1_kredit']) ? '0' : $row['year1_kredit'],
											'daily premi' 	=> is_null($row['year1_premi']) ? '0' : $row['year1_premi'],
											'monthly peserta'	=> is_null($row['year2_peserta']) ? '0' : $row['year2_peserta'],
											'monthly kredit'	=> is_null($row['year2_kredit']) ? '0' : $row['year2_kredit'],
											'monthly premi'	=> is_null($row['year2_premi']) ? '0' : $row['year2_premi'],
											'yearly peserta'	=> is_null($row['year3_peserta']) ? '0' : $row['year3_peserta'],
											'yearly kredit'	=> is_null($row['year3_kredit']) ? '0' : $row['year3_kredit'],
											'yearly premi'	=> is_null($row['year3_premi']) ? '0' : $row['year3_premi']
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