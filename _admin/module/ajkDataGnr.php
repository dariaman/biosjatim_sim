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

case "viewGeneral":
$metGen = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.name AS broker,
													   ajkcobroker.logo AS logobroker,
													   ajkclient.name AS perusahaan,
													   ajkclient.logo AS logoperusahaan,
													   ajkpolis.produk,
													   useraccess.firstname AS userappraisal,
													   ajkumum.id,
													   ajkumum.idbroker,
													   ajkumum.idclient,
													   ajkumum.idproduk,
													   ajkumum.nomorpk,
													   ajkumum.nomorspajk,
													   ajkumum.statusspajk,
													   ajkumum.nama,
													   IF(ajkumum.jnskelamin="L", "Male", "Female") AS gender,
													   ajkumum.tgllahir,
													   ajkumum.ktp,
													   ajkumum.nilaiplafond,
													   ajkumum.tglakadapproval,
													   ajkumum.hp,
													   ajkumum.alamatdebitur,
													   ajkumum.alamatobjek,
													   ajkumum.nilaidiajukan,
													   ajkumum.photodebitur,
													   ajkpolis.idgeneral,
													   ajkgeneraltype.type,
													   ajkgeneraltype.kode,
													   ajkregional.name AS regional,
													   ajkcabang.name AS cabang
												FROM ajkumum
												INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
												INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
												INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
												INNER JOIN useraccess ON ajkumum.idusersurvey = useraccess.id
												INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
												INNER JOIN ajkregional ON ajkumum.idregional = ajkregional.er
												INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
												WHERE ajkumum.id = "'.$thisEncrypter->decode($_REQUEST['gid']).'"'));
$metAlamatMember = str_replace("#","<br />",$metGen['alamatdebitur']);
$metAlamatObjek = str_replace("#","<br />",$metGen['alamatobjek']);
if ($metGen['statusspajk']=="Request") {
	$_statusdata = '<button type="button" class="btn btn-warning btn-xs mb5"><strong>'.$metGen['statusspajk'].'</strong></button>';
}elseif ($metGen['statusspajk']=="Survey") {
	$_statusdata = '<button type="button" class="btn btn-inverse btn-xs mb5"><strong>'.$metGen['statusspajk'].'</strong></button>';
}elseif ($metGen['statusspajk']=="Approved") {
	$_statusdata = '<button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metGen['statusspajk'].'</strong></button>';
}elseif ($metGen['statusspajk']=="Aktif") {
	$_statusdata = '<button type="button" class="btn btn-success btn-xs mb5"><strong>'.$metGen['statusspajk'].'</strong></button>';
}else{
	$_statusdata = '<button type="button" class="btn btn-danger btn-xs mb5"><strong>'.$metGen['statusspajk'].'</strong></button>';
}
$dataDobjek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkumum WHERE id="'.$thisEncrypter->decode($_REQUEST['gid']).'"'));
$dataObjOkupasi = mysql_fetch_array($database->doQuery('SELECT keterangan FROM ajkgeneralkategori WHERE id="'.$dataDobjek['okupasi'].'"'));
$dataObjRegional = mysql_fetch_array($database->doQuery('SELECT name FROM ajkregional WHERE er="'.$dataDobjek['idregional'].'"'));
$dataObjCabang = mysql_fetch_array($database->doQuery('SELECT name FROM ajkcabang WHERE er="'.$dataDobjek['idcabang'].'"'));
if ($metGen['kode']=="KPR") {
	$totalappraisalkpr = $dataDobjek['nilaiappraisalbangunan'] + $dataDobjek['nilaiappraisalperabot'] + $dataDobjek['nilaiappraisalstok'] + $dataDobjek['nilaiappraisalmesin'];
	$DataObjekGeneral = '<div class="col-sm-3"><a href="javascript:void(0);">Luas Bangunan / Tanah</a></div><div class="col-sm-9">'.$dataDobjek['luastanah'].' / '.$dataDobjek['luasbangunan'].' (m2)</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Pertanggungan</a></div><div class="col-sm-9">'.$dataObjOkupasi['keterangan'].'&nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Tahun Pembangunan</a></div><div class="col-sm-9">'.duit($dataDobjek['luasbangunan']).' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Kelas Kontruksi</a></div><div class="col-sm-9">'.duit($dataDobjek['luasbangunan']).' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Konstruksi</a></div><div class="col-sm-2"><strong>Dinding</strong></div><div class="col-sm-7">: '.$dataDobjek['kontruksibangundinding'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Atap</strong></div><div class="col-sm-7">: '.$dataDobjek['kontruksibangunatap'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Lantai</strong></div><div class="col-sm-7">: '.$dataDobjek['kontruksibangunlantai'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Tiang</strong></div><div class="col-sm-7">: '.$dataDobjek['kontruksibanguntiang'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Batas Bangunan & Jarak</a></div><div class="col-sm-2"><strong>Kiri</strong></div><div class="col-sm-7">: '.$dataDobjek['jarakbangunkiri'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Kanan</strong></div><div class="col-sm-7">: '.$dataDobjek['jarakbangunkanan'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Depan</strong></div><div class="col-sm-7">: '.$dataDobjek['jarakbangundepan'].' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Belakang</strong></div><div class="col-sm-7">: '.$dataDobjek['jarakbangunbelakang'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Jenis Pemadam</a></div><div class="col-sm-9">'.$dataDobjek['jenispemadam'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Jenis Stok (Bila ada)</a></div><div class="col-sm-9">'.$dataDobjek['jenisstok'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Nilai Pertanggungan</a></div><div class="col-sm-2"><strong>Bangunan</strong></div><div class="col-sm-7">: '.duit($dataDobjek['nilaiappraisalbangunan']).' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Perabot</strong></div><div class="col-sm-7">: '.duit($dataDobjek['nilaiappraisalperabot']).' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Stok</strong></div><div class="col-sm-7">: '.duit($dataDobjek['nilaiappraisalstok']).' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><strong>Mesin</strong></div><div class="col-sm-7">: '.duit($dataDobjek['nilaiappraisalmesin']).' &nbsp;</div>
						 <div class="col-sm-3">&nbsp;</div><div class="col-sm-2"><h4 class="semibold text-danger mt0 mb5">Total</h4></div><div class="col-sm-7"><h4 class="semibold text-danger mt0 mb5">: '.duit($totalappraisalkpr).'</h4></div>';

	if ($dataDobjek['photo1']!="") {	$vPhotoObjek1= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo1'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo1'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek1 = '';	}
	if ($dataDobjek['photo2']!="") {	$vPhotoObjek2= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo2'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo2'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek2 = '';	}
	if ($dataDobjek['photo3']!="") {	$vPhotoObjek3= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo3'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo3'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek3 = '';	}
	if ($dataDobjek['photo4']!="") {	$vPhotoObjek4= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo4'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo4'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek4 = '';	}

	if ($dataDobjek['statusspajk']=="Request") {
		$photoObjeknya = '';
	}else{
	$photoObjeknya = '<div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photodepan'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photobelakang'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                	  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photokanan'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                	  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photokiri'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>';
	$setLocation = '<br /><br /><div id="map" class="col-md-12"></div>
					<script>
					window.onload = function() {
					var latlng = new google.maps.LatLng('.$dataDobjek['latitude'].', '.$dataDobjek['longitude'].');
					var map = new google.maps.Map(document.getElementById(\'map\'), {
					center: latlng,
					zoom: 11,
					mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					title: \'Set lat/lon values for this object\',	draggable: true
					});
					google.maps.event.addListener(marker, \'dragend\', function(a) {
					console.log(a);
					var div = document.createElement(\'div\');
					div.innerHTML = a.latLng.lat().toFixed(4) + \', \' + a.latLng.lng().toFixed(4);
					document.getElementsByTagName(\'body\')[0].appendChild(div);
					});
					};
					</script>';
}
$DataObjekPhotoGeneral = '
<div class="row" id="shuffle-grid">
	<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($dataDobjek['input_date']).'" data-title="background1">
    	<div class="thumbnail">
        	<div class="media">
            <div class="indicator"><span class="spinner"></span></div>
				<div class="overlay">
                	'.$photoObjeknya.'
					<div class="toolbar"><a href="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                </div>
            <span class="meta bottom darken">
            <h5 class="nm semibold">'.$metGen['nama'].' <br/><small><i class="ico-calendar2"></i> '._convertDate($dataDobjek['input_date']).'</small></h5>
            </span>
            <img data-toggle="unveil" src="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" data-src="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" alt="Photo" width="100%" height="500"/>
        	</div>
    	</div>
	</div>
</div>';
}else{
	$totalappraisalkkb = $dataDobjek['nilaiappraisalbangunan'] + $dataDobjek['nilaiappraisalperabot'] + $dataDobjek['nilaiappraisalstok'] + $dataDobjek['nilaiappraisalmesin'];
	$DataObjekGeneral = '<div class="col-sm-3"><a href="javascript:void(0);">Merk & Tipe</a></div><div class="col-sm-9">'.$dataDobjek['merktipe'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Pertanggungan</a></div><div class="col-sm-9">'.$dataObjOkupasi['keterangan'].'&nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Tahun Kendaraan</a></div><div class="col-sm-9">'.$dataDobjek['tahunkendaraan'].'&nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Nomor Polisi</a></div><div class="col-sm-9">'.$dataDobjek['nomorpolisi'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Nomor Rangka & Mesin</a></div><div class="col-sm-9">'.$dataDobjek['nomorrangka'].' / '.$dataDobjek['nomormesin'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Tempat Duduk</a></div><div class="col-sm-9">'.$dataDobjek['tempatduduk'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Daya Angkut</a></div><div class="col-sm-9">'.$dataDobjek['dayaangkut'].' Ton</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Penggunaan</a></div><div class="col-sm-9">'.$dataDobjek['pengunaan'].' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Nilai Kendaraan</a></div><div class="col-sm-9">'.duit($dataDobjek['nilaiappraisalkendaraan']).' &nbsp;</div>
						 <div class="col-sm-3"><a href="javascript:void(0);">Alamat System</a></div><div class="col-sm-9">'.$dataDobjek['alamatlatlon'].' &nbsp;</div>
						';
	if ($dataDobjek['photo1']!="") {	$vPhotoObjek1= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo1'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo1'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek1 = '';	}
	if ($dataDobjek['photo2']!="") {	$vPhotoObjek2= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo2'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo2'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek2 = '';	}
	if ($dataDobjek['photo3']!="") {	$vPhotoObjek3= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo3'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo3'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek3 = '';	}
	if ($dataDobjek['photo4']!="") {	$vPhotoObjek4= '<center><div class="col-md-6"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo4'].'" data-lightbox="gallery-group-1" target="_blank"><img src="../'.$PhotoGeneralSurvey.''.$dataDobjek['photo4'].'" alt="" class="img-circle" width="200" height="200"/></a></div>';	}
	else{	$vPhotoObjek4 = '';	}

	if ($dataDobjek['statusspajk']=="Request") {
		$photoObjeknya = '';
	}else{
	$photoObjeknya = '<div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photodepan'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photobelakang'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photokanan'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					  <div class="toolbar"><a href="../'.$PhotoGeneralSurvey.''.$dataDobjek['photokiri'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>';
	$setLocation = '<br /><br /><div id="map" class="col-md-12"></div>
					<script>
					window.onload = function() {
					var latlng = new google.maps.LatLng(-6.2181857, 106.8370583);
					var map = new google.maps.Map(document.getElementById(\'map\'), {
					center: latlng,
					zoom: 11,
					mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					title: \'Set lat/lon values for this object\',	draggable: true
					});
					google.maps.event.addListener(marker, \'dragend\', function(a) {
					console.log(a);
					var div = document.createElement(\'div\');
					div.innerHTML = a.latLng.lat().toFixed(4) + \', \' + a.latLng.lng().toFixed(4);
					document.getElementsByTagName(\'body\')[0].appendChild(div);
					});
					};
					</script>';
	}
$DataObjekPhotoGeneral = '
<div class="row" id="shuffle-grid">
	<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($dataDobjek['input_date']).'" data-title="background1">
    	<div class="thumbnail">
        	<div class="media">
            <div class="indicator"><span class="spinner"></span></div>
				<div class="overlay">
                	'.$photoObjeknya.'
					<div class="toolbar"><a href="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
                </div>
            <span class="meta bottom darken">
            <h5 class="nm semibold">'.$metGen['nama'].' <br/><small><i class="ico-calendar2"></i> '._convertDate($dataDobjek['input_date']).'</small></h5>
            </span>
            <img data-toggle="unveil" src="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" data-src="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" alt="Photo" width="100%" height="500"/>
        	</div>
    	</div>
	</div>
</div>';

}
echo '<div class="page-header-section"><h2 class="title semibold">Preview Member AJK GENERAL</h2></div>
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
	            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metGen['logobroker'].'" alt="" width="75px"></li>
						<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metGen['broker'].'</h4></li>
					</ul>
					</div>
					<div class="col-md-6">
	            	<ul class="list-table">
						<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metGen['perusahaan'].'</h4></li>
						<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metGen['logoperusahaan'].'" alt="" width="75px"></li>
					</ul>
					</div>
	            </div>
				<div class="form-group">
	            	<div class="col-xs-12 col-sm-12 col-md-8">
							<div class="col-sm-3"><a href="javascript:void(0);">Produk</a></div><div class="col-sm-9">'.$metGen['produk'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Status</a></div><div class="col-sm-9">'.$_statusdata.'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Regional</a></div><div class="col-sm-9">'.$metGen['regional'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Cabang</a></div><div class="col-sm-9">'.$metGen['cabang'].'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Debitur</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">Nomor PK</a></div><div class="col-sm-9">'.$metGen['nomorpk'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">IDSPAJK</a></div><div class="col-sm-9">'.$metGen['nomorspajk'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">K.T.P</a></div><div class="col-sm-9">'.$metGen['ktp'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Member</a></div><div class="col-sm-9">'.$metGen['nama'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">D.O.B</a></div><div class="col-sm-9">'._convertDate($metGen['tgllahir']).'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Jenis Kelamin</a></div><div class="col-sm-9">'.$metGen['gender'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Telephone</a></div><div class="col-sm-9">'.$metGen['hp'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Alamat Member</a></div><div class="col-sm-9">'.$metAlamatMember.'&nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Insurance</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">Plafond</a></div><div class="col-sm-9">'.duit($metGen['nilaiplafond']).'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tenor</a></div><div class="col-sm-9">'.duit($metGen['tenor']).' month&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Tanggal Asuransi</a></div><div class="col-sm-9">'._convertDate($metGen['tglakadapproval']).' &nbsp;</div>
							<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Objek</h4></div>
							<div class="col-sm-3"><a href="javascript:void(0);">Appraisal</a></div><div class="col-sm-9">'.$metGen['userappraisal'].'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Alamat Objek</a></div><div class="col-sm-9">'.$metAlamatObjek.'&nbsp;</div>
							<div class="col-sm-3"><a href="javascript:void(0);">Nilai Diajukan</a></div><div class="col-sm-9">'.duit($metGen['nilaidiajukan']).' &nbsp;</div>
							'.$DataObjekGeneral.'
	                </div>
	                <div class="col-xs-12 col-sm-12 col-md-4">
							'.$DataObjekPhotoGeneral.'
							<!--<div class="col-sm-12 text-center"><a href="../'.$PhotoGeneralDebitur.''.$metGen['photodebitur'].'" data-lightbox="gallery-group-1"><img src="../'.$PhotoGeneralDebitur.'/'.$metGen['photodebitur'].'" alt="" class="img-circle" width="200" height="200"></a></div>-->
							'.$vPhotoObjek1.''.$vPhotoObjek2.''.$vPhotoObjek3.''.$vPhotoObjek4.'
					</div>
					'.$setLocation.'
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
	</div>
</div>';
echo '<style>	#map {	height: 350px;	}	</style>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';
	;
	break;

case "statusGnr":
echo '<div class="page-header-section"><h2 class="title semibold">Status AJK GENERAL</h2></div>
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
	<th width="1%">Approved</th>
	<th width="1%">Pending</th>
	<th width="1%">Process</th>
	<th width="1%">Survey</th>
	<th width="1%">Request</th>
	<th width="1%">Batal</th>
	<th width="1%">Tolak</th>
</tr>
</thead>
<tbody>';
$metDataGNR = $database->doQuery('SELECT
ajkumum.idbroker,
ajkumum.idclient,
ajkumum.idproduk,
ajkcobroker.`name` AS broker,
ajkclient.`name` AS perusahaan,
ajkpolis.produk AS produk,
Count(ajkumum.statusspajk) AS jData,
count(case when ajkumum.statusspajk ="Tolak" then ajkumum.statusspajk END) as jDataTolak,
count(case when ajkumum.statusspajk ="Batal" then ajkumum.statusspajk END) as jDataBatal,
count(case when ajkumum.statusspajk ="Request" then ajkumum.statusspajk END) as jDataRequest,
count(case when ajkumum.statusspajk ="Survey" then ajkumum.statusspajk END) as jDataSurvey,
count(case when ajkumum.statusspajk ="Process" then ajkumum.statusspajk END) as jDataProcess,
count(case when ajkumum.statusspajk ="Pending" then ajkumum.statusspajk END) as jDataPending,
count(case when ajkumum.statusspajk ="Approved" then ajkumum.statusspajk END) as jDataApproved,
count(case when ajkumum.statusspajk ="Aktif" then ajkumum.statusspajk END) as jDataAktif,
count(case when ajkumum.statusspajk ="Realisasi" then ajkumum.statusspajk END) as jDataRealisasi,
ajkumum.statusspajk,
ajkcabang.`name` AS cabang
FROM ajkumum
INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
WHERE ajkumum.id !="" '.$q___5.'
GROUP BY ajkumum.idbroker, ajkumum.idclient, ajkumum.idproduk');
	//WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
while ($metDataStatus_ = mysql_fetch_array($metDataGNR)) {
if ($metDataStatus_['jDataTolak'] > 0) {	$statusTolak = '<span class="label label-danger">'.$metDataStatus_['jDataTolak'].'</span>';	}else{	$statusTolak = $metDataStatus_['jDataTolak'];	}
if ($metDataStatus_['jDataBatal'] > 0) {	$statusBatal = '<span class="label label-danger">'.$metDataStatus_['jDataBatal'].'</span>';	}else{	$statusBatal = $metDataStatus_['jDataBatal'];	}
if ($metDataStatus_['jDataRequest'] >= 1 && $metDataStatus_['jDataRequest'] <= 5) 		{	$statusRequest = '<span class="label label-danger">'.$metDataStatus_['jDataRequest'].'</span>';		}	elseif ($metDataStatus_['jDataRequest'] == 0)		{	$statusRequest = $metDataStatus_['jDataRequest'];		}	else{	$statusRequest = '<span class="label label-danger">'.$metDataStatus_['jDataRequest'].'</span>';	}
if ($metDataStatus_['jDataSurvey'] >= 1 && $metDataStatus_['jDataSurvey'] <= 5) 		{	$statusSurvey = '<span class="label label-danger">'.$metDataStatus_['jDataSurvey'].'</span>';		}	elseif ($metDataStatus_['jDataSurvey'] == 0)		{	$statusSurvey = $metDataStatus_['jDataSurvey'];			}	else{	$statusSurvey = '<span class="label label-primary">'.$metDataStatus_['jDataSurvey'].'</span>';	}
if ($metDataStatus_['jDataProcess'] >= 1 && $metDataStatus_['jDataProcess'] <= 5) 		{	$statusProcess = '<span class="label label-danger">'.$metDataStatus_['jDataProcess'].'</span>';		}	elseif ($metDataStatus_['jDataProcess'] == 0)		{	$statusProcess = $metDataStatus_['jDataProcess'];		}	else{	$statusProcess = '<span class="label label-primary">'.$metDataStatus_['jDataProcess'].'</span>';	}
if ($metDataStatus_['jDataPending'] >= 1 && $metDataStatus_['jDataPending'] <= 5) 		{	$statusPending = '<span class="label label-danger">'.$metDataStatus_['jDataPending'].'</span>';		}	elseif ($metDataStatus_['jDataPending'] == 0)		{	$statusPending = $metDataStatus_['jDataPending'];		}	else{	$statusPending = '<span class="label label-primary">'.$metDataStatus_['jDataPending'].'</span>';	}
if ($metDataStatus_['jDataApproved'] >= 1 && $metDataStatus_['jDataApproved'] <= 5) 	{	$statusApproved = '<span class="label label-danger">'.$metDataStatus_['jDataApproved'].'</span>';	}	elseif ($metDataStatus_['jDataApproved'] == 0)		{	$statusApproved = $metDataStatus_['jDataApproved'];		}	else{	$statusApproved = '<span class="label label-primary">'.$metDataStatus_['jDataApproved'].'</span>';	}
if ($metDataStatus_['jDataAktif'] >= 1 && $metDataStatus_['jDataAktif'] <= 5) 			{	$statusAktif = '<span class="label label-danger">'.$metDataStatus_['jDataAktif'].'</span>';			}	elseif ($metDataStatus_['jDataAktif'] == 0)			{	$statusAktif = $metDataStatus_['jDataAktif'];			}	else{	$statusAktif = '<span class="label label-primary">'.$metDataStatus_['jDataAktif'].'</span>';	}
if ($metDataStatus_['jDataARealisasi'] >= 1 && $metDataStatus_['jDataRealisasi'] <= 5) 	{	$statusRealisasi = '<span class="label label-danger">'.$metDataStatus_['jDataRealisasi'].'</span>';	}	elseif ($metDataStatus_['jDataRealisasi'] == 0)		{	$statusRealisasi = $metDataStatus_['jDataRealisasi'];	}	else{	$statusRealisasi = '<span class="label label-primary">'.$metDataStatus_['jDataRealisasi'].'</span>';	}
echo '<tr>
	   	<td align="center">'.++$no.'</td>
	   	<td>'.$metDataStatus_['broker'].'</td>
	   	<td>'.$metDataStatus_['perusahaan'].'</td>
	   	<td align="center">'.$metDataStatus_['produk'].'</td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'"><span class="label label-success">'.$metDataStatus_['jData'].'</span></a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Realisasi").'">'.$statusRealisasi.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Aktif").'">'.$statusAktif.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Approved").'">'.$statusApproved.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Pending").'">'.$statusPending.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Process").'">'.$statusProcess.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Survey").'">'.$statusSurvey.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Request").'">'.$statusRequest.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Batal").'">'.$statusBatal.'</a></td>
	   	<td align="center"><a href="ajk.php?re=dataGnr&dt=statusGeneral&gidb='.$thisEncrypter->encode($metDataStatus_['idbroker']).'&gidc='.$thisEncrypter->encode($metDataStatus_['idclient']).'&gidp='.$thisEncrypter->encode($metDataStatus_['idproduk']).'&gidstat='.$thisEncrypter->encode("Tolak").'">'.$statusTolak.'</a></td>
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
			<th><input type="search" class="form-control" name="search_engine" placeholder="Approved"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Pending"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Process"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Survey"></th>
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

case "statusGeneral":
echo '<div class="page-header-section"><h2 class="title semibold">Status AJK GENERAL</h2></div>
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
	<th width="1%">SPAJK</th>
	<th width="1%">Name</th>
	<th width="1%">DOB</th>
	<th width="1%">Phone</th>
	<th width="1%">Value Proposed</th>
	<th width="1%">Input</th>
	<th width="1%">Input Date</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
if ($_REQUEST['gidstat']) {
	$statusDatanya = 'AND ajkumum.statusspajk="'.$thisEncrypter->decode($_REQUEST['gidstat']).'"';
}else{
	$statusDatanya = '';
}
$viewGeneral = $database->doQuery('SELECT
ajkumum.id,
ajkumum.idbroker,
ajkumum.idclient,
ajkumum.idproduk,
ajkcobroker.`name` AS broker,
ajkclient.`name` AS perusahaan,
ajkpolis.produk AS produk,
ajkumum.statusspajk,
ajkcabang.`name` AS cabang,
ajkumum.nomorspajk,
ajkumum.nama,
ajkumum.tgllahir,
ajkumum.hp,
ajkumum.nilaidiajukan,
useraccess.firstname,
DATE_FORMAT(ajkumum.input_date,"%Y-%m-%d") AS tglinput
FROM
ajkumum
INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
INNER JOIN useraccess ON ajkumum.input_by = useraccess.id
WHERE
ajkumum.idbroker="'.$thisEncrypter->decode($_REQUEST['gidb']).'" AND
ajkumum.idclient="'.$thisEncrypter->decode($_REQUEST['gidc']).'" AND
ajkumum.idproduk="'.$thisEncrypter->decode($_REQUEST['gidp']).'" '.$statusDatanya.'');
while ($viewGeneral_ = mysql_fetch_array($viewGeneral)) {
echo '<tr>
	   	<td align="center">'.++$no.'</td>
	   	<td>'.$viewGeneral_['broker'].'</td>
	   	<td>'.$viewGeneral_['perusahaan'].'</td>
	   	<td align="center">'.$viewGeneral_['produk'].'</td>
	   	<td align="center"><span class="label label-primary">'.$viewGeneral_['nomorspajk'].'</span></td>
	   	<td><a href="ajk.php?re=dataGnr&dt=viewGeneral&gid='.$thisEncrypter->encode($viewGeneral_['id']).'">'.$viewGeneral_['nama'].'</a></td>
	   	<td align="center">'._convertDate($viewGeneral_['tgllahir']).'</td>
	   	<td align="center">'.$viewGeneral_['hp'].'</td>
	   	<td align="right"><span class="label label-info">'.duit($viewGeneral_['nilaidiajukan']).'</span></td>
	   	<td align="center">'.$viewGeneral_['firstname'].'</td>
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
			<th><input type="search" class="form-control" name="search_engine" placeholder="SPAJK"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="DOB"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Phone"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Value"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="User"></th>
			<th><input type="hidden" class="form-control" name="search_engine" placeholder="Survey"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
		</tr>
		</tfoot></table>
		</div>
		</div>
		</div>
	</div>';
	;
	break;



	default:
echo '<div class="page-header-section"><h2 class="title semibold">Members AJK GENERAL</h2></div>
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
	<th width="1%">SPAJK</th>
	<th width="1%">Name</th>
	<th width="1%">Gender</th>
	<th width="1%">DOB</th>
	<th width="1%">Phone</th>
	<th width="1%">Address Debitur</th>
	<th width="1%">Address Objek</th>
	<th width="1%">Value Proposed</th>
	<th width="1%">User Input</th>
	<th width="1%">Date Input</th>
	<th width="1%">Branch</th>
</tr>
</thead>
<tbody>';
$metDataGNR = $database->doQuery('SELECT
ajkclient.`name` AS perusahaan,
ajkpolis.produk,
ajkumum.id,
ajkumum.nomorspajk,
ajkumum.statusspajk,
ajkumum.nama,
IF(ajkumum.jnskelamin="P", "Male", "Female") AS gender,
ajkumum.tgllahir,
ajkumum.ktp,
ajkumum.hp,
ajkumum.alamatdebitur,
ajkumum.alamatobjek,
ajkumum.nilaidiajukan,
ajkregional.`name` AS regional,
ajkcabang.`name` AS cabang,
useraccess.firstname AS userinput,
DATE_FORMAT(ajkumum.input_date,"%y-%m-%d") AS tglinput,
ajkcobroker.`name` AS broker
FROM ajkumum
INNER JOIN ajkclient ON ajkumum.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkumum.idproduk = ajkpolis.id
INNER JOIN ajkregional ON ajkumum.idregional = ajkregional.er
INNER JOIN ajkcabang ON ajkumum.idcabang = ajkcabang.er
INNER JOIN useraccess ON ajkumum.input_by = useraccess.id
INNER JOIN ajkcobroker ON ajkumum.idbroker = ajkcobroker.id
WHERE ajkumum.id !="" '.$q___5.'
ORDER BY ajkumum.id DESC');
//WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
while ($metData_ = mysql_fetch_array($metDataGNR)) {
if ($metData_['statusspajk'] == "Request") {
	$metStatus='<span class="label label-danger">'.$metData_['statusspajk'].'</span>';
}else{
	$metStatus='<span class="label label-primary">'.$metData_['statusspajk'].'</span>';
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metData_['broker'].'</td>
   	<td>'.$metData_['perusahaan'].'</td>
   	<td align="center">'.$metData_['produk'].'</td>
   	<td>'.$metStatus.'</td>
   	<td align="center">'.$metData_['nomorspajk'].'</td>
   	<td><a href="ajk.php?re=dataGnr&dt=viewGeneral&gid='.$thisEncrypter->encode($metData_['id']).'">'.$metData_['nama'].'</a></td>
   	<td align="center">'.$metData_['gender'].'</td>
   	<td align="center">'._convertDate($metData_['tgllahir']).'</td>
   	<td align="right">'.$metData_['hp'].'</td>
   	<td align="center">'.str_replace("#"," ", $metData_['alamatdebitur']).'</td>
   	<td align="center">'.str_replace("#"," ", $metData_['alamtobjek']).'</td>
   	<td align="right">'.duit($metData_['nilaidiajukan']).'</td>
   	<td align="center">'.$metData_['userinput'].'</td>
   	<td align="right">'._convertDate($metData_['tglinput']).'</td>
   	<td>'.$metData_['cabang'].'</td>
    </tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="SPAJK"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Debitur"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Objek"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
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
?>
