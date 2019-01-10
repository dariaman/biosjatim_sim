<?php
include "../param.php";
include_once "../includes/functions.php";
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
$idpolis = $_REQUEST['namaproduk'];
$metproduk = mysql_fetch_array(mysql_query('SELECT * FROM ajkpolis WHERE id="'.$idpolis.'"'));
?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
			    <h4 class="m-t-0">Upload Data Refund Member</h4>
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Produk : <?php echo $metproduk['produk'];	?> </h4>
					<form action="douploadrefund.php" id="form-upload" name="form-upload" class="form-horizontal" method="post" enctype="multipart/form-data">
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
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch (Exception $e) {
		die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
		. '": ' . $e->getMessage());
	}

	//Table used to display the contents of the file
	echo '<input type="hidden" name="qtype" value="'.$_REQUEST['qtype'].'">';
	echo '<div class="panel-body">
			<table class="table table-bordered table-hover" id="table-upload"  width="100%">';

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
		$no = $data[0];
		$cabang = $data[1];
		$nama = $data[2];
		$gender = $data[3];
		$ktp = $data[4];
		$npk = $data[5];
		$tgllahirdd = $data[6];
		$tgllahirmm = $data[7];
		$tgllahiryy = $data[8];
		if ($data[6] <=9) {	$tgllahirdd = '0'.$data[6];	}else{	$tgllahirdd = $data[6];	}
		if ($data[7] <=9) {	$tgllahirmm = '0'.$data[7];	}else{	$tgllahirmm = $data[7];	}
		$tgllahir = $tgllahiryy.'-'.$tgllahirmm.'-'.$tgllahirdd;
		//$tgllahir = $tgllahiryy.'-'.$tgllahirmm.'-'.$tgllahirdd;
		//$tgllahir = date('Y-m-d', strtotime($tgllahir));
		$usia = birthday($tgllahir,$today);
		$tglakaddd = $data[9];
		$tglakadmm = $data[10];
		$tglakadyy = $data[11];
		$tglakad = $tglakadyy.'-'.$tglakadmm.'-'.$tglakaddd;
		$tglakad = date('Y-m-d', strtotime($tglakad));
		$tenor = $data[12];
		$graceperiod = $data[13];
		//$plafon = str_replace(".","" , $data[14]);
		$plafon = str_replace($_separatorsNumb,$_separatorsNumb_,$data[14]);
		$expremi = $data[15];
		$keterangan = $data[16];
		$paket = $data[17];
		$qpolis = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND id = '".$idpolis."'");
		$rpolis = mysql_fetch_array($qpolis);
		$levelval = $rpolis['levelvalidasi'];
		$lastdayinsurance = $rpolis['lastdayinsurance'];
		$ageend = $rpolis['ageend'];
		$agemin = $rpolis['agestart'];
		$byrate = $rpolis['byrate'];
		$calculaterate = $rpolis['calculatedrate'];
		$adminfee = $rpolis['adminfee'];
		$diskon = $rpolis['diskon'];
		if($lastdayinsurance=1){
			$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -1 Day"));
		}else{
			$tglakhir = $tglakad;
		}
		if($levelval==3){
			$statusaktif = 'Upload';
		}elseif($levelval==2){
			$statusaktif = 'Pending';
		}
		$qcabang = mysql_query("SELECT * FROM ajkcabang WHERE name = UPPER('".$cabang."') AND idclient = '".$idclient."'");
		$rcabang = mysql_fetch_array($qcabang);
		$idcabang  = $rcabang['er'];
		$idregional  = $rcabang['idreg'];
		$ceknamacabang  = strtoupper($rcabang['name']);
		if($ceknamacabang != $cabang){
			$errorcab = '<span class="label label-danger">Nama cabang tidak ada</span>';
		}else{
			$errorcab = null;
		}
		if($branchid !='1'){
			if($namacabang != $ceknamacabang){
				$errorcab = '<span class="label label-danger">Nama cabang tidak sesuai</span>';
			}else{
				$errorcab = null;
			}
		}
		//DATA DOUBLE TEMP
		$cekDOubleTemp = mysql_fetch_array(mysql_query('SELECT * FROM ajkpeserta_temp WHERE idbroker="'.$idbro.'" AND idclient="'.$idclient.'" AND idpolicy="'.$idpolis.'" AND nomorktp="'.$ktp.'" AND tiperefund IS NOT NULL'));
		if ($cekDOubleTemp['id']) {
			$errorrefund = '<span class="label label-danger">Double data refund</span>';
		}else{
			$errorrefund = null;
		}
		//DATA DOUBLE TEMP

		$cekDataLama = mysql_fetch_array(mysql_query('SELECT * FROM ajkpeserta WHERE idbroker="'.$idbro.'" AND idclient="'.$idclient.'" AND idpolicy="'.$idpolis.'" AND nomorktp="'.$ktp.'" AND statusaktif="Inforce"'));
		if ($cekDataLama['id']) {
			if (strtoupper($cekDataLama['nama']) != strtoupper($nama)) {
				$errornama = '<span class="label label-danger">Nama tidak sesuai dengan nomor KTP</span>';
			}elseif ($cekDataLama['tgllahir'] != $tgllahir) {
				$errortgllahir = '<span class="label label-danger">Tanggal lahir tidak sesuai dengan nomor KTP</span>';
			}elseif ($cekDataLama['cabang'] != $idcabang) {
				$errorcab = '<span class="label label-danger">Nama cabang dengan nomor KTP</span>';
			}else{
				$errorcab =null;
				$errornama =null;
				$errortgllahir =null;
			}

			//DATA MEDICAL
			if ($rpolis['freecover']=="Y") {
				$querymedical = mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafon.' BETWEEN upfrom AND upto AND del IS NULL');
				$rowmedical = mysql_fetch_array($querymedical);
				$typemedical = $rowmedical['type'];
				if (!$typemedical) {
					$dataMedical = '<span class="label label-danger">Medical tidak sesuai</span>';
				}
				else
				{
					if ($typemedical == "FCL" OR $typemedical == "NM") {
						$dataMedical = '<span class="label label-primary">'.$typemedical.'</span>';
					}else{
						$dataMedical = '<span class="label label-danger">'.$typemedical.'</span>';
					}
				}
			}else{
				$dataMedical = '<span class="label label-primary">FCL</span>';
			}
			//DATA MEDICAL
			if($lastdayinsurance=1){
				$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -1 Day"));
			}else{
				$tglakhir = $tglakad;
			}
			if($usia >= $ageend){
				$errorusia = '<span class="label label-danger">Usia diluar batas maksimum usia '.$ageend.' thn</span>';
			}else{
				$errorusia = null;
			}
			if($usia <= $agemin){
				$errorusia = '<span class="label label-danger">Usia terlalu muda minimum usia '.$agemin.' thn</span>';
			}else{
				$errorusia = null;
			}
			if($byrate=="Age"){
				$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
			}else{
				$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
			}

			$rrate = mysql_fetch_array($qrate);
			$rate = $rrate['rate'];
			$premi = ($plafon * $rate) / $calculaterate;
			if($rate==0 OR $rate==""){
				$errorrate = '<span class="label label-danger">Rate tidak ditemukan</span>';
			}else{
				$errorrate = null;
			}
			if($premi==""){
				$errorpremi = '<span class="label label-danger">Error premi</span>';
			}else{
				$errorpremi = null;
			}

			if(isValidDate($tgllahir)){
				$tgllahir = _convertDate($tgllahir);
				$errortgllahir = null;
			}else{
				$errortgllahir = '<span class="label label-danger">Tanggal tidak sesuai</span>';
			}
			$debitnote = mysql_fetch_array(mysql_query('SELECT * FROM ajkdebitnote WHERE id="'.$cekDataLama['iddn'].'"'));
			$nomordebitnote = '<span class="label label-primary">'.$debitnote['nomordebitnote'].'</span>';
			echo "<td>".$no." </td>";
			echo "<td>".$cabang."<br />$errorcab</td>";
			echo "<td>".$nama."<br />$errornama $nomordebitnote $errorrefund</td>";
			echo "<td>".$ktp." </td>";
			echo "<td>".$npk." </td>";
			echo "<td>".$tgllahir."<br />$errortgllahir</td>";
			echo "<td>".$usia."<br />$errorusia</td>";
			echo "<td>".$tglakad."</td>";
			echo "<td>".$tglakhir."</td>";
			echo "<td>".$tenor."</td>";
			echo "<td class='text-right'>".number_format($plafon,0,".",",")."</td>";
			echo "<td class='text-right'>".$rate."<br />$errorrate</td>";
			echo "<td class='text-right'>".number_format($premi,0,".",",")."<br />$errorpremi</td>";
			echo "<td class='text-right'>".$dataMedical."</td>";
		}else{
			$errorktp = '<span class="label label-danger">Nomor KTP tidak ada</span>';
			echo "<td>".$no." </td>";
			echo "<td>".$cabang."</td>";
			echo "<td>".$nama."<br />$errorrefund</td>";
			echo "<td>".$ktp."<br />$errorktp</td>";
			echo "<td>".$npk."</td>";
			echo "<td>".$tgllahir."</td>";
			echo "<td>".$usia."</td>";
			echo "<td>".$tglakad."</td>";
			echo "<td>".$tglakhir."</td>";
			echo "<td>".$tenor."</td>";
			echo "<td class='text-right'>".number_format($plafon,0,".",",")."</td>";
			echo "<td class='text-right'>".$rate."</td>";
			echo "<td class='text-right'>".number_format($premi,0,".",",")."</td>";
			echo "<td class='text-right'>".$dataMedical."</td>";
		}
		echo "</tr>";
	}
	if($errornama==null AND $errorcab ==null AND $errorktp ==null AND $errorusia ==null AND $errorrate ==null AND $errorpremi ==null AND $errortgllahir ==null AND $errorrefund==null){
		move_uploaded_file($file_name,'temp/'.$namefile) or die( "Could not upload file!");
		$disabledbtn = '';
	}else{
		$disabledbtn = 'disabled';
	}
	echo '</tbody></table>
			<div class="form-group m-b-0">
			<label class="control-label col-sm-12"></label>
				<div class="col-sm-6"><input type="submit" name="sub" class="btn btn-success width-xs" value="Submit" '.$disabledbtn.'>
				                    <a href="../klaim?type='.AES::encrypt128CBC('klaimRefund',ENCRYPTION_KEY).'" class="btn btn-danger width-xs">Cancel</a>
				</div>
			</div>
		</div></form>';
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
			document.getElementById("has_upload").classList.add("active");
			document.getElementById("idsub_upload").classList.add("active");
			document.getElementById("idsub_databaru").classList.add("active");
			$("#table-upload").DataTable({
				responsive: true
			})

		});
	</script>
</body>
</html>
