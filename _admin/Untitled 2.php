<?php

if ($data2=="") {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = strtoupper($data2);	}
if ($datagender=="") {	$ErrorEXLGender = '<span class="label label-danger">Error</span>';	$dataEXLGender = $ErrorEXLGender;	}else{	$dataEXLGender = strtoupper($datagender);	}
if ($data3=="") {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{
	$cekCabang = mysql_fetch_array($database->doQuery('SELECT ajkregional.er AS idreg, ajkregional.`name` AS regional, ajkcabang.er AS idcab, ajkcabang.`name` AS cabang
	FROM ajkcabang
	INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
	WHERE ajkcabang.idclient = "'.$met['clientid'].'" AND
		  ajkcabang.`name` = "'.strtoupper($data3).'" AND
	      ajkcabang.del IS NULL'));
	if (!$cekCabang['idcab']) {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = strtoupper($data3);	}
}

if ($data4=="") {	$ErrorEXL4 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = $data4;	}
if ($data5=="") {	$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;	}else{	$dataEXL5 = $data5;	}

if ($data6 <= 9) { $data6_ = '0'.$data6;	}else{	$data6_ = $data6;}
if ($data7 <= 9) { $data7_ = '0'.$data7;	}else{	$data7_ = $data7;}
$dataTGLLAHIR = $data8.'-'.$data7_.'-'.$data6_;
if ($data6=="" OR $data7=="" OR $data8=="") {	$ErrorEXL6 = '<span class="label label-danger">'.$data8.'-'.$data7.'-'.$data6.'</span>';	$dataEXL6 = $ErrorEXL6;	}
elseif (!isValidDate($dataTGLLAHIR)) 		{	$ErrorEXL6 = '<span class="label label-danger">'.$dataTGLLAHIR.'</span>';					$dataEXL6 = $ErrorEXL6;	}
else{	$dataEXL6 = _convertDate($dataTGLLAHIR);	}

if ($data9 <= 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
if ($data10 <= 9) { $data10_ = '0'.$data10;	}else{	$data10_ = $data10;}
$dataTGLAKAD = $data11.'-'.$data10_.'-'.$data9_;
if ($data9=="" OR $data10=="" OR $data11=="")	{	$ErrorEXL7 = '<span class="label label-danger">'.$data9.'-'.$data10.'-'.$data11.'</span>';	$dataEXL7 = $ErrorEXL7;	}
elseif (!isValidDate($dataTGLAKAD))			 	{	$ErrorEXL7 = '<span class="label label-danger">'.$dataTGLAKAD.'</span>';					$dataEXL7 = $ErrorEXL7;	}
else{	$dataEXL7 = _convertDate($dataTGLAKAD);	}

if ($data12=="") {	$ErrorEXL8 = '<span class="label label-danger">Error</span>';	$dataEXL8 = $ErrorEXL8;	}else{	$dataEXL8 = $data12;	}

if ($data13=="") {	$ErrorEXL9 = '<span class="label label-danger">Insert Plafond</span>';	$dataEXL9 = $ErrorEXL9;	}
elseif (str_replace($_separatorsNumb,$_separatorsNumb_, $data13) < 0 OR str_replace($_separatorsNumb,$_separatorsNumb_, $data13) > $met['plafondend'] ) {	$ErrorEXL9 = '<span class="label label-danger">Error Plafond</span>';	$dataEXL9 = $ErrorEXL9;	}
else{	$dataEXL9 = '<span class="label label-success">'.duit(str_replace($_separatorsNumb,$_separatorsNumb_, $data13)).'</span>';	}
//VALIDASI


//CEK DATA DOUBLE
//TEMPRORY
$metDoubleKTP = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta_temp WHERE nomorktp="'.$data4.'"'));	//CEK KTP
if ($metDoubleKTP['id']) {	$ErrorKTP ='<span class="label label-danger">KTP DOUBLE</span>';	$dataEXL4KTP = $ErrorKTP;	}	else{	$dataEXL4KTP = '';	}

$metDoubleDEB = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir
												   FROM ajkpeserta_temp
												   WHERE idbroker="'.$_REQUEST['coBroker'].'" AND
												   		 idclient="'.$_REQUEST['coClient'].'" AND
												   		 idpolicy="'.$_REQUEST['coPolicy'].'" AND
												   		 nama="'.strtoupper($data2).'" AND
												   		 tgllahir="'.$dataTGLLAHIR.'"'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
if ($metDoubleDEB['id']) {	$ErrorDEB ='<span class="label label-danger">Nama DOUBLE</span>';	$dataEXL2DEBITUR = $ErrorDEB;	}	else{	$dataEXL2DEBITUR = '';	}

//TABLE
if (!$metDoubleKTP['id']) {
	$metDoubleKTPtbl = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta WHERE nomorktp="'.$data4.'" AND del IS NULL'));	//CEK KTP
	if ($metDoubleKTPtbl['id']) {	$ErrorKTPtbl ='<span class="label label-danger">KTP DOUBLE</span>';	$dataEXL4KTPtbl = $ErrorKTPtbl;	}	else{	$dataEXL4KTPtbl = '';	}
}

if (!$metDoubleDEB['id']) {
	$metDoubleDEBtbl = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir
												   FROM ajkpeserta
												   WHERE idbroker="'.$_REQUEST['coBroker'].'" AND
												   		 idclient="'.$_REQUEST['coClient'].'" AND
												   		 idpolicy="'.$_REQUEST['coPolicy'].'" AND
												   		 nama="'.strtoupper($data2).'" AND
												   		 tgllahir="'.$dataTGLLAHIR.'" AND
												   		 del IS NULL'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
	if ($metDoubleDEBtbl['id']) {	$ErrorDEBtbl ='<span class="label label-danger">Nama DOUBLE</span>';	$dataEXL2DEBITURtbl = $ErrorDEBtbl;	}	else{	$dataEXL2DEBITURtbl = '';	}
}
//CEK DATA DOUBLE

//CEK USIA
$met_Date = datediff($dataTGLAKAD, $dataTGLLAHIR);
$met_Date_ = explode(",", $met_Date);
if ($met_Date_[1] >= 6) {	$metUsia = $met_Date_[0] + 1;	} else {	$metUsia = $met_Date_[0];	}
if ($metUsia < $met['agestart']) {	$ErrorUsia = '<span class="label label-danger">Error</span>';	$metUsia_ = $ErrorUsia;	}
elseif ($metUsia > $met['ageend']) {	$ErrorUsia = '<span class="label label-danger">Error</span>';	$metUsia_ = $ErrorUsia;	}
else{	$metUsia_ = '<span class="number"><span class="label label-primary">'.$metUsia.'</span></span>';	}
//CEK USIA

//CEK TANGGAL AKHIR
$tglAkhir = date('Y-m-d',strtotime($dataTGLAKAD."+".$data12." Month"."-".$met['lastdayinsurance']." day"));	//KREDIT AKHIR
$tglAkhir_ = _convertDate($tglAkhir);
//CEK TANGGAL AKHIR

//CEK VALIDASI RATE PREMI
if ($met['byrate']=="Age") {
	$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$data12.' BETWEEN tenorfrom AND tenorto'));
}else{
	$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$data12.' BETWEEN tenorfrom AND tenorto'));
}
if (!$metRate['rate'] OR $metRate['rate']=="0.0000") {	$ErrorRateND = '<span class="label label-danger">Error</span>';	$metRate_ = $ErrorRateND;	}
else{	$metRate_ = '<span class="number"><span class="label label-inverse">'.$metRate['rate'].'</span></span>';
	$metPlafond = str_replace($_separatorsNumb,$_separatorsNumb_, $data13);
	$dataEXLPremium = ($metPlafond * $metRate['rate']) / $met['calculatedrate'];				//REAL PREMIRATE
	$metPremiDiskon = $dataEXLPremium * ($met['diskon'] / 100);									//REAL PREMIRATE DISKON
	$metPremi = $dataEXLPremium - $metPremiDiskon + $met['adminfee'];							//TOTAL PREMI

	//DATA MEDICAL
	if ($ErrorEXL2 OR $ErrorEXL3 OR $ErrorEXL4 OR $ErrorEXL5 OR $ErrorEXL6 OR $ErrorEXL7 OR $ErrorEXL8 OR $ErrorEXL9 OR $ErrorKTP OR $ErrorDEB OR $ErrorKTPtbl OR $ErrorDEBtbl OR $ErrorRateND){
	}else{
		if ($met['freecover']=="T") {
			$metMedical = mysql_fetch_array($database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$met['brokerid'].'" AND idpartner="'.$met['clientid'].'" AND idproduk="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$metPlafond.' BETWEEN upfrom AND upto AND del IS NULL'));
			if ($metMedical['type'] == "FCL" OR $metMedical['type'] == "NM") {
				$dataMedical = '<span class="label label-primary">'.$metMedical['type'].'</span>';
			}elseif ($metMedical['type'] == "SKKT") {
				$dataMedical = '<span class="label label-warning">'.$metMedical['type'].'</span>';
			}else{
				$dataMedical = '<span class="label label-danger">'.$metMedical['type'].'</span>';
			}
		}else{
			$dataMedical = '<span class="label label-primary">FCL</span>';
		}
	}
	//DATA MEDICAL
}
//CEK VALIDASI RATE PREMI

?>


<?php

//CEK MEDICAL
if ($met['freecover']=="T") {
	$metMedical = mysql_fetch_array($database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$met['brokerid'].'" AND idpartner="'.$met['clientid'].'" AND idproduk="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14).' BETWEEN upfrom AND upto AND del IS NULL'));
	if ($metMedical['type'] == "FCL" OR $metMedical['type'] == "NM") {
		$dataMedical = '<span class="label label-primary">'.$metMedical['type'].'</span>';
	}elseif ($metMedical['type'] == "SKKT") {
		$dataMedical = '<span class="label label-warning">'.$metMedical['type'].'</span>';
	}else{
		$dataMedical = '<span class="label label-danger">'.$metMedical['type'].'</span>';
	}
}else{
	$dataMedical = '<span class="label label-primary">FCL</span>';
}
//CEK MEDICAL
/*VALIDASI*/

//CEK DATA DOUBLE
//TBL TEMPRORY
$metDoubleKTP = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta_temp WHERE nomorktp="'.$datakolom4.'" AND del IS NULL'));	//CEK KTP
if ($metDoubleKTP['id']) {
	$dataEXL4KTP = '<span class="label label-danger" title="KTP number was uploaded">Error</span>';
}else{
	//TBL PESERTA
	$metDoubleKTPPESERTA = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta WHERE nomorktp="'.$datakolom4.'" AND del IS NULL'));	//CEK KTP
	if ($metDoubleKTPPESERTA['id']) {
		$dataEXL4KTP = '<span class="label label-danger" title="Double KTP number">Error</span>';
	}else{
		$dataEXL4KTP = '';
	}
}

//TBL TEMPRORY
$metProduk_ = explode("_", $_REQUEST['coPolicy']);
$metDoubleDEB = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir FROM ajkpeserta_temp WHERE idbroker="'.$_REQUEST['coBroker'].'" AND idclient="'.$_REQUEST['coClient'].'" AND idpolicy="'.$metProduk_[0].'" AND nama="'.strtoupper($datakolom2).'" AND tgllahir="'.$dataTGLLAHIR.'"'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
if ($metDoubleDEB['id']) {
	$dataEXL4PESERTA = '<span class="label label-danger" title="Debitur was uploaded">Error</span>';
}else{
	//TBL PESERTA
	$metDoubleDEBITUR = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir FROM ajkpeserta WHERE idbroker="'.$_REQUEST['coBroker'].'" AND idclient="'.$_REQUEST['coClient'].'" AND idpolicy="'.$metProduk_[0].'" AND nama="'.strtoupper($datakolom2).'" AND tgllahir="'.$dataTGLLAHIR.'"'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
	echo $metDoubleDEBITUR['id'].'<br />';
	if ($metDoubleDEBITUR['id']) {
		$dataEXL4PESERTA = '<span class="label label-danger" title="Double Debitur">Error '.$metDoubleDEBITUR['nama'].'</span>';
	}else{
		$dataEXL4PESERTA = '';
	}
}
//CEK DATA DOUBLE

?>
