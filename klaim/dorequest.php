<?php
include "../param.php";
include_once('../includes/functions.php');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
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
<?php
    _head($user, $namauser, $photo, $logo);


    echo '<body>
		<!-- begin #page-loader -->
		<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
		<!-- end #page-loader -->

		<!-- begin #page-container -->
		<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">';

    _header($user, $namauser, $photo, $logo, $logoklient);
    _sidebar($user, $namauser, '', '');
    if (isset($_REQUEST['qr'])) {
        $casetype = $_REQUEST['qr'];
        $casetype = AES::decrypt128CBC($casetype, ENCRYPTION_KEY);
    } else {
        header("location:../dashboard");
    }
?>
<?php
echo '<div id="content" class="content">';
switch ($_REQUEST['xq']) {
    case "batal":
        $metBatal = mysql_fetch_array(mysql_query('SELECT
				ajkpeserta.id,
				ajkpeserta.idbroker,
				ajkpeserta.idclient,
				ajkpeserta.idpolicy,
				ajkpeserta.iddn,
				ajkpeserta.regional,
				ajkpeserta.cabang,
				ajkpeserta.idpeserta,
				ajkpeserta.nama,
				ajkpolis.produk,
				ajkpolis.jumlahharibatal,
				ajkpeserta.nomorktp,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.plafond,
				ajkpeserta.totalpremi,
				ajkpeserta.astotalpremi,
				ajkpeserta.statusaktif,
				ajkpeserta.statuspeserta,
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.tgldebitnote,
				ajkpeserta.asuransi,
				ajkdebitnote.idaspolis,
				ajkcabang.`name` AS namacabang
				FROM
				ajkpeserta
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
				WHERE
				ajkpeserta.id="'.AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY).'"'));

        if ($_REQUEST['xqr']=="regbatal") {
            echo '<div class="panel panel-warning">
							<div class="panel-heading">
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								</div>
								<h4 class="m-t-0">Pengajuan Data Batal</h4>
							</div>
							<div class="panel-body">
								<div class="m-t-5">
									<dl class="dl-horizontal">
									<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
									<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
									<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
									<dt>Tanggal Akad :</dt><dd> <strong>'._convertDate($metBatal['tglakad']).'</strong></dd>
									<dt>Tanggal Batal :</dt><dd> <strong>'._convertDate(_convertDate2($_REQUEST['tglbatal'])).'</strong></dd>
									<dt>Alasan Batal :</dt><dd> <strong>'.$_REQUEST['alasanbatal'].'</strong></dd>
								</div>';
            $cekdataCN = mysql_fetch_array(mysql_query('SELECT ajkcreditnote.idpeserta, useraccess.firstname AS namanya, DATE_FORMAT(ajkcreditnote.input_time,"%Y-%m-%d") AS tglinput
															FROM ajkcreditnote
															INNER JOIN useraccess ON ajkcreditnote.input_by = useraccess.id
															WHERE ajkcreditnote.idpeserta="'.$metBatal['id'].'"'));
            if ($cekdataCN['idpeserta']) {
                echo '<div class="alert alert-warning fade in m-b-10"><h4><strong>Ouuchh..!</strong> Data sudah pernah di input oleh '.$cekdataCN['namanya'].' pada tanggal '._convertDate($cekdataCN['tglinput']).'</h4></div>';
            } else {
                $metReqBatal = mysql_query('UPDATE ajkpeserta SET statuspeserta="Req_Batal" WHERE id="'.AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY).'"');
                $metCNBatal = mysql_query('INSERT INTO ajkcreditnote SET idbroker="'.$metBatal['idbroker'].'",
																	 idclient="'.$metBatal['idclient'].'",
																	 idproduk="'.$metBatal['idpolicy'].'",
																	 idas="'.$metBatal['asuransi'].'",
																	 idaspolis="'.$metBatal['idaspolis'].'",
																	 idpeserta="'.$metBatal['id'].'",
																	 idregional="'.$metBatal['regional'].'",
																	 idcabang="'.$metBatal['cabang'].'",
																	 iddn="'.$metBatal['iddn'].'",
																	 tglklaim="'._convertDate2($_REQUEST['tglbatal']).'",
																	 nilaiclaimclient="'.$metBatal['totalpremi'].'",
																	 nilaiclaimasuransi="'.$metBatal['astotalpremi'].'",
																	 status="Request",
																	 tipeklaim="Batal",
																	 keterangan="'.$_REQUEST['alasanbatal'].'",
																	 input_by="'.$iduser.'",
																	 input_time="'.$mamettoday.'"');
                echo '<div class="alert alert-warning fade in m-b-10"><h4><strong>Selesai!</strong> Data pengajuan batal telah dibuat. Menunggu konfirmasi oleh supervisor untuk proses approval.</h4></div>
			        	</div>';
            }
            echo '</div>';
        } else {
            echo '<form action="#" id="inputklaim" class="form-horizontal" method="post" enctype="multipart/form-data">
					  <div class="panel panel-warning">
						<div class="panel-heading">
				        	<div class="panel-heading-btn">
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				            </div>
				            <h4 class="m-t-0">Pengajuan Data Batal</h4>
				        </div>
				        <div class="panel-body">
				        <div class="m-t-5">
							<dl class="dl-horizontal">
							<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
							<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
							<h4 class="text-left">DATA DEBITUR</h4>
							<dt>K.T.P :</dt><dd> '.$metBatal['nomorktp'].'</dd>
							<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
							<dt>Tanggal Lahir :</dt><dd> '._convertDate($metBatal['tgllahir']).'</dd>
							<dt>Usia :</dt><dd> '.$metBatal['usia'].' tahun</dd>
							<dt>Tanggal Akad :</dt><dd> '._convertDate($metBatal['tglakad']).'</dd>
							<dt>Tenor :</dt><dd> '.$metBatal['tenor'].' bulan</dd>
							<dt>Tanggal Akhir :</dt><dd> '._convertDate($metBatal['tglakhir']).'</dd>
							<dt>Plafond :</dt><dd> '.duit($metBatal['plafond']).'</dd>
							<dt>Nett Premi :</dt><dd> <strong>'.duit($metBatal['totalpremi']).'</strong></dd>
							<h4 class="text-left">DATA BATAL</h4>
							<dt><label class="control-label"><strong>Tanggal Batal <span class="text-danger">*</span></label> :</strong></dt><dd>
										<div class="form-group">
					                <div class="col-sm-12"><input name="tglbatal" id="tglbatal" class="form-control" placeholder="Silahkan Input Tanggal Batal" type="text"></div>
					                </div>
								  </dd>
							<dt><label class="control-label"><strong>Alasan Batal <span class="text-danger">*</span></label> :</strong></dt><dd>
										<div class="form-group">
					                <div class="col-sm-12"><textarea class="form-control" rows="3" placeholder="Alasan Batal" name="alasanbatal" id="alasanbatal">'.$_REQUEST['alasanbatal'].'</textarea>
					                </div>
								  </dd>
							</dl>
				        </div>

				        </div>
				        <div class="panel-footer text-center">
				        <a href="../klaim?type='.AES::encrypt128CBC('klaimBatal', ENCRYPTION_KEY).'"><button type="button" class="btn btn-info m-b-5">Cancel</button></a> &nbsp;
				        <input type="hidden" name="xqr" value="regbatal"><button type="submit" class="btn btn-danger m-b-5">Proses Batal</button>
				        </div>
				    </div>
				    </form>';
        }
        ;
    break;

    case "refund":
        $metBatal = mysql_fetch_array(mysql_query('SELECT
				ajkpeserta.id,
				ajkpeserta.idbroker,
				ajkpeserta.idclient,
				ajkpeserta.idpolicy,
				ajkpeserta.iddn,
				ajkpeserta.regional,
				ajkpeserta.cabang,
				ajkpeserta.idpeserta,
				ajkpeserta.nama,
				ajkpolis.produk,
				ajkpolis.jumlahharibatal,
				ajkpeserta.nomorktp,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.plafond,
				ajkpeserta.totalpremi,
				ajkpeserta.astotalpremi,
				ajkpeserta.statusaktif,
				ajkpeserta.statuspeserta,
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.tgldebitnote,
				ajkpeserta.asuransi,
				ajkdebitnote.idaspolis,
				ajkcabang.`name` AS namacabang
				FROM
				ajkpeserta
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
				WHERE
				ajkpeserta.id="'.AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY).'"'));

        if ($_REQUEST['xqr']=="regrefund") {
            echo '<div class="panel panel-warning">
							<div class="panel-heading">
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								</div>
								<h4 class="m-t-0">Pengajuan Data Refund</h4>
							</div>
							<div class="panel-body">
								<div class="m-t-5">
									<dl class="dl-horizontal">
									<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
									<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
									<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
									<dt>Tanggal Akad :</dt><dd> <strong>'._convertDate($metBatal['tglakad']).'</strong></dd>
									<dt>Tanggal Refund :</dt><dd> <strong>'._convertDate(_convertDate2($_REQUEST['tglbatal'])).'</strong></dd>
									<!--<dt>Alasan Refund :</dt><dd> <strong>'.$_REQUEST['alasanbatal'].'</strong></dd>-->
								</div>';
            $cekdataCN = mysql_fetch_array(mysql_query('SELECT ajkcreditnote.idpeserta, useraccess.firstname AS namanya, DATE_FORMAT(ajkcreditnote.input_time,"%Y-%m-%d") AS tglinput
															FROM ajkcreditnote
															INNER JOIN useraccess ON ajkcreditnote.input_by = useraccess.id
															WHERE ajkcreditnote.idpeserta="'.$metBatal['id'].'"'));
            if ($cekdataCN['idpeserta']) {
                echo '<div class="alert alert-warning fade in m-b-10"><h4><strong>Ouuchh..!</strong> Data sudah pernah di input oleh '.$cekdataCN['namanya'].' pada tanggal '._convertDate($cekdataCN['tglinput']).'</h4></div>';
            } else {
            		$hanpolis = mysql_fetch_array(mysql_query('SELECT * FROM ajkpolis WHERE id = "'.$metBatal['idpolicy'].'"'));
            		$refund_persen = $hanpolis['refundpersentage'];
            		$nilai_refund_premi = (($metBatal['tenor']-datediffmonth($metBatal['tglakad'],_convertDate2($_REQUEST['tglbatal'])))/$metBatal['tenor'])*$metBatal['totalpremi'];
								$nilai_refund_premias = (($metBatal['tenor']-datediffmonth($metBatal['tglakad'],_convertDate2($_REQUEST['tglbatal'])))/$metBatal['tenor'])*$metBatal['astotalpremi'];

                $metReqBatal = mysql_query('UPDATE ajkpeserta SET statuspeserta="Req_Refund" WHERE id="'.AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY).'"');
                $metCNBatal = mysql_query('INSERT INTO ajkcreditnote SET idbroker="'.$metBatal['idbroker'].'",
																	 idclient="'.$metBatal['idclient'].'",
																	 idproduk="'.$metBatal['idpolicy'].'",
																	 idas="'.$metBatal['asuransi'].'",
																	 idaspolis="'.$metBatal['idaspolis'].'",
																	 idpeserta="'.$metBatal['id'].'",
																	 idregional="'.$metBatal['regional'].'",
																	 idcabang="'.$metBatal['cabang'].'",
																	 iddn="'.$metBatal['iddn'].'",
																	 tglklaim="'._convertDate2($_REQUEST['tglbatal']).'",
																	 nilaiclaimclient="'.round($nilai_refund_premi,2).'",
																	 nilaiclaimasuransi="'.round($nilai_refund_premias,2).'",
																	 status="Request",
																	 tipeklaim="Refund",
																	 keterangan="'.$_REQUEST['alasanbatal'].'",
																	 input_by="'.$iduser.'",
																	 input_time="'.$mamettoday.'"');

                echo '<div class="alert alert-warning fade in m-b-10"><h4><strong>Selesai!</strong> Data pengajuan refund telah dibuat. Menunggu konfirmasi oleh supervisor untuk proses approval.</h4></div>
			        	</div>';

            }
            echo '</div>';
        } else {
            echo '<form action="#" id="inputklaim" class="form-horizontal" method="post" enctype="multipart/form-data">
					  <div class="panel panel-warning">
						<div class="panel-heading">
				        	<div class="panel-heading-btn">
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
				            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				            </div>
				            <h4 class="m-t-0">Pengajuan Data Refund</h4>
				        </div>
				        <div class="panel-body">
				        <div class="m-t-5">
							<dl class="dl-horizontal">
							<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
							<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
							<h4 class="text-left">DATA DEBITUR</h4>
							<dt>K.T.P :</dt><dd> '.$metBatal['nomorktp'].'</dd>
							<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
							<dt>Tanggal Lahir :</dt><dd> '._convertDate($metBatal['tgllahir']).'</dd>
							<dt>Usia :</dt><dd> '.$metBatal['usia'].' tahun</dd>
							<dt>Tanggal Akad :</dt><dd> '._convertDate($metBatal['tglakad']).'</dd>
							<dt>Tenor :</dt><dd> '.$metBatal['tenor'].' bulan</dd>
							<dt>Tanggal Akhir :</dt><dd> '._convertDate($metBatal['tglakhir']).'</dd>
							<dt>Plafond :</dt><dd> '.duit($metBatal['plafond']).'</dd>
							<dt>Nett Premi :</dt><dd> <strong>'.duit($metBatal['totalpremi']).'</strong></dd>
							<h4 class="text-left">DATA REFUND</h4>
							<dt><label class="control-label"><strong>Tanggal Refund <span class="text-danger">*</span></label> :</strong></dt><dd>
										<div class="form-group">
					                <div class="col-sm-12"><input name="tglbatal" id="tglbatal" class="form-control" placeholder="Silahkan Input Tanggal Refund" type="text"></div>
					                </div>
								  </dd>
							<!--<dt>
							<label class="control-label">
							<strong>Alasan Refund <span class="text-danger">*</span></label> :</strong>
							</dt>
							<dd>
								<div class="form-group">
									<div class="col-sm-12"><textarea class="form-control" rows="3" placeholder="Alasan Refund" name="alasanbatal" id="alasanbatal">'.$_REQUEST['alasanbatal'].'</textarea>
								</div>
							</dd>-->
							</dl>
				        </div>

				        </div>
				        <div class="panel-footer text-center">
				        <a href="../klaim?type='.AES::encrypt128CBC('klaimRefund', ENCRYPTION_KEY).'"><button type="button" class="btn btn-info m-b-5">Cancel</button></a> &nbsp;
				        <input type="hidden" name="xqr" value="regrefund"><button type="submit" class="btn btn-danger m-b-5">Proses Refund</button>
				        </div>
				    </div>
				    </form>';
        }
        ;
    break;

    case "vRegBatal":
        $metBatal = mysql_fetch_array(mysql_query('SELECT
				ajkcreditnote.id,
				ajkpolis.produk,
				ajkdebitnote.nomordebitnote,
				ajkcabang.`name` AS namacabang,
				ajkpeserta.idbroker,
				ajkpeserta.idclient,
				ajkpeserta.idpolicy,
				ajkpeserta.iddn,
				ajkpeserta.regional,
				ajkpeserta.cabang,
				ajkpeserta.idpeserta,
				ajkpeserta.nama,
				ajkpolis.produk,
				ajkpolis.jumlahharibatal,
				ajkpeserta.nomorktp,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.plafond,
				ajkpeserta.totalpremi,
				ajkpeserta.astotalpremi,
				ajkpeserta.statusaktif,
				ajkpeserta.statuspeserta,
				ajkcreditnote.tglklaim,
				ajkcreditnote.keterangan,
				ajkcreditnote.`status`,
				ajkcreditnote.tipeklaim
				FROM ajkcreditnote
				INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
				INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
				INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
				WHERE
				ajkcreditnote.id = "'.AES::decrypt128CBC($_REQUEST['cnID'], ENCRYPTION_KEY).'"'));
        echo '<div class="panel panel-default">
				<div class="panel-heading">
		       		<div class="panel-heading-btn">
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		           	</div>
		           	<h4 class="m-t-0">Pengajuan Verifikasi Data Batal</h4>
		       	</div>
		       	<div class="panel-body">
		       		<div class="m-t-5">
						<dl class="dl-horizontal">
						<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
						<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
						<h4 class="text-left">DATA DEBITUR</h4>
						<dt>K.T.P :</dt><dd> '.$metBatal['nomorktp'].'</dd>
						<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
						<dt>Tanggal Lahir :</dt><dd> '._convertDate($metBatal['tgllahir']).'</dd>
						<dt>Usia :</dt><dd> '.$metBatal['usia'].' tahun</dd>
						<dt>Tanggal Akad :</dt><dd> '._convertDate($metBatal['tglakad']).'</dd>
						<dt>Tenor :</dt><dd> '.$metBatal['tenor'].' bulan</dd>
						<dt>Tanggal Akhir :</dt><dd> '._convertDate($metBatal['tglakhir']).'</dd>
						<dt>Plafond :</dt><dd> '.duit($metBatal['plafond']).'</dd>
						<dt>Nett Premi :</dt><dd> <strong>'.duit($metBatal['totalpremi']).'</strong></dd>
						<h4 class="text-left">DATA PEMBATALAN</h4>
						<dt>Tanggal Pembatalan :</dt><dd> <strong>'._convertDate($metBatal['tglklaim']).'</strong></dd>
						<dt>Alasan pembatalan :</dt><dd> <strong>'.$metBatal['keterangan'].'</strong></dd>
		        	</div>
		        </div>';
        echo '</div>';
            ;
    break;

    case "appbatal":
        if (!$_REQUEST['approve']) {
            echo '<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimBatalVerifikasi', ENCRYPTION_KEY).'">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="alert alert-danger fade in m-b-10"><h4><strong> Uppss...!</strong><br /> Silahkan ceklist untuk persetujuan data batal debitur.</h4></div>
							</div>
						</div>';
        } else {
            foreach ($_REQUEST['approve'] as $k => $met) {
                $metBatal_ = mysql_fetch_array(mysql_query('SELECT id, idpeserta FROM ajkcreditnote WHERE id="'.$met.'"'));
                $metDebitur = mysql_query('UPDATE ajkpeserta SET statuspeserta="App_Batal" WHERE id="'.$metBatal_['idpeserta'].'"');
                $metDebiturCN = mysql_query('UPDATE ajkcreditnote SET status="Approve", approve_by="'.$iduser.'", approve_time="'.$mamettoday.'" WHERE id="'.$metBatal_['id'].'"');
            }
            echo '<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								</div>
								<h4 class="m-t-0">Pengajuan Verifikasi Data Batal</h4>
							</div>
							<div class="panel-body">
								<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data batal telah disetujui oleh '.$namauser.'.</strong><br /> untuk proses selanjutnya menunggu konfirmasi untuk membuat data creditnote oleh bagian Admin klaim.</h4></div>
							</div>
						</div>
						<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimBatalVerifikasi', ENCRYPTION_KEY).'">';
        }
            ;
    break;

    case "apprefund":
        if (!$_REQUEST['approve']) {
            echo '<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY).'">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="alert alert-danger fade in m-b-10"><h4><strong> Uppss...!</strong><br /> Silahkan ceklist untuk persetujuan data batal debitur.</h4></div>
							</div>
						</div>';
        } else {
            foreach ($_REQUEST['approve'] as $k => $met) {
                $metBatal_ = mysql_fetch_array(mysql_query('SELECT id, idpeserta FROM ajkcreditnote WHERE id="'.$met.'"'));
                $metPeserta_ = mysql_fetch_array(mysql_query('SELECT * FROM ajkpeserta WHERE id="'.$metBatal_['idpeserta'].'"'));
                $metDebitur = mysql_query('UPDATE ajkpeserta SET statuspeserta="App_Refund" WHERE id="'.$metBatal_['idpeserta'].'"');
                $metDebiturCN = mysql_query('UPDATE ajkcreditnote SET status="Approve", approve_by="'.$iduser.'", approve_time="'.$mamettoday.'" WHERE id="'.$metBatal_['id'].'"');

 								//EMAIL NOTIF 									
	 								$RecipientsFrom = array('notifikasi@jatim.adonai.co.id'=>'Simulasi Jatim');
	 								$to = "Adonai";
	                $RecipientsTo = array('ajk@adonai.co.id'=>'Adonai');
	                $RecipientsCC = array('chrismanuel@adonaits.co.id'=>'Chris');
	                $RecipientsBCC = array('hansen@adonai.co.id'=>'Hansen');
	                $subject = 'Pengajuan Refund AN '.$metPeserta_['nama'].' ['.$metPeserta_['idpeserta'].']';
	                $body = '<p>Pengajuan Refund AN '.$metPeserta_['nama'].' ['.$metPeserta_['idpeserta'].'] telah di approve oleh Penyelia, mohon segera di proses.</p>';

	                kirimemail($RecipientsFrom, $to, $RecipientsTo,'', $RecipientsBCC, $subject, $body,'','');
                //EMAIL NOTIF END
            }
               

            echo '<div class="panel panel-default">
							<div class="panel-heading">
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								</div>
								<h4 class="m-t-0">Pengajuan Verifikasi Data Refund</h4>
							</div>
							<div class="panel-body">
								<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data refund telah disetujui oleh '.$namauser.'.</strong><br /> untuk proses selanjutnya menunggu konfirmasi untuk membuat data creditnote oleh bagian Admin klaim.</h4></div>
							</div>
						</div>
						<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY).'">';
        }
            ;
    break;

    case "apprefundbak":
        if (!$_REQUEST['approve']) {
            if ($_REQUEST['typerefund']=="topup") {
                $setBackRT = '<meta http-equiv="refresh" content="3; url=../klaim?qtype='.$_REQUEST['typerefund'].'&type='.AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY).'">';
            } else {
                $setBackRT = '<meta http-equiv="refresh" content="3; url=../klaim?qtype='.$_REQUEST['typerefund'].'&type='.AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY).'">';
            }
            echo $setBackRT.'
				<div class="panel panel-default"><div class="panel-body">
		    		<div class="alert alert-danger fade in m-b-10"><h4><strong> Uppss...!</strong><br /> Silahkan ceklist untuk persetujuan data '.$_REQUEST['typerefund'].' debitur.</h4></div>
		    	</div>';
        } else {
            foreach ($_REQUEST['approve'] as $key) {
                $metRefund = mysql_fetch_array(mysql_query('SELECT * FROM ajkpeserta_temp WHERE id="'.$key.'"'));
                $metInsRefund = mysql_query('INSERT INTO ajkpeserta SET idbroker="'.$metRefund['idbroker'].'",
																idclient="'.$metRefund['idclient'].'",
																idpolicy="'.$metRefund['idpolicy'].'",
																filename="'.$metRefund['filename'].'",
																nomorktp="'.$metRefund['nomorktp'].'",
																nomorpk="'.$metRefund['nomorpk'].'",
																nama="'.$metRefund['nama'].'",
																tgllahir="'.$metRefund['tgllahir'].'",
																usia="'.$metRefund['usia'].'",
																plafond="'.$metRefund['plafond'].'",
																tglakad="'.$metRefund['tglakad'].'",
																tenor="'.$metRefund['tenor'].'",
																tglakhir="'.$metRefund['tglakhir'].'",
																premirateid="'.$metRefund['premirateid'].'",
																premirate="'.$metRefund['premirate'].'",
																premi="'.$metRefund['premi'].'",
																diskonpremi="'.$metRefund['diskonpremi'].'",
																biayaadmin="'.$metRefund['biayaadmin'].'",
																extrapremi="'.$metRefund['extrapremi'].'",
																totalpremi="'.$metRefund['totalpremi'].'",
																medical="'.$metRefund['medical'].'",
																statuslunas="'.$metRefund['statuslunas'].'",
																statusaktif="Approve",
																tiperefund="'.$metRefund['tiperefund'].'",
																regional="'.$metRefund['regional'].'",
																cabang="'.$metRefund['cabang'].'",
																input_by="'.$metRefund['input_by'].'",
																input_time="'.$metRefund['input_time'].'",
																approve_by="'.$iduser.'",
																approve_time="'.$mamettoday.'"');

                $delDataRefund_Topup = mysql_query('DELETE FROM ajkpeserta_temp WHERE id="'.$key.'" ');
            }
            echo '<div class="panel panel-default">
					<div class="panel-heading">
		       		<div class="panel-heading-btn">
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		           	</div>
		           	<h4 class="m-t-0">Pengajuan Verifikasi Data Topup</h4>
		       	</div>
		       	<div class="panel-body">
		       		<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data topup telah disetujui oleh '.$namauser.'.</strong><br /> untuk proses selanjutnya menunggu konfirmasi untuk membuat data debitnote oleh bagian Admin.</h4></div>
		        </div>
		    </div>
		    <meta http-equiv="refresh" content="3; url=../klaim?qtype=topup&type='.AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY).'">';
        }
                ;
    break;

    case "klaim":

        if (isset($_REQUEST['tk'])) {
            $jenisklaim = $_REQUEST['tk'];
            if ($jenisklaim == "klaimphk") {
                $judul = "PHK";
                $tipeklaim = $judul;
                $qdok = 'ajkdocumentclaim.type in ("AJK","PHK") and ';
            } elseif ($jenisklaim == "klaimpaw") {
                $judul = "PAW";
                $tipeklaim = $judul;
                $qdok = 'ajkdocumentclaim.type in ("AJK","PAW") and ';
            } elseif ($jenisklaim == "klaimmacet") {
                $judul = "Kredit Macet";
                $tipeklaim = $judul;
                $qdok = 'ajkdocumentclaim.type in ("AJK","OTHER") and ';                
            } else {
                $judul = "Meninggal";
                $tipeklaim = "Death";
                $note = '<tr>
													<td><span class="text-danger"><strong>Note</strong></span></td>
													<td><span class="text-danger">Tidak semua dokumen klaim harus diupload, dokumen yang harus diupload berdasarkan tempat kejadian debitur meninggal.</span></td>
												</tr>';
                $qtempat = mysql_query('select * from ajkkejadianklaim WHERE tipe="tempatmeninggal" ORDER BY id ASC');
                $qPenyakit = mysql_query('select * from ajkkejadianklaim WHERE tipe="penyebabmeninggal" ORDER BY id ASC');

                while ($qtempat_ = mysql_fetch_array($qtempat)) {
                    $listtempat = $listtempat.'<option value="'.$qtempat_['id'].'"'._selected($_REQUEST['tempat'], $qtempat_['id']).'>'.$qtempat_['nama'].'</option>';
                }
                while ($qPenyakit_ = mysql_fetch_array($qPenyakit)) {
                    $listpenyakit = $listpenyakit.'<option value="'.$qPenyakit_['id'].'"'._selected($_REQUEST['penyakit'], $qPenyakit_['id']).'>'.$qPenyakit_['nama'].'</option>';
                }

                $tempat = '<dt><label class="control-label"><strong>Tempat Meninggal <span class="text-danger">*</span></label> :</strong></dt>
										<dd>
											<div class="form-group">
												<div class="col-sm-12">
													<select class="form-control" name="tempat">
														<option value="">-- Pilih --</option>
														'.$listtempat.'
													</select>
												</div>
											</div>
										</dd>';
                $penyakit ='<dt><label class="control-label"><strong>Penyebab Meninggal <span class="text-danger">*</span></label> :</strong></dt>
										<dd>
											<div class="form-group">
												<div class="col-sm-12">
													<select class="form-control" name="penyakit">
													<option value="">-- Pilih --</option>
													'.$listpenyakit.'
													</select>
												</div>
											</div>
										</dd>';
                $qdok = 'ajkdocumentclaim.type in ("AJK","DEATH") AND ';
            }
        }

        $metBatal = mysql_fetch_array(mysql_query('SELECT
				ajkpeserta.id,
				ajkpeserta.idbroker,
				ajkpeserta.idclient,
				ajkpeserta.idpolicy,
				ajkpeserta.iddn,
				ajkpeserta.regional,
				ajkpeserta.cabang,
				ajkpeserta.idpeserta,
				ajkpeserta.nama,
				ajkpolis.produk,
				ajkpolis.jumlahharibatal,
				ajkpeserta.nomorktp,
				ajkpeserta.tgllahir,
				ajkpeserta.usia,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				ajkpeserta.plafond,
				ajkpeserta.totalpremi,
				ajkpeserta.astotalpremi,
				ajkpeserta.statusaktif,
				ajkpeserta.statuspeserta,
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.tgldebitnote,
				ajkdebitnote.idas,
				ajkdebitnote.idaspolis,
				ajkcabang.`name` AS namacabang
				FROM
				ajkpeserta
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
				WHERE
				ajkpeserta.id="'.AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY).'"'));

        if (isset($_REQUEST['xqr'])=="prosesKlaim") {
        	//cek CN
        	$query = "SELECT * FROM ajkcreditnote WHERE idpeserta = ".AES::decrypt128CBC($_REQUEST['er'], ENCRYPTION_KEY)." AND del is NULL"; 
        	$qcn = mysql_query($query);

        	if(mysql_num_rows($qcn) == 0){

            $_separatorsNumb 	 = array(",", ".", "*", ".00", " ", "?", "?**");
            $_separatorsNumb_ 	  = array("");
            $regKlaim = mysql_query('INSERT INTO ajkcreditnote SET idbroker="'.$metBatal['idbroker'].'",
																    idclient="'.$metBatal['idclient'].'",
																    idproduk="'.$metBatal['idpolicy'].'",
																    idas="'.$metBatal['idas'].'",
																    idaspolis="'.$metBatal['idaspolis'].'",
																    idpeserta="'.$metBatal['id'].'",
																    idregional="'.$metBatal['regional'].'",
																    idcabang="'.$metBatal['cabang'].'",
																    iddn="'.$metBatal['iddn'].'",
																    tglklaim="'._convertDate2($_REQUEST['tglmeninggal']).'",
																    nilaiklaimdiajukan="'.str_replace($_separatorsNumb, $_separatorsNumb_, $_REQUEST['nilaidiajukan']).'",
																    tempatmeninggal="'.$_REQUEST['tempat'].'",
																    penyebabmeninggal="'.$_REQUEST['penyakit'].'",
																    noperkreditbank="'.$_REQUEST['noperkredit'].'",
																    status="Proses Penyelia",
																    tipeklaim="'.$tipeklaim.'",
																    input_by="'.$iduser.'",
																		input_time="'.$mamettoday.'"');
            

            //SET DOKUMEN KLAIM
            $cekLokasiMeninggal = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" AND id="'.$_REQUEST['tempat'].'"'));
            if ($cekLokasiMeninggal['nama']=="RUMAH") {
                $setDokumen = 'AND ajkdocumentclaim.opsional=""';
                // $setDokumen = 'AND ajkdocumentclaim.opsional="" OR ajkdocumentclaim.opsional="RUMAH"';
            } elseif ($cekLokasiMeninggal['nama']=="RUMAH SAKIT") {
                $setDokumen = 'AND (ajkdocumentclaim.opsional="" OR ajkdocumentclaim.opsional="Hospital")';
                // $setDokumen = 'AND (ajkdocumentclaim.opsional="" OR ajkdocumentclaim.opsional="RUMAH SAKIT")';
						} elseif ($cekLokasiMeninggal['nama']=="LUAR NEGRI") {
								// $setDokumen = 'AND (ajkdocumentclaim.opsional="" OR ajkdocumentclaim.opsional="LUAR NEGRI")';
            } else {
                $setDokumen = '';
            }
            $setDokumenKlaim = mysql_query('SELECT ajkdocumentclaim.id,
				   								  ajkdocumentclaim.namadokumen,
				   								  ajkdocumentclaim.opsional,
				   								  ajkdocumentclaimpartner.id AS iddokpartner,
				   								  ajkdocumentclaimpartner.idbroker,
				   								  ajkdocumentclaimpartner.idclient,
				   								  ajkdocumentclaimpartner.idpolicy
					   						FROM ajkdocumentclaim
				   							INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaim.id = ajkdocumentclaimpartner.iddoc
				   							WHERE '.$qdok.'
				   								  ajkdocumentclaimpartner.idbroker = "'.$metBatal['idbroker'].'" AND
				   								  ajkdocumentclaimpartner.idclient = "'.$metBatal['idclient'].'" AND
				   								  ajkdocumentclaimpartner.idpolicy = "'.$metBatal['idpolicy'].'"
				   								  '.$setDokumen.'
				   							ORDER BY ajkdocumentclaim.namadokumen ASC');
            while ($setDokumenKlaim_ = mysql_fetch_array($setDokumenKlaim)) {
                $setDoknya = mysql_query('INSERT INTO ajkdocumentclaimmember SET iddoc="'.$setDokumenKlaim_['iddokpartner'].'", idmember="'.$metBatal['id'].'"');
            }

            //SET DOKUMEN KLAIM

            echo '<div class="panel panel-default">
						<div class="panel-heading">
			           	<h4 class="m-t-0">Pengajuan Data Klaim '.$judul.'</h4>
			       	</div>
			       	<div class="panel-body">
			       		<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data klaim '.$judul.' telah dibuat oleh '.$namauser.'.</strong><br /> Silahkan upload dokumen klaim '.$judul.' pada halaman berikutnya.</h4></div>
			        </div>
				    </div>
				    <meta http-equiv="refresh" content="3; url=dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metBatal['id'], ENCRYPTION_KEY).'">';
			  	}else{
			  		 echo '<div class="panel panel-default">
						<div class="panel-heading">
			           	<h4 class="m-t-0">Pengajuan Data Klaim '.$judul.'</h4>
			       	</div>
			       	<div class="panel-body">
			       		<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data klaim '.$judul.' sudah pernah dibuat.</strong><br /> Silahkan diperiksa lagi klaim '.$judul.' pada halaman berikutnya.</h4></div>
			        </div>
				    </div>
				    <meta http-equiv="refresh" content="3; url=dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metBatal['id'], ENCRYPTION_KEY).'">';
			  	}
        } else {
            $metDoct = mysql_query('SELECT ajkdocumentclaim.id,
						ajkdocumentclaim.namadokumen,
						ajkdocumentclaim.opsional
						FROM ajkdocumentclaim
						INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaim.id = ajkdocumentclaimpartner.iddoc
						WHERE '.$qdok.'
						ajkdocumentclaimpartner.idbroker = "'.$metBatal['idbroker'].'" AND
					  ajkdocumentclaimpartner.idclient = "'.$metBatal['idclient'].'" AND
					  ajkdocumentclaimpartner.idpolicy = "'.$metBatal['idpolicy'].'"					  
						ORDER BY ajkdocumentclaim.namadokumen ASC');

            while ($metDoct_ = mysql_fetch_array($metDoct)) {
                $listdok = $listdok.'<tr class="odd gradeX">
															<td align="center">'.++$no.'</td>
															<td>'.$metDoct_['namadokumen'].'</td>
														</tr>';
            }

            echo '<form action="#" id="inputklaim" class="form-horizontal" method="post" enctype="multipart/form-data">
							<div class="panel panel-warning">
								<div class="panel-heading">
									<div class="panel-heading-btn">
										<!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>-->
									</div>
									<h4 class="m-t-0">Pengajuan Data Klaim '.$judul.'</h4>
								</div>
								<div class="panel-body">
									<div class="col-sm-6">
									<div class="m-t-5">
									<dl class="dl-horizontal">
									<dt>Produk :</dt><dd><strong> '.$metBatal['produk'].'</strong></dd>
									<dt>Debitnote :</dt><dd><strong> '.$metBatal['nomordebitnote'].'</strong></dd>
									<dt>ID Peserta :</dt><dd><strong> '.$metBatal['idpeserta'].'</strong></dd>
									<h4 class="text-left">DATA DEBITUR</h4>
									<dt>K.T.P :</dt><dd> '.$metBatal['nomorktp'].'</dd>
									<dt>Nama :</dt><dd> <strong>'.$metBatal['nama'].'</strong></dd>
									<dt>Tanggal Lahir :</dt><dd> '._convertDate($metBatal['tgllahir']).'</dd>
									<dt>Usia :</dt><dd> '.$metBatal['usia'].' tahun</dd>
									<dt>Tanggal Akad :</dt><dd> '._convertDate($metBatal['tglakad']).'</dd>
									<dt>Tenor :</dt><dd> '.$metBatal['tenor'].' bulan</dd>
									<dt>Tanggal Akhir :</dt><dd> '._convertDate($metBatal['tglakhir']).'</dd>
									<dt>Plafond :</dt><dd> '.duit($metBatal['plafond']).'</dd>
									<dt>Nett Premi :</dt><dd> <strong>'.duit($metBatal['totalpremi']).'</strong></dd>

									<h4 class="text-left">DATA KLAIM '.$judul.'</h4>
									<dt><label class="control-label"><strong>Nilai Klaim Diajukan <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
											<div class="col-sm-12"><input name="nilaidiajukan" id="nilaidiajukan" class="form-control" placeholder="Silahkan Input Nilai yang diajukan" type="text"></div>
										</div>
									</dd>
									<dt><label class="control-label"><strong>Tanggal '.$judul.' <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
											<div class="col-sm-12"><input name="tglmeninggal" id="tglmeninggal" class="form-control" placeholder="Silahkan Input Tanggal '.$judul.'" type="text"></div>
										</div>
									</dd>
									'.$tempat.'
									'.$penyakit.'
									<dt><label class="control-label"><strong>Nomor Perjanjian Kredit <span class="text-danger">*</span></label> :</strong></dt>
										<dd>
											<div class="form-group">
												<div class="col-sm-12"><input name="noperkredit" id="noperkredit" class="form-control" placeholder="Silahkan Input Nomor Perjanjian Kredit Bank" type="text"></div>
											</div>
										</dd>
									</dl>
								</div>
							</div>
							<div class="col-sm-6">
								<h4 class="text-center">DOKUMEN KLAIM</h4>
								<table id="data-debitnote" class="table table-bordered table-hover" width="100%">
									<thead>
										<tr class="warning">
											<th width="1%">No</th>
											<th>Nama Dokumen</th>
										</tr>
									</thead>
									<tbody>'.$listdok.' '.$note.'
									</tbody>
								</table>
								</div>
								</div>
								<div class="panel-footer text-center">
									<a href="../klaim?type='.AES::encrypt128CBC('klaimKlaim', ENCRYPTION_KEY).'&er='.$jenisklaim.'"><button type="button" class="btn btn-info m-b-5">Cancel</button></a> &nbsp;
									<!--<input type="hidden" name="xqr" value="regmeninggal"><button type="submit" class="btn btn-danger m-b-5">Upload Dokumen Klaim Meninggal</button>-->
									<input type="hidden" name="xqr" value="prosesKlaim"><button type="submit" class="btn btn-danger m-b-5">Proses Klaim</button>
								</div>
							</div>
						</form>';
        }
    break;

    case "dokumenklaim":
      $metKlaim = mysql_fetch_array(mysql_query('SELECT
			ajkpeserta.id,
			ajkpeserta.idbroker,
			ajkpeserta.idclient,
			ajkpeserta.idpolicy,
			ajkpeserta.iddn,
			ajkpeserta.idpeserta,
			ajkpeserta.nama,
			ajkpeserta.tgllahir,
			ajkpeserta.usia,
			ajkpeserta.tglakad,
			ajkpeserta.tenor,
			ajkpeserta.tglakhir,
			ajkpeserta.plafond,
			ajkpeserta.totalpremi,
			ajkpolis.produk,
			ajkdebitnote.nomordebitnote,
			ajkdebitnote.tgldebitnote,
			ajkcabang.`name` AS namacabang,
			ajkcreditnote.tglklaim,
			ajkcreditnote.nilaiklaimdiajukan,
			ajkcreditnote.tempatmeninggal,
			ajkcreditnote.penyebabmeninggal,
			ajkcreditnote.noperkreditbank,
			ajkcreditnote.status,
			IF(ajkcreditnote.tipeklaim = "Claim", "Meninggal", ajkcreditnote.tipeklaim) AS tipeklaim
			FROM
			ajkpeserta
			INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
			INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
			INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
			INNER JOIN ajkcreditnote ON ajkpeserta.id = ajkcreditnote.idpeserta
			WHERE ajkpeserta.id="'.AES::decrypt128CBC($_REQUEST['cnIDp'], ENCRYPTION_KEY).'" AND
				  ajkcreditnote.del IS NULL AND
				  ajkcreditnote.status !="Cancel" AND
				  ajkcreditnote.del IS NULL'));

            $qLokasinyanya = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metKlaim['tempatmeninggal'].'" AND tipe="tempatmeninggal"'));
            $qPenyakitnya = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metKlaim['penyebabmeninggal'].'" AND tipe="penyebabmeninggal"'));
            $mets = datediff($metKlaim['tglakad'], $metKlaim['tglklaim']);
            $usiapolis = explode(",", $mets);
            echo '<div class="panel panel-warning">
				   <div class="panel-heading">
				   <div class="panel-heading-btn">
				   <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				   <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
				   <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				   </div>
				   <h4 class="m-t-0">Data Klaim '.$metKlaim['tipeklaim'].'</h4>
				   </div>
				   <div class="panel-body">
				   <div class="col-sm-6">
				   <div class="m-t-5">
				   <dl class="dl-horizontal">
				   <h4 class="text-left">DATA DEBITUR</h4>
					 <dt>ID Peserta :</dt><dd><strong> '.$metKlaim['idpeserta'].'</strong></dd>
				   <dt>Nama :</dt><dd> '.$metKlaim['nama'].'</dd>
				   <dt>Produk :</dt><dd><strong> '.$metKlaim['produk'].'</strong></dd>
				   <dt>Debitnote :</dt><dd><strong> '.$metKlaim['nomordebitnote'].'</strong></dd>
				   <dt>Tanggal Lahir :</dt><dd> '._convertDate($metKlaim['tgllahir']).'</dd>
				   <dt>Usia :</dt><dd> '.$metKlaim['usia'].' Tahun</dd>
				   <dt>Tanggal Akad :</dt><dd> '._convertDate($metKlaim['tglakad']).' - '._convertDate($metKlaim['tglakhir']).'</dd>
				   <dt>Jangka Waktu (Tenor) :</dt><dd> '.$metKlaim['tenor'].' Bulan</dd>
				   <dt>Nilai Plafond :</dt><dd> '.duit($metKlaim['plafond']).'</dd>
				   <dt>Total Premi :</dt><dd> '.duit($metKlaim['totalpremi']).'</dd>
				   <dt>Cabang :</dt><dd> '.$metKlaim['namacabang'].'</dd>
				   </div>
				   </div>
				   <div class="col-sm-6">
				   <div class="m-t-5">
				   <dl class="dl-horizontal">
				   <h4 class="text-left">DATA KLAIM</h4>
				   <dt>Tanggal Klaim :</dt><dd> <strong>'._convertDate($metKlaim['tglklaim']).'</strong></dd>
				   <dt>Usia Polis :</dt><dd> ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</dd>
				   <dt>Tempat Klaim :</dt><dd> <strong>'.$qLokasinyanya['nama'].'</strong></dd>
				   <dt>Penyebab Klaim :</dt><dd> '.$qPenyakitnya['nama'].'</dd>
				   <dt>No. Perjanjian Kredit :</dt><dd> '.$metKlaim['noperkreditbank'].'</dd>
				   <dt>Nilai Klaim Diajukan :</dt><dd> '.duit($metKlaim['nilaiklaimdiajukan']).'</dd>
				   </div>
				   </div>
				   <div class="col-sm-12">
				   <h4 class="text-left">DOKUMEN KLAIM</h4>';
                $metDoct = mysql_query('SELECT ajkdocumentclaimmember.id,
											   ajkdocumentclaimmember.iddoc,
											   ajkdocumentclaimmember.idmember,
											   ajkdocumentclaimmember.fileklaim,
											   ajkdocumentclaimmember.catatan,
											   ajkdocumentclaim.namadokumen
										FROM ajkdocumentclaimmember
										INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
										INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
										WHERE ajkdocumentclaimmember.idmember = "'.$metKlaim['id'].'" AND
											  ajkdocumentclaimmember.status IS NULL
				   						ORDER BY ajkdocumentclaim.namadokumen ASC');

                   //UPLOAD DOKUMEN KLAIM
                if ($_REQUEST['uplklaim']=="xklaim") {
                    $dokPartner_ = mysql_fetch_array(mysql_query('SELECT ajkdocumentclaim.namadokumen
				   												 FROM ajkdocumentclaimpartner
				   												 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
				   												 WHERE ajkdocumentclaimpartner.id = "'.AES::decrypt128CBC($_REQUEST['dokpartner'], ENCRYPTION_KEY).'"'));
                    if ($_REQUEST['el']=="uploadDokDebitur") {
                        $FILESIZE_2 = 2000000;
                        if ($_FILES['fileupload']['size'] / 1024 > $FILESIZE_2) {
                            $metnotif = '<div class="alert alert-dismissable alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
								<strong>Error!</strong> File dokumen klaim tidak boleh lebih dari 2Mb !
			        	    	</div';
                        } else {
                            $futgl = date("Y-m-d H:i:s");
                            $klaimtime = date("His");
                            $foldername = date("y", strtotime($futgl)).date("m", strtotime($futgl)).'/';
                            $PathUploadKlaim= "../myFiles/_docs/".$foldername."";
                            if (!file_exists($PathUploadKlaim)) {
                                mkdir($PathUploadKlaim, 0777);
                                chmod($PathUploadKlaim, 0777);
                            }
                            $namafileuploadKlaim =  str_replace(" ", "_", $foldername."CLAIM_".$metKlaim['idpeserta'].'_'.$klaimtime.'_'.$_FILES['fileupload']['name']);
                            $nama_fileuploadKlaim =  str_replace(" ", "_", "CLAIM_".$metKlaim['idpeserta'].'_'.$klaimtime.'_'.$_FILES['fileupload']['name']);
                            $file_type = $_FILES['fileupload']['type']; //tipe file
                            $source = $_FILES['fileupload']['tmp_name'];
                            $direktori = "$PathUploadKlaim$nama_fileuploadKlaim"; // direktori tempat menyimpan file

                            move_uploaded_file($source, $direktori);
                            $setKlaim = mysql_query('UPDATE ajkdocumentclaimmember SET fileklaim="'.$namafileuploadKlaim.'" WHERE id="'.AES::decrypt128CBC($_REQUEST['idDokMember'], ENCRYPTION_KEY).'"');

                            echo '<meta http-equiv="refresh" content="1; url=dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metKlaim['id'], ENCRYPTION_KEY).'">';
                        }
                    }
                    echo '<form method="post" action="" id="inputklaim" class="form-horizontal" enctype="multipart/form-data">

					<div class="panel panel-primary">
						<div class="panel-heading">
					   	<h4 class="panel-title">'.$dokPartner_['namadokumen'].'</h4>
					   	</div>
						<div class="panel-body">
							'.$metnotif.'
					   		<div class="col-md-10"><input type="file" name="fileupload" id="fileupload" class="form-control" accept="application/pdf" /></div>
					   		<div class="col-md-2"><input type="hidden" name="el" value="uploadDokDebitur"><input name="upload" type="submit" value="Upload File" class="btn btn-info m-b-5"> &nbsp;
					   		<!--<a href="../klaim/dorequest.php?xq=klaim&er='.$_REQUEST['er'].'&xqr=regmeninggal&tglmeninggal='.$_REQUEST['tglmeninggal'].'&tempat='.$_REQUEST['tempat'].'&penyakit='.$_REQUEST['penyakit'].'&noperkredit='.$_REQUEST['noperkredit'].'"><button type="button" class="btn btn-danger m-b-5">Cancel</button></a>-->
					   		</div>
					   </div>
				</form>
			</div>';
                } elseif ($_REQUEST['uplklaim']=="deldokklaim") {
                    /*
                            echo AES::decrypt128CBC($_REQUEST['cnIDp'],ENCRYPTION_KEY).'<br />';
                               echo AES::decrypt128CBC($_REQUEST['dokpartner'],ENCRYPTION_KEY).'<br />';
                               echo AES::decrypt128CBC($_REQUEST['idDokMember'],ENCRYPTION_KEY).'<br />';
                    */
                    $delDok__=mysql_fetch_array(mysql_query('SELECT * FROM ajkdocumentclaimmember WHERE id="'.AES::decrypt128CBC($_REQUEST['idDokMember'], ENCRYPTION_KEY).'"'));
                    unlink('../myFiles/_docs/'.$delDok__['fileklaim'].'');
                    $delDok = mysql_query('UPDATE ajkdocumentclaimmember SET fileklaim = NULL WHERE id="'.AES::decrypt128CBC($_REQUEST['idDokMember'], ENCRYPTION_KEY).'"');
                    header("location:dorequest.php?xq=dokumenklaim&cnIDp=".AES::encrypt128CBC($metKlaim['id'], ENCRYPTION_KEY)."");
                } else {
                    //<meta http-equiv="refresh" content="3; url=dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metBatal['id'],ENCRYPTION_KEY).'">';
                }
                //UPLOAD DOKUMEN KLAIM
                echo '<table id="data-debitnote" class="table table-bordered table-hover" width="100%">
					   <thead><tr class="warning">
					   <th width="1%">No</th>
					   <th>Nama Dokumen</th>
					   <th width="20%">FIle Upload</th>
					   </tr>
					   </thead>
					   <tbody>';
                       while ($metDoct_ = mysql_fetch_array($metDoct)) {
                           if ($metKlaim['status']=="Cancel" or $metKlaim['status']=="Approve Paid") {
                               if ($metDoct_['fileklaim'] == null) {
                                   if ($level==6) {
                                       $metFIle = '';
                                   } else {
                                       $metFIle = '';
                                   }
                               } else {
                                   if ($level==6) {
                                       $metFIle = '<div class="col-sm-6"><a href="../myFiles/_docs/'.$metDoct_['fileklaim'].'" target="_blank" class="btn btn-info btn-block">View</a></div>';
                                   } else {
                                       $metFIle = '<a href="../myFiles/_docs/'.$metDoct_['fileklaim'].'" target="_blank" class="btn btn-info btn-block">View</a>';
                                   }
                               }
                           } else {
                               if ($metDoct_['fileklaim'] == null) {
                                   if ($level==6) {
                                       $metFIle = '<a href="../klaim/dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metKlaim['id'], ENCRYPTION_KEY).'&uplklaim=xklaim&dokpartner='.AES::encrypt128CBC($metDoct_['iddoc'], ENCRYPTION_KEY).'&idDokMember='.AES::encrypt128CBC($metDoct_['id'], ENCRYPTION_KEY).'" class="btn btn-block btn-danger" '.$disabledupload.'>Upload Dokumen Klaim</a>';
                                   } else {
                                       $metFIle = '<a href="javascript:;" class="btn btn-block btn-warning">Dokumen belum diupload</a>';
                                   }
                               } else {
                                   if ($level==6) {
                                       $metFIle = '<div class="col-sm-6"><a href="../myFiles/_docs/'.$metDoct_['fileklaim'].'" target="_blank" class="btn btn-info btn-block">View</a></div>
											<div class="col-sm-6 text-right"><a href="../klaim/dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metKlaim['id'], ENCRYPTION_KEY).'&uplklaim=deldokklaim&dokpartner='.AES::encrypt128CBC($metDoct_['iddoc'], ENCRYPTION_KEY).'&idDokMember='.AES::encrypt128CBC($metDoct_['id'], ENCRYPTION_KEY).'" class="btn btn-danger btn-block" '.$disabledupload.'>Hapus</a></div>';
                                   } else {
                                       $metFIle = '<a href="../myFiles/_docs/'.$metDoct_['fileklaim'].'" target="_blank" class="btn btn-info btn-block">View</a>';
                                   }
                               }
                           }
                           echo '<tr class="odd gradeX">
					   <td align="center">'.++$no.'</td>
					   <td><strong>'.$metDoct_['namadokumen'].'</strong></td>
					   <td>'.$metFIle.'</td>
					   </tr>';
                       }
            echo '</tbody>
				   </table>
				   </div>';
            echo '</div>';
                ;
    break;

    case "appklaim":
        if (isset($_REQUEST['tk'])) {
            $jenisklaim = $_REQUEST['tk'];
            if ($jenisklaim == "klaimphk") {
                $judul = "PHK";
                $tipeklaim = $judul;
            } elseif ($jenisklaim == "klaimpaw") {
                $judul = "PAW";
                $tipeklaim = $judul;
 						} elseif ($jenisklaim == "klaimmacet") {
                $judul = "Kredit Macet";
                $tipeklaim = $judul;                
            } else {
                $judul = "Meninggal";
                $tipeklaim = "Death";
            }
        }
        if (!$_REQUEST['approve']) {
            echo '<meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimKlaimVerifikasi', ENCRYPTION_KEY).'">
				<div class="panel panel-default"><div class="panel-body">
		    	<div class="alert alert-danger fade in m-b-10"><h4><strong> Uppss...!</strong><br /> Silahkan ceklist untuk persetujuan data pengajuan klaim '.$judul.'.</h4></div>
		    </div></div>';
        } else {
            foreach ($_REQUEST['approve'] as $k => $met) {
                $metKlaim_ = mysql_fetch_array(mysql_query('SELECT id, idpeserta FROM ajkcreditnote WHERE id="'.$met.'"'));
                $metPeserta_ = mysql_fetch_array(mysql_query('SELECT * FROM ajkpeserta WHERE id="'.$metKlaim_['idpeserta'].'"'));
                $metDebitur = mysql_query('UPDATE ajkpeserta SET status="Lapse", statuspeserta="'.$tipeklaim.'" WHERE id="'.$metKlaim_['idpeserta'].'"');
                $metDebiturCN = mysql_query('UPDATE ajkcreditnote SET status="Proses Adonai", approve_by="'.$iduser.'", approve_time="'.$mamettoday.'" WHERE id="'.$metKlaim_['id'].'"');
 								//EMAIL NOTIF 									
	 								$RecipientsFrom = array('notifikasi@jatim.adonai.co.id'=>'Simulasi Jatim');
	 								$to = "Adonai";
	                $RecipientsTo = array('klaim@adonai.co.id'=>'Klaim Adonai');
	                $RecipientsCC = array();
	                $RecipientsBCC = array('hansen@adonai.co.id'=>'Hansen');
	                $subject = 'Pengajuan Klaim '.$judul.' AN '.$metPeserta_['nama'].' ['.$metPeserta_['idpeserta'].']';
	                $body = '<p>Pengajuan Klaim '.$judul.' AN '.$metPeserta_['nama'].' ['.$metPeserta_['idpeserta'].'] telah di approve oleh Penyelia, mohon segera di proses.</p>';

	                kirimemail($RecipientsFrom, $to, $RecipientsTo,'', $RecipientsBCC, $subject, $body,'','');
                //EMAIL NOTIF END                
            }
            echo '<div class="panel panel-default">
				<div class="panel-heading">
		       		<div class="panel-heading-btn">
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
		           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		           	</div>
		           	<h4 class="m-t-0">Pengajuan Verifikasi Data Klaim '.$judul.'</h4>
		       	</div>
		       	<div class="panel-body">
		       		<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data klaim '.$judul.' telah disetujui oleh '.$namauser.'.</strong><br /> untuk proses selanjutnya menunggu konfirmasi untuk membuat data creditnote oleh bagian Admin klaim.</h4></div>
		        </div>
		    </div>
		    <meta http-equiv="refresh" content="3; url=../klaim?type='.AES::encrypt128CBC('klaimKlaimVerifikasi', ENCRYPTION_KEY).'">';
        }
            ;
    break;

    case "edklaim":   
			$metEdKlaim = mysql_fetch_array(mysql_query('SELECT
			ajkcreditnote.id,
			ajkcreditnote.idproduk,
			ajkpolis.produk,
			ajkdebitnote.nomordebitnote,
			ajkcreditnote.idbroker,
			ajkcreditnote.idclient,
			ajkcreditnote.idproduk,
			ajkcreditnote.iddn,
			ajkpeserta.id AS id_peserta,
			ajkpeserta.idpeserta,
			ajkpeserta.nomorktp,
			ajkpeserta.nama,
			ajkpeserta.idpolicy,
			ajkpeserta.tgllahir,
			ajkpeserta.usia,
			ajkpeserta.tglakad,
			ajkpeserta.tenor,
			ajkpeserta.tglakhir,
			ajkpeserta.plafond,
			ajkpeserta.totalpremi,
			ajkcreditnote.nilaiklaimdiajukan,
			ajkcreditnote.tglklaim,
			ajkcreditnote.tempatmeninggal,
			ajkcreditnote.penyebabmeninggal,
			ajkcreditnote.noperkreditbank
			FROM
			ajkcreditnote
			INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
			INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
			INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
			WHERE
			ajkcreditnote.id ="'.AES::decrypt128CBC($_REQUEST['cnIDp'], ENCRYPTION_KEY).'"'));

			if (isset($_REQUEST['tk'])) {
          $jenisklaim = $_REQUEST['tk'];
          if ($jenisklaim == "klaimphk") {
              $judul = "PHK";
              $tipeklaim = $judul;
              $qdok = 'ajkdocumentclaim.type in ("AJK","PHK") AND ';
          } elseif ($jenisklaim == "klaimpaw") {
              $judul = "PAW";
              $tipeklaim = $judul;
              $qdok = 'ajkdocumentclaim.type in ("AJK","PAW") AND ';
          } elseif ($jenisklaim == "klaimmacet") {
              $judul = "Kredit Macet";
              $tipeklaim = $judul;
              $qdok = 'ajkdocumentclaim.type in ("AJK","OTHER") AND ';
          } else {
              $judul = "Meninggal";
              $tipeklaim = "Death";
							$note = '<tr>
												<td><span class="text-danger"><strong>Note</strong></span></td>
												<td><span class="text-danger">Tidak semua dokumen klaim harus diupload, dokumen yang harus diupload berdasarkan tempat kejadian debitur meninggal.</span></td>
											</tr>';
              $qtempat = mysql_query('select * from ajkkejadianklaim WHERE tipe="tempatmeninggal" ORDER BY id ASC');
              $qPenyakit = mysql_query('select * from ajkkejadianklaim WHERE tipe="penyebabmeninggal" ORDER BY id ASC');

              while ($qtempat_ = mysql_fetch_array($qtempat)) {
                  $listtempat = $listtempat.'<option value="'.$qtempat_['id'].'"'._selected($metEdKlaim['tempatmeninggal'], $qtempat_['id']).'>'.$qtempat_['nama'].'</option>';
              }
              while ($qPenyakit_ = mysql_fetch_array($qPenyakit)) {
                  $listpenyakit = $listpenyakit.'<option value="'.$qPenyakit_['id'].'"'._selected($metEdKlaim['penyebabmeninggal'], $qPenyakit_['id']).'>'.$qPenyakit_['nama'].'</option>';
              }

              $tempat = '<dt><label class="control-label"><strong>Tempat Meninggal <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
											<div class="col-sm-12">
												<select class="form-control" name="tempat">
													<option value="">-- Pilih --</option>
													'.$listtempat.'
												</select>
											</div>
										</div>
									</dd>';
              $penyakit ='<dt><label class="control-label"><strong>Penyebab Meninggal <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
											<div class="col-sm-12">
												<select class="form-control" name="penyakit">
												<option value="">-- Pilih --</option>
												'.$listpenyakit.'
												</select>
											</div>
										</div>
									</dd>';       
							$qdok = 'ajkdocumentclaim.type in ("AJK","DEATH") AND ';         
          }
      } 

      if ($_REQUEST['xedit']=="prosesEditKlaim") {
          

          $_separatorsNumb = array(",", ".", "*", ".00", " ", "?", "?**");
          $_separatorsNumb_ 	  = array("");
          $regKlaim = mysql_query('UPDATE ajkcreditnote SET tglklaim="'._convertDate2($_REQUEST['tglmeninggal']).'",
											  nilaiklaimdiajukan="'.str_replace($_separatorsNumb, $_separatorsNumb_, $_REQUEST['nilaidiajukan']).'",
											  tempatmeninggal="'.$_REQUEST['tempat'].'",
											  penyebabmeninggal="'.$_REQUEST['penyakit'].'",
											  noperkreditbank="'.$_REQUEST['noperkredit'].'",
											  update_by="'.$iduser.'",
											  update_time="'.$mamettoday.'"
										WHERE id="'.AES::decrypt128CBC($_REQUEST['cnIDp'], ENCRYPTION_KEY).'"');

          //UPDATE DOKUMEN KLAIM
          $cekLokasiMeninggal = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" AND id="'.$_REQUEST['tempat'].'"'));
          if ($cekLokasiMeninggal['nama']=="RUMAH") {
              $setDokumen = 'AND ajkdocumentclaim.opsional=""';
          } elseif ($cekLokasiMeninggal['nama']=="RUMAH SAKIT") {
              $setDokumen = 'AND (ajkdocumentclaim.opsional="" OR ajkdocumentclaim.opsional="Hospital")';
          } else {
              $setDokumen = '';
          }
          $dokUpdateklaim = mysql_query('UPDATE ajkdocumentclaimmember SET catatan="Batal karena update tempat meninggal", status="Batal" WHERE idmember="'.$metEdKlaim['id_peserta'].'" AND status IS NULL');
          $setDokumenKlaim = mysql_query('SELECT ajkdocumentclaim.id,
   								  ajkdocumentclaim.namadokumen,
   								  ajkdocumentclaim.opsional,
   								  ajkdocumentclaimpartner.id AS iddokpartner,
   								  ajkdocumentclaimpartner.idbroker,
   								  ajkdocumentclaimpartner.idclient,
   								  ajkdocumentclaimpartner.idpolicy
	   						FROM ajkdocumentclaim
   							INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaim.id = ajkdocumentclaimpartner.iddoc
   							WHERE ajkdocumentclaim.type = "AJK" AND
   								  ajkdocumentclaimpartner.idbroker = "'.$metEdKlaim['idbroker'].'" AND
   								  ajkdocumentclaimpartner.idclient = "'.$metEdKlaim['idclient'].'" AND
   								  ajkdocumentclaimpartner.idpolicy = "'.$metEdKlaim['idproduk'].'"
   								  '.$setDokumen.'
   							ORDER BY ajkdocumentclaim.namadokumen ASC');
          while ($setDokumenKlaim_ = mysql_fetch_array($setDokumenKlaim)) {
              $cekDoknya_ = mysql_fetch_array(mysql_query('SELECT * FROM ajkdocumentclaimmember WHERE iddoc="'.$setDokumenKlaim_['iddokpartner'].'"AND idmember="'.$metEdKlaim['id_peserta'].'" AND status ="Batal"'));
              if ($cekDoknya_['fileklaim']) {
                  $setDokumennya = ',fileklaim="'.$cekDoknya_['fileklaim'].'"';
              } else {
                  $setDokumennya = '';
              }
              $setDoknya = mysql_query('INSERT INTO ajkdocumentclaimmember SET iddoc="'.$setDokumenKlaim_['iddokpartner'].'" '.$setDokumennya.', idmember="'.$metEdKlaim['id_peserta'].'"');
          }
          //SET DOKUMEN KLAIM
          echo '<div class="panel panel-default">
					<div class="panel-heading">
			      		<div class="panel-heading-btn">
			           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
			           	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
			           	</div>
			           	<h4 class="m-t-0">Pengajuan Data Klaim '.$judul.'</h4>
			       	</div>
			       	<div class="panel-body">
			       		<div class="alert alert-warning fade in m-b-10"><h4><strong> Pengajuan data klaim '.$judul.' telah diedit oleh '.$namauser.'.</strong></h4></div>
			        </div>
			    </div>
			    <meta http-equiv="refresh" content="3; url=dorequest.php?xq=dokumenklaim&cnIDp='.AES::encrypt128CBC($metEdKlaim['id_peserta'], ENCRYPTION_KEY).'">';
      }
     
      $metDoct = mysql_query('SELECT ajkdocumentclaim.id,
																			ajkdocumentclaim.namadokumen,
																			ajkdocumentclaim.opsional
															FROM ajkdocumentclaim
															INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaim.id = ajkdocumentclaimpartner.iddoc
															WHERE '.$qdok.'
																		ajkdocumentclaimpartner.idbroker = "'.$metEdKlaim['idbroker'].'" AND
																	  ajkdocumentclaimpartner.idclient = "'.$metEdKlaim['idclient'].'" AND
																	  ajkdocumentclaimpartner.idpolicy = "'.$metEdKlaim['idpolicy'].'"					  
																		ORDER BY ajkdocumentclaim.namadokumen ASC');

        while ($metDoct_ = mysql_fetch_array($metDoct)) {
            $listdok = $listdok.'<tr class="odd gradeX">
													<td align="center">'.++$no.'</td>
													<td>'.$metDoct_['namadokumen'].'</td>
												</tr>';
        }
      echo '
      <form action="#" id="inputklaim" class="form-horizontal" method="post" enctype="multipart/form-data">
			  <div class="panel panel-warning">
					<div class="panel-heading">
						<div class="panel-heading-btn">
							<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-expand"><i class="fa fa-expand"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-inverse" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						</div>
						<h4 class="m-t-0">Pengajuan Edit Data Klaim '.$judul.'</h4>
					</div>
					<div class="panel-body">
						<div class="col-sm-6">
							<div class="m-t-5">
								<dl class="dl-horizontal">
									<dt>Produk :</dt>
									<dd><strong> '.$metEdKlaim['produk'].'</strong></dd>
									<dt>Debitnote :</dt>
									<dd><strong> '.$metEdKlaim['nomordebitnote'].'</strong></dd>
									<dt>ID Peserta :</dt>
									<dd><strong> '.$metEdKlaim['idpeserta'].'</strong></dd>
									<h4 class="text-left">DATA DEBITUR</h4>
									<dt>K.T.P :</dt>
									<dd> '.$metEdKlaim['nomorktp'].'</dd>
									<dt>Nama :</dt>
									<dd> <strong>'.$metEdKlaim['nama'].'</strong></dd>
									<dt>Tanggal Lahir :</dt>
									<dd> '._convertDate($metEdKlaim['tgllahir']).'</dd>
									<dt>Usia :</dt>
									<dd> '.$metEdKlaim['usia'].' tahun</dd>
									<dt>Tanggal Akad :</dt>
									<dd> '._convertDate($metEdKlaim['tglakad']).'</dd>
									<dt>Tenor :</dt>
									<dd> '.$metEdKlaim['tenor'].' bulan</dd>
									<dt>Tanggal Akhir :</dt>
									<dd> '._convertDate($metEdKlaim['tglakhir']).'</dd>
									<dt>Plafond :</dt>
									<dd> '.duit($metEdKlaim['plafond']).'</dd>
									<dt>Nett Premi :</dt>
									<dd> <strong>'.duit($metEdKlaim['totalpremi']).'</strong></dd>
									<h4 class="text-left">DATA KLAIM MENINGGAL</h4>
									<dt><label class="control-label"><strong>Nilai Klaim Diajukan <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
							      	<div class="col-sm-12"><input name="nilaidiajukan" id="nilaidiajukan" class="form-control" value="'.$metEdKlaim['nilaiklaimdiajukan'].'" placeholder="Silahkan Input Nilai yang diajukan" type="text"></div>
							      </div>
									</dd>
										<dt><label class="control-label"><strong>Tanggal Klaim <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
							      	<div class="col-sm-12"><input name="tglmeninggal" id="tglmeninggal" value="'._convertDate3($metEdKlaim['tglklaim']).'" class="form-control" placeholder="Silahkan Input Tanggal Meninggal" type="text"></div>
							      </div>
									</dd>'.$tempat.' '.$penyakit.'
									<dt><label class="control-label"><strong>Nomor Perjanjian Kredit <span class="text-danger">*</span></label> :</strong></dt>
									<dd>
										<div class="form-group">
											<div class="col-sm-12"><input name="noperkredit" id="noperkredit" class="form-control" value="'.$metEdKlaim['noperkreditbank'].'" placeholder="Silahkan Input Nomor Perjanjian Kredit Bank" type="text"></div>
										</div>
									</dd>
								</dl>
							</div>
						</div>
						<div class="col-sm-6">
							<h4 class="text-center">DOKUMEN KLAIM</h4>
							<table id="data-debitnote" class="table table-bordered table-hover" width="100%">
								<thead>
									<tr class="warning">
										<th width="1%">No</th>
										<th>Nama Dokumen</th>
									</tr>
									</thead>
								<tbody>
									'.$listdok.$note.'
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel-footer text-center">
						<a href="../klaim?type='.AES::encrypt128CBC('klaimData', ENCRYPTION_KEY).'"><button type="button" class="btn btn-info m-b-5">Cancel</button></a> &nbsp;
						<input type="hidden" name="xedit" value="prosesEditKlaim"><button type="submit" class="btn btn-danger m-b-5">Edit Klaim</button>
					</div>
				</div>
			</form>';
                    ;
    break;

    default:

        ;
} // switch

?>
<?php
_footer();

echo '	</div>
	</div>
	';
_javascript();
?>

<script>
$(document).ready(function() {
	App.init();
	//Demo.init();
	$(".active").removeClass("active");
	document.getElementById("has_klaim").classList.add("active");
	<?php
    if ($_REQUEST['type']=="klaimBatal") {
        echo 'document.getElementById("idsub_klaimbatal").classList.add("active");';
    } elseif ($_REQUEST['type']=="klaimRefund") {
        echo 'document.getElementById("idsub_klaimrefund").classList.add("active");';
    } elseif ($_REQUEST['type']=="klaimTopup") {
        echo 'document.getElementById("idsub_klaimtopup").classList.add("active");';
    } elseif ($_REQUEST['type']=="klaimKlaim") {
        echo 'document.getElementById("idsub_klaimklaim").classList.add("active");';
    } else {
    }
    ?>
	$('#inputklaim').bootstrapValidator({
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
			tglbatal: {
				validators: {
					notEmpty: {	message: 'Silahkan input tanggal pembatalan'	},
					date: {	format: 'DD/MM/YYYY',
						message: 'Format tanggal pembatalan dd/mm/yyyy'
					}
				}
			},
			alasanbatal: {
				validators: {	notEmpty: {	message: 'Silahkan input alasan pembatalan'	}	}
			},
			plafon: {
				validators: {	notEmpty: {	message: 'Silahkan input plafon'	}	}
			},
			tglmeninggal: {
				validators: {
					notEmpty: {	message: 'Silahkan input tanggal meninggal'	},
					date: {	format: 'DD/MM/YYYY',
					message: 'Format tanggal meninggal dd/mm/yyyy'
					}
				}
			},
			tempat: {
				validators: {	notEmpty: {	message: 'Silahkan pilih tempat meninggal'	}	}
			},
			penyakit: {
				validators: {	notEmpty: {	message: 'Silahkan pilih penyebab meninggal'	}	}
			},
			noperkredit: {
				validators: {	notEmpty: {	message: 'Silahkan input nomor perjanjian kredit bank'	}	}
			},
			nilaidiajukan: {
				validators: {	notEmpty: {	message: 'Silahkan input nilai klaim yang diajukan'	}	}
			},
			fileupload: {
				validators: {	notEmpty: {	message: 'Silahkan upload dokumen klaim'	}	}
			}
		}
	});

	$("#tglbatal").datepicker({
		todayHighlight: !0,
		format:'dd/mm/yyyy',
		autoclose: true
	}).on('changeDate', function(e) {
		$('#inputklaim').bootstrapValidator('revalidateField', 'tglbatal');
	});

	$("#tglmeninggal").datepicker({
		todayHighlight: !0,
		format:'dd/mm/yyyy',
		autoclose: true
	}).on('changeDate', function(e) {
		$('#inputklaim').bootstrapValidator('revalidateField', 'tglmeninggal');
	});

	$('#plafon').mask('000,000,000,000,000' , {reverse: true});
	$('#nilaiobjek').mask('000,000,000,000,000' , {reverse: true});
	$('#nilaidiajukan').mask('000,000,000,000,000' , {reverse: true});
	$('#tgllahir').mask('99/99/9999');
	$('#tglakad').mask('99/99/9999');
	$('#tglmeninggal').mask('99/99/9999');
	$('#tenor').mask('000' , {reverse: true});

});
	</script>
</body>

</html>
