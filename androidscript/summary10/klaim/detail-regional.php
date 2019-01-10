<?php
$userID 	 = $_POST['user-id'];
// $date 	 = $_POST['date'];
// $range  	 = $_POST['range'];
// $nmproduk = $_POST['nmproduk'];
$dateStart 	 = $_POST['dateStart'];
$dateEnd	 = $_POST['dateEnd'];
$nmgrup	 	 = $_POST['nmgrup'];
$regional	 = $_POST['regional'];
$groupID	 = $_POST['groupID']; // buat nyari berdasarkan group produk
//$statusKlaim = $_POST['statusKlaim'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$cancel =false;
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
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}else{
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}
}elseif($usersType == "Kadiv" AND $userlevel =="13"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}else{
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.regional='$userRegional' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}

}elseif($usersType == "Kadiv" AND $userlevel =="12"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS regional";
	}else{
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpeserta.cabang='$userCabang' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.cabang,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}
}elseif($usersType == "Broker" or $usersType == "Admin"){
	if($nmgrup == 'All Report'){
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}else{
		$filterquery = "AND ajkcreditnote.idbroker = '$idbroker' AND ajkcreditnote.idclient = '$idclient' AND ajkpolis.produk = '$nmgrup'";
		$filterquery2 = "GROUP BY ajkpeserta.regional,ajkcreditnote.idproduk";
		$filterselect = "ajkcabang.name AS cabang";
	}
}

	$querySelect = "
SELECT ajkpolis.produk as nmproduk,
$filterselect,
SUM(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Approve Unpaid' THEN ajkcreditnote.nilaiklaimdiajukan END) AS adm_nilai_klaim,
SUM(CASE WHEN ajkcreditnote.status = 'Process' THEN ajkcreditnote.nilaiklaimdiajukan END) AS proses_klaim,
SUM(CASE WHEN ajkcreditnote.status = 'Approve' THEN ajkcreditnote.nilaiklaimdiajukan END) AS settled_klaim,
SUM(CASE WHEN ajkcreditnote.status = 'Approve Paid' THEN ajkcreditnote.nilaiklaimdiajukan END)  AS klaim_finish,
SUM(CASE WHEN ajkcreditnote.status = 'Tolak' THEN ajkcreditnote.nilaiklaimdiajukan END) AS klaim_tolak,

COUNT(CASE WHEN ajkcreditnote.status = 'Request' THEN ajkcreditnote.nilaiklaimdiajukan END) AS adm_klaim_peserta,
COUNT(CASE WHEN ajkcreditnote.status = 'Process' THEN ajkcreditnote.nilaiklaimdiajukan END) AS proses_klaim_peserta,
COUNT(CASE WHEN ajkcreditnote.status = 'Approve' THEN ajkcreditnote.nilaiklaimdiajukan END) AS klaim_peserta_settled,
COUNT(CASE WHEN ajkcreditnote.status = 'Approve Paid' THEN ajkcreditnote.nilaiklaimdiajukan END) AS klaim_peserta_finish,
COUNT(CASE WHEN ajkcreditnote.status = 'Tolak' THEN ajkcreditnote.nilaiklaimdiajukan END) AS klaim_peserta_tolak,

SUM(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Process' OR ajkcreditnote.status = 'Approve' OR ajkcreditnote.status = 'Approve Paid' OR ajkcreditnote.status = 'Approve Unpaid' OR ajkcreditnote.status = 'Tolak' THEN ajkcreditnote.nilaiklaimdiajukan END) AS all_klaim_nilai,
COUNT(CASE WHEN ajkcreditnote.status = 'Request' OR ajkcreditnote.status = 'Process' OR ajkcreditnote.status = 'Approve' OR ajkcreditnote.status = 'Approve Paid' OR ajkcreditnote.status = 'Approve Unpaid' OR ajkcreditnote.status = 'Tolak' THEN ajkcreditnote.nilaiklaimdiajukan END) AS all_klaim_peserta
FROM ajkcreditnote
INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
WHERE
ajkcreditnote.tipeklaim = 'Claim'
AND DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') BETWEEN '$dateStart' AND '$dateEnd'
AND ajkregional.name = '$regional'
$filterquery
$filterquery2";

$querySelect1 = "SELECT
				nmproduk,
				SUM(adm_nilai_klaim) AS total_adm_klaim,
				SUM(proses_klaim) AS total_process_klaim,
				SUM(settled_klaim) AS total_settled_klaim,
				SUM(klaim_finish) AS total_finish_klaim,
				SUM(klaim_tolak) AS total_tolak_klaim,
				SUM(all_klaim_nilai) AS total_all_klaim,
				SUM(adm_klaim_peserta) AS total_adm_peserta,
				SUM(proses_klaim_peserta) AS total_proses_peserta,
				SUM(klaim_peserta_settled) AS total_settled_peserta,
				SUM(klaim_peserta_finish) AS total_finish_peserta,
				SUM(klaim_peserta_tolak) AS total_tolak_peserta,
				SUM(all_klaim_peserta) AS total_all_peserta
				FROM(
				$querySelect
				)AS total_klaim
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
					while ($row=mysql_fetch_assoc($result))
					{
						// $nmproduk = $row['nmproduk'];
						if($row['all_klaim_nilai'] != NULL){
							$nmproduk = $row['nmproduk'];
							$data['data'][] = array(
							'nama produk'	=> $row['nmproduk'],
							'regional'=> is_null($row['cabang']) ? 'Lainnya' : $row['cabang'],
							'administrasi nilai klaim'	=> is_null($row['adm_nilai_klaim']) ? '0' : $row['adm_nilai_klaim'],
							'proses nilai klaim'	=> is_null($row['proses_klaim']) ? '0' : $row['proses_klaim'],
							'settled nilai klaim'	=> is_null($row['settled_klaim']) ? '0' : $row['settled_klaim'],
							'klaim finish'	=> is_null($row['klaim_finish']) ? '0' : $row['klaim_finish'],
							'klaim tolak'	=> is_null($row['klaim_tolak']) ? '0' : $row['klaim_tolak'],
							'administrasi klaim peserta'=> is_null($row['adm_klaim_peserta']) ? '0' : $row['adm_klaim_peserta'],
							'proses klaim peserta'=> is_null($row['proses_klaim_peserta']) ? '0' : $row['proses_klaim_peserta'],
							'settled klaim peserta'=> is_null($row['klaim_peserta_settled']) ? '0' : $row['klaim_peserta_settled'],
							'klaim peserta finish'	=> is_null($row['klaim_peserta_finish']) ? '0' : $row['klaim_peserta_finish'],
							'klaim peserta tolak'	=> is_null($row['klaim_peserta_tolak']) ? '0' : $row['klaim_peserta_tolak'],
							'all klaim nilai'=> is_null($row['all_klaim_nilai']) ? '0' : $row['all_klaim_nilai'],
							'all klaim peserta'	=> is_null($row['all_klaim_peserta']) ? '0' : $row['all_klaim_peserta']
							);
						}else{

						}
					}
					while ($row1=mysql_fetch_assoc($result1)) {
						if($row1['total_all_klaim'] != NULL){
							$data['total'][] =
									array(
									'nama produk'	=> $row1['nmproduk'],
									'total administrasi klaim'	=> is_null($row1['total_adm_klaim']) ? '0' : $row1['total_adm_klaim'],
									'total proses klaim'	=> is_null($row1['total_process_klaim']) ? '0' : $row1['total_process_klaim'],
									'total settled klaim'	=> is_null($row1['total_settled_klaim']) ? '0' : $row1['total_settled_klaim'],
									'total klaim finish'	=> is_null($row1['total_finish_klaim']) ? '0' : $row1['total_finish_klaim'],
									'total klaim tolak'	=> is_null($row1['total_tolak_klaim']) ? '0' : $row1['total_tolak_klaim'],
									'total administrasi peserta'=> is_null($row1['total_adm_peserta']) ? '0' : $row1['total_adm_peserta'],
									'total proses peserta'=> is_null($row1['total_proses_peserta']) ? '0' : $row1['total_proses_peserta'],
									'total settled peserta'=> is_null($row1['total_settled_peserta']) ? '0' : $row1['total_settled_peserta'],
									'total peserta finish'	=> is_null($row1['total_finish_peserta']) ? '0' : $row1['total_finish_peserta'],
									'total peserta tolak'	=> is_null($row1['total_tolak_peserta']) ? '0' : $row1['total_tolak_peserta'],
									'total all klaim'	=> is_null($row1['total_all_klaim']) ? '0' : $row1['total_all_klaim'],
									'total all peserta'	=> is_null($row1['total_all_peserta']) ? '0' : $row1['total_all_peserta']
											);
						}else{

						}
					}
					echo json_encode($data);
				}


?>