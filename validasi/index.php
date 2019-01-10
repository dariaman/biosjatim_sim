<?php
	include "../param.php";
	if(isset($_REQUEST['type'])){
		$typedata = $_REQUEST['type'];
	}else{
		$typedata = '';
	}

	$typedata = AES::decrypt128CBC($typedata,ENCRYPTION_KEY);
?>

<!DOCTYPE html>
<html lang="en">
<?php
	_head($user,$namauser,$photo,$logo);
?>
<script>
	function toggle(source) {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(:disabled)');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i] != source)
			checkboxes[i].checked = source.checked;
		}
	}
</script>

<style type="text/css">
	#icheckForm .radio label, #icheckForm .checkbox label {
  	padding-left: 0;
	}
</style>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
			_header($user,$namauser,$photo,$logo,$logoklient);
			_sidebar($user,$namauser,'','');$typedata
		?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
			<?php
				if($typedata=="dataspk"){
			?>
			<!-- begin section-container -->
			<form action="dovalidasispk.php" id="form-validasispk" class="form-horizontal" method="post" enctype="multipart/form-data">
				<h4 class="m-t-0">Validasi Data SPK</h4>
				<div class="section-container section-with-top-border">
          <table id="data-table" data-order='[[1,"asc"]]' class="table table-bordered table-hover" width="100%">
            <thead>
							<tr class="success">
								<th>No</th>
								<th>
									<center>
										<div class="checkbox">
										  <input onClick="toggle(this)" class="styled" type="checkbox" >
										  <label for="checkbox"></label>
										</div>
									</center>
								</th>
								<th>Produk</th>
								<th>Nomor SPK</th>
								<th>Nama</th>
								<th>Tanggal Lahir</th>
								<th>Tenor (Bulan)</th>
								<th>Plafond</th>								
								<th>Asuransi</th>
								<th>Status</th>
								<th>Cabang</th>
								<th>User Input</th>
								<th>Tanggal Input</th>
								<th>Option</th>
							</tr>
						</thead>
   					<tbody>
				<?php
					$queryspk = mysql_query('SELECT ajkcobroker.`name` AS broker,
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
																					ajkspk.premi,
																					ajkspk.nettpremi,
																					ajkspk.photodebitur1,
																					ajkspk.photodebitur2,
																					ajkspk.photoktp,
																					ajkspk.photosk,
																					ajkspk.ttddebitur,
																					ajkspk.ttdmarketing,
																					ajkcabang.`name` AS cabang,
																					useraccess.firstname AS userinput,
																					DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
																					ajkinsurance.name as nm_asuransi
																		FROM ajkspk
																		INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
																		INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
																		INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
																		INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
																		INNER JOIN useraccess ON ajkspk.input_by = useraccess.id
																		LEFT JOIN ajkinsurance ON ajkspk.asuransi = ajkinsurance.id
																		WHERE ajkspk.idbroker = "'.$idbro.'" AND
																				  ajkspk.idpartner = "'.$idclient.'" AND
																				  ajkspk.cabang = "'.$cabang.'" AND
																				  ajkspk.statusspk = "Request" AND
																				  ajkpolis.typemedical = "SPK" AND
																				  ajkspk.del IS NULL
																		ORDER BY ajkspk.input_date DESC');

									$li_row = 1;

									while($rowspk = mysql_fetch_array($queryspk)){
				            echo '<tr class="odd gradeX">
					                  <td>'.$li_row.'</td>
														<td>
															<center>
																<div class="checkbox">
									                <input name="approve[]" id="approve_'.$rowspk['id'].'" class="styled" type="checkbox" value="'.$rowspk['id'].'">
									                <label for="checkbox"></label>
													      </div>
															</center>
														</td>
														<td>'.$rowspk['produk'].'</td>
														<td>'.$rowspk['nomorspk'].'</td>
														<td><a title="View Detail"  href="../validasi?type='.AES::encrypt128CBC('viewdebitur',ENCRYPTION_KEY).'&nospk='.AES::encrypt128CBC($rowspk['nomorspk'],ENCRYPTION_KEY).'" target="_blank">'.$rowspk['nama'].'</a></td>
														<td>'._convertDate($rowspk['dob']).'</td>
														<td class="text-center">'.$rowspk['tenor'].'</td>
														<td class="text-right">'.duit($rowspk['plafond']).'</td>
														<td>'.$rowspk['nm_asuransi'].'</td>
														<td><span class="label label-warning">'.$rowspk['statusspk'].'</span></td>
														<td>'.$rowspk['cabang'].'</td>
														<td>'.$rowspk['userinput'].'</td>
														<td>'._convertDate($rowspk['tglinput']).'</td>
														<td>
															<a title="Edit"  href="../validasi?type='.AES::encrypt128CBC('editdebitur',ENCRYPTION_KEY).'&nospk='.AES::encrypt128CBC($rowspk['nomorspk'],ENCRYPTION_KEY).'">
					                    	<span class="fa-stack fa-2x text-warning">
																	<i class="fa fa-circle fa-stack-2x"></i>
																	<i class="fa fa fa-pencil fa-stack-1x fa-inverse"></i>
																</span>
															</a>
															<a title="Batal" data-id="'.$rowspk['nomorspk'].'" data-target="#modal-alert" data-toggle="modal">
																<span class="fa-stack fa-2x text-danger">
																	<i class="fa fa-circle fa-stack-2x"></i>
																	<i class="fa fa fa-remove fa-stack-1x fa-inverse"></i>
																</span>
															</a>
														</td>
				                 	</tr>';

										$li_row++;
				          }
									if ($level==7 OR $level==8) {
										$approveSPK = '<input name="sub" class="btn btn-success width-xs" value="Approved" type="submit">';
									}else{
										$approveSPK = '';
									}
				?>
						</tbody>
          </table>
					<div class="form-group m-b-0">
          	<label class="control-label col-sm-12"></label>
            <div class="col-sm-6"><?php echo $approveSPK;	?></div>
         	</div>
        </div>
	    </form>
			<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog" aria-hidden="true">
	      <div class="modal-dialog">
          <div class="modal-content">
           	<div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
	            <h4 class="modal-title">Delete Data</h4>
            </div>
            <form action="dodeletespk.php" id="form-batal" class="form-batal" method="post">
              <div class="modal-body">
	              <div class="alert alert-danger m-b-0">
	             		 <p><b>Konfirmasi:</b> Apakah anda yakin untuk membatalkan data ini?</p>
	              </div>
								<label class="control-label">Alasan Pembatalan <span class="text-danger">*</span></label>
								<div class="form-group">
		              <textarea class="form-control alasan" id="txtalasan" name="txtalasan"></textarea>
		            </div>
	            	<input type="hidden" name="nospk" id="nospk"/>
	            </div>
              <div class="modal-footer">
	              <a href="javascript:;" class="btn width-100 btn-default" data-dismiss="modal">Close</a>
	              <input type="submit" id="btnbatal" name="btnbatal"  class="btn width-100 btn-danger" value="Batal"/>
              </div>
	          </form>
        	</div>
	    	</div>
	    </div>
	            <!-- end section-container -->
	    <?php
				}elseif($typedata=="dataskkt"){
			?>

			<!-- begin section-container -->
			<form action="dovalidasispk.php" id="form-validasispk" class="form-horizontal" method="post" enctype="multipart/form-data">
				<h4 class="m-t-0">Validasi Data SKKT</h4>
				<div class="section-container section-with-top-border">
	        <table id="data-table" data-order='[[1,"asc"]]' class="table table-bordered table-hover" width="100%">
	          <thead>
							<tr class="success">
								<th>No</th>
								<th>
									<center>
										<div class="checkbox">
					            <input onClick="toggle(this)" class="styled" type="checkbox" >
					            <label for="checkbox"></label>
							      </div>
									</center></th>
								<th>Produk</th>
								<th>Nomor SPK</th>
								<th>Nama</th>
								<th>Tanggal Lahir</th>
								<th>Tenor (Bulan)</th>
								<th>Plafond</th>
								<th>Asuransi</th>
								<th>Status</th>
								<th>Cabang</th>
								<th>User Input</th>
								<th>Tanggal Input</th>
								<th>Option</th>
							</tr>
						</thead>
   					<tbody>

        <?php
					$queryspk = mysql_query('SELECT ajkcobroker.`name` AS broker,
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
																					ajkspk.premi,
																					ajkspk.nettpremi,
																					ajkspk.photodebitur1,
																					ajkspk.photodebitur2,
																					ajkspk.photoktp,
																					ajkspk.photosk,
																					ajkspk.ttddebitur,
																					ajkspk.ttdmarketing,
																					ajkcabang.`name` AS cabang,
																					useraccess.firstname AS userinput,
																					DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
																					ajkinsurance.name as nm_asuransi
																	FROM ajkspk
																	INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
																	INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
																	INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
																	INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
																	INNER JOIN useraccess ON ajkspk.input_by = useraccess.id
																	LEFT JOIN ajkinsurance ON ajkspk.asuransi = ajkinsurance.id
																	WHERE ajkspk.idbroker = "'.$idbro.'" AND
																			  ajkspk.idpartner = "'.$idclient.'" AND
																			  ajkspk.cabang = "'.$cabang.'" AND
																			  ajkspk.statusspk = "Request" AND
																			  ajkpolis.typemedical = "SKKT" AND
																			  ajkspk.del IS NULL');

					$li_row = 1;

					while($rowspk = mysql_fetch_array($queryspk)){
						echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td>
											<center>
												<div class="checkbox">
					                <input name="approve[]" id="approve_'.$rowspk['id'].'" class="styled" type="checkbox" value="'.$rowspk['id'].'">
					                <label for="checkbox"></label>
						            </div>
											</center>
										</td>
										<td>'.$rowspk['produk'].'</td>
										<td>'.$rowspk['nomorspk'].'</td>
										<td><a title="View Detail"  href="../validasi?type='.AES::encrypt128CBC('viewdebitur',ENCRYPTION_KEY).'&nospk='.AES::encrypt128CBC($rowspk['nomorspk'],ENCRYPTION_KEY).'" target="_blank">'.$rowspk['nama'].'</a></td>
										<td>'._convertDate($rowspk['dob']).'</td>
										<td class="text-center">'.$rowspk['tenor'].'</td>
										<td class="text-right">'.duit($rowspk['plafond']).'</td>
										<td>'.$rowspk['nm_asuransi'].'</td>
										<td><span class="label label-warning">'.$rowspk['statusspk'].'</span></td>
										<td>'.$rowspk['cabang'].'</td>
										<td>'.$rowspk['userinput'].'</td>
										<td>'._convertDate($rowspk['tglinput']).'</td>
										<td>
											<a title="Edit"  href="../validasi?type='.AES::encrypt128CBC('editdebitur',ENCRYPTION_KEY).'&nospk='.AES::encrypt128CBC($rowspk['nomorspk'],ENCRYPTION_KEY).'">
												<span class="fa-stack fa-2x text-warning">
													<i class="fa fa-circle fa-stack-2x"></i>
													<i class="fa fa fa-pencil fa-stack-1x fa-inverse"></i>
												</span>
											</a>
											<a title="Batal" data-id="'.$rowspk['nomorspk'].'" data-target="#modal-alert" data-toggle="modal">
												<span class="fa-stack fa-2x text-danger">
													<i class="fa fa-circle fa-stack-2x"></i>
													<i class="fa fa fa-remove fa-stack-1x fa-inverse"></i>
												</span>
											</a>
										</td>
	                </tr>';

						$li_row++;
					}
					if ($level==7 OR $level==8) {
						$_approve = '<input name="sub" class="btn btn-success width-xs" value="Approved" type="submit">';
					}else{
						$_approve = '';
					}
				?>

						</tbody>
          </table>

					<div class="form-group m-b-0">
           	<label class="control-label col-sm-12"></label>
           	<div class="col-sm-6"><?php echo $_approve;	?></div>
				  </div>
				</div>
	    </form>
			<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
	         	<div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
	            <h4 class="modal-title">Delete Data</h4>
	          </div>
            <form action="dodeletespk.php" id="form-batal" class="form-batal" method="post">
              <div class="modal-body">
	              <div class="alert alert-danger m-b-0">
	             		 <p><b>Konfirmasi:</b> Apakah anda yakin untuk membatalkan data ini?</p>
	              </div>
								<label class="control-label">Alasan Pembatalan <span class="text-danger">*</span></label>
								<div class="form-group">
		              <textarea class="form-control alasan" id="txtalasan" name="txtalasan"></textarea>
		            </div>
		            <input type="hidden" name="nospk" id="nospk"/>
              </div>
              <div class="modal-footer">
                  <a href="javascript:;" class="btn width-100 btn-default" data-dismiss="modal">Close</a>
                  <input type="submit" id="btnbatal" name="btnbatal"  class="btn width-100 btn-danger" value="Batal"/>
              </div>
            </form>
        	</div>
	    	</div>
	    </div>
	    <!-- end section-container -->

	    <?php
				}elseif($typedata=="viewdebitur"){
					$nospk = $_REQUEST['nospk'];
					$nospk = AES::decrypt128CBC($nospk,ENCRYPTION_KEY);
					$queryspk = mysql_query("SELECT * FROM ajkspk WHERE nomorspk = '".$nospk."'");
					$rowspk = mysql_fetch_array($queryspk);
					$nomorspk = $rowspk['nomorspk'];
					$nomorktp = $rowspk['nomorktp'];
					$token = $rowspk['token'];
					$nama = $rowspk['nama'];
					$jeniskelamin = $rowspk['jeniskelamin'];
					if($jeniskelamin=="L"){
						$gender = "Laki-Laki";
					}else{
						$gender = "Perempuan";
					}
					$dob = $rowspk['dob'];
					$dob_format = date("d-m-Y", strtotime($dob));
					$alamat = $rowspk['alamat'];
					$pekerjaan = $rowspk['pekerjaan'];
					$plafond = $rowspk['plafond'];
					$plafond_format = number_format($plafond,0,".",",");
					$tenor = $rowspk['tenor'];
					$input_date = $rowspk['input_date'];
					$qasuransi = mysql_fetch_array(mysql_query("SELECT * FROM ajkinsurance WHERE id = '".$rowspk['asuransi']."'"));
					$asuransi = $qasuransi['name'];
					$foldername = date("y",strtotime($input_date)).date("m",strtotime($input_date));
					$photodebitur1 = $rowspk['photodebitur1'];
					$photoktp = $rowspk['photoktp'];
					$photosk = $rowspk['photosk'];
					$ttdmarketing = $rowspk['ttdmarketing'];
					$ttddebitur = $rowspk['ttddebitur'];
			?>

			<!-- begin section-container -->
			<div class="section-container section-with-top-border">
		    <h4 class="text-center">DATA DEBITUR</h4>
		    <form action="doeditinventaris.php?type=<?php echo $kode ?>&idinv=<?php echo $idkom ?>" id="form-inv" class="form-horizontal" method="post" enctype="multipart/form-data">
          <div class="form-group">
              <label class="control-label col-md-6">Nomor SPK :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $nomorspk ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Nomor Identitas :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $nomorktp ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Nomor Token :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $token ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Nama Debitur :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $nama ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Jenis Kelamin :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $gender ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Tanggal Lahir :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $dob_format ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Alamat :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $alamat ?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Pekerjaan :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo $pekerjaan ?> </label>
              </div>
          </div>
					<div class="form-group">
              <label class="control-label col-md-6">Asuransi :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo  $asuransi?> </label>
              </div>
          </div>          
          <div class="form-group">
              <label class="control-label col-md-6">Jumlah Penjaminan :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo  $plafond_format?> </label>
              </div>
          </div>
          <div class="form-group">
              <label class="control-label col-md-6">Jangka Waktu Penjaminan :</label>
              <div class="col-md-6">
                <label class="control-label"><?php echo  $tenor?> (Bulan)</label>
              </div>
          </div>
          <div class="form-group">
            <div id="gallery" class="gallery">
              <div class="image gallery-group-1">
                  <div class="image-inner">
                  	<div class="col-md-3">
                        <a href="../myFiles/_ajk/<?php echo $photodebitur1 ?>" data-lightbox="gallery-group-1">
                            <img src="../myFiles/_ajk/<?php echo $photodebitur1 ?>" alt="" class="img-circle" width="200" height="200"/>
                        </a>
                        <p class="image-caption">
                            Foto KTP
                        </p>
                  	</div>
                  </div>
              </div>
              <div class="image gallery-group-2">
                  <div class="image-inner">
                  	<div class="col-md-3">
                        <a href="../myFiles/_ajk/<?php echo $photoktp ?>" data-lightbox="gallery-group-1">
                            <img src="../myFiles/_ajk/<?php echo $photoktp ?>" alt="" class="img-circle" width="200" height="200"/>
                        </a>
                        <p class="image-caption">
                            Foto KTP
                        </p>
                  	</div>
                  </div>
              </div>
              <div class="image gallery-group-3">
                  <div class="image-inner">
                  	<div class="col-md-3">
                        <a href="../myFiles/_ajk/<?php echo $ttddebitur ?>" data-lightbox="gallery-group-1">
                            <img src="../myFiles/_ajk/<?php echo $ttddebitur ?>" alt="" class="img-circle" width="200" height="200"/>
                        </a>
                        <p class="image-caption">
                            TTD Debitur
                        </p>
                  	</div>
                  </div>
              </div>
              <div class="image gallery-group-4">
                  <div class="image-inner">
                  	<div class="col-md-3">
                        <a href="../myFiles/_ajk/<?php echo $ttdmarketing ?>" data-lightbox="gallery-group-1">
                            <img src="../myFiles/_ajk/<?php echo $ttdmarketing ?>" alt="" class="img-circle" width="200" height="200"/>
                        </a>
                        <p class="image-caption">
                            TTD Marketing
                        </p>
                  	</div>
                  </div>
              </div>
              <div class="image gallery-group-5">
                  <div class="image-inner">
                  	<div class="col-md-3">
                        <a href="../myFiles/_ajk/<?php echo $photosk ?>" data-lightbox="gallery-group-1">
                            <img src="../myFiles/_ajk/<?php echo $photosk ?>" alt="" class="img-circle" width="200" height="200"/>
                        </a>
                        <p class="image-caption">
                            Foto SK
                        </p>
                  	</div>
                  </div>
              </div>
    				</div>
          </div>
        </form>
      </div>
      <!-- end section-container -->

			<?php
				}elseif($typedata=="editdebitur"){
					$nospk = $_REQUEST['nospk'];
					$nospk = AES::decrypt128CBC($nospk,ENCRYPTION_KEY);
					$queryspk = mysql_query("SELECT * FROM ajkspk WHERE nomorspk = '".$nospk."'");
					$rowspk = mysql_fetch_array($queryspk);
					$nomorspk = $rowspk['nomorspk'];
					$nomorktp = $rowspk['nomorktp'];
					$token = $rowspk['token'];
					$nama = $rowspk['nama'];
					$jeniskelamin = $rowspk['jeniskelamin'];
					if($jeniskelamin=="L"){
						$gender = "Laki-Laki";
					}else{
						$gender = "Perempuan";
					}
					$dob = $rowspk['dob'];
					$dob_format = date("d/m/Y", strtotime($dob));
					$alamat = $rowspk['alamat'];
					$pekerjaan = $rowspk['pekerjaan'];
					$plafond = $rowspk['plafond'];
					$plafond_format = number_format($plafond,0,".",",");
					$tenor = $rowspk['tenor'];
					$input_date = $rowspk['input_date'];

					$foldername = date("y",strtotime($input_date)).date("m",strtotime($input_date));
					$photodebitur1 = $rowspk['photodebitur1'];
					$photoktp = $rowspk['photoktp'];
					$photosk = $rowspk['photosk'];
					$ttdmarketing = $rowspk['ttdmarketing'];
					$ttddebitur = $rowspk['ttddebitur'];
			?>

			<h4>Modul Edit Data SPK</h4>
			<div class="section-container section-with-top-border">
		    <form action="doeditspk.php" id="form-editpeserta" class="form-horizontal" method="post">
          <div class="form-group">
            <label class="control-label col-md-3">Nomor SPK :</label>
            <div class="col-md-6">
              <label class="control-label"><?php echo $nomorspk ?> </label>
              <input type="hidden" class="form-control" name="nomorspk" id="nomorspk" value="<?php echo $nomorspk ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Nama <span class="text-danger">*</span> :</label>
            <div class="col-md-6">
              <input type="text" class="form-control" name="namadebitur" id="namadebitur" value="<?php echo $nama ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Nomor KTP <span class="text-danger">*</span> :</label>
            <div class="col-md-4">
              <input type="text" class="form-control" name="nomorktp" id="nomorktp" value="<?php echo $nomorktp ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Jenis Kelamin <span class="text-danger">*</span> :</label>
            <div class="col-md-6">
              <div class="radio">
  							<input type="radio" name="jeniskelamin[]" value="M" id="gandermale" class="styled" <?php if($jeniskelamin=="L") echo "checked" ?>/>
  							<label for="gandermaleRadio">Laki-Laki</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  							<input type="radio" name="jeniskelamin[]" value="F" id="ganderfemale" class="styled" <?php if($jeniskelamin=="P") echo "checked" ?>/>
  							<label for="ganderfemaleRadio">Perempuan</label>
							</div>
						</div>
          </div>
					<div class="form-group">
            <label class="control-label col-sm-3">Tanggal Lahir <span class="text-danger">*</span> :</label>
            <div class="col-sm-3">
							<div class="input-group date">
                <input type="text" name="tgllahir" id="tgllahir" class="form-control" placeholder="Tanggal Lahir" value="<?php echo $dob_format ?>"/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Alamat <span class="text-danger">*</span> :</label>
            <div class="col-md-6">
              <textarea class="form-control" name="alamat" id="alamat" cols="3"><?php echo $alamat ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Pekerjaan <span class="text-danger">*</span> :</label>
            <div class="col-md-3">
            	<input type="text" name="pekerjaan" id="pekerjaan" class="form-control" placeholder="Pekerjaan" value="<?php echo $pekerjaan ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Jumlah Pinjaman <span class="text-danger">*</span> :</label>
            <div class="col-md-2">
              <input type="text" name="jumlahpinjaman" id="jumlahpinjaman" class="form-control text-right" placeholder="Jumlah Pinjaman" value="<?php echo $plafond_format ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Jangka Waktu Pinjaman <span class="text-danger">*</span> :</label>
            <div class="col-md-1">
              <input type="text" name="jangkawaktupinjaman" id="jangkawaktupinjaman" class="form-control" placeholder="Jangka Waktu Pinjaman" value="<?php echo $tenor ?>"/>
            </div>
          </div>
          <div class="form-group">
	          <label class="control-label col-md-3"></label>
	          <div class="col-md-3">
	            <input type="submit" name="btnsimpan" id="btnsimpan" class="btn btn-primary" value="Simpan"/>
	            <a href="../validasi?type=<?php echo AES::encrypt128CBC('dataspk',ENCRYPTION_KEY) ?>" name="btnsimpan" id="btnsimpan" class="btn btn-danger">Batal</a>
	          </div>
          </div>
        </form>
      </div>
      <!-- end section-container -->

			<?php
				}else{
			?>

			<h4 class="m-t-0">Validasi Data Deklarasi</h4>
			<div class="section-container section-with-top-border">
				<?php
					$li_row = 1;
					if($level=="8"){
						$status = 'Pending';
					}elseif($level=="7"){
						$status = 'Upload';
					}else{
						$status = '';
					}
				?>
	      <table id="data-tableverifikasi" class="table table-bordered table-hover" width="100%">
	        <thead>
						<tr class="success">
							<th>No</th>
							<th>Cabang</th>
							<!-- <th>Nama Files</th> -->
							<th>Asuransi</th>
							<th>Nama Produk</th>
							<th>Jumlah Peserta</th>
							<th>Total Plafond</th>
							<th>Total Premi</th>
							<th>Status</th>
							<th>User Input</th>
							<th>Tanggal Input</th>
							<th>Option</th>
						</tr>
					</thead>
	        <tbody>
	        <?php
						$cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idclient.'" AND er="'.$cabang.'"'));
						
						if ($cekCabang['name'] == "PUSAT") {
							$cabangverifikasi = '';
						}else{
							$cabangverifikasi = " AND cabang = '".$cabang."'";
						}

						$querygeneraltemp = mysql_query("	SELECT *, 
																										sum(totalpremi) as totprem, 
																										sum(plafond) as totplafon, 
																										count(nama) AS totalpeserta
																						 	FROM ajkpeserta_temp
																						 	WHERE idbroker = '".$idbro."' AND 
																						 				idclient = '".$idclient."' ".$cabangverifikasi." AND 
																						 				del IS NULL AND 
																						 				tiperefund IS NULL
																						 GROUP BY idpolicy, input_time, cabang ORDER BY input_time DESC, cabang ASC");
						while($rowgeneraltemp = mysql_fetch_array($querygeneraltemp)){
							$idpolis = $rowgeneraltemp['idpolicy'];
							$tglinput = $rowgeneraltemp['input_time'];
							$filename = $rowgeneraltemp['filename'];
							$totalpremi = $rowgeneraltemp['totprem'];
							$nomorspk = $rowgeneraltemp['nomorspk'];
							$totalpremi_format = number_format($totalpremi,0,".",",");
							$totalplafon = $rowgeneraltemp['totplafon'];
							$totalpflafon_format = number_format($totalplafon,0,".",",");
							$sendding = AES::encrypt128CBC($rowgeneraltemp['input_time'], ENCRYPTION_KEY);
							$_cabang = AES::encrypt128CBC($rowgeneraltemp['cabang'], ENCRYPTION_KEY);

							//CEK TIPE PRODUK GENERAL
							$qpolis__ = mysql_fetch_array(mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND id = '".$idpolis."'"));
							
							if ($qpolis__['general']=="Y") {
								if($level=="8"){
									$status = 'Pending';
								}elseif($level=="7"){
									$status = 'Upload';
								}else{
									$status = 'Upload';
								}
								$querytemp = mysql_query("SELECT * 
																					FROM ajkpeserta_temp 
																					WHERE statusaktif = '".$status."' AND 
																								idbroker = '".$idbro."' AND 
																								idclient = '".$idclient."' AND 
																								filename ='".$filename."' AND 
																								input_time = '".$tglinput."' AND 
																								cabang = '".$cabang."' AND 
																								del IS NULL
																					GROUP BY input_time ");

								if($level=="8" OR $level=="7"){
									$link = "detail.php?inpt=$sendding&cab=$_cabang";
								}else{
									$link = "upload.php?inpt=$sendding&cab=$_cabang";
								}

								$icon = 'fa-eye';
								$tittle = 'View';
							}else{
								$querytemp = mysql_query("SELECT * 
																					FROM ajkpeserta_temp
														  						WHERE statusaktif = '".$status."' AND 
														  									idbroker = '".$idbro."' AND 
														  									idclient = '".$idclient."' AND 
														  									input_time = '".$tglinput."' AND 
														  									cabang = '".$cabang."' AND 
														  									del IS NULL
																					GROUP BY input_time ");

								$link = "detail.php?inpt=$sendding&cab=$_cabang";
								$icon = 'fa-eye';
								$tittle = 'View';
							}

	            $totalpremi = 0;
	            $totalpflafon = 0;					    	
				    	$tglinput = $rowquerytemp['input_time'];
	          	$totpeserta = $rowgeneraltemp['totalpeserta'];
	          	$namafile = $rowgeneraltemp['filename'];
	          	$idpolicy = $rowgeneraltemp['idpolicy'];
			    		$querypolicy = mysql_query("SELECT * FROM ajkpolis WHERE id = '".$idpolicy."' AND idcost = '".$idclient."'");
			    		$rowpolicy = mysql_fetch_array($querypolicy);
			    		$namaprod = $rowpolicy['produk'];
							$iduserinput = $rowgeneraltemp['input_by'];
			    		$queryuserinput = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$iduserinput."'");
			    		$rowuserinput = mysql_fetch_array($queryuserinput);
			    		$userinput = $rowuserinput['firstname'];
	          	$tglinput = $rowgeneraltemp['input_time'];
	          	$statusaktif = $rowgeneraltemp['statusaktif'];
	          	$qasuransi = mysql_fetch_array(mysql_query("SELECT ajkinsurance.name as nm_asuransi 
																          								FROM ajkspk 
																          										 LEFT JOIN ajkinsurance 
																          										 on ajkinsurance.id = ajkspk.asuransi 
																          								WHERE ajkspk.nomorspk = '".$nomorspk."'"));
	          	$nm_asuransi = $qasuransi['nm_asuransi'];


			    		if($statusaktif=="Upload"){
			    			$classlabel = 'label-warning';
			    		}elseif($statusaktif=="Pending"){
			    			$classlabel = 'label-success';
			    		}

	          	$tglinput = date('d-m-Y', strtotime($tglinput));
	          	$idcabang = $rowgeneraltemp['cabang'];
	          	$qcab = mysql_query("SELECT * FROM ajkcabang WHERE er = '$idcabang' AND idclient = '$idclient'");
	          	$rcab = mysql_fetch_array($qcab);
	          	$nmcab  = $rcab['name'];
			    		$sendding = AES::encrypt128CBC($rowquerytemp['input_time'], ENCRYPTION_KEY);
			    		$_cabang = AES::encrypt128CBC($rowgeneraltemp['cabang'], ENCRYPTION_KEY);

							echo '<tr class="odd gradeX">
				              <td>'.$li_row.'</td>
											<td>'.$nmcab.'</td>
											<td>'.$nm_asuransi.'</td>
											<td>'.$namaprod.'</td>
											<td>'.$totpeserta.'</td>
											<td class="text-right">'.$totalpflafon_format.'</td>
											<td class="text-right">'.$totalpremi_format.'</td>
											<td><span class="label '.$classlabel.'">'.$statusaktif.'</span></td>
											<td>'.$userinput.'</td>
											<td>'.$tglinput.'</td>
											<td>
												<a title="'.$tittle.'"  href="'.$link.'">
													<span class="fa-stack fa-2x text-warning">
														<i class="fa fa-circle fa-stack-2x"></i>
														<i class="fa fa '.$icon.' fa-stack-1x fa-inverse"></i>
													</span>
												</a>
											</td>
		                </tr>';
		          $li_row++;
		                        //}
						}
	        ?>
	        </tbody>
	      </table>
			</div>
			<!-- end section-container -->
			</div>
	  		<?php
				}
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
			if($typedata=="editdebitur"){
			?>
			$(".active").removeClass("active");
			//$(".open").removeClass("open");
			document.getElementById("has_verification").classList.add("active");
			document.getElementById("idsub_verification").classList.add("active");
			document.getElementById("idsub_datavalidasi_spk").classList.add("active");
			
			PageDemo.init();

			$('#form-editpeserta').bootstrapValidator({
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
					namadebitur: {
						validators: {
							notEmpty: {
								message: 'Nama harus diisi'
							}
						}
					},
					nomorktp: {
						validators: {
							notEmpty: {
								message: 'Nomor KTP harus diisi'
							}
						}
					},
					'jeniskelamin[]': {
						validators: {
							choice: {
								min: 1,
								message: 'Silahkan pilih jenis kelamin'
							}
						}
					},
					tgllahir: {
						validators: {
							notEmpty: {
								message: 'Nomor KTP harus diisi'
							},
							date: {
								format: 'DD/MM/YYYY',
								message: 'Format tanggal lahir dd/mm/yyyy'

							}
						}
					},

					alamat: {
						validators: {
							notEmpty: {
								message: 'Alamat harus diisi'
							}
						}
					},
					pekerjaan: {
						validators: {
							notEmpty: {
								message: 'Pekerjaan harus diisi'
							}
						}
					},
					jumlahpinjaman: {
						validators: {
							notEmpty: {
								message: 'Jumlah pinjaman harus diisi'
							}
						}
					},
					jangkawaktupinjaman: {
						validators: {
							notEmpty: {
								message: 'Jangka Waktu pinjaman harus diisi'
							}
						}
					}

				}
			});

			$("#tgllahir").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy'
			}).on('changeDate', function(e) {
				$('#form-editpeserta').bootstrapValidator('revalidateField', 'tgllahir');
			});

			$('#jumlahpinjaman').mask('000,000,000,000,000' , {reverse: true});
			$('#tgllahir').mask('99/99/9999');
			$('#jangkawaktupinjaman').mask('000' , {reverse: true});
			<?php
			}
			?>
			<?php
			if($typedata=="verifikasideklarasi"){
			?>
			$(".active").removeClass("active");
			document.getElementById("has_verification").classList.add("active");
			document.getElementById("idsub_datavalidasi_deklarasi").classList.add("active");
			$("#data-tableverifikasi").DataTable({
				responsive: true
			})
			<?php
			}
			if($typedata=="dataspk"){
			?>
			$(".active").removeClass("active");
			document.getElementById("has_verification").classList.add("active");
			document.getElementById("idsub_datavalidasi_spk").classList.add("active");


			$('#modal-alert').on('show.bs.modal', function(e) {
				idspk = $(e.relatedTarget).data('id');
				nospk = document.getElementById("nospk");
				nospk.value = idspk;

			});


			$('#form-batal').bootstrapValidator({
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
					txtalasan: {
						validators: {
							notEmpty: {
								message: 'Username harus diisi'
							}
						}
					}
				}
			});


			$('.alasan')
			.on('focus', function (e) {
				$('.form-batal').bootstrapValidator('revalidateField', 'txtalasan');
			});


			<?php
			}
			if($typedata=="dataskkt"){
			?>
			$(".active").removeClass("active");
			document.getElementById("has_verification").classList.add("active");
			document.getElementById("idsub_datavalidasi_skkt").classList.add("active");


			$('#modal-alert').on('show.bs.modal', function(e) {
				idspk = $(e.relatedTarget).data('id');
				nospk = document.getElementById("nospk");
				nospk.value = idspk;

			});


			$('#form-batal').bootstrapValidator({
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
					txtalasan: {
						validators: {
							notEmpty: {
								message: 'Username harus diisi'
							}
						}
					}
				}
			});


			$('.alasan')
			.on('focus', function (e) {
				$('.form-batal').bootstrapValidator('revalidateField', 'txtalasan');
			});


			<?php
			}else{

			}
			?>


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
				"paging":   false,
				"ordering": false,
				"info":     false
			})

		});
	</script>
</body>

</html>
