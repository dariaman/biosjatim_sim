<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;">
		<div class="page-header page-header-block">';
switch ($_REQUEST["mcl"]) {
case "delmedical":
echo '<div class="page-header-section"><h2 class="title semibold">Delete Table Medical</h2></div>
      	<div class="page-header-section">
		</div>
		</div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
$setMedical = $database->doQuery('UPDATE ajkmedical SET status="Tidak Aktif" WHERE filename="'.$thisEncrypter->decode($_REQUEST['idf']).'"');
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=medical">
	<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<strong>Success!</strong> delete data table medical by '.$q['firstname'].'.
    </div>';
echo '</div>
	</div>
</div>';
	;
	break;


	case "viewmedical":
echo '<div class="page-header-section"><h2 class="title semibold">View Table Medical</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=medical">'.BTN_BACK.'</a></div>
		</div>
		</div>';
		echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
		$met = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.`name` AS broker, ajkcobroker.`logo` AS brokerlogo, ajkclient.`name` AS client, ajkclient.`logo` AS clientlogo, ajkpolis.policyauto, ajkpolis.policymanual, ajkpolis.typerate, ajkpolis.byrate, ajkpolis.calculatedrate, ajkpolis.produk
											 FROM ajkcobroker
											 INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
											 INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
											 WHERE ajkcobroker.id="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkclient.id="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkpolis.id="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkcobroker.del IS NULL AND ajkclient.del IS NULL AND ajkpolis.del IS NULL'));

		if ($met['byrate']=="Age") {
			$kolomAge = '<th>Age From</th><th>Age To</th>';
			$kolomFootAge = '<th><input type="search" class="form-control" name="search_engine" placeholder="Age From"></th>
							 <th><input type="search" class="form-control" name="search_engine" placeholder="Age To"></th>';
		}
		$metFileRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND idpartner="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND input_time="'.$thisEncrypter->decode($_REQUEST['time']).'" AND del IS NULL'));
		echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$met['broker'].'</dd>
				<dt>Company</dt><dd>'.$met['client'].'</dd>
				<dt>Product</dt><dd>'.$met['produk'].'</dd>
				<dt>Product</dt><dd><button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metFileRate['status'].'</button></dd>
				<dt>File</dt><dd><a href="../'.$PathTblMedical.''.$metFileRate['filename'].'">'.$metFileRate['filename'].'</a></dd>
			</dl>
			</div>
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="65px" height="65px"></div>
		</div>';
		if ($metFileRate['del']==NULL) {
			echo '<a href="ajk.php?re=medical&mcl=delmedical&idf='.$thisEncrypter->encode($metFileRate['filename']).'" onClick="if(confirm(\'Are you sure to delete this table medical?\')){return true;}{return false;}"><div class="panel-toolbar text-right">'.BTN_DEL.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></a>';
		}else{

		}

		echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th width="10%">Age From</th>
		<th width="10%">Age To</th>
		<th>Plafond From</th>
		<th>Plafond To</th>
		<th width="10%">Type</th>
		</tr>
	</thead>
	<tbody>';
		$metRate = $database->doQuery('SELECT * FROM ajkmedical WHERE idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND idpartner="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND input_time="'.$thisEncrypter->decode($_REQUEST['time']).'" AND del IS NULL');
		while ($metRate_ = mysql_fetch_array($metRate)) {
			if ($met['byrate']=="Age") {
				$kolomViewAge = '<td align="center">'.$metRate_['agefrom'].'</td>
				 				 <td align="center">'.$metRate_['ageto'].'</td>';
			}
			echo '<tr>
		<td align="center">'.++$no.'</td>
		<td align="center">'.$metRate_['agefrom'].'</td>
		<td align="center">'.$metRate_['ageto'].'</td>
		<td align="right">'.duit($metRate_['upfrom']).'</td>
		<td align="right">'.duit($metRate_['upto']).'</td>
		<td align="center">'.$metRate_['type'].'</td>
	</tr>';
		}
		echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Age From"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Age To"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="UP From"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="UP To"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
	</tr>
	</tfoot>
	</table>
			</div>
		</div>
	</div>
</div>';
		;
		break;
	case "savemedical":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">New Data Uploading</h2></div>
      	<div class="page-header-section">
	</div></div>';
echo '<div class="row">
		<div class="col-md-12">';
$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id AS brokerid,
															 ajkcobroker.name AS brokername,
															 ajkcobroker.logo AS brokerlogo,
															 ajkclient.name AS clientname,
															 ajkclient.id AS clientid,
															 ajkclient.logo AS clientlogo,
															 ajkpolis.id AS polisid,
															 ajkpolis.policyauto,
															 ajkpolis.policymanual,
															 ajkpolis.typerate,
															 ajkpolis.typemedical,
															 ajkpolis.produk,
															 ajkpolis.byrate,
															 IF(ajkpolis.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkcobroker
													INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													WHERE ajkcobroker.id="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkclient.id="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkpolis.id="'.$thisEncrypter->decode($_REQUEST['idp']).'" '));
echo '<div class="row">
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Table Medical</h3></div>
				<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['brokerlogo'].'" alt="" width="65px" height="65px"></div>
					<div class="col-md-10">
						<dl class="dl-horizontal">
							<dt>Broker</dt><dd>'.$metRatePremi['brokername'].'</dd>
							<dt>Company</dt><dd>'.$metRatePremi['clientname'].'</dd>
							<dt>Product</dt><dd>'.$metRatePremi['produk'].'</dd>
							<dt>Filename</dt><dd>'.$thisEncrypter->decode($_REQUEST['fname']).'</dd>
						</dl>
					</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>
			<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>';
$metFileNameExisting = '../'.$PathTblMedical.''.$thisEncrypter->decode($_REQUEST['fname']);
$file_temp = $_SESSION['file_temp'];
$file_name = $_SESSION['file_name'];
		try {
			$inputFileType = PHPExcel_IOFactory::identify($metFileNameExisting);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($metFileNameExisting);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($metFileNameExisting, PATHINFO_BASENAME). '": ' . $e->getMessage());
		}
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		for ($row = 2; $row <= $highestRow; $row++) {
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			echo "<tr>";
			$i = 0;
			foreach($rowData[0] as $k=>$v){
				$data[$i] = $v;
				$i++;
			}
			$data1 = $data[0];		//MEDICAL
			$data2 = $data[1];		//AGE FROM
			$data3 = $data[2];		//AGE TO
			$data4 = $data[3];		//PLAFOND FROM
			$data5 = $data[4];		//PLAFOND TO

if ($metRatePremi['typemedical']=="SPK") {
	$_kodemedical = "Dokter";
}else{
	$_kodemedical = "PK";
}
$metTblMedical = $database->doQuery('INSERT INTO ajkmedical SET idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'",
																idpartner="'.$thisEncrypter->decode($_REQUEST['idc']).'",
															 	idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'",
															 	type="'.$data1.'",
															 	kode="'.$_kodemedical.'",
															 	agefrom="'.$data2.'",
															 	ageto="'.$data3.'",
															 	upfrom="'.$data4.'",
															 	upto="'.$data5.'",
															 	filename="'.$thisEncrypter->decode($_REQUEST['fname']).'",
															 	input_by="'.$q['id'].'",
															 	input_time="'.$futgl.'"');
		}
echo '	</div>
	</div>';
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=medical">
	<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<strong>Success!</strong> Upload data table medical '.$thisEncrypter->decode($_REQUEST['fname']).' by '.$q['firstname'].'.
    </div>';

		;
		break;

	case "nMedicalbtl":
		unlink('../'.$PathTblMedical.''.$thisEncrypter->decode($_REQUEST['fname']));
		header('location:ajk.php?re=medical&mcl=nMedical');
		;
		break;

	case "nMedical":
		echo '<div class="page-header-section"><h2 class="title semibold">Table Medical</h2></div>
		<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=medical">'.BTN_BACK.'</a></div>
		</div>
	</div>';
		if ($_REQUEST['met']=="savemerate") {
/*
			$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.name AS brokername,
															 ajkcobroker.logo AS brokerlogo,
															 ajkclient.name AS clientname,
															 ajkclient.logo AS clientlogo,
															 ajkpolis.policyauto,
															 ajkpolis.policymanual,
															 ajkpolis.typerate,
															 ajkpolis.byrate,
															 IF(ajkpolis.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkcobroker
													INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													WHERE ajkcobroker.id="'.$_REQUEST['coBroker'].'" AND ajkclient.id="'.$_REQUEST['coClient'].'" AND ajkpolis.id="'.$_REQUEST['coPolicy'].'" '));
			echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate</h3></div>
			<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['brokerlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
					<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metRatePremi['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metRatePremi['clientname'].'</dd>
					<dt>Policy</dt><dd>'.$metRatePremi['policyauto'].'</dd>
					<dt>Type Rate</dt><dd>'.$metRatePremi['typerate'].' by '.$metRatePremi['byrate'].'</dd>
					<dt>Percentage</dt><dd>'.$metRatePremi['calculatedrate'].'</dd>
					</dl>
				</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>
			<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>
			<table class="table table-striped table-bordered">
			<thead>';
			if ($metRatePremi['byrate']=="Age") {
				$RateKolomiAge = '<th>Age From</th>
				  <th>Age To</th>';
			}
			$FileNamRate =  $futoday.'_'.$metRatePremi['brokername'].'_'.$metRatePremi['clientname'].'_'.$_FILES['fileRate']['name'];
			$sourcefile = $_FILES['fileRate']['tmp_name'];
			$direktori = "../$PathRate$FileNamRate"; // direktori tempat menyimpan file
			$data = new Spreadsheet_Excel_Reader($sourcefile);
			$hasildata = $data->rowcount($sheet_index=0);
			echo '<tr><th width="1%">#</th>
			                '.$RateKolomiAge.'
			                <th>Tenor From (month)</th>
			                <th>Tenor To (month)</th>
			                <th>Rate</th>
			            </tr>
			            </thead>
			            <tbody>';
			for ($i=2; $i<=$hasildata; $i++)
			{
				if ($metRatePremi['byrate']=="Age") {
					$data1=$data->val($i, 1);		//NOMOR
					$data2=$data->val($i, 2);		//AGE AWAL
					$data3=$data->val($i, 3);		//AGE AKHIR
					$data4=$data->val($i, 4);		//TENOR AWAL
					$data5=$data->val($i, 5);		//TENOR AKHIR
					$data6=$data->val($i, 6);		//RATE
					if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
					if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
					if ($data4=="" OR !number_format($data4)) {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.$data4.'</td>';	}
					if ($data5=="" OR !number_format($data5)) {	$error = '<td class="text-center danger">'.$data5.'</td>';	$mamet4=$error;	}else{	$mamet4 = '<td class="text-center">'.$data5.'</td>';	}
					if ($data6=="") {	$error = '<td class="text-center danger">'.$data6.'</td>';	$mamet5=$error;	}else{	$mamet5 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data6).'</td>';	}
				}else{
					$data1=$data->val($i, 1);		//NOMOR
					$data2=$data->val($i, 2);		//TENOR AWAL
					$data3=$data->val($i, 3);		//TENOR AKHIR
					$data4=$data->val($i, 4);		//RATE
					if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
					if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
					if ($data4=="") {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data4).'</td>';	}
				}
				echo '<tr><td class="text-center">'.++$no.'</td>
                '.$mamet1.'
                '.$mamet2.'
                '.$mamet3.'
                '.$mamet4.'
                '.$mamet5.'
            </tr>';
			}
			if ($error) {
				$metValidRate = '<div class="col-md-12">
                        <div class="alert alert-danger fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <h4 class="semibold">Warning!</h4>
                        <p class="mb10">there was an error with the upload rate.</p>
                        <button type="button" class="btn btn-warning"><a href="re=ratepremi&op=nRate">Check your file and upload again !</a></button>
                        </div>
                        </div>';
			}else{
				move_uploaded_file($sourcefile,$direktori);
				echo '<input type="hidden" name="mametFileRate" value="'.$FileNamRate.'">';
				echo '<input type="hidden" name="coBroker" value="'.$_REQUEST['coBroker'].'">';
				echo '<input type="hidden" name="coClient" value="'.$_REQUEST['coClient'].'">';
				echo '<input type="hidden" name="coPolicy" value="'.$_REQUEST['coPolicy'].'">';
				echo '<input type="hidden" name="MetbyRate" value="'.$metRatePremi['byrate'].'">';
				$metValidRate = '<div class="panel-footer" align="center"><input type="hidden" name="op" value="savemeratepremi">'.BTN_SUBMIT.'</div>';
			}
			echo ''.$metValidRate.'
						</tbody>
			    		</table>
						</form>
					</div>
				</div>';
*/

$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id AS brokerid,
															 ajkcobroker.name AS brokername,
															 ajkcobroker.logo AS brokerlogo,
															 ajkclient.name AS clientname,
															 ajkclient.id AS clientid,
															 ajkclient.logo AS clientlogo,
															 ajkpolis.id AS polisid,
															 ajkpolis.policyauto,
															 ajkpolis.policymanual,
															 ajkpolis.typerate,
															 ajkpolis.produk,
															 ajkpolis.byrate,
															 IF(ajkpolis.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkcobroker
													INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													WHERE ajkcobroker.id="'.$_REQUEST['coBroker'].'" AND ajkclient.id="'.$_REQUEST['coClient'].'" AND ajkpolis.id="'.$_REQUEST['coPolicy'].'" '));
echo '<div class="row">
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Table Medical</h3></div>
			<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['brokerlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metRatePremi['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metRatePremi['clientname'].'</dd>
					<dt>Product</dt><dd>'.$metRatePremi['produk'].'</dd>
				</dl>
				</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>
		<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>
		<table class="table table-striped table-bordered">
		<thead>
		<tr><th width="1%">#</th>
			<th>Medical</th>
			<th>Age From</th>
			<th>Age To</th>
			<th>Plafond From</th>
			<th>Plafond To</th>
		</tr>
		</thead>
		<tbody>';
			$fNameUpload = 'MEDICAL_B'.$_REQUEST['coBroker'].'_C'.$_REQUEST['coClient'].'_P'.$_REQUEST['coPolicy'].'_'.$_FILES['fileRate']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
			$metFileNameExistingCek = '../'.$PathTblMedical.''.$fNameUpload;
			$fNameUploadTemp = $_FILES['fileRate']['tmp_name'];
			$namafile =  $_FILES['fileRate']['tmp_name'];
			//echo $namafile;
			$ext = pathinfo($namafile, PATHINFO_EXTENSION);
			$file_info = pathinfo($namafile);
			$file_extension = $file_info["extension"];
			$namefile = $file_info["fileRate"].'.'.$file_extension;
			$inputFileName = $namafile;
			$_SESSION['file_temp'] = $namefile;
			$_SESSION['fileRate'] = $_FILES['fileRate']['name'];
			//  Read your Excel workbook
			try {
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());
			}
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
for ($row = 2; $row <= $highestRow; $row++) {
$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
echo "<tr>";
	$i = 0;
	foreach($rowData[0] as $k=>$v){
	$data[$i] = $v;
	$i++;
	}
$data1 = $data[0];		//MEDICAL
if ($data1=="") {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = strtoupper($data1);	}

$data2 = $data[1];		//AGE FROM
if ($data2=="") {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = duit($data2);	}

$data3 = $data[2];		//AGE TO
if ($data3=="") {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = duit($data3);	}

$data4 = $data[3];		//PLAFOND FROM
if ($data4=="") {	$ErrorEXL4 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = duit($data4);	}

$data5 = $data[4];		//PLAFOND TO
if ($data5=="") {	$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;	}else{	$dataEXL5 = duit($data5);	}


echo '<td>'.++$no.'</td>
	<td align="center">'.$dataEXL1.'</td>
	<td align="center">'.$dataEXL2.'</td>
	<td align="center">'.$dataEXL3.'</td>
	<td align="center">'.$dataEXL4.'</td>
	<td align="center">'.$dataEXL5.'</td>
	</tr>';
}
if ($ErrorEXL1 OR $ErrorEXL2 OR $ErrorEXL3 OR $ErrorEXL4 OR $ErrorEXL5) {
echo '<div align="center" class="col-md-12"><a href="ajk.php?re=exsist&exs=Xls2">'.BTN_UPLOADERROR.'</a></div>';
}else{
	$direktori = '../'.$PathTblMedical.'/'.$fNameUpload;
	move_uploaded_file($fNameUploadTemp,$direktori);
	echo '<div align="right" class="col-md-6"><a href="ajk.php?re=medical&mcl=nMedicalbtl&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_BACK2.'</a></div>
		<div class="col-md-6"><a href="ajk.php?re=medical&mcl=savemedical&idb='.$thisEncrypter->encode($metRatePremi['brokerid']).'&idc='.$thisEncrypter->encode($metRatePremi['clientid']).'&idp='.$thisEncrypter->encode($metRatePremi['polisid']).'&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_SUBMITMEDICAL.'</a></div>';
}
echo '</tbody>
		</table>
		</form>
	</div>
</div>';
		}
		else{
			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			echo '<div class="row">
					<div class="col-md-12">
						<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
						<div class="panel-heading"><h3 class="panel-title">Upload New Table Medical</h3></div>
						<div class="panel-body">
							<div class="form-group">
							<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
							<div class="col-sm-10">
							<select name="coBroker" class="form-control" onChange="mametBrokerMedical(this);" required>
					            		<option value="">Select Broker</option>';
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
				echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
			}
			echo '</select>
						    </div>
						    </div>
							<div class="form-group">
							<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
							<div class="col-sm-10">
							<select name="coClient" class="form-control" id="coClient" onChange="mametClientMedical(this);" required>
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
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">File Upload<span class="text-danger">*</span></label>
			                <div class="col-sm-10"><input type="file" name="fileRate" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></div>
						</div>';
			echo '	</div>
				<div class="panel-footer"><input type="hidden" name="met" value="savemerate">'.BTN_SUBMIT.'</div>
				</form>
			</div>
			</div>';
		}
		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;
	default:
echo '<div class="page-header-section"><h2 class="title semibold">Table Medical</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=medical&mcl=nMedical">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="row">
      	<div class="col-md-12">
	       	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th>Company</th>
		<th width="10%">Product</th>
		<th width="10%">Type</th>
		<th width="8%">Age From</th>
		<th width="8%">Age To</th>
		<th width="10%">Plafond From</th>
		<th width="10%">Plafond To</th>
		<th width="8%">Option</th>
		<th width="8%">Status</th>
			</tr>
	</thead>
<tbody>';
$metClient = $database->doQuery('SELECT ajkmedical.idbroker,
										ajkmedical.idpartner,
										ajkmedical.idproduk,
										ajkmedical.type,
										ajkmedical.agefrom,
										ajkmedical.ageto,
										ajkmedical.upfrom,
										ajkmedical.upto,
										ajkmedical.status,
										ajkmedical.input_time,
										ajkclient.name,
										ajkpolis.produk
										FROM ajkmedical
										INNER JOIN ajkclient ON ajkmedical.idpartner = ajkclient.id
										INNER JOIN ajkpolis ON ajkmedical.idproduk = ajkpolis.id
										WHERE ajkmedical.del IS NULL '.$q___.'
										GROUP BY ajkmedical.idproduk, ajkmedical.input_time
										ORDER BY ajkpolis.produk DESC');
		while ($metClient_ = mysql_fetch_array($metClient)) {
			if ($metClient_['status']=="Aktif") {
				$ratestatus='<span class="badge badge-primary">'.$metClient_['status'].'</span>';
			}else{
				$ratestatus='<span class="badge badge-danger">'.$metClient_['status'].'</span>';
			}
			echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metClient_['name'].'</td>
		<td>'.$metClient_['produk'].'</td>
		<td>'.$metClient_['type'].'</td>
		<td align="center">'.$metClient_['agefrom'].'</td>
		<td align="center">'.$metClient_['ageto'].'</td>
		<td align="center">'.$metClient_['upfrom'].'</td>
		<td align="center">'.$metClient_['upto'].'</td>
		<td align="center">'.$ratestatus.'</td>
		<td align="center"><a href="ajk.php?re=medical&mcl=viewmedical&idb='.$thisEncrypter->encode($metClient_['idbroker']).'&idc='.$thisEncrypter->encode($metClient_['idpartner']).'&idp='.$thisEncrypter->encode($metClient_['idproduk']).'&time='.$thisEncrypter->encode($metClient_['input_time']).'">'.BTN_VIEW.'</a></td>
	</tr>';
		}
		echo '</tbody>
		<tfoot>
		<tr>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		</tr>
		</tfoot></table>
			</div>
		</div>
	</div>
</div>';
		;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>