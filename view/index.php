<?php
	include "../param.php";
	include_once('../includes/functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<style>
	.map-canvas {
		position:relative;
		width:100%;
		height:400px;
	}

	.map-canvas .info-window-content h2 {
		font-size:18px;
		font-weight:600;
		margin-bottom:8px;
	}

	.map-canvas .info-window-content p {
		margin-top:20px;
		text-align:center;
		font-size:12px;
		color:#999;
		text-shadow:none;
	}	

	.map-canvas-square {
		height:200px;
	}
</style>

<script>
	function toggle(source) {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(:disabled)');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i] != source)
			checkboxes[i].checked = source.checked;
		}
	}

	var map;

	function initialize(lat,long) {

		var myLatlng = new google.maps.LatLng(lat,long);
		var mapOptions = {
			zoom: 14,
			scrollwheel: false,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);

		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			animation: google.maps.Animation.DROP,
			title: "Survey Location"
		});

		var contentString = "";
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});
	}

	function mygps(lat, long) {
		initialize(lat,long);
	}
</script>

<?php
	_head($user,$namauser,$photo,$logo);


	echo '<body>
				<!-- begin #page-loader -->
				<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
				<!-- end #page-loader -->

				<!-- begin #page-container -->
				<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">';

	_header($user,$namauser,$photo,$logo,$logoklient);
	_sidebar($user,$namauser,'','');
?>

<?php
	switch ($_REQUEST['op']) {
		case "vAJK":
			echo '<div id="content" class="content">
							<div class="row">
								<div class="panel p-30">
									<h4 class="m-t-0">View Data AJK</h4>
									<div class="section-container section-with-top-border">
										<form action="#" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
		            			<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
	                    	<thead>
													<tr class="primary">
														<th>No</th>
														<th>Broker</th>
														<th>Partner</th>
														<th>Product</th>
														<th>Nomor SPK</th>
														<th>Name</th>
														<th>DOB</th>
														<th>Age</th>
														<th>Plafond</th>
														<th>Tgl Akad</th>
														<th>Tenor</th>
														<th>Tgl Akhir</th>
														<th>rate</th>
														<th>Premium</th>
														<th>Status</th>
														<th>Branch</th>
														<th>User Input</th>
														<th>Tgl Input</th>
													</tr>
												</thead>
	                      <tbody>';
			$cabangpusat = mysql_fetch_array(mysql_query("SELECT * 
																										FROM ajkcabang 
																										WHERE er = '".$rowuser['branch']."' AND 
																													idclient = '".$idclient."'"));
			if ($cabangpusat['name']=="PUSAT") {
				$aksescabang = '';
			}else{
				$aksescabang = 'ajkpeserta_temp.cabang = "'.$branchid.'" AND';
			}
			$metAJK = mysql_query('	SELECT ajkcobroker.`name` AS broker,
																	  ajkclient.`name` AS perusahaan,
																	  ajkpolis.produk,
																	  ajkpeserta_temp.nomorspk,
																	  ajkpeserta_temp.nama,
																	  ajkpeserta_temp.gender,
																	  ajkpeserta_temp.tgllahir,
																	  ajkpeserta_temp.usia,
																	  ajkpeserta_temp.plafond,
																	  ajkpeserta_temp.tglakad,
																	  ajkpeserta_temp.tenor,
																	  ajkpeserta_temp.tglakhir,
																	  ajkpeserta_temp.premirate,
																	  ajkpeserta_temp.totalpremi,
																	  ajkpeserta_temp.statusaktif,
																	  ajkpeserta_temp.tiperefund,
																	  ajkpeserta_temp.input_by,
																	  DATE_FORMAT(ajkpeserta_temp.input_time,"%Y-%m-%d") AS tglinput,
																	  useraccess.firstname,
																	  useraccess.lastname,
																	  ajkcabang.name AS namacabang
															FROM ajkpeserta_temp
															INNER JOIN ajkcobroker ON ajkpeserta_temp.idbroker = ajkcobroker.id
															INNER JOIN ajkclient ON ajkpeserta_temp.idclient = ajkclient.id
															INNER JOIN ajkpolis ON ajkpeserta_temp.idpolicy = ajkpolis.id
															INNER JOIN useraccess ON ajkpeserta_temp.input_by = useraccess.id
															INNER JOIN ajkcabang ON ajkpeserta_temp.cabang = ajkcabang.er
															WHERE ajkpeserta_temp.del IS NULL AND
																	  ajkpeserta_temp.idbroker = "'.$idbro.'" AND
																	  ajkpeserta_temp.idclient = "'.$idclient.'" AND
																	  '.$aksescabang.'
																	  ajkpeserta_temp.del IS NULL
															ORDER BY ajkpeserta_temp.input_time DESC, ajkcabang.name ASC');
			$li_row =1;

			while ($metAJK_ = mysql_fetch_array($metAJK)) {
				echo '<tr class="odd gradeX">
					      <td>'.$li_row.'</td>
								<td>'.$metAJK_['broker'].'</td>
								<td>'.$metAJK_['perusahaan'].'</td>
								<td>'.$metAJK_['produk'].'</td>
								<td>'.$metAJK_['nomorspk'].'</td>
								<td>'.$metAJK_['nama'].'</td>
								<td>'.$metAJK_['tgllahir'].'</td>
								<td>'.$metAJK_['usia'].'</td>
								<td>'.$metAJK_['plafond'].'</td>
								<td>'.$metAJK_['tglakad'].'</td>
								<td>'.$metAJK_['tenor'].'</td>
								<td>'.$metAJK_['tglakhir'].'</td>
								<td>'.$metAJK_['premirate'].'</td>
								<td>'.$metAJK_['totalpremi'].'</td>
								<td>'.$metAJK_['statusaktif'].'</td>
								<td>'.$metAJK_['namacabang'].'</td>
								<td>'.$metAJK_['firstname'].' '.$metAJK_['lastname'].'</td>
								<td>'.$metAJK_['tglinput'].'</td>
			        </tr>';
				$li_row++;
			}

			echo '				</tbody>
	          			</table>
	          		</form>
			        </div>
			        <!-- end section-container -->
		        </div>';
		break;

		case "vFLX":
			/* DISABLED 23112016
			if($level >= 7 AND $rowuser['tipe']=="Bank"){
				$kolApprove = '<th><center><input type="checkbox" onClick="toggle(this)" /></center></th>';
				$kolApproveSbmt = '<div class="form-group m-b-0">
								<label class="control-label col-sm-12"></label>
									<div class="col-sm-6"><input type="submit" name="sub" class="btn btn-success width-xs" value="Approve"></div>
								</div>';
			}else{
				$kolApprove = '';
				$kolApproveSbmt = '';
			}
			*/
			echo '<div id="content" class="content">
						<div class="row">
						<div class="panel p-30"><h4 class="m-t-0">View Data FLEXAS</h4>
							<div class="section-container section-with-top-border">
							<form action="#" id="validasi" class="form-horizontal" method="post" enctype="multipart/form-data">
						    <table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
					        <thead>
										<tr class="primary">
											<th>No</th>
											<th>Option</th>
											'.$kolApprove.'
											<th>Broker</th>
											<th>Partner</th>
											<th>Product</th>
											<th>Status</th>
											<th>IDSPAJK</th>
											<th>Name</th>
											<th>DOB</th>
											<th>KTP</th>
											<th>Telephone</th>
											<th>Alamat Debitur</th>
											<th>Alamat Objek</th>
											<th>Nilai Diajukan</th>
											<th>Nilai Appraisal</th>
											<th>Branch</th>
											<th>User Input</th>
											<th>Tgl Input</th>
										</tr>
									</thead>
					      	<tbody>';

			$metGeneral = mysql_query('	SELECT ajkcobroker.`name` AS broker,
																				ajkclient.`name` AS perusahaan,
																				ajkpolis.produk,
																				ajkcabang.`name` AS cabang,
																				ajkumum.id AS idkpr,
																				ajkumum.statusspajk,
																				ajkumum.nomorspajk,
																				ajkumum.nama,
																				ajkumum.tgllahir,
																				ajkumum.ktp,
																				ajkumum.hp,
																				ajkumum.alamatdebitur,
																				ajkumum.alamatobjek,
																				ajkumum.nilaidiajukan,
																				ajkumum.nilaiapproval,
																				(ajkumum.nilaiappraisalbangunan + ajkumum.nilaiappraisalperabot + ajkumum.nilaiappraisalstok + ajkumum.nilaiappraisalmesin) AS nilaiappraisal,
																				useraccess.firstname,
																				DATE_FORMAT(ajkumum.input_date, "%Y-%m-%d") AS tglinput,
																				ajkgeneraltype.type,
																				ajkgeneraltype.kode
																	FROM ajkumum
																	INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
																	INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
																	INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
																	INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
																	INNER JOIN useraccess ON ajkumum.input_by = useraccess.id
																	INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
																	WHERE ajkumum.idbroker = "'.$idbro.'" AND
																			  ajkumum.idclient = "'.$idclient.'" AND
																			  ajkumum.idcabang = "'.$branchid.'" AND
																			  ajkumum.statusspajk IN ("Request", "Survey") AND
																			  ajkgeneraltype.kode="KPR"
																	ORDER BY ajkumum.id DESC');

			$li_row =1;

			while ($metGeneral_ = mysql_fetch_array($metGeneral)) {
				$metAlamatMember = str_replace("#"," ",$metGeneral_['alamatdebitur']);
				$metAlamatObjek = str_replace("#"," ",$metGeneral_['alamatobjek']);

				if($level >= 7 AND $rowuser['tipe']=="Bank"){

				/* 23112016
				if ($metGeneral_['nilaiapproval'] == NULL) {
						$kolApproveKol = '<td align="center">&nbsp;</td>';
					}else{
						$kolApproveKol = '<td align="center"><div class="form-group"><center><input id="approve_'.$li_row.'" name="approve[]" value="'.$ktp.'"  type="checkbox"></center></div></td>';
					}
				*/
				if ($metGeneral_['nilaiappraisal'] <= 0 OR $metGeneral_['nilaiappraisal'] == NULL) {
					$dataNilaiAdmin= '';
				}else{
					$dataNilaiAdmin= '<!--<a href="view/?op=edFLX&xkpr='.AES::encrypt128CBC($metGeneral_['idkpr'],ENCRYPTION_KEY).'"><span class="label label-danger m-b-5">Edit</span></a> &nbsp;-->
							<a href="../view/?op=inFLX&xkpr='.AES::encrypt128CBC($metGeneral_['idkpr'],ENCRYPTION_KEY).'"><span class="label label-lime m-b-5">Set Approval</span></a>';
				}

				}else{
					$kolApproveKol = '';
				}
				if ($metGeneral_['statusspajk']=="Request") {
					$metStatusGeneral = '<span class="label label-danger m-b-5">'.$metGeneral_['statusspajk'].'</span>';
				}else{
					$metStatusGeneral = '<span class="label label-primary m-b-5">'.$metGeneral_['statusspajk'].'</span>';
				}

				echo '<tr class="odd gradeX">
				        <td>'.$li_row.'</td>
								<td>'.$dataNilaiAdmin.'</td>
								'.$kolApproveKol.'
				        <td>'.$metGeneral_['broker'].'</td>
				        <td>'.$metGeneral_['perusahaan'].'</td>
								<td>'.$metGeneral_['produk'].'</td>
								<td>'.$metStatusGeneral.'</td>
								<td>'.$metGeneral_['nomorspajk'].'</td>
								<td><a title="view data" href="../view?op=pFLX&k='.AES::encrypt128CBC($metGeneral_['idkpr'],ENCRYPTION_KEY).'">'.$metGeneral_['nama'].'</a></td>
								<td>'._convertDate($metGeneral_['tgllahir']).'</td>
								<td>'.$metGeneral_['ktp'].'</td>
								<td>'.$metGeneral_['hp'].'</td>
								<td>'.$metAlamatMember.'</td>
								<td>'.$metAlamatObjek.'</td>
								<td>'.duit($metGeneral_['nilaidiajukan']).'</td>
								<td>'.duit($metGeneral_['nilaiappraisal']).'</td>
								<td>'.$metGeneral_['cabang'].'</td>
								<td>'.$metGeneral_['firstname'].'</td>
								<td>'._convertDate($metGeneral_['tglinput']).'</td>
							</tr>';

				$li_row++;
			}

			echo '					</tbody>
										</table>
										'.$kolApproveSbmt.'
									</form>
								</div>
							</div>
						</div>';
		break;

		case "pFLX":
			$metPreview = mysql_fetch_array(mysql_query('SELECT ajkumum.id,
																													ajkumum.idproduk,
																													ajkcobroker.`name` AS broker,
																													ajkclient.`name` AS perusahaan,
																													ajkpolis.produk,
																													ajkgeneraltype.type,
																													ajkgeneraltype.kode,
																													ajkumum.nomorspajk,
																													ajkumum.statusspajk,
																													ajkumum.nama,
																													IF(ajkumum.jnskelamin="L", "Laki-Laki","Perempuan") AS gender,
																													ajkumum.tgllahir,
																													ajkumum.ktp,
																													ajkumum.hp,
																													ajkumum.alamatdebitur,
																													ajkumum.alamatobjek,
																													ajkumum.wilayah,
																													ajkumum.nilaidiajukan,
																													ajkumum.nilaiapproval,
																													ajkumum.okupasi,
																													ajkumum.luastanah,
																													ajkumum.luasbangunan,
																													ajkumum.tahunpembangunan,
																													ajkumum.jumlahlantai,
																													ajkumum.kelaskontruksi,
																													ajkumum.kontruksibangundinding,
																													ajkumum.kontruksibangunatap,
																													ajkumum.kontruksibangunlantai,
																													ajkumum.kontruksibanguntiang,
																													ajkumum.jarakbangunkiri,
																													ajkumum.jarakbangunkanan,
																													ajkumum.jarakbangundepan,
																													ajkumum.jarakbangunbelakang,
																													ajkumum.jenispemadam,
																													ajkumum.`security`,
																													ajkumum.jenisstok,
																													ajkumum.nilaiappraisalbangunan,
																													ajkumum.nilaiappraisalperabot,
																													ajkumum.nilaiappraisalstok,
																													ajkumum.nilaiappraisalmesin,
																													ajkumum.photodebitur,
																													ajkumum.photodepan,
																													ajkumum.photobelakang,
																													ajkumum.photokanan,
																													ajkumum.photokiri,
																													ajkumum.photo1,
																													ajkumum.photo2,
																													ajkumum.photo3,
																													ajkumum.photo4,
																													ajkregional.`name` AS regional,
																													ajkcabang.`name` AS cabang,
																													ajkumum.input_by,
																													DATE_FORMAT(ajkumum.input_date, "%Y-%m-%d") AS tglinput,
																													ajkumum.ttd_appraisal,
																													ajkumum.input_apraisal,
																													DATE_FORMAT(ajkumum.date_apraisal, "%Y-%m-%d") AS tglappraisal,
																													ajkgeneralkategori.keterangan AS okupasi,
																													ajkgeneralkelas.kelas,
																													ajkgeneralkelas.keterangan AS ketkelas,
																													ajkumum.latitude,
																													ajkumum.longitude,
																													ajkumum.alamatlatlon,
																													ajkgeneralarea.lokasi
																													FROM ajkumum
																													INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
																													INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
																													INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
																													INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id AND ajkumum.idbroker = ajkgeneraltype.idb
																													INNER JOIN ajkregional ON ajkumum.idregional = ajkregional.er
																													INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
																													LEFT JOIN ajkgeneralarea ON ajkumum.wilayah = ajkgeneralarea.id
																													LEFT JOIN ajkgeneralkategori ON ajkumum.okupasi = ajkgeneralkategori.id
																													LEFT JOIN ajkgeneralkelas ON ajkumum.kelaskontruksi = ajkgeneralkelas.id
																													WHERE ajkumum.id = '.AES::decrypt128CBC($_REQUEST['k'],ENCRYPTION_KEY).' AND
																																ajkgeneraltype.kode = "KPR"'));

			$lat = $metPreview['latitude'];
			$longitude = $metPreview['longitude'];
			$metAlamatMember = str_replace("#","<br>",$metPreview['alamatdebitur']);
			$metAlamatObjek = str_replace("#","<br>",$metPreview['alamatobjek']);

			if ($metPreview['statusspajk']=="Request") {
				$metPreviewData = '<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>';
			}else{
				$metPreviewData = '';
			}

			if ($metPreview['statusspajk'] !="Request" ) {
				//DATA PERLUASAN JAMINAN
				$cekGuaranteeGnr = mysql_query('SELECT  ajkgeneraljaminan.id,
																							  ajkgeneraljaminan.idbroker,
																							  ajkgeneraljaminan.idpartner,
																							  ajkgeneraljaminan.idproduk,
																							  ajkgeneraljaminan.idguarantee,
																							  ajkgeneraljaminan.wilayah,
																							  ajkgeneraljaminan.carahitungkontribusi,
																							  ajkgeneraljaminan.carahitungresiko,
																							  ajkgeneralnamajaminan.namajaminan
																				FROM ajkgeneraljaminan
																				INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
																				WHERE ajkgeneraljaminan.idproduk="'.$metPreview['idproduk'].'" AND 
																							ajkgeneraljaminan.del IS NULL');

				while ($cekGuaranteeGnr_ = mysql_fetch_array($cekGuaranteeGnr)) {
					$cekRider = mysql_fetch_array(mysql_query('SELECT * FROM ajkumumrider WHERE idajkumum="'.$metPreview['id'].'" AND idgeneraljaminan="'.$cekGuaranteeGnr_['id'].'" AND status="Ya"'));

					//CARA HITUNG KETENTUAN RATE GENERAL !!!
					if ($cekGuaranteeGnr_['wilayah']=="Ya") {
						if ($cekGuaranteeGnr_['carahitungkontribusi']=="Rate") {
							$raterider = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraljaminanrate WHERE idgeneraljaminan="'.$cekRider['idgeneraljaminan'].'" AND area="'.$metPreview['wilayah'].'"'));
							$rateridernilai = ROUND($metPreview['nilaiapproval'] * $raterider['c_cpr_rate'] / 100);
							$rateridernilai_ = $rateridernilai;
						}elseif ($cekGuaranteeGnr_['carahitungkontribusi']=="Plafond") {
							$raterider = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraljaminanrate WHERE idgeneraljaminan="'.$cekRider['idgeneraljaminan'].'" AND area="'.$metPreview['wilayah'].'" AND "'.$metPreview['nilaiapproval'].'" BETWEEN c_cpr_plafondstart AND c_cpr_plafondend'));
							$rateridernilai = ROUND($metPreview['nilaiapproval'] * $raterider['c_cpr_plafondpersen'] / 100);
							$rateridernilai_ = $rateridernilai;
						}elseif ($cekGuaranteeGnr_['carahitungkontribusi']=="Percentage") {
							$raterider = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraljaminanrate WHERE idgeneraljaminan="'.$cekRider['idgeneraljaminan'].'" AND area="'.$metPreview['wilayah'].'"'));
							$rateridernilai = ROUND($metPreview['nilaiapproval'] * $raterider['c_cpr_nilaipersen'] / 100);
							if ($rateridernilai <= $raterider['c_cpr_nilaiminimum']) {
								$rateridernilai_ = $rateridernilai;
							}else{
								$rateridernilai_ = $raterider['c_cpr_nilaiminimum'];
							}
						}else{

						}
					}else{
						$raterider = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraljaminanrate WHERE idgeneraljaminan="'.$cekRider['idgeneraljaminan'].'"'));
					}
					//CARA HITUNG KETENTUAN RATE GENERAL !!!
					if ($cekRider['id']) {
						$metRidernya = '<div class="col-lg-1"><span class="fa-stack fa-1x text-primary"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-check fa-stack-1x"></i></span></div>';
					}else{
						$metRidernya = '<div class="col-lg-1"><span class="fa-stack fa-1x text-danger"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-remove fa-stack-1x"></i></span></div>';
					}

					$tambahjaminan .= '<div class="col-lg-1">'.++$no.'.</div>
													  '.$metRidernya.'
													  <div class="col-lg-8">'.$cekGuaranteeGnr_['namajaminan'].'</div>
													  <div class="col-lg-2"><span class="fa-stack fa-1x text-warning"><strong>'.duit($rateridernilai_).'</strong></span></div>';
					$rateridernilai__ +=$rateridernilai_;
				}
				//DATA PERLUASAN JAMINAN

				$nilaitotalappraisal = $metPreview['nilaiappraisalbangunan'] + $metPreview['nilaiappraisalperabot'] + $metPreview['nilaiappraisalstok'] + $metPreview['nilaiappraisalmesin'];
				$vDataObjek= '<dt>Luas Bangunan&Tanah :</dt><dd> '.duit($metPreview['luasbangunan']).' / '.duit($metPreview['luastanah']).'</dd>
										  <dt>Okupasi :</dt><dd> '.$metPreview['okupasi'].'</dd>
										  <dt>Tahun Pembangunan :</dt><dd> '.$metPreview['tahunpembangunan'].'</dd>
										  <dt>Kelas Konstruksi :</dt><dd> '.$metPreview['kelas'].' - '.$metPreview['ketkelas'].'</dd>
										  <dt>Konstruksi Bangunan :</dt><dd><div class="col-md-2">Dinding</div><div class="col-md-10">: '.$metPreview['kontruksibangundinding'].'</div>
																	  	   <div class="col-md-2">Atap</div><div class="col-md-10">: '.$metPreview['kontruksibangunatap'].'</div>
																	  	   <div class="col-md-2">Lantai</div><div class="col-md-10">: '.$metPreview['kontruksibangunlantai'].'</div>
																	  	   <div class="col-md-2">Tiang</div><div class="col-md-10">: '.$metPreview['kontruksibanguntiang'].'</div>
																	  </dd>
										  <dt>Batas Bangunan&Jarak :</dt><dd><div class="col-md-2">Kiri</div><div class="col-md-10">: '.$metPreview['jarakbangunkiri'].'</div>
																	  	   <div class="col-md-2">Kanan</div><div class="col-md-10">: '.$metPreview['jarakbangunkanan'].'</div>
																	  	   <div class="col-md-2">Depan</div><div class="col-md-10">: '.$metPreview['jarakbangundepan'].'</div>
																	  	   <div class="col-md-2">Belakang</div><div class="col-md-10">: '.$metPreview['jarakbangunbelakang'].'</div>
																	  </dd>
										  <dt>Jenis Alat Pemadam :</dt><dd>'.$metPreview['jenispemadam'].'</dd>
										  <dt>Jenis Stok :</dt><dd>'.$metPreview['jenisstok'].'</dd>
										  <dt>Nilai Pertanggungan :</dt><dd><div class="col-md-2">Bangunan</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalbangunan']).'</div>
																	  	   <div class="col-md-2">Perabot</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalperabot']).'</div>
																	  	   <div class="col-md-2">Stok</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalstok']).'</div>
																	  	   <div class="col-md-2">Mesin</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalmesin']).'</div>
																	  </dd>
										  <dt>Total Nilai Appraisal :</dt><dd class="text-right"><strong>'.duit($nilaitotalappraisal).'</strong></dd>
										  <dt>Total Nilai Approval :</dt><dd class="text-right"><strong>'.duit($metPreview['nilaiapproval']).'</strong></dd>
										  <h4 class="text-left">TAMBAHAN JAMINAN</h4>
										  '.$tambahjaminan;

				if ($metPreview['photo1']!="") {	
					$vPhotoObjek1= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek1 = '';	
				}

				if ($metPreview['photo2']!="") {	
					$vPhotoObjek2= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek2 = '';	
				}

				if ($metPreview['photo3']!="") {	
					$vPhotoObjek3= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek3 = '';	
				}

				if ($metPreview['photo4']!="") {	
					$vPhotoObjek4= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek4 = '';	
				}

				$vPhotoObjek='<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
										  <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
										  <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
										  <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" alt="" class="img-circle" width="200" height="200"/></a></div></center>
										  '.$vPhotoObjek1.''.$vPhotoObjek2.''.$vPhotoObjek3.''.$vPhotoObjek4.'';
											//$vPhotoObjek= ''.$PhotoGeneralDebitur.''.$metPreview['photodebitur'].'';
											$vDataObjekLocation = 'mygps('.$lat.','.$longitude.').';
											$vDataObjekLocation = '<div class="col-lg-12">
											<div class="panel panel-inverse">
												<div class="panel-heading">
						            	<div class="panel-heading-btn">
						                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						              </div>
													<h4 class="panel-title">Map Data</h4>
												</div>
												<div class="panel-body">
													<div id="mapCanvas" class="map-canvas"></div>
												</div>
											</div>
										</div>';
			}else{
				$vDataObjek= '<div class="alert alert-warning fade in m-b-10"><strong>Tidak ada data objek!</strong> Data belum di survey.</div>';
				$vPhotoObjek= '<div class="alert alert-warning fade in m-b-10"><strong>Tidak ada data objek!</strong> Data belum di survey.</div>';
			}

			if ($metPreview['nilaiapproval']=="") {
				$grandTotal_= $nilaitotalappraisal + $rateridernilai__;
			}else{
				$grandTotal_= $metPreview['nilaiapproval'] + $rateridernilai__;
			}

			echo '<div id="content" class="content">
							<div class="row">
								<div class="col-lg-7">
									<div class="panel panel-inverse">
										<div class="panel-heading">
				            	<div class="panel-heading-btn">
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				              </div>
										  <h4 class="panel-title">Preview Data</h4>
										</div>
										<div class="panel-body">
										<div class="col-md-8">
			              <div class="m-t-5">
										<dl class="dl-horizontal">
											<dt>Produk :</dt><dd><strong> '.$metPreview['produk'].'</strong></dd>
											<dt>Nomor SPAJK :</dt><dd><strong> '.$metPreview['nomorspajk'].'</strong></dd>
											<dt>Status SPAJK :</dt><dd><strong> '.$metPreview['statusspajk'].'</strong></dd>
											<h4 class="text-left">DATA DEBITUR</h4>
											<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>
											<dt>Jenis Kelamin :</dt><dd> '.$metPreview['gender'].'</dd>
											<dt>Tanggal Lahir :</dt><dd> '._convertDate($metPreview['tgllahir']).'</dd>
											<dt>K.T.P :</dt><dd> '.$metPreview['ktp'].'</dd>
											<dt>Telephone :</dt><dd> '.$metPreview['hp'].'</dd>
											<dt>Alamat Debitur :</dt><dd> '.$metAlamatMember.'</dd>
											<h4 class="text-left">DATA OBJEK</h4>
											<!--<dt>Nilai Diajukan :</dt><dd> '.duit($metPreview['nilaidiajukan']).'</dd>-->
											<dt>Wilayah :</dt><dd> '.$metPreview['lokasi'].'</dd>
											<dt>Alamat Objek :</dt><dd> '.$metAlamatObjek.'</dd>
											<dt>Alamat Versi Tab :</dt><dd> '.$metPreview['alamatlatlon'].'</dd>
											'.$vDataObjek.'
				 							<dt>&nbsp; </dt><dd class="text-right">&nbsp; </dd>
				 							<dt>Grand Total :</dt><dd class="text-right  text-primary"><strong>'.duit($grandTotal_).'</strong></dd>
										</dl>
			            </div>
						    </div>
					    <div class="col-md-4">
					    	<a href="../'.$PhotoGeneralDebitur.''.$metPreview['photodebitur'].'" data-lightbox="gallery-group-1">
									<img src="../'.$PhotoGeneralDebitur.''.$metPreview['photodebitur'].'" alt="" class="img-circle" width="200" height="200"/>
								</a>
					    </div>
						</div>
					</div>
				</div>

				<div class="col-lg-5">
					<div class="panel panel-inverse">
						<div class="panel-heading">
				    	<div class="panel-heading-btn">
				        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				       </div>
						   <h4 class="panel-title">Data Objek</h4>
						</div>
						<div class="panel-body">
						'.$vPhotoObjek.'
						</div>
					</div>
				</div>
				'.$vDataObjekLocation.'
			</div>';
		break;

		case "inFLX":
			$metPreview = mysql_fetch_array(mysql_query('SELECT ajkumum.id,
																													ajkumum.idproduk,
																													ajkcobroker.`name` AS broker,
																													ajkclient.`name` AS perusahaan,
																													ajkpolis.produk,
																													ajkgeneraltype.type,
																													ajkgeneraltype.kode,
																													ajkumum.nomorspajk,
																													ajkumum.statusspajk,
																													ajkumum.tenor,
																													ajkumum.nilaiapproval,
																													ajkumum.tglakadapproval,
																													ajkumum.nama,
																													IF(ajkumum.jnskelamin="L", "Laki-Laki","Perempuan") AS gender,
																													ajkumum.tgllahir,
																													ajkumum.ktp,
																													ajkumum.nilaiplafond,
																													ajkumum.hp,
																													ajkumum.alamatdebitur,
																													ajkumum.alamatobjek,
																													ajkumum.nilaidiajukan,
																													ajkumum.okupasi,
																													ajkumum.wilayah,
																													ajkumum.luastanah,
																													ajkumum.luasbangunan,
																													ajkumum.tahunpembangunan,
																													ajkumum.jumlahlantai,
																													ajkumum.kelaskontruksi,
																													ajkumum.kontruksibangundinding,
																													ajkumum.kontruksibangunatap,
																													ajkumum.kontruksibangunlantai,
																													ajkumum.kontruksibanguntiang,
																													ajkumum.jarakbangunkiri,
																													ajkumum.jarakbangunkanan,
																													ajkumum.jarakbangundepan,
																													ajkumum.jarakbangunbelakang,
																													ajkumum.jenispemadam,
																													ajkumum.`security`,
																													ajkumum.jenisstok,
																													ajkumum.nilaiappraisalbangunan,
																													ajkumum.nilaiappraisalperabot,
																													ajkumum.nilaiappraisalstok,
																													ajkumum.nilaiappraisalmesin,
																													(ajkumum.nilaiappraisalbangunan + ajkumum.nilaiappraisalperabot + ajkumum.nilaiappraisalstok + ajkumum.nilaiappraisalmesin) AS nilaiappraisal,
																													ajkumum.photodebitur,
																													ajkumum.photodepan,
																													ajkumum.photobelakang,
																													ajkumum.photokanan,
																													ajkumum.photokiri,
																													ajkumum.photo1,
																													ajkumum.photo2,
																													ajkumum.photo3,
																													ajkumum.photo4,
																													ajkregional.`name` AS regional,
																													ajkcabang.`name` AS cabang,
																													ajkumum.input_by,
																													DATE_FORMAT(ajkumum.input_date, "%Y-%m-%d") AS tglinput,
																													ajkumum.ttd_appraisal,
																													ajkumum.input_apraisal,
																													DATE_FORMAT(ajkumum.date_apraisal, "%Y-%m-%d") AS tglappraisal,
																													ajkgeneralkategori.keterangan AS okupasi,
																													ajkgeneralkelas.kelas,
																													ajkgeneralkelas.keterangan AS ketkelas
																													FROM
																													ajkumum
																													INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
																													INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
																													INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
																													INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id AND ajkumum.idbroker = ajkgeneraltype.idb
																													INNER JOIN ajkregional ON ajkumum.idregional = ajkregional.er
																													INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
																													LEFT JOIN ajkgeneralkategori ON ajkumum.okupasi = ajkgeneralkategori.id
																													LEFT JOIN ajkgeneralkelas ON ajkumum.kelaskontruksi = ajkgeneralkelas.id
																													WHERE
																													ajkumum.id = '.AES::decrypt128CBC($_REQUEST['xkpr'],ENCRYPTION_KEY).' AND
																													ajkgeneraltype.kode = "KPR"'));

			$metAlamatMember = str_replace("#","<br>",$metPreview['alamatdebitur']);
			$metAlamatObjek = str_replace("#","<br>",$metPreview['alamatobjek']);

			if ($metPreview['statusspajk']=="Request") {
				$metPreviewData = '<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>';
			}else{	}

			//DATA PERLUASAN JAMINAN
			$cekGuaranteeGnr = mysql_query('SELECT  ajkgeneraljaminan.id,
																						  ajkgeneraljaminan.idbroker,
																						  ajkgeneraljaminan.idpartner,
																						  ajkgeneraljaminan.idproduk,
																						  ajkgeneraljaminan.idguarantee,
																						  ajkgeneraljaminan.wilayah,
																						  ajkgeneraljaminan.carahitungkontribusi,
																						  ajkgeneraljaminan.carahitungresiko,
																						  ajkgeneralnamajaminan.namajaminan
																			FROM ajkgeneraljaminan
																			INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
																			WHERE ajkgeneraljaminan.idproduk="'.$metPreview['idproduk'].'" AND 
																						ajkgeneraljaminan.del IS NULL');
			//DATA PERLUASAN JAMINAN

			if ($_REQUEST['kprapprove']=="approvekpr") {
				if ($_REQUEST['ed']=="editkpr") {
					$pesertakpr = AES::encrypt128CBC('pesertaKPR',ENCRYPTION_KEY);
					$metUpdateApproval = mysql_query('UPDATE ajkumum 
																						SET statusspajk="Pending",
																								tenor="'.$_REQUEST['tenor'].'",
																								nilaiapproval="'.str_replace(",", '', $_REQUEST['nilaiobjek']).'",
																								tglakadapproval="'._convertDate2($_REQUEST['tglakad']).'",
																								wilayah="'.$_REQUEST['namawilayah'].'",
																								editinput_approval="'.$iduser.'",
																								editdate_approval="'.$futgl.'"
																					 	WHERE id="'.$_REQUEST['idkpr'].'"');

					$metTambahan = mysql_query('UPDATE ajkumumrider 
																			SET status="Tidak", 
																					update_by="'.$iduser.'", 
																					update_date="'.$futgl.'" 
																			WHERE idajkumum="'.$metPreview['id'].'"');

					foreach ($_REQUEST['jaminannya'] as $quarantee) {
						$cekEditRider = mysql_fetch_array(mysql_query('SELECT * FROM ajkumumrider WHERE idajkumum="'.$metPreview['id'].'" AND idgeneraljaminan="'.$quarantee.'"'));
						if ($cekEditRider['id']) {
							$metTambahan = mysql_query('UPDATE ajkumumrider 
																					SET status="Tidak", 
																							update_by="'.$iduser.'", 
																							update_date="'.$futgl.'" 
																					WHERE idajkumum="'.$metPreview['id'].'" AND 
																								idgeneraljaminan="'.$quarantee.'"');

							$metTambahan = mysql_query('UPDATE ajkumumrider 
																					SET status="Ya", 
																							update_by=null, 
																							update_date=null 
																					WHERE idajkumum="'.$metPreview['id'].'" AND 
																								idgeneraljaminan="'.$quarantee.'"');
						}else{
							$metTambahan = mysql_query('INSERT INTO ajkumumrider 
																					SET idajkumum="'.$metPreview['id'].'", 
																							idgeneraljaminan="'.$quarantee.'", 
																							input_by="'.$iduser.'", 
																							input_date="'.$futgl.'"');
						}
					}
					echo '<meta http-equiv="refresh" content="0; url=../masterdata?type='.$pesertakpr.'">';
				}else{
					$metUpdateApproval = mysql_query('UPDATE ajkumum 
																						SET statusspajk="Pending",
																								tenor="'.$_REQUEST['tenor'].'",
																								nilaiapproval="'.str_replace(",", '', $_REQUEST['nilaiobjek']).'",
																								tglakadapproval="'._convertDate2($_REQUEST['tglakad']).'",
																								wilayah="'.$_REQUEST['namawilayah'].'",
																								input_approval="'.$iduser.'",
																								date_approval="'.$futgl.'"
																						WHERE id="'.$_REQUEST['idkpr'].'"');

					foreach ($_REQUEST['jaminannya'] as $quarantee) {
						$metTambahan = mysql_query('INSERT INTO ajkumumrider 
																				SET idajkumum="'.$metPreview['id'].'",
																						idgeneraljaminan="'.$quarantee.'",
																						input_by="'.$iduser.'",
																						input_date="'.$futgl.'"');
					}
					echo '<meta http-equiv="refresh" content="0; url=../view/?op=vFLX">';
				}
			}
			echo '<div id="content" class="content">
						<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
		            	<div class="panel-heading-btn">
		              	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		              </div>
								  <h4 class="panel-title">Preview Data</h4>
								</div>
								<div class="panel-body">
								<form action="#" id="inputmember" class="form-horizontal" method="post" enctype="multipart/form-data">
									<input type="hidden" name="idkpr" value="'.AES::decrypt128CBC($_REQUEST['xkpr'],ENCRYPTION_KEY).'">
										<div class="col-md-6">
					           	<div class="m-t-12">
											<dl class="dl-horizontal">
												<dt>Produk :</dt><dd><strong> '.$metPreview['produk'].'</strong></dd>
												<dt>Nomor SPAJK :</dt><dd><strong> '.$metPreview['nomorspajk'].'</strong></dd>
												<dt>Status SPAJK :</dt><dd><strong> '.$metPreview['statusspajk'].'</strong></dd>
												<h4 class="text-left">DATA DEBITUR</h4>
												<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>
												<dt>Jenis Kelamin :</dt><dd> '.$metPreview['gender'].'</dd>
												<dt>Tanggal Lahir :</dt><dd> '._convertDate($metPreview['tgllahir']).'</dd>
												<dt>K.T.P :</dt><dd> '.$metPreview['ktp'].'</dd>
												<dt>Telephone :</dt><dd> '.$metPreview['hp'].'</dd>
												<dt>Alamat Debitur :</dt><dd> '.$metAlamatMember.'</dd>
												<!--<h4 class="text-left">DATA ASURANSI</h4>
												<dt>Nilai Plafond :</dt><dd> '.duit($metPreview['nilaiplafond']).'</dd>-->
												<h4 class="text-left">DATA OBJEK</h4>
												<!--<dt>Nilai Diajukan :</dt><dd> '.duit($metPreview['nilaidiajukan']).'</dd>-->
												<dt>Nilai Appraisal :</dt><dd> '.duit($metPreview['nilaiappraisal']).'</dd>';

			if ($_REQUEST['ed']=="editkpr") {
				echo '<dt>							
								<label class="control-label"><strong>Nilai Objek <span class="text-danger">*</span></label> :</strong>
							</dt>
							<dd>
								<div class="form-group">
			           	<div class="col-sm-12"><input name="nilaiobjek" id="nilaiobjek" class="form-control" placeholder="Silahkan Input Nilai Objek" type="text" value="'.$metPreview['nilaiapproval'].'"></div>
			           </div>
				  		</dd>
				  		<dt>
				  			<label class="control-label"><strong>Input Tanggal Akad <span class="text-danger">*</span></label> :</strong>
				  		</dt>
				  		<dd>
								<div class="form-group">
	                <div class="col-sm-12"><input name="tglakad" id="tglakad" class="form-control" placeholder="Silahkan Tanggal AKAD" type="text" value="'._convertDate3($metPreview['tglakadapproval']).'"></div>
	              </div>
				  		</dd>
				  		<dt>
				  			<label class="control-label"><strong>Tenor (Bulan) <span class="text-danger">*</span></label> :</strong>
				  		</dt>
				  		<dd>
								<div class="form-group">
		            	<div class="col-sm-12"><input name="tenor" id="tenor" class="form-control" placeholder="Silahkan Input Tenor (Bulan)" type="text" value="'.$metPreview['tenor'].'"></div>
		          	</div>
				  		</dd>
				  		<dt><label class="control-label"><strong>Wilayah <span class="text-danger">*</span></label> :</strong></dt>
				  		<dd>
								<div class="col-sm-12"><div class="form-group">
		            	<select class="form-control" name="namawilayah">
										<option value="">-- Pilih Wilayah --</option>';

				$genWilayah = mysql_query('SELECT * FROM ajkgeneralarea WHERE idproduk="'.$metPreview['idproduk'].'" AND del IS NULL');

				while($genWilayah_ = mysql_fetch_array($genWilayah)){
					$genWilayah_id = $genWilayah_['id'];
					$genWilayah_lokasi = $genWilayah_['lokasi'];
					echo '<option value="'.$genWilayah_id.'"'._selected($metPreview['wilayah'], $genWilayah_id).'>'.$genWilayah_lokasi.'</option>';
				}
				echo '	</select>
								</div>
				  		</dd>';
				$tambahjaminan .= ' <table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
													  <thead>
														  <tr>
														  	<th width="1%">No</th>
													  	  <th width="1%"><center><input type="checkbox" onClick="toggle(this)" /></center></th>
													  	  <th>Nama Perluasan Jaminan</th>
														  </tr>
														  </thead>
													  <tbody>';

				while ($cekGuaranteeGnr_ = mysql_fetch_array($cekGuaranteeGnr)) {
					$metRideredit = mysql_fetch_array(mysql_query('	SELECT * 
																													FROM ajkumumrider 
																													WHERE idajkumum="'.$metPreview['id'].'" AND 
																																idgeneraljaminan="'.$cekGuaranteeGnr_['id'].'" AND 
																																status="Ya"'));

					if ($metRideredit['id']) {
						$tambahjaminan .='<tr><td>'.++$no.'</td>
															  <td><center><input id="'.$cekGuaranteeGnr_['id'].'" name="jaminannya[]" value="'.$cekGuaranteeGnr_['id'].'"  type="checkbox" checked></center></td>
															  <td>'.$cekGuaranteeGnr_['namajaminan'].'</td>
													  	</tr>';
					}else{
						$tambahjaminan .='<tr>
																<td>'.++$no.'</td>
															  <td><center><input id="'.$cekGuaranteeGnr_['id'].'" name="jaminannya[]" value="'.$cekGuaranteeGnr_['id'].'"  type="checkbox"></center></td>
															  <td>'.$cekGuaranteeGnr_['namajaminan'].'</td>
															</tr>';
					}
				}

				$tambahjaminan .= '		</tbody>
									   			 	<tfoot>
									   			 </table>';

				echo '<dt><label class="control-label"><strong>Perluasan Jaminan </label> :</strong></dt>
							<dd>
								<div class="form-group">
		              <div class="col-sm-12">'.$tambahjaminan.'</div>
		            </div>
					  	</dd>';
			}
			else{
				echo '<dt><label class="control-label"><strong>Nilai Objek <span class="text-danger">*</span></label> :</strong></dt>
							<dd>
								<div class="form-group">
			          	<div class="col-sm-12"><input name="nilaiobjek" id="nilaiobjek" class="form-control" placeholder="Silahkan Input Nilai Objek" type="text"></div>
			          </div>
					  	</dd>
					  	<dt><label class="control-label"><strong>Input Tanggal Akad <span class="text-danger">*</span></label> :</strong></dt>
					  	<dd>
								<div class="form-group">
		            	<div class="col-sm-12"><input name="tglakad" id="tglakad" class="form-control" placeholder="Silahkan Tanggal AKAD" type="text"></div>
		            </div>
					  	</dd>
					  	<dt><label class="control-label"><strong>Tenor (Bulan) <span class="text-danger">*</span></label> :</strong></dt>
					  	<dd>
								<div class="form-group">
		            	<div class="col-sm-12"><input name="tenor" id="tenor" class="form-control" placeholder="Silahkan Input Tenor (Bulan)" type="text"></div>
		            </div>
					  	</dd>
					  	<dt><label class="control-label"><strong>Wilayah <span class="text-danger">*</span></label> :</strong></dt>
					  	<dd>
								<div class="col-sm-12"><div class="form-group">
									<select class="form-control" name="namawilayah">
										<option value="">-- Pilih Wilayah --</option>';
				$genWilayah = mysql_query('	SELECT * 
																		FROM ajkgeneralarea 
																		WHERE idproduk="'.$metPreview['idproduk'].'" AND 
																					del IS NULL');

				while($genWilayah_ = mysql_fetch_array($genWilayah)){
					$genWilayah_id = $genWilayah_['id'];
					$genWilayah_lokasi = $genWilayah_['lokasi'];
					echo '<option value="'.$genWilayah_id.'">'.$genWilayah_lokasi.'</option>';
				}

				echo '	</select>
								</div>
							</dd>';

				$tambahjaminan .='<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
												  <thead>
													  <tr>
													  	<th width="1%">No</th>
													  	<th width="1%"><center><input type="checkbox" onClick="toggle(this)" /></center></th>
													  	<th>Nama Perluasan Jaminan</th>
													  </tr>
												  </thead>
												  <tbody>';

				while ($cekGuaranteeGnr_ = mysql_fetch_array($cekGuaranteeGnr)) {
					$tambahjaminan .='<tr>
															<td>'.++$no.'</td>
														  <td><center><input id="'.$cekGuaranteeGnr_['id'].'" name="jaminannya[]" value="'.$cekGuaranteeGnr_['id'].'"  type="checkbox"></center></td>
														  <td>'.$cekGuaranteeGnr_['namajaminan'].'</td>
													  </tr>';

				}

				$tambahjaminan .= '	</tbody>
												   	<tfoot>
												   </table>';

				echo '<dt><label class="control-label"><strong>Perluasan Jaminan </label> :</strong></dt>
							<dd>
								<div class="form-group">
		            	<div class="col-sm-12">'.$tambahjaminan.'</div>
		            </div>
					  	</dd>';
			}

			echo '		</dl>
	          	</div>
	    			</div>
	    			<div class="col-md-6">';

			if ($metPreview['photo1']!="") {	
				$vPhotoObjek1= '<center><div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" alt="" class="img-circle" width="150" height="150"/></a></div>';	
			}else{	
				$vPhotoObjek1 = '';	
			}

			if ($metPreview['photo2']!="") {	
				$vPhotoObjek2= '<center><div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" alt="" class="img-circle" width="150" height="150"/></a></div>';	
			}else{	
				$vPhotoObjek2 = '';	
			}

			if ($metPreview['photo3']!="") {	
				$vPhotoObjek3= '<center><div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" alt="" class="img-circle" width="150" height="150"/></a></div>';	
			}else{	
				$vPhotoObjek3 = '';	
			}

			if ($metPreview['photo4']!="") {	
				$vPhotoObjek4= '<center><div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" alt="" class="img-circle" width="150" height="150"/></a></div>';	
			}else{	
				$vPhotoObjek4 = '';	
			}

			echo '<center>
					   	<div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" alt="" class="img-circle" width="150" height="150"/></a></div>
					   	<div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" alt="" class="img-circle" width="150" height="150"/></a></div>
					   	<div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" alt="" class="img-circle" width="150" height="150"/></a></div>
					  	<div class="col-md-3"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" alt="" class="img-circle" width="150" height="150"/></a></div>
					  </center>
					  '.$vPhotoObjek1.''.$vPhotoObjek2.''.$vPhotoObjek3.''.$vPhotoObjek4.'';

			echo '</div>
					  <div class="form-group m-b-0">
		          <label class="control-label col-sm-2"></label>';
		  if ($_REQUEST['ed']=="editkpr") {
				echo '<div class="col-sm-12"><input type="hidden" class="btn btn-warning width-xs" name="kprapprove" value="approvekpr"><button type="submit" class="btn btn-warning width-xs">Edit Approval</button></div>';
		  }else{
				echo '<div class="col-sm-12"><input type="hidden" class="btn btn-success width-xs" name="kprapprove" value="approvekpr"><button type="submit" class="btn btn-success width-xs">Approval</button></div>';
		  }

		  echo '				</div>
									</form>
									</div>
								</div>
								</div>
							</div>
						</div>';
		break;

		case "vKKB":
			echo '<div id="content" class="content">
						<div class="panel p-30"><h4 class="m-t-0">View Data KKB</h4>
							<div class="section-container section-with-top-border">
								<form action="#" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
						    	<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
					        	<thead>
											<tr class="primary">
												<th>No</th>
												<th>Broker</th>
												<th>Partner</th>
												<th>Product</th>
												<th>Status</th>
												<th>IDSPAJK</th>
												<th>Name</th>
												<th>DOB</th>
												<th>KTP</th>
												<th>Telephone</th>
												<th>Alamat Debitur</th>
												<th>Alamat Objek</th>
												<th>Nilai Diajukan</th>
												<th>Branch</th>
												<th>User Input</th>
												<th>Tgl Input</th>
												<th>Edit</th>
											</tr>
										</thead>
					        	<tbody>';

			$metGeneral = mysql_query('SELECT ajkcobroker.`name` AS broker,
																				ajkclient.`name` AS perusahaan,
																				ajkpolis.produk,
																				ajkcabang.`name` AS cabang,
																				ajkumum.id AS idkpr,
																				ajkumum.statusspajk,
																				ajkumum.nomorspajk,
																				ajkumum.nama,
																				ajkumum.tgllahir,
																				ajkumum.ktp,
																				ajkumum.hp,
																				ajkumum.alamatdebitur,
																				ajkumum.alamatobjek,
																				ajkumum.nilaidiajukan,
																				useraccess.firstname,
																				DATE_FORMAT(ajkumum.input_date, "%Y-%m-%d") AS tglinput,
																				ajkgeneraltype.type,
																				ajkgeneraltype.kode
																				FROM ajkumum
																				INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
																				INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
																				INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
																				INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
																				INNER JOIN useraccess ON ajkumum.input_by = useraccess.id
																				INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
																				WHERE ajkumum.idbroker = "'.$idbro.'" AND
																						  ajkumum.idclient = "'.$idclient.'" AND
																						  ajkumum.idcabang = "'.$branchid.'" AND
																						  ajkumum.statusspajk IN ("Request", "Survey") AND
																						  ajkgeneraltype.kode="KKB"
																						  ORDER BY ajkumum.id DESC');

			$li_row =1;

			while ($metGeneral_ = mysql_fetch_array($metGeneral)) {
				$metAlamatMember = str_replace("#"," ",$metGeneral_['alamatdebitur']);
				$metAlamatObjek = str_replace("#"," ",$metGeneral_['alamatobjek']);

				echo '<tr class="odd gradeX">
				        <td>'.$li_row.'</td>
				        <td>'.$metGeneral_['broker'].'</td>
				        <td>'.$metGeneral_['perusahaan'].'</td>
								<td>'.$metGeneral_['produk'].'</td>
								<td>'.$metGeneral_['statusspajk'].'</td>
								<td>'.$metGeneral_['nomorspajk'].'</td>
								<td><a title="view data" href="../view?op=pKKB&k='.AES::encrypt128CBC($metGeneral_['idkpr'],ENCRYPTION_KEY).'">'.$metGeneral_['nama'].'</a></td>
								<td>'._convertDate($metGeneral_['tgllahir']).'</td>
								<td>'.$metGeneral_['ktp'].'</td>
								<td>'.$metGeneral_['hp'].'</td>
								<td>'.$metAlamatMember.'</td>
								<td>'.$metAlamatObjek.'</td>
								<td>'.duit($metGeneral_['nilaidiajukan']).'</td>
								<td>'.$metGeneral_['cabang'].'</td>
								<td>'.$metGeneral_['firstname'].'</td>
								<td>'._convertDate($metGeneral_['tglinput']).'</td>
								<td><a title="preview data"  href="#"><span class="fa-stack fa-2x text-warning"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a></td>
							</tr>';

				$li_row++;
			}

			echo '							</tbody>
												</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>';					
		break;

		case "pKKB":
			$metPreview = mysql_fetch_array(mysql_query('SELECT ajkumum.id,
																													ajkcobroker.`name` AS broker,
																													ajkclient.`name` AS perusahaan,
																													ajkpolis.produk,
																													ajkgeneraltype.type,
																													ajkgeneraltype.kode,
																													ajkumum.nomorspajk,
																													ajkumum.statusspajk,
																													ajkumum.nama,
																													IF(ajkumum.jnskelamin="L", "Laki-Laki","Perempuan") AS gender,
																													ajkumum.tgllahir,
																													ajkumum.ktp,
																													ajkumum.nilaiplafond,
																													ajkumum.hp,
																													ajkumum.alamatdebitur,
																													ajkumum.alamatobjek,
																													ajkumum.nilaidiajukan,
																													ajkumum.okupasi,
																													ajkumum.luastanah,
																													ajkumum.luasbangunan,
																													ajkumum.tahunpembangunan,
																													ajkumum.jumlahlantai,
																													ajkumum.kelaskontruksi,
																													ajkumum.kontruksibangundinding,
																													ajkumum.kontruksibangunatap,
																													ajkumum.kontruksibangunlantai,
																													ajkumum.kontruksibanguntiang,
																													ajkumum.jarakbangunkiri,
																													ajkumum.jarakbangunkanan,
																													ajkumum.jarakbangundepan,
																													ajkumum.jarakbangunbelakang,
																													ajkumum.jenispemadam,
																													ajkumum.`security`,
																													ajkumum.jenisstok,
																													ajkumum.nilaiappraisalbangunan,
																													ajkumum.nilaiappraisalperabot,
																													ajkumum.nilaiappraisalstok,
																													ajkumum.nilaiappraisalmesin,
																													ajkumum.photodebitur,
																													ajkumum.photodepan,
																													ajkumum.photobelakang,
																													ajkumum.photokanan,
																													ajkumum.photokiri,
																													ajkumum.photo1,
																													ajkumum.photo2,
																													ajkumum.photo3,
																													ajkumum.photo4,
																													ajkregional.`name` AS regional,
																													ajkcabang.`name` AS cabang,
																													ajkumum.input_by,
																													DATE_FORMAT(ajkumum.input_date, "%Y-%m-%d") AS tglinput,
																													ajkumum.ttd_appraisal,
																													ajkumum.input_apraisal,
																													DATE_FORMAT(ajkumum.date_apraisal, "%Y-%m-%d") AS tglappraisal,
																													ajkgeneralkategori.keterangan AS okupasi,
																													ajkgeneralkelas.kelas,
																													ajkgeneralkelas.keterangan AS ketkelas
																									FROM ajkumum
																									INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
																									INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
																									INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
																									INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id AND ajkumum.idbroker = ajkgeneraltype.idb
																									INNER JOIN ajkregional ON ajkumum.idregional = ajkregional.er
																									INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
																									LEFT JOIN ajkgeneralkategori ON ajkumum.okupasi = ajkgeneralkategori.id
																									LEFT JOIN ajkgeneralkelas ON ajkumum.kelaskontruksi = ajkgeneralkelas.id
																									WHERE ajkumum.id = '.AES::decrypt128CBC($_REQUEST['k'],ENCRYPTION_KEY).' AND
																												ajkgeneraltype.kode = "KKB"'));

			$metAlamatMember = str_replace("#","<br>",$metPreview['alamatdebitur']);
			$metAlamatObjek = str_replace("#","<br>",$metPreview['alamatobjek']);

			if ($metPreview['statusspajk']=="Request") {
				$metPreviewData = '<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>';
			}else{

			}

			if ($metPreview['statusspajk'] !="Request" ) {
				$vDataObjek= '<dt>Luas Bangunan&Tanah :</dt><dd> '.duit($metPreview['luasbangunan']).' / '.duit($metPreview['luastanah']).'</dd>
										  <dt>Okupasi :</dt><dd> '.$metPreview['okupasi'].'</dd>
										  <dt>Tahun Pembangunan :</dt><dd> '.$metPreview['tahunpembangunan'].'</dd>
										  <dt>Kelas Konstruksi :</dt><dd> '.$metPreview['kelas'].' - '.$metPreview['ketkelas'].'</dd>
										  <dt>Konstruksi Bangunan :</dt>
										  <dd>
										  	<div class="col-md-2">Dinding</div><div class="col-md-10">: '.$metPreview['kontruksibangundinding'].'</div>
								  	   	<div class="col-md-2">Atap</div><div class="col-md-10">: '.$metPreview['kontruksibangunatap'].'</div>
								  	   	<div class="col-md-2">Lantai</div><div class="col-md-10">: '.$metPreview['kontruksibangunlantai'].'</div>
								  	   	<div class="col-md-2">Tiang</div><div class="col-md-10">: '.$metPreview['kontruksibanguntiang'].'</div>
											</dd>
							  			<dt>Batas Bangunan&Jarak :</dt>
							  			<dd>
							  				<div class="col-md-2">Kiri</div><div class="col-md-10">: '.$metPreview['jarakbangunkiri'].'</div>
								  	   	<div class="col-md-2">Kanan</div><div class="col-md-10">: '.$metPreview['jarakbangunkanan'].'</div>
								  	   	<div class="col-md-2">Depan</div><div class="col-md-10">: '.$metPreview['jarakbangundepan'].'</div>
								  	   	<div class="col-md-2">Belakang</div><div class="col-md-10">: '.$metPreview['jarakbangunbelakang'].'</div>
								  		</dd>
										  <dt>Jenis Alat Pemadam :</dt><dd>'.$metPreview['jenispemadam'].'</dd>
										  <dt>Jenis Stok :</dt><dd>'.$metPreview['jenisstok'].'</dd>
										  <dt>Nilai Pertanggungan :</dt>
										  <dd>
										  	<div class="col-md-2">Bangunan</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalbangunan']).'</div>
								  	   	<div class="col-md-2">Perabot</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalperabot']).'</div>
								  	   	<div class="col-md-2">Stok</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalstok']).'</div>
								  	   	<div class="col-md-2">Mesin</div><div class="col-md-10">: '.duit($metPreview['nilaiappraisalmesin']).'</div>
								  		</dd>';

				if ($metPreview['photo1']!="") {	
					$vPhotoObjek1= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo1'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek1 = '';	
				}

				if ($metPreview['photo2']!="") {	
					$vPhotoObjek2= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo2'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek2 = '';	
				}

				if ($metPreview['photo3']!="") {	
					$vPhotoObjek3= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo3'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek3 = '';	
				}

				if ($metPreview['photo4']!="") {	
					$vPhotoObjek4= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photo4'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	
				}else{	
					$vPhotoObjek4 = '';	
				}

				$vPhotoObjek= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photodepan'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
											   <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photobelakang'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
											   <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokanan'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
											   <div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralSurvey.''.$metPreview['photokiri'].'" alt="" class="img-circle" width="200" height="200"/></a></div>
										   </center>
										   '.$vPhotoObjek1.''.$vPhotoObjek2.''.$vPhotoObjek3.''.$vPhotoObjek4.'';
			}else{
				$vDataObjek= '<div class="alert alert-warning fade in m-b-10"><strong>Tidak ada data objek!</strong> Data belum di survey.</div>';
				$vPhotoObjek= '<div class="alert alert-warning fade in m-b-10"><strong>Tidak ada data objek!</strong> Data belum di survey.</div>';
			}

			echo '<div id="content" class="content">
							<div class="row">
								<div class="col-lg-7">
									<div class="panel panel-inverse">
										<div class="panel-heading">
				            	<div class="panel-heading-btn">
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				              </div>
										  <h4 class="panel-title">Preview Data</h4>
										</div>
										<div class="panel-body">
											<div class="col-md-8">
			                  <div class="m-t-5">
													<dl class="dl-horizontal">
														<dt>Nomor SPAJK :</dt><dd><strong> '.$metPreview['nomorspajk'].'</strong></dd>
														<dt>Status SPAJK :</dt><dd><strong> '.$metPreview['statusspajk'].'</strong></dd>
														<h4 class="text-left">DATA DEBITUR</h4>
														<dt>Nama :</dt><dd> '.$metPreview['nama'].'</dd>
														<dt>Jenis Kelamin :</dt><dd> '.$metPreview['gender'].'</dd>
														<dt>Tanggal Lahir :</dt><dd> '._convertDate($metPreview['tgllahir']).'</dd>
														<dt>K.T.P :</dt><dd> '.$metPreview['ktp'].'</dd>
														<dt>Telephone :</dt><dd> '.$metPreview['hp'].'</dd>
														<dt>Alamat Debitur :</dt><dd> '.$metAlamatMember.'</dd>
														<h4 class="text-left">DATA OBJEK</h4>
														<dt>Nilai Diajukan :</dt><dd> '.duit($metPreview['nilaidiajukan']).'</dd>
														<dt>Alamat Objek :</dt><dd> '.$metAlamatObjek.'</dd>
														'.$vDataObjek.'
													</dl>
			                  </div>
						    			</div>
						    			<div class="col-md-4">
						    				<a href="../'.$PhotoGeneralDebitur.''.$metPreview['photodebitur'].'" data-lightbox="gallery-group-1">
													<img src="../'.$PhotoGeneralDebitur.''.$metPreview['photodebitur'].'" alt="" class="img-circle" width="200" height="200"/>
												</a>
									    </div>
										</div>
									</div>
								</div>
								<div class="col-lg-5">
									<div class="panel panel-inverse">
										<div class="panel-heading">
				            	<div class="panel-heading-btn">
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				              </div>
										  <h4 class="panel-title">Data Objek</h4>
										</div>
										<div class="panel-body">
											'.$vPhotoObjek.'
										</div>
									</div>
								</div>
							</div>
						</div>';
		break;

		default:
	} // switch
?>

<?php
 	_footer();

	echo '	</div>
				</div>';
	_javascript();
?>

<script>
	$(document).ready(function() {
	  App.init();
	  Demo.init();
	});


	$(document).ready(function() {
		App.init();
		//Demo.init();
		$(".active").removeClass("active");
		//$(".open").removeClass("open");
		document.getElementById("has_view").classList.add("active");
		document.getElementById("idhas_view").classList.add("active");
		<?php
		if ($_REQUEST['op']=="vAJK") {
		?>
		document.getElementById("idsub_viewdatabaruajk").classList.add("active");
		$("#data-pesertatemp").DataTable({
			responsive: true
		})
		<?php
		}elseif ($_REQUEST['op']=="vFLX") {
		?>
		document.getElementById("idsub_viewdatabarukpr").classList.add("active");
		$("#data-pesertatemp").DataTable({
			responsive: true
		})
		<?php
		}elseif ($_REQUEST['op']=="vKKB") {
		?>
		document.getElementById("idsub_viewdatabarukkb").classList.add("active");
		$("#data-pesertatemp").DataTable({
			responsive: true
		})
		<?php
		}elseif ($_REQUEST['op']=="inFLX") {
		?>
		document.getElementById("idsub_viewdatabarukpr").classList.add("active");
		<?php
		}else{

		}
		?>
	
		$('#inputmember').bootstrapValidator({
			err: {
				container: 'tooltip'
			},
			framework: 'bootstrap',
			icon: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},

			fields: {
				namaproduk: {
					validators: {	notEmpty: {	message: 'Silahkan pilih nama produk'	}	}
				},
				metalamatmember: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat member'	}	}
				},
				metkotamember: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat kota member'	}	}
				},
				metkodeposmember: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat kodepos member'	}	}
				},
				metalamatobjek: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat objek'	}	}
				},
				metkotaobjek: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat kota objek'	}	}
				},
				metkodeposobjek: {
					validators: {	notEmpty: {	message: 'Silahkan input alamat kodepos objek'	}	}
				},
				namatertanggung: {
					validators: {	notEmpty: {	message: 'Silahkan input nama tertanggung'	}	}
				},
				nomorktp: {
					validators: {	notEmpty: {	message: 'Silahkan input nomor KTP '	}	}
				},
				nomorpk: {
					validators: {	notEmpty: {	message: 'Silahkan input nomor PK'	}	}
				},
				namawilayah: {
					validators: {	notEmpty: {	message: 'Silahkan pilih nama wilayah'	}	}
				},
				tgllahir: {
					validators: {
						notEmpty: {	message: 'Silahkan input tanggal lahir'	},
						date: {	format: 'DD/MM/YYYY',
							message: 'Format tanggal lahir dd/mm/yyyy'
						}
					}
				},
				tglakad: {
					validators: {
						notEmpty: {	message: 'Silahkan input tanggal akad'	},
						date: {	format: 'DD/MM/YYYY',
							message: 'Format tanggal akad dd/mm/yyyy'
						}
					}
				},
				tenor: {
					validators: {	notEmpty: {	message: 'Silahkan input tenor (bulan)'	}	}
				},
				jnsklmn: {
					validators: {	notEmpty: {	message: 'Silahkan input jenis kelamin'	}	}
				},
				nilaiobjek: {
					validators: {	notEmpty: {	message: 'Silahkan input nilai objek'	}	}
				},
				plafon: {
					validators: {	notEmpty: {	message: 'Silahkan input plafon'	}	}
				}
			}
		});

		$("#tgllahir").datepicker({
			todayHighlight: !0,
			format:'dd/mm/yyyy'
		}).on('changeDate', function(e) {
			$('#inputmember').bootstrapValidator('revalidateField', 'tgllahir');
		});

		$("#tglakad").datepicker({
			todayHighlight: !0,
			format:'dd/mm/yyyy'
		}).on('changeDate', function(e) {
			$('#inputmember').bootstrapValidator('revalidateField', 'tglakad');
		});

		$('#plafon').mask('000,000,000,000,000' , {reverse: true});
		$('#nilaiobjek').mask('000,000,000,000,000' , {reverse: true});
		$('#tgllahir').mask('99/99/9999');
		$('#tglakad').mask('99/99/9999');
		$('#tenor').mask('000' , {reverse: true});
	});

	<?php
		if($_REQUEST['op']=='pFLX'){
			echo 'mygps('.$lat.','.$longitude.')';
		}
	?>
</script>
</body>

</html>
