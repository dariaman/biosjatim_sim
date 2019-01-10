<?php
include "../param.php";
include_once('../includes/functions.php');
if (isset($_REQUEST['type'])) {
    $typedata = $_REQUEST['type'];
    $typedata = AES::decrypt128CBC($typedata, ENCRYPTION_KEY);
} else {
    header("location:../dashboard");
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user, $namauser, $photo, $logo);
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
        _header($user, $namauser, $photo, $logo, $logoklient);
        _sidebar($user, $namauser, '', '');
        ?>
		<!-- begin #content -->
		<div id="content" class="content">
				<?php
      if ($typedata == 'peserta') {
          ?>
					<div class="panel p-30">
						<h4 class="m-t-0">Data Peserta</h4>
						<div class="section-container section-with-top-border">
					    <form action="#" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
		            <table id="data-peserta" class="table table-bordered table-hover" width="100%">
		              <thead>
										<tr class="primary">
											<th>No</th>
											<th>Produk</th>
											<th>No Pinjaman</th>
											<th>ID Pesserta</th>
											<th>Nama</th>
											<th>Tgl. Lahir</th>
											<th>Umur</th>
											<th>Plafond</th>
											<th>Tgl. Akad</th>
											<th>Tenor</th>
											<th>Tgl. Akhir</th>
											<th>Premi</th>
											<th>Status</th>
											<th>Cabang</th>
											<th>Asuransi</th>
										</tr>
									</thead>
		              <tbody>
		              </tbody>
		            </table>
			        </form>
		        </div>
		          <!-- end section-container -->
		      </div>
      	<?php
      } elseif ($typedata == 'debitnote') {
      	?>
				<div class="panel p-30">
					<h4 class="m-t-0">Data Nota Debit</h4>
					<div class="section-container section-with-top-border">
						<form action="#" id="form-debitnote" class="form-horizontal" method="post" enctype="multipart/form-data">
							<table id="data-debitnote" class="table table-bordered table-hover" width="100%">
								<thead>
									<tr class="warning">
										<th>No</th>
										<th>Produk</th>
										<th>Asuransi</th>
										<th>Tgl. DN</th>
										<th>Nota Debit</th>
										<th>Peserta</th>
										<th>Premi</th>
										<th>Status</th>
										<th>Tgl. Bayar</th>
										<th>Cabang</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</form>
					</div>
					<!-- end section-container -->
		    </div>

				<?php
      } elseif ($typedata=="pesertaSPK") {
        ?>
				<div class="panel p-30">
				<h4 class="m-t-0">Data Peserta SPK</h4>
				<div class="section-container section-with-top-border">
				    <form action="#" id="form-debitnote" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <table id="data-pesertaspk" class="table table-bordered table-hover" width="100%">
                        <thead>
							<tr class="warning">
								<th>No</th>
								<th>Produk</th>
								<th>Status</th>
								<th>Partner</th>
								<th>SPK</th>
								<th>Nama</th>
								<th>KTP</th>
								<th>Tgl Lahir</th>
								<th>Usia</th>
								<th>Alamat</th>
								<th>Awal Asuransi</th>
								<th>Tenor (bln)</th>
								<th>Akhir Asuransi</th>
								<th>Plafond</th>
								<th>Premi</th>
								<th>EM(%)</th>
								<th>Premi EM</th>
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

							$queryspk = mysql_query('SELECT
							ajkcobroker.`name` AS broker,
							ajkclient.`name` AS perusahaan,
							ajkpolis.produk AS produk,
							ajkspk.id,
							ajkspk.idbroker,
							ajkspk.idpartner,
							ajkspk.idproduk,
							ajkspk.nomorspk,
							ajkspk.statusspk,
							ajkspk.statusnote,
							ajkspk.nomorktp,
							ajkspk.nama,
							IF(ajkspk.jeniskelamin="M", "Laki-laki","Perempuan") AS jnskelamin,
							ajkspk.dob,
							ajkspk.usia,
							ajkspk.alamat,
							ajkspk.pekerjaan,
							ajkspk.plafond,
							ajkspk.tglakad,
							ajkspk.tenor,
							ajkspk.tglakhir,
							ajkspk.mppbln,
							ajkspk.em,
							ajkspk.premi,
							ajkspk.premiem,
							IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
							ajkspk.photodebitur1,
							ajkspk.photodebitur2,
							ajkspk.photoktp,
							ajkspk.photosk,
							ajkspk.ttddebitur,
							ajkspk.ttdmarketing,
							ajkcabang.`name` AS cabang,
							userinput.firstname AS userinput,
							DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
							userapprove.firstname AS userapprove,
							DATE_FORMAT(ajkspk.approve_date, "%Y-%m-%d") AS tglapprove
							FROM ajkspk
							INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
							INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
							INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
							INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
							INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
							LEFT JOIN useraccess AS userapprove ON ajkspk.approve_by = userapprove.id
							WHERE ajkspk.idbroker = "'.$idbro.'" AND
								  ajkspk.idpartner = "'.$idclient.'" AND
								  ajkspk.cabang = "'.$cabang.'" AND
								  ajkspk.del IS NULL
							ORDER BY ajkspk.approve_date DESC');
              $li_row = 1;
              while ($rowspk = mysql_fetch_array($queryspk)) {
                  $input_date_format = date('d-m-Y', strtotime($input_date));
                  $approve_date = $rowspk['approve_date'];
                  $approve_date_format = date('d-m-Y', strtotime($approve_date));
                  if ($rowspk['statusspk']!="Request" and $rowspk['statusspk']!="Pending" and $rowspk['statusspk']!="Batal") {
                      $linknama = '<a href="../modules/modPdfdl_front.php?pdf=_spk&ids='.AES::encrypt128CBC($rowspk['nomorspk'], ENCRYPTION_KEY).'&idp='.AES::encrypt128CBC($rowspk['idproduk'], ENCRYPTION_KEY).'&idc='.AES::encrypt128CBC($rowspk['idpartner'], ENCRYPTION_KEY).'&idb='.AES::encrypt128CBC($rowspk['idbroker'], ENCRYPTION_KEY).'" target="_blank">'.$rowspk['nama'].'</a>';
                  } else {
                      $linknama = $rowspk['nama'];
                  }

                  if ($rowspk['statusspk']=="Aktif") {
                      $statusspk = '<span class="label label-success">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Approve") {
                      $statusspk = '<span class="label label-info">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="PreApproval") {
                      $statusspk = '<span class="label label-warning">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Proses") {
                      $statusspk = '<span class="label label-primary">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Request") {
                      $statusspk = '<span class="label label-lime">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Pending") {
                      $statusspk = '<span class="label label-grey">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Batal") {
                      $statusspk = '<span class="label label-danger">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Tolak") {
                      $statusspk = '<span class="label label-inverse">'.$rowspk['statusspk'].'</span>';
                  } elseif ($rowspk['statusspk']=="Realisasi") {
                      $statusspk = '<span class="label label-success">'.$rowspk['statusspk'].'</span>';
                  }


                  if ($rowspk['premiem']==null) {
                      $metPremiem = '';
                  } else {
                      $metPremiem = duit($rowspk['premiem']);
                  }
                  if ($rowspk['mppbln']==null) {
                      $metMPPbln = '';
                  } else {
                      $metMPPbln = $rowspk['mppbln'].' bulan';
                  }
                  echo '<tr class="odd gradeX">
		        <td>'.$li_row.'</td>
				<td>'.$rowspk['produk'].'</td>
				<td>'.$statusspk.'</td>
				<td>'.$rowspk['perusahaan'].'</td>
				<td>'.$rowspk['nomorspk'].'</td>
				<td>'.$rowspk['nama'].'</td>
				<td>'.$rowspk['nomorktp'].'</td>
				<td>'._convertDate($rowspk['dob']).'</td>
				<td>'.$rowspk['usia'].'</td>
				<td>'.$rowspk['alamat'].'</td>
				<td>'._convertDate($rowspk['tglakad']).'</td>
				<td>'.$rowspk['tenor'].'</td>
				<td>'._convertDate($rowspk['tglakhir']).'</td>
				<td>'.duit($rowspk['plafond']).'</td>
				<td><span class="label label-success">'.duit($rowspk['premi']).'</span></td>
				<td>'.$rowspk['em'].'</td>
				<td>'.$metPremiem.'</td>
				<td><span class="label label-success">'.duit($rowspk['totalpremiSPK']).'</span></td>
				<td>'.$metMPPbln.'</td>
				<td>'.$rowspk['cabang'].'</td>
				<td>'.$rowspk['userinput'].'</td>
				<td>'._convertDate($rowspk['tglinput']).'</td>
				<td>'._convertDate($rowspk['tglapprove']).'</td>
            </tr>';
                  $li_row++;
              } ?>

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
        if ($typedata == 'peserta') {
          ?>
					$(".active").removeClass("active");
					document.getElementById("has_master").classList.add("active");
					document.getElementById("idsub_master").classList.add("active");
					document.getElementById("idsub_peserta").classList.add("active");

					$("#data-peserta").DataTable({
						"scrollX":true,
						"bProcessing": true,
						"bServerSide": true,
						"order": [[ 8, "desc" ]],
						"ajax":{
							url :"data.php?action=datapeserta", // json datasource
							type: "post",  // method  , by default get
							error: function(aa){  // error handling
								$(".data-peserta-error").html("");
								$("#data-peserta").append('<tbody class="data-peserta-error"><tr><th colspan="16">Data tidak tersedia</th></tr></tbody>');
							}
						}
					})

					<?php
        } elseif ($typedata == 'debitnote') {
          ?>
					$(".active").removeClass("active");
					document.getElementById("has_master").classList.add("active");
					document.getElementById("idsub_master").classList.add("active");
					document.getElementById("idsub_debitnote").classList.add("active");

					$("#data-debitnote").DataTable({
						responsive: true,
						"bProcessing": true,
						"bServerSide": true,
						"order": [[ 3, "desc" ]],
						"ajax":{
							url :"data.php?action=datadebitnote", // json datasource
							type: "post",  // method  , by default get
							error: function(aa){  // error handling
								$(".data-debitnote-error").html("");
								$("#data-debitnote").append('<tbody class="data-debitnote-error"><tr><th colspan="16">Data tidak tersedia</th></tr></tbody>');
							}
						}
					})
					<?php
        } elseif ($typedata == 'pesertaSPK') {
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

function toggle(source) {
	var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(:disabled)');
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i] != source)
		checkboxes[i].checked = source.checked;
	}
}
	</script>
</body>

</html>
