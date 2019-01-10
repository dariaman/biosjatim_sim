<?php
include_once('../includes/functions.php');
include "../param.php";
if(isset($_REQUEST['type'])){
	$typedata = $_REQUEST['type'];
	$typedata = AES::decrypt128CBC($typedata, ENCRYPTION_KEY);
}else{
	header("location:../dashboard");
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
			<?php
			if($typedata == 'peserta'){
			?>
			<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Data Peserta</h4>
				    <form action="#" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-peserta" class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="primary">
								<th>No</th>
								<th>Broker</th>
								<th>Partner</th>
								<th>Product</th>
								<th>Debitnote</th>
								<th>ID Member</th>
								<th>Name</th>
								<th>DOB</th>
								<th>Age</th>
								<th>Plafond</th>
								<th>Tgl Akad</th>
								<th>Tenor</th>
								<th>Tgl Akhir</th>
								<th>Premium</th>
								<th>Status</th>
								<th>Branch</th>
							</tr>
						</thead>
                        <tbody>
                        <?php
				$querypeserta = mysql_query("SELECT
				ajkpeserta.id,
				ajkcobroker.`name` AS namebroker,
				ajkclient.`name` AS nameclient,
				ajkpolis.produk,
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.tgldebitnote,
				ajkpeserta.idpeserta,
				ajkpeserta.nomorktp,
				ajkpeserta.nama,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.plafond,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.totalpremi,
				ajkpeserta.astotalpremi,
				ajkpeserta.statusaktif,
				ajkcabang.`name` AS cabang,
				ajkpeserta.idpolicy,
				ajkclient.id
				FROM ajkpeserta
				INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
				INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
				WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif='Inforce' OR ajkpeserta.statusaktif='Lapse' OR ajkpeserta.statusaktif='Maturity')
				AND ajkpeserta.idbroker = '".$idbro."'
				AND ajkpeserta.idclient = '".$idclient."'
				ORDER BY ajkdebitnote.tgldebitnote DESC");
                        $totalpremi = 0;
                        $totalpflafon = 0;
						$li_row =1;
				    	while($rowquerypeserta = mysql_fetch_array($querypeserta)){;
							$idproduk = $rowquerypeserta['idpolicy'];
							$klientidpeserta = $rowquerypeserta['id'];
							$pesertabroker = $rowquerypeserta['namebroker'];
							$pesertapartner = $rowquerypeserta['nameclient'];
				    		$queryprod = mysql_query("SELECT * FROM ajkpolis WHERE idp = '".$idproduk."' AND idcost = '".$klientidpeserta."'");
				    		$rowprod = mysql_fetch_array($queryprod);
				    		$namaprod = $rowprod['produk'];
							$nomordn = $rowquerypeserta['nomordebitnote'];
							$idmember = $rowquerypeserta['idpeserta'];
							$namapeserta = $rowquerypeserta['nama'];
							$tgllahir = $rowquerypeserta['tgllahir'];
							$tgllahir = date('d-m-Y', strtotime($tgllahir));
				    		$usia = $rowquerypeserta['usia'];
							$plafond = $rowquerypeserta['plafond'];
							$plafond_format = number_format($plafond,0,'.',',');
							$tglakad = $rowquerypeserta['tglakad'];
							$tglakad = date('d-m-Y', strtotime($tglakad));
							$tenor = $rowquerypeserta['tenor'];
							$tenor_format = number_format($tenor,0,'.',',');
							$tglakhir = $rowquerypeserta['tglakhir'];
							$tglakhir = date('d-m-Y', strtotime($tglakhir));
							$totalpremi = $rowquerypeserta['totalpremi'];
							$totalpremi_format = number_format($totalpremi,0,'.',',');
				    		$statusaktif = $rowquerypeserta['statusaktif'];
				    		if($statusaktif=='Inforce'){
				    			$status = '<span class="label label-success">'.$statusaktif.'</span>';
				    		}else{
				    			$status = '<span class="label label-danger">'.$statusaktif.'</span>';
				    		}
				    		$namacabang = $rowquerypeserta['cabang'];
	                        echo '<tr class="odd gradeX">
		                            <td>'.$li_row.'</td>
									<td>'.$pesertabroker.'</td>
									<td>'.$pesertapartner.'</td>
									<td>'.$namaprod.'</td>
									<td>'.$nomordn.'</td>
									<td>'.$idmember.'</td>
									<td>'.$namapeserta.'</td>
									<td>'.$tgllahir.'</td>
									<td>'.$usia.'</td>
									<td class="text-right">'.$plafond_format.'</td>
									<td>'.$tglakad.'</td>
									<td class="text-right">'.$tenor_format.'</td>
									<td>'.$tglakhir.'</td>
									<td class="text-right">'.$totalpremi_format.'</td>
									<td>'.$status.'</td>
									<td>'.$namacabang.'</td>
                            	</tr>';
                        	$li_row++;
                        }

                        ?>

                        </tbody>
                    </table>

	                </form>
	            </div>
	            <!-- end section-container -->
	        </div>
	        <?php
			}elseif($typedata == 'debitnote'){
			?>
			<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Data Debitnote</h4>
				    <form action="#" id="form-debitnote" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-debitnote" class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="warning">
								<th>No</th>
								<th>Broker</th>
								<th>Partner</th>
								<th>Produk</th>
								<th>Date DN</th>
								<th>Debitnote</th>
								<th>Members</th>
								<th>Premium</th>
								<th>Status</th>
								<th>Paid Date</th>
								<th>Branch</th>
							</tr>
						</thead>
                        <tbody>
                        <?php
				$querydebitnote = mysql_query("SELECT
				Count(ajkpeserta.nama) AS jData,
				ajkcobroker.`name` AS namebroker,
				ajkclient.`name` AS nameclient,
				ajkpolis.produk,
				ajkcabang.`name` AS cabang,
				ajkdebitnote.id,
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.premiclient,
				ajkdebitnote.premiasuransi,
				ajkdebitnote.paidstatus,
				ajkdebitnote.paidtanggal,
				ajkdebitnote.tgldebitnote,
				ajkdebitnote.idproduk,
				ajkclient.id AS idclient
				FROM ajkdebitnote
				INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
				INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
				INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
				INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
				INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
				WHERE ajkdebitnote.del IS NULL
				AND ajkdebitnote.idbroker = '".$idbro."'
				AND ajkdebitnote.idclient = '".$idclient."'
				GROUP BY ajkdebitnote.id
				ORDER BY ajkdebitnote.tgldebitnote DESC");
                        $totalpremi = 0;
                        $totalpflafon = 0;
						$li_row =1;
				    	while($rowdebitnote = mysql_fetch_array($querydebitnote)){
							$idprod = $rowdebitnote['idproduk'];
							$dnklient = $rowdebitnote['id'];
				    		$namaprod = $rowdebitnote['produk'];
				    		$namabroker = $rowdebitnote['namebroker'];
				    		$namapartner = $rowdebitnote['nameclient'];
				    		$nomordn = $rowdebitnote['nomordebitnote'];
				    		$iddn = $rowdebitnote['id'];
				    		$tgldn = $rowdebitnote['tgldebitnote'];
				    		$tgldn = date('d-m-Y', strtotime($tgldn));
				    		$statupaid = $rowdebitnote['paidstatus'];
				    		$tglpaid = $rowdebitnote['paidtanggal'];
				    		if($tglpaid=="" OR $tglpaid == null OR $tglpaid == '0000-00-00'){
				    			$tglpaid = '';
				    		}else{
				    			$tglpaid = date('d-m-Y', strtotime($tglpaid));
				    		}
				    		$jdata = $rowdebitnote['jData'];
				    		$premiclient = $rowdebitnote['premiclient'];
				    		$premiasuransi = $rowdebitnote['premiasuransi'];
				    		$dncabang = $rowdebitnote['cabang'];
				    		if($statupaid=="Unpaid"){
				    			$statusp = '<span class="label label-danger">'.$statupaid.'</span>';
				    		}else{
				    			$statusp = '<span class="label label-success">'.$statupaid.'</span>';
				    		}
	                        echo '<tr class="odd gradeX">
		                            <td>'.$li_row.'</td>
									<td>'.$namabroker.'</td>
									<td>'.$namapartner.'</td>
									<td>'.$namaprod.'</td>
									<td>'.$tgldn.'</td>
									<td><a href="../_admincli/ajk.php?re=dlPdf&pID='.metEncrypt($nomordn).'&idd='.metEncrypt($iddn).'" target="_blank">'.$nomordn.'</a></td>
									<td><a href="../_admincli/ajk.php?re=dlPdf&pdf=member&pID='.metEncrypt($nomordn).'&idd='.metEncrypt($iddn).'" target="_blank">'.$jdata.'</a></td>
									<td class="text-right">'.number_format($premiclient,2,'.',',').'</td>
									<td>'.$statusp.'</td>
									<td>'.$tglpaid.'</td>
									<td>'.$dncabang.'</td>
                            	</tr>';
                        	$li_row++;
                        }

                        ?>

                        </tbody>
                    </table>

	                </form>
	            </div>
	            <!-- end section-container -->
	        </div>

			<?php
			}elseif($typedata=="pesertaSPK"){
			?>
						<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0"></h4>
				    <form action="#" id="form-debitnote" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-pesertaspk" class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="warning">
								<th>No</th>
								<th>Produk</th>
								<th>Status</th>
								<th>Partner</th>
								<th>SPAK</th>
								<th>Nama</th>
								<th>Tgl Lahir</th>
								<th>Usia</th>
								<th>Alamat</th>
								<th>Awal Asuransi</th>
								<th>Tenor (bln)</th>
								<th>Akhir Asuransi</th>
								<th>Plafond</th>
								<th>Premi</th>
								<th>EM(%)</th>
								<th>Total Premi</th>
								<th>Grace Period</th>
								<th>Cabang</th>
								<th>Staff</th>
								<th>Tgl Input</th>
								<th>Tgl Approve</th>
							</tr>
						</thead>
                        <tbody>
                        <?php
						$queryspk = mysql_query("SELECT *, ajkspk.dob as tgllahir FROM ajkspk
						LEFT JOIN ajkpolis ON idproduk = ajkpolis.idp
						LEFT JOIN useraccess ON useraccess.id = '".$iduser."'
						WHERE ajkspk.idbroker = '".$idbro."' AND ajkspk.idpartner = '".$idclient."'
						AND useraccess.branch = ajkspk.cabang
						AND ajkspk.del IS NULL
						ORDER BY ajkspk.input_date DESC");
						$li_row = 1;
						while($rowspk = mysql_fetch_array($queryspk)){
							$idproduk = $rowspk['idproduk'];
							$idbroker = $rowspk['idbroker'];
							$idpartner = $rowspk['idpartner'];
							$querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id = '".$idbroker."'");
							$rowbro = mysql_fetch_array($querybroker);
							$namabroker = $rowbro['name'];
							$querypartner = mysql_query("SELECT * FROM ajkclient WHERE id = '".$idpartner."'");
							$rowpartner = mysql_fetch_array($querypartner);
							$namamitra = $rowpartner['name'];
							$produk = $rowspk['produk'];
							$nomorspk = $rowspk['nomorspk'];
							$statusspk = $rowspk['statusspk'];
							$nama = $rowspk['nama'];
							$dob = $rowspk['tgllahir'];
							$dob_format = date('d-m-Y', strtotime($dob));
							$usia = $rowspk['usia'];
							$alamat = $rowspk['alamat'];
							$tglakad = $rowspk['tglakad'];
							$tglakad_format = date('d-m-Y', strtotime($tglakad));
							$tenor = $rowspk['tenor'];
							$tglakhir = $rowspk['tglakhir'];
							$tglakhir_format = date('d-m-Y', strtotime($tglakhir));
							$plafond = $rowspk['plafond'];
							$plafond_format = number_format($plafond,0,".",",");
							$premi = $rowspk['premi'];
							$premi_format = number_format($premi,0,".",",");
							$nettpremi = $rowspk['nettpremi'];
							$nettpremi_format = number_format($nettpremi,0,".",",");
							$em = $rowspk['em'];
							$mppbln = $rowspk['mppbln'];
							$cabang = $rowspk['cabang'];
							$cabang = $rowspk['cabang'];
							$firstname = $rowspk['firstname'];
							$input_date = $rowspk['input_date'];

							$input_date_format = date('d-m-Y', strtotime($input_date));
							$approve_date = $rowspk['approve_date'];
							$approve_date_format = date('d-m-Y', strtotime($approve_date));
							if($statusspk!="Request" AND $statusspk!="Pending" AND $statusspk!="Batal"){
								$linknama = '<a href="../modules/modPdfdl_front.php?pdf=_spk&ids='.AES::encrypt128CBC($nomorspk, ENCRYPTION_KEY).'&idp='.AES::encrypt128CBC($idproduk, ENCRYPTION_KEY).'&idc='.AES::encrypt128CBC($idclient, ENCRYPTION_KEY).'&idb='.AES::encrypt128CBC($idbro, ENCRYPTION_KEY).'" target="_blank">'.$nama.'</a>';
							}else{
								$linknama = $nama;
							}

							if($statusspk=="Aktif"){
								$statusspk = '<span class="label label-success">'.$statusspk.'</span>';
							}elseif($statusspk=="PreApproval"){
								$statusspk = '<span class="label label-info">'.$statusspk.'</span>';
							}elseif($statusspk=="Proses"){
								$statusspk = '<span class="label label-primary">'.$statusspk.'</span>';
							}elseif($statusspk=="Request"){
								$statusspk = '<span class="label label-lime">'.$statusspk.'</span>';
							}elseif($statusspk=="Pending"){
								$statusspk = '<span class="label label-grey">'.$statusspk.'</span>';
							}elseif($statusspk=="Batal"){
								$statusspk = '<span class="label label-danger">'.$statusspk.'</span>';
							}elseif($statusspk=="Tolak"){
								$statusspk = '<span class="label label-inverse">'.$statusspk.'</span>';
							}

	                        echo '<tr class="odd gradeX">
		                            <td>'.$li_row.'</td>
									<td>'.$produk.'</td>
									<td>'.$statusspk.'</td>
									<td>'.$namamitra.'</td>
									<td>'.$nomorspk.'</td>
									<td>'.$linknama.'</td>
									<td>'.$dob.'</td>
									<td>'.$usia.'</td>
									<td>'.$alamat.'</td>
									<td>'.$tglakad_format.'</td>
									<td>'.$tenor.'</td>
									<td>'.$tglakhir_format.'</td>
									<td>'.$plafond_format.'</td>
									<td>'.$premi_format.'</td>
									<td>'.$em.'</td>
									<td>'.$nettpremi_format.'</td>
									<td>'.$mppbln.'</td>
									<td>'.$cabang.'</td>
									<td>'.$firstname.'</td>
									<td>'.$input_date_format.'</td>
									<td>'.$approve_date_format.'</td>
                            	</tr>';
                        	$li_row++;
                        }

                        ?>

                        </tbody>
                    </table>

	                </form>
	            </div>
	            <!-- end section-container -->
	        </div>
			<?php
			}
			?>
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
			<?php
			if($typedata == 'peserta'){
			?>
				$(".active").removeClass("active");
				document.getElementById("has_master").classList.add("active");
				document.getElementById("idsub_master").classList.add("active");
				document.getElementById("idsub_peserta").classList.add("active");

				$("#data-peserta").DataTable({
					responsive: true
				})
			<?php
			}else if($typedata == 'debitnote'){
			?>
				$(".active").removeClass("active");
				document.getElementById("has_master").classList.add("active");
				document.getElementById("idsub_master").classList.add("active");
				document.getElementById("idsub_debitnote").classList.add("active");

				$("#data-debitnote").DataTable({
					responsive: true
				})

			<?php
			}else if($typedata == 'pesertaSPK'){
			?>
				$(".active").removeClass("active");
				document.getElementById("has_master").classList.add("active");
				document.getElementById("idsub_master").classList.add("active");
				document.getElementById("idsub_pesertaspk").classList.add("active");

				$("#data-pesertaspk").DataTable({
					responsive: true
				})
			<?php
			}
			?>

		});
	</script>
</body>

</html>
