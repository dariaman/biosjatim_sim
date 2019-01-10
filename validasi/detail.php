<?php
include "../param.php";
if(isset($_REQUEST['inpt'])){
	$input_time = $_REQUEST['inpt'];
	$input_cabang = $_REQUEST['cab'];
}else{
	header("location:../validasi");
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user,$namauser,$photo,$logo);
?>

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
		title: "Adonai Location"
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
			    <h4 class="m-t-0">Validasi Data Member Deklarasi</h4>
				<div class="section-container section-with-top-border">
				    <?php
				    $li_row = 1;

				    $input_time = AES::decrypt128CBC($input_time, ENCRYPTION_KEY);
				    $input_cabang = AES::decrypt128CBC($input_cabang, ENCRYPTION_KEY);
				    	if($level=='8'){
				    		$status = 'Pending';
				    		$qtmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND cabang='".$input_cabang."' AND input_time = '".$input_time."'");
				    	}else{
				    		$status = 'Upload';
				    		$qtmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND cabang='".$input_cabang."' AND input_time = '".$input_time."'");
				    	}
				    $qpersertatmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND cabang='".$input_cabang."' AND input_time = '".$input_time."'");
				    $rowqtmp = mysql_fetch_array($qpersertatmp);
				    $idstaff = $rowqtmp['input_by'];
				    $tglinput = $rowqtmp['input_time'];
				    $idprod = $rowqtmp['idpolicy'];
				    $namafiles = $rowqtmp['filename'];
				    $tglinput = date('d-m-Y', strtotime($tglinput));
				    $inputdate = $rowqtmp['input_time'];
				    $queryinput = mysql_query("SELECT * FROM  useraccess WHERE id ='".$idstaff."'");
				    $rowinpunt = mysql_fetch_array($queryinput);
				    $namastaff = $rowinpunt['username'];
				    $qupolicy = mysql_query("SELECT * FROM ajkpolis WHERE id = '".$idprod."' AND idcost = '".$idclient."'");
				    $rowpolicy = mysql_fetch_array($qupolicy);
				    $namaprod = $rowpolicy['produk'];
				    $levelval = $rowpolicy['levelvalidasi'];
				    $inputdate = AES::encrypt128CBC($inputdate, ENCRYPTION_KEY);
				    $status = AES::encrypt128CBC($status, ENCRYPTION_KEY);
				    $general = $rowpolicy['general'];
				    $idprtner = $rowpolicy['idcost'];
				    $idproduk = $rowpolicy['id'];


	if ($general['general']!="T") {
		$_metGeneral = '<th class="text-center" width="1%">Nomor SPAK</td>
						<!--<th class="text-center" width="1%">Paket</td>
						<th class="text-center" width="10%">Okupasi</td>
						<th class="text-center" width="1%">Kelas</td>
						<th class="text-center" width="1%">Lokasi Objek</td>
						<th class="text-center" width="1%">Alamat</td>-->';
		$_metGeneralPremi = '<!--<th class="text-center" width="1%">Rate PA</td>
							<th class="text-center" width="1%">Premi PA</td>
							<th class="text-center" width="1%">Rate Fire</td>
							<th class="text-center" width="10%">Premi Fire</td>-->';
		$approve = '<th><center><input type="checkbox" onClick="toggle(this)" /> Check All</center></th>';
	}else{
		$_metGeneral = '';
		$_metGeneralPremi = '';
	}
	                    	//CEK TIPE PRODUK GENERAL
					?>

				    <form action="dovalidasi.php?idp=<?php echo $inputdate ?>&tval=<?php echo $status ?>" id="validasi" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-table" data-order='[[1,"asc"]]' class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="danger">
								<th width="1%">No</th>
								<th width="1%"><center><input type="checkbox" onClick="toggle(this)" /><br />All</center></th>
								<th>Nomor SPK</th>
								<?php echo $_metGeneral ?>
								<th>Nama Tertanggung</th>
								<th>Cabang</th>
								<th>Nomor KTP</th>
								<th>Nomor PK</th>
								<th class="text-center" width="1%">Tanggal<br />Lahir</th>
								<th class="text-center" width="1%">Usia</th>
								<th class="text-center" width="1%">Tanggal<br /> Akad</th>
								<th class="text-center" width="1%">Tanggal<br />Akhir</th>
								<th class="text-center" width="1%">Tenor<br />(bulan)</th>
								<th class="text-center">Nilai Pertanggungan<br />(Plafond)</th>
								<th class="text-center" width="1%">Rate Premi</th>
								<th class="text-center">Premi</th>
								<th class="text-center">Status</th>
								<?php echo $_metGeneralPremi ?>
								<!--<th class="text-center">OPT</th>-->
							</tr>
						</thead>
                        <tbody>
                        <?php
                        while($rtmp = mysql_fetch_array($qtmp)){
                        	$namatertanggung = $rtmp['nama'];
                        	$nomorspk = $rtmp['nomorspk'];
                        	$nomorspak = $rtmp['nomorspak'];
                        	$cabang = $rtmp['cabang'];
                        	$ktp = $rtmp['nomorktp'];
                        	$npk = $rtmp['nomorpk'];
                        	$tgllahir = $rtmp['tgllahir'];
                        	$tgllahir = date("d-m-Y", strtotime($tgllahir));
                        	$usia = $rtmp['usia'];
                        	$tglakad = $rtmp['tglakad'];
                        	$tglakad = date("d-m-Y", strtotime($tglakad));
                        	$tglakhir = $rtmp['tglakhir'];
                        	$tglakhir = date("d-m-Y", strtotime($tglakhir));
                        	$tenor = $rtmp['tenor'];
                        	$tenor = number_format($tenor,0,".",",");
                        	$plafon = $rtmp['plafond'];
                        	$plafon = number_format($plafon,0,".",",");
                        	$premirate = $rtmp['premirate'];
                        	$premi = $rtmp['premi'];
                        	$premi = number_format($premi,0,".",",");
                        	$qcab = mysql_query("SELECT * FROM ajkcabang WHERE er = '$cabang' AND idclient = '$idclient'");
                        	$rcab = mysql_fetch_array($qcab);
                        	$nmcab  = $rcab['name'];

                        	$paketasuransi = $rtmp['paketasuransi'];
                        	$cekGenPaket = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraltype WHERE type="Paket Asuransi" AND kode="'.$paketasuransi.'"'));
                        	$okupasi = $rtmp['okupasi'];
                        	$cekGenPertanggungan = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraltype WHERE type="Okupasi" AND kode="'.$okupasi.'"'));
                        	$kelas = $rtmp['kelas'];
                        	$cekGenKelas = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraltype WHERE type="Kelas" AND kode="'.$kelas.'"'));
                        	$lokasi = $rtmp['lokasi'];
                        	$cekGenLokasi = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraltype WHERE type="Lokasi" AND kode="'.$lokasi.'"'));
                        	$nilaijaminan = $rtmp['nilaijaminan'];
                        	$nilaijaminan = number_format($nilaijaminan,0,".",",");
                        	//$alamatobjek = $rtmp['alamatobjek'];
                        	//$kota = $rtmp['kota'];
                        	//$provinsi = $rtmp['provinsi'];
                        	//$kodepos = $rtmp['kodepos'];
                        	$alamat = $rtmp['alamatobjek'].','. $rtmp['kota'].','. $rtmp['provinsi'].','. $rtmp['kodepos'];
							$premifire = $rtmp['premifire'];
                        	$premifire = number_format($premifire,0,".",",");
                        	$premipa = $rtmp['premipa'];
                        	$premipa = number_format($premipa,0,".",",");


/*
	if ($rpolis['general']=="T") {
								if($rowpolicy=="Age"){
                        			$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idprtner."' AND idpolis='".$idproduk."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto"));
                        		}else{
									$qrate = mysql_fetch_array(mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idprtner."' AND idpolis='".$idproduk."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto"));
                        		}
                        	}else{
                        		$cekajkumum = mysql_fetch_array(mysql_query('SELECT * FROM ajkumum WHERE nomorspajk="'.strtoupper($nomorspak).'" AND nama="'.strtoupper($namatertanggung).'" AND tgllahir="'.$rtmp['tgllahir'].'" AND nilaidiajukan="'.$rtmp['plafond'].'" AND tenor="'.$rtmp['tenor'].'"'));
                        		$qrate = mysql_fetch_array(mysql_query("SELECT ajkpolis.idgeneral,
																	 ajkpolis.byrategeneral,
																	 ajkpolis.classgeneral,
																	 ajkpolis.calculatedrate,
																	 ajkrategeneral.id,
																	 ajkrategeneral.tenorstart,
																	 ajkrategeneral.tenorend,
																	 ajkrategeneral.plafondstart,
																	 ajkrategeneral.plafondend,
																	 ajkrategeneral.lokasi,
																	 ajkrategeneral.quarantee,
																	 ajkrategeneral.kelas,
																	 ajkrategeneral.rate,
																	 ajkrategeneral.type
															FROM ajkpolis
															INNER JOIN ajkrategeneral ON ajkpolis.id = ajkrategeneral.idproduk
															WHERE ajkpolis.id = ".$idproduk." AND
																  IF(ajkpolis.byrategeneral='Tenor', ".$tenor." BETWEEN ajkrategeneral.tenorstart AND ajkrategeneral.tenorend, ajkrategeneral.tenorstart IS NULL) AND
																  IF(ajkpolis.byrategeneral='Plafond', ".$rtmp['plafond']." BETWEEN ajkrategeneral.plafondstart AND ajkrategeneral.plafondend, ajkrategeneral.plafondstart IS NULL) AND
																  IF(ajkpolis.classgeneral='Ya', ajkrategeneral.kelas=".$cekajkumum['kelaskontruksi']." ,ajkrategeneral.kelas IS NULL) AND
																  ajkrategeneral.lokasi=".$cekajkumum['wilayah']." AND
																  ajkrategeneral.quarantee=".$cekajkumum['okupasi']." AND
																  ajkrategeneral.status='Aktif'"));
                        	}
*/

                        	if ($general['general']!="T") {
							/*
								$queryphotoklaim = mysql_query("SELECT * FROM ajkphotoklaim WHERE idpeserta = '".$ktp."' AND type = 'awal'"); $jmlphoto = mysql_num_rows($queryphotoklaim);
                        		if($jmlphoto>0){
                        			$classdisabled = '';
                        		}else{
                        			$classdisabled = 'disabled';
                        		}
							*/
                        		$classdisabled = '';
                        		$metRateGeneral = mysql_fetch_array(mysql_query('SELECT * FROM ajkrategeneral WHERE idbroker="'.$idbro.'" AND
																									   idclient="'.$idclient.'" AND
																									   idproduk="'.$idprod.'" AND
																									   '.$tenor.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$lokasi.'" AND
																									   quarantee = "'.$okupasi.'" AND
																									   kelas = "'.$kelas.'" AND
																									   status="Aktif"'));

                        		$generaldetail = '<td class="text-center" width="1%">'.$nomorspak.'</td>
                        						<!--<td class="text-center" width="1%">'.$cekGenPaket['keterangan'].'</td>
                        						<td class="text-center" width="10%">'.$cekGenPertanggungan['keterangan'].'</td>
                        						<td class="text-center" width="1%">'.$cekGenKelas['keterangan'].'</td>
                        						<td class="text-center" width="1%">'.$cekGenLokasi['keterangan'].'</td>
                        						<td class="text-center" width="1%">'.$alamat.'</td>-->';
                        		$GeneralPremiDetail = '<!--<td class="text-center" width="1%">'.$metRateGeneral['ratepa'].'</td>
                        							<td class="text-center" width="1%">'.$premipa.'</td>
                        							<td class="text-center" width="1%">'.$metRateGeneral['ratefire'].'</td>
                        							<td class="text-center" width="10%">'.$premifire.'</td>-->';
                        	}else{
                        		$generaldetail = '';
                        		$GeneralPremiDetail = '';
                        		$classdisabled ='';
                        	}
	                        echo '<tr class="odd gradeX">
		                            <td>'.$li_row.'</td>
									<td>
									<div class="form-group">
										<center><input id="approve_'.$li_row.'" name="approve[]" value="'.$ktp.'"  type="checkbox" '.$classdisabled.'></center>
									</div>
									</td>
									<td>'.$nomorspk.'</td>
									'.$generaldetail.'
									<td>'.$namatertanggung.'</td>
									<td>'.$nmcab.'</td>
									<td>'.$ktp.'</td>
									<td>'.$npk.'</td>
									<td class="text-center">'.$tgllahir.'</td>
									<td class="text-center">'.$usia.'</td>
									<td class="text-center">'.$tglakad.'</td>
									<td class="text-center">'.$tglakhir.'</td>
									<td class="text-center">'.$tenor.'</td>
									<td class="text-right">'.$plafon.'</td>
									<td class="text-right">'.$premirate.'</td>
									<td class="text-right">'.$premi.'</td>
									<td class="text-right">'.$rtmp['statusaktif'].'</td>
									'.$GeneralPremiDetail.'';
                        	$queryfotoklaim = mysql_query("SELECT * FROM ajkphotoklaim WHERE idpeserta = '".$ktp."' AND type ='awal'");
                        	$rowlat = mysql_fetch_array($queryfotoklaim);
                        	$countfoto = mysql_num_rows($queryfotoklaim);
                        	$long = "";
                        	$lat = "";
                        	if (!$rowlat['id']) {

                        	}else{
							echo '<td class="text-center">
									<div id="myModal-'.$ktp.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									        <div class="modal-body">';
                        						while($rowfotoklaim = mysql_fetch_array($queryfotoklaim)){
                        							$fotoklaim = $rowfotoklaim['photo'];
                        							$inputfoto = $rowfotoklaim['input_date'];
                        							$inputfoto = date("Y-m-d", strtotime($inputfoto));
                        							$foldername = date("y",strtotime($inputfoto)).date("m",strtotime($inputfoto));
                        							$path = '../myFiles/_photogeneral/'.$foldername.'/';
                        							echo '<img src="../myFiles/_photogeneral/'.$foldername.'/'.$fotoklaim.'" class="img-responsive">';
                        						}
					                        	$lat = $rowlat['latitude'];
					                        	$long = $rowlat['longitude'];
									        echo'</div>
									    </div>
									  </div>
									</div>';
									if($countfoto>1){
                        				echo '<a title="View" data-toggle="modal" href="#myModal-'.$ktp.'">
	                                        <span class="fa-stack fa-2x text-primary"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-camera-retro fa-stack-1x fa-inverse"></i></span>
											</a>';
                        			}
									if($lat !=="" AND $lat !== "0" AND $lat !== null){
										echo '<a title="Maps" href="javascript:mygps('.$lat.','.$long.');">
	                                        <span class="fa-stack fa-2x text-warning"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-street-view fa-stack-1x fa-inverse"></i></span>
											</a>';
									}
									echo'</td>';
                        		}
                            	echo'</tr>';
                        	$li_row++;

                        	if($levelval==3){
                        		$nilaival = 'Checker';
                        	}elseif($levelval==2){
                        		$nilaival = 'Approved';
                        	}
                        	$decstatus = AES::decrypt128CBC($status, ENCRYPTION_KEY);
                        	if($decstatus=="Pending"){
                        		$nilaival = 'Approved';
                        	}else{
                        		$nilaival = 'Checker';
                        	}
                        }
				    	if ($level==7 OR $level==8) {
				    		$approveDeklrasi = '<input type="submit" name="sub" class="btn btn-success width-xs" value="'.$nilaival.'">';
				    	}else{
				    		$approveDeklrasi = '';
				    	}
                        ?>

                        </tbody>
                    </table>
                    	<div class="form-group m-b-0">
				             <label class="control-label col-sm-12"></label>
				             <div class="col-sm-6"><?php echo $approveDeklrasi;	?></div>
				         </div>
	                </form>
	            </div>
	            <div id="mapCanvas" class="map-canvas"></div>
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
			//document.getElementById("has_upload").classList.add("active");
			//document.getElementById("idsub_upload").classList.add("active");
			//document.getElementById("idsub_datavalidasi").classList.add("active");
			//$('#navsubul_manage').css('display','block');

			$('#validasi').bootstrapValidator({
				framework: 'bootstrap',
				fields: {
					'approve[]': {
		                validators: {
		                    choice: {
		                        min: 1,
		                        message: 'Silahkan dipilih data yang ingin diapprove/checker'
		                    }
		                }
		            }
				}
			});

			function namaproduk(kodeprod){
				data = $.ajax({
					url: 'data.php',
					global: false,
					type: "POST",
					data: {functionname: 'produk', idprod:kodeprod},
					dataType: 'json',
					async:false
				}
				).responseText;
				return data;
			}

			$("#namaproduk").change(function(){
				var idprod = document.getElementById('namaproduk').value;
				$('#tglupload').html(namaproduk(product));
			});

			$("#data-table").DataTable({
				responsive: true,
				paging: false,
				sort:false
			})


		});
	</script>
</body>

</html>
