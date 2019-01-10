<?php
include "../param.php";
include_once('../includes/functions.php');
if (isset($_REQUEST['type'])) {
    $typedata = $_REQUEST['type'];
    $typedata = AES::decrypt128CBC($typedata, ENCRYPTION_KEY);
} else {
    header("location:../dashboard");
}
if (isset($_REQUEST['er'])) {
    $jenisklaim = $_REQUEST['er'];
    if ($jenisklaim == "klaimphk") {
        $judul = "PHK";
    } elseif ($jenisklaim == "klaimpaw") {
        $judul = "PAW";
    } elseif ($jenisklaim == "klaimmacet") {
        $judul = "Kredit Macet";
    } else {
        $judul = "Meninggal";
    }
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
_header($user, $namauser, $photo, $logo, $logoklient);
_sidebar($user, $namauser, '', '');
?>
		<!-- begin #content -->
		<div id="content" class="content">
			<?php
            if ($typedata == 'klaimBatal') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Pengajuan Data Batal</h4>
				<div class="section-container section-with-top-border">
					<?php

                        if (isset($_REQUEST['xq'])=="batal") {
                            if ($_REQUEST['nopinjaman']=="" && $_REQUEST['namapeserta']=="") {
                                echo '<div class="alert alert-danger alert-bordered fade in">
												<strong>Error!</strong>Silahkan isi data nomor atau nama debitur.<span class="close" data-dismiss="alert">&times;</span>
											</div>
											<meta http-equiv="refresh" content="2; url=../klaim?type='.$_REQUEST['type'].'">';
                            } else {
                                if ($_REQUEST['nopinjaman']!="" and $_REQUEST['namapeserta']=="") {
                                    $metReqBatal = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND';
                                } elseif ($_REQUEST['nopinjaman']=="" and $_REQUEST['namapeserta']!="") {
                                    $metReqBatal = 'ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
                                } else {
                                    $metReqBatal = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
                                }

                                $xBatal = mysql_query('SELECT ajkpeserta.id,
																							ajkpeserta.idpeserta,
																							ajkpeserta.nama,
																							ajkpolis.produk,
																							ajkpolis.jumlahharibatal,
																							ajkpeserta.tgllahir,
																							ajkpeserta.usia,
																							ajkpeserta.tglakad,
																							ajkpeserta.tenor,
																							ajkpeserta.tglakhir,
																							ajkpeserta.plafond,
																							ajkpeserta.totalpremi,
																							ajkpeserta.statusaktif,
																							ajkpeserta.statuspeserta,
																							ajkpeserta.cabang,
																							ajkpeserta.nopinjaman,
																							ajkdebitnote.nomordebitnote,
																							ajkdebitnote.tgldebitnote,
																							ajkcabang.`name` AS cabang
																			FROM ajkpeserta
																			INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
																			INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																			INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
																			WHERE ajkpeserta.statusaktif="Inforce" AND
																							'.$metReqBatal.'
																							ajkpeserta.statuspeserta IS NULL AND
																							ajkpeserta.idbroker="'.$idbro.'" AND
																							ajkpeserta.idclient="'.$idclient.'" AND
																							ajkpeserta.cabang="'.$cabang.'" AND
																							ajkpeserta.statuslunas="1" AND
																							ajkpeserta.del IS NULL'); ?>
						<table id="data-peserta-batal" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="primary">
								<th>No</th>
									<th>No Pinjaman</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Produk</th>
									<th>Tgl.Lahir</th>
									<th>Umur</th>
									<th>Tgl Akad</th>
									<th>Tenor</th>
									<th>Tgl Akhir</th>
									<th>Plafond</th>
									<th>Premium</th>
									<th>Status</th>
									<th>Cabang</th>
								</tr>
							</thead>
							<tbody>
							<?php
                                $li_row =1;
                                while ($xBatal_ = mysql_fetch_array($xBatal)) {
                                    echo '<tr class="odd gradeX">
													<td>'.$li_row.'</td>
													<td><a href="dorequest.php?xq=batal&er='.AES::encrypt128CBC($xBatal_['id'], ENCRYPTION_KEY).'" title="pengajuan data batal">'.$xBatal_['nopinjaman'].'</a></td>
													<td>'.$xBatal_['idpeserta'].'</td>
													<td>'.$xBatal_['nama'].'</td>
													<td>'.$xBatal_['produk'].'</td>
													<td>'._convertDate($xBatal_['tgllahir']).'</td>
													<td>'.$xBatal_['usia'].'</td>
													<td>'._convertDate($xBatal_['tglakad']).'</td>
													<td>'.$xBatal_['tenor'].'</td>
													<td>'._convertDate($xBatal_['tglakhir']).'</td>
													<td class="text-right">'.duit($xBatal_['plafond']).'</td>
													<td class="text-right">'.duit($xBatal_['totalpremi']).'</td>
													<td>'.$xBatal_['statusaktif'].'</td>
													<td>'.$xBatal_['cabang'].'</td>
								        </tr>';
                                    $li_row++;
                                } ?>
					    </tbody>
						</table>
					<?php
                            }
                        } else {
                            ?>
							<form action="#" id="inputmember" class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label class="control-label col-sm-2">Cabang </label>
									<div class="col-sm-10"><label class="control-label "><?php echo $namacabang ?> </label></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" style="display: none;">ID Peserta </label>
									<div class="col-sm-10"><input style="display: none;" name="nomorpeserta" id="nomorpeserta" class="form-control" placeholder="Silahkan Input Nomor Peserta" type="text"></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">No Pinjaman </label>
									<div class="col-sm-10"><input name="nopinjaman" id="nopinjaman" class="form-control" placeholder="Silahkan Input Nomor Pinjaman" type="text"></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Nama Tertanggung </label>
									<div class="col-sm-10"><input name="namapeserta" id="namapeserta" class="form-control" placeholder="Silahkan Input Nama Peserta" type="text"></div>
								</div>
								<div class="form-group m-b-0">
									<label class="control-label col-sm-2"></label>
									<div class="col-sm-10"><input type="hidden" name="xq" value="batal"/><button type="submit" class="btn btn-success width-xs">Submit</button></div>
								</div>
								<div id="progressbox" style="display:none;">
									<div class="progress">
										<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
											<div id="statustxt" class="info"></div>
										</div>
									</div>
								</div>
							</form>
					<?php
                        } ?>
        </div>
	      <!-- end section-container -->
	  	</div>
			<?php
            } elseif ($typedata == 'klaimRefund') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Pengajuan Data Refund</h4>
				<div class="section-container section-with-top-border">
					<?php

                        if (isset($_REQUEST['xq'])=="refund") {
                            if ($_REQUEST['nopinjaman']=="" && $_REQUEST['namapeserta']=="") {
                                echo '<div class="alert alert-danger alert-bordered fade in">
												<strong>Error!</strong>Silahkan isi data nomor atau nama debitur.<span class="close" data-dismiss="alert">&times;</span>
											</div>
											<meta http-equiv="refresh" content="2; url=../klaim?type='.$_REQUEST['type'].'">';
                            } else {
                                if ($_REQUEST['nopinjaman']!="" and $_REQUEST['namapeserta']=="") {
                                    $metReqBatal = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND';
                                } elseif ($_REQUEST['nopinjaman']=="" and $_REQUEST['namapeserta']!="") {
                                    $metReqBatal = 'ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
                                } else {
                                    $metReqBatal = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
                                }

                                $xBatal = mysql_query('SELECT ajkpeserta.id,
																							ajkpeserta.idpeserta,
																							ajkpeserta.nama,
																							ajkpolis.produk,
																							ajkpolis.jumlahharibatal,
																							ajkpeserta.tgllahir,
																							ajkpeserta.usia,
																							ajkpeserta.tglakad,
																							ajkpeserta.tenor,
																							ajkpeserta.tglakhir,
																							ajkpeserta.plafond,
																							ajkpeserta.totalpremi,
																							ajkpeserta.statusaktif,
																							ajkpeserta.statuspeserta,
																							ajkpeserta.cabang,
																							ajkpeserta.nopinjaman,
																							ajkdebitnote.nomordebitnote,
																							ajkdebitnote.tgldebitnote,
																							ajkcabang.`name` AS cabang
																			FROM ajkpeserta
																			INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
																			INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																			INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
																			WHERE ajkpeserta.statusaktif="Inforce" AND
																							'.$metReqBatal.'
																							ajkpeserta.statuspeserta IS NULL AND
																							ajkpeserta.idbroker="'.$idbro.'" AND
																							ajkpeserta.idclient="'.$idclient.'" AND
																							ajkpeserta.cabang="'.$cabang.'" AND
																							ajkpeserta.statuslunas="1" AND
																							ajkpeserta.del IS NULL'); ?>
						<table id="data-peserta-batal" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="primary">
								<th>No</th>
									<th>No Pinjaman</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Produk</th>
									<th>Tgl.Lahir</th>
									<th>Umur</th>
									<th>Tgl Akad</th>
									<th>Tenor</th>
									<th>Tgl Akhir</th>
									<th>Plafond</th>
									<th>Premium</th>
									<th>Status</th>
									<th>Cabang</th>
								</tr>
							</thead>
							<tbody>
							<?php
                                $li_row =1;
                                while ($xBatal_ = mysql_fetch_array($xBatal)) {
                                    echo '<tr class="odd gradeX">
													<td>'.$li_row.'</td>
													<td><a href="dorequest.php?xq=refund&er='.AES::encrypt128CBC($xBatal_['id'], ENCRYPTION_KEY).'" title="pengajuan data refund">'.$xBatal_['nopinjaman'].'</a></td>
													<td>'.$xBatal_['idpeserta'].'</td>
													<td>'.$xBatal_['nama'].'</td>
													<td>'.$xBatal_['produk'].'</td>
													<td>'._convertDate($xBatal_['tgllahir']).'</td>
													<td>'.$xBatal_['usia'].'</td>
													<td>'._convertDate($xBatal_['tglakad']).'</td>
													<td>'.$xBatal_['tenor'].'</td>
													<td>'._convertDate($xBatal_['tglakhir']).'</td>
													<td class="text-right">'.duit($xBatal_['plafond']).'</td>
													<td class="text-right">'.duit($xBatal_['totalpremi']).'</td>
													<td>'.$xBatal_['statusaktif'].'</td>
													<td>'.$xBatal_['cabang'].'</td>
								        </tr>';
                                    $li_row++;
                                } ?>
					    </tbody>
						</table>
					<?php
                            }
                        } else {
                            ?>
							<form action="#" id="inputmember" class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label class="control-label col-sm-2">Cabang </label>
									<div class="col-sm-10"><label class="control-label "><?php echo $namacabang ?> </label></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" style="display: none;">ID Peserta </label>
									<div class="col-sm-10"><input style="display: none;" name="nomorpeserta" id="nomorpeserta" class="form-control" placeholder="Silahkan Input Nomor Peserta" type="text"></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">No Pinjaman </label>
									<div class="col-sm-10"><input name="nopinjaman" id="nopinjaman" class="form-control" placeholder="Silahkan Input Nomor Pinjaman" type="text"></div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Nama Tertanggung </label>
									<div class="col-sm-10"><input name="namapeserta" id="namapeserta" class="form-control" placeholder="Silahkan Input Nama Peserta" type="text"></div>
								</div>
								<div class="form-group m-b-0">
									<label class="control-label col-sm-2"></label>
									<div class="col-sm-10"><input type="hidden" name="xq" value="refund"/><button type="submit" class="btn btn-success width-xs">Submit</button></div>
								</div>
								<div id="progressbox" style="display:none;">
									<div class="progress">
										<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
											<div id="statustxt" class="info"></div>
										</div>
									</div>
								</div>
							</form>
					<?php
                        } ?>
        </div>
	      <!-- end section-container -->
	  	</div>
			<?php
            } elseif ($typedata == 'klaimRefundBak') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Pengajuan Data <?php echo strtoupper($_REQUEST['qtype']); ?></h4>
				<div class="section-container section-with-top-border">
					<form action="uploadrefund.php" id="uploadmember" class="form-horizontal" method="post" enctype="multipart/form-data">
						<input type="hidden" name="qtype" value="<?php echo $_REQUEST['qtype']; ?>"/>
						<div class="form-group">
			        <label class="control-label col-sm-3">Nama Partner </label>
			        <div class="col-sm-6"><label class="control-label "><?php echo $namaklient ?> </label></div>
			        </div>
			        <div class="form-group">
								<label class="control-label col-sm-3">Nama Produk <span class="text-danger">*</span></label>
								<div class="col-sm-6"><select class="form-control" name="namaproduk">
									<option value="">-- Pilih Produk --</option>
									<?php
                                        $queryprod = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND del IS NULL");
                while ($rowprod = mysql_fetch_array($queryprod)) {
                    $idprod = $rowprod['id'];
                    $namaprod = $rowprod['produk'];
                    echo '<option value="'.$idprod.'">'.$namaprod.'</option>';
                } ?>
									</select>
								</div>
			        </div>
			        <div class="form-group">
			        	<label class="control-label col-sm-3">Silakan Pilih File Excel <span class="text-danger">*</span></label>
			        	<div class="col-sm-6"><input type="file" name="fileupload" id="fileupload" class="form-control" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" /></div>
			        </div>
			        <div class="form-group m-b-0">
			        	<label class="control-label col-sm-3"></label>
			        	<div class="col-sm-6"><button type="submit" class="btn btn-success width-xs">Import</button></div>
			        </div>
			        <div id="progressbox" style="display:none;">
								<div class="progress">
									<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
										<div id="statustxt" class="info"></div>
									</div>
								</div>
							</div>
		      </form>
	      </div>
	      <!-- end section-container -->
	    </div>
			<?php
            } elseif ($typedata=="klaimTopup") {
                ?>
			<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Pengajuan Data Topup</h4>
	      </div>
	      <!-- end section-container -->
	   	</div>
			<?php
            } elseif ($typedata == 'klaimKlaim') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Pengajuan Data Klaim <?php echo $judul; ?></h4>
				<div class="section-container section-with-top-border">
				<?php
	        if (isset($_REQUEST['xq'])=="klaimmeninggal") {
            if ($_REQUEST['nopinjaman']=="" && $_REQUEST['namapeserta']=="") {
              echo '
              <div class="alert alert-danger alert-bordered fade in">
							<strong>Error!</strong>Silahkan isi data No Pinjaman atau nama debitur.<span class="close" data-dismiss="alert">&times;</span>
							</div>
							<meta http-equiv="refresh" content="2; url=../klaim?type='.$_REQUEST['type'].'">';
            } else {
              if ($_REQUEST['nopinjaman']!="" and $_REQUEST['namapeserta']=="") {
                $metReqKlaim = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND';
              } elseif ($_REQUEST['nopinjaman']=="" and $_REQUEST['namapeserta']!="") {
                $metReqKlaim = 'ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
              } else {
                $metReqKlaim = 'ajkpeserta.nopinjaman LIKE "%'.$_REQUEST['nopinjaman'].'%" AND ajkpeserta.nama LIKE "%'.$_REQUEST['namapeserta'].'%" AND';
              }
              $cabangpusat = mysql_fetch_array(mysql_query("SELECT * FROM ajkcabang WHERE er = '".$rowuser['branch']."' AND idclient = '".$idclient."'"));
              if ($cabangpusat['name']=="PUSAT") {
                $authcabang ='';
              } else {
                $authcabang ='ajkpeserta.cabang="'.$cabang.'" AND';
              }

              if($judul == "PAW"){
              	$qstatus = 'ajkpeserta.pekerjaan in(select ref_mapping from ajkprofesi where idkategoriprofesi = 3) AND ';
              }else{
								$qstatus = "";
              }

              $query = 'SELECT ajkpeserta.id,
							ajkpeserta.idpeserta,
							ajkpeserta.nama,
							ajkpolis.produk,
							ajkpolis.jumlahharibatal,
							ajkpeserta.tgllahir,
							ajkpeserta.usia,
							ajkpeserta.tglakad,
							ajkpeserta.tenor,
							ajkpeserta.tglakhir,
							ajkpeserta.plafond,
							ajkpeserta.totalpremi,
							ajkpeserta.statusaktif,
							ajkpeserta.statuspeserta,
							ajkpeserta.statuslunas,
							ajkpeserta.cabang,
							ajkpeserta.nopinjaman,
							ajkdebitnote.nomordebitnote,
							ajkdebitnote.tgldebitnote,
							ajkdebitnote.paidstatus,
							ajkcabang.`name` AS cabang
							FROM ajkpeserta
							INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
							INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
							INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
							WHERE ajkpeserta.statusaktif="Inforce" AND
							'.$metReqKlaim.'
							ajkpeserta.statuspeserta IS NULL AND
							ajkpeserta.idbroker="'.$idbro.'" AND
							ajkpeserta.idclient="'.$idclient.'" AND
							'.$authcabang.'
							'.$qstatus.'
							ajkpeserta.del IS NULL AND
							ajkpeserta.statuslunas="1"
							ORDER BY ajkdebitnote.paidstatus DESC,  ajkpeserta.nama ASC';
							
							$xBatal = mysql_query($query); 
					?>
					<table id="data-peserta" class="table table-bordered table-hover" width="100%">
						<thead>
							<tr class="primary">
								<th>No</th>
								<th>No Pinjaman</th>
								<th>ID Peserta</th>
								<th>Nama</th>
								<th>Produk</th>
								<th>Tgl.Lahir</th>
								<th>Umur</th>
								<th>Tgl Akad</th>
								<th>Tenor</th>
								<th>Tgl Akhir</th>
								<th>Plafond</th>
								<th>Premi</th>
								<th>Status</th>
								<th>pembayaran</th>
								<th>Cabang</th>
							</tr>
						</thead>
						<tbody>
						<?php
              $li_row =1;
              while ($xBatal_ = mysql_fetch_array($xBatal)) {
                if ($xBatal_['statuslunas']=='0') {
                  $statusByr = '<span class="label label-danger">Unpaid</span>';
                  $setklaim =$xBatal_['nama'];
                } else {
                  $statusByr = '<span class="label label-success">Paid</span>';

                  $cekpengajuan = mysql_fetch_array(mysql_query('SELECT idpeserta FROM ajkcreditnote WHERE idpeserta="'.$xBatal_['id'].'" AND status !="Cancel"'));
                  if ($cekpengajuan['idpeserta']) {
                    $setklaim ='<a href="#modal-alert" data-toggle="modal">'.$xBatal_['nama'].' '.$cekpengajuan['idpeserta'].'</a>
										<div class="modal fade" id="modal-alert">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
														<h4 class="modal-title">Pengajuan klaim meninggal</h4>
													</div>
													<div class="modal-body">
														<div class="alert alert-danger m-b-0"><p><b>Data sudah pernah dibuat,</b> silahkan lihat pada menu Pengajuan - Data Klaim Meninggal</p></div>
													</div>
													<div class="modal-footer"><a href="javascript:;" class="btn width-100 btn-default" data-dismiss="modal">Close</a></div>
												</div>
											</div>
										</div>';
                  } else {
                      $setklaim ='<a href="dorequest.php?xq=klaim&er='.AES::encrypt128CBC($xBatal_['id'], ENCRYPTION_KEY).'&tk='.$jenisklaim.'" title="pengajuan data klaim">'.$xBatal_['nopinjaman'].' '.$cekpengajuan['idpeserta'].'</a>';
                  }
                }

		            echo '<tr class="odd gradeX">
								<td>'.$li_row.'</td>
								<td>'.$setklaim.'</td>
								<td>'.$xBatal_['idpeserta'].'</td>
								<td>'.$xBatal_['nama'].'</td>
								<td>'.$xBatal_['produk'].'</td>
								<td>'._convertDate($xBatal_['tgllahir']).'</td>
								<td>'.$xBatal_['usia'].'</td>
								<td>'._convertDate($xBatal_['tglakad']).'</td>
								<td>'.$xBatal_['tenor'].'</td>
								<td>'._convertDate($xBatal_['tglakhir']).'</td>
								<td class="text-right">'.duit($xBatal_['plafond']).'</td>
								<td class="text-right">'.duit($xBatal_['totalpremi']).'</td>
								<td>'.$xBatal_['statusaktif'].'</td>
								<td>'.$statusByr.'</td>
								<td>'.$xBatal_['cabang'].'</td>
								</tr>';
                $li_row++;
              } 
            ?>
						</tbody>
					</table>
					<?php
          	}
          } else {
          ?>
					<form action="#" id="inputmember" class="form-horizontal" method="post" enctype="multipart/form-data">
						<div class="form-group">
					    <label class="control-label col-sm-2">Cabang </label>
					    <div class="col-sm-10"><label class="control-label "><?php echo $namacabang ?> </label></div>
						</div>
						<div class="form-group">
					    <label class="control-label col-sm-2"  style="display: none;">ID Peserta </label>
					    <div class="col-sm-10"><input name="nomorpeserta" id="nomorpeserta" style="display: none;" class="form-control" placeholder="Silahkan Input Nomor Peserta" type="text"></div>
						</div>
						<div class="form-group">
					    <label class="control-label col-sm-2">No Pinjaman </label>
					    <div class="col-sm-10"><input name="nopinjaman" id="nopinjaman" class="form-control" placeholder="Silahkan Input Nomor Pinjaman" type="text"></div>
						</div>

						<div class="form-group">
					    <label class="control-label col-sm-2">Nama Tertanggung </label>
					    <div class="col-sm-10"><input name="namapeserta" id="namapeserta" class="form-control" placeholder="Silahkan Input Nama Peserta" type="text"></div>
						</div>
						<div class="form-group m-b-0">
					    <label class="control-label col-sm-2"></label>
					    <div class="col-sm-10"><input type="hidden" name="xq" value="klaimmeninggal"/><button type="submit" class="btn btn-success width-xs">Submit</button></div>
						</div>

						<div id="progressbox" style="display:none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									<div id="statustxt" class="info"></div>
								</div>
							</div>
						</div>
					</form>
					<?php
          } 
          ?>
				</div>
				<!-- end section-container -->
			</div>
			<?php
            } elseif ($typedata == 'klaimData') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Data Klaim</h4>
				<div class="section-container section-with-top-border">
					<?php
                        $cabangpusat = mysql_fetch_array(mysql_query("SELECT * FROM ajkcabang WHERE er = '".$rowuser['branch']."' AND idclient = '".$idclient."'"));
                if ($cabangpusat['name']=="PUSAT") {
                    $authcabang ='';
                } else {
                    $authcabang ='ajkcreditnote.idcabang = "'.$cabang.'" AND';
                }
                $metDataCN = mysql_query('SELECT
					ajkcreditnote.id,
					ajkcreditnote.idpeserta AS idmember,
					ajkpolis.produk,
					ajkdebitnote.nomordebitnote,
					ajkcabang.`name` AS namacabang,
					ajkpeserta.idpeserta,
					ajkpeserta.nama,
					ajkpeserta.gender,
					ajkpeserta.tgllahir,
					ajkpeserta.usia,
					ajkpeserta.tglakad,
					ajkpeserta.tenor,
					ajkpeserta.tglakhir,
					ajkpeserta.plafond,
					ajkpeserta.premirate,
					ajkpeserta.totalpremi,
					ajkpeserta.statusaktif,
					ajkpeserta.nopinjaman,
					ajkpeserta.statuspeserta,
					ajkcreditnote.nomorcreditnote,
					ajkcreditnote.tglklaim,
					ajkcreditnote.tgllengkapdokumen,
					ajkcreditnote.status,
					ajkcreditnote.tipeklaim,
					ajkcreditnote.nilaiklaimdiajukan,
					ajkcreditnote.tempatmeninggal,
					ajkcreditnote.penyebabmeninggal,
					ajkcreditnote.tipeklaim
					FROM ajkcreditnote
					INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
					INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
					INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
					INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
					WHERE
					ajkcreditnote.idbroker = "'.$idbro.'" AND
					ajkcreditnote.idclient = "'.$idclient.'" AND
					'.$authcabang.'
					ajkcreditnote.del IS NULL and
					ajkcreditnote.tipeklaim in ("Death","PAW","PHK","Kredit Macet")
					ORDER BY ajkcreditnote.id DESC'); ?>
					<table id="data-peserta" class="table table-bordered table-hover" width="100%">
						<thead>
							<tr class="primary">
								<th>No</th>
								<th>Option</th>
								<th>No Pinjaman</th>
								<th>Nota Kredit</th>
								<th>ID Peserta</th>
								<th>Nama</th>
								<th>Tgl.Akad</th>
								<th>Tenor</th>
								<th>Tanggal<br />Klaim</th>
								<th>Plafond</th>								
								<th>Tipe<br />Klaim</th>
								<th>Status</th>
								<th>Status Dok.</th>								
								<th>Cabang</th>								
								<th>Produk</th>
								<th>Tgl.Lahir</th>
								<th>Umur</th>								
								<th>Premium</th>								
							</tr>
						</thead>
						<tbody>
						<?php
                        $li_row =1;
                while ($metDataCN_ = mysql_fetch_array($metDataCN)) {
                    if ($metDataCN_['status']=="Batal" or $metDataCN_['status']=="Cancel") {
                        $metStatusData = '<span class="label label-danger">'.$metDataCN_['status'].'</span>';
                    } elseif ($metDataCN_['status']=="Request") {
                        $metStatusData = '<span class="label label-warning">'.$metDataCN_['status'].'</span>';
                    } elseif ($metDataCN_['status']=="Pending" or $metDataCN_['status']=="Process") {
                        $metStatusData = '<span class="label label-default">'.$metDataCN_['status'].'</span>';
                    } elseif ($metDataCN_['status']=="Approve" or $metDataCN_['status']=="Approve Unpaid") {
                        $metStatusData = '<span class="label label-info">'.$metDataCN_['status'].'</span>';
                    } elseif ($metDataCN_['status']=="Approve Paid") {
                        $metStatusData = '<span class="label label-primary">'.$metDataCN_['status'].'</span>';
                    } else {
                        $metStatusData = '<span class="label label-success">'.$metDataCN_['status'].'</span>';
                    }

                    if ($metDataCN_['status']=="") {
                        $delDataKalim = '<a class="btn btn-danger btn-icon btn-circle btn-sm" href="#pembatalanklaim'.$metDataCN_['id'].'" data-toggle="modal"><i class="fa fa-remove"></i></a>
															<div class="modal" id="pembatalanklaim'.$metDataCN_['id'].'">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
																			<h4 class="modal-title">Pembatalan Pengajuan Data '.$metDataCN_['tipeklaim'].'</h4>
																		</div>
																		<form action="#" id="uploadmember" class="form-horizontal" method="post" enctype="multipart/form-data">
																			<input type="hidden" name="idcnklaim" value="'.$metDataCN_['id'].'">
																			<div class="col-sm-3">Produk</div><div class="col-sm-9">'.$metDataCN_['produk'].'</div>
																			<div class="col-sm-3">ID Peserta</div><div class="col-sm-9">'.$metDataCN_['idpeserta'].'</div>
																			<div class="col-sm-3">Nama</div><div class="col-sm-9">'.$metDataCN_['nama'].'</div>
																			<div class="col-sm-3">Tanggal Lahir</div><div class="col-sm-9">'._convertDate($metDataCN_['tgllahir']).'</div>
																			<div class="col-sm-3">Plafond</div><div class="col-sm-9">'.duit($metDataCN_['plafond']).'</div>
																			<div class="col-sm-3">Alasan dibatalkan</div><div class="col-sm-9">
																			<textarea class="form-control" name="alasanbatal" id="alasanbatal" value="'.$_REQUEST['alasanbatal'].'" rows="3" cols="55" data-parsley-required="true" placeholder="Alasan Pembatalan Pengajuan Data '.$metDataCN_['tipeklaim'].'"></textarea>
																			</div>
																			<div class="col-sm-12"> &nbsp; </div>
																			<Center><input type="hidden" name="type" value="'.AES::encrypt128CBC('PengajuanKlaimBatal', ENCRYPTION_KEY).'"><button type="submit" class="btn btn-danger m-b-5">Proses Pembatalan Pengajuan Data '.$metDataCN_['tipeklaim'].'</button></Center>
																		</form>
																	</div>
																</div>
															</div>';
                    } else {
                        $delDataKalim = '';
                    }

                    if ($metDataCN_['status']=="Proses Penyelia") {
                        // $editDataKlaim ='<a class="btn btn-warning btn-icon btn-circle btn-sm" href="dorequest.php?xq=edklaim&cnIDp='.AES::encrypt128CBC($metDataCN_['id'], ENCRYPTION_KEY).'"><i class="fa fa-pencil"></i></a>';
												if($metDataCN_['tipeklaim'] == "PHK"){
                    			$tipeklaim = 'klaimphk';
	                    	}elseif($metDataCN_['tipeklaim'] == "PAW"){
	                    		$tipeklaim = 'klaimpaw';
	                    	}elseif($metDataCN_['tipeklaim'] == "PAW"){
	                    		$tipeklaim = 'klaimmacet';
												}                    	
                    $editDataKlaim ='<a class="btn btn-warning btn-sm" href="dorequest.php?xq=edklaim&tk='.$tipeklaim.'&cnIDp='.AES::encrypt128CBC($metDataCN_['id'],ENCRYPTION_KEY).'">Edit <i class="fa fa-pencil"></i></a>';
                    } else {
                        $editDataKlaim ='';
                    }
                    if($metDataCN_['tgllengkapdokumen'] != ""){
                    	$metStatusDok = '<span class="label label-success">Lengkap</span>';
                    }else{
                    	$metStatusDok = '<span class="label label-danger">Belum Lengkap</span>';
                    }
                    echo '<tr class="odd gradeX">
								<td>'.$li_row.'</td>
								<td class="text-right"><div class="col-md-6">
									'.$editDataKlaim.'
									</div>
									<div class="col-md-6">
									'.$delDataKalim.'
									</div>
								</td>
								<td><a href="dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metDataCN_['idpeserta'], ENCRYPTION_KEY).'">'.$metDataCN_['nopinjaman'].'</a></td>
								<td><a href="../_admin/ajk.php?re=dlPdf&pdf=dlPdfcn&idc='.metEncrypt($metDataCN_['id']).'&logCN='.metEncrypt("B").'" target="_blank">'.$metDataCN_['nomorcreditnote'].'</a></td>
								<td>'.$metDataCN_['idpeserta'].'</td>
								<td>'.$metDataCN_['nama'].'</td>
								<td>'._convertDate($metDataCN_['tglakad']).'</td>
								<td>'.$metDataCN_['tenor'].'</td>
								<td>'._convertDate($metDataCN_['tglklaim']).'</td>
								<td class="text-right">'.duit($metDataCN_['plafond']).'</td>
								<td><span class="label label-info">'.$metDataCN_['tipeklaim'].'</span></td>
								<td>'.$metStatusData.'</td>
								<td>'.$metStatusDok.'</td>																
								<td>'.$metDataCN_['namacabang'].'</td>								
								<td>'.$metDataCN_['produk'].'</td>
								<td>'._convertDate($metDataCN_['tgllahir']).'</td>
								<td>'.$metDataCN_['usia'].'</td>								
								<td class="text-right">'.duit($metDataCN_['totalpremi']).'</td>
					      </tr>';
                    $li_row++;
                } ?>
					</tbody>
					</table>
				</div>
			</div>
			<?php
            } elseif ($typedata == 'klaimBatalVerifikasi') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Verifikasi Data Batal</h4>
				<div class="section-container section-with-top-border">
					<?php
                        $metDataCN = mysql_query('SELECT
						ajkcreditnote.id,
						ajkpolis.produk,
						ajkpeserta.idpeserta,
						ajkdebitnote.nomordebitnote,
						ajkcabang.`name` AS namacabang,
						ajkpeserta.nama,
						ajkpeserta.gender,
						ajkpeserta.tgllahir,
						ajkpeserta.usia,
						ajkpeserta.tglakad,
						ajkpeserta.tenor,
						ajkpeserta.tglakhir,
						ajkpeserta.plafond,
						ajkpeserta.premirate,
						ajkpeserta.totalpremi,
						ajkpeserta.statusaktif,
						ajkpeserta.statuspeserta,
						ajkcreditnote.tglklaim,
						ajkcreditnote.`status`,
						ajkcreditnote.tipeklaim
						FROM ajkcreditnote
						INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
						INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
						INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
						INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
						WHERE
						ajkcreditnote.idbroker = "'.$idbro.'" AND
						ajkcreditnote.idclient = "'.$idclient.'" AND
						ajkcreditnote.idcabang = "'.$cabang.'" AND
						ajkcreditnote.del IS NULL AND
						ajkcreditnote.tipeklaim = "Batal" AND
						ajkpeserta.statuspeserta = "Req_Batal"'); ?>
					<form action="dorequest.php?xq=appbatal" id="form-validasispk" class="form-horizontal" method="post" enctype="multipart/form-data">
						<table id="data-peserta" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="primary">
									<th>No</th>
									<th><center>Pilih Semua<br /><input onClick="toggle(this)" class="styled" type="checkbox" ></center>
									<th>Produk</th>
									<th>Nota Debit</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Tgl.Lahir</th>
									<th>Umur</th>
									<th>Tgl.Akad</th>
									<th>Tenor</th>
									<th>Tgl.Akhir</th>
									<th>Plafond</th>
									<th>Premium</th>
									<th>Status</th>
									<th>Tanggal<br />Klaim</th>
									<th>Cabang</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $li_row =1;
                while ($metDataCN_ = mysql_fetch_array($metDataCN)) {
                    if ($metDataCN_['statuspeserta']=="Req_Batal") {
                        $metStatusData = '<span class="label label-warning">'.$metDataCN_['statuspeserta'].'</span>';
                    } else {
                        $metStatusData = '<span class="label label-info">'.$metDataCN_['statuspeserta'].'</span>';
                    }
                    echo '<tr class="odd gradeX">
													<td>'.$li_row.'</td>
													<td><center><input name="approve[]" id="'.$metDataCN_['id'].'" class="styled" type="checkbox" value="'.$metDataCN_['id'].'"></center></td>
													<td>'.$metDataCN_['produk'].'</td>
													<td>'.$metDataCN_['nomordebitnote'].'</td>
													<td>'.$metDataCN_['idpeserta'].'</td>
													<td><a href="dorequest.php?xq=vRegBatal&cnID='.AES::encrypt128CBC($metDataCN_['id'], ENCRYPTION_KEY).'" target="_blank">'.$metDataCN_['nama'].'</a></td>
													<td>'._convertDate($metDataCN_['tgllahir']).'</td>
													<td>'.$metDataCN_['usia'].'</td>
													<td>'._convertDate($metDataCN_['tglakad']).'</td>
													<td>'.$metDataCN_['tenor'].'</td>
													<td>'._convertDate($metDataCN_['tglakhir']).'</td>
													<td class="text-right">'.duit($metDataCN_['plafond']).'</td>
													<td class="text-right">'.duit($metDataCN_['totalpremi']).'</td>
													<td>'.$metStatusData.'</td>
													<td>'._convertDate($metDataCN_['tglklaim']).'</td>
													<td>'.$metDataCN_['namacabang'].'</td>
								        </tr>';
                    $li_row++;
                } ?>
							</tbody>
						</table>
						<div class="form-group m-b-0">
						<label class="control-label col-sm-12"></label>
						<div class="col-sm-6"><input name="sub" class="btn btn-success width-xs" value="Approved" type="submit"></div>
						</div>
					</form>
					<!-- end section-container -->
				</div>
	  	</div>
			<?php
            } elseif ($typedata == 'klaimRefundVerifikasi') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Verifikasi Data Refund</h4>
				<div class="section-container section-with-top-border">
				<?php

                    $metDataCN = mysql_query('SELECT
											ajkcreditnote.id,
											ajkpolis.produk,
											ajkpeserta.idpeserta,
											ajkdebitnote.nomordebitnote,
											ajkcabang.`name` AS namacabang,
											ajkpeserta.nama,
											ajkpeserta.gender,
											ajkpeserta.tgllahir,
											ajkpeserta.usia,
											ajkpeserta.tglakad,
											ajkpeserta.tenor,
											ajkpeserta.tglakhir,
											ajkpeserta.plafond,
											ajkpeserta.premirate,
											ajkpeserta.totalpremi,
											ajkpeserta.statusaktif,
											ajkpeserta.statuspeserta,
											ajkcreditnote.tglklaim,
											ajkcreditnote.`status`,
											ajkcreditnote.tipeklaim
											FROM ajkcreditnote
											INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
											INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
											INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
											INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
											WHERE
											ajkcreditnote.idbroker = "'.$idbro.'" AND
											ajkcreditnote.idclient = "'.$idclient.'" AND
											ajkcreditnote.idcabang = "'.$cabang.'" AND
											ajkcreditnote.del IS NULL AND
											ajkcreditnote.tipeklaim = "Refund" AND
											ajkpeserta.statuspeserta = "Req_Refund"'); ?>
				<form action="dorequest.php?xq=apprefund" id="form-validasispk" class="form-horizontal" method="post" enctype="multipart/form-data">
					<table id="data-peserta" class="table table-bordered table-hover" width="100%">
						<thead>
						<tr class="primary">
						<th width="1%">No</th>
						<th width="1%"><center><input onClick="toggle(this)" class="styled" type="checkbox" ></center>
						<th>Produk</th>
						<th width="1%">No Pinjaman</th>
						<th width="1%">ID Peserta</th>
						<th>Nama</th>
						<th>Tgl.Lahir</th>
						<th width="1%">Umur</th>
						<th>Tgl.Akad</th>
						<th width="1%">Tenor</th>
						<th>Tgl.Akhir</th>
						<th>Plafond</th>
						<th>Premium</th>
						<th width="1%">Status</th>
						<th>Tanggal<br />Refund</th>
						<th>Cabang</th>
						</tr>
						</thead>
						<tbody>
						<?php
                            $li_row =1;
                while ($metDataCN_ = mysql_fetch_array($metDataCN)) {
                    $metIDDataAwal = mysql_fetch_array(mysql_query('SELECT ajkpeserta.id,
																					   ajkpeserta.nomorktp,
																					   ajkpeserta.idpeserta,
																					   ajkpeserta.totalpremi,
																					   ajkdebitnote.id AS dID,
																					   ajkdebitnote.nomordebitnote
																				FROM ajkpeserta
																				INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																				WHERE ajkpeserta.nomorktp = "'.$metDataCN_['nomorktp'].'" AND
																					  ajkpeserta.statusaktif = "Inforce"'));
                    $nettoffPremium = $metDataCN_['totalpremi'] - $metIDDataAwal['totalpremi'];
                    echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td><center><input name="approve[]" id="'.$metDataCN_['id'].'" class="styled" type="checkbox" value="'.$metDataCN_['id'].'"></center></td>
										<td>'.$metDataCN_['produk'].'</td>
										<td>'.$metDataCN_['nopinjaman'].'</td>
										<td>'.$metDataCN_['idpeserta'].'</td>
										<td>'.$metDataCN_['nama'].'</td>
										<td>'._convertDate($metDataCN_['tgllahir']).'</td>
										<td>'.$metDataCN_['usia'].'</td>
										<td>'._convertDate($metDataCN_['tglakad']).'</td>
										<td>'.$metDataCN_['tenor'].'</td>
										<td>'._convertDate($metDataCN_['tglakhir']).'</td>
										<td class="text-right">'.duit($metDataCN_['plafond']).'</td>
										<td class="text-right">'.duit($metDataCN_['totalpremi']).'</td>
										<td>'.$metDataCN_['statusaktif'].'</td>
										<td>'._convertDate($metDataCN_['tglakad']).'</td>
										<td>'.$metDataCN_['namacabang'].'</td>
								    </tr>';
                    $li_row++;
                } ?>
						</tbody>
					</table>
					<div class="form-group m-b-0">
					<label class="control-label col-sm-12"></label>
					<div class="col-sm-6"><input name="sub" class="btn btn-success width-xs" value="Approved" type="submit"></div>
					</div>
		      </div>
        </form>
        <!-- end section-container -->
	    </div>
			<?php
            } elseif ($typedata == 'klaimKlaimVerifikasi') {
                ?>
			<div class="panel p-30">
				<h4 class="m-t-0">Verifikasi Data Klaim</h4>
				<div class="section-container section-with-top-border">
					<?php
                    $cabangpusat = mysql_fetch_array(mysql_query("SELECT * FROM ajkcabang WHERE er = '".$rowuser['branch']."' AND idclient = '".$idclient."'"));
                if ($cabangpusat['name']=="PUSAT") {
                    $authcabang ='';
                } else {
                    $authcabang ='ajkcreditnote.idcabang = "'.$cabang.'" AND';
                }
                $metDataCN = mysql_query('SELECT
				ajkcreditnote.id,
				ajkpolis.produk,
				ajkpeserta.idpeserta,
				ajkdebitnote.nomordebitnote,
				ajkcabang.`name` AS namacabang,
				ajkpeserta.nama,
				ajkpeserta.gender,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.plafond,
				ajkpeserta.premirate,
				ajkpeserta.totalpremi,
				ajkpeserta.statusaktif,
				ajkpeserta.statuspeserta,
				ajkpeserta.nopinjaman,
				ajkcreditnote.tglklaim,
				ajkcreditnote.status,
				ajkcreditnote.nilaiklaimdiajukan,
				ajkcreditnote.tempatmeninggal,
				ajkcreditnote.penyebabmeninggal,
				ajkcreditnote.tipeklaim
				FROM ajkcreditnote
				INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
				INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
				INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
				WHERE
				ajkcreditnote.idbroker = "'.$idbro.'" AND
				ajkcreditnote.idclient = "'.$idclient.'" AND
				'.$authcabang.'
				ajkcreditnote.del IS NULL AND
				ajkcreditnote.tipeklaim in ("Death","PAW","PHK","Kredit Macet") AND
				ajkcreditnote.status = "Proses Penyelia"
				ORDER BY ajkcreditnote.input_time DESC'); ?>
				<form action="dorequest.php?xq=appklaim" id="form-validasispk" class="form-horizontal" method="post" enctype="multipart/form-data">
				<table id="data-peserta" class="table table-bordered table-hover" width="100%">
					<thead>
					<tr class="primary">
					<th>No</th>
					<th><center><input onClick="toggle(this)" class="styled" type="checkbox" ></center>
					<th>No Pinjaman</th>
					<th>ID Peserta</th>
					<th>Nama</th>
					<th>Tgl.Lahir</th>
					<th>Umur</th>
					<th>Tgl.Akad</th>
					<th>Tenor</th>
					<th>Tgl.Akhir</th>
					<th>Plafond</th>
					<th>Premium</th>
					<th>Status</th>
					<th>Tipe Klaim</th>
					<th>Tanggal<br />Klaim</th>
					<th>Nilai Klaim<br />Diajukan</th>
					<th>Cabang</th>
					</tr>
					</thead>
					<tbody>
					<?php
                    $li_row =1;
                while ($metDataCN_ = mysql_fetch_array($metDataCN)) {
                    $qLokasinyanya = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metDataCN_['tempatmeninggal'].'" AND tipe="tempatmeninggal"'));
                    $qPenyakitnya = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metDataCN_['penyebabmeninggal'].'" AND tipe="penyebabmeninggal"'));
                    if ($metDataCN_['status']=="Request") {
                        $metStatusData = '<span class="label label-warning">'.$metDataCN_['status'].'</span>';
                    } else {
                        $metStatusData = '<span class="label label-info">'.$metDataCN_['status'].'</span>';
                    }
                    echo '<tr class="odd gradeX">
									<td>'.$li_row.'</td>
									<td><center><input name="approve[]" id="'.$metDataCN_['id'].'" class="styled" type="checkbox" value="'.$metDataCN_['id'].'"></center></td>
									<td><a href="dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metDataCN_['idpeserta'], ENCRYPTION_KEY).'">'.$metDataCN_['nopinjaman'].'</a></td>
									<td>'.$metDataCN_['idpeserta'].'</td>
									<!--<td><a href="dorequest.php?xq=vRegBatal&cnID='.AES::encrypt128CBC($metDataCN_['id'], ENCRYPTION_KEY).'">'.$metDataCN_['nama'].'</a></td>-->
									<td>'.$metDataCN_['nama'].'</td>
									<td>'._convertDate($metDataCN_['tgllahir']).'</td>
									<td>'.$metDataCN_['usia'].'</td>
									<td>'._convertDate($metDataCN_['tglakad']).'</td>
									<td>'.$metDataCN_['tenor'].'</td>
									<td>'._convertDate($metDataCN_['tglakhir']).'</td>
									<td class="text-right">'.duit($metDataCN_['plafond']).'</td>
									<td class="text-right">'.duit($metDataCN_['totalpremi']).'</td>
									<td>'.$metStatusData.'</td>									
									<td class="text-center"><span class="label label-info">'.$metDataCN_['tipeklaim'].'</span></td>
									<td>'._convertDate($metDataCN_['tglklaim']).'</td>
									<td>'.duit($metDataCN_['nilaiklaimdiajukan']).'</td>
									<td>'.$metDataCN_['namacabang'].'</td>
				        </tr>';
                    $li_row++;
                } ?>
				</tbody>
				</table>
				<div class="form-group m-b-0">
				<label class="control-label col-sm-12"></label>
				<div class="col-sm-6"><input name="sub" class="btn btn-success width-xs" value="Approved" type="submit"></div>
				</div>
					            </div>
					            </form>
					            <!-- end section-container -->
			</div>
			<?php
            } elseif ($typedata == 'PengajuanKlaimBatal') {
                if (!$_REQUEST['alasanbatal']) {
                    echo '<div class="col-md-12">
					<div class="alert alert-danger fade in m-b-12">
					<strong>Error!</strong>Silahkan isi keterangan pengajuan data klaim dibatalkan .<span class="close" data-dismiss="alert">&times;</span>
					</div>
					</div>';
                } else {
                    $metBatalKlaimPengajuan = mysql_query('UPDATE ajkcreditnote SET keterangan="'.$_REQUEST['alasanbatal'].'",
																				status="Cancel",
																				update_by="'.$iduser.'",
																		 		update_time="'.$mamettoday.'"
																		 WHERE id="'.$_REQUEST['idcnklaim'].'"');
                    $cekDokKlaim = mysql_fetch_array(mysql_query('SELECT id, idpeserta FROM ajkcreditnote WHERE id="'.$_REQUEST['idcnklaim'].'"'));
                    $metKlaimDokumen = mysql_query('UPDATE ajkdocumentclaimmember SET status="Cancel" WHERE idmember="'.$cekDokKlaim['idpeserta'].'"');
                    echo '<div class="col-md-12">
			    	    <div class="alert alert-success fade in m-b-12">
			        	<strong>Berhasil!</strong>Pengajuan data klaim telah dibatalkan.<span class="close" data-dismiss="alert">&times;</span>
				        </div>
			    	</div>
					<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimData', ENCRYPTION_KEY).'">';
                }
            } else {
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
                if ($typedata == 'klaimBatal') {
                    ?>

					$(".active").removeClass("active");
					document.getElementById("has_refund").classList.add("active");
					document.getElementById("idsub_klaimbatal").classList.add("active");
					$("#data-peserta").DataTable({
						responsive: true
					})

			<?php
                } elseif ($typedata == 'klaimRefund') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_refund").classList.add("active");
					document.getElementById("idsub_klaimrefund").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
                } elseif ($typedata == 'klaimTopup') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");
					document.getElementById("idsub_klaimtopup").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
                } elseif ($typedata == 'klaimKlaim') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");

					document.getElementById("idsub_<?php echo $jenisklaim; ?>").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
                } elseif ($typedata == 'klaimData') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");
					document.getElementById("idsub_klaimdata").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
                } elseif ($typedata == 'klaimBatalVerifikasi') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");
					document.getElementById("idsub_klaimbatalver").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
                } elseif ($typedata == 'klaimRefundVerifikasi') {
                    ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");
					document.getElementById("idsub_klaimrefundver").classList.add("active");
					$("#data-debitnote").DataTable({
						responsive: true
					})
			<?php
          }elseif($typedata == 'klaimKlaimVerifikasi'){
      ?>
					$(".active").removeClass("active");
					document.getElementById("has_klaim").classList.add("active");
					document.getElementById("idsub_klaimklaimver").classList.add("active");
					$("#data-debitnote").DataTable({
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

		$('#uploadmember').bootstrapValidator({
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
					validators: {
						notEmpty: {
							message: 'Silahkan pilih nama produk'
						}
					}
				},
				fileupload: {
					validators: {
						notEmpty: {
							message: 'Silahkan upload file deklarasi refund'
						}
					}
				},
				alasanbatal: {
					validators: {
						notEmpty: {
							message: 'Silahkan isi keterangan alasan pembatalan'
						}
					}
				}
			}
		});

		$("#data-peserta").DataTable({	responsive: true	});
		$("#data-peserta-batal").DataTable({	responsive: true	});
	</script>
</body>

</html>
