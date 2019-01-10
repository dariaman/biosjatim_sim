<?php
    include "../param.php";
    include_once('../includes/functions.php');

    if (isset($_REQUEST['startdate'])) {
        $startdate = $_REQUEST['startdate'];
        $startdate = substr($startdate, 6, 10).'-'.substr($startdate, 3, 2).'-'.substr($startdate, 0, 2);
    } else {
        header("location:../report");
    }
    if (isset($_REQUEST['enddate'])) {
        $enddate = $_REQUEST['enddate'];
        $enddate = substr($enddate, 6, 10).'-'.substr($enddate, 3, 2).'-'.substr($enddate, 0, 2);
    } else {
        header("location:../report");
    }

    $startdatep = isset($_REQUEST['startdatep']) ? date('Y-m-d', strtotime(strtr($_REQUEST['startdatep'], '/', '-'))) : '';
    $enddatep = isset($_REQUEST['enddatep']) ? date('Y-m-d', strtotime(strtr($_REQUEST['enddatep'], '/', '-'))) : '';



    //echo $_REQUEST['cabang'];
    //echo "test";

    if (isset($_REQUEST['cabang'])) {
        $scabang = $_REQUEST['cabang'];
    // $scabang = "Masuk";
    } else {
        $scabang = "";
    }

    if ($scabang == "") {
        $ls_cabang = 'ALL CABANG';
    } else {
        $qcabang = mysql_fetch_array(mysql_query("select * from ajkcabang where er = '".$scabang."'"));
        $ls_cabang = $qcabang['name'];
    }


    if ($scabang == "") {
        $cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idclient.'" AND er="'.$cabang.'"'));
        if ($cekCabang['level'] == 1) {
            $cabangverifikasi = '';
        } elseif ($cekCabang['level'] == 2) {
            $cabangverifikasi = " AND ajkpeserta.regional = '".$cekCabang['idreg']."'";
        } else {
            $cabangverifikasi = " AND ajkpeserta.cabang = '".$cabang."'";
        }
    } else {
        $cabangverifikasi = " AND ajkpeserta.cabang = '".$scabang."'";
    }

    if ($_REQUEST['namaproduk']=="") {
        $idprod = '%';
        $produk = 'ALL PRODUK';
        $prod_param = '';
    } else {
        $idprod = $_REQUEST['namaproduk'];
        $qproduk = mysql_fetch_array(mysql_query("select * from ajkpolis where id = '".$idprod."'"));
        //$produk = $idprod;
        $produk = $qproduk['produk'];
        $prod_param = $idprod;
    }

    if ($_REQUEST['asuransi']=="") {
        $idins = '%';
        $insurance = 'ALL ASURANSI';
        $ins_param = '';
    } else {
        $idins = $_REQUEST['asuransi'];
        $qins = mysql_fetch_array(mysql_query("select * from ajkinsurance where id = '".$idins."'"));
        
        $insurance = $qins['produk'];
        $ins_param = $insurance;
    }

    if (isset($_REQUEST['status'])) {
        $stat = $_REQUEST['status'];
    } else {
        $stat = "";
    }

    if ($stat=="") {
        $status = '%';
        $ls_status = 'ALL STATUS';
        $status_param = '';
    } else {
        $status = $_REQUEST['status'];
        $ls_status = $status;
        $status_param = $status;
    }

    $tgl_pengajuan = '';
    if (($_REQUEST['startdate']!='' && $_REQUEST['enddatep']!='')) {
        if ($typereport=='cnbatal' && $typereport=='cnrefund') {
            $tgl_pengajuan = ' AND ajkcreditnote.tglcreditnote BETWEEN "'.$startdatep.'" AND "'.$enddatep.'"';
        } elseif ($typereport=='cnklaim') {
            $tgl_pengajuan = ' AND ajkcreditnote.tglklaim BETWEEN "'.$startdatep.'" AND "'.$enddatep.'"';
        }
    }

    $_SESSION['lprcabang'] = $ls_cabang;
    $_SESSION['lprproduk'] = $produk;
    $_SESSION['lprasuransi'] = $nmins;
    $_SESSION['lprstatus'] = $ls_status;
    $_SESSION['lprperiode'] = _convertDate($startdate). ' s/d '._convertDate($enddate);
    $typereport = $_REQUEST['type'];
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
			<div class="panel p-30">
				<?php
    if ($typereport=="peserta") {
        ?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				  <h4 class="m-t-0">Laporan Peserta</h4>
					<a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=lprmember" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=lprmember" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
				  <?php
                    $li_row = 1;
                    $query = "SELECT
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
						ajkpeserta.statusaktif,
						ajkpeserta.nopinjaman,
						ajkinsurance.name as asuransi,
						ajkcabang.`name` AS cabang,
            case when ajkinsurance.discount > 0 then (ajkpeserta.totalpremi - (ajkpeserta.totalpremi*ajkinsurance.discount/100))*ajkinsurance.feebase/100 else ajkpeserta.totalpremi*ajkinsurance.feebase/100 end as resturno,
            			ajkpeserta.`tglbayaras`,ar.`tgl_bayar`

						FROM ajkpeserta
						LEFT JOIN ajkdebitnote  ON ajkdebitnote.id = ajkpeserta.iddn  
						INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
						INNER JOIN `ajkhisresturno` ar ON ar.`cabang`=ajkpeserta.`cabang` 
							AND ajkpeserta.`tglakad` BETWEEN STR_TO_DATE(CONCAT('01-',LEFT(ar.`periode`,7)), '%d-%m-%Y') 
							AND LAST_DAY(STR_TO_DATE(CONCAT('01-',RIGHT(ar.`periode`,7)), '%d-%m-%Y'))
						LEFT JOIN ajkinsurance oN ajkinsurance.id = ajkpeserta.asuransi
						WHERE ajkpeserta.id !='' AND ajkpeserta.idbroker= '".$idbro."'
						AND ajkpeserta.idclient='".$idclient."'
						AND ajkpeserta.idpolicy like '".$idprod."'
						AND ajkpeserta.statusaktif like '".$status."'
						AND ajkpeserta.asuransi like '".$idins."'
						".$cabangverifikasi."
						AND ajkpeserta.tglakad BETWEEN '".$startdate."' AND '".$enddate."'";
						
                    $_SESSION['querylprmember'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);
                    $qlpeserta = mysql_query($query); ?>

					<center><label class="control-label col-sm-12"><?php echo $produk ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>
					<label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate  ?></label></center>
				  <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
				    <table id="table-laporan" class="table table-bordered table-hover" width="100%">
				      <thead>
								<tr class="warning">
									<th>No</th>
									<th>No Pinjaman</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th class="text-center">Tgl.Lahir</th>
									<th class="text-center">Umur</th>
									<th class="text-center">Plafond</th>
									<th class="text-center">Tgl Akad</th>
									<th class="text-center">Tenor</th>
									<th class="text-center">Tgl Akhir</th>
									<th class="text-center">Total Premi</th>
                  					<th class="text-center">Resturno</th>
									<th class="text-center">Cabang</th>
									<th class="text-center">Asuransi</th>

									<th class="text-center">Tgl Bayar Premi</th>
									<th class="text-center">Tgl Bayar Resturno</th>
								</tr>
							</thead>
				  		<tbody>
							  <?php
                                while ($rtmp = mysql_fetch_array($qlpeserta)) {
                                    $nomordb = $rtmp['nomordebitnote'];
                                    $tgldn = $rtmp['tgldebitnote'];
                                    $tgldn = date('d-m-Y', strtotime($tgldn));
                                    $nomorktp = $rtmp['nomorktp'];
                                    $idpeserta = $rtmp['idpeserta'];
                                    $namapeserta = $rtmp['nama'];
                                    $tgllahir = $rtmp['tgllahir'];
                                    $tgllahir = date('d-m-Y', strtotime($tgllahir));
                                    $usia = $rtmp['usia'];
                                    $plafond = $rtmp['plafond'];
                                    $plafond_format = number_format($plafond, 0, '.', ',');
                                    $tglakad = $rtmp['tglakad'];
                                    $tglakad = date('d-m-Y', strtotime($tglakad));
                                    $tenor = $rtmp['tenor'];
                                    $tglakhir = $rtmp['tglakhir'];
                                    $tglakhir = date('d-m-Y', strtotime($tglakhir));
                                    $totalpremi = $rtmp['totalpremi'];
                                    $resturno = duit($rtmp['resturno']);
                                    $totalpremi_format = number_format($totalpremi, 0, '.', ',');
                                    $nmcab  = $rtmp['cabang'];
                                    $nopinjaman  = $rtmp['nopinjaman'];
                                    $asuransi  = $rtmp['asuransi'];

                                    echo '<tr class="odd gradeX">
							      <td>'.$li_row.'</td>
										<td>'.$nopinjaman.'</td>
										<td>'.$idpeserta.'</td>
										<td>'.$namapeserta.'</td>
										<td class="text-center">'.$tgllahir.'</td>
										<td class="text-center">'.$usia.'</td>
										<td class="text-right">'.$plafond_format.'</td>
										<td class="text-center">'.$tglakad.'</td>
										<td class="text-center">'.$tenor.'</td>
										<td class="text-right">'.$tglakhir.'</td>
            							<td class="text-right">'.$totalpremi_format.'</td>
            							<td class="text-right">'.$resturno.'</td>
										<td class="text-center">'.$nmcab.'</td>
										<td class="text-center">'.$asuransi.'</td>

								<td class="text-center">'.date('d-m-Y', strtotime($rtmp['tglbayaras'])).'</td>
								<td class="text-center">'.date('d-m-Y', strtotime($rtmp['tgl_bayar'])).'</td>
							   	</tr>';
                                    $li_row++;
                                } ?>
						  </tbody>
						</table>
					</form>
				</div>
	      <?php
    } elseif ($typereport=="debitnote") {
        ?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
					<h4 class="m-t-0">Laporan Nota Debit</h4>
					<a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=rptdebitnote" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=rptdebitnote" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<?php
                        $li_row = 1;
                    $query = "SELECT ajkdebitnote.id,
						ajkdebitnote.idbroker,
						ajkdebitnote.idclient,
						ajkdebitnote.idproduk,
						ajkdebitnote.idas,
						ajkdebitnote.idaspolis,
						ajkcabang.`name` AS cabang,
						ajkdebitnote.tgldebitnote,
						ajkdebitnote.nomordebitnote,
						ajkdebitnote.premiclient,
						ajkdebitnote.paidstatus,
						ajkdebitnote.paidtanggal,
						ajkdebitnote.premiasuransi,
						Count(ajkpeserta.nama) AS jmember
						FROM ajkdebitnote
						INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
						INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
						WHERE ajkdebitnote.del IS NULL
						AND ajkdebitnote.idbroker='".$idbro."'
						AND ajkdebitnote.idclient='".$idclient."'
						AND ajkdebitnote.idproduk like '".$idprod."'
						AND ajkdebitnote.paidstatus like '".$status."'
						".$cabangverifikasi."
						AND ajkdebitnote.tgldebitnote  BETWEEN '".$startdate."' AND '".$enddate."'
						GROUP BY ajkdebitnote.id";

                    $_SESSION['queryrptdebitnote'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);
                    $qdebitnote = mysql_query($query); ?>
					<center><label class="control-label col-sm-12"><?php echo $produk ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>
					<label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate  ?></label></center>
					<form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
						<table id="table-laporan" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="warning">
									<th>No</th>
									<th>Tgl.DN</th>
									<th>Nota Debit</th>
									<th>Peserta</th>
									<th>Premi</th>
									<th>Status</th>
									<th class="text-center">Tgl.Bayar</th>
									<th class="text-center">Cabang</th>
								</tr>
							</thead>
							<tbody>
								<?php
                    while ($rdn = mysql_fetch_array($qdebitnote)) {
                        $nomordn = $rdn['nomordebitnote'];
                        $tgldn = $rdn['tgldebitnote'];
                        $tgldn = date('d-m-Y', strtotime($tgldn));
                        $jumlahmember = $rdn['jmember'];
                        $jumlahmember_format = number_format($jumlahmember, 0, '.', ',');
                        $premiclient = $rdn['premiclient'];
                        $premiclient_format = number_format($premiclient, 0, '.', ',');
                        $status = $rdn['paidstatus'];
                        $tglpaid = $rdn['paidtanggal'];
                        $tglpaid_format = date('d-m-Y', strtotime($tglpaid));

                        if ($tglpaid=="1900-01-01" or $tglpaid=="0000-00-00") {
                            $tglpaid_format = "";
                        }

                        $nmcab = $rdn['cabang'];

                        if ($status=="Paid") {
                            $paidclass = 'label-success';
                        } else {
                            $paidclass = 'label-danger';
                        }

                        echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td>'.$tgldn.'</td>
										<td>'.$nomordn.'</td>
										<td class="text-center">'.$jumlahmember_format.'</td>
										<td class="text-right">'.$premiclient_format.'</td>
										<td class="text-center">
										<span class="label '.$paidclass.'">'.$status.'</span>
										</td>
										<td class="text-center">'.$tglpaid_format.'</td>
										<td class="text-center">'.$nmcab.'</td>
										</tr>';
                        $li_row++;
                    } ?>
							</tbody>
						</table>
					</form>
				</div>
				<?php
    } elseif ($typereport=="cnbatal") {
        ?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
					<h4 class="m-t-0">Laporan Batal</h4>
					<a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=rptcnbatal" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=rptcnbatal" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<center>
					    <label class="control-label col-sm-12"><?php echo $produk ?></label>
						<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
						<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>
						<?php if (!empty($_REQUEST['startdate'])) {
                        ?>
						 <label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate; ?></label>
						<?php
                    } else {
                        ?>
						 <label class="control-label col-sm-12"><?php echo $startdatep. ' s/d '.$enddatep; ?></label>
						<?php
                    } ?>
					</center>
					<form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
						<table id="table-laporan" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="warning">
									<th>No</th>
									<th>No Pinjaman</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Tgl.Lahir</th>
									<th>Usia</th>
									<th>Plafond</th>
									<th>Tgl.Akad</th>
									<th>Tenor</th>
									<th>Tgl.Akhir</th>
									<th>Cabang</th>
									<th>Asuransi</th>
									<th>Total Premi</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $li_row = 1;

                    $query = 'SELECT
										ajkpolis.produk,
										ajkpeserta.idpeserta,
										ajkpeserta.nomorspk,
										ajkpeserta.nama,
										ajkpeserta.tgllahir,
										ajkpeserta.usia,
										ajkpeserta.plafond,
										ajkpeserta.tglakad,
										ajkpeserta.tenor,
										ajkpeserta.nopinjaman,
										ajkpeserta.tglakhir,
										ajkpeserta.totalpremi,
										ajkcabang.`name` AS cabang,
										ajkcreditnote.nomorcreditnote,
										ajkcreditnote.tglcreditnote,
										ajkcreditnote.tglklaim,
										ajkcreditnote.nilaiclaimclient,
										ajkcreditnote.status AS statusklaim,
										ajkcreditnote.tipeklaim,
										ajkinsurance.name as asuransi
										FROM ajkcreditnote
										INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
										INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
										INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
										LEFT JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
										WHERE
										ajkcreditnote.tipeklaim = "Batal" AND
										ajkcreditnote.idbroker = "'.$idbro.'"
										'.$cabangverifikasi.'
										AND ajkcreditnote.idclient = "'.$idclient.'"
                    AND ajkpeserta.tglakad BETWEEN "'.$startdate.'" AND "'.$enddate.'"'
                    .$tgl_pengajuan;

                    $_SESSION['queryrptcnbatal'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);
                    $klaim_ = mysql_query($query);

                    while ($klaim__ = mysql_fetch_array($klaim_)) {
                        echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td>'.$klaim__['nopinjaman'].'</td>
										<td>'.$klaim__['idpeserta'].'</td>
										<td>'.$klaim__['nama'].'</td>
										<td>'._convertDate($klaim__['tgllahir']).'</td>
										<td>'.$klaim__['usia'].'</td>
										<td>'.$klaim__['plafond'].'</td>
										<td>'._convertDate($klaim__['tglakad']).'</td>
										<td>'.$klaim__['tenor'].'</td>
										<td>'._convertDate($klaim__['tglakhir']).'</td>
										<td>'.$klaim__['cabang'].'</td>
										<td>'.$klaim__['asuransi'].'</td>
										<td>'.$klaim__['totalpremi'].'</td>
										</tr>';

                        $li_row++;
                    } ?>
							</tbody>
						</table>
					</form>
				</div>
				<?php
    } elseif ($typereport=="cnrefund") {
        ?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
					<h4 class="m-t-0">Laporan Refund</h4>
					<a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=rptcnrefund" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=rptcnrefund" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<center><label class="control-label col-sm-12"><?php echo $produk ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>

					<?php if (!empty($_REQUEST['startdate'])) {
                        ?>
					 <label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate; ?></label>
					<?php
                    } else {
                        ?>
					 <label class="control-label col-sm-12"><?php echo $startdatep. ' s/d '.$enddatep; ?></label>
					<?php
                    } ?>

					</center>
					<form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
						<table id="table-laporan" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="warning">
									<th>No</th>
									<th>No Pinjaman</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Tgl.Lahir</th>
									<th>Usia</th>
									<th>Tgl.Akad</th>
									<th>Tenor</th>
									<th>Tgl.Akhir</th>
									<th>Plafond</th>
									<th>Cabang</th>
									<th>Asuransi</th>
									<th>Total Premi</th>
									<th>Tgl.Refund</th>
									<th>Nilai Refund</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $li_row = 1;

                    $query = 'SELECT
										ajkpolis.produk,
										ajkpeserta.idpeserta,
										ajkpeserta.nomorspk,
										ajkpeserta.nama,
										ajkpeserta.tgllahir,
										ajkpeserta.usia,
										ajkpeserta.plafond,
										ajkpeserta.tglakad,
										ajkpeserta.tenor,
										ajkpeserta.nopinjaman,
										ajkpeserta.tglakhir,
										ajkpeserta.totalpremi,
										ajkcabang.`name` AS cabang,
										ajkcreditnote.nomorcreditnote,
										ajkcreditnote.tglcreditnote,
										ajkcreditnote.tglklaim,
										ajkcreditnote.nilaiclaimclient,
										ajkcreditnote.status AS statusklaim,
										ajkcreditnote.tipeklaim,
										ajkinsurance.name as asuransi
										FROM ajkcreditnote
										INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
										INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
										INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
										LEFT JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
										WHERE	ajkcreditnote.tipeklaim = "Refund"
                    AND ajkcreditnote.idbroker = "'.$idbro.'"
										'.$cabangverifikasi.'
										AND ajkcreditnote.idclient = "'.$idclient.'"
                    AND ajkpeserta.tglakad BETWEEN "'.$startdate.'" AND "'.$enddate.'"'
                    .$tgl_pengajuan;

                    $_SESSION['queryrptcnrefund'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);

                    $klaim_ = mysql_query($query);

                    while ($klaim__ = mysql_fetch_array($klaim_)) {
                        echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td>'.$klaim__['nopinjaman'].'</td>
										<td>'.$klaim__['idpeserta'].'</td>
										<td>'.$klaim__['nama'].'</td>
										<td>'._convertDate($klaim__['tgllahir']).'</td>
										<td>'.$klaim__['usia'].'</td>
										<td>'._convertDate($klaim__['tglakad']).'</td>
										<td>'.$klaim__['tenor'].'</td>
										<td>'._convertDate($klaim__['tglakhir']).'</td>
										<td>'.$klaim__['plafond'].'</td>
										<td>'.$klaim__['cabang'].'</td>
										<td>'.$klaim__['asuransi'].'</td>
										<td>'.$klaim__['totalpremi'].'</td>
										<td>'._convertDate($klaim__['tglklaim']).'</td>
										<td>'.$klaim__['nilaiclaimclient'].'</td>
										</tr>';

                        $li_row++;
                    } ?>
							</tbody>
						</table>
					</form>
				</div>
				<?php
    } elseif ($typereport=="cnklaim") {
        ?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
					<h4 class="m-t-0">Laporan Klaim</h4>
					<!-- <a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=rptdebitnote&idb=<?php echo AES::encrypt128CBC($idbro, ENCRYPTION_KEY) ?>&idc=<?php echo AES::encrypt128CBC($idclient, ENCRYPTION_KEY) ?>&idp=<?php echo AES::encrypt128CBC($prod_param, ENCRYPTION_KEY) ?>&dtfrom=<?php echo AES::encrypt128CBC($startdate, ENCRYPTION_KEY) ?>&dtto=<?php echo AES::encrypt128CBC($enddate, ENCRYPTION_KEY) ?>&st=<?php echo AES::encrypt128CBC($status_param, ENCRYPTION_KEY) ?>" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=rptdebitnote&idb=<?php echo AES::encrypt128CBC($idbro, ENCRYPTION_KEY) ?>&idc=<?php echo AES::encrypt128CBC($idclient, ENCRYPTION_KEY) ?>&idp=<?php echo AES::encrypt128CBC($prod_param, ENCRYPTION_KEY) ?>&dtfrom=<?php echo AES::encrypt128CBC($startdate, ENCRYPTION_KEY) ?>&dtto=<?php echo AES::encrypt128CBC($enddate, ENCRYPTION_KEY) ?>&st=<?php echo AES::encrypt128CBC($status_param, ENCRYPTION_KEY) ?>" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a> -->
					<center><label class="control-label col-sm-12"><?php echo $produk ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>

					<?php if (!empty($_REQUEST['startdate'])) {
                        ?>
					 <label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate; ?></label>
					<?php
                    } else {
                        ?>
					 <label class="control-label col-sm-12"><?php echo $startdatep. ' s/d '.$enddatep; ?></label>
					<?php
                    } ?>

					</center>
					<form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
						<table id="table-laporan" class="table table-bordered table-hover" width="100%">
							<thead>
								<tr class="warning">
									<th>No</th>
									<th>Produk</th>
									<th>ID Peserta</th>
									<th>Nama</th>
									<th>Tgl. Lahir</th>
									<th>Usia</th>
									<th>Tgl.Akad</th>
									<th>Tenor</th>
									<th>Tgl.Akhir</th>
									<th>Plafond</th>
									<th>Total Premi</th>
									<th>Nota Kredit</th>
									<th>Nilai Nota Kredit</th>
									<th>Cabang</th>
									<th>Tgl.Refund</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $li_row = 1;

                    $query = 'SELECT ajkpolis.produk,
														ajkpeserta.idpeserta,
														ajkpeserta.nomorspk,
														ajkpeserta.nama,
														ajkpeserta.tgllahir,
														ajkpeserta.usia,
														ajkpeserta.plafond,
														ajkpeserta.tglakad,
														ajkpeserta.tenor,
														ajkpeserta.tglakhir,
														ajkpeserta.totalpremi,
														ajkcabang.`name` AS cabang,
														ajkcreditnote.nomorcreditnote,
														ajkcreditnote.tglcreditnote,
														ajkcreditnote.tglklaim,
														ajkcreditnote.nilaiclaimclient,
														ajkcreditnote.status AS statusklaim,
														ajkcreditnote.tipeklaim
														FROM ajkcreditnote
														INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
														INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
														INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
														WHERE ajkpeserta.statusaktif = "Claim"
					                  AND ajkcreditnote.idbroker = "'.$idbro.'"
					                  AND ajkcreditnote.idclient = "'.$idclient.'"
														'.$cabangverifikasi.'
					                  AND ajkpeserta.tglakad BETWEEN "'.$startdate.'" AND "'.$enddate.'"'.$tgl_pengajuan;

                    $_SESSION['queryrptklaim'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);
                    $klaim_ = mysql_query($query);

                    while ($klaim__ = mysql_fetch_array($klaim_)) {
                        echo '<tr class="odd gradeX">
										<td>'.$li_row.'</td>
										<td>'.$klaim__['produk'].'</td>
										<td>'.$klaim__['idpeserta'].'</td>
										<td>'.$klaim__['nama'].'</td>
										<td>'._convertDate($klaim__['tgllahir']).'</td>
										<td>'.$klaim__['usia'].'</td>
										<td>'._convertDate($klaim__['tglakad']).'</td>
										<td>'.$klaim__['tenor'].'</td>
										<td>'._convertDate($klaim__['tglakhir']).'</td>
										<td>'.$klaim__['plafond'].'</td>
										<td>'.$klaim__['totalpremi'].'</td>
										<td>'.$klaim__['nomorcreditnote'].'</td>
										<td>'.$klaim__['nilaiclaimclient'].'</td>
										<td>'.$klaim__['cabang'].'</td>
										<td>'._convertDate($klaim__['tglklaim']).'</td>
										<td>'.$klaim__['statusklaim'].'</td>
										</tr>';

                        $li_row++;
                    } ?>
							</tbody>
						</table>
					</form>
				</div>
				<?php
    } elseif ($typereport=="lapmemins"){
    	?>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				  <h4 class="m-t-0">Laporan Peserta Insurance</h4>
					<a title="Download Excel"  href="../modules/modEXLdl_front.php?Rxls=lapmemins" target='_blank'">
						<span class="fa-stack fa-2x text-success">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<!-- <a title="Download PDF"  href="../modules/modPdfdl_front.php?pdf=lapmemins" target="_blank"">
						<span class="fa-stack fa-2x text-danger">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-file-pdf-o fa-stack-1x fa-inverse"></i>
						</span>
					</a> -->
				  <?php
            $li_row = 1;
            $query1 = "SELECT
						ajkdebitnote.nomordebitnote,
						ajkdebitnote.tgldebitnote,
						ajkpeserta.idpeserta,
						ajkpeserta.nomorktp,
						ajkpeserta.nama,
						ajkpeserta.tgllahir,
						ajkpeserta.usia,
						ajkpeserta.plafond,
						ajkpeserta.pekerjaan,
						ajkpeserta.tglakad,
						ajkpeserta.tenor,
						ajkpeserta.tglakhir,
						ajkpeserta.totalpremi,
						ajkpeserta.statusaktif,
						ajkpeserta.nopinjaman,
						ajkprofesi.nm_profesi,
						ajkinsurance.name as asuransi,
						ajkcabang.`name` AS cabang
						FROM ajkpeserta
						LEFT JOIN ajkdebitnote  ON ajkdebitnote.id = ajkpeserta.iddn  
						INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
						LEFT JOIN ajkinsurance oN ajkinsurance.id = ajkpeserta.asuransi
						LEFT JOIN ajkprofesi ON ajkprofesi.ref_mapping = ajkpeserta.Pekerjaan
						WHERE ajkpeserta.id !='' AND ajkpeserta.idbroker= '".$idbro."'
						AND ajkpeserta.idclient='".$idclient."'
						AND ajkpeserta.idpolicy like '".$idprod."'
						AND ajkpeserta.statusaktif like '".$status."'
						AND ajkpeserta.asuransi = '".$idas."'
						".$cabangverifikasi."
						AND ajkpeserta.tglakad BETWEEN '".$startdate."' AND '".$enddate."' 
						AND ajkpeserta.statusaktif = 'Inforce' 
						AND ajkpeserta.checker_by is not null";
						// echo $query;
						$query = "
						SELECT idpeserta,
										nmcabang,
										nama,
										nm_profesi,
										nm_kategori_profesi,
										tgllahir,
										usia,
										produk,
										plafond,
										tenor,
										tglakad,
										tglakhir,
										premirate,
										premi,
										0 as feebase,
										0 as netpremi,
										'New' as tipe_pinjaman
						FROM vpeserta
						WHERE idbroker= '".$idbro."' AND 
						idclient='".$idclient."' AND
						statusaktif like '".$status."' AND 
						asuransi = '".$idas."'
						AND tglakad BETWEEN '".$startdate."' AND '".$enddate."' 
						AND statusaktif = 'Inforce' 
						AND checker_by is not null";



            $_SESSION['querylapmemins'] = AES::encrypt128CBC($query, ENCRYPTION_KEY);
            $qlpeserta = mysql_query($query); 
          ?>

					<center><label class="control-label col-sm-12"><?php echo $produk ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_status ?></label>
					<label class="control-label col-sm-12"><?php echo $ls_cabang ?></label>
					<label class="control-label col-sm-12"><?php echo $startdate. ' s/d '.$enddate  ?></label></center>
				  <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
				    <table id="table-laporan" class="table table-bordered table-hover" width="100%">
				      <thead>
								<tr class="warning">
									<th>No</th>
									<th>ID Peserta</th>
									<th class="text-center">Cabang</th>
									<th class="text-center">Nama Debitur</th>
									<th class="text-center">Instansi</th>									
									<th class="text-center">Tgl.Lahir</th>
									<th class="text-center">Usia</th>
									<th class="text-center">Penggunaan Kredit</th>
									<th class="text-center">Pokok Kredit</th>
									<th class="text-center">Jk. Waktu</th>
									<th class="text-center">Tgl Realisasi</th>
									<th class="text-center">Tgl Jatuh Tempo</th>
									<th class="text-center">Premi</th>
									<!-- <th class="text-center">IJP</th>
									<th class="text-center">Pendapatan Bank</th>
									<th class="text-center">IJP Netto</th> -->
									<th class="text-center">Tipe Pinjaman</th>									
								</tr>
							</thead>
				  		<tbody>
							  <?php
	                while ($rtmp = mysql_fetch_array($qlpeserta)) {
	                    $nomordb = $rtmp['nomordebitnote'];
	                    $tgldn = $rtmp['tgldebitnote'];
	                    $tgldn = date('d-m-Y', strtotime($tgldn));
	                    $nomorktp = $rtmp['nomorktp'];
	                    $idpeserta = $rtmp['idpeserta'];
	                    $namapeserta = $rtmp['nama'];
	                    $tgllahir = date('d-m-Y', strtotime($rtmp['tgllahir']));
	                    $usia = $rtmp['usia'];
	                    $plafond = $rtmp['plafond'];
	                    $tglakad = date('d-m-Y', strtotime($rtmp['tglakad']));
	                    $tenor = $rtmp['tenor'];
	                    $tglakhir = date('d-m-Y', strtotime($rtmp['tglakhir']));
	                    $totalpremi = $rtmp['totalpremi'];
	                    $nmcab  = $rtmp['nmcabang'];
	                    $nopinjaman  = $rtmp['nopinjaman'];
	                    $asuransi  = $rtmp['asuransi'];
	                    $Pekerjaan = $rtmp['nm_profesi'];
	                    $produk = $rtmp['produk'];
	                    $tipe_pinjaman = $rtmp['tipe_pinjaman'];	                    
	                    $premi = $rtmp['premi'];

	                    echo '<tr class="odd gradeX">
											      <td>'.$li_row.'</td>
														<td>'.$idpeserta.'</td>
														<td>'.$nmcab.'</td>
														<td>'.$namapeserta.'</td>
														<td class="text-center">'.$Pekerjaan.'</td>
														<td class="text-center">'.$tgllahir.'</td>
														<td class="text-center">'.$usia.'</td>
														<td class="text-center">'.$produk.'</td>														
														<td class="text-right">'.duit($plafond).'</td>
														<td class="text-center">'.$tenor.'</td>
														<td class="text-center">'.$tglakad.'</td>
														<td class="text-right">'.$tglakhir.'</td>
														<td class="text-right">'.duit($premi).'</td>
														<td class="text-center">'.$tipe_pinjaman.'</td>
											   	</tr>';
                      $li_row++;
                  } 
                ?>
						  </tbody>
						</table>
					</form>
				</div>    	
    	<?php    	
    }
        ?>
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
			document.getElementById("has_laporan").classList.add("active");
			document.getElementById("sub_laporan").classList.add("active");

			<?php
            if ($typereport=="peserta") {
                echo 'document.getElementById("idsub_lappeserta").classList.add("active");';
            } elseif ($typereport=="cnbatal") {
                echo 'document.getElementById("idsub_lapcreditnotebatal").classList.add("active");';
            } elseif ($typereport=="cnrefund") {
                echo 'document.getElementById("idsub_lapcreditnoterefund").classList.add("active");';
            } elseif ($typereport=="cnklaim") {
                echo 'document.getElementById("idsub_lapcreditnoteklaim").classList.add("active");';
						} elseif ($typereport=="lapmemins") {
                echo 'document.getElementById("idsub_lappeserta").classList.add("active");';                
            } else {
                echo 'document.getElementById("idsub_lapdebitnote").classList.add("active");';
            }
            ?>

			$("#table-laporan").DataTable({
				responsive: true
			})
		});
	</script>
</body>

</html>
