<?php
$userID 	 		= $_REQUEST['user-id'];
$dateStart			= $_REQUEST['dateStart'];
$dateEnd			= $_REQUEST['dateEnd'];
$usersType	 		= $_REQUEST['type']; // Privilege user
$privilege 	 		= $_REQUEST['privilege'];
$groupID			= $_REQUEST['groupID']; // buat nyari berdasarkan group produk
$nmgrup				= $_REQUEST['nmgrup']; // buat nyari berdasarkan group produk
$regional			= $_REQUEST['regional'];
$deviceVersion  	= $_REQUEST['deviceVersion'];
$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startYear = $year."-01-01";
$endYear = $year."-12-31";
$cancel =false;

if($userID=="" || $usersType=="" || $groupID=="" || $dateStart=="" || $dateEnd=="" || $regional == "")
{
    $cancel=true;
}

if($cancel)
{
    $json['err_no']  = '1';
    $json['err_msg'] = 'Error occured. Please try again. a';

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

if($nmgrup == 'All Report'){
    $querySelect = "
    SELECT
			COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS daily_peserta,
			COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.nama END) AS monthly_peserta,
			COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.nama END) AS yearly_peserta,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.plafond END) AS daily_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.plafond END) AS monthly_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.plafond END) AS yearly_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS daily_premi,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.totalpremi END) AS monthly_premi,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.totalpremi END) AS yearly_premi,
ajkpolis.produk as nmproduk,
		ajkcabang.name as cabang
		FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkarea ON ajkcabang.idarea = ajkarea.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						AND ajkpeserta.idclient = '$groupID'
						AND ajkarea.name = '$regional'
						AND ajkpeserta.statusaktif in ('Inforce','Pending','Lapse','Maturnity','Approve')
						GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk
						ORDER BY yearly_premi DESC";
}else{

    $coba = "";
    $querySelect = "
    SELECT
    		COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.nama END) AS daily_peserta,
			COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.nama END) AS monthly_peserta,
			COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.nama END) AS yearly_peserta,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.plafond END) AS daily_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.plafond END) AS monthly_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.plafond END) AS yearly_kredit,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS daily_premi,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startMonth' AND LAST_DAY('$dateEnd') THEN ajkpeserta.totalpremi END) AS monthly_premi,
			SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$startYear'  AND '$endYear' THEN ajkpeserta.totalpremi END) AS yearly_premi,
ajkpolis.produk as nmproduk,
		ajkcabang.name as cabang
		FROM ajkdebitnote
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
						INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
						WHERE ajkpeserta.id !=''
						AND ajkpeserta.idclient = '$groupID'
						AND ajkregional.name = '$regional'
						AND ajkpolis.produk = '$nmgrup'
						AND ajkpeserta.statusaktif in ('Inforce','Pending','Lapse','Maturnity','Approve')
						GROUP BY ajkpeserta.cabang,ajkdebitnote.idproduk
						ORDER BY yearly_premi DESC";
}

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
        								'regional'	=> is_null($row['cabang']) ? 'Lainnya' : $row['cabang'],
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