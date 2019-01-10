<?php

$userID 	 	= $_POST['user-id'];
$dateStart	 	= $_POST['dateStart'];
$dateEnd	 	= $_POST['dateEnd'];
$groupID	 	= $_POST['groupID'];
$nmgrup	 		= $_POST['nmgrup'];
$usersType	 	= $_POST['type']; // Privilege user
$cabang 	 	= $_POST['regional']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion 	= $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";

if($userID=="" || $dateStart =="" || $dateEnd == "" || $usersType=="" || $groupID == "")
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
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
		$filterselect = "ajkcabang.name AS cabang";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
		$filterselect = "ajkcabang.name AS cabang";
	}
}elseif($usersType == "Broker" or $usersType == "Admin"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient'";
		$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
		$filterselect = "ajkcabang.name AS cabang";
	}else{
		$filterquery = "AND ajkdebitnote.idbroker = '$idbroker' AND ajkdebitnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquerycn = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkpolis.id";
		$filterselect = "ajkcabang.name AS cabang";
	}
}

	$querySelect = "
SELECT
	produk.cabang, produk.jml_peserta, produk.jumlah_kredit, produk.total_premi, klaim.peserta_klaim, klaim.nilai_klaim, klaim.klaim_paid, produk.nmproduk as nmproduk
	FROM
	(
		SELECT
		COUNT(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS jml_peserta,
		SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.tenor END) AS jumlah_kredit,
		SUM(CASE WHEN ajkdebitnote.tgldebitnote BETWEEN '$dateStart' AND '$dateEnd' THEN ajkpeserta.totalpremi END) AS total_premi,
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
		AND ajkregional.er = '$cabang'
		$filterquery
		$filterquery2
		ORDER BY total_premi DESC
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
		WHERE ajkcreditnote.tgllengkapdokumen BETWEEN '2017-01-01' AND '$dateEnd'
		AND ajkpeserta.del IS NULL
		AND ajkcreditnote.tipeklaim = 'Claim'
		AND ajkregional.er = '$cabang'
		$filterquerycn
		$filterquery2
		ORDER BY peserta_klaim DESC
	) AS klaim ON (produk.cabang = klaim.cabang AND produk.nmproduk = klaim.nmproduk AND produk.nmproduk = klaim.nmproduk)
	GROUP BY produk.cabang
	ORDER BY produk.total_premi DESC
	";


$querySelect1 		= "	SELECT
						nmproduk,
						SUM(jml_peserta) AS total_peserta,
						SUM(jumlah_kredit) AS total_kredit,
						SUM(total_premi) AS total_jml_premi,
						SUM(peserta_klaim) AS total_peserta_klaim,
						SUM(nilai_klaim) AS total_klaim,
						SUM(klaim_paid) AS total_paid
						FROM(
						$querySelect
						) AS total_premi
						GROUP BY nmproduk
						";

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
					while ($row1=mysql_fetch_assoc($result1))
					{
						$persen_total_peserta = ($row1['total_peserta_klaim']/$row1['total_peserta'])*100;
						$persen_total_klaim = ($row1['total_klaim']/$row1['total_jml_premi'])*100;
						$persen_total_paid = ($row1['total_paid']/$row1['total_jml_premi'])*100;
						while ($row=mysql_fetch_assoc($result))
						{
							if($row1['nmproduk'] == $row['nmproduk']){
								$persen_peserta = ($row['peserta_klaim']/$row['jml_peserta'])*100;
								$persen_klaim = ($row['nilai_klaim']/$row['total_premi'])*100;
								$persen_paid = ($row['klaim_paid']/$row['total_premi'])*100;
								// $nmproduk = $row['nmproduk'];
								$nmproduk = $row['nmproduk'];
								$data['data'][] = array('nama produk'	=> $row['nmproduk'],
														 'regional'		=> is_null($row['cabang']) ? 'Lainnya' : $row['cabang'],
														 'total premi'	=> is_null($row['total_premi']) ? '0' : $row['total_premi'],
														 'jumlah kredit' => is_null($row['jumlah_kredit']) ? '0' : $row['jumlah_kredit'],
														 'jumlah peserta' => is_null($row['jml_peserta']) ? '0' : $row['jml_peserta'],
														 'peserta klaim' => is_null($row['peserta_klaim']) ? '0' : $row['peserta_klaim'],
														 'nilai klaim' 	=> is_null($row['nilai_klaim']) ? '0' : $row['nilai_klaim'],
														 'klaim dibayar' => is_null($row['klaim_paid']) ? '0' : $row['klaim_paid'],
														 'persen peserta' => is_null($persen_peserta) ? '0' : round($persen_peserta,2),
														 'persen nilai' => is_null($persen_klaim) ? '0' : round($persen_klaim,2),
														 'persen paid' => is_null($persen_paid) ? '0' : round($persen_paid,2)
														);
							}

						}
					$data['total'][] = array(
									 'nama produk'	=> $row1['nmproduk'],
									 'total peserta' 	=> is_null($row1['total_peserta']) ? '0' : $row1['total_peserta'],
									 'total kredit' 	=> is_null($row1['total_kredit']) ? '0' : $row1['total_kredit'],
									 'total premi'		=> is_null($row1['total_jml_premi']) ? '0' : $row1['total_jml_premi'],
									 'total peserta klaim' 	=> is_null($row1['total_peserta_klaim']) ? '0' : $row1['total_peserta_klaim'],
									 'total klaim' 	=> is_null($row1['total_klaim']) ? '0' : $row1['total_klaim'],
									 'total dibayar' 	=> is_null($row1['total_paid']) ? '0' : $row1['total_paid'],
									 'persen total peserta' => is_null($persen_total_peserta) ? '0' : round($persen_total_peserta,2),
									 'persen total nilai' => is_null($persen_total_klaim) ? '0' : round($persen_total_klaim,2),
									 'persen total paid' => is_null($persen_total_paid) ? '0' : round($persen_total_paid,2)
									);
					mysql_data_seek($result, 0);
					}
					mysql_free_result($result);
					echo json_encode($data);
				}

?>