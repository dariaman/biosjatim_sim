<?php
include "../param.php";
if(isset($_REQUEST['inpt'])){
	$input_time = $_REQUEST['inpt'];
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
	var checkboxes = document.querySelectorAll('input[type="checkbox"]');
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
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Upload Foto Property</h4>
				    <?php
				    $li_row = 1;

				    $input_time = AES::decrypt128CBC($input_time, ENCRYPTION_KEY);
				    	if($level=='8'){
				    		$status = 'Pending';
				    		$qtmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND input_time = '".$input_time."'");
				    	}else{
				    		$status = 'Upload';
				    		$qtmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND input_time = '".$input_time."'");
				    	}
				    $qpersertatmp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE statusaktif = '".$status."' AND idbroker = '".$idbro."' AND idclient = '".$idclient."' AND input_time = '".$input_time."'");
				    $rowqtmp = mysql_fetch_array($qpersertatmp);
				    $idstaff = $rowqtmp['input_by'];
				    $tglinput = $rowqtmp['input_time'];
				    $idprod = $rowqtmp['idpolicy'];
				    $namafiles = $rowqtmp['filename'];
				    $tglinput = date('d-m-Y', strtotime($tglinput));
				    $inputdate = $rowqtmp['input_time'];
				    $idpeserta = $rowqtmp['input_time'];
				    $queryinput = mysql_query("SELECT * FROM  useraccess WHERE id ='".$idstaff."'");
				    $rowinpunt = mysql_fetch_array($queryinput);
				    $namastaff = $rowinpunt['username'];
				    $qupolicy = mysql_query("SELECT * FROM ajkpolis WHERE idp = '".$idprod."' AND idcost = '".$idclient."'");
				    $rowpolicy = mysql_fetch_array($qupolicy);
				    $namaprod = $rowpolicy['produk'];
				    $levelval = $rowpolicy['levelvalidasi'];
				    $inputdate = AES::encrypt128CBC($inputdate, ENCRYPTION_KEY);
				    $status = AES::encrypt128CBC($status, ENCRYPTION_KEY);
				    $general = $rowpolicy['general'];
				    $queryphotoklaim = mysql_query("SELECT * FROM ajkphotoklaim
					WHERE idpeserta = ''
					AND type = 'awal'");
	                    	//CEK TIPE PRODUK GENERAL
					?>

				    <form action="javascript:;" id="upload" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-table" data-order='[[1,"asc"]]' class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="danger">
								<th>No</th>
								<th>Nama Tertanggung <span class="text-danger">*</span></th>
								<th>Cabang</th>
								<th>Nomor KTP</th>
								<th>Upload Foto</th>
							</tr>
						</thead>
                        <tbody>
                        <?php
                        $ktp = null;
                        while($rtmp = mysql_fetch_array($qtmp)){
                        	$namatertanggung = $rtmp['nama'];
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
                        	$premi = $rtmp['premi'];
                        	$premi = number_format($premi,0,".",",");
                        	$qcab = mysql_query("SELECT * FROM ajkcabang WHERE er = '$cabang' AND idclient = '$idclient'");
                        	$rcab = mysql_fetch_array($qcab);
                        	$nmcab  = $rcab['name'];

                        	$paketasuransi = $rtmp['paketasuransi'];
                        	$okupasi = $rtmp['okupasi'];
                        	$kelas = $rtmp['kelas'];
                        	$lokasi = $rtmp['lokasi'];
                        	$nilaijaminan = $rtmp['nilaijaminan'];
                        	$nilaijaminan = number_format($nilaijaminan,0,".",",");
                        	$alamatobjek = $rtmp['alamatobjek'];
                        	$kota = $rtmp['kota'];
                        	$provinsi = $rtmp['provinsi'];
                        	$kodepos = $rtmp['kodepos'];
                        	$premifire = $rtmp['premifire'];
                        	$premifire = number_format($premifire,0,".",",");
                        	$premipa = $rtmp['premipa'];
                        	$premipa = number_format($premipa,0,".",",");
							$ktpencrypt = AES::encrypt128CBC($ktp, ENCRYPTION_KEY);
                        	echo '<div id="myModal-'.$ktp.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									        <div class="modal-body">';
                        						$queryfotoklaim = mysql_query("SELECT * FROM ajkphotoklaim WHERE idpeserta = '".$ktp."' AND type ='awal'");
                        						$rowlat = mysql_fetch_array($queryfotoklaim);
                        						$countfoto = mysql_num_rows($queryfotoklaim);
                        						$long = "";
                        						$lat = "";
                        						while($rowfotoklaim = mysql_fetch_array($queryfotoklaim)){
                        							$fotoklaim = $rowfotoklaim['photo'];
                        							$inputfoto = $rowfotoklaim['input_date'];
                        							$inputfoto = date("Y-m-d", strtotime($inputfoto));
                        							$foldername = date("y",strtotime($inputfoto)).date("m",strtotime($inputfoto));
                        							$path = '../myFiles/_photogeneral/'.$foldername.'/';
                        							echo '<img src="'.$path.$fotoklaim.'" class="img-responsive">';
                        						}
					                        	$lat = $rowlat['latitude'];
					                        	$long = $rowlat['longitude'];
									        echo'</div>
									    </div>
									  </div>
									</div>';
                        	if ($general['general']=="Y") {

                        		$metRateGeneral = mysql_fetch_array(mysql_query('SELECT * FROM ajkrategeneral WHERE idbroker="'.$idbro.'" AND
																									   idclient="'.$idclient.'" AND
																									   idproduk="'.$idprod.'" AND
																									   '.$tenor.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$lokasi.'" AND
																									   quarantee = "'.$okupasi.'" AND
																									   kelas = "'.$kelas.'" AND
																									   status="Aktif"'));

                        		$generaldetail = '<td class="text-center" width="1%">'.$nilaijaminan.'</td>
                        						<td class="text-center" width="1%">'.$paketasuransi.'</td>
                        						<td class="text-center" width="10%">'.$okupasi.'</td>
                        						<td class="text-center" width="1%">'.$okupasi.'</td>
                        						<td class="text-center" width="1%">'.$alamatobjek.'</td>';
                        		$GeneralPremiDetail = '<td class="text-center" width="1%">'.$metRateGeneral['ratepa'].'</td>
                        							<td class="text-center" width="1%">'.$premipa.'</td>
                        							<td class="text-center" width="1%">'.$metRateGeneral['ratefire'].'</td>
                        							<td class="text-center" width="10%">'.$premifire.'</td>';
                        	}else{
                        		$generaldetail = '';
                        		$GeneralPremiDetail = '';
                        	}
	                        echo '<tr class="odd gradeX">
		                            <td>'.$li_row.'</td>
									<td>'.$namatertanggung.'</td>
									<td>'.$nmcab.'</td>
									<td>'.$ktp.'</td>
									<td class="text-center">
									<a title="Upload"  href="generalphoto.php?inpt='.$_REQUEST['inpt'].'&idk='.$ktp.'">
	                                        <span class="fa-stack fa-2x text-success">
												<i class="fa fa-circle fa-stack-2x"></i>
												<i class="fa fa-camera fa-stack-1x fa-inverse"></i>
											</span>
											</a>';
                        			if($countfoto>1){
                        				echo '<a title="View" data-toggle="modal" href="#myModal-'.$ktp.'">
	                                        <span class="fa-stack fa-2x text-primary">
												<i class="fa fa-circle fa-stack-2x"></i>
												<i class="fa fa fa-camera-retro fa-stack-1x fa-inverse"></i>
											</span>
											</a>';
                        			}
									if($lat !=="" AND $lat !== "0" AND $lat !== null){
										echo '<a title="Maps" href="javascript:mygps('.$lat.','.$long.');">
	                                        <span class="fa-stack fa-2x text-warning">
												<i class="fa fa-circle fa-stack-2x"></i>
												<i class="fa fa fa-street-view fa-stack-1x fa-inverse"></i>
											</span>
											</a>';
									}


									echo'</td>
                            	</tr>';
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

                        ?>

                        </tbody>
                    </table>
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
			document.getElementById("has_upload").classList.add("active");
			document.getElementById("idsub_upload").classList.add("active");
			document.getElementById("idsub_datavalidasi").classList.add("active");
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
				responsive: true
			})



		});
	</script>
</body>

</html>
