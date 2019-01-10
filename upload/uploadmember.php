<?php
include "../param.php";
include_once "../includes/functions.php";

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
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
				<h4 class="m-t-0">Upload Data Deklarasi</h4>
				<div class="section-container section-with-top-border">			    
			    <form action="../api/apijatim.php" id="form-upload" name="form-upload" class="form-horizontal" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="han" value="upload">
			    	<div class="panel-body">
							<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
								<thead >
									<tr class="primary">
										<th class="text-center">No</th>
										<th class="text-center">Produk</th>
										<th class="text-center">Nama</th>
										<th class="text-center">Nomor KTP</th>										
										<th class="text-center">Gender</th>
										<th class="text-center">Tanggal Lahir</th>
										<th class="text-center">Usia</th>
										<th class="text-center">Plafond</th>
										<th class="text-center">Tanggal Akad</th>
										<th class="text-center">Tenor</th>
										<th class="text-center">Tanggal Akhir</th>										
										<th class="text-center">Premi</th>
										<th class="text-center">No Pinjaman</th>
										<th class="text-center">Cabang</th>										
										<th class="text-center">Asuransi</th>
									</tr>
								</thead>
								<tbody>									
									<?php
										if(isset($_FILES['fileupload']['name'])){
											$file_name = $_FILES['fileupload']['name'];
											$ext = pathinfo($file_name, PATHINFO_EXTENSION);
											$file_name = $_FILES['fileupload']['tmp_name'];
											$file_info = pathinfo($file_name);
											$file_extension = $file_info["extension"];
											$namefile = $file_info["filename"].'.'.$file_extension;
											$inputFileName = $file_name;
											$_SESSION['file_temp'] = $namefile;
											$_SESSION['file_name'] = $_FILES['fileupload']['name'];											
													 
											if(strtolower($ext)=='xls'){   		
												//Read your Excel workbook
												try {
													PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
													$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
													$objReader = PHPExcel_IOFactory::createReader($inputFileType);
													$objPHPExcel = $objReader->load($inputFileName);
												} catch (Exception $e) {
													die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME).'":'.$e->getMessage());
												}	
											}else{ 
												$objReader = PHPExcel_IOFactory::createReader('CSV');
												//If the files uses a delimiter other than a comma (e.g. a tab), then tell the reader
												$objReader->setDelimiter("\t");
												//If the files uses an encoding other than UTF-8 or ASCII, then tell the reader
												$objReader->setInputEncoding('UTF-8');
												//$objPHPExcel = $objReader->load('MyCSVFile.csv');
												$objPHPExcel = $objReader->load($inputFileName);
												$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
												//saved to file
												//$objWriter->save('MyExcelFile.xls'); 
												//exit;
											}

											//Table used to display the contents of the file
											//Get worksheet dimensions

											$sheet = $objPHPExcel->getSheet(0);
											$highestRow = $sheet->getHighestRow();
											$highestColumn = $sheet->getHighestColumn();

											$error=0;
											$no=1;

											for ($row = 1; $row <= $highestRow; $row++) {
												//  Read a row of data into an array
												$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL, TRUE, FALSE);
												echo "<tr>";
												$i = 0;

												foreach($rowData[0] as $k=>$v){
													$datatest[$i] = $v;
													$i++;
												}

												$data = explode("|",$datatest[0]);
												
												$today = date('Y-m-d');

												//$no = $data[0]; //no
												$nocif = $data[0];
												$nopinjaman = $data[1];
												$cab = $data[2];
												$nama = $data[3];
												$produk = $data[4];
												$gender = $data[5];
												$ktp = $data[6];
												$tptlahir = $data[7];
												$tgllahir = $data[8];
												$alamat = $data[9];
												$pekerjaan = $data[10];
												$npk = $data[11];
												$tglakad = $data[12];
												$tglakhir = $data[13];
												$plafond = str_replace($_separatorsNumb,$_separatorsNumb_,$data[14]);
												$premi = str_replace($_separatorsNumb,$_separatorsNumb_,$data[15]);
												$refpremi = $data[16];
												$tipepenutupan = $data[17];
												$asuransi = $data[18];		

												$nopinjaman_temp = "";
												$qcabang = mysql_fetch_array(mysql_query("SELECT * FROM ajkcabang WHERE idclient = '".$idclient."' AND (ref_mapping = '".$cab."' or name = '".$cab."')"));							
												
												//VALIDASI 
													//-------------------------------------------Validasi Produk-------------------------------------------//
													if($produk != ""){
														$qproduk = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND (ref_mapping = '".$produk."' or produk = '".$produk."')");
														if(mysql_num_rows($qproduk) > 0){
															$qrproduk = mysql_fetch_array($qproduk);
															$produk = $qrproduk['produk'];
															$errorproduk = null;
														}else{
															$errorproduk = '<span class="label label-danger">Produk tidak terdapat di database</span>';
															$error = 1;
														}
													}else{
														$errorproduk = '<span class="label label-danger">Produk Tidak Boleh Kosong</span>';
													}
													//---------------------------------------- End Validasi Produk----------------------------------------//

													//---------------------------------------Validasi No Pinjaman--------------------------------------------//
													if($nopinjaman != ""){
														$qproduk = mysql_query("SELECT * FROM ajkpeserta WHERE idclient = '".$idclient."' AND nopinjaman = '".$nopinjaman."'");

														if(mysql_num_rows($qproduk) > 0){
															$errornopinjaman = '<span class="label label-danger">No Pinjaman sudah terdapat di database</span>';
															$error = 1;
														}else{
															if($nopinjaman == $nopinjaman_temp){
																$errornopinjaman = '<span class="label label-danger">No Pinjaman Double</span>';
																$error = 1;
															}else{
																$nopinjaman_temp = $nopinjaman;																
																$errornopinjaman = null;
															}
														}
													}else{
														$errornopinjaman = '<span class="label label-danger">No Pinjaman tidak Boleh Kosong</span>';
														$error = 1;
													}

													//---------------------------------------End Validasi No Pinjaman----------------------------------------//

													//-------------------------------------------Validasi Cabang-------------------------------------------//
													if($cab != ""){
														$qcabang = mysql_query("SELECT * FROM ajkcabang WHERE idclient = '".$idclient."' AND (ref_mapping = '".$cab."' OR name = '".$cab."')");
														if(mysql_num_rows($qcabang) > 0){
															$qrcabang = mysql_fetch_array($qcabang);
															$cab = $qrcabang['name'];
															$errorcabang = null;
														}else{
															$errorcabang = '<span class="label label-danger">Cabang tidak terdapat di database</span>';
															$error = 1;
														}
													}else{
														$errorcabang = '<span class="label label-danger">Cabang Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Cabang----------------------------------------//

													//-------------------------------------------Validasi Nama-------------------------------------------//
													if($nama != ""){
														$errornama = null;
													}else{
														$errornama = '<span class="label label-danger">Nama Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Nama----------------------------------------//


													//-------------------------------------------Validasi Gender-------------------------------------------//
													if($gender != ""){
														if($gender == "L" or $gender == "P"){
															$errorgender = null;	
														}else{															
															$errorgender = '<span class="label label-danger">Jenis Kelamin hanya bisa diisi L (Laki - laki)atau P (Perempuan)</span>';
															$error = 1;
														}														
													}else{
														$errorgender = '<span class="label label-danger">Jenis Kelamin Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Gender----------------------------------------//

													//-------------------------------------------Validasi KTP-------------------------------------------//
													if($ktp != ""){
														$errorktp = null;
													}else{
														$errorktp = '<span class="label label-danger">KTP Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi KTP----------------------------------------//

													//-------------------------------------------Validasi Nomor PK-------------------------------------------//
													if($npk != ""){
														$errornpk = null;
													}else{
														$errornpk = '<span class="label label-danger">Nomor PK Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Nomor PK----------------------------------------//

													//-------------------------------------------Validasi Tanggal Lahir-------------------------------------------//
													if($tgllahir != ""){														
														$tgllahir = substr($tgllahir,0,4).'-'.substr($tgllahir,-4,2).'-'.substr($tgllahir,-2,2);														
														$errortgllahir = null;
													}else{
														$errortgllahir = '<span class="label label-danger">Tanggal Lahir Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Tanggal Lahir----------------------------------------//

													//-------------------------------------------Validasi Tanggal Akad-------------------------------------------//
													if($tglakad != ""){
														$tglakad = substr($tglakad,0,4).'-'.substr($tglakad,-4,2).'-'.substr($tglakad,-2,2);
														$errortglakad = null;
													}else{
														$errortglakad = '<span class="label label-danger">Tanggal Akad Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Tanggal Akad----------------------------------------//
													
													//-------------------------------------------Validasi Usia-------------------------------------------//
													$usia = birthday($tgllahir,$tglakad);

													$usiaawal = $qrproduk['agestart'];
													$usiaakhir = $qrproduk['ageend'];
													
													if($usia < $usiaawal or $usia > $usiaakhir){
														$errorusia = '<span class="label label-danger">Usia tidak sesuai ketentuan polis</span>';
														// $error = 1;														
													}else{
														$errorusia = null;
													}
													//---------------------------------------- End Validasi Usia----------------------------------------//

													//-------------------------------------------Validasi Tanggal Akhir-------------------------------------------//
													if($tglakhir != ""){
														$tglakhir = substr($tglakhir,0,4).'-'.substr($tglakhir,-4,2).'-'.substr($tglakhir,-2,2);
														$errortglakhir = null;
													}else{
														$errortglakhir = '<span class="label label-danger">Tanggal Akhir Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Tanggal Akhir----------------------------------------//

													//-------------------------------------------Validasi Tenor-------------------------------------------//
													$tenor = datediffmonth($tglakad,$tglakhir);
													$tenorawal = $qrproduk['tenormin'];
													$tenorakhir = $qrproduk['tenormax'];

													if($tenor < $tenorawal or $tenor > $tenorakhir){
														$errortenor = '<span class="label label-danger">Tenor tidak sesuai ketentuan polis</span>';
														$error = 1;														
													}else{
														$errortenor = null;
													}


													//-------------------------------------------End Validasi Tenor-------------------------------------------//

													//-------------------------------------------Validasi Plafond-------------------------------------------//
													if($plafond != ""){
														$plafondawal = $qrproduk['plafondstart'];
														$plafondakhir = $qrproduk['plafondend'];
														if($plafond < $plafondawal or $plafond > $plafondakhir){
															$errorplafond = '<span class="label label-danger">Plafond tidak sesuai polis</span>';
														}else{
															$errorplafond = null;	
														}														
													}else{
														$errorplafond = '<span class="label label-danger">Plafond Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Plafond----------------------------------------//

													//-------------------------------------------Validasi Premi-------------------------------------------//
													if($premi != ""){
														$qpremi = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker = '".$idbro."' and idclient = '".$idclient."' and idpolis = '".$qrproduk['id']."' and '".$tenor."' BETWEEN tenorfrom and tenorto and status = 'Aktif' and del is null");
														if(mysql_num_rows($qpremi) > 0){
															$qpremi_ = mysql_fetch_array($qpremi);
															$premisys = $plafond /1000 * $qpremi_['rate'];
															if($premi != $premisys){
																$errorpremi = '<span class="label label-danger">Premi Berbeda, Seharusnya '.duit($premisys).'</span>';	
															}else{
																$errorpremi = null;
															}														 	
														}else{
															$errorpremi = '<span class="label label-danger">Rate belum tersedia di database</span>';
															//$error = 1;
														}
													}else{
														$errorpremi = '<span class="label label-danger">Premi Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Premi----------------------------------------//


													//-------------------------------------------Validasi Tipe Penutupan-------------------------------------------//
													if($tipepenutupan != ""){
														$errortgltipepenutupan = null;
													}else{
														$errortgltipepenutupan = '<span class="label label-danger">Tipe Penutupan Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Tipe Penutupan----------------------------------------//

													//-------------------------------------------Validasi Asuransi-------------------------------------------//
													if($asuransi != ""){
														$qins = mysql_query("SELECT * FROM ajkinsurance WHERE idc = '".$idclient."' AND (ref_mapping = '".$asuransi."' or name = '".$asuransi."')");
														if(mysql_num_rows($qins) > 0){
															$qrins = mysql_fetch_array($qins);
															$asuransi = $qrins['name'];
															$errorasuransi = null;
														}else{
															$errorasuransi = '<span class="label label-danger">Asuransi tidak terdapat di database</span>';
															$error = 1;
														}
													}else{
														$errorasuransi = '<span class="label label-danger">Asuransi Tidak Boleh Kosong</span>';
														$error = 1;
													}
													//---------------------------------------- End Validasi Asuransi----------------------------------------//											


												//END VALIDASI

												echo "<td>".$no." </td>";
												echo "<td>".strtoupper($produk)." $errorproduk</td>";
												echo "<td>".$nama." $errornama</td>";
												echo "<td>".$ktp." $errorktp</td>";
												echo "<td>".$gender." $errorgender</td>";
												echo "<td>".viewBulan($tgllahir)." $errortgllahir</td>";
												echo "<td>".$usia." $errorusia</td>";
												echo "<td class='text-right'>".number_format($plafond,0,".",",")." $errorplafond</td>";										
												echo "<td>".viewBulan($tglakad)." $errortglakad</td>";
												echo "<td>".$tenor." $errortenor</td>";
												echo "<td>".viewBulan($tglakhir)." $errortglakhir</td>";												
												echo "<td class='text-right'>".number_format($premi,0,".",",")." $errorpremi</td>";
												echo "<td>".$nopinjaman." $errornopinjaman</td>";
												echo "<td>".$cab." $errorcabang</td>";
												echo "<td class='text-right'>".$asuransi." $errorasuransi</td>";
												echo "</tr>";
												$no++;
											}
											
											//if($error == 0){
												move_uploaded_file($file_name,'temp/'.$namefile) or die( "Could not upload file!");
												$disabledbtn = '';
											//}else{
												// $disabledbtn = 'disabled';
											//}
										}
									?>
								</tbody>
							</table>
							<div class="form-group m-b-0">
								<label class="control-label col-sm-12"></label>
								<div class="col-sm-6">
									<input type="submit" name="sub" class="btn btn-success width-xs" value="Submit" <?php echo $disabledbtn ?>>
									<a href="../upload?xq=<?php echo AES::encrypt128CBC($_REQUEST['xq'],ENCRYPTION_KEY)?>" class="btn btn-danger width-xs">Cancel</a>
								</div>
							</div>
						</div>
					</form>
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
			document.getElementById("has_input").classList.add("active");
			document.getElementById("idhas_input").classList.add("active");
		});

		$("#data-pesertatemp").DataTable({	responsive: false,scrollX:true,paging:false	});

	</script>
</body>
</html>
