<?php
include "../param.php";
$myspk = AES::decrypt128CBC($_REQUEST['metspk'],ENCRYPTION_KEY);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user,$namauser,$photo,$logo);
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
		_header($user,$namauser,$photo,$logo,$logoklient);
		_sidebar($user,$namauser,'','');
		?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
				<h4 class="m-t-0">Upload Data Member SPK</h4>
				<div class="section-container section-with-top-border">
<?php
if ($myspk == "spkmember") {
	$today = date('Y-m-d H:i:s');
	$idpolis = $_SESSION['polis'];
	$file_temp = $_SESSION['file_temp'];
	$file_name = $_SESSION['file_name'];

	$inputFileName = 'temp/'.$file_temp;
	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = '../myFiles/_uploaddata/'.$foldername;

	$querysup = mysql_query("SELECT * FROM  useraccess WHERE id = '".$idsupervisor."'");
	$rowsup = mysql_fetch_array($querysup);
	$namasup = $rowsup['firstname'].' '.$rowsup['lastname'];
	$emailsup = $rowsup['email'];

	if($gender=="L"){
		$gen= 'Bpk';
	}else{
		$gen= 'Ibu';
	}
	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}



	try {
	    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch (Exception $e) {	die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());	}

	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	$newfilename = date('ymd_his').'_'.$file_name;

	copy($inputFileName, $path.'/'.$newfilename) or die( "Could not upload file!");
	//  Loop through each row of the worksheet in turn
	$qpolis__ = mysql_fetch_array(mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND id = '".$idpolis."'"));
	$levelval = $qpolis__['levelvalidasi'];
	for ($row = 7; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		$i = 0;
		foreach($rowData[0] as $v){
			$data[$i] = $v;
			$i++;
		}
		//$no 		= $data[0];
		$cabang 	= $data[0];
		$spk 		= $data[1];
		$nama 		= $data[2];
		$gender 	= $data[3];
		$ktp 		= $data[4];
		$npk 		= $data[5];
		$tgllahirdd = $data[6];
		$tgllahirmm = $data[7];
		$tgllahiryy = $data[8];
		$tglakaddd 	= $data[9];
		$tglakadmm 	= $data[10];
		$tglakadyy 	= $data[11];
		$tenor 		= $data[12];
		$graceperiod= $data[13];
		$plafon 	= str_replace($_separatorsNumb,$_separatorsNumb_,$data[14]);
		$expremi 	= $data[15];
		$keterangan = $data[16];
		$paket 		= $data[17];

		//TANGGALLAHIR
		if ($data[6] <=9) {	$tgllahirdd = '0'.$data[6];	}else{	$tgllahirdd = $data[6];	}
		if ($data[7] <=9) {	$tgllahirmm = '0'.$data[7];	}else{	$tgllahirmm = $data[7];	}
		$tgllahir = $tgllahiryy.'-'.$tgllahirmm.'-'.$tgllahirdd;
		//TANGGALLAHIR

		//TANGGALAKAD
		if ($data[9] <=9) 	{	$tglakaddd = '0'.$data[9];	}else{	$tglakaddd = $data[9];	}
		if ($data[10] <=9) 	{	$tglakadmm = '0'.$data[10];	}else{	$tglakadmm = $data[10];	}
		$tglakad = $tglakadyy.'-'.$tglakadmm.'-'.$tglakaddd;
		//TANGGALAKAD
		$usia = birthday($tgllahir,$tglakad);
		$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -".$qpolis__['lastdayinsurance']." Day"));
		if($levelval==3){
			$statusaktif = 'Upload';
		}elseif($levelval==2){
			$statusaktif = 'Pending';
		}

		$qcabang = mysql_query("SELECT * FROM ajkcabang WHERE name = UPPER('".$cabang."') AND idclient = '".$idclient."'");
		$rcabang = mysql_fetch_array($qcabang);
		$idcabang  = $rcabang['er'];
		$idregional  = $rcabang['idreg'];

		if ($qpolis__['mpptype']=="Y") {
			//ratempp
		}else{
			if ($qpolis__['byrate']=="Age") {
				$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'"));
			}else{
				$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'"));
			}
		}
		$metSPK = mysql_fetch_array(mysql_query('SELECT id, nomorspk, premiem FROM ajkspk WHERE nomorspk="'.$spk.'"'));
		$extpremi = $metSPK['premiem'];
		$premiX = round($plafon * $qrate['rate'] / $qpolis__['calculatedrate']);
		$discountpremi = $premiX * $qpolis__['diskon']/100;
		$totalpremi = $premiX - $discountpremi + $extpremi + $qpolis__['adminfee'];

		$metmedical = mysql_fetch_array(mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafon.' BETWEEN upfrom AND upto AND del IS NULL'));

		$metInsSPK = mysql_query('INSERT INTO ajkpeserta_temp SET idbroker="'.$idbro.'",
																  idclient="'.$idclient.'",
																  idpolicy="'.$idpolis.'",
																  filename="'.$newfilename.'",
																  gender="'.$gender.'",
													  			  nomorktp="'.$ktp.'",
													  			  nomorpk="'.$npk.'",
													  			  nomorspk="'.$spk.'",
													  			  nama="'.strtoupper(trim($nama)).'",
													  			  tgllahir="'.$tgllahir.'",
													  			  usia="'.$usia.'",
													  			  plafond="'.$plafon.'",
													  			  tglakad="'.$tglakad.'",
													  			  tenor="'.$tenor.'",
													  			  tglakhir="'.$tglakhir.'",
													  			  statusaktif="'.$statusaktif.'",
													  			  cabang="'.$idcabang.'",
													  			  regional="'.$idregional.'",
													  			  premirateid="'.$qrate['id'].'",
													  			  premirate="'.$qrate['rate'].'",
													  			  premi="'.$premiX.'",
													  			  diskonpremi="'.$discountpremi.'",
													  			  biayaadmin="'.$qpolis__['adminfee'].'",
													  			  extrapremi = "'.$metSPK['premiem'].'",
																  totalpremi="'.$totalpremi.'",
													  			  medical="'.$metmedical['type'].'",
																  input_by="'.$iduser.'",
																  input_time="'.$today.'"');
		$ls_body_data .= '<tr>
						<td>'.strtoupper(trim($nama)).'</td>
						<td>'.strtoupper($cabang).'</td>
						<td>'.$ktp.'</td>
						<td>'.$npk.'</td>
						<td align="center">'.$tgllahir.'</td>
						<td align="center">'.$usia.'</td>
						<td align="center">'.$tglakad.'</td>
						<td align="center">'.$tglakhir.'</td>
						<td align="center">'.$tenor.'</td>
						<td align="right">'.duit($plafon).'</td>
						<td align="center">'.$qrate['rate'].'</td>
					 	<td align="right">'.duit($totalpremi).'</td>
					</tr>';
	}
	$ls_body = '
<div class=msg>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="27"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="10"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left"><b>Upload Data Member</b></span>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="2"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td align="right" valign="bottom">&nbsp;</td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="20"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#454545"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#444444"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#434343"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#414141"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#404040"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3f3f3f"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3e3e3e"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="4"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="70"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="40"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="19"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="14"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">Dear '.$namasup.'</div>
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table width="100%" border="1" cellspacing="0" cellpadding="0">
                                                                <thead >
																	<tr bgcolor="#4CB7EF" style="color:#fff;text-decoration:none" rel="noreferrer">
																		<th>Nama Tertanggung </th>
																		<th>Cabang</th>
																		<th>Nomor KTP</th>
																		<th>Nomor PK</th>
																		<th class="text-center">Tanggal Lahir</th>
																		<th class="text-center">Usia</th>
																		<th class="text-center">Tanggal Akad</th>
																		<th class="text-center">Tanggal Akhir</th>
																		<th class="text-center">Tenor (bulan)</th>
																		<th class="text-center">Nilai Pertanggungan (Plafond)</th>
																		<th class="text-center">Rate</th>
																		<th class="text-center">Premi</th>

																	</tr>

																</thead><tbody>
	'.$ls_body_data.'
</tbody>
			</table>
			</tr></tbody></table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
            <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">Salam </br> '.$namauser.'</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                   <tr>
                      <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                </tr>
                </tbody>
             </table>

                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>

                                                        <td width="560">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="25">
                                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left">
                                                                                <b>'.$namebro.'</b> '.$alamat.'
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
</tr></tbody></table></div>';
	$ls_toemail = $emailsup;
	$ls_toname = $namasup;
	$ls_subject = "[App Credit Life Insurance] Upload Data Peserta";
	$ls_countemail = 1;
	$ls_fromname = $namauser;
	$ls_fromemail = $emailuser;
	$ls_ccname = '';
	$ls_ccmail = '';
	$li_countcc = 0;
	//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);

/*
	echo $ls_fromname.'<br />';
	echo $ls_fromemail.'<br />';
	echo $ls_toname.'<br />';
	echo $ls_toemail.'<br />';
	echo $ls_countemail.'<br />';
	echo $ls_ccname.'<br />';
	echo $ls_ccmail.'<br />';
	echo $li_countcc.'<br />';
	echo $ls_subject.'<br />';
	echo$ls_body;
*/
echo '<!--<meta http-equiv="refresh" content="3; url=../upload?xq='.AES::encrypt128CBC('uploadspk',ENCRYPTION_KEY).'">-->
		<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Success!</strong> Deklarasi data SPK telah diupload dengan status <strong>'.$statusaktif.'</strong>.
        </div>';
}else{

}
?>
				    <form action="uploadmemberspk.php?metspk=<?php echo AES::encrypt128CBC('spkmember',ENCRYPTION_KEY); ?>" id="form-upload" name="form-upload" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <?php


	                    if(isset($_FILES['fileupload']['name'])){
	                    	$idpolis = $_REQUEST['namaproduk'];
	                    	$_SESSION['polis'] = $idpolis;
							$file_name = $_FILES['fileupload']['name'];

							$ext = pathinfo($file_name, PATHINFO_EXTENSION);

								$file_name = $_FILES['fileupload']['tmp_name'];
	                    		$file_info = pathinfo($file_name);
	                    		$file_extension = $file_info["extension"];
	                    		$namefile = $file_info["filename"].'.'.$file_extension;
								$inputFileName = $file_name;
		                    	$_SESSION['file_temp'] = $namefile;
		                    	$_SESSION['file_name'] = $_FILES['fileupload']['name'];
								//  Read your Excel workbook
								try {
								    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
									$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
									$objReader = PHPExcel_IOFactory::createReader($inputFileType);
									$objPHPExcel = $objReader->load($inputFileName);
								} catch (Exception $e) {
									die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
									. '": ' . $e->getMessage());
								}



								//Table used to display the contents of the file
								echo '<div class="panel-body">
										<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">';

								//  Get worksheet dimensions
								$sheet = $objPHPExcel->getSheet(0);
								$highestRow = $sheet->getHighestRow();
								$highestColumn = $sheet->getHighestColumn();

   	//CEK TIPE PRODUK GENERAL
$qpolis__ = mysql_fetch_array(mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND id = '".$idpolis."'"));
	if ($qpolis__['general']=="Y") {
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

									echo '<thead >
												<tr class="primary">
													<th>No</th>
													<th>Cabang</th>
													<th>Nomor SPK</th>
													<th>Nama Tertanggung <span class="text-danger">*</span></th>
													<th>Nomor KTP</th>
													<th>Nomor PK</th>
													<th class="text-center">Tanggal Lahir</th>
													<th class="text-center">Usia</th>
													<th class="text-center">Tanggal Akad</th>
													<th class="text-center">Tanggal Akhir</th>
													<th class="text-center">Tenor (bulan)</th>
													'.$_metGeneral.'
													<th class="text-center">Nilai Pertanggungan (Plafond)</th>
													<th class="text-center">Rate</th>
													<th class="text-center">Premi</th>
													<th class="text-center">EM(%)</th>
													<th class="text-center">Premi EM</th>
													<th class="text-center">Total Premi</th>
													'.$_metGeneralPremi.'
													<th class="text-center">Medical</th>
												</tr>

											</thead><tbody>';
								//  Loop through each row of the worksheet in turn
								for ($row = 7; $row <= $highestRow; $row++) {
									//  Read a row of data into an array
									$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
									NULL, TRUE, FALSE);
									echo "<tr>";
									$i = 0;
									foreach($rowData[0] as $k=>$v){
										$data[$i] = $v;
										$i++;
									}
									$today = date('Y-m-d');
									$no 		= $data[0];
									$cabang 	= $data[1];
									$spk 		= $data[2];
									$nama 		= $data[3];
									$gender 	= $data[4];
									$ktp 		= $data[5];
									$npk 		= $data[6];
									$tgllahirdd = $data[7];
									$tgllahirmm = $data[8];
									$tgllahiryy = $data[9];
									$tglakaddd 	= $data[10];
									$tglakadmm 	= $data[11];
									$tglakadyy 	= $data[12];
									$tenor 		= $data[13];
									$graceperiod= $data[14];
									$plafon 	= str_replace($_separatorsNumb,$_separatorsNumb_,$data[15]);
									$expremi 	= $data[16];
									$keterangan = $data[17];
									$paket 		= $data[18];

//DOB//
if ($data[7] <=9) {	$tgllahirdd = '0'.$data[7];	}else{	$tgllahirdd = $data[7];	}
if ($data[8] <=9) {	$tgllahirmm = '0'.$data[8];	}else{	$tgllahirmm = $data[8];	}
$tgllahir = $tgllahiryy.'-'.$tgllahirmm.'-'.$tgllahirdd;
if(isValidDate($tgllahir)){
	$tgllahir = _convertDate($tgllahir);
	$errortgllahir = null;
}else{
	$errortgllahir = '<span class="label label-danger">Tanggal tidak sesuai</span>';
}
//DOB//

//TGL AKAD//
if ($data[10] <=9) {	$tglakaddd = '0'.$data[10];	}else{	$tglakaddd = $data[10];	}
if ($data[11] <=9) {	$tglakadmm = '0'.$data[11];	}else{	$tglakadmm = $data[11];	}
$tglakad = $tglakadyy.'-'.$tglakadmm.'-'.$tglakaddd;
if(isValidDate($tglakad)){
	$tglakad = _convertDate($tglakad);
	$usia = birthday($tgllahir,$tglakad);
	$errortglakad = null;
}else{
	$errortglakad = '<span class="label label-danger">Tanggal tidak sesuai</span>';
}
//TGL AKAD//

//CEK USIA//
if($errortgllahir==null AND $errortglakad==null){
$usia = birthday($tgllahir,$tglakad);
$tglakhir = _convertDate(Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -".$qpolis__['lastdayinsurance']." Day")));
}else{
$usia = '0';
}
//CEK USIA//

//CEK DATA SPK
$cekSPKTemp = mysql_fetch_array(mysql_query('SELECT * FROM ajkspk WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND nomorspk="'.$spk.'" AND statusspk="Aktif" AND cabang="'.$rowuser['branch'].'" AND del IS NULL'));
if ($cekSPKTemp['id'] AND $cekSPKTemp['nama'] == strtoupper($nama) AND $cekSPKTemp['dob'] == _convertDate($tgllahir) AND $cekSPKTemp['plafond'] == $plafon AND $cekSPKTemp['nomorktp'] == $ktp ) {
	$errorspk = null;
	$EMdekl = $cekSPKTemp['em'];
}elseif ($cekSPKTemp['id'] AND $cekSPKTemp['nama'] != strtoupper($nama)) {
	$errorspk = '<span class="label label-danger">Nama tidak sesuai</span>';
}elseif ($cekSPKTemp['id'] AND $cekSPKTemp['dob'] != _convertDate($tgllahir)) {
	$errorspk = '<span class="label label-danger">Tanggal lahir tidak sesuai</span>';
}elseif ($cekSPKTemp['id'] AND $cekSPKTemp['plafond'] != $plafon) {
	$errorspk = '<span class="label label-danger">Plafond tidak sesuai</span>';
}elseif ($cekSPKTemp['id'] AND $cekSPKTemp['nomorktp'] != $ktp) {
	$errorspk = '<span class="label label-danger">Nomor KTP tidak sesuai</span>';
}else{
	$errorspk = '<span class="label label-danger">Nomor SPK tidak ada</span>';
}
//CEK DATA SPK

//CEK SUAI DAN PLAFOND SETUP
if ($usia >= $qpolis__['agestart'] AND $usia <= $qpolis__['ageend']) {
	$errorusia = null;
}else{
	$errorusia = '<span class="label label-danger">Usia tidak sesuai produk setup</span>';
}

if ($plafon >= $qpolis__['plafondstart'] AND $plafon <= $qpolis__['plafondend']) {
	$errorplafond = null;
}else{
	$errorplafond = '<span class="label label-danger">Plafond tidak sesuai produk setup</span>';
}
//CEK SUAI DAN PLAFOND SETUP

//CEKRATE//
if($errorspk==null AND $errortgllahir==null AND $errortglakad==null AND $errorusia==null AND $errorplafond==null){
	if ($qpolis__['mpptype']=="Y") {
	//ratempp
	}else{
		if ($qpolis__['byrate']=="Age") {
			$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'"));
		}else{
			$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'"));
		}
	}
}else{

}
if ($qrate['rate']) {
	$myRate = $qrate['rate'];
	$premiX = round($plafon * $qrate['rate'] / $qpolis__['calculatedrate']);
	$premiX_EM = $premiX * $EMdekl / 100;
	$premiXnett = $premiX + $premiX_EM;
	$errorrate = null;
	//CEK MEDICAL
	$metmedical = mysql_fetch_array(mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafon.' BETWEEN upfrom AND upto AND del IS NULL'));
	$dataMedical = '<span class="label label-primary">'.$metmedical['type'].'</span>';
	//CEK MEDICAL
}else{
	$premiX = '';
	$errorrate = '<span class="label label-danger">Rate tidak ada</span>';
}
//CEKRATE//


		if ($rpolis['general']=="Y") {
			$data14 = $data[17];	//PAKET
			$data15 = $data[18];	//QUARANTEE
			$data16 = $data[19];	//KELAS
			$data17 = $data[20];	//LOKASI
			$data18 = $data[21];	//HARGA PASAR
			$_rowMetGeneral = '
				<td align="center">'.$data14.'</td>
				<td align="center">'.$data15.'</td>
				<td align="center">'.$data16.'</td>
				<td align="center">'.$data17.'</td>
				<td align="center">'.$data18.'</td>';
			$metRateGeneral = mysql_fetch_array(mysql_query('SELECT * FROM ajkrategeneral WHERE idbroker="'.$idbro.'" AND
																									   idclient="'.$idclient.'" AND
																									   idproduk="'.$idpolis.'" AND
																									   '.$tenor.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$data17.'" AND
																									   quarantee = "'.$data15.'" AND
																									   kelas = "'.$data16.'" AND
																									   status="Aktif"'));
		$metPremiGenFire = ($data18 * $metRateGeneral['ratefire']) / $rpolis['calculatedrate'];
		$metPremiGenPA = ($data18 * $metRateGeneral['ratepa']) / $rpolis['calculatedrate'];
		$_rowmetGeneralPremi = '<td>'.$metRateGeneral['ratepa'].'</td>
								<td>'.$metPremiGenPA.'</td>
								<td>'.$metRateGeneral['ratefire'].'</td>
								<td>'.$metPremiGenFire.'</td>';
		}else{
			$_rowMetGeneral = '';
			$_rowmetGeneralPremi = '';
		}
										echo "<td>".$no." </td>";
										echo "<td>".$cabang." $errorcab</td>";
										echo "<td>".$spk." $errorspk</td>";
										echo "<td>".$nama." $errornama</td>";
										echo "<td>".$ktp." $errorktp</td>";
										echo "<td>".$npk." $errornpk</td>";
										echo "<td>".$tgllahir."$errortgllahir</td>";
										echo "<td>".$usia."$errorusia</td>";
										echo "<td>".$tglakad."</td>";
										echo "<td>".$tglakhir."</td>";
										echo "<td>".$tenor."</td>";
										echo "<td class='text-right'>".number_format($plafon,0,".",",")."$errorplafond</td>";
										echo "$_rowMetGeneral";
										echo "<td class='text-right'>".$myRate."$errorrate</td>";
										echo "<td class='text-right'>".duit($premiX)."$errorpremi</td>";
										echo "<td class='text-right'>".$EMdekl."</td>";
										echo "<td class='text-right'>".duit($premiX_EM)."</td>";
										echo "<td class='text-right'>".duit($premiXnett)."</td>";
										echo "$_rowmetGeneralPremi";
										echo "<td class='text-right'>".$dataMedical."</td>";

									echo "</tr>";
								}
	                    		if($errorspk==null AND $errortgllahir==null AND $errortglakad==null AND $errorrate==null AND $errorusia==null AND $errorplafond==null){
	                    			move_uploaded_file($file_name,'temp/'.$namefile) or die( "Could not upload file!");
	                    			$disabledbtn = '';
	                    		}else{
	                    			$disabledbtn = 'disabled';
	                    		}
								echo '</tbody></table>
									<div class="form-group m-b-0">
				                        <label class="control-label col-sm-12"></label>
				                        <div class="col-sm-6">
				                            <input type="submit" name="sub" class="btn btn-success width-xs" value="Submit" '.$disabledbtn.'>
				                            <a href="../upload?xq='.AES::encrypt128CBC($_REQUEST['xq'],ENCRYPTION_KEY).'" class="btn btn-danger width-xs">Cancel</a>
				                        </div>
				                    </div></div></form>';
						}
						?>
	            </div>
	            <!-- end section-container -->
	        </div>
            <?php
            _footer();
            ?>
		</div>
		<!-- end #content -->
	</div>
	<!-- end page container -->

	<?php
	_javascript();
	?>

	<script>
		$(document).ready(function() {
		    App.init();
		    Demo.init();

			$(".active").removeClass("active");
			//$(".open").removeClass("open");
			$(".active").removeClass("active");
			document.getElementById("has_input").classList.add("active");
			document.getElementById("idhas_input").classList.add("active");
			document.getElementById("idsub_databaru_spk").classList.add("active");
				$("#table-upload").DataTable({
					responsive: true
				})

		});

		$("#data-pesertatemp").DataTable({	responsive: true	});

	</script>
</body>
</html>
