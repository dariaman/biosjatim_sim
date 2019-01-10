<?php 
	include "../param.php";

	if (!$_REQUEST['approve']) {
		echo 'Tidak ada data yang dipilih';
  }else{
		foreach($_POST['approve'] as $r => $id) {
			$query = "SELECT ajkspk.*,
											 ajkpolis.general,
											 ajkpolis.typemedical,
											 ajkcabang.name as nmcabang,
											 ajkarea.er as nmarea,
											 ajkregional.er as nmreg
								FROM ajkspk
								INNER JOIN ajkcabang
								on ajkcabang.er = ajkspk.cabang
								INNER JOIN ajkarea
								on ajkarea.er = ajkcabang.idarea
								INNER JOIN ajkregional
								on ajkregional.er = ajkcabang.idreg								
								INNER JOIN ajkpolis
								ON ajkpolis.id = ajkspk.idproduk
								WHERE ajkspk.id = ".$id;
			
			$qspk = mysql_fetch_array(mysql_query($query));

			if($qspk['general']=="T"){
				if($qspk['typemedical']=="SPK"){
					$typedata = "SPK";
				}else{
					$typedata = "SPAJ";
				}
			}else{
				$typedata = "GENERAL";
			}

			$rate = $qspk['ratebank'];
			$idbro = $qspk['idbroker'];
			$idclient = $qspk['idpartner'];
			$idpolis = $qspk['idproduk'];
			$newfilename = $idpolis.$qspk['nmcabang'].date("Ymdhis");
			$gender = $qspk['jeniskelamin'];
			$ktp = $qspk['nomorktp'];
			$nomorformulir = $qspk['nomorspk'];
			$nama = $qspk['nama'];
			$tgllahir = $qspk['dob'];
			$usia = $qspk['usia'];
			$plafon = $qspk['plafond'];
			$tglakad = $qspk['tglakad'];
			$tenor = $qspk['tenor'];
			$tglakhir = $qspk['tglakhir'];
			$idcabang = $qspk['cabang'];
			$idarea = $qspk['nmarea'];
			$idregional = $qspk['nmreg'];
			$premi = $qspk['premi'];
			$extpremi = $qspk['premiem'];
			$totalpremi = $qspk['nettpremi'];

			$qinsert = "INSERT INTO ajkpeserta_temp 
									SET idbroker='".$idbro."',
										  idclient='".$idclient."',
										  idpolicy='".$idpolis."',
										  filename='".$newfilename."',
										  gender='".$gender."',
										  typedata='".$typedata."',
										  nomorktp='".$ktp."',
										  nomorspk='".$nomorformulir."',
										  nama='".strtoupper(trim($nama))."',
										  tgllahir='".$tgllahir."',
										  usia='".$usia."',
										  plafond='".$plafon."',
										  tglakad='".$tglakad."',
										  tenor='".$tenor."',
										  tglakhir='".$tglakhir."',
										  statusaktif='Pending',
										  cabang='".$idcabang."',
										  area='".$idarea."',
										  regional='".$idregional."',
										  premirate='".$rate."',
										  premi='".$premi."',
										  diskonpremi='".$discountpremi."',
										  biayaadmin='".$adminfee."',
										  extrapremi='".$extpremi."',
										  totalpremi='".$totalpremi."',
										  medical='".$typemedical."',
										  input_by='".$iduser."',
										  input_time='".$mamettoday."'";

			$result = mysql_query($qinsert);
			if (!$result) {
    		$msg  = 'Invalid query: ' . mysql_error();
			}else{
				$msg  = 'success';
			}
		}
		echo $msg;
  }
?>
