<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// Copyright (C) 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['dt']) {
case "edtdata":
	;
	break;

case "pending":
echo '<div class="page-header-section"><h2 class="title semibold">Pending/Medical Members</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';

		//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">

<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%">No</th>
	<th width="1%">Broker</th>
	<th>Partner</th>
	<th>Product</th>
	<th>Name</th>
	<th width="1%">DOB</th>
	<th width="1%">Age</th>
	<th width="10%">Plafond</th>
	<th width="10%">Tgl Akad</th>
	<th width="1%">Tenor</th>
	<th width="10%">Tgl Akhir</th>
	<th width="1%">Premium</th>
	<th>Medical</th>
	<th>Status</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
$metData = $database->doQuery('SELECT ajkpeserta.id,
ajkcobroker.`name` AS namebroker,
ajkclient.`name` AS nameclient,
ajkpolis.produk,
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
ajkpeserta.medical,
ajkcabang.`name` AS cabang
FROM
ajkpeserta
INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
WHERE ajkpeserta.iddn IS NULL AND ajkpeserta.del IS NULL AND ajkpeserta.statusaktif="Pending" '.$q___1.'
ORDER BY ajkpeserta.input_time DESC');
while ($metData_ = mysql_fetch_array($metData)) {
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metData_['namebroker'].'</td>
   	<td>'.$metData_['nameclient'].'</td>
   	<td align="center">'.$metData_['produk'].'</td>
   	<td>'.$metData_['nama'].'</td>
   	<td align="center">'._convertDate($metData_['tgllahir']).'</td>
   	<td align="center">'.$metData_['usia'].'</td>
   	<td align="right">'.duit($metData_['plafond']).'</td>
   	<td align="center">'._convertDate($metData_['tglakad']).'</td>
   	<td align="center">'.$metData_['tenor'].'</td>
   	<td align="center">'._convertDate($metData_['tglakhir']).'</td>
   	<td align="right">'.duit($metData_['totalpremi']).'</td>
   	<td align="center"><span class="label label-warning">'.$metData_['medical'].'</span></td>
   	<td align="center"><span class="label label-danger">'.$metData_['statusaktif'].'</span></td>
   	<td>'.$metData_['cabang'].'</td>
    </tr>';
		}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine" placeholder="Age"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Plafond"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Medical"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
        </tr>
        </tfoot></table>
    	</div>
		</div>
    </div>
</div>';
		;
		break;

case "viewSPK":
$metSPK = mysql_fetch_array($database->doQuery('SELECT ajkspk.id,
													   ajkspk.idbroker,
													   ajkspk.idpartner,
													   ajkspk.idproduk,
													   ajkcobroker.`name` AS broker,
													   ajkcobroker.logo AS brokerlogo,
													   ajkclient.`name` AS perusahaan,
													   ajkclient.logo AS perusahaanlogo,
													   ajkpolis.produk,
													   ajkcabang.`name` AS cabang,
													   ajkspk.nomorspk,
													   ajkspk.statusspk,
													   ajkspk.nomorktp,
													   ajkspk.nama,
													   IF(ajkspk.jeniskelamin="M", "Male", "Female") AS gender,
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
													   ajkspk.em,
													   ajkspk.premiem,
													   ajkspk.ketem,
													   ajkspk.nettpremi,
													   	IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
										 				/*IF(ajkspk.em IS NULL, ajkspk.nettpremi, (ajkspk.nettpremi + (ajkspk.nettpremi * ajkspk.em / 100))) AS nettpremi,*/
													   ajkspk.tinggibadan,
													   ajkspk.beratbadan,
													   ajkspk.tekanandarah,
													   ajkspk.nadi,
													   ajkspk.pernafasan,
													   ajkspk.guladarah,
													   ajkspk.tglperiksa,
													   ajkspk.dokterpemeriksa,
													   ajkspk.dokterpertanyaan,
													   ajkspk.pertanyaanketerangan,
													   ajkspk.doktercatatan,
													   ajkspk.dokterkesimpulan,
													   ajkspk.photodebitur1,
													   ajkspk.photodebitur2,
													   ajkspk.photoktp,
													   ajkspk.photosk,
													   ajkspk.ttddebitur,
													   ajkspk.ttdmarketing,
													   ajkspk.photobydokter,
													   ajkspk.ttddokter,
													   userinput.firstname AS namainput,
													   DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
													   dokter.firstname AS namadokter,
													   DATE_FORMAT(ajkspk.medical_date, "%Y-%m-%d") AS tgldokter,
													   userapprove.firstname AS namaapprove,
													   DATE_FORMAT(ajkspk.approve_date, "%Y-%m-%d") AS tglapprove,
													   userapproveem.firstname AS namaapproveem,
													   DATE_FORMAT(ajkspk.approveem_date, "%Y-%m-%d") AS tglapproveem,
													   userapprovespk.firstname AS namaapprovespk,
													   DATE_FORMAT(ajkspk.approvespk_date, "%Y-%m-%d") AS tglapprovespk
												FROM ajkspk
												INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
												INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
												INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
												INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
												INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
												LEFT JOIN useraccess AS dokter ON ajkspk.dokterpemeriksa = dokter.id
												LEFT JOIN useraccess AS userapprove ON ajkspk.approve_by = userapprove.id
												LEFT JOIN useraccess AS userapproveem ON ajkspk.approveem_by = userapproveem.id
												LEFT JOIN useraccess AS userapprovespk ON ajkspk.approvespk_by = userapprovespk.id
												WHERE ajkspk.id = "'.$thisEncrypter->decode($_REQUEST['gid']).'" '.$q___SPK.''));
if ($metSPK['statusspk']=="Request") {
	$_statusdata = '<button type="button" class="btn btn-warning btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}elseif ($metSPK['statusspk']=="Survey") {
	$_statusdata = '<button type="button" class="btn btn-inverse btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}elseif ($metSPK['statusspk']=="Approved") {
	$_statusdata = '<button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}elseif ($metSPK['statusspk']=="Aktif") {
	$_statusdata = '<button type="button" class="btn btn-primary btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}elseif ($metSPK['statusspk']=="Realisasi") {
	$_statusdata = '<button type="button" class="btn btn-success btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}else{
	$_statusdata = '<button type="button" class="btn btn-danger btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
}

if ($metSPK['statusspk']=="Request") {
	$photoSPKnya = '';
}else{
	$photoSPKnya = '<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur2'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photoktp'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photosk'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>';
}

if ($metSPK['ttddebitur']!="") 		{	$ttddebitur_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddebitur_ = '';	}
if ($metSPK['ttdmarketing']!="")	{	$ttdmarketing_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttdmarketing_ = '';	}
if ($metSPK['ttddokter']!="") 		{	$ttddokter_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddokter'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddokter'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddokter_ = '';	}

//TAMPILDEBITNOTE
if ($metSPK['statusspk']=="Realisasi") {
$cekDNspk = mysql_fetch_array($database->doQuery('SELECT ajkdebitnote.id,
														 ajkdebitnote.nomordebitnote
												FROM ajkspk
												INNER JOIN ajkpeserta ON ajkspk.nomorspk = ajkpeserta.nomorspk
												INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
												WHERE ajkspk.id = "'.$thisEncrypter->decode($_REQUEST['gid']).'"'));
	$spkdebitnote = '<button type="button" class="btn btn-success btn-xs mb5"><strong>'.$cekDNspk['nomordebitnote'].'</strong></button>';
}else{
	$spkdebitnote = '';
}
//TAMPILDEBITNOTE
$nettpremidebiturspk = $metSPK['premi'] + $metSPK['premiem'];
echo '<div class="page-header-section"><h2 class="title semibold">Preview Member SPK</h2></div>
	<div class="page-header-section">
	<div class="toolbar"><a href="ajk.php?re=dataGnr">'.BTN_BACK.'</a></div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="tab-content">
	    	<div class="tab-pane active" id="profile">
	        <form class="panel form-horizontal form-bordered" name="form-profile">
			<div class="panel-body pt0 pb0">
	        	<div class="form-group header bgcolor-default">
	            	<div class="col-md-6">
	            	<ul class="list-table">
	            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['brokerlogo'].'" alt="" width="75px"></li>
						<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['broker'].'</h4></li>
					</ul>
					</div>
					<div class="col-md-6">
	            	<ul class="list-table">
						<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['perusahaan'].'</h4></li>
						<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['perusahaanlogo'].'" alt="" width="75px"></li>
					</ul>
					</div>
	            </div>
				<div class="form-group">
	            	<div class="col-xs-12 col-sm-12 col-md-8">
							<div class="col-sm-3"><a href="javascript:void(0);">Product</a></div><div class="col-sm-9">'.$metSPK['produk'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Status</a></div><div class="col-sm-9">'.$_statusdata.'&nbsp; '.$spkdebitnote.' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Branch</a></div><div class="col-sm-9">'.$metSPK['cabang'].'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Debitur</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">SPK Number</a></div><div class="col-sm-9"><button type="button" class="btn btn-success btn-xs"><strong>'.$metSPK['nomorspk'].'&nbsp;</strong></button></div>
							<div class="col-sm-3"><a href="javascript:void(0);">K.T.P</a></div><div class="col-sm-9">'.$metSPK['nomorktp'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Member</a></div><div class="col-sm-9">'.$metSPK['nama'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">D.O.B</a></div><div class="col-sm-9">'._convertDate($metSPK['dob']).'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Gender</a></div><div class="col-sm-9">'.$metSPK['gender'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Age</a></div><div class="col-sm-9">'.$metSPK['usia'].' years&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Address Member</a></div><div class="col-sm-9">'.$metSPK['alamat'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Occupation</a></div><div class="col-sm-9">'.$metSPK['pekerjaan'].'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-warning mt0 mb5">Data Insurance</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">Plafond</a></div><div class="col-sm-9">'.duit($metSPK['plafond']).'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tenor</a></div><div class="col-sm-9">'.duit($metSPK['tenor']).' month&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Date of Insurance</a></div><div class="col-sm-9">'._convertDate($metSPK['tglakad']).' to '._convertDate($metSPK['tglakhir']).' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">MPP (month)</a></div><div class="col-sm-9">'.$metSPK['mppbln'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Premium</a></div><div class="col-sm-9">'.duit($metSPK['premi']).' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">EM</a></div><div class="col-sm-9">'.duit($metSPK['em']).'% &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Premi EM</a></div><div class="col-sm-9">'.duit($metSPK['premiem']).' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Note EM</a></div><div class="col-sm-9">'.$metSPK['ketem'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Nett Premium</a></div><div class="col-sm-9">'.duit($nettpremidebiturspk).' &nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-danger mt0 mb5">Data Medical</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tinggi/Berat Badan</a></div><div class="col-sm-9">'.$metSPK['tinggibadan'].'/'.$metSPK['beratbadan'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tekanan Darah</a></div><div class="col-sm-9">'.$metSPK['tekanandarah'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Nadi</a></div><div class="col-sm-9">'.$metSPK['nadi'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Pernafasan</a></div><div class="col-sm-9">'.$metSPK['pernafasan'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Gula Darah</a></div><div class="col-sm-9">'.$metSPK['guladarah'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tanggal Pemeriksa</a></div><div class="col-sm-9">'._convertDate($metSPK['tglperiksa']).' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Dokter Pemeriksa</a></div><div class="col-sm-9">'.$metSPK['namadokter'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Catatan Dokter</a></div><div class="col-sm-9">'.$metSPK['doktercatatan'].' &nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Kesimpulan Dokter</a></div><div class="col-sm-9">'.$metSPK['dokterkesimpulan'].' &nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data User</h4></div>';
							if ($metSPK['statusspk']=="Aktif") {
						echo '<div class="col-sm-3"><a href="javascript:void(0);">User Input</a></div><div class="col-sm-9">'.$metSPK['namainput'].'  - '.$metSPK['tglinput'].' &nbsp;</div>
							  <div class="col-sm-3"><a href="javascript:void(0);">User Approve</a></div><div class="col-sm-9">'.$metSPK['namaapprove'].'  - '.$metSPK['tglapprove'].' &nbsp;</div>
							  <div class="col-sm-3"><a href="javascript:void(0);">EM By</a></div><div class="col-sm-9">'.$metSPK['namaapproveem'].'  - '.$metSPK['tglapproveem'].' &nbsp;</div>
							  <div class="col-sm-3"><a href="javascript:void(0);">Approve SPK By</a></div><div class="col-sm-9">'.$metSPK['namaapprovespk'].'  - '.$metSPK['tglapprovespk'].' &nbsp;</div>';
							}else{
						echo '<div class="col-sm-3"><a href="javascript:void(0);">User Input</a></div><div class="col-sm-9">'.$metSPK['namainput'].'  - '.$metSPK['tglinput'].' &nbsp;</div>
							  <div class="col-sm-3"><a href="javascript:void(0);">User Approve</a></div><div class="col-sm-9">'.$metSPK['namaapprove'].'  - '.$metSPK['tglapprove'].' &nbsp;</div>';
							}
                echo '</div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
						<div class="row" id="shuffle-grid">
							<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($metSPK['tglinput']).'" data-title="background1">
    							<div class="thumbnail">
        							<div class="media">
									<div class="indicator"><span class="spinner"></span></div>
										<div class="overlay">
                						'.$photoSPKnya.'
										<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                						</div>
            						<span class="meta bottom darken">
            						<h5 class="nm semibold">'.$metSPK['nama'].' <br/><small><i class="ico-calendar2"></i> '._convertDate($metSPK['tglinput']).'</small></h5>
            						</span>
            						<img data-toggle="unveil" src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" data-src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" alt="Photo" width="100%" height="500"/>
        							</div>
    							</div>
							</div>
						</div>
						'.$ttddebitur_.' &nbsp; '.$ttdmarketing_.' &nbsp; '.$ttddokter_.'
					</div>
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';
	;
	break;

case "statusSPK":
echo '<div class="page-header-section"><h2 class="title semibold">Status SPK</h2></div>
      	<div class="page-header-section">
		</div>
      </div>
      <div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%">No</th>
	<th>Broker</th>
	<th>Partner</th>
	<th>Product</th>
	<th width="1%">Total</th>
	<th width="1%">Realisasi</th>
	<th width="1%">Aktif</th>
	<th width="1%">PreApproval</th>
	<th width="1%">Approved</th>
	<th width="1%">Process</th>
	<th width="1%">Pending</th>
	<th width="1%">Request</th>
	<th width="1%">Batal</th>
	<th width="1%">Tolak</th>
</tr>
</thead>
<tbody>';
$metDataSPK = $database->doQuery('SELECT
ajkcobroker.`name` AS broker,
ajkclient.`name` AS perusahaan,
ajkpolis.produk AS produk,
Count(ajkspk.nomorspk) AS jSPK,
count(case when ajkspk.statusspk ="Tolak" then ajkspk.statusspk END) as jDataTolak,
count(case when ajkspk.statusspk ="Batal" then ajkspk.statusspk END) as jDataBatal,
count(case when ajkspk.statusspk ="Request" then ajkspk.statusspk END) as jDataRequest,
count(case when ajkspk.statusspk ="Proses" then ajkspk.statusspk END) as jDataProcess,
count(case when ajkspk.statusspk ="Pending" then ajkspk.statusspk END) as jDataPending,
count(case when ajkspk.statusspk ="Approve" then ajkspk.statusspk END) as jDataApproved,
count(case when ajkspk.statusspk ="PreApproval" then ajkspk.statusspk END) as jDataPreApproval,
count(case when ajkspk.statusspk ="Aktif" then ajkspk.statusspk END) as jDataAktif,
count(case when ajkspk.statusspk ="Realisasi" then ajkspk.statusspk END) as jDataRealisasi,
ajkspk.id,
ajkspk.idbroker,
ajkspk.idpartner,
ajkspk.idproduk,
ajkspk.statusspk
FROM ajkspk
INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
WHERE ajkspk.id !="" AND ajkspk.del IS NULL '.$q___SPK.'
GROUP BY ajkspk.idbroker, ajkspk.idpartner, ajkspk.idproduk');
	//WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
while ($metDataStatus_ = mysql_fetch_array($metDataSPK)) {
if ($metDataStatus_['jDataTolak'] > 0) {	$statusTolak = '<span class="label label-danger">'.$metDataStatus_['jDataTolak'].'</span>';	}else{	$statusTolak = $metDataStatus_['jDataTolak'];	}
if ($metDataStatus_['jDataBatal'] > 0) {	$statusBatal = '<span class="label label-danger">'.$metDataStatus_['jDataBatal'].'</span>';	}else{	$statusBatal = $metDataStatus_['jDataBatal'];	}
if ($metDataStatus_['jDataRequest'] >= 1 && $metDataStatus_['jDataRequest'] <= 5) 		{	$statusRequest = '<span class="label label-danger">'.$metDataStatus_['jDataRequest'].'</span>';		}	elseif ($metDataStatus_['jDataRequest'] == 0)		{	$statusRequest = $metDataStatus_['jDataRequest'];		}	else{	$statusRequest = '<span class="label label-danger">'.$metDataStatus_['jDataRequest'].'</span>';	}
if ($metDataStatus_['jDataProcess'] >= 1 && $metDataStatus_['jDataProcess'] <= 5) 		{	$statusProcess = '<span class="label label-danger">'.$metDataStatus_['jDataProcess'].'</span>';		}	elseif ($metDataStatus_['jDataProcess'] == 0)		{	$statusProcess = $metDataStatus_['jDataProcess'];		}	else{	$statusProcess = '<span class="label label-primary">'.$metDataStatus_['jDataProcess'].'</span>';	}
if ($metDataStatus_['jDataPending'] >= 1 && $metDataStatus_['jDataPending'] <= 5) 		{	$statusPending = '<span class="label label-danger">'.$metDataStatus_['jDataPending'].'</span>';		}	elseif ($metDataStatus_['jDataPending'] == 0)		{	$statusPending = $metDataStatus_['jDataPending'];		}	else{	$statusPending = '<span class="label label-primary">'.$metDataStatus_['jDataPending'].'</span>';	}
if ($metDataStatus_['jDataPreApproval'] >= 1 && $metDataStatus_['jDataPreApproval'] <= 5) 	{	$statusPreApproval = '<span class="label label-danger">'.$metDataStatus_['jDataPreApproval'].'</span>';	}	elseif ($metDataStatus_['jDataPreApproval'] == 0)		{	$statusPreApproval = $metDataStatus_['jDataPreApproval'];		}	else{	$statusPreApproval = '<span class="label label-primary">'.$metDataStatus_['jDataPreApproval'].'</span>';	}
if ($metDataStatus_['jDataApproved'] >= 1 && $metDataStatus_['jDataApproved'] <= 5) 	{	$statusApproved = '<span class="label label-danger">'.$metDataStatus_['jDataApproved'].'</span>';	}	elseif ($metDataStatus_['jDataApproved'] == 0)		{	$statusApproved = $metDataStatus_['jDataApproved'];		}	else{	$statusApproved = '<span class="label label-primary">'.$metDataStatus_['jDataApproved'].'</span>';	}
if ($metDataStatus_['jDataAktif'] >= 1 && $metDataStatus_['jDataAktif'] <= 5) 			{	$statusAktif = '<span class="label label-danger">'.$metDataStatus_['jDataAktif'].'</span>';			}	elseif ($metDataStatus_['jDataAktif'] == 0)			{	$statusAktif = $metDataStatus_['jDataAktif'];			}	else{	$statusAktif = '<span class="label label-primary">'.$metDataStatus_['jDataAktif'].'</span>';	}
if ($metDataStatus_['jDataARealisasi'] >= 1 && $metDataStatus_['jDataRealisasi'] <= 5) 	{	$statusRealisasi = '<span class="label label-danger">'.$metDataStatus_['jDataRealisasi'].'</span>';	}	elseif ($metDataStatus_['jDataRealisasi'] == 0)		{	$statusRealisasi = $metDataStatus_['jDataRealisasi'];	}	else{	$statusRealisasi = '<span class="label label-primary">'.$metDataStatus_['jDataRealisasi'].'</span>';	}
echo '<tr>
	   	<td align="center">'.++$no.'</td>
	   	<td>'.$metDataStatus_['broker'].'</td>
	   	<td>'.$metDataStatus_['perusahaan'].'</td>
	   	<td align="center">'.$metDataStatus_['produk'].'</td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'"><span class="label label-success">'.$metDataStatus_['jSPK'].'</span></a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Realisasi").'">'.$statusRealisasi.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Aktif").'">'.$statusAktif.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("PreApproval").'">'.$statusPreApproval.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Approve").'">'.$statusApproved.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Proses").'">'.$statusProcess.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Pending").'">'.$statusPending.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Request").'">'.$statusRequest.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Batal").'">'.$statusBatal.'</a></td>
	   	<td align="center"><a href="ajk.php?re=spk&dt=statusvSPK&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idpartner']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Tolak").'">'.$statusTolak.'</a></td>
	    </tr>';
}
echo '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Total"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Realisasi"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Aktif"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="PreApproval"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Approved"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Process"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Pending"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Request"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Batal"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Tolak"></th>
		</tr>
		</tfoot></table>
		</div>
		</div>
		</div>
	</div>';
	;
	break;

case "statusvSPK":
echo '<div class="page-header-section"><h2 class="title semibold">Status AJK</h2></div>
      	<div class="page-header-section">
		</div>
      </div>
      <div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%">No</th>
	<th>Broker</th>
	<th>Partner</th>
	<th>Product</th>
	<th>Status</th>
	<th width="1%">Nomor SPK</th>
	<th width="1%">Name</th>
	<th width="1%">DOB</th>
	<th width="1%">Age</th>
	<th width="1%">Start Insurance</th>
	<th width="1%">Tenor</th>
	<th width="1%">End Insurance</th>
	<th width="1%">Plafond</th>
	<th width="1%">Nett Premium</th>
	<th width="1%">Input</th>
	<th width="1%">Input Date</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
if ($_REQUEST['gidstat']) {
	$statusDatanya = 'AND ajkspk.statusspk="'.$thisEncrypter->decode($_REQUEST['gidstat']).'"';
}else{
	$statusDatanya = '';
}
$viewGeneral = $database->doQuery('SELECT
ajkcobroker.`name` AS broker,
ajkclient.`name` AS perusahaan,
ajkpolis.produk AS produk,
ajkspk.id,
ajkspk.idbroker,
ajkspk.idpartner,
ajkspk.idproduk,
ajkspk.statusspk,
ajkspk.nomorspk,
ajkspk.nama,
ajkspk.dob,
ajkspk.usia,
ajkspk.plafond,
ajkspk.tglakad,
ajkspk.tenor,
ajkspk.tglakhir,
ajkspk.nettpremi,
userinput.firstname AS namainput,
DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
ajkcabang.`name` AS cabang
FROM ajkspk
INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
WHERE
ajkspk.idbroker="'.$thisEncrypter->decode($_REQUEST['gidb']).'" AND
ajkspk.idpartner="'.$thisEncrypter->decode($_REQUEST['gidc']).'" AND
ajkspk.idproduk="'.$thisEncrypter->decode($_REQUEST['gidp']).'" '.$statusDatanya.' AND
ajkspk.del IS NULL
ORDER BY ajkspk.id DESC');
while ($viewGeneral_ = mysql_fetch_array($viewGeneral)) {
echo '<tr>
	   	<td align="center">'.++$no.'</td>
	   	<td>'.$viewGeneral_['broker'].'</td>
	   	<td>'.$viewGeneral_['perusahaan'].'</td>
	   	<td align="center">'.$viewGeneral_['produk'].'</td>
	   	<td align="center">'.$viewGeneral_['statusspk'].'</td>
	   	<td align="center"><span class="label label-primary">'.$viewGeneral_['nomorspk'].'</span></td>
		<td><a href="ajk.php?re=spk&dt=viewSPK&gid='.$thisEncrypter->encode($viewGeneral_['id']).'">'.$viewGeneral_['nama'].'</a></td>
	   	<td align="center">'._convertDate($viewGeneral_['dob']).'</td>
	   	<td align="center">'.$viewGeneral_['usia'].'</td>
	   	<td align="center">'._convertDate($viewGeneral_['tglakad']).'</td>
	   	<td align="center">'.$viewGeneral_['tenor'].'</td>
	   	<td align="center">'._convertDate($viewGeneral_['tglakhir']).'</td>
	   	<td align="right">'.duit($viewGeneral_['plafond']).'</td>
	   	<td align="right">'.duit($viewGeneral_['nettpremi']).'</td>
	   	<td align="center">'.$viewGeneral_['namainput'].'</td>
	   	<td align="center">'._convertDate($viewGeneral_['tglinput']).'</td>
	   	<td align="center">'.$viewGeneral_['cabang'].'</td>
	</tr>';
}
echo '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="SPK"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="DOB"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Value"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="User"></th>
			<th><input type="hidden" class="form-control" name="search_engine" placeholder="Survey"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="plafond"></th>
			<th><input type="hidden" class="form-control" name="search_engine" placeholder=""></th>
			<th><input type="hidden" class="form-control" name="search_engine" placeholder=""></th>
			<th><input type="hidden" class="form-control" name="search_engine" placeholder=""></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
		</tr>
		</tfoot></table>
		</div>
		</div>
		</div>
	</div>';
	;
	break;

case "verf":
echo '<div class="page-header-section"><h2 class="title semibold">Verification SPK (EM)</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
if ($q['level']==9) {
	$statusSPK = 'AND ajkspk.statusspk ="Proses"';
}elseif ($q['level']==10) {
	$statusSPK = 'AND ajkspk.statusspk ="Approve"';
}else{
	$statusSPK = '';
}
$cekVerf = mysql_fetch_array($database->doQuery('SELECT ajkspk.id FROM ajkspk WHERE ajkspk.id !="" '.$statusSPK.' '.$q___SPK.' AND ajkspk.del IS NULL'));
if ($cekVerf['id']) {
echo '<div class="row">
      	<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-1 control-label">SPAK</label>
				<div class="col-sm-11"><input type="text" name="nomorspk" value="'.$_REQUEST['nomorspk'].'" class="form-control" placeholder="SPK"></div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Name</label>
				<div class="col-sm-11"><input type="text" name="namaspk" value="'.$_REQUEST['namaspk'].'" class="form-control" placeholder="Name"></div>
			</div>
		</div>
		<div class="panel-footer"><input type="hidden" name="sx" value="searchspk">'.BTN_SEARCHING.'</div>
		</form>
		</div>
		</div>';
if ($_REQUEST['sx']=="searchspk") {
	$spknomor = 'AND ajkspk.nomorspk LIKE "%'.$_REQUEST['nomorspk'].'%"';
	$spknama = 'AND ajkspk.nama LIKE "%'.$_REQUEST['namaspk'].'%"';
}

echo '<div class="table-responsive panel-collapse pull out">
<form method="post" action="" id ="frm1">
<table class="table table-bordered table-hover">
<thead>
<tr><th width="1%">No</th>
	<th width="1%"><span class="checkbox custom-checkbox">
					<input type="checkbox" name="checkall" id=" " value=" " onclick="checkedAll(frm1);"/>
                    <label for=" "></label>
                   </span>
    </th>
	<th width="1%">Edit</th>
	<th>Broker</th>
	<th>Partner</th>
	<th>Product</th>
	<th width="1%">Status</th>
	<th width="1%">SPK</th>
	<th width="1%">Name</th>
	<th width="5%">DOB</th>
	<th width="1%">Age</th>
	<th width="5%">Start Insurance</th>
	<th width="1%">Tenor</th>
	<th width="5%">End Insurance</th>
	<th width="1%">Plafond</th>
	<th width="1%">Premium</th>
	<th width="1%">EM(%)</th>
	<th width="1%">Premium EM</th>
	<th width="1%">Nett Premium</th>
	<th width="10%">User Input</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
if ($q['level']==9) {
	$statusSPK = 'AND ajkspk.statusspk ="Proses"';
	$ApprovalSPK= '<input type="hidden" name="dt" value="approvespk">'.BTN_APPROVESPK.'';
}elseif ($q['level']==10) {
	$statusSPK = 'AND ajkspk.statusspk ="Approve"';
	$ApprovalSPK= '<input type="hidden" name="dt" value="approvespkaktif">'.BTN_APPROVESPKAktif.'';
}else{
	$statusSPK = '';
	$ApprovalSPK='';
}
$metDataSPK = $database->doQuery('SELECT ajkspk.id,
										 ajkspk.idbroker,
										 ajkspk.idpartner,
										 ajkspk.idproduk,
										 ajkcobroker.`name` AS broker,
										 ajkclient.`name` AS perusahaan,
										 ajkpolis.produk AS produk,
										 ajkspk.nomorspk,
										 ajkspk.statusspk,
										 ajkspk.nama,
										 IF(ajkspk.jeniskelamin="M", "Male", "Female") AS gender,
										 ajkspk.dob,
										 ajkspk.usia,
										 ajkspk.plafond,
										 ajkspk.tglakad,
										 ajkspk.tenor,
										 ajkspk.tglakhir,
										 ajkspk.premi,
										 ajkspk.em,
										 ajkspk.premiem,
										 IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
										 /*IF(ajkspk.em IS NULL, ajkspk.nettpremi, (ajkspk.nettpremi + (ajkspk.nettpremi * ajkspk.em / 100))) AS nettpremi,*/
										 ajkcabang.`name` AS cabang,
										 DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
										 userinput.firstname AS namauserinput
									FROM ajkspk
									INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
									INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
									INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
									INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
									INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
									WHERE ajkspk.id !="" '.$statusSPK.' '.$q___SPK.' '.$spknomor.' '.$spknama.' AND ajkpolis.typemedical="SPK"
									ORDER BY ajkspk.id DESC');
	//WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
while ($metDataSPK_ = mysql_fetch_array($metDataSPK)) {
if ($metDataSPK_['statusspk'] == "Request") {
	$metStatus='<span class="label label-warning">'.$metDataSPK_['statusspk'].'</span>';
}elseif ($metDataSPK_['statusspk'] == "Batal" OR $metDataSPK_['statusspk'] == "Tolak") {
	$metStatus='<span class="label label-danger">'.$metDataSPK_['statusspk'].'</span>';
}else{
	$metStatus='<span class="label label-primary">'.$metDataSPK_['statusspk'].'</span>';
}

if ($metDataSPK_['statusspk']=="Proses") {
	if ($metDataSPK_['em']==NULL) {
		$nilaiEM = '<a href="ajk.php?re=spk&dt=em&spk='.$thisEncrypter->encode($metDataSPK_['id']).'" title="input extra premium"><span class="label label-danger"><strong>+</strong></span></a>';
		$editEMSPK_ = '';
	}else{
		$nilaiEM = '<span class="label label-success">'.duit($metDataSPK_['em']).'%</span>';
		$editEMSPK_ = '<a href="ajk.php?re=spk&dt=em&edt=emedt&spk='.$thisEncrypter->encode($metDataSPK_['id']).'" title="input extra premium"><span class="label label-inverse"><strong>Edit</strong></span></a>';
	}
}else{
	if ($metDataSPK_['em']==NULL) {
		$nilaiEM = '';
	}else{
		$nilaiEM = '<span class="label label-success">'.duit($metDataSPK_['em']).'%</span>';
	}
	$editEMSPK_ ='<a href="ajk.php?re=spk&dt=spkedt&spk='.$thisEncrypter->encode($metDataSPK_['id']).'" title="input data SPK"><span class="label label-warning"><strong>Edit</strong></span></a>';
}

$nettpremidebiturspk = $metDataSPK_['premi'] + $metDataSPK_['premiem'];

echo '<tr>
	   	<td align="center">'.++$no.'</td>
	   	<td align="center"><span class="checkbox custom-checkbox">
							<input type="checkbox" name="metSPK[]" id="'.$metDataSPK_['id'].'" value="'.$metDataSPK_['id'].'" />
                			<label for="'.$metDataSPK_['id'].'"></label>
                		   </span>
		</td>
	   	<td>'.$editEMSPK_.'</td>
	   	<td>'.$metDataSPK_['broker'].'</td>
	   	<td>'.$metDataSPK_['perusahaan'].'</td>
	   	<td align="center">'.$metDataSPK_['produk'].'</td>
	   	<td>'.$metStatus.'</td>
	   	<td align="center">'.$metDataSPK_['nomorspk'].'</td>
	   	<td><a href="ajk.php?re=spk&dt=viewSPK&gid='.$thisEncrypter->encode($metDataSPK_['id']).'">'.$metDataSPK_['nama'].'</a></td>
	   	<td align="center">'._convertDate($metDataSPK_['dob']).'</td>
	   	<td align="center">'.$metDataSPK_['usia'].'</td>
	   	<td align="center">'._convertDate($metDataSPK_['tglakad']).'</td>
	   	<td align="center">'.$metDataSPK_['tenor'].'</td>
	   	<td align="center">'._convertDate($metDataSPK_['tglakhir']).'</td>
	   	<td align="right">'.duit($metDataSPK_['plafond']).'</td>
   		<td align="right"><span class="label label-info">'.duit($metDataSPK_['premi']).'</span></td>
   		<td align="right">'.$nilaiEM.'</td>
   		<td align="right"><span class="label label-primary">'.duit($metDataSPK_['premiem']).'</span></td>
   		<td align="right"><span class="label label-success">'.duit($nettpremidebiturspk).'</span></td>
	   	<td align="center">'.$metDataSPK_['namauserinput'].'<br />'._convertDate($metDataSPK_['tglinput']).'</td>
	   	<td>'.$metDataSPK_['cabang'].'</td>
	    </tr>';
		}
		echo '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="SPK"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Start"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="End"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Plafond"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Premium"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="EM"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Premium EM"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Nett Premium"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="User"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
		</tr>
		</tfoot></table>
		<Center>'.$ApprovalSPK.'</Center>
		</form>
		</div>';
}else{
echo '<div class="alert alert-dismissable alert-warning">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
      <strong>There is no data to be verified !</strong>.
      </div>';
}
echo '</div>
		</div>
	</div>';
	;
	break;

case "em":
$metSPK = mysql_fetch_array($database->doQuery('SELECT ajkspk.id,
													   ajkspk.idbroker,
													   ajkspk.idpartner,
													   ajkspk.idproduk,
													   ajkcobroker.`name` AS broker,
													   ajkcobroker.logo AS brokerlogo,
													   ajkclient.`name` AS perusahaan,
													   ajkclient.logo AS perusahaanlogo,
													   ajkpolis.produk,
													   ajkcabang.`name` AS cabang,
													   ajkspk.nomorspk,
													   ajkspk.statusspk,
													   ajkspk.nomorktp,
													   ajkspk.nama,
													   IF(ajkspk.jeniskelamin="M", "Male", "Female") AS gender,
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
													   ajkspk.em,
													   ajkspk.ketem,
													   ajkspk.premiem,
										 			   IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
													   /*IF(ajkspk.em IS NULL, ajkspk.nettpremi, (ajkspk.nettpremi + (ajkspk.nettpremi * ajkspk.em / 100))) AS nettpreminya,*/
													   ajkspk.tinggibadan,
													   ajkspk.beratbadan,
													   ajkspk.tekanandarah,
													   ajkspk.nadi,
													   ajkspk.pernafasan,
													   ajkspk.guladarah,
													   ajkspk.tglperiksa,
													   ajkspk.dokterpemeriksa,
													   ajkspk.dokterpertanyaan,
													   ajkspk.pertanyaanketerangan,
													   ajkspk.doktercatatan,
													   ajkspk.dokterkesimpulan,
													   ajkspk.photodebitur1,
													   ajkspk.photodebitur2,
													   ajkspk.photoktp,
													   ajkspk.photosk,
													   ajkspk.ttddebitur,
													   ajkspk.ttdmarketing,
													   ajkspk.photobydokter,
													   ajkspk.ttddokter,
													   userinput.firstname AS namainput,
													   DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
													   dokter.firstname AS namadokter,
													   DATE_FORMAT(ajkspk.medical_date, "%Y-%m-%d") AS tgldokter,
													   userapprove.firstname AS namaapprove,
													   DATE_FORMAT(ajkspk.approve_date, "%Y-%m-%d") AS tglapprove
												FROM ajkspk
												INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
												INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
												INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
												INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
												INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
												LEFT JOIN useraccess AS dokter ON ajkspk.dokterpemeriksa = dokter.id
												LEFT JOIN useraccess AS userapprove ON ajkspk.approve_by = userapprove.id
												WHERE ajkspk.id = "'.$thisEncrypter->decode($_REQUEST['spk']).'" '.$q___SPK.''));
		if ($metSPK['statusspk']=="Request") {
			$_statusdata = '<button type="button" class="btn btn-warning btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
		}elseif ($metSPK['statusspajk']=="Survey") {
			$_statusdata = '<button type="button" class="btn btn-inverse btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
		}elseif ($metSPK['statusspajk']=="Approved") {
			$_statusdata = '<button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
		}elseif ($metSPK['statusspajk']=="Aktif") {
			$_statusdata = '<button type="button" class="btn btn-success btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
		}else{
			$_statusdata = '<button type="button" class="btn btn-danger btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
		}

		if ($metSPK['statusspajk']=="Request") {
			$photoSPKnya = '';
		}else{
			$photoSPKnya = '<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur2'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
							  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photoktp'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
							  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photosk'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>';
		}

		if ($metSPK['ttddebitur']!="") 		{	$ttddebitur_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddebitur_ = '';	}
		if ($metSPK['ttdmarketing']!="")	{	$ttdmarketing_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttdmarketing_ = '';	}
		if ($metSPK['ttddokter']!="") 		{	$ttddokter_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddokter'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddokter'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddokter_ = '';	}

echo '<div class="page-header-section"><h2 class="title semibold">FORM SPK (EM)</h2></div>
	<div class="page-header-section">
	<div class="toolbar"><a href="ajk.php?re=spk&dt=verf">'.BTN_BACK.'</a></div>
	</div>
</div>';
	if ($_REQUEST['met']=="saveEM") {
		$premiEMnya = $metSPK['premi'] * $_REQUEST['met_nilai_em'] / 100;
		$metEM = $database->doQuery('UPDATE ajkspk SET em="'.$_REQUEST['met_nilai_em'].'", premiem="'.$premiEMnya.'", ketem="'.$_REQUEST['catatanem'].'", em_by="'.$q['id'].'", em_date="'.$futgl.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['spk']).'"');
		header('location:ajk.php?re=spk&dt=verf');
	}

$nettpremidebiturspk = $metSPK['premi'] + $metSPK['premiem'];
echo '<div class="row">
	<div class="col-lg-12">
		<div class="tab-content">
	    	<div class="tab-pane active" id="profile">
	        <form method="post" class="panel form-horizontal form-bordered" name="form-profile" action="#" data-parsley-validate enctype="multipart/form-data">
			<input type="hidden" name="spk" value="'.$thisEncrypter->encode($metSPK['id']).'">
			<div class="panel-body pt0 pb0">
	        	<div class="form-group header bgcolor-default">
	            	<div class="col-md-6">
	            	<ul class="list-table">
	            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['brokerlogo'].'" alt="" width="75px"></li>
						<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['broker'].'</h4></li>
					</ul>
					</div>
					<div class="col-md-6">
	            	<ul class="list-table">
						<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['perusahaan'].'</h4></li>
						<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['perusahaanlogo'].'" alt="" width="75px"></li>
					</ul>
					</div>
	            </div>
				<div class="form-group">
	            	<div class="col-xs-12 col-sm-12 col-md-4">
							<div class="col-sm-5"><a href="javascript:void(0);">Product</a></div><div class="col-sm-7">'.$metSPK['produk'].'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Status</a></div><div class="col-sm-7">'.$_statusdata.'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Branch</a></div><div class="col-sm-7">'.$metSPK['cabang'].'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Debitur</h4></div>
							<div class="col-sm-5"><a href="javascript:void(0);">SPK Number</a></div><div class="col-sm-7"><button type="button" class="btn btn-success btn-xs"><strong>'.$metSPK['nomorspk'].'&nbsp;</strong></button></div>
							<div class="col-sm-5"><a href="javascript:void(0);">K.T.P</a></div><div class="col-sm-7">'.$metSPK['nomorktp'].'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Member</a></div><div class="col-sm-7">'.$metSPK['nama'].'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">D.O.B</a></div><div class="col-sm-7">'._convertDate($metSPK['dob']).'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Gender</a></div><div class="col-sm-7">'.$metSPK['gender'].'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Age</a></div><div class="col-sm-7">'.$metSPK['usia'].' years&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Address Member</a></div><div class="col-sm-7">'.$metSPK['alamat'].'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Occupation</a></div><div class="col-sm-7">'.$metSPK['pekerjaan'].'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-danger mt0 mb5">Data Medical</h4></div>
							<div class="col-sm-5"><a href="javascript:void(0);">Tinggi/Berat Badan</a></div><div class="col-sm-7">'.$metSPK['tinggibadan'].'/'.$metSPK['beratbadan'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Tekanan Darah</a></div><div class="col-sm-7">'.$metSPK['tekanandarah'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Nadi</a></div><div class="col-sm-7">'.$metSPK['nadi'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Pernafasan</a></div><div class="col-sm-7">'.$metSPK['pernafasan'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Gula Darah</a></div><div class="col-sm-7">'.$metSPK['guladarah'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Tanggal Pemeriksa</a></div><div class="col-sm-7">'._convertDate($metSPK['tglperiksa']).' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Dokter Pemeriksa</a></div><div class="col-sm-7">'.$metSPK['namadokter'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Catatan Dokter</a></div><div class="col-sm-7">'.$metSPK['doktercatatan'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Kesimpulan Dokter</a></div><div class="col-sm-7">'.$metSPK['dokterkesimpulan'].' &nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data User</h4></div>
							<div class="col-sm-5"><a href="javascript:void(0);">User Input</a></div><div class="col-sm-7">'.$metSPK['namainput'].'  - '.$metSPK['tglinput'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">User Approve</a></div><div class="col-sm-7">'.$metSPK['namaapprove'].'  - '.$metSPK['tglapprove'].' &nbsp;</div>
	                </div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-7">&nbsp;</div>
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-7"><span type="button" class="btn btn-successor btn-xs mb5">&nbsp;</span></div>
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-7">&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-warning mt0 mb5">Data Insurance</h4></div>
							<div class="col-sm-5"><a href="javascript:void(0);">Plafond</a></div><div class="col-sm-7">'.duit($metSPK['plafond']).'&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Tenor</a></div><div class="col-sm-7">'.duit($metSPK['tenor']).' month&nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Date of Insurance</a></div><div class="col-sm-7">'._convertDate($metSPK['tglakad']).' to '._convertDate($metSPK['tglakhir']).' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">MPP (month)</a></div><div class="col-sm-7">'.$metSPK['mppbln'].' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Premium</a></div><div class="col-sm-7">'.duit($metSPK['premi']).' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">EM</a></div><div class="col-sm-7">'.duit($metSPK['em']).'% &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Premi EM</a></div><div class="col-sm-7">'.duit($metSPK['premiem']).' &nbsp;</div>
							<div class="col-sm-5"><a href="javascript:void(0);">Nett Premium</a></div><div class="col-sm-7">'.duit($nettpremidebiturspk).' &nbsp;</div>
							<div class="col-sm-5">&nbsp;</div><div class="col-sm-7">&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-danger mt0 mb5">Form Input Data EM (%)</h4></div>
							<div class="form-group">
	  						<div class="col-sm-10">';
        					if ($_REQUEST['edt']=="emedt") {
							$cekEditEM = mysql_fetch_array($database->doQuery('SELECT * FROM ajkspk WHERE id="'.$thisEncrypter->decode($_REQUEST['spk']).'"'));
       						echo '<div class="row mb5"><div class="col-sm-12"><input type="text" name="met_nilai_em" value="'.$cekEditEM['em'].'" placeholder="Percentage EM" required></div></div>
        						  <div class="row mb5"><div class="col-sm-12"><textarea name="catatanem" type="text" class="form-control" placeholder="Explanation Extra Premium" rows="10" required>'.$cekEditEM['ketem'].'</textarea></div></div>';

        					}else{
							echo '<div class="row mb5"><div class="col-sm-12"><input type="text" name="met_nilai_em" value="'.$_REQUEST['met_nilai_em'].'" placeholder="Percentage EM" required></div></div>
        						  <div class="row mb5"><div class="col-sm-12"><textarea name="catatanem" type="text" class="form-control" placeholder="Explanation Extra Premium" rows="10" required>'.$_REQUEST['catatanem'].'</textarea></div></div>';
        			  		}
					  echo '</div>
    						</div>
							<div><input type="hidden" name="met" value="saveEM">'.BTN_SUBMIT.'</div>';
	            echo '</div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
						<div class="row" id="shuffle-grid">
							<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($metSPK['tglinput']).'" data-title="background1">
    							<div class="thumbnail">
        							<div class="media">
									<div class="indicator"><span class="spinner"></span></div>
										<div class="overlay">
                						'.$photoSPKnya.'
										<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                						</div>
            						<span class="meta bottom darken">
            						<h5 class="nm semibold">'.$metSPK['nama'].' <br/><small><i class="ico-calendar2"></i> '._convertDate($metSPK['tglinput']).'</small></h5>
            						</span>
            						<img data-toggle="unveil" src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" data-src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" alt="Photo" width="100%" height="500"/>
        							</div>
    							</div>
							</div>
						</div>
						'.$ttddebitur_.' &nbsp; '.$ttdmarketing_.' &nbsp; '.$ttddokter_.'
					</div>
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';

	;
	break;

case "spkedt":
$metSPK = mysql_fetch_array($database->doQuery('SELECT ajkspk.id,
													   ajkspk.idbroker,
													   ajkspk.idpartner,
													   ajkspk.idproduk,
													   ajkcobroker.`name` AS broker,
													   ajkcobroker.logo AS brokerlogo,
													   ajkclient.`name` AS perusahaan,
													   ajkclient.logo AS perusahaanlogo,
													   ajkpolis.produk,
													   ajkpolis.plafondend,
													   ajkpolis.ageend,
													   ajkpolis.agecalculateday,
													   ajkpolis.byrate,
													   ajkpolis.calculatedrate,
													   ajkpolis.lastdayinsurance,
													   ajkpolis.mpptype,
													   ajkpolis.mppend,
													   ajkpolis.adminfee,
													   ajkpolis.diskon,
													   ajkcabang.`name` AS cabang,
													   ajkspk.nomorspk,
													   ajkspk.statusspk,
													   ajkspk.nomorktp,
													   ajkspk.nama,
													   ajkspk.jeniskelamin,
													   IF(ajkspk.jeniskelamin="M", "Male", "Female") AS gender,
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
													   ajkspk.em,
													   ajkspk.ketem,
													   ajkspk.premiem,
													   ajkspk.nettpremi,
													   IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
													   /*IF(ajkspk.em IS NULL, ajkspk.nettpremi, (ajkspk.nettpremi + (ajkspk.nettpremi * ajkspk.em / 100))) AS nettpreminya,*/
													   ajkspk.tinggibadan,
													   ajkspk.beratbadan,
													   ajkspk.tekanandarah,
													   ajkspk.nadi,
													   ajkspk.pernafasan,
													   ajkspk.guladarah,
													   ajkspk.tglperiksa,
													   ajkspk.dokterpemeriksa,
													   ajkspk.dokterpertanyaan,
													   ajkspk.pertanyaanketerangan,
													   ajkspk.doktercatatan,
													   ajkspk.dokterkesimpulan,
													   ajkspk.photodebitur1,
													   ajkspk.photodebitur2,
													   ajkspk.photoktp,
													   ajkspk.photosk,
													   ajkspk.ttddebitur,
													   ajkspk.ttdmarketing,
													   ajkspk.photobydokter,
													   ajkspk.ttddokter,
													   userinput.firstname AS namainput,
													   DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
													   dokter.firstname AS namadokter,
													   DATE_FORMAT(ajkspk.medical_date, "%Y-%m-%d") AS tgldokter,
													   userapprove.firstname AS namaapprove,
													   DATE_FORMAT(ajkspk.approve_date, "%Y-%m-%d") AS tglapprove
												FROM ajkspk
												INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
												INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
												INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
												INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
												INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
												LEFT JOIN useraccess AS dokter ON ajkspk.dokterpemeriksa = dokter.id
												LEFT JOIN useraccess AS userapprove ON ajkspk.approve_by = userapprove.id
												WHERE ajkspk.id = "'.$thisEncrypter->decode($_REQUEST['spk']).'" '.$q___SPK.''));
	if ($metSPK['statusspk']=="Request") {
		$_statusdata = '<button type="button" class="btn btn-warning btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
	}elseif ($metSPK['statusspajk']=="Survey") {
		$_statusdata = '<button type="button" class="btn btn-inverse btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
	}elseif ($metSPK['statusspajk']=="Approve") {
		$_statusdata = '<button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
	}elseif ($metSPK['statusspajk']=="Aktif") {
		$_statusdata = '<button type="button" class="btn btn-success btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
	}else{
		$_statusdata = '<button type="button" class="btn btn-danger btn-xs mb5"><strong>'.$metSPK['statusspk'].'</strong></button>';
	}

	if ($metSPK['statusspajk']=="Request") {
		$photoSPKnya = '';
	}else{
		$photoSPKnya = '<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur2'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
						  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photoktp'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
						  <div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photosk'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>';
	}

	if ($metSPK['ttddebitur']!="") 		{	$ttddebitur_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddebitur'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddebitur_ = '';	}
	if ($metSPK['ttdmarketing']!="")	{	$ttdmarketing_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttdmarketing'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttdmarketing_ = '';	}
	if ($metSPK['ttddokter']!="") 		{	$ttddokter_= '<center><div class="col-md-4"><a href="../myFiles/_ajk/'.$metSPK['ttddokter'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../myFiles/_ajk/'.$metSPK['ttddokter'].'" alt="" class="img-circle" width="100" height="100"/></a></div>';	}	else{	$ttddokter_ = '';	}

		echo '<div class="page-header-section"><h2 class="title semibold">FORM SPK (EM)</h2></div>
	<div class="page-header-section">
	<div class="toolbar"><a href="ajk.php?re=spk&dt=verf">'.BTN_BACK.'</a></div>
	</div>
</div>';
//SAVE EDIT SPK
if ($_REQUEST['mets']=="saveEdSPK") {
	function birthday($birthday, $today){
		$age = strtotime($birthday);
		$now = strtotime($today);
		if($age === false){		return false;	}
		list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
		list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
		$diifyear = $y2 - $y1;
		if((int)($m1) > (int)($m2))
		{
			$diifyear = $diifyear -1;
		}
		$clinetage = $y1 + $diifyear;
		$birthdate = $clinetage.'-'.$m1.'-'.$d1;
		$now = $y2.'-'.$m2.'-'.$d2;
		$diffday = (strtotime($birthdate) - strtotime($now))/  ( 60 * 60 * 24 )*-1;
		if($diffday >= $metSPK['agecalculateday'])
		{	$diifyear = $diifyear + 1;
		}elseif($diffday < $metSPK['agecalculateday'])
		{	$diifyear = $diifyear + 1;
		}else{	$diifyear = $diifyear;
		}
		return $diifyear;
	}
	$spkusia = birthday(_convertDateEng2($_REQUEST['metdob']), _convertDateEng2($_REQUEST['mettglakad']));
	//CEK USIA DAN PLAFOND
	if ($spkusia > $metSPK['ageend']) {
		$errorspk = '<div class="alert alert-dismissable alert-danger">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        			  <strong>Error!</strong> Age of the member exceeeds the age limit product setup.
    				  </div>';
	}elseif ($_REQUEST['metplafond'] > $metSPK['plafondend']) {
		$errorplafond = '<div class="alert alert-dismissable alert-danger">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        			  <strong>Error!</strong> Plafond of the member exceeeds the plafond limit product setup.
    				  </div>';
	}else{	}

	if($errorspk OR $errorplafond) {

	}
	else{
		if ($metSPK['mpptype']=="Y") {
			/*RATE SPK	............	RATE SPK*/
		}else{
			if ($metSPK['byrate']=="Age"){
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$metSPK['idbroker'].'" AND idclient="'.$metSPK['idpartner'].'" AND idpolis="'.$metSPK['idproduk'].'" AND '.$spkusia.' BETWEEN agefrom AND ageto AND '.$_REQUEST['mettenor'].' BETWEEN tenorfrom AND tenorto'));
			}else{
				$metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremi WHERE idbroker="'.$metSPK['idbroker'].'" AND idclient="'.$metSPK['idpartner'].'" AND idpolis="'.$metSPK['idproduk'].'" AND '.$_REQUEST['mettenor'].' BETWEEN tenorfrom AND tenorto'));
			}
		}
	//echo $metRate['id'].'<br />';
	$spkpremi = $_REQUEST['metplafond'] * $metRate['rate'] / $metSPK['calculatedrate'];
	$spknettpremi = $spkpremi + $metRate['adminfee'] - $metRate['diskon'];
	$tglakhir = Date("Y-m-d", strtotime(_convertDateEng2($_REQUEST['mettglakad'])." +".$_REQUEST['mettenor']." Month -".$metSPK['lastdayinsurance']." Day"));
	//CEK USIA DAN PLAFOND
	$metUpdSPK = $database->doQuery('UPDATE ajkspk SET nomorktp="'.$_REQUEST['metnomorktp'].'",
													   idrate="'.$metRate['id'].'",
													   nama="'.$_REQUEST['metnama'].'",
													   jeniskelamin="'.$_REQUEST['gender'].'",
													   dob="'._convertDateEng2($_REQUEST['metdob']).'",
													   usia="'.$spkusia.'",
													   alamat="'.$_REQUEST['metaddress'].'",
													   pekerjaan="'.$_REQUEST['metpekerjaan'].'",
													   plafond="'.$_REQUEST['metplafond'].'",
													   tglakad="'._convertDateEng2($_REQUEST['mettglakad']).'",
													   tglakhir="'.$tglakhir.'",
													   tenor="'.$_REQUEST['mettenor'].'",
													   mppbln="'.$_REQUEST['metmppbln'].'",
													   premi="'.$spkpremi.'",
													   nettpremi="'.$spknettpremi.'",
													   tinggibadan="'.$_REQUEST['mettinggi'].'",
													   beratbadan="'.$_REQUEST['metberat'].'",
													   tekanandarah="'.$_REQUEST['metdarah1'].'/'.$_REQUEST['metdarah2'].'",
													   nadi="'.$_REQUEST['metnadi'].'",
													   pernafasan="'.$_REQUEST['metnafas'].'",
													   guladarah="'.$_REQUEST['metguladarah'].'",
													   doktercatatan="'.$_REQUEST['metcatatan'].'",
													   dokterkesimpulan="'.$_REQUEST['metkesimpulan'].'",
													   tglperiksa="'._convertDateEng2($_REQUEST['mettglperiksa']).'",
													   update_by="'.$q['id'].'",
													   update_date="'.$futgl.'"
													   WHERE id="'.$metSPK['id'].'"');

	echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=spk&dt=verf">
		  <div class="alert alert-dismissable alert-success">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		  <strong>Success!</strong> Edit data SPK.
		  </div>';
	}
}
//SAVE EDIT SPK
$nettpremidebiturspk = $metSPK['premi'] + $metSPK['premiem'];
$tekanandarah = explode("/", $metSPK['tekanandarah']);
echo '<div class="row">
	<div class="col-lg-12">
	<div class="tab-content">
	    	<div class="tab-pane active" id="profile">
	        <form method="post" class="panel form-horizontal form-bordered" name="form-profile" action="#" data-parsley-validate enctype="multipart/form-data">
		<input type="hidden" name="spk" value="'.$thisEncrypter->encode($metSPK['id']).'">
		<div class="panel-body pt0 pb0">
	        	<div class="form-group header bgcolor-default">
	            	<div class="col-md-6">
	            	<ul class="list-table">
	            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['brokerlogo'].'" alt="" width="75px"></li>
					<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['broker'].'</h4></li>
				</ul>
				</div>
				<div class="col-md-6">
	            	<ul class="list-table">
					<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metSPK['perusahaan'].'</h4></li>
					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metSPK['perusahaanlogo'].'" alt="" width="75px"></li>
				</ul>
				</div>
	            </div>
			<div class="form-group">
			'.$errorspk.'
	            	<div class="col-xs-12 col-sm-12 col-md-4">
						<div class="col-sm-5"><a href="javascript:void(0);">Product</a></div><div class="col-sm-7">'.$metSPK['produk'].'&nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Status</a></div><div class="col-sm-7">'.$_statusdata.'&nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Branch</a></div><div class="col-sm-7">'.$metSPK['cabang'].'&nbsp;</div>
						<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Debitur</h4></div>
						<div class="col-sm-5"><a href="javascript:void(0);">SPK Number</a></div><div class="col-sm-7"><button type="button" class="btn btn-success btn-xs"><strong>'.$metSPK['nomorspk'].'&nbsp;</strong></button></div>
						<div class="col-sm-5"><a href="javascript:void(0);">K.T.P <font color="red">*</font></a></div><div class="col-sm-7"><input name="metnomorktp" value="'.$metSPK['nomorktp'].'" type="text" class="form-control" placeholder="KTP" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Member <font color="red">*</font></a></div><div class="col-sm-7"><input name="metnama" value="'.$metSPK['nama'].'" type="text" class="form-control" placeholder="Name" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">D.O.B <font color="red">*</font></a></div><div class="col-sm-7"><input name="metdob" value="'._convertDateEng3($metSPK['dob']).'" id="datepicker4" type="text" class="form-control" placeholder="Date of birth" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Gender <font color="red">*</font></a></div><div class="col-sm-7"><span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($metSPK['jeniskelamin'], "M").' name="gender" id="customradio1" value="M" required><label for="customradio1">&nbsp;&nbsp;Male&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metSPK['jeniskelamin'], "F").' name="gender" id="customradio2" value="F" required><label for="customradio2">&nbsp;&nbsp;Female</label>
            </span></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Age</a></div><div class="col-sm-7">'.$metSPK['usia'].' years&nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Address Member <font color="red">*</font></a></div><div class="col-sm-7"><textarea name="metaddress" rows="5" type="text" class="form-control" placeholder="Address" required>'.$metSPK['alamat'].'</textarea></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Occupation <font color="red">*</font></a></div><div class="col-sm-7"><input name="metpekerjaan" value="'.$metSPK['pekerjaan'].'" type="text" class="form-control" placeholder="Occupation" required></div>
						<div class="col-md-12"><h4 class="semibold text-danger mt0 mb5">Data Medical</h4></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Tinggi/Berat Badan <font color="red">*</font></a></div><div class="col-sm-3"><input name="mettinggi" value="'.$metSPK['tinggibadan'].'" type="text" class="form-control" placeholder="Height" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div><div class="col-sm-1">/</div><div class="col-sm-3"><input name="metberat" value="'.$metSPK['beratbadan'].'" type="text" class="form-control" placeholder="Weight" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Tekanan Darah <font color="red">*</font></a></div><div class="col-sm-3"><input name="metdarah1" value="'.$tekanandarah[0].'" type="text" class="form-control" placeholder="" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div><div class="col-sm-1">/</div><div class="col-sm-3"><input name="metdarah2" value="'.$tekanandarah[1].'" type="text" class="form-control" placeholder="" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Nadi <font color="red">*</font></a></div><div class="col-sm-7"><input name="metnadi" value="'.$metSPK['nadi'].'" type="text" class="form-control" placeholder="Nadi" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Pernafasan <font color="red">*</font></a></div><div class="col-sm-7"><input name="metnafas" value="'.$metSPK['pernafasan'].'" type="text" class="form-control" placeholder="Pernafasan" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Gula Darah <font color="red">*</font></a></div><div class="col-sm-7"><input name="metguladarah" value="'.$metSPK['guladarah'].'" type="text" class="form-control" placeholder="Gula Darah" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Tanggal Pemeriksa <font color="red">*</font></a></div><div class="col-sm-7"><input name="mettglperiksa" value="'._convertDateEng3($metSPK['tglperiksa']).'" id="datepicker2" type="text" class="form-control" placeholder="Tanggal Periksa" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Dokter Pemeriksa <font color="red">*</font></a></div><div class="col-sm-7">'.$metSPK['namadokter'].' &nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Catatan Dokter <font color="red">*</font></a></div><div class="col-sm-7"><textarea name="metcatatan" rows="5" type="text" class="form-control" placeholder="Catatan Dokter" required>'.$metSPK['doktercatatan'].'</textarea></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Kesimpulan Dokter <font color="red">*</font></a></div><div class="col-sm-7"><textarea name="metkesimpulan" rows="5" type="text" class="form-control" placeholder="Kesimpulan Dokter" required>'.$metSPK['dokterkesimpulan'].'</textarea></div>
						<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data User</h4></div>
						<div class="col-sm-5"><a href="javascript:void(0);">User Input</a></div><div class="col-sm-7">'.$metSPK['namainput'].'  - '.$metSPK['tglinput'].' &nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">User Approve</a></div><div class="col-sm-7">'.$metSPK['namaapprove'].'  - '.$metSPK['tglapprove'].' &nbsp;</div>
	                </div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-4">&nbsp;</div><div class="col-sm-1" align="center"><input type="hidden" name="mets" value="saveEdSPK">'.BTN_EDITSPK.'</div>
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-7"><span type="button" class="btn btn-successor btn-xs mb5">&nbsp;</span></div>
	                		<div class="col-sm-5">&nbsp;</div><div class="col-sm-7">&nbsp;</div>
						<div class="col-md-12"><h4 class="semibold text-warning mt0 mb5">Data Insurance</h4></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Plafond <font color="red">*</font></a></div><div class="col-sm-7"><input name="metplafond" value="'.$metSPK['plafond'].'" type="text" class="form-control" placeholder="Plafond" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Tenor <font color="red">*</font></a></div><div class="col-sm-7"><input name="mettenor" value="'.$metSPK['tenor'].'" type="text" class="form-control" placeholder="Tenor (month)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Date of Insurance <font color="red">*</font></a></div><div class="col-sm-7"><input name="mettglakad" value="'._convertDateEng3($metSPK['tglakad']).'" id="datepicker3" type="text" class="form-control" placeholder="Tanggal Asuransi" required></div>
						<div class="col-sm-5"><a href="javascript:void(0);">MPP (month)</a></div><div class="col-sm-7"><input name="metmppbln" value="'.$metSPK['mppbln'].'" type="text" class="form-control" placeholder="MPP (month)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></div>
						<div class="col-sm-5"><a href="javascript:void(0);">Premium</a></div><div class="col-sm-7">'.duit($metSPK['premi']).' &nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">EM</a></div><div class="col-sm-7">'.duit($metSPK['em']).'% &nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Premi EM</a></div><div class="col-sm-7">'.duit($metSPK['premiem']).' &nbsp;</div>
						<div class="col-sm-5"><a href="javascript:void(0);">Nett Premium</a></div><div class="col-sm-7"><button type="button" class="btn btn-info btn-xs mb5"><strong>'.duit($nettpremidebiturspk).'</strong></button></div>';
	echo '</div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
					<div class="row" id="shuffle-grid">
						<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($metSPK['tglinput']).'" data-title="background1">
    							<div class="thumbnail">
        							<div class="media">
								<div class="indicator"><span class="spinner"></span></div>
									<div class="overlay">
                						'.$photoSPKnya.'
									<div class="toolbar"><a href="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                						</div>
            						<span class="meta bottom darken">
            						<h5 class="nm semibold">'.$metSPK['nama'].' <br/><small><i class="ico-calendar2"></i> '._convertDate($metSPK['tglinput']).'</small></h5>
            						</span>
            						<img data-toggle="unveil" src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" data-src="../myFiles/_ajk/'.$metSPK['photodebitur1'].'" alt="Photo" width="100%" height="500"/>
        							</div>
    							</div>
						</div>
					</div>
					'.$ttddebitur_.' &nbsp; '.$ttdmarketing_.' &nbsp; '.$ttddokter_.'
				</div>
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';
	;
	break;


case "approvespk":
echo '<div class="page-header-section"><h2 class="title semibold">Verification SPK (EM)</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
if (!$_REQUEST['metSPK']) {
echo '<div class="alert alert-dismissable alert-danger">
	<strong>Error!</strong> Checklist data SPK to approval for Declaration.
    </div>';
}else{
foreach ($_REQUEST['metSPK'] as $spk) {
	$ApproveSPK = $database->doQuery('UPDATE ajkspk SET statusspk="Approve",approveem_by="'.$q['id'].'", approveem_date="'.$futgl.'" WHERE id="'.$spk.'"');
}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=spk&dt=verf">
		<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Success!</strong> Approve data SPK.
    </div>';
}
echo '</div>
</div>';
	;
	break;

case "approvespkaktif":
echo '<div class="page-header-section"><h2 class="title semibold">Verification SPK (EM)</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">';
if (!$_REQUEST['metSPK']) {
echo '<div class="alert alert-dismissable alert-danger">
	<strong>Error!</strong> Checklist data SPK to approval for Declaration.
    </div>';
}else{
foreach ($_REQUEST['metSPK'] as $spk) {
$metApproveSPK = mysql_fetch_array($database->doQuery('SELECT * FROM ajkspk WHERE id="'.$spk.'"'));
$nettpremispkdebitur = $metApproveSPK['premi'] + $metApproveSPK['premiem'];
$ApproveSPK = $database->doQuery('UPDATE ajkspk SET statusspk="Aktif",nettpremi="'.$nettpremispkdebitur.'",approvespk_by="'.$q['id'].'", approvespk_date="'.$futgl.'" WHERE id="'.$spk.'"');

//NOTIFIKASI TOKEN STAFF MARKETING TABLET
	$qtoken = mysql_query("SELECT UserToken FROM user_mobile_token WHERE UserID = '".$metApproveSPK['input_by']."' AND packagename='com.biosajk.marketing'");
	$regTokens = array();
	while ($rtoken = mysql_fetch_assoc($qtoken)) {
		$notoken = $rtoken['UserToken'];

		$nomorspk = $metApproveSPK['nomorspk'];
		$nama = $metApproveSPK['nama'];
		$idspk = $metApproveSPK['id'];

		$data = array("post_title" => "Nomor $nomorspk Telah disetujui",
			"post_msg" => "Data SPK nomor $nomorspk atas nama $nama telah disetujui oleh dokter underwriting.",
			"datamsg" =>"SPK",
			//"datastatus" => $statusspk,
			"datastatus" => "Aktif",
			"dataformid" => $idspk,
			"dataidspk" => $nomorspk);
		_sendnotif($notoken,$data);
	}
//NOTIFIKASI TOKEN STAFF MARKETING TABLET
}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=spk&dt=verf">
	<div class="alert alert-dismissable alert-success">
	<strong>Success!</strong> Approve data SPK to Upload Declaration.
    </div>';
}
echo '</div>
</div>';
	;
	break;


	default:
echo '<div class="page-header-section"><h2 class="title semibold">Members SPK</h2></div>
      	<div class="page-header-section">
		</div>
      </div>
      <div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%">No</th>
	<th>Broker</th>
	<th>Partner</th>
	<th>Product</th>
	<th width="1%">Status</th>
	<th width="1%">SPK</th>
	<th width="1%">Name</th>
	<th width="1%">DOB</th>
	<th width="1%">Age</th>
	<th width="1%">Start Insurance</th>
	<th width="1%">Tenor</th>
	<th width="1%">End Insurance</th>
	<th width="1%">Plafond</th>
	<th width="1%">Premium</th>
	<th width="1%">EM(%)</th>
	<th width="1%">Premium EM</th>
	<th width="1%">Nett Premium</th>
	<th width="1%">User</th>
	<th width="1%">Input Date</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
$metDataSPK = $database->doQuery('SELECT ajkspk.id,
										 ajkspk.idbroker,
										 ajkspk.idpartner,
										 ajkspk.idproduk,
										 ajkcobroker.`name` AS broker,
										 ajkclient.`name` AS perusahaan,
										 ajkpolis.produk AS produk,
										 ajkspk.nomorspk,
										 ajkspk.statusspk,
										 ajkspk.nama,
										 IF(ajkspk.jeniskelamin="M", "Male", "Female") AS gender,
										 ajkspk.dob,
										 ajkspk.usia,
										 ajkspk.plafond,
										 ajkspk.tglakad,
										 ajkspk.tenor,
										 ajkspk.tglakhir,
										 ajkspk.premi,
										 ajkspk.em,
										 ajkspk.premiem,
										 IF(ajkspk.nettpremi IS NULL, ajkspk.premi, ajkspk.nettpremi) AS totalpremiSPK,
										 /*IF(ajkspk.em IS NULL, ajkspk.nettpremi, (ajkspk.nettpremi + (ajkspk.nettpremi * ajkspk.em / 100))) AS nettpremi,*/
										 ajkcabang.`name` AS cabang,
										 DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput,
										 userinput.firstname AS namauserinput
									FROM ajkspk
									INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
									INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
									INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
									INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
									INNER JOIN useraccess AS userinput ON ajkspk.input_by = userinput.id
									WHERE ajkspk.id !="" '.$q___SPK.'
									ORDER BY ajkspk.id DESC');
//WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
while ($metDataSPK_ = mysql_fetch_array($metDataSPK)) {
if ($metDataSPK_['statusspk'] == "Request") {
	$metStatus='<span class="label label-warning">'.$metDataSPK_['statusspk'].'</span>';
}elseif ($metDataSPK_['statusspk'] == "Pending") {
	$metStatus='<span class="label label-inverse">'.$metDataSPK_['statusspk'].'</span>';
}elseif ($metDataSPK_['statusspk'] == "Proses") {
	$metStatus='<span class="label label-info">'.$metDataSPK_['statusspk'].'</span>';
}elseif ($metDataSPK_['statusspk'] == "Approve" OR $metDataSPK_['statusspk'] == "PreApproval") {
	$metStatus='<span class="label label-primary">'.$metDataSPK_['statusspk'].'</span>';
}elseif ($metDataSPK_['statusspk'] == "Aktif") {
	$metStatus='<span class="label label-success">'.$metDataSPK_['statusspk'].'</span>';
}else{
	$metStatus='<span class="label label-danger">'.$metDataSPK_['statusspk'].'</span>';
}
if ($metDataSPK_['em']==NULL) {
	$nilaiEM = '';
}else{
	$nilaiEM = '<span class="label label-primary">'.duit($metDataSPK_['em'].'</span>');
}
$nettpremidebiturspk = $metDataSPK_['premi'] + $metDataSPK_['premiem'];
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metDataSPK_['broker'].'</td>
   	<td>'.$metDataSPK_['perusahaan'].'</td>
   	<td align="center">'.$metDataSPK_['produk'].'</td>
   	<td>'.$metStatus.'</td>
   	<td align="center">'.$metDataSPK_['nomorspk'].'</td>
   	<td><a href="ajk.php?re=spk&dt=viewSPK&gid='.$thisEncrypter->encode($metDataSPK_['id']).'">'.$metDataSPK_['nama'].'</a></td>
   	<td align="center">'._convertDate($metDataSPK_['dob']).'</td>
   	<td align="center">'.$metDataSPK_['usia'].'</td>
   	<td align="center">'._convertDate($metDataSPK_['tglakad']).'</td>
   	<td align="center">'.$metDataSPK_['tenor'].'</td>
   	<td align="center">'._convertDate($metDataSPK_['tglakhir']).'</td>
   	<td align="right"><strong>'.duit($metDataSPK_['plafond']).'</strong></td>
   	<td align="right"><span class="label label-info">'.duit($metDataSPK_['premi']).'</span></td>
   	<td align="right">'.$nilaiEM.'</td>
   	<td align="right"><span class="label label-primary">'.duit($metDataSPK_['premiem']).'</span></td>
   	<td align="right"><span class="label label-success">'.duit($nettpremidebiturspk).'</span></td>
   	<td align="center">'.$metDataSPK_['namauserinput'].'</td>
   	<td align="right">'._convertDate($metDataSPK_['tglinput']).'</td>
   	<td>'.$metDataSPK_['cabang'].'</td>
    </tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="SPK"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Start"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="End"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Plafond"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Premium"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="EM"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Premium EM"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Nett Premium"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="User"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
	</tr>
	</tfoot></table>
	</div>
	</div>
	</div>
</div>';
	;
} // switch

echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
function _sendnotif($registatoin_ids, $data) {
	//Google cloud messaging GCM-API url
	$url = 'https://fcm.googleapis.com/fcm/send';
	$fields = array(
		//'to' => "/topics/global",
		'to' => $registatoin_ids,
		'data' => $data


	);

	// Google Cloud Messaging GCM API Key
	define("GOOGLE_API_KEY", "AIzaSyCaRuBxKGCnya7dRTiuPph7q0sCv2Nc9sY");
	$headers = array(
	'Authorization: key=' . GOOGLE_API_KEY,
	'Content-Type: application/json'
);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}
?>
<script type="text/javascript">
checked=false;
function checkedAll (frm1) {
	var aa= document.getElementById('frm1');
	if (checked == false)	{	checked = true	}	else	{	checked = false	}
	for (var i =0; i < aa.elements.length; i++){ aa.elements[i].checked = checked;}
}
</script>
