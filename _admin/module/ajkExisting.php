<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
require_once('../includes/metPHPXLS/Worksheet.php');
require_once('../includes/metPHPXLS/Workbook.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['exs']) {
	case "val":
		;
		break;
	case "inv":
		;
		break;

case "UploadXls":
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
	</div></div>';
echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
/*
echo $_REQUEST['coBroker'].'<br />';
echo $_REQUEST['coClient'].'<br />';
echo $_REQUEST['coPolicy'].'<br />';
echo $_FILES['fileUpload']['name'].'<br />';
*/
$met = mysql_fetch_array($database->doQuery('SELECT ajkexcelupload.idb, ajkexcelupload.idc, ajkexcelupload.idp,  COUNT(ajkexcelupload.idxls) AS jumField, ajkcobroker.`name` AS brokername, ajkcobroker.logo AS brokerlogo, ajkclient.`name` AS clientname, ajkclient.logo AS clientlogo, ajkpolis.policyauto, ajkpolis.policymanual
											 FROM ajkexcelupload
											 INNER JOIN ajkcobroker ON ajkexcelupload.idb = ajkcobroker.id
											 INNER JOIN ajkclient ON ajkexcelupload.idc = ajkclient.id
											 INNER JOIN ajkpolis ON ajkexcelupload.idp = ajkpolis.id
											 WHERE ajkexcelupload.idp = "'.$_REQUEST['coPolicy'].'"
											 GROUP BY ajkexcelupload.idp'));
//echo $met['jumField'];
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$met['brokername'].'</dd>
				<dt>Company</dt><dd>'.$met['clientname'].'</dd>
				<dt>Policy</dt><dd>'.$met['policyauto'].'</dd>
				<dt>Filename</dt><dd>'.$_FILES['fileUpload']['name'].'</dd>
			</dl>
			</div>
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="65px" height="65px"></div>
		</div>';
$metDLExl = $database->doQuery('SELECT ajkexcel.fieldname, ajkexcelupload.valempty, ajkexcelupload.valdate, ajkexcelupload.valsamedata
								 FROM ajkexcelupload
								 INNER JOIN ajkexcel ON ajkexcelupload.idxls = ajkexcel.id
								 WHERE ajkexcelupload.idb = "'.$_REQUEST['coBroker'].'" AND
								 	   ajkexcelupload.idc = "'.$_REQUEST['coClient'].'" AND
								 	   ajkexcelupload.idp = "'.$_REQUEST['coPolicy'].'"
								 ORDER BY ajkexcelupload.id ASC');
while ($metDLExl_ = mysql_fetch_array($metDLExl)) {
if ($metDLExl_['valempty']=="Y" OR $metDLExl_['valdate']=="Y" OR $metDLExl_['valsamedata']=="Y") {
	$metKolomVal .= '<th>'.$metDLExl_['fieldname'].'*</th>';
}else{
	$metKolomVal .= '<th>'.$metDLExl_['fieldname'].'</th>';
}
	$metKolomValEmpty .= $metDLExl_['fieldname'].'_'.$metDLExl_['valempty'].',';
}
echo '<table class="table table-hover table-bordered">
	<thead>
		<tr><th width="1%">No</td>
			'.$metKolomVal.'
		</tr>
	</thead>
	<tbody>';
$data = new Spreadsheet_Excel_Reader($_FILES['fileUpload']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

for ($i=6; $i<=$hasildata; $i++)
{
//	$data1 = $data->val($i,1);
	$data2 = $data->val($i,2);
	$data3 = $data->val($i,3);
	$data4 = $data->val($i,4);
	$data5 = $data->val($i,5);
	$data6 = $data->val($i,6);

/*CEK VAL EMPTY*/
//echo '<tr><td colspan="8">'.$metKolomValEmpty.'</td></tr>';
$metDLExl = mysql_fetch_array($database->doQuery('SELECT ajkexcelupload.id, ajkexcelupload.valempty, ajkexcelupload.valdate, ajkexcelupload.valsamedata
								 FROM ajkexcelupload
								 WHERE ajkexcelupload.idp = "'.$_REQUEST['coPolicy'].'"
								 ORDER BY ajkexcelupload.id ASC'));
echo $metDLExl['valempty'].'<br />';
if ($metDLExl['valempty']=="Y") {
	if ($data2=="") {	$error = "error";	$data2EXL = $error;	}else{	$data2EXL = $data2;	}
}

/*CEK VAL EMPTY*/
echo '<tr>
		<td>'.++$no.'</td>
		<td>'.$data2EXL.'</td>
		<td align="center">'.$data3.'</td>
		<td align="center">'.$data4.'</td>
		<td align="center">'.$data5.'</td>
		<td align="center">'.$data6.'</td>
	</tr>';
}

echo '</tbody>
	</table>
			</div>
		</div>
	</div>
</div>';
	;
	break;

case "UploadXls2":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
	</div></div>';
echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
		/*
		   echo $_REQUEST['coBroker'].'<br />';
		   echo $_REQUEST['coClient'].'<br />';
		   echo $_REQUEST['coPolicy'].'<br />';
		   echo $_FILES['fileUpload']['name'].'<br />';
		*/
if ($q['idbroker'] == NULL) {
	$metIDBroker = 'ajkcobroker.id = "'.$_REQUEST['coBroker'].'" AND';
}else{
	$metIDBroker = 'ajkcobroker.id = "'.$q['idbroker'].'" AND';
}
$met = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id AS brokerid,
													ajkcobroker.`name` AS brokername,
													ajkcobroker.logo AS brokerlogo,
													ajkclient.id AS clientid,
													ajkclient.`name` AS clientname,
													ajkclient.logo AS clientlogo,
													ajkpolis.id AS polisid,
													ajkpolis.policyauto,
													ajkpolis.produk,
													ajkpolis.general,
													ajkpolis.byrate,
													ajkpolis.freecover,
													ajkpolis.diskon,
													ajkpolis.adminfee,
													ajkpolis.calculatedrate,
													ajkpolis.agestart,
													ajkpolis.ageend,
													ajkpolis.plafondstart,
													ajkpolis.plafondend,
													ajkpolis.lastdayinsurance
												FROM ajkcobroker
												INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
												INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
												WHERE '.$metIDBroker.'
													  ajkclient.id = "'.$_REQUEST['coClient'].'" AND
													  ajkpolis.id = "'.$_REQUEST['coPolicy'].'"'));

//CEK TIPE PRODUK GENERAL
if ($met['general']=="Y") {
	$_metGeneral = '<th class="text-center" width="1%">Nilai Jaminan</td>
					<th class="text-center" width="1%">Paket</td>
					<th class="text-center" width="10%">Okupasi</td>
					<th class="text-center" width="1%">Kelas</td>
					<th class="text-center" width="1%">Lokasi Objek</td>';
	$_metGeneralPremi = '<th class="text-center" width="1%">Rate PA</td>
						<th class="text-center" width="1%">Premi PA</td>
						<th class="text-center" width="1%">Rate Fire</td>
						<th class="text-center" width="10%">Premi Fire</td>';
}else{
	$_metGeneral = '';
	$_metGeneralPremi = '';
}
//CEK TIPE PRODUK GENERAL

//echo $met['jumField'];
echo '<div class="panel-body">
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$met['brokername'].'</dd>
					<dt>Partner</dt><dd>'.$met['clientname'].'</dd>
					<dt>Product</dt><dd>'.$met['produk'].'</dd>
					<dt>Filename</dt><dd>'.$_FILES['fileUploadExcel']['name'].'</dd>
				</dl>
				</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>';
	if ($_FILES['fileUploadExcel']['size'] / 1024 > $FILESIZE_2)	{
		echo '<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Error!</strong> File PKS tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div>';
	}
	else{
	//$fNameUpload = $DatePolis.'_B'.$_REQUEST['coBroker'].'_C'.$_REQUEST['coClient'].'_P'.$_REQUEST['coPolicy'].'_USER'.$q['id'].'_'.$_FILES['fileUploadExcel']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
	$fNameUpload =  str_replace(" ", "_", "DKL_".date("YmdHis")."_P".$met['clientid'].'_'.$_FILES['fileUploadExcel']['name']);
	$metFileNameExistingCek		= "../myFiles/_uploaddata/".$foldername."".$fNameUpload."";
//	$metFileNameExistingCek = '../'.$PathUploadExcel.''.$fNameUpload;
	if (file_exists($metFileNameExistingCek)){		//CEK KEBERADAAN FILE YANG SAMA PADA SAAT UPLOAD
		echo '</div><div class="col-xs-12 col-sm-12 col-md-12">
			<div class="alert alert-dismissable alert-warning">
			<p class="mb10"><strong>Error !</strong> Filename already upload.</p>
			<a href="ajk.php?re=exsist&exs=Xls2">'.BTN_NEWUPLOAD.'</a>
			</div>
		  </div>';
	}else{
		$fNameUploadTemp = $_FILES['fileUploadExcel']['tmp_name'];
		$namafile =  $_FILES['fileUploadExcel']['tmp_name'];
		//echo $namafile;
		$ext = pathinfo($namafile, PATHINFO_EXTENSION);
		$file_info = pathinfo($namafile);
		$file_extension = $file_info["extension"];
		$namefile = $file_info["fileUploadExcel"].'.'.$file_extension;
		$inputFileName = $namafile;
		$_SESSION['file_temp'] = $namefile;
		$_SESSION['fileUploadExcel'] = $_FILES['fileUploadExcel']['name'];
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());
		}
echo '<div class="panel-body">
		<div class="table-responsive panel-collapse pull out">
		<table class="table table-hover table-bordered table-responsive">';
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
echo '<thead>
		<input type="hidden" name="fNameData" value="'.$fNameUpload.'">
		<tr><th width="1%">No</td>
			<th class="text-center" width="1%">Branch</td>
			<th class="text-center">Name</td>
			<th class="text-center" width="1%">Gender</td>
			<th class="text-center" width="1%">No.KTP</td>
			<th class="text-center" width="1%">No.PK</td>
			<th class="text-center" width="10%">DOB</td>
			<th class="text-center" width="1%">Age</td>
			<th class="text-center" width="10%">Satrt Date</td>
			<th class="text-center" width="1%">Tenor</td>
			<th class="text-center" width="1%">End Date</td>
			<th class="text-center" width="1%">Grace Period</td>
			<th class="text-center" width="10%">Plafond</td>
			<th class="text-center" width="1%">EM(%)</td>
			'.$_metGeneral.'
			<th class="text-center" width="1%">Rate ND</td>
			<th class="text-center" width="1%">Premium ND</td>
			<!--<th class="text-center" width="1%">Discount</td>
			<th class="text-center" width="1%">Adminfee</td>-->
			<th class="text-center" width="1%">Total Premium ND</td>
			'.$_metGeneralPremi.'
			<th class="text-center" width="1%">Medical</td>
			<th class="text-center" width="1%">Note</td>
		</tr>
		</thead>
		<tbody>';
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	for ($row = 7; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		echo "<tr>";
		$i = 0;
		foreach($rowData[0] as $k=>$v){
			$data[$i] = $v;
			$i++;
		}
		$today = date('Y-m-d');
		$datakolom1		= $data[1];		//CABANG
		$datakolom2		= $data[2];		//TERTANGGUNG
		$datakolom3		= $data[3];		//JENIS KELAMIN
		$datakolom4		= $data[4];		//KTP
		$datakolom5		= $data[5];		//PK
		$datakolom6		= $data[6];		//DOB (DD)
		$datakolom7		= $data[7];		//DOB (MM)
		$datakolom8		= $data[8];		//DOB (YYYY)
		$datakolom9		= $data[9];		//TGL AKAD (DD)
		$datakolom10	= $data[10];	//TGL AKAD (MM)
		$datakolom11	= $data[11];	//TGL AKAD (YYYY)
		$datakolom12	= $data[12];	//TENOR
		$datakolom13	= $data[13];	//GRACE PRERIOD
		$datakolom14	= $data[14];	//PLAFOND
		$datakolom15	= $data[15];	//EM manual
		$datakolom16	= $data[16];	//KETERANGAN

		//CEK TIPE PRODUK GENERAL
		if ($met['general']=="Y") {
			$data14 = $data[17];	//PAKET
			$data15 = $data[18];	//QUARANTEE
			$data16 = $data[19];	//KELAS
			$data17 = $data[20];	//LOKASI
			$data18 = $data[21];	//HARGA PASAR

			if ($data14=="") {	$ErrorEXL14 = '<span class="label label-danger">Error</span>';	$dataEXL14 = $ErrorEXL14;	}else{
				$cekGenPaket = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Paket Asuransi" AND kode="'.$data14.'"'));
				if ($cekGenPaket['id']) {
					$dataEXL14 = $cekGenPaket['keterangan'];
				}else{
					$ErrorEXL14 = '<span class="label label-danger">Error</span>';	$dataEXL14 = $ErrorEXL14;
				}
			}

			if ($data15=="") {	$ErrorEXL15 = '<span class="label label-danger">Error</span>';	$dataEXL15 = $ErrorEXL15;	}else{
				$cekGenPertanggungan = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Okupasi" AND kode="'.$data15.'"'));
				if ($cekGenPertanggungan['id']) {
					$dataEXL15 = $cekGenPertanggungan['keterangan'];
				}else{
					$ErrorEXL15 = '<span class="label label-danger">Error</span>';	$dataEXL15 = $ErrorEXL15;
				}
			}

			if ($data16=="") {	$ErrorEXL16 = '<span class="label label-danger">Error</span>';	$dataEXL16 = $ErrorEXL16;	}else{
				$cekGenKelas = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Kelas" AND kode="'.$data16.'"'));
				if ($cekGenKelas['id']) {
					$dataEXL16 = $cekGenKelas['keterangan'].'';
				}else{
					$ErrorEXL16 = '<span class="label label-danger">Error</span>';	$dataEXL16 = $ErrorEXL16;
				}
			}

			if ($data17=="") {	$ErrorEXL17 = '<span class="label label-danger">Error</span>';	$dataEXL17 = $ErrorEXL17;	}else{
				$cekGenLokasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Lokasi" AND kode="'.$data17.'"'));
				if ($cekGenLokasi['id']) {
					$dataEXL17 = $cekGenLokasi['keterangan'];
				}else{
					$ErrorEXL17 = '<span class="label label-danger">Error</span>';	$dataEXL17 = $ErrorEXL17;
				}
			}

			if ($data18=="") {	$ErrorEXL18 = '<span class="label label-danger">Error Plafond</span>';	$dataEXL18 = $ErrorEXL18;	}
			else{	$dataEXL18 = '<span class="label label-primary">'.duit(str_replace($_separatorsNumb,$_separatorsNumb_, $data18)).'</span>';	}

			$_rowMetGeneral = '
				<td align="center">'.$dataEXL18.'</td>
				<td align="center">'.$dataEXL14.'</td>
				<td align="center">'.$dataEXL15.'</td>
				<td align="center">'.$dataEXL16.'</td>
				<td align="center">'.$dataEXL17.'</td>';
			$metRateGeneral = mysql_fetch_array($database->doQuery('SELECT * FROM ajkrategeneral WHERE idbroker="'.$q['idbroker'].'" AND
																									   idclient="'.$_REQUEST['coClient'].'" AND
																									   idproduk="'.$_REQUEST['coPolicy'].'" AND
																									   '.$data12.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$data17.'" AND
																									   quarantee = "'.$data15.'" AND
																									   kelas = "'.$data16.'" AND
																									   status="Aktif"'));
//			echo '<br /><br />';
			$metPlafondGenFire = str_replace($_separatorsNumb,$_separatorsNumb_, $data18);
			$metPremiGenFire = ($metPlafondGenFire * $metRateGeneral['ratefire']) / $met['calculatedrate'];

			$metPlafondGenPA = str_replace($_separatorsNumb,$_separatorsNumb_, $data18);
			$metPremiGenPA = ($metPlafondGenPA * $metRateGeneral['ratepa']) / $met['calculatedrate'];

			$_rowmetGeneralPremi = '<td align="center"><span class="label label-inverse">'.$metRateGeneral['ratepa'].'</span></td>
									<td align="center"><span class="label label-primary">'.duit($metPremiGenPA).'</span></td>
									<td align="center"><span class="label label-inverse">'.$metRateGeneral['ratefire'].'</span></td>
									<td align="center"><span class="label label-primary">'.duit($metPremiGenFire).'</span></td>';
		}else{
			$_rowMetGeneral = '';
			$_rowmetGeneralPremi = '';
		}
		//CEK TIPE PRODUK GENERAL

/*VALIDASI*/
	//CABANG
	if ($datakolom1=="") {	$Erdatakolom1 = '<span class="label label-danger" title="branch is empty">Error</span>';	$dataKLM1 = $Erdatakolom1;	}else{
	$cekCabang = mysql_fetch_array($database->doQuery('SELECT ajkregional.er AS idreg, ajkregional.`name` AS regional, ajkcabang.er AS idcab, ajkcabang.`name` AS cabang
													   FROM ajkcabang
													   INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
													   WHERE ajkcabang.idclient = "'.$met['clientid'].'" AND
													   		 ajkcabang.`name` = "'.strtoupper($datakolom1).'" AND
													   		 ajkcabang.del IS NULL'));
	if (!$cekCabang['idcab']) {	$Erdatakolom1 = '<span class="label label-danger" title="branch not list">Error</span>';	$dataKLM1 = $Erdatakolom1;	}else{	$dataKLM1 = strtoupper($datakolom1);	}
	}
	//CABANG

	//NAMA
	if ($datakolom2=="") {	$Erdatakolom2 = '<span class="label label-danger" title="name is empty">Error</span>';	$dataKLM2 = $Erdatakolom2;	}else{	$dataKLM2 = strtoupper($datakolom2);	}
	//NAMA

	//GENDER
	if ($datakolom3=="") {	$Erdatakolom3 = '<span class="label label-danger" title="gender is empty">Error</span>';	$dataKLM3 = $Erdatakolom3;	}else{
		if ($datakolom3=="L" OR $datakolom3=="P") {
		$dataKLM3 = strtoupper($datakolom3);
		}else{
			$Erdatakolom3 = '<span class="label label-danger" title="gender not match">Error</span>';	$dataKLM3 = $Erdatakolom3;
		}
	}
	//GENDER

	//NOMORKTP
	if ($datakolom4=="") {	$Erdatakolom4 = '<span class="label label-danger" title="KTP number is empty">Error</span>';	$dataKLM4 = $Erdatakolom2;	}else{	$dataKLM4 = strtoupper($datakolom4);	}
	//NOMORKTP

	//NOMORPK
	if ($datakolom5=="") {	$Erdatakolom5 = '<span class="label label-danger" title="PK number is empty">Error</span>';	$dataKLM5 = $Erdatakolom5;	}else{	$dataKLM5 = strtoupper($datakolom5);	}
	//NOMORPK

	//DOB
	if ($datakolom6 <= 9) { $datakolom6_ = '0'.$datakolom6;	}else{	$datakolom6_ = $datakolom6;	}
	if ($datakolom7 <= 9) { $datakolom7_ = '0'.$datakolom7;	}else{	$datakolom7_ = $datakolom7;	}

	$dataTGLLAHIR = $datakolom8.'-'.$datakolom7_.'-'.$datakolom6_;
	if ($datakolom8=="" OR $datakolom7=="" OR $datakolom6=="") 		{	$Erdatakolom6 = '<span class="label label-danger" title="Date of birth error">'.$dataTGLLAHIR.'</span>';			$dataKLM6 = $Erdatakolom6;	}
	elseif (!isValidDate($dataTGLLAHIR)) 							{	$Erdatakolom6 = '<span class="label label-danger" title="Date of birth is not valid">'.$dataTGLLAHIR.'</span>';		$dataKLM6 = $Erdatakolom6;	}
	else{	$dataKLM6 = _convertDate($dataTGLLAHIR);	}
	//DOB

	//STARTDATE
	if ($datakolom9 <= 9)	{ $datakolom9_ = '0'.$datakolom9;	}else{	$datakolom9_ = $datakolom9;	}
	if ($datakolom10 <= 9)	{ $datakolom10_ = '0'.$datakolom10;	}else{	$datakolom10_ = $datakolom10;	}

	$dataTGLAKAD = $datakolom11.'-'.$datakolom10_.'-'.$datakolom9_;
	if ($datakolom11=="" OR $datakolom10=="" OR $datakolom9=="") 	{	$Erdatakolom7 = '<span class="label label-danger" title="Date error">'.$dataTGLAKAD.'</span>';						$dataKLM7 = $Erdatakolom7;	}
	elseif (!isValidDate($dataTGLAKAD)) 							{	$Erdatakolom7 = '<span class="label label-danger" title="Date deklarasi is not valid">'.$dataTGLAKAD.'</span>';		$dataKLM7 = $Erdatakolom7;	}
	else{	$dataKLM7 = _convertDate($dataTGLAKAD);	}
	//STARTDATE

	//AGE
		$met_Date = datediff($dataTGLAKAD, $dataTGLLAHIR);
		$met_Date_ = explode(",", $met_Date);
		if ($met_Date_[1] >= 6) {	$metUsia = $met_Date_[0] + 1;	} else {	$metUsia = $met_Date_[0];	}
		if ($metUsia < $met['agestart']) {	$ErrorUsia = '<span class="label label-danger">Error</span>';	$metUsia_ = $ErrorUsia;	}
		elseif ($metUsia > $met['ageend']) {	$ErrorUsia = '<span class="label label-danger">Error</span>';	$metUsia_ = $ErrorUsia;	}
		else{	$metUsia_ = '<span class="number"><span class="label label-primary">'.$metUsia.'</span></span>';	}
	//AGE

	//TENOR
	if ($datakolom12=="") {	$Erdatakolom8 = '<span class="label label-danger" title="Tenor is empty">Error</span>';	$dataKLM8 = $Erdatakolom8;	}else{	$dataKLM8 = $datakolom12;	}
	//TENOR

	//TGL AKHIR
	$tglAkhir = date('Y-m-d',strtotime($dataTGLAKAD."+".$datakolom12." Month"."-".$met['lastdayinsurance']." day"));	//KREDIT AKHIR
	$tglAkhir_ = _convertDate($tglAkhir);
	//TGL AKHIR

	//GRACE PRERIOD
	//if ($datakolom13=="") {	$Erdatakolom9 = '<span class="label label-danger" title="Tenor is empty">Error</span>';	$dataKLM9 = $Erdatakolom9;	}else{	$dataKLM9 = $datakolom13;	}
	$dataKLM9 = $datakolom13;
	//GRACE PRERIOD

	//PLAFOND
	if ($datakolom14=="") {	$Erdatakolom9 = '<span class="label label-danger">Insert Plafond</span>';	$dataKLM10 = $Erdatakolom9;	}
	elseif (str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14) < 0 OR str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14) > $met['plafondend'] ) {	$Erdatakolom9 = '<span class="label label-danger">Error Plafond</span>';	$dataKLM10 = $Erdatakolom9;	}
	else{	$dataKLM10 = '<span class="label label-success">'.duit(str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14)).'</span>';	}
	//PLAFOND

	//EM manual
	//if ($datakolom13=="") {	$Erdatakolom9 = '<span class="label label-danger" title="Tenor is empty">Error</span>';	$dataKLM9 = $Erdatakolom9;	}else{	$dataKLM9 = $datakolom13;	}
	$dataKLM11 = $datakolom15;
	//EM manual

	//KETERANGAN
	$dataKLM12 = $datakolom16;
	//KETERANGAN


	if ($Erdatakolom1 OR $Erdatakolom2 OR $Erdatakolom3 OR $Erdatakolom4 OR $Erdatakolom5 OR $Erdatakolom6 OR $Erdatakolom7 OR $Erdatakolom8 OR $Erdatakolom9) {
	/*	$metUsia_='';	$metPremi='';	$metRate_='';	$tglAkhir_='';	*/

	}else{
	//CEK RATE PREMI

/*
			$met_Date = datediff($dataTGLAKAD, $dataTGLLAHIR);
			$met_Date_ = explode(",", $met_Date);
			if ($met_Date_[1] >= 6) {	$metUsia = $met_Date_[0] + 1;	} else {	$metUsia = $met_Date_[0];	}
			$metUsia_ = '<span class="number"><span class="label label-primary">'.$metUsia.'</span></span>';

			if ($met['byrate']=="Age") {
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto'));
			}else{
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto'));
			}
			$metPlafond = str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14);
			$dataEXLPremium = ($metPlafond * $metRate['rate']) / $met['calculatedrate'];				//REAL PREMIRATE
			$metPremiDiskon = $dataEXLPremium * ($met['diskon'] / 100);									//REAL PREMIRATE DISKON
			$metPremi = $dataEXLPremium - $metPremiDiskon + $met['adminfee'];							//TOTAL PREMI

			$metRate_ ='<span class="number"><span class="label label-inverse">'.$metRate['rate'].'</span></span>';
*/
			//CEK RATE PREMI

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

	//cek datadouble
		/* table temp ktp*/
		$cekTempKTP = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta_temp WHERE nomorktp="'.$datakolom4.'"'));
		if ($cekTempKTP['id']) {
			$dataEXL4KTP = '<br /><span class="label label-danger" title="KTP number was uploaded">Error</span>';
		}else{
			$cekTblKTP = mysql_fetch_array($database->doQuery('SELECT id, nomorktp FROM ajkpeserta WHERE nomorktp="'.$datakolom4.'"'));
			if ($cekTblKTP['id']) {
				$dataEXL4KTP = '<br /><span class="label label-danger" title="Double data KTP">Error</span>';
			}else{
			$dataEXL4KTP = '';
			}
		}
		/* table temp ktp*/

		/* table temp debitur*/
		if (!$cekTempKTP['id'] && !$cekTblKTP['id']) {
			$metProduk_ = explode("_", $_REQUEST['coPolicy']);
			$metTempDoubleDEB = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir FROM ajkpeserta_temp WHERE idbroker="'.$_REQUEST['coBroker'].'" AND idclient="'.$_REQUEST['coClient'].'" AND idpolicy="'.$metProduk_[0].'" AND nama="'.strtoupper($datakolom2).'" AND tgllahir="'.$dataTGLLAHIR.'"'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
			if ($metTempDoubleDEB['id']) {
				$dataEXL4PESERTA = '<br /><span class="label label-danger" title="Debitur was uploaded">Error</span>';
			}else{
				$metTblDoubleDEB = mysql_fetch_array($database->doQuery('SELECT id, idbroker, idclient, idpolicy, nama, tgllahir FROM ajkpeserta WHERE idbroker="'.$_REQUEST['coBroker'].'" AND idclient="'.$_REQUEST['coClient'].'" AND idpolicy="'.$metProduk_[0].'" AND nama="'.strtoupper($datakolom2).'" AND tgllahir="'.$dataTGLLAHIR.'" AND del IS NULL'));	//CEK DEBITUR (IDBROKER, IDCLIENT, IDPOLIS, NAMA, TGLLAHIR
				if ($metTblDoubleDEB['id']) {
				$dataEXL4PESERTA = '<br /><span class="label label-danger" title="Double data debitur">Error</span>';
				}else{
				$dataEXL4PESERTA = '';
				}
			}
		}else{	}
		/* table temp debitur*/
	//cek datadouble

	//kalkulasi rate
		if (!$cekTempKTP['id'] && !$cekTblKTP['id'] && !$metTempDoubleDEB['id'] && !$metTblDoubleDEB['id']) {
			if ($met['byrate']=="Age") {
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
			}else{
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
			}
			$metRate_ = '<span class="number"><span class="label label-inverse">'.$metRate['rate'].'</span></span>';
			$metPlafond = str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14);
			$dataEXLPremium = ($metPlafond * $metRate['rate']) / $met['calculatedrate'];				//REAL PREMIRATE
			$metPremiDiskon = $dataEXLPremium * ($met['diskon'] / 100);									//REAL PREMIRATE DISKON
			$metPremi = $dataEXLPremium - $metPremiDiskon + $met['adminfee'];							//TOTAL PREMI

		}else{

		}
	//kalkulasi rate
	}


echo '<td>'.++$no.'</td>
		<td>'.$dataKLM1.'</td>
		<td align="center">'.$dataKLM2.''.$dataEXL4PESERTA.'</td>
		<td align="center">'.$dataKLM3.'</td>
		<td align="center">'.$dataKLM4.''.$dataEXL4KTP.'</td>
		<td align="center">'.$dataKLM5.'</td>
		<td align="center">'.$dataKLM6.'</td>
		<td align="center">'.$metUsia_.'</td>
		<td align="center">'.$dataKLM7.'</td>
		<td align="center">'.$dataKLM8.'</td>
		<td align="center">'.$tglAkhir_.'</td>
		<td align="center">'.$dataKLM9.'</td>
		<td align="right">'.$dataKLM10.'</td>
		<td align="center">'.$dataKLM11.'</td>
		'.$_rowMetGeneral.'
		<td align="center">'.$metRate_.'</td>
		<td align="center"><strong>'.duit($dataEXLPremium).'</strong></td>
		<!--<td align="center"><strong>'.duit($metPremiDiskon).'</strong></td>
		<td align="center"><strong>'.duit($met['adminfee']).'</strong></td>-->
		<td align="center"><span class="number"><span class="label label-success">'.duit($metPremi).'</span></span></td>
		'.$_rowmetGeneralPremi.'
		<td align="center">'.$dataMedical.'</td>
		<td align="center">'.$dataKLM12.'</td>
	</tr>';
		}
if ($Erdatakolom1 OR $Erdatakolom2 OR $Erdatakolom3 OR $Erdatakolom4 OR $Erdatakolom5 OR $Erdatakolom6 OR $Erdatakolom7 OR $Erdatakolom8 OR $Erdatakolom9) {
echo '<div align="center" class="col-md-12"><a href="ajk.php?re=exsist&exs=Xls2">'.BTN_UPLOADERROR.'</a></div>';
}else{
	$PathUploadExcel		= "../myFiles/_uploaddata/".$foldername."";
	if (!file_exists($PathUploadExcel)) 	{	mkdir($PathUploadExcel, 0777);	chmod($PathUploadExcel, 0777);	}
	$namafileupload =  str_replace(" ", "_", $foldername."DKL_".date("YmdHis")."_P".$met['clientid'].'_'.$_FILES['fileUploadExcel']['name']);
	$nama_fileupload =  str_replace(" ", "_", "DKL_".date("YmdHis")."_P".$met['clientid'].'_'.$_FILES['fileUploadExcel']['name']);
	$file_type = $_FILES['fileUploadExcel']['type']; //tipe file
	$source = $_FILES['fileUploadExcel']['tmp_name'];
	$direktori = "$PathUploadExcel$nama_fileupload"; // direktori tempat menyimpan file
	move_uploaded_file($source,$direktori);

	//$direktori = '../'.$PathUploadExcel.'/'.$fNameUpload;
	//move_uploaded_file($fNameUploadTemp,$direktori);
	echo '<div align="right" class="col-md-6"><a href="ajk.php?re=exsist&exs=BtlXls2&fname='.$fNameUpload.'">'.BTN_BACK2.'</a></div>
		<div class="col-md-6"><a href="ajk.php?re=exsist&exs=savemeFile&idb='.$thisEncrypter->encode($met['brokerid']).'&idc='.$thisEncrypter->encode($met['clientid']).'&idp='.$thisEncrypter->encode($met['polisid']).'&fname='.$fNameUpload.'">'.BTN_SUBMIT.'</a></div>';
}
echo '</tbody>
		</table>
	</div>
	</div>';
	}
	}
echo '		</div>
		</div>
	</div>';
	;
	break;

case "UploadXls2General":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
	</div></div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';

if ($q['idbroker'] == NULL) {
	$metIDBroker = 'ajkcobroker.id = "'.$_REQUEST['coBroker'].'" AND';
}else{
$metIDBroker = 'ajkcobroker.id = "'.$q['idbroker'].'" AND';
}
$fNameUpload = $_FILES['fileUploadExcel']['name'];
$fNameUploadTemp = $_FILES['fileUploadExcel']['tmp_name'];
echo $fNameUpload.'<br />';
echo $fNameUploadTemp.'<br />';
echo $metIDBroker.'<br />';

echo '</div>
	</div>
</div>';
	;
	break;


case "BtlXls2":
$PathUploadExcel		= "../myFiles/_uploaddata/".$foldername."";
unlink($PathUploadExcel.''.$_REQUEST['fname']);
header('location:ajk.php?re=exsist&exs=Xls2');
	;
	break;

case "savemeFile":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
	</div></div>';
echo '<div class="row">
		<div class="col-md-12">';
$met = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id AS brokerid,
													ajkcobroker.`name` AS brokername,
													ajkcobroker.logo AS brokerlogo,
													ajkclient.id AS clientid,
													ajkclient.`name` AS clientname,
													ajkclient.logo AS clientlogo,
													ajkpolis.id AS polisid,
													ajkpolis.policyauto,
													ajkpolis.produk,
													ajkpolis.general,
													ajkpolis.byrate,
													ajkpolis.freecover,
													ajkpolis.diskon,
													ajkpolis.adminfee,
													ajkpolis.calculatedrate,
													ajkpolis.agestart,
													ajkpolis.ageend,
													ajkpolis.plafondstart,
													ajkpolis.lastdayinsurance
												FROM ajkcobroker
												INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
												INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
												WHERE ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'" AND
													  ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'" AND
													  ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));

	$PathUploadExcel		= "../myFiles/_uploaddata/".$foldername."";
	$metFileNameExisting = $PathUploadExcel.''.$_REQUEST['fname'];
	$file_temp = $_SESSION['file_temp'];
	$file_name = $_SESSION['file_name'];
	//echo $metFileNameExisting.'<br />';
	//echo $file_temp.'<br />';
	//echo $file_name.'<br />';

		try {
			$inputFileType = PHPExcel_IOFactory::identify($metFileNameExisting);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($metFileNameExisting);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($metFileNameExisting, PATHINFO_BASENAME)
			. '": ' . $e->getMessage());
		}

	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	$newfilename = date('ymd_his').'_'.$file_name;

	for ($row = 7; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		echo "<tr>";
		$i = 0;
		foreach($rowData[0] as $k=>$v){
			$data[$i] = $v;
			$i++;
		}
		$today = date('Y-m-d');
		//$_data1 = $data[0];
		$datakolom1		= $data[1];		//CABANG
		$datakolom2		= $data[2];		//TERTANGGUNG
		$datakolom3		= $data[3];		//JENIS KELAMIN
		$datakolom4		= $data[4];		//KTP
		$datakolom5		= $data[5];		//PK
		$datakolom6		= $data[6];		//DOB (DD)
		$datakolom7		= $data[7];		//DOB (MM)
		$datakolom8		= $data[8];		//DOB (YYYY)
		$datakolom9		= $data[9];		//TGL AKAD (DD)
		$datakolom10	= $data[10];	//TGL AKAD (MM)
		$datakolom11	= $data[11];	//TGL AKAD (YYYY)
		$datakolom12	= $data[12];	//TENOR
		$datakolom13	= $data[13];	//GRACE PRERIOD
		$datakolom14	= $data[14];	//PLAFOND
		$datakolom15	= $data[15];	//EM manual
		$datakolom16	= $data[16];	//KETERANGAN

		if ($datakolom6 <= 9) { $datakolom6_ = '0'.$datakolom6;	}else{	$datakolom6_ = $datakolom6;}
		if ($datakolom7 <= 9) { $datakolom7_ = '0'.$datakolom7;	}else{	$datakolom7_ = $datakolom7;}
		$dataTGLLAHIR = $datakolom8.'-'.$datakolom7_.'-'.$datakolom6_;

		if ($datakolom9 <= 9) { $datakolom9_ = '0'.$datakolom9;	}else{	$datakolom9_ = $datakolom9;}
		if ($datakolom10 <= 9) { $datakolom10_ = '0'.$datakolom10;	}else{	$datakolom10_ = $datakolom10;}
		$dataTGLAKAD = $datakolom11.'-'.$datakolom10_.'-'.$datakolom9_;

		$metplafond = str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14);

		$met_Date = datediff($dataTGLAKAD, $dataTGLLAHIR);
		$met_Date_ = explode(",", $met_Date);
		if ($met_Date_[1] >= 6) {	$metUsia = $met_Date_[0] + 1;	} else {	$metUsia = $met_Date_[0];	}

		$tglAkhir = date('Y-m-d',strtotime($dataTGLAKAD."+".$datakolom12." Month"."-".$met['lastdayinsurance']." day"));	//KREDIT AKHIR

		if ($met['byrate']=="Age") {
			$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
		}else{
			$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$met['brokerid'].'" AND idclient="'.$met['clientid'].'" AND idpolis="'.$met['polisid'].'" AND '.$datakolom12.' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
		}

/*
		$metPremi = ($metplafond * $metRate['rate']) / $met['calculatedrate'];
		$metPremiDiskon = ($metPremi * $met['diskon']) / 100;
		$metTotalPremi = $metPremi - $metPremiDiskon + $met['adminfee'];
*/
		$metPlafond = str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14);
		$dataEXLPremium = ($metPlafond * $metRate['rate']) / $met['calculatedrate'];				//REAL PREMIRATE
		$metPremiDiskon = $dataEXLPremium * ($met['diskon'] / 100);									//REAL PREMIRATE DISKON
		$metPremi = $dataEXLPremium - $metPremiDiskon + $met['adminfee'];							//TOTAL PREMI

		$cekCabang = mysql_fetch_array($database->doQuery('SELECT ajkregional.er AS idreg, ajkregional.`name` AS regional, ajkcabang.er AS idcab, ajkcabang.`name` AS cabang
													FROM ajkcabang
													INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
													WHERE ajkcabang.idclient = "'.$thisEncrypter->decode($_REQUEST['idc']).'" AND
														  ajkcabang.`name` = "'.strtoupper($datakolom1).'" AND
														  ajkcabang.del IS NULL'));



		//CEK TIPE PRODUK GENERAL
		if ($met['general']=="Y") {
			$data14 = $data[17];	//PAKET
			$data15 = $data[18];	//QUARANTEE
			$data16 = $data[19];	//KELAS
			$data17 = $data[20];	//LOKASI
			$data18 = $data[21];	//HARGA PASAR

			$metRateGeneral = mysql_fetch_array($database->doQuery('SELECT * FROM ajkrategeneral WHERE idbroker="'.$q['idbroker'].'" AND
																									   idclient="'.$met['clientid'].'" AND
																									   idproduk="'.$met['polisid'].'" AND
																									   '.$datakolom12.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$data17.'" AND
																									   quarantee = "'.$data15.'" AND
																									   kelas = "'.$data16.'"'));
			//			echo '<br /><br />';
			$metPlafondGenFire = str_replace($_separatorsNumb,$_separatorsNumb_, $data18);
			$metPremiGenFire = ($metPlafondGenFire * $metRateGeneral['ratefire']) / $met['calculatedrate'];

			$metPlafondGenPA = str_replace($_separatorsNumb,$_separatorsNumb_, $data18);
			$metPremiGenPA = ($metPlafondGenPA * $metRateGeneral['ratepa']) / $met['calculatedrate'];

			$_rowMetGeneral = '
				paketasuransi="'.$data14.'",
				okupasi="'.$data15.'",
				kelas="'.$data16.'",
				lokasi="'.$data17.'",
				nilaijaminan="'.$data18.'",
				premifire="'.$metPremiGenFire.'",
				premipa="'.$metPremiGenPA.'",';
		}else{
			$_rowMetGeneral = '';
		}
		//CEK TIPE PRODUK GENERAL

		//DATA MEDICAL
		if ($met['freecover']=="T") {
			$metMedical = mysql_fetch_array($database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$met['brokerid'].'" AND idpartner="'.$met['clientid'].'" AND idproduk="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.str_replace($_separatorsNumb,$_separatorsNumb_, $datakolom14).' BETWEEN upfrom AND upto AND del IS NULL'));
			$dataMedical = $metMedical['type'];
		}else{
			$dataMedical = 'FCL';
		}
/*
		if ($met['freecover']=="T") {
			$metMedical = mysql_fetch_array($database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$met['brokerid'].'" AND idpartner="'.$met['clientid'].'" AND idproduk="'.$met['polisid'].'" AND '.$metUsia.' BETWEEN agefrom AND ageto AND '.$metPlafond.' BETWEEN upfrom AND upto AND del IS NULL'));
			$dataMedical = $metMedical['type'];
		}else{
			$dataMedical = 'FCL';
		}
*/
		//DATA MEDICAL

$metExist  =$database->doQuery('INSERT INTO ajkpeserta_temp SET idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'",
														   		idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'",
														   		idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'",
														   		filename="'.$_REQUEST['fname'].'",
														   		nomorktp="'.$datakolom4.'",
														   		nomorpk="'.$datakolom5.'",
														   		nama="'.strtoupper($datakolom2).'",
														   		tgllahir="'.$dataTGLLAHIR.'",
														   		usia="'.$metUsia.'",
														   		plafond="'.$metplafond.'",
														   		tglakad="'.$dataTGLAKAD.'",
														   		tenor="'.$datakolom12.'",
														   		tglakhir="'.$tglAkhir.'",
														   		premirate="'.$metRate['rate'].'",
														   		premi="'.$dataEXLPremium.'",
														   		diskonpremi="'.$metPremiDiskon.'",
														   		biayaadmin="'.$met['adminfee'].'",
														   		totalpremi="'.$metPremi.'",
														   		medical="'.$dataMedical.'",
														   		keterangan="'.$datakolom16.'",
														   		statusaktif="Pending",
														   		regional="'.$cekCabang['idreg'].'",
														   		cabang="'.$cekCabang['idcab'].'",
														   		'.$_rowMetGeneral.'
																input_by="'.$q['id'].'",
														   		input_time="'.$futgl.'"');
//echo '<br /><br />';
	}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=exsist&exs=Xls2">
	<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<strong>Success!</strong> Upload data '.$_REQUEST['fname'].' by '.$q['firstname'].'.
    </div>';

echo '	</div>
	</div>';
	;
	break;

case "Xls2":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Selection Data Uploading</h3></div>
			<div class="panel-body">
				<div class="form-group">';
if ($q['idbroker'] == NULL) {
echo '<label class="col-sm-2 control-label">Broker</label>
	<div class="col-sm-10">
	<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
		</div>
	</div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
				            		<option value="">Select Partner</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
	       	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10">
		        <select name="coPolicy" class="form-control" id="coPolicy" required>
				              			<option value="">Select Product</option>
		        </select>
				</div>
			</div>';
}else{
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
	  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
echo '<label class="col-sm-2 control-label">Partner<strong class="text-danger"> *</strong></label>
		<div class="col-sm-10">
		<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);" required><option value="">Select Partner</option>';
$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
echo '</select>
		</div>
	</div>
		    <div class="form-group">
	     	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10"><select name="coPolicy" class="form-control" id="coProduct" onchange="showUser(this.value)" required><option value="">Select Product</option></select></div>
			</div>';
}
echo '
			<div class="form-group">
				<label class="col-sm-2 control-label">File Excel (.xls)<span class="text-danger">*</span></label>
                <div class="col-sm-10">
                <input type="file" name="fileUploadExcel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                </div>
			</div>';

//$metSetEXL = mysql_fetch_array($database->doQuery('SELECT * FROM '))
echo '	</div>
		 <div class="panel-footer text-center"><button type="type"><div id="txtHint"></div></button></div>

		<!--<div class="panel-footer text-center"><input type="hidden" name="exs" value="UploadXls2">'.BTN_SUBMIT.'</div>-->
			</form>

		</div>
	</div>';

		echo '<!DOCTYPE html>
<html>
<body>


<script>
function showUser(str) {
  if (str=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("txtHint").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","getmetbutton.php?qr="+str,true);
  xmlhttp.send();
}
</script>
</head>
<body>





</body>
</html>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

case "valData":
echo '<div class="page-header-section"><h2 class="title semibold">Upload Verification</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
      <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <th class="text-center" width="1%">General</th>
        <th class="text-center">Partner</th>
        <th class="text-center" width="40%">Product</th>
        <th class="text-center" width="1%">Data</th>
        <th class="text-center" width="1%">InputBy</th>
        <th class="text-center" width="1%">InputTime</th>
        <th class="text-center" width="1%">Create</th>
        </tr>
    </thead>
    <tbody>';
$metExl = $database->doQuery('SELECT Count(ajkpeserta_temp.nama) AS jData, ajkpeserta_temp.input_by, ajkpeserta_temp.input_time, ajkpeserta_temp.id, useraccess.username, ajkclient.`name`, ajkpolis.policyauto, ajkpolis.policymanual, ajkpolis.produk, IF(ajkpolis.general="Y", "Yes","No") AS general
							  FROM ajkpeserta_temp
							  INNER JOIN ajkclient ON ajkpeserta_temp.idclient = ajkclient.id
							  INNER JOIN ajkpolis ON ajkpeserta_temp.idpolicy = ajkpolis.id
							  INNER JOIN useraccess ON ajkpeserta_temp.input_by = useraccess.id
							  WHERE ajkpeserta_temp.del IS NULL '.$q___4.'
							  GROUP BY ajkpeserta_temp.input_time
							  ORDER BY ajkpeserta_temp.input_time DESC');
while ($metExl_ = mysql_fetch_array($metExl)) {
echo '<tr>
   	<td align="center"><span class="label label-inverse">'.$metExl_['general'].'</span></td>
   	<td>'.$metExl_['name'].'</td>
   	<td>'.$metExl_['produk'].'</td>
   	<td align="center"><span class="label label-primary">'.$metExl_['jData'].'</span></td>
   	<td align="center"><span class="label label-primary">'.$metExl_['username'].'</span></td>
   	<td align="center"><span class="label label-primary">'.$metExl_['input_time'].'</span></td>
   	<td align="center"><a href="ajk.php?re=exsist&exs=appValView&user='.$thisEncrypter->encode($metExl_['input_by']).'&dtime='.$thisEncrypter->encode($metExl_['input_time']).'">'.BTN_VIEW.'</a></td>
    </tr>';
	}
echo '</tbody>
    </table>
    </div>';
	;
	break;

case "appValView":
echo '<div class="page-header-section"><h2 class="title semibold">Upload Verification</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
$met_EXL = mysql_fetch_array($database->doQuery('SELECT ajkpeserta_temp.id,
														ajkpeserta_temp.input_time,
														ajkpolis.byrate,
														ajkcobroker.name AS brokername,
														ajkcobroker.logo AS brokerlogo,
														ajkclient.`name` AS clientname,
														ajkclient.logo AS clientlogo,
														ajkpolis.produk,
														ajkpolis.general,
														ajkcabang.name AS nmcabang,
														SUM(IF(ajkpolis.general="Y",(ajkpeserta_temp.totalpremi + ajkpeserta_temp.premigeneral + ajkpeserta_temp.premipa), ajkpeserta_temp.totalpremi)) AS totalpremi
												 FROM ajkpeserta_temp
												 INNER JOIN ajkclient ON ajkpeserta_temp.idclient = ajkclient.id
												 INNER JOIN ajkpolis ON ajkpeserta_temp.idpolicy = ajkpolis.id
												 INNER JOIN ajkcobroker ON ajkpeserta_temp.idbroker = ajkcobroker.id
												 INNER JOIN ajkcabang ON ajkpeserta_temp.cabang = ajkcabang.er
												 WHERE ajkpeserta_temp.input_by="'.$thisEncrypter->decode($_REQUEST['user']).'" AND ajkpeserta_temp.input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'"
												 GROUP BY ajkpeserta_temp.input_by, ajkpeserta_temp.input_time'));
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met_EXL['brokerlogo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$met_EXL['brokername'].'</dd>
				<dt>Partner</dt><dd>'.$met_EXL['clientname'].'</dd>
				<dt>Product</dt><dd>'.$met_EXL['produk'].'</dd>
				<dt>Total Premium</dt><dd><span class="number"><span class="label label-primary">'.duit($met_EXL['totalpremi']).'</span></dd>
				<dt>Cabang</dt><dd>'.$met_EXL['nmcabang'].'</dd>
			</dl>
			</div>
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met_EXL['clientlogo'].'" alt="" width="65px" height="65px"></div>
		</div>';
if ($met_EXL['general']=="Y") {
	$kolom_EXL = '<th class="text-center" width="1%">Premi ND</th>
        		  <th class="text-center" width="1%">Discount</th>
        		  <th class="text-center" width="1%">Adminfee</th>
        		  <th class="text-center" width="1%">Total Premi ND</th>
        		  <th class="text-center" width="1%">Premi Fire</th>
        		  <th class="text-center" width="1%">Premi PA</th>
        		  <th class="text-center" width="1%">Total Premi</th>';
}else{
	$kolom_EXL = '<th class="text-center" width="1%">Premi</th>
				  <!--<th class="text-center" width="1%">Discount</th>
        		  <th class="text-center" width="1%">Adminfee</th>-->
				  <th class="text-center" width="1%">Total Premi</th>';
}
echo '<div class="table-responsive panel-collapse pull out">
	  <form method="post" action="" id ="frm1">
	  <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <td width="1%" align="center"><span class="checkbox custom-checkbox">
										<input type="checkbox" name="checkall" id=" " value=" " onclick="checkedAll(frm1);"/>
                        				<label for=" "></label>
                      				 </span>
                      				<!--<input type="checkbox" name="checkall" onclick="checkedAll(frm1);">--></td>
        <th class="text-center">Nama</th>
        <th class="text-center" width="10%">Nomor KTP</th>
        <th class="text-center" width="10%">Nomor PK</th>
        <th class="text-center" width="1%">Tanggal Lahir</th>
        <th class="text-center" width="1%">Usia</th>
        <th class="text-center" width="8%">Tanggal Akad</th>
        <th class="text-center" width="1%">Tenor</th>
        <th class="text-center" width="8%">Tanggal Akhir</th>
        <th class="text-center" width="1%">Plafond</th>
        <th class="text-center" width="1%">Rate</th>
		'.$kolom_EXL.'
        <th class="text-center" width="1%">Medical</th>
        <th class="text-center" width="1%">Keterangan</th>
        </tr>
    </thead>
    <tbody>';
$metExl = $database->doQuery('SELECT
ajkpeserta_temp.nama,
ajkpeserta_temp.input_time,
ajkclient.`name`,
ajkpolis.policyauto,
ajkpolis.policymanual,
ajkpolis.produk,
ajkpeserta_temp.id,
ajkpeserta_temp.idbroker,
ajkpeserta_temp.idclient,
ajkpeserta_temp.idpolicy,
ajkpeserta_temp.nama,
ajkpeserta_temp.nomorktp,
ajkpeserta_temp.nomorpk,
ajkpeserta_temp.tgllahir,
ajkpeserta_temp.usia,
ajkpeserta_temp.plafond,
ajkpeserta_temp.tglakad,
ajkpeserta_temp.tglakhir,
ajkpeserta_temp.tenor,
ajkpeserta_temp.premi,
ajkpeserta_temp.diskonpremi,
ajkpeserta_temp.biayaadmin,
ajkpeserta_temp.totalpremi,
ajkpeserta_temp.premigeneral,
ajkpeserta_temp.premipa,
ajkpeserta_temp.medical,
ajkpeserta_temp.filemedical,
ajkpeserta_temp.keterangan,
IF(ajkpolis.general="Y",(ajkpeserta_temp.totalpremi + ajkpeserta_temp.premigeneral + ajkpeserta_temp.premipa), ajkpeserta_temp.totalpremi) AS totalpremi_general
FROM ajkpeserta_temp
INNER JOIN ajkclient ON ajkpeserta_temp.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkpeserta_temp.idpolicy = ajkpolis.id
WHERE ajkpeserta_temp.input_by="'.$thisEncrypter->decode($_REQUEST['user']).'" AND ajkpeserta_temp.input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'"
ORDER BY ajkpeserta_temp.input_time ASC');
while ($metExl_ = mysql_fetch_array($metExl)) {
if ($met['byrate']=="Age") {
	$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$metExl_['idbroker'].'" AND idclient="'.$metExl_['idclient'].'" AND idpolis="'.$metExl_['idpolicy'].'" AND '.$metExl_['usia'].' BETWEEN agefrom AND ageto AND '.$metExl_['tenor'].' BETWEEN tenorfrom AND tenorto'));
}else{
	$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$metExl_['idbroker'].'" AND idclient="'.$metExl_['idclient'].'" AND idpolis="'.$metExl_['idpolicy'].'" AND '.$metExl_['tenor'].' BETWEEN tenorfrom AND tenorto'));
}

//CEK DATA GENERAL
if ($met_EXL['general']=="Y") {
$_met_EXL = '<td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premi']).'</span></td>
		   	 <td align="right"><strong>'.duit($metExl_['diskonpremi']).'</strong></td>
		   	 <td align="right"><strong>'.duit($metExl_['biayaadmin']).'</strong></td>
		   	 <td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi']).'</span></td>
		   	 <td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premifire']).'</span></td>
   			 <td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premipa']).'</span></td>
   			 <td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi_general']).'</span></td>';
}else{
$_met_EXL = '<td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premi']).'</span></td>
			 <!--<td align="right"><strong>'.duit($metExl_['diskonpremi']).'</strong></td>
			 <td align="right"><strong>'.duit($metExl_['biayaadmin']).'</strong></td>-->
		   	 <td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi']).'</span></td>';
}
//CEK DATA GENERAL
if ($metExl_['medical']=="FCL" OR $metExl_['medical']=="NM") {
	$metMedical_ = '<span class="number"><span class="label label-info">'.$metExl_['medical'].'</span>';
	$cekData = '<span class="checkbox custom-checkbox">
				<input type="checkbox" name="dataTemp[]" id="'.$metExl_['id'].'" value="'.$metExl_['id'].'" />
                <label for="'.$metExl_['id'].'"></label>
                </span>';
}else{
	if ($metExl_['filemedical'] == NULL) {
	$metMedical_ = '<span class="number"><span class="label label-danger"><a href="ajk.php?re=exsist&exs=appDokMedical&idtemp='.$thisEncrypter->encode($metExl_['id']).'&user='.$_REQUEST['user'].'&dtime='.$_REQUEST['dtime'].'" title="Upload dokumen medical"><font color="#FFF">Upload '.$metExl_['medical'].'</font></a></span>';
	$cekData = '';
	}else{
	$metMedical_ = '<span class="number"><span class="label label-warning"><a href="../'.$PathDokumen.''.$metExl_['filemedical'].'" target="_blank" title="View dokumen medical"><font color="#FFF"> View '.$metExl_['medical'].'</font></a></span>';
	$cekData = '<span class="checkbox custom-checkbox">
				<input type="checkbox" name="dataTemp[]" id="'.$metExl_['id'].'" value="'.$metExl_['id'].'" />
                <label for="'.$metExl_['id'].'"></label>
                </span>';
	}
}
echo '<tr>
   	<td align="center">'.$cekData.'
                      <!--<input type="checkbox" class="case" name="dataTemp[]" value="'.$metExl_['id'].'">--></td>
   	<td>'.$metExl_['nama'].'</td>
   	<td align="center">'.$metExl_['nomorktp'].'</td>
   	<td align="center">'.$metExl_['nomorpk'].'</td>
   	<td align="center">'._convertDate($metExl_['tgllahir']).'</td>
   	<td align="center">'.$metExl_['usia'].'</td>
   	<td align="center">'._convertDate($metExl_['tglakad']).'</td>
   	<td align="center">'.$metExl_['tenor'].'</td>
   	<td align="center">'._convertDate($metExl_['tglakhir']).'</td>
   	<td align="right">'.duit($metExl_['plafond']).'</td>
   	<td align="center"><span class="number"><span class="label label-inverse">'.$metRate['rate'].'</span></td>
   	'.$_met_EXL.'
   	<td align="center">'.$metMedical_.'</td>
   	<td align="center">'.$metExl_['keterangan'].'</td>
    </tr>';
		}
echo '</tbody>
	</table>
	<div align="center"><input type="hidden" name="exs" value="approVal">'.BTN_SUBMIT.'</div>
    </form>
    </div>';
	;
	break;

case "appDokMedical":
/*
echo $thisEncrypter->decode($_REQUEST['idtemp']).'<br />';
echo $thisEncrypter->decode($_REQUEST['user']).'<br />';
echo $thisEncrypter->decode($_REQUEST['dtime']).'<br />';
*/
$metDok = mysql_fetch_array($database->doQuery('SELECT
ajkpeserta_temp.id,
ajkpeserta_temp.idbroker,
ajkpeserta_temp.idclient,
ajkpeserta_temp.idpolicy,
ajkpeserta_temp.typedata,
ajkpeserta_temp.nama,
ajkpeserta_temp.gender,
ajkpeserta_temp.tgllahir,
ajkpeserta_temp.usia,
ajkpeserta_temp.tglakad,
ajkpeserta_temp.tenor,
ajkpeserta_temp.tglakhir,
ajkpeserta_temp.plafond,
ajkpeserta_temp.totalpremi,
ajkpeserta_temp.medical,
ajkpeserta_temp.cabang,
ajkcobroker.`name` AS nmbroker,
ajkcobroker.logo AS logobroker,
ajkclient.`name` AS nmpartner,
ajkclient.logo AS logopartner,
ajkpolis.produk AS produk,
ajkcabang.`name` AS nmcabang
FROM ajkpeserta_temp
INNER JOIN ajkcobroker ON ajkpeserta_temp.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkpeserta_temp.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkpeserta_temp.idpolicy = ajkpolis.id
INNER JOIN ajkcabang ON ajkpeserta_temp.cabang = ajkcabang.er
WHERE ajkpeserta_temp.id = "'. $thisEncrypter->decode($_REQUEST['idtemp']).'" '));
echo '<div class="page-header-section"><h2 class="title semibold">Modul Upload Document Medical</h2></div>
	<div class="page-header-section">
	<div class="toolbar"><a href="ajk.php?re=exsist&exs=appValView&user='.$_REQUEST['user'].'&dtime='.$_REQUEST['dtime'].'">'.BTN_BACK.'</a></div>
	</div>
	</div>';
if ($_REQUEST['docmedical']=="savefilemedical") {
	if ($_FILES['fileImage']['size'] / 1024 > $FILESIZE_2)	{
		$metnotif .= '<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div>';
	}
	else{
		$PathDokumen		= "../myFiles/_docs/".$foldername."";
		if (!file_exists($PathDokumen)) 	{	mkdir($PathDokumen, 0777);	chmod($PathDokumen, 0777);	}

		$namafilemedical =  str_replace(" ", "_", strtoupper($foldername."SKKT_".date("YmdHis")."_P".$metDok['idclient'].$_FILES['fileImage']['name']));
		$nama_file =  str_replace(" ", "_", strtoupper("SKKT_".date("YmdHis")."_P".$metDok['idclient'].$_FILES['fileImage']['name']));
		$file_type = $_FILES['fileImage']['type']; //tipe file
		$source = $_FILES['fileImage']['tmp_name'];
		$direktori = "$PathDokumen$nama_file"; // direktori tempat menyimpan file
		move_uploaded_file($source,$direktori);
/*
		echo $source.'<br />';
		echo $namafilemedical.'<br />';
		echo $direktori;
*/

		$metCompany = $database->doQuery('UPDATE ajkpeserta_temp SET filemedical="'.$namafilemedical.'" WHERE id="'. $thisEncrypter->decode($_REQUEST['idtemp']).'"');
		$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=exsist&exs=appValView&user='.$_REQUEST['user'].'&dtime='.$_REQUEST['dtime'].'">
					 <div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> Upload document file medical.
                 </div>';
	}
}
echo '<div class="row">
		<div class="col-lg-12">
        	<div class="tab-content">
            	<div class="tab-pane active" id="profile">
                <form method="post" class="panel form-horizontal form-bordered" name="form-profile" action="#" data-parsley-validate enctype="multipart/form-data">
					<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-1">
           					<ul class="list-table">
	           					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metDok['logobroker'].'" alt="" width="75px"></li>
							</ul>
							</div>
							<div class="col-md-10">
           					<ul class="list-table">
	           					<li class="text-center"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metDok['nmbroker'].'<br />'.$metDok['nmpartner'].'<br />'.$metDok['produk'].'<br />'.$metDok['nmcabang'].'</h4></li>
							</ul>
							</div>
							<div class="col-md-1">
           					<ul class="list-table">
	           					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metDok['logopartner'].'" alt="" width="75px"></li>
							</ul>
							</div>
						</div>
						<div class="form-group">
	                		<div class="col-xs-12 col-sm-12 col-md-12">
	                		'.$metnotif.'
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Name</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.$metDok['nama'].'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Date Of Birth</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'._convertDate($metDok['tgllahir']).'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Age</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.$metDok['usia'].' Years</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Start Date</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'._convertDate($metDok['tglakad']).'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Tenor</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.$metDok['tenor'].' Months</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">End Date</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'._convertDate($metDok['tglakhir']).'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Plafond</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.duit($metDok['plafond']).'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Total Premium</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.duit($metDok['totalpremi']).'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Medical</a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default">'.$metDok['medical'].'</p></div>
								</div>
								<div class="table-layout mt1 mb0">
									<div class="col-sm-1"><p class="meta nm"><a href="javascript:void(0);">Upload Document <font color="red">*</font></a></p></div>
									<div class="col-sm-11"><p class="meta nm text-default"><input type="file" name="fileImage" accept="image/*" required></p></div>
								</div>
								<div class="panel-footer"><input type="hidden" name="docmedical" value="savefilemedical">'.BTN_SUBMIT.'</div>
	                    	</div>
						</div>
	                </div>
	            </form>
	            </div>
			</div>
        </div>
    </div>';
	;
	break;

case "approVal":
if (!$_REQUEST['dataTemp']) {
echo '<center><div class="alert alert-danger"><strong>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</div>
	  <a href="ajk.php?re=exsist&exs=valData"><button type="Button" class="btn btn-lg btn-danger">Kembali Ke Halaman Approval Data</button></a></center>';
}else{
	foreach($_REQUEST['dataTemp'] as $k => $val){
	$metTemp = mysql_fetch_array($database->doQuery('SELECT * FROM ajkpeserta_temp WHERE id="'.$val.'"'));
	$cekProduk = mysql_fetch_array($database->doQuery('SELECT id, general FROM ajkpolis WHERE id="'.$metTemp['idpolicy'].'"'));
	if ($cekProduk['general']) {
		$metIns = 'nilaijaminan="'.$metTemp['nilaijaminan'].'",
				   paketasuransi="'.$metTemp['paketasuransi'].'",
				   okupasi="'.$metTemp['okupasi'].'",
				   kelas="'.$metTemp['kelas'].'",
				   lokasi="'.$metTemp['lokasi'].'",
				   premigeneralrate="'.$metTemp['premigeneralrate'].'",
				   premigeneral="'.$metTemp['premifire'].'",
				   premiparate="'.$metTemp['premiparate'].'",
				   premipa="'.$metTemp['premipa'].'",';
	}else{
		$metIns ='';
	}
	$keterangansakit = strpos($metTemp['keterangan'], "Sakit");
	if ($keterangansakit === false){
		$statusDEB = "Approve";
	}else{
		$statusDEB = "Pending";
	}
	$metInsTemp = $database->doQuery('INSERT INTO ajkpeserta SET idbroker="'.$metTemp['idbroker'].'",
														   		 idclient="'.$metTemp['idclient'].'",
														   		 idpolicy="'.$metTemp['idpolicy'].'",
														   		 filename="'.$metTemp['filename'].'",
														   		 nomorktp="'.$metTemp['nomorktp'].'",
														   		 nomorpk="'.$metTemp['nomorpk'].'",
														   		 nama="'.$metTemp['nama'].'",
														   		 tgllahir="'.$metTemp['tgllahir'].'",
														   		 usia="'.$metTemp['usia'].'",
														   		 plafond="'.$metTemp['plafond'].'",
														   		 tglakad="'.$metTemp['tglakad'].'",
														   		 tenor="'.$metTemp['tenor'].'",
														   		 tglakhir="'.$metTemp['tglakhir'].'",
														   		 premirate="'.$metTemp['premirate'].'",
														   		 premi="'.$metTemp['premi'].'",
														   		 diskonpremi="'.$metTemp['diskonpremi'].'",
														   		 biayaadmin="'.$metTemp['biayaadmin'].'",
														   		 totalpremi="'.$metTemp['totalpremi'].'",
														   		 medical="'.$metTemp['medical'].'",
														   		 filemedical="'.$metTemp['filemedical'].'",
														   		 keterangan="'.$metTemp['keterangan'].'",
														   		 statusaktif="'.$statusDEB.'",
														   		 '.$metIns.'
														   		 regional="'.$metTemp['regional'].'",
														   		 cabang="'.$metTemp['cabang'].'",
														   		 mppbln="'.$metTemp['mppbln'].'",
														   		 input_by="'.$metTemp['input_by'].'",
														   		 input_time="'.$metTemp['input_time'].'",
																 approve_by="'.$q['id'].'",
																 approve_time="'.$futgl.'"');

	$metTemp = mysql_fetch_array($database->doQuery('DELETE FROM ajkpeserta_temp WHERE id="'.$val.'"'));
	}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=exsist&exs=valData">
	<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<strong>Success!</strong> Approval data by '.$q['firstname'].'.
    </div>';
}
	;
	break;


case "DataManual":
echo '<div class="page-header-section"><h2 class="title semibold">New Data</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
if ($_REQUEST['err']=="setManual") {
$epl = explode("-", $_REQUEST['coProduk']);
/*
echo $q['idbroker'].'<br />';
echo $_REQUEST['coClient'].'<br />';
echo $epl[0].'<br />';
echo $_REQUEST['coRegional'].'<br />';
echo $_REQUEST['coCabang'].'<br />';
echo $_REQUEST['nmrktp'].'<br />';
echo $_REQUEST['metNama'].'<br />';
echo $_REQUEST['gender'].'<br />';
echo _convertDateEng2($_REQUEST['dob']).'<br />';
echo $_REQUEST['tlphp'].'<br />';
echo $_REQUEST['valueproposed'].'<br />';
echo $_REQUEST['street1'].'#'.$_REQUEST['addressline1'].'#'.$_REQUEST['city1'].'#'.$_REQUEST['postcode1'].'<br />';
echo $_REQUEST['street2'].'#'.$_REQUEST['addressline2'].'#'.$_REQUEST['city2'].'#'.$_REQUEST['postcode2'].'<br />';
echo $_FILES['fileImage']['name'].'<br />';
*/
if ($_FILES['fileImage']['name']) {
	$PathUploadGeneral	= "../".$PhotoGeneral_F."/".$foldername."";
	if (!file_exists($PathUploadGeneral)) 	{	mkdir($PathUploadGeneral, 0777);	chmod($PathUploadGeneral, 0777);	}
	$namafileupload =  str_replace(" ", "_", $foldername."FLEXAS_".date("YmdHis")."_".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
	$nama_fileupload =  str_replace(" ", "_", "FLEXAS_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
	$file_type = $_FILES['fileImage']['type']; //tipe file
	$source = $_FILES['fileImage']['tmp_name'];
	$direktori = "$PathUploadGeneral$nama_fileupload"; // direktori tempat menyimpan file
	move_uploaded_file($source,$direktori);

	$ktpdebitur = 'pc_ktp="'.$namafileupload.'",';
}else{
	$ktpdebitur = '';
}
$metGen = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS id FROM ajkumum WHERE idbroker="'.$q['idbroker'].'"'));
$nomorspajk = 100000 + $metGen['id'] + 1; $nomorspajk = substr($nomorspajk, 1);	//jumlah data dari masing-masing broker tambah 1
$metdebitur = $database->doQuery('INSERT INTO ajkumum SET idbroker="'.$q['idbroker'].'",
														  idclient="'.$_REQUEST['coClient'].'",
														  idproduk="'.$epl[0].'",
														  nomorspajk="'.$DatePolis.'0'.$q['idbroker'].''.$nomorspajk.'",
														  idregional="'.$_REQUEST['coRegional'].'",
														  idcabang="'.$_REQUEST['coCabang'].'",
														  ktp="'.$_REQUEST['nmrktp'].'",
														  nama="'.$_REQUEST['metNama'].'",
														  jnskelamin="'.$_REQUEST['gender'].'",
														  tgllahir="'._convertDateEng2($_REQUEST['dob']).'",
														  hp="'.$_REQUEST['tlphp'].'",
														  statusspajk="Request",
														  '.$ktpdebitur.'
														  alamatdebitur="'.$_REQUEST['street1'].'#'.$_REQUEST['addressline1'].'#'.$_REQUEST['city1'].'#'.$_REQUEST['postcode1'].'",
														  alamtobjek="'.$_REQUEST['street2'].'#'.$_REQUEST['addressline2'].'#'.$_REQUEST['city2'].'#'.$_REQUEST['postcode2'].'",
														  nilaidiajukan="'.$_REQUEST['valueproposed'].'",
														  input_by="'.$q['id'].'",
														  input_date="'.$futgl.'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=exsist&exs=DataManual">
			<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
            <strong>Success!</strong> New Debitur General with SPAJK number <strong>'.$nomorspajk.'</strong>.
            </div>';
}
echo '<div class="row">
		<div class="col-md-12">
		'.$metnotif.'
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Input Data Manual</h3></div>
			<div class="panel-body">
				<div class="form-group">';
if ($q['idbroker'] == NULL) {
echo '<label class="col-sm-2 control-label">Broker</label>
	<div class="col-sm-10">
	<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
echo '</select>
		</div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
		<div class="col-sm-10">
		<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
		<option value="">Select Partner</option>
		</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		<div class="col-lg-10">
		<select name="coPolicy" class="form-control" id="coPolicy" required>
		<option value="">Select Product</option>
		</select>
		</div>
	</div>';
}else{
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '</div><h4 class="text-primary mt0"> &nbsp; '.$_broker['name'].'</h4>

	  <div class="form-group">';
echo '<label class="col-sm-2 control-label">Partner<strong class="text-danger"> *</strong></label>
	 <div class="col-sm-10">
<select name="coClient" class="form-control" onChange="UserPartner(this);" required><option value="">Select Partner</option>';
$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
}
echo '</select>
		</div>
	</div>

	<div class="form-group">
	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		<div class="col-lg-10"><select name="coProduk" class="form-control" onChange="UserProduk(this);" id="coProduk" required><option value="">Select Product</option></select></div>
	</div>

	<div class="form-group">
	<label class="col-lg-2 control-label">Regional<strong class="text-danger"> *</strong></label>
		<div class="col-lg-10"><select name="coRegional" class="form-control" onChange="UserRegional(this);" id="coRegional" required><option value="">Select Regional</option></select></div>
	</div>

	<div class="form-group">
	<label class="col-lg-2 control-label">Branch<strong class="text-danger"> *</strong></label>
		<div class="col-lg-10"><select name="coCabang" class="form-control" id="coCabang" " required><option value="">Select Branch</option></select></div>
	</div>';
}
echo '<h4 class="text-primary mt0"> &nbsp; Data Debitur</h4>
    <div class="form-group">
		<label class="col-sm-2 control-label">K T P  <font color="blue">(img)</font></label>
        <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*"></div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">KTP Number <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row">
           	<div class="col-md-12"><input type="text" name="nmrktp" class="form-control" value="'.$_REQUEST['nmrktp'].'" placeholder="KTP Number" required/></div>
            </div>
        </div>
    </div>
    <div class="form-group">
	<label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
    	<div class="col-sm-10"><input name="metNama" value="'.$_REQUEST['metNama'].'" type="text" class="form-control" placeholder="Debitur Name" required></div>
	</div>
	<div class="form-group">
    <label class="col-sm-2 control-label">Gender <span class="text-danger">*</span></label>
    	<div class="col-sm-10">
        	<span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($_REQUEST['gender'], "P").' name="gender" id="customradio1" value="P" required><label for="customradio1">&nbsp;&nbsp;Men&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($_REQUEST['gender'], "W").' name="gender" id="customradio2" value="W" required><label for="customradio2">&nbsp;&nbsp;Women</label>
            </span>
		</div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Date of Birth <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row">
            	<div class="col-md-12"><input type="text" name="dob" class="form-control" id="datepicker4" value="'.$_REQUEST['dob'].'" placeholder="Date of birth" required/></div>
            </div>
        </div>
    </div>
    <div class="form-group">
	<label class="control-label col-sm-2">Telephone <span class="text-danger">*</span></label>
    	<div class="col-sm-10">
			<div class="row">
            	<div class="col-md-12"><input type="text" name="tlphp" class="form-control" data-parsley-type="number" value="'.$_REQUEST['tlphp'].'" placeholder="Telephone" required/></div>
			</div>
		</div>
    </div>
	<div class="form-group">
		<label class="control-label col-sm-2">Address <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row mb5"><div class="col-sm-12"><input name="street1" value="'.$_REQUEST['street1'].'" type="text" class="form-control" placeholder="Street Address 1" required></div></div>
			<div class="row mb5"><div class="col-sm-12"><input name="addressline1" value="'.$_REQUEST['addressline1'].'" type="text" class="form-control" placeholder="Street Address 2"></div></div>
			<div class="row">
        		<div class="col-xs-6 pr5"><input name="city1" value="'.$_REQUEST['city1'].'" type="text" class="form-control" placeholder="City" required></div>
        		<div class="col-xs-6 pl5"><input name="postcode1" value="'.$_REQUEST['postcode1'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
			</div>
    	</div>
    </div>
	<h4 class="text-primary mt0"> &nbsp; Data Object</h4>
	<div class="form-group">
		<label class="control-label col-sm-2">Address<span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row mb5"><div class="col-sm-12"><input name="street2" value="'.$_REQUEST['street2'].'" type="text" class="form-control" placeholder="Street Address 1" required></div></div>
			<div class="row mb5"><div class="col-sm-12"><input name="addressline2" value="'.$_REQUEST['addressline2'].'" type="text" class="form-control" placeholder="Street Address 2"></div></div>
			<div class="row">
        		<div class="col-xs-6 pr5"><input name="city2" value="'.$_REQUEST['city2'].'" type="text" class="form-control" placeholder="City" required></div>
        		<div class="col-xs-6 pl5"><input name="postcode2" value="'.$_REQUEST['postcode2'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
			</div>
    	</div>
    </div>
    <div class="form-group">
	<label class="control-label col-sm-2">Value Proposed <span class="text-danger">*</span></label>
    	<div class="col-sm-10">
			<div class="row">
            	<div class="col-md-12"><input type="text" name="valueproposed" class="form-control" data-parsley-type="number" value="'.$_REQUEST['valueproposed'].'" placeholder="Value Proposed" required/></div>
			</div>
		</div>
    </div>
	<!--<div class="form-group">
		<label class="col-sm-2 control-label">Photocopy Certificate  <font color="blue">(pdf)</font></label>
        <div class="col-sm-10"><input type="file" name="fileCertificate" accept="application/pdf"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Photocopy IMB  <font color="blue">(pdf)</font></label>
        <div class="col-sm-10"><input type="file" name="fileIMB" accept="application/pdf"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Photocopy PBB <font color="blue">(pdf)</font></label>
        <div class="col-sm-10"><input type="file" name="filePBB" accept="application/pdf"></div>
	</div>-->';

echo '	</div>
	<div class="panel-footer text-center"><input type="hidden" name="err" value="setManual">'.BTN_SUBMIT.'</div>
		</form>
		</div>
</div>';

echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Selection Data Uploading</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="coBroker" class="form-control" onChange="mametBrokerUploadExcel(this);" required>
				            		<option value="">Select Broker</option>';
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
}
echo '</select>
				    </div>
			    </div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Company <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
				            		<option value="">Select Company</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
	       	<label class="col-lg-2 control-label">Policy<strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10">
		        <select name="coPolicy" class="form-control" id="coPolicy" required>
				              			<option value="">Select Policy</option>
		        </select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">File Excel (.xls)<span class="text-danger">*</span></label>
                <div class="col-sm-10"><input type="file" name="fileUpload" accept="application/vnd.ms-excel" required></div>
			</div>';
echo '	</div>
		<div class="panel-footer"><input type="hidden" name="exs" value="UploadXls">'.BTN_SUBMIT.'</div>
			</form>
		</div>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>
<script type="text/javascript">
checked=false;
function checkedAll (frm1) {
	var aa= document.getElementById('frm1');
	if (checked == false)	{	checked = true	}	else	{	checked = false	}
	for (var i =0; i < aa.elements.length; i++){ aa.elements[i].checked = checked;}
}
</script>

