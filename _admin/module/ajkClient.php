<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// Copyright (C) 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;">
		<div class="page-header page-header-block">';
switch ($_REQUEST['op']) {
case "comp":
echo '<div class="page-header-section"><h2 class="title semibold">Partner</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=client&op=newcompany">'.BTN_NEW.'</a></div>
		</div>
      </div>';

//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">

<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="20%">Broker</th>
        <th width="1%">Logo</th>
        <th>Company</th>
        <th width="30%">Address</th>
        <th width="10%">Option</th>
        </tr>
    </thead>
    <tbody>';
		$metClient = $database->doQuery('SELECT ajkcobroker.name AS broker,	ajkclient.id, ajkclient.name, ajkclient.logo, ajkclient.address1
										 FROM ajkclient
										 LEFT JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
										 WHERE ajkclient.del IS NULL  '.$q__.'ORDER BY ajkclient.id DESC');
while ($metClient_ = mysql_fetch_array($metClient)) {
if ($metClient_['logo']=="") {
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
}else{
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.''.$metClient_['logo'].'" alt="" class="img-circle"></div>';
}

echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metClient_['broker'].'</td>
   	<td>'.$logoclient.'</td>
   	<td><a href="ajk.php?re=client&op=compview&cid='.$thisEncrypter->encode($metClient_['id']).'">'.$metClient_['name'].'</a></td>
   	<td>'.$metClient_['address1'].'</td>
   	<td align="center"><a href="ajk.php?re=client&op=compedt&cid='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
		<tfoot>
        <tr>
        	<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Address"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            </tr>
        </tfoot></table>
    	</div>
		</div>
    </div>
</div>';
	;
	break;
case "newcompany":
if ($_REQUEST['met']=="saveme") {
	if ($_FILES['fileImage']['size'] / 1024 > $FILESIZE_2)	{
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div>';
	}
	else{
	$nama_file =  strtolower(strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
	$nama_file_thumb =  strtolower("thumb_".strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
	metImage($nama_file);
	$metCompany = $database->doQuery('INSERT INTO ajkclient SET name="'.strtoupper($_REQUEST['companyname']).'",
																idc="'.$_REQUEST['cobroker'].'",
																address1="'.ucwords($_REQUEST['street']).'",
																address2="'.ucwords($_REQUEST['addressline']).'",
																city="'.strtoupper($_REQUEST['city']).'",
																postcode="'.$_REQUEST['postcode'].'",
																phoneoffice="'.$_REQUEST['phoneoffice'].'",
																phonehp="'.$_REQUEST['phonehp'].'",
																phonefax="'.$_REQUEST['phonefax'].'",
																logo="'.$nama_file.'",
																logothumb="'.$nama_file_thumb.'",
																input_by="'.$q['id'].'",
																input_time="'.$futgl.'"');
		$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=comp">
				 <div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> Input Company '.strtoupper($_REQUEST['companyname']).'.
                 </div>';
	}
}

$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="page-header-section"><h2 class="title semibold">Company</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=comp">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Company</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="cobroker" class="form-control" required>
		            		<option value="">Select Broker</option>';
while ($metBroker_ = mysql_fetch_array($metBroker)) {
echo '<option value="'.$metBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
}
		echo '</select>
			    </div>
		    </div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
				<div class="col-sm-10"><input type="text" name="companyname" value="'.$_REQUEST['companyname'].'" class="form-control" placeholder="Company Name" required></div>
			</div>

			<div class="form-group">
	            <label class="control-label col-sm-2">Address <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                <div class="row mb5"><div class="col-sm-12"><input name="street" value="'.$_REQUEST['street'].'" type="text" class="form-control" placeholder="Street Address" required></div></div>
				<div class="row mb5"><div class="col-sm-12"><input name="addressline" value="'.$_REQUEST['addressline'].'" type="text" class="form-control" placeholder="Address Line 2"></div></div>
				<div class="row">
                	<div class="col-xs-6 pr5"><input name="city" value="'.$_REQUEST['city'].'" type="text" class="form-control" placeholder="City" required></div>
                    <div class="col-xs-6 pl5"><input name="postcode" value="'.$_REQUEST['postcode'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
				</div>
                </div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Phone <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-4 pr5"><input name="phoneoffice" value="'.$_REQUEST['phoneoffice'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Phone Office" required></div>
                	<div class="col-xs-4 pc5"><input name="phonehp" value="'.$_REQUEST['phonehp'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Handphone" required></div>
                    <div class="col-xs-4 pl5"><input name="phonefax" value="'.$_REQUEST['phonefax'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Fax" required></div>
				</div>
				</div>
            </div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Logo <span class="text-danger">*</span></label>
                <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*" required></div>
			</div>

		</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
case "compedt":
$metComp = mysql_fetch_array($database->doQuery('SELECT * FROM ajkclient WHERE id="'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
if ($_REQUEST['met']=="editsaveme") {
	if ($_FILES['fileImage']['name']) {
		$nama_file =  strtolower(strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
		$nama_file_thumb =  strtolower("thumb_".strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
		metImage($nama_file);
		//echo $nama_file;
		$setPhotoUser = 'logo="'.$nama_file.'",
						 logothumb="'.$nama_file_thumb.'",';
	}else{
		$setPhotoUser = '';
	}
	$metCompany = $database->doQuery('UPDATE ajkclient SET name="'.strtoupper($_REQUEST['companyname']).'",
															idc="'.$_REQUEST['cobroker'].'",
															address1="'.ucwords($_REQUEST['street']).'",
															address2="'.ucwords($_REQUEST['addressline']).'",
															city="'.strtoupper($_REQUEST['city']).'",
															postcode="'.$_REQUEST['postcode'].'",
															phoneoffice="'.$_REQUEST['phoneoffice'].'",
															phonehp="'.$_REQUEST['phonehp'].'",
															phonefax="'.$_REQUEST['phonefax'].'",
															'.$setPhotoUser.'
															update_by="'.$q['id'].'",
															update_time="'.$futgl.'"
									WHERE id="'.$thisEncrypter->decode($_REQUEST['cid']).'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=comp">
				 <div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				 <strong>Success!</strong> Edit Company '.strtoupper($_REQUEST['companyname']).'.
                 </div>';
}

$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="page-header-section"><h2 class="title semibold">Company</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=comp">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Form Company</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="cobroker" class="form-control" required>
		            		<option value="">Select Broker</option>';
while ($metBroker_ = mysql_fetch_array($metBroker)) {
echo '<option value="'.$metBroker_['id'].'"'._selected($metComp['idc'], $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
}
echo '</select>
		    </div>
	    </div>
			<div class="form-group">
					<label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
					<div class="col-sm-10"><input type="text" name="companyname" value="'.$metComp['name'].'" class="form-control" placeholder="Company Name" required></div>
			</div>

			<div class="form-group">
	            <label class="control-label col-sm-2">Address <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                <div class="row mb5"><div class="col-sm-12"><input name="street" value="'.$metComp['address1'].'" type="text" class="form-control" placeholder="Street Address" required></div></div>
				<div class="row mb5"><div class="col-sm-12"><input name="addressline" value="'.$metComp['address2'].'" type="text" class="form-control" placeholder="Address Line 2"></div></div>
				<div class="row">
                	<div class="col-xs-6 pr5"><input name="city" value="'.$metComp['city'].'" type="text" class="form-control" placeholder="City" required></div>
                    <div class="col-xs-6 pl5"><input name="postcode" value="'.$metComp['postcode'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
				</div>
                </div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Phone <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-4 pr5"><input name="phoneoffice" value="'.$metComp['phoneoffice'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Phone Office" required></div>
                	<div class="col-xs-4 pc5"><input name="phonehp" value="'.$metComp['phonehp'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Handphone" required></div>
                    <div class="col-xs-4 pl5"><input name="phonefax" value="'.$metComp['phonefax'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Fax" required></div>
				</div>
				</div>
            </div>
		</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Logo <span class="text-danger">*</span></label>
		<div class="col-sm-10">';
	if ($metComp['logothumb']=="") {
		echo '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
	}else{
		echo '<div class="media-object"><img src="../'.$PathPhoto.''.$metComp['logothumb'].'" alt="" class="img-circle" width="150"></div>';
	}
		echo '<input type="file" name="fileImage" accept="image/*">
	</div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="editsaveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
case "compview":
$metComp = mysql_fetch_array($database->doQuery('SELECT * FROM ajkclient WHERE id="'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
$metCdebitur = mysql_fetch_array($database->doQuery('SELECT Count(ajkpeserta.nama) AS jNama, SUM(ajkpeserta.totalpremi) AS jTP, SUM(ajkpeserta.plafond) AS jTPlafond FROM ajkpeserta WHERE ajkpeserta.idclient = "'.$metComp['id'].'" AND ajkpeserta.iddn != "" AND ajkpeserta.del IS NULL'));
$metCdebitnote = mysql_fetch_array($database->doQuery('SELECT Count(ajkdebitnote.nomordebitnote) AS jDN FROM ajkdebitnote WHERE ajkdebitnote.idclient = "'.$metComp['id'].'" AND ajkdebitnote.del IS NULL'));
$metCcreditnote = mysql_fetch_array($database->doQuery('SELECT Count(ajkcreditnote.nomorcreditnote) AS jCN, SUM(ajkcreditnote.nilaiclaimclient) AS jNilaiCN FROM ajkcreditnote WHERE ajkcreditnote.idclient = "'.$metComp['id'].'" AND ajkcreditnote.tipeklaim !="Batal" AND ajkcreditnote.del IS NULL'));

echo '<div class="page-header-section"><h2 class="title semibold">Company</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=comp">'.BTN_BACK.'</a></div>
		</div>
	</div>';
echo '<div class="row">
    	<div class="col-lg-3">
        	<ul class="list-group list-group-tabs">
            	<li class="list-group-item active"><a href="#profile" data-toggle="tab"><i class="ico-office mr5"></i> Profile Company</a></li>
            	<li class="list-group-item"><a href="#Agreement" data-toggle="tab"><i class="ico-archive2 mr5"></i> Agreement</a></li>
            	<li class="list-group-item"><a href="#debitur" data-toggle="tab"><i class="ico-shield3 mr5"></i> Debitur</a></li>
            	<li class="list-group-item"><a href="#regional" data-toggle="tab"><i class="ico-globe mr5"></i> Regional</a></li>
            	<li class="list-group-item"><a href="#account" data-toggle="tab"><i class="ico-user mr5"></i> Account</a></li>
            </ul>
            <hr>
            <ul class="nav nav-section nav-justified mt15">
            <li><div class="section"><h4 class="nm semibold">'.duit($metCdebitur['jNama']).'</h4><p class="nm text-muted">Debitur</p></div></li>
            <li><div class="section"><h4 class="nm semibold">'.duit($metCdebitnote['jDN']).'</h4><p class="nm text-muted">Debitnote</p></div></li>
            <li><div class="section"><h4 class="nm semibold">'.duit($metCcreditnote['jCN']).'</h4><p class="nm text-muted">Creditnote</p></div></li>
            </ul>
            <hr>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Plafond</h5>
                    <h4 class="semibold mb0">'.duit($metCdebitur['jTPlafond']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-primary"><i class="ico-money"></i></a>
            </div>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Premium</h5>
                    <h4 class="semibold mb0">'.duit($metCdebitur['jTP']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-success"><i class="ico-money"></i></a>
            </div>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Claim</h5>
                    <h4 class="semibold mb0">'.duit($metCcreditnote['jNilaiCN']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-danger"><i class="ico-money"></i></a>
            </div>
        </div>

		<div class="col-lg-9">
        	<div class="tab-content">
            	<div class="tab-pane active" id="profile">
                <form class="panel form-horizontal form-bordered" name="form-profile">
					<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4></li>
								</ul>
							</div>
                        </div>
						<div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12">
								<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Address</a></p></div></div>
                                <div class="text-default"><p>'.$metComp['address1'].'<br />'.$metComp['address2'].'<br />'.$metComp['city'].'<br />'.$metComp['postcode'].'</p></div>
								<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Phone</a></p></div></div>
                                <div class="text-default"><p>'.$metComp['phonehp'].'<br />'.$metComp['phoneoffice'].'<br />'.$metComp['phonefax'].'</p></div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>

                <div class="tab-pane" id="Agreement">
				<form class="panel form-horizontal form-bordered" name="form-Agreement">
                	<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Agreement / Product</h4></li>
								</ul>
							</div>
						</div>';

$metAgreement = $database->doQuery('SELECT * FROM ajkpolis WHERE idcost="'.$metComp['id'].'"');
while ($metAgreement_ = mysql_fetch_array($metAgreement)) {
if ($metAgreement_['status']=="Aktif") {
	$agreestatus= '<button type="button" class="btn btn-teal mb5"><i class="ico-file"></i> Active</button>';
}else{
	$agreestatus= '<button type="button" class="btn btn-danger mb5"><i class="ico-cancel"></i> Not Active</button>';
}

if ($metAgreement_['filepks']=="") {
	$agreefile= '<a href="#"><button type="button" class="btn btn-default mb5"><i class="ico-file-pdf"></i></button></a>';
}else{
	$agreefile= '<a href="../'.$PathDokumen.''.$metAgreement_['filepks'].'" target="_blank"><button type="button" class="btn btn-primary mb5"><i class="ico-file-pdf"></i></button></a>';
}

if ($metAgreement_['filedeklarasi']=="") {
	$agreedeklarasi= '<a href="#"><button type="button" class="btn btn-default mb5"><i class="ico-file-excel"></i></button></a>';
}else{
	$agreedeklarasi= '<a href="../'.$PathDokumen.''.$metAgreement_['filepks'].'" target="_blank"><button type="button" class="btn btn-success mb5"><i class="ico-file-excel"></i></button></a>';
}
	$rAgree .= '<div class="table-layout mt1 mb0">
					<div class="col-sm-8"><p class="meta nm"><a href="javascript:void(0);">'.$metAgreement_['produk'].'</a></p></div>
					<div class="col-sm-2"><p class="meta nm">'.$agreestatus.'</p></div>
					<div class="col-sm-1">'.$agreefile.'</div>
					<div class="col-sm-1">'.$agreedeklarasi.'</div>
				</div>
				';
}
					echo '<div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12">
								'.$rAgree.'
                            </div>
                        </div>

	                </div>
                </form>
                </div>

                <div class="tab-pane" id="debitur">
				<form class="panel form-horizontal form-bordered" name="form-debitur">
                	<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Debitur</h4></li>
								</ul>
							</div>
						</div>';
$metDebitur = $database->doQuery('SELECT ajkpeserta.statusaktif,
										Count(ajkpeserta.nama) AS jDebitur
								  FROM ajkpeserta
								  WHERE ajkpeserta.iddn != "" AND ajkpeserta.idclient = '.$metComp['id'].' AND ajkpeserta.del IS NULL
								  GROUP BY ajkpeserta.statusaktif');
while ($metDebitur_ = mysql_fetch_array($metDebitur)) {
	if ($metDebitur_['statusaktif']=="Batal" OR $metDebitur_['statusaktif']=="Reject") {
		$setColor = "panel-inverse";
	}elseif ($metDebitur_['statusaktif']=="Maturity") {
		$setColor = "panel-default";
	}elseif ($metDebitur_['statusaktif']=="Approve") {
		$setColor = "panel-primary";
	}elseif ($metDebitur_['statusaktif']=="Pending" OR $metDebitur_['statusaktif']=="Refund" OR $metDebitur_['statusaktif']=="Upload" OR $metDebitur_['statusaktif']=="Request") {
		$setColor = "panel-warning";
	}elseif ($metDebitur_['statusaktif']=="Claim" OR $metDebitur_['statusaktif']=="Lapse") {
		$setColor = "panel-danger";
	}else{
		$setColor = "panel-success";
	}
$rDebitur .='<div class="col-xs-6 col-sm-6 col-md-6">
				<div class="panel '.$setColor.'">
	            <div class="panel-heading"><h3 class="panel-title"><i class="ico-stats-up mr5"></i> '.$metDebitur_['statusaktif'].'</h3></div>
        		<div class="indicator"><span class="spinner"></span></div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
            	<div class="panel '.$setColor.'">
	            <div class="panel-heading"><h3 class="panel-title"><i class="ico-people mr5"></i> '.$metDebitur_['jDebitur'].' Debitur</h3></div>
        		<div class="indicator"><span class="spinner"></span></div>
                </div>
            </div>';
}
					echo '<div class="form-group">
                            '.$rDebitur.'
                        </div>
	                </div>
                </form>
                </div>

                <div class="tab-pane" id="regional">
				<form class="panel form-horizontal form-bordered" name="form-regional">
                	<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Regional</h4></li>
								</ul>
							</div>
						</div>';
$rRegion = $database->doQuery('SELECT ajkregional.`name` AS regional,
									  ajkarea.`name` AS area,
									  ajkcabang.`name` AS cabang
							   FROM ajkregional
							   INNER JOIN ajkarea ON ajkregional.er = ajkarea.idreg
							   INNER JOIN ajkcabang ON ajkarea.er = ajkcabang.idarea
							   WHERE ajkregional.idclient = '.$metComp['id'].' AND
							   		 ajkregional.del IS NULL AND
							   		 ajkarea.del IS NULL AND
							   		 ajkcabang.del IS NULL
							   	ORDER BY regional, cabang ASC');
while ($rRegion_ = mysql_fetch_array($rRegion)) {
$metRegion .= '<tr><td align="center">'.++$no.'</td>
				   <td align="center">'.$rRegion_['regional'].'</td>
				   <td align="center">'.$rRegion_['area'].'</td>
				   <td align="center">'.$rRegion_['cabang'].'</td>
			</tr>';
}
			echo '<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
							<thead>
							<tr><th width="1%">No</th>
								<th>Regional</th>
								<th>Area</th>
								<th>Branch</th>
							</tr>
							</thead>
							<tbody>
							'.$metRegion.'
							</tbody>
							<tfoot>
							<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Regional"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Area"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
							</tr>
							</tfoot></table>
							</div>
							</div>
                        </div>
	                </div>
                </form>
                </div>';
$rAccount = $database->doQuery('SELECT useraccess.photo,
									   CONCAT(useraccess.firstname," ",useraccess.lastname) AS namauser,
									   useraccess.username,
									   leveluser.nama AS leveluser,
									   useraccess.email,
									   useraccess.supervisor,
									   ajkcabang.`name` AS cabang,
									   useraccess.aktif
								FROM useraccess
								INNER JOIN leveluser ON useraccess.`level` = leveluser.er
								INNER JOIN ajkcabang ON useraccess.branch = ajkcabang.er
								WHERE useraccess.idclient = "'.$metComp['id'].'" AND useraccess.idas IS NULL AND useraccess.del IS NULL
								ORDER BY leveluser ASC');
while ($rAccount_ = mysql_fetch_array($rAccount)) {
if ($rAccount_['photo']=="") {
	$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
}else{
	$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.''.$rAccount_['photo'].'" alt="" class="img-circle"></div>';
}

if ($rAccount_['aktif']=="Y") {
	$metAktifnya = '<span class="label label-primary">Active</span>';
}else{
	$metAktifnya = '<span class="label label-Danger">Not Active</span>';
}
$metstaffspv = mysql_fetch_array($database->doQuery('SELECT id, username FROM useraccess WHERE id="'.$rAccount_['supervisor'].'"'));
$$metAccount .='<tr><td align="center">'.++$no1.'</td>
				   <td align="center">'.$logoCOB.'</td>
				   <td>'.$rAccount_['namauser'].'</td>
				   <td>'.$rAccount_['username'].'</td>
				   <td align="center">'.$rAccount_['leveluser'].'</td>
				   <td><a href="mailto:'.$metComp['email'].'">'.$rAccount_['email'].'</a></td>
				   <td>'.$rAccount_['cabang'].'</td>
				   <td align="center">'.$metAktifnya.'</td>
				   <td>'.$metstaffspv['username'].'</td>
				</tr>';
}
            echo '<div class="tab-pane" id="account">
				<form class="panel form-horizontal form-bordered" name="form-account">
                	<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        <div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Account</h4></li>
								</ul>
							</div>
						</div>
						<div class="form-group">
                            <div class="row">
						<div class="col-md-12">
							<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
							<thead>
							<tr><th width="1%">No</th>
								<th>Photo</th>
								<th>Name</th>
								<th>Username</th>
								<th>Level</th>
								<th>Email</th>
								<th>Branch</th>
								<th>Active</th>
								<th>Supervisor</th>
							</tr>
							</thead>
							<tbody>
							'.$$metAccount.'
							</tbody>
							<tfoot>
							<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
								<th><input type="hidden" class="form-control" name="search_engine"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Username"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Level"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Email"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Active"></th>
								<th><input type="search" class="form-control" name="search_engine" placeholder="Supervisor"></th>
							</tr>
							</tfoot></table>
							</div>
							</div>
                        </div>

	                </div>
                </form>
                </div>
			</div>
        </div>
    </div>';
		;
		break;

case "policy":
echo '<div class="page-header-section"><h2 class="title semibold">Agreement</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=client&op=newpolicy">'.BTN_NEW.'</a></div>
		</div>
      </div>';

//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">

<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr>
	<th width="1%">No</th>
	<th width="1%">General</th>
	<th>Company</th>
	<th>Agreement</th>
	<th width="20%">Product</th>
	<th width="1%">TypeRate</th>
	<th width="1%">Status</th>
	<th width="1%">PKS</th>
	<th width="1%">File</th>
	<th width="1%">Option</th>
</tr>
</thead>
<tbody>';
$metPolicy = $database->doQuery('SELECT
ajkclient.`name`,
ajkclient.idc,
ajkpolis.id,
if(ajkpolis.policyauto="",ajkpolis.policymanual,ajkpolis.policyauto) AS nopolicy,
ajkpolis.general,
ajkpolis.produk,
ajkpolis.typerate,
ajkpolis.byrate,
ajkpolis.general,
ajkpolis.start_date,
ajkpolis.end_date,
ajkpolis.agestart,
ajkpolis.ageend,
ajkpolis.brokrage,
ajkpolis.shareins,
ajkpolis.adminfee,
ajkpolis.diskon,
ajkpolis.ppn,
ajkpolis.pph,
ajkpolis.freecover,
ajkpolis.status,
ajkpolis.filedeklarasi,
ajkpolis.filepks
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
WHERE ajkpolis.del IS NULL AND ajkclient.del IS NULL '.$q__.'
ORDER BY ajkclient.id DESC');
while ($metPolicy_ = mysql_fetch_array($metPolicy)) {
if ($metPolicy_['general']!="T") {
	$geneal_ = '<a href="ajk.php?re=client&op=rtgeneral&idp='.$thisEncrypter->encode($metPolicy_['id']).'" title="Setup rate general"><span class="label label-primary">Ya</span></a>';
}else{
	$geneal_ = '<span class="label label-primary">Tidak</span>';
}

if ($metPolicy_['filedeklarasi']=="") {
	$dataDeklarasi='';
}else{
	$dataDeklarasi='<a href="../'.$PathUploadExcel.''.$metPolicy_['filedeklarasi'].'"><img src="../image/excel.png" width="20"></a>';
}

if ($metPolicy_['filepks']=="") {
	$dataPKS='';
}else{
	$dataPKS='<a href="../'.$PathDokumen.''.$metPolicy_['filepks'].'"><img src="../image/dninvoice.png" width="20"></a>';
}
if ($metPolicy_['general']=="T") {
$_status = mysql_fetch_array($database->doQuery('SELECT ajkratepremi.id AS idrateclient,
														ajkmedical.id AS idmedical
												FROM ajkpolis
												LEFT JOIN ajkratepremi ON ajkpolis.id = ajkratepremi.idpolis
												LEFT JOIN ajkmedical ON ajkpolis.id = ajkmedical.idproduk
												WHERE ajkpolis.id = "'.$metPolicy_['id'].'" AND ajkratepremi.status="Aktif" AND ajkmedical.status="Aktif"'));

	if ($_status['idrateclient']==null) {
		$statusproduk = '<span class="label label-danger" title="Silahkan upload rate cleint">'.$metPolicy_['status'].'</span>';
	}elseif ($_status['idmedical']==null) {
		$statusproduk = '<span class="label label-warning" title="Silahkan upload table medical">'.$metPolicy_['status'].'</span>';
	}else{
		if ($metPolicy_['status']=="Proses") {	$status_produk = $database->doQuery('UPDATE ajkpolis SET status="Aktif" WHERE id="'.$metPolicy_['id'].'" AND status="Proses"');	}else{	}
		$statusproduk = '<span class="label label-primary">'.$metPolicy_['status'].'</span>';
	}
}else{
$_status = mysql_fetch_array($database->doQuery('SELECT ajkrategeneral.id AS idrategeneral,
														ajkgeneralarea.id AS idgeneralarea,
														ajkgeneralkategori.id AS idgeneralkategori
												FROM ajkpolis
												LEFT JOIN ajkrategeneral ON ajkpolis.id = ajkrategeneral.idproduk
												LEFT JOIN ajkgeneralarea ON ajkpolis.id = ajkgeneralarea.idproduk
												LEFT JOIN ajkgeneralkategori ON ajkpolis.id = ajkgeneralkategori.idproduk
												WHERE ajkpolis.id = "'.$metPolicy_['id'].'" AND ajkrategeneral.status = "Aktif" AND ajkrategeneral.del IS NULL AND ajkgeneralarea.del IS NULL AND ajkgeneralkategori.del IS NULL
												GROUP BY ajkpolis.id'));
	if ($_status['idrategeneral']==null) {
		$statusproduk = '<span class="label label-danger" title="Silahkan upload rate cleint">'.$metPolicy_['status'].'</span>';
	}elseif ($_status['idgeneralarea']==null) {
		$statusproduk = '<span class="label label-warning" title="Silahkan tentukan area produk">'.$metPolicy_['status'].'</span>';
	}elseif ($_status['idgeneralkategori']==null) {
		$statusproduk = '<span class="label label-warning" title="Silahkan tentukan kategori produk">'.$metPolicy_['status'].'</span>';
	}else{
		if ($metPolicy_['status']=="Proses") {	$status_produk = $database->doQuery('UPDATE ajkpolis SET status="Aktif" WHERE id="'.$metPolicy_['id'].'" AND status="Proses"');	}else{	}
		$statusproduk = '<span class="label label-primary">'.$metPolicy_['status'].'</span>';
	}
}

echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td align="center">'.$geneal_.'</td>
   	<td>'.$metPolicy_['name'].'</td>
   	<td><a href="ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($metPolicy_['id']).'">'.$metPolicy_['nopolicy'].'</a></td>
   	<td>'.$metPolicy_['produk'].'</td>
   	<td align="center">'.$metPolicy_['typerate'].'('.$metPolicy_['byrate'].')</td>
   	<td align="center">'.$statusproduk.'</td>
   	<td align="center">'.$dataPKS.'</td>
   	<td align="center">'.$dataDeklarasi.'</td>
   	<td align="center"><a href="ajk.php?re=client&op=poledt&pid='.$thisEncrypter->encode($metPolicy_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
	<tfoot>
	<tr>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Agreement"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Type Rate"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	</tr>
	</tfoot></table>
			</div>
		</div>
	</div>
</div>';
	;
	break;
case "newpolicy":
$metClient = $database->doQuery('SELECT id, name FROM ajkclient WHERE del IS NULL '.$q__.' ORDER BY name ASC');
$polis =mysql_fetch_array($database->doQuery('SELECT idp FROM ajkpolis ORDER BY id DESC'));
if ($polis['idp']=="") {	$xidPol = 1;	}	else	{	$xidPol = $polis['idp'] + 1;	}
$numb = 100000; $numb1 = substr($numb,1);
$RNoPolis = $DatePolis.''.$numb1.''.$xidPol;
$codeproduk = 305;

echo '<div class="page-header-section"><h2 class="title semibold">Agreement</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=policy">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="savemepolis") {
	if ($_FILES['filePKS']['size'] / 1024 > $FILESIZE_2)	{
		$metnotif .= '<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Error!</strong> File PKS tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div>';
	}
	else{
	$nama_file =  'PKS_'.$_REQUEST['coClient'].'_'.$xidPol.'_'.$_FILES['filePKS']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
	$sourceFILE = $_FILES['filePKS']['tmp_name'];
	$direktori = '../'.$PathDokumen.'/'.$nama_file;
	move_uploaded_file($sourceFILE,$direktori);


		$PathUploadExcelDeklarasi= "../myFiles/_uploaddata/".$foldername."";
		if (!file_exists($PathUploadExcelDeklarasi)) 	{	mkdir($PathUploadExcelDeklarasi, 0777);	chmod($PathUploadExcelDeklarasi, 0777);	}
		$namafileuploadDeklarasi =  str_replace(" ", "_", $foldername."".$codeproduk.'.'.$RNoPolis.'_'.$_FILES['filedeklarasi']['name']);
		$nama_fileuploadDeklarasi =  str_replace(" ", "_", $codeproduk.'.'.$RNoPolis.'_'.$_FILES['filedeklarasi']['name']);
		$file_type = $_FILES['filedeklarasi']['type']; //tipe file
		$sourceDekl = $_FILES['filedeklarasi']['tmp_name'];
		$direktoriDekl = "$PathUploadExcelDeklarasi$nama_fileuploadDeklarasi"; // direktori tempat menyimpan file
		move_uploaded_file($sourceDekl,$direktoriDekl);
		//CEK DATA DEKLARASI
		if (!$_FILES['filedeklarasi']['name']) {
			$file_dekilarasinya = '';
		}else{
			$file_dekilarasinya = 'filedeklarasi="'.$namafileuploadDeklarasi.'",';
		}
		//CEK DATA DEKLARASI

	if ($_REQUEST['ajkgeneral']=="AJK") {	$_metGen = "T";	}
	elseif ($_REQUEST['ajkgeneral']=="GENERAL") {	$_metGen = "Y";	}
	else {	$_metGen = "YY";	}
	$metPolicyNew = $database->doQuery('INSERT INTO ajkpolis SET idcost="'.$_REQUEST['coClient'].'",
																 idp="'.$xidPol.'",
																 policyauto="'.$codeproduk.'.'.$RNoPolis.'",
																 policymanual="'.strtoupper($_REQUEST['manualpolicy']).'",
																 produk="'.strtoupper($_REQUEST['productname']).'",
																 typerate="'.$_REQUEST['typerate'].'",
																 typemedical="'.$_REQUEST['typemedical'].'",
																 byrate="'.$_REQUEST['byrate'].'",
																 idgeneral="'.$_REQUEST['idgeneral'].'",
																 byrategeneral="'.$_REQUEST['ratemethodgeneral'].'",
																 classgeneral="'.$_REQUEST['generalclass'].'",
																 general="'.$_metGen.'",
																 calculatedrate="'.$_REQUEST['ratecalculate'].'",
																 refundrate="'.$_REQUEST['raterefund'].'",
																 refundpercentage="'.$_REQUEST['percentageRefund'].'",
																 klaimrate="'.$_REQUEST['rateklaim'].'",
																 klaimpercentage="'.$_REQUEST['percentageClaim'].'",
																 jumlahharibatal="'.$_REQUEST['jumlahharibatal'].'",
																 start_date="'._convertDate2($_REQUEST['datefrom']).'",
																 end_date="'._convertDate2($_REQUEST['dateto']).'",
																 lastdayinsurance="'.$_REQUEST['bs-touchspin-basic'].'",
																 wpc="'.$_REQUEST['bs-touchspin-wpc'].'",
																 plafondstart="'.$_REQUEST['plafondfrom'].'",
																 plafondend="'.$_REQUEST['plafondto'].'",
																 minimumpremi="'.$_REQUEST['minpremi'].'",
																 agestart="'.$_REQUEST['agefrom'].'",
																 ageend="'.$_REQUEST['ageto'].'",
																 agecalculateday="'.$_REQUEST['agebirthday'].'",
																 tenormin="'.$_REQUEST['tenormin'].'",
																 tenormax="'.$_REQUEST['tenormax'].'",
																 shareins="'.$_REQUEST['bs-touchspin-shareins'].'",
																 brokrage="'.$_REQUEST['bs-touchspin-brokrage'].'",
																 adminfee="'.$_REQUEST['adminfee'].'",
																 rmf="'.$_REQUEST['bs-touchspin-rmf'].'",
																 diskon="'.$_REQUEST['bs-touchspin-discount'].'",
																 ppn="'.$_REQUEST['bs-touchspin-ppn'].'",
																 pph="'.$_REQUEST['bs-touchspin-pph'].'",
																 bankdebitnote="'.$_REQUEST['dnbankname'].'",
																 bankdebitnotenama="'.$_REQUEST['dnbanknmaccount'].'",
																 bankdebitnotecabang="'.$_REQUEST['dnbankbranch'].'",
																 bankdebitnoteaccount="'.$_REQUEST['dnbankaccount'].'",
																 bankcreditnote="'.$_REQUEST['cnbankname'].'",
																 bankcreditnotenama="'.$_REQUEST['cnbanknmaccount'].'",
																 bankcreditnotecabang="'.$_REQUEST['cnbankbranch'].'",
																 bankcreditnoteaccount="'.$_REQUEST['cnbankaccount'].'",
																 paymentmode="'.$_REQUEST['paymentmode'].'",
																 freecover="T",
																 status="Proses",
																 phk="'.$_REQUEST['phk'].'",
																 wp="'.$_REQUEST['wp'].'",
																 setphoto="'.$_REQUEST['uploadphoto'].'",
																 setktp="'.$_REQUEST['uploadktp'].'",
																 levelvalidasi="'.$_REQUEST['valuser'].'",
																 filepks="'.$nama_file.'",
																 '.$file_dekilarasinya.'
																 input_by="'.$q['id'].'",
																 input_date="'.$futgl.'"');
/* DISABLE KARNA BEDA ALUR
		$metProdukLast = mysql_fetch_array($database->doQuery('SELECT id FROM ajkpolis ORDER BY id DESC'));
		foreach($_REQUEST['listgeneral'] as $k => $val) {
			$metGeneralProd = $database->doQuery('INSERT INTO ajklistgeneral SET idproduk="'.$metProdukLast['id'].'", idgeneral="'.$val.'"');
		}
*/
		$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=policy">
				 <div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> New Agreement '.$RNoPolis.'.
                 </div>';
	}
}

echo '<script type="text/javascript">
	function ohYesOhNo() {	if (document.getElementById("customradio4").checked) {	document.getElementById("ifYes").style.display = "block";	}	else {	document.getElementById("ifYes").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function SikAsik() {	if (document.getElementById("customradio6").checked) {	document.getElementById("ifAsik").style.display = "block";	}	else {	document.getElementById("ifAsik").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function GealGeol() {	if (document.getElementById("customradio18").checked) {	document.getElementById("ifGealGeol").style.display = "block";	}	else {	document.getElementById("ifGealGeol").style.display = "none";	}	}
	</script>

<script type="text/javascript">
$(document).ready(function(){
    $(\'input[type="radio"]\').click(function(){
        if($(this).attr("value")=="AJK")		{	$(".box").not(".AJK").hide();			$(".AJK").show();	}
        if($(this).attr("value")=="GENERAL")	{	$(".box").not(".GENERAL").hide();		$(".GENERAL").show();	}
        if($(this).attr("value")=="GENERALAJK")	{	$(".box").not(".GENERALAJK").hide();	$(".GENERALAJK").show();	}
    });
});
</script>
<style type="text/css">
    .box{
        display: none;
    }
</style>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Agreement</h3></div>
		<div class="panel-body">
		<div class="form-group">
            <label class="col-sm-2 control-label">Type Product</label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['ajkgeneral'], "AJK").' name="ajkgeneral" id="customradio19" value="AJK" required><label for="customradio19">&nbsp;&nbsp;AJK</label>
						<input type="radio"'.pilih($_REQUEST['ajkgeneral'], "GENERAL").' name="ajkgeneral" id="customradio18" value="GENERAL" required><label for="customradio18">&nbsp;&nbsp;GENERAL</label>
						<input type="radio"'.pilih($_REQUEST['ajkgeneral'], "GENERALAJK").' name="ajkgeneral" id="customradioga" value="GENERALAJK" required><label for="customradioga" ">&nbsp;&nbsp;AJK + GENERAL</label>
                    </span>
				</div>
			</div>
		<!-- SETUP KETENTUAN GENERAL -->
		<div class="GENERAL box">
            <label class="col-sm-2 control-label">&nbsp;</label>
			<div class=" col-sm-10">
				<div class="panel panel-success">
            		<div class="panel-heading"><h3 class="panel-title">General</h3></div>
					<div class="form-group">
					<label class="col-sm-2 control-label">List General <span class="text-danger">*</span></label>
						<div class="col-sm-10">';
	$metGen = $database->doQuery('SELECT ajkgeneraltype.id, ajkgeneraltype.idb, ajkgeneraltype.type FROM ajkcobroker INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb WHERE ajkcobroker.id = "'.$q['idbroker'].'" AND ajkcobroker.del IS NULL AND ajkgeneraltype.keterangan="GENERAL" ORDER BY ajkgeneraltype.type ASC');
	$nomorradio = 1;
	while ($metGen_ = mysql_fetch_array($metGen)) {
/*
		$genList = mysql_fetch_array($database->doQuery('SELECT * FROM ajklistgeneral WHERE idproduk="'.$metPolicy['id'].'" AND idgeneral="'.$metGen_['id'].'"'));
		if ($genList['idgeneral']) {
			echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).' checked disabled><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
		}else{
			echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).'><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
		}
*/
	echo '<span class="radio custom-radio custom-radio-primary">
    		<input type="radio"'.pilih($_REQUEST['idgeneral'], $metGen_['id']).' name="idgeneral" id="nmrgeneral'.$nomorradio.'" value="'.$metGen_['id'].'"><label for="nmrgeneral'.$nomorradio.'">&nbsp;'.$metGen_['type'].'</label>
        </span>';
	$nomorradio = $nomorradio + 1;
	}
echo '</div>
		</div>
		<div class="form-group">
		<label class="col-sm-2 control-label">Rate Method By <span class="text-danger">*</span></label>
        	<div class="col-sm-10">
            <span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($metPolicy['byrategeneral'], "Plafond").' name="ratemethodgeneral" id="customradioG1" value="Plafond"><label for="customradioG1">&nbsp;&nbsp;Plafond&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metPolicy['byrategeneral'], "Tenor").' name="ratemethodgeneral" id="customradioG2" value="Tenor"><label for="customradioG2">&nbsp;&nbsp;Tenor</label>
            </span>
			</div>
		</div>

		<div class="form-group">
        <label class="col-sm-2 control-label">Use Type Class <span class="text-danger">*</span></label>
        	<div class="col-sm-10">
            <span class="radio custom-radio custom-radio-primary">
            	<input type="radio"'.pilih($metPolicy['classgeneral'], "Ya").' name="generalclass" id="customradioG5" value="Ya"><label for="customradioG5">&nbsp;&nbsp;Ya&nbsp;&nbsp;</label>
                <input type="radio"'.pilih($metPolicy['classgeneral'], "Tidak").' name="generalclass" id="customradioG6" value="Tidak"><label for="customradioG6">&nbsp;&nbsp;Tidak</label>
            </span>
			</div>
		</div>

	</div>
    </div>
</div>
<!-- SETUP KETENTUAN GENERAL -->


		<!-- SETUP KETENTUAN GENERAL + AJK -->
		<div class="GENERALAJK box">
<label class="col-sm-2 control-label">&nbsp;</label>
			<div class=" col-sm-10">
				<div class="panel panel-success">
            		<div class="panel-heading"><h3 class="panel-title">AJK + General</h3></div>
					<div class="form-group">
					<label class="col-sm-2 control-label">List General <span class="text-danger">*</span></label>
	<div class="col-sm-10">';
	$metGen = $database->doQuery('SELECT ajkgeneraltype.id, ajkgeneraltype.idb, ajkgeneraltype.type FROM ajkcobroker INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb WHERE ajkcobroker.id = "'.$q['idbroker'].'" AND ajkcobroker.del IS NULL AND ajkgeneraltype.keterangan="GENERAL + AJK" ORDER BY ajkgeneraltype.type ASC');
	$nomorradio1 = 1;
	while ($metGen_ = mysql_fetch_array($metGen)) {
/*
		$genList = mysql_fetch_array($database->doQuery('SELECT * FROM ajklistgeneral WHERE idproduk="'.$metPolicy['id'].'" AND idgeneral="'.$metGen_['id'].'"'));
		if ($genList['idgeneral']) {
			echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).' checked disabled><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
		}else{
			echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).'><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
		}
*/
		echo '<span class="radio custom-radio custom-radio-primary">
    		<input type="radio"'.pilih($_REQUEST['idgeneral'], $metGen_['id']).' name="idgeneral" id="nmrgeneral1'.$nomorradio1.'" value="'.$metGen_['id'].'"><label for="nmrgeneral1'.$nomorradio1.'">&nbsp;'.$metGen_['type'].'</label>
        </span>';
		$nomorradio1 = $nomorradio1 + 1;
	}
echo '</div>
		</div>

		<div class="form-group">
        <label class="col-sm-2 control-label">Rate Method By <span class="text-danger">*</span></label>
        	<div class="col-sm-10">
            <span class="radio custom-radio custom-radio-primary">
            	<input type="radio"'.pilih($metPolicy['byrategeneral'], "Plafond").' name="ratemethodgeneral" id="customradioG3" value="Plafond"><label for="customradioG3">&nbsp;&nbsp;Plafond&nbsp;&nbsp;</label>
                <input type="radio"'.pilih($metPolicy['byrategeneral'], "Tenor").' name="ratemethodgeneral" id="customradioG4" value="Tenor"><label for="customradioG4">&nbsp;&nbsp;Tenor</label>
            </span>
			</div>
		</div>

		<div class="form-group">
        <label class="col-sm-2 control-label">Use Type Class <span class="text-danger">*</span></label>
        	<div class="col-sm-10">
            <span class="radio custom-radio custom-radio-primary">
            	<input type="radio"'.pilih($metPolicy['classgeneral'], "Ya").' name="generalclass" id="customradioG7" value="Ya"><label for="customradioG7">&nbsp;&nbsp;Ya&nbsp;&nbsp;</label>
                <input type="radio"'.pilih($metPolicy['classgeneral'], "Tidak").' name="generalclass" id="customradioG8" value="Tidak"><label for="customradioG8">&nbsp;&nbsp;Tidak</label>
            </span>
			</div>
		</div>

		</div>
    </div>
</div>
<!-- SETUP KETENTUAN GENERAL + AJK -->
';
	$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
		echo '
			<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBroker(this);" required>
		         		<option value="">Select Broker</option>';
	while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
		echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
	}
	echo '</select>
			    </div>
			    </div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
			<div class="col-sm-10">
			<select name="coClient" class="form-control" id="coClient" onChange="mametClient(this);" required>
	            		<option value="">Select Partner</option>
			</select>
		    </div>
	    </div>
			<div class="form-group">
					<label class="col-sm-2 control-label">Product Name <span class="text-danger">*</span></label>
					<div class="col-sm-10"><input type="text" name="productname" value="'.$_REQUEST['productname'].'" class="form-control" placeholder="Product Name" required></div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label">Type Product <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['typemedical'], "SKKT").' name="typemedical" id="customradiotype1" value="SKKT" required><label for="customradiotype1">&nbsp;&nbsp;SKKT&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['typemedical'], "SPK").' name="typemedical" id="customradiotype2" value="SPK" required><label for="customradiotype2">&nbsp;&nbsp;SPK</label>
                    </span>
				</div>
			</div>
			<div class="form-group">
	            <label class="control-label col-sm-2">Agreement Number</label>
            	<div class="col-sm-10">
                <div class="row mb5"><div class="col-sm-6"><input name="kodeproduk" value="'.$codeproduk.'" type="text" class="form-control" placeholder="Agreement System" disabled></div>
									 <div class="col-sm-6"><input name="autopolicy" value="'.$codeproduk.'.'.$RNoPolis.'" type="text" class="form-control" placeholder="Agreement System" disabled></div>
				</div>
				<div class="row mb5"><div class="col-sm-12"><input name="manualpolicy" value="'.$_REQUEST['manualpolicy'].'" type="text" class="form-control" placeholder="Agreement Manual"></div></div>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label">Date of Agreement <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<div class="row">
                    <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" required/></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Bank for Debit Note <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
					<div class="col-xs-3 pc5"><input name="dnbanknmaccount" value="'.$_REQUEST['dnbanknmaccount'].'" type="text" class="form-control" placeholder="Name Account" required></div>
                	<div class="col-xs-3 pr5"><input name="dnbankname" value="'.$_REQUEST['dnbankname'].'" type="text" class="form-control" placeholder="Bank" required></div>
                	<div class="col-xs-3 pc5"><input name="dnbankbranch" value="'.$_REQUEST['dnbankbranch'].'" type="text" class="form-control" placeholder="Branch" required></div>
                    <div class="col-xs-3 pl5"><input name="dnbankaccount" value="'.$_REQUEST['dnbankaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Bank for Creditnote <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-3 pr5"><input name="cnbanknmaccount" value="'.$_REQUEST['cnbanknmaccount'].'" type="text" class="form-control" placeholder="Name Account" required></div>
					<div class="col-xs-3 pr5"><input name="cnbankname" value="'.$_REQUEST['cnbankname'].'" type="text" class="form-control" placeholder="Bank" required></div>
                	<div class="col-xs-3 pc5"><input name="cnbankbranch" value="'.$_REQUEST['cnbankbranch'].'" type="text" class="form-control" placeholder="Branch" required></div>
                    <div class="col-xs-3 pl5"><input name="cnbankaccount" value="'.$_REQUEST['cnbankaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Rate Premium<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-6 pr5">
                		<select name="typerate" class="form-control" required>
                        <option value="">Select Type Rate</option>
                        <option value="Decrease"'._selected($_REQUEST["typerate"], "Decrease").'>Decrease</option>
                        <option value="Flat"'._selected($_REQUEST["typerate"], "Flat").'>Flat</option>
                        </select>
					</div>
                	<div class="col-xs-6 pr5">
                		<select name="byrate" class="form-control" required>
                        <option value="">Select By Rate</option>
                        <option value="Age"'._selected($_REQUEST["byrate"], "Age").'>Age</option>
                        <option value="Table"'._selected($_REQUEST["byrate"], "Table").'>Table</option>
                        </select>
					</div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Age Birthday<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                		<select name="agebirthday" class="form-control" required>
                        <option value="">Age Calculatede Day</option>
                        <option value="183"'._selected($_REQUEST["agebirthday"], "183").'>Nearest Birthday</option>
                        <option value="366"'._selected($_REQUEST["agebirthday"], "366").'>Last Birthday</option>
                        <option value="0"'._selected($_REQUEST["agebirthday"], "0").'>Next Birthday</option>
                        </select>
				</div>
			</div>

            <div class="form-group">
            <label class="col-sm-2 control-label">Calculated Rate <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['ratecalculate'], "100").' name="ratecalculate" id="customradio1" value="100" required><label for="customradio1">&nbsp;&nbsp;100 (persen)&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['ratecalculate'], "1000").' name="ratecalculate" id="customradio2" value="1000" required><label for="customradio2">&nbsp;&nbsp;1000 (permil)</label>
                    </span>
				</div>
			</div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Formula Refund<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['raterefund'], "Table").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio3" value="Table" required><label for="customradio3">&nbsp;&nbsp;by Table Rate Refund&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['raterefund'], "Percentage").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio4" value="Percentage" required><label for="customradio4">&nbsp;&nbsp;by Percentage</label>
	                    <div id="ifYes" '.($_REQUEST["raterefund"]=="customradio3" ? " style=\"display:block\"":" style=\"display:none\"").' required>
						<div class="row mb5"><div class="col-sm-12"><input name="percentageRefund" class="form-control" value="'.$_REQUEST['percentageRefund'].'" data-parsley-type="number" type="text" placeholder="Percentage Refund"></div></div>
						</div>
                    </span>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Formula Claim<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['rateklaim'], "Table").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio5" value="Table" required><label for="customradio5">&nbsp;&nbsp;by Table Rate Claim&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['rateklaim'], "Percentage").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio6" value="Percentage" required><label for="customradio6">&nbsp;&nbsp;by Percentage</label>
	                    <div id="ifAsik" '.($_REQUEST["raterefund"]=="customradio6" ? " style=\"display:block\"":" style=\"display:none\"").' required>
						<div class="row mb5"><div class="col-sm-12"><input name="percentageClaim" class="form-control" value="'.$_REQUEST['percentageClaim'].'" data-parsley-type="number" type="text" placeholder="Percentage Claim"></div></div>
						</div>
                    </span>
				</div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Cancel Member <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="jumlahharibatal" value="'.$_REQUEST['jumlahharibatal'].'" placeholder="Day" required></div>
            </div>
			<div class="form-group">
            	<label class="col-sm-2 control-label">Last Day Covered <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="bs-touchspin-basic" value="'.$_REQUEST['bs-touchspin-basic'].'" placeholder="Day" required></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">W.P.C <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="bs-touchspin-wpc" value="'.$_REQUEST['bs-touchspin-wpc'].'" placeholder="Day" required></div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Plafond <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="plafondfrom" class="form-control" data-parsley-type="number" value="'.$_REQUEST['plafondfrom'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="plafondto" class="form-control" data-parsley-type="number" value="'.$_REQUEST['plafondto'].'" placeholder="to" required/></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Age <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="agefrom" class="form-control" data-parsley-type="number" value="'.$_REQUEST['agefrom'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="ageto" class="form-control" data-parsley-type="number" value="'.$_REQUEST['ageto'].'" placeholder="to" required/></div>
				</div>
				</div>
            </div>
			<div class="form-group">
	            <label class="control-label col-sm-2">Range Tenor <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="tenormin" class="form-control" data-parsley-type="number" value="'.$_REQUEST['tenormin'].'" placeholder="Minimum Tenor" required/></div>
                    <div class="col-md-6"><input type="text" name="tenormax" class="form-control" data-parsley-type="number" value="'.$_REQUEST['tenormax'].'" placeholder="Maksimum Tenor" required/></div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Minimum Premium</label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-12"><input type="text" name="minpremi" class="form-control" data-parsley-type="number" value="'.$_REQUEST['minpremi'].'" placeholder="Minimum Premium" required/></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Admin Fee</label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-12"><input type="text" name="adminfee" class="form-control" data-parsley-type="number" value="'.$_REQUEST['adminfee'].'" placeholder="Admin Fee" required/></div>
				</div>
				</div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Discount</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-discount" value="'.$_REQUEST['bs-touchspin-discount'].'" placeholder="Discount"></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Brokrage</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-brokrage" value="'.$_REQUEST['bs-touchspin-brokrage'].'" placeholder="Brokrage"></div>
				<label class="col-sm-2 control-label">Gross Bank Premium</label>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">R.M.F <span class="text-danger">*</span></label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-rmf" value="'.$_REQUEST['bs-touchspin-rmf'].'" placeholder="R.M.F" required></div>
				<label class="col-sm-2 control-label">Gross Bank Premium</label>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">PPN</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-ppn" value="'.$_REQUEST['bs-touchspin-ppn'].'" placeholder="PPN"></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">PPh</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-pph" value="'.$_REQUEST['bs-touchspin-pph'].'" placeholder="PPh"></div>
            </div>

            <div class="form-group">
            <label class="col-sm-2 control-label">Mode Payment <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['paymentmode'], "FrontPayment").' name="paymentmode" id="customradio13" value="FrontPayment" required><label for="customradio13">&nbsp;&nbsp;FrontPayment&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['paymentmode'], "BackPayment").' name="paymentmode" id="customradio14" value="BackPayment" required><label for="customradio14">&nbsp;&nbsp;BackPayment</label>
                    </span>
				</div>
			</div>

			<div class="form-group">
            <label class="col-sm-2 control-label">P H K <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['phk'], "Y").' name="phk" id="customradio22" value="Y" required><label for="customradio22">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['phk'], "T").' name="phk" id="customradio23" value="T" required><label for="customradio23">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

			<div class="form-group">
            <label class="col-sm-2 control-label">Wanprestasi <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['wp'], "Y").' name="wp" id="customradio20" value="Y" required><label for="customradio20">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['wp'], "T").' name="wp" id="customradio21" value="T" required><label for="customradio21">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

            <!--<div class="form-group">
            <label class="col-sm-2 control-label">Free Covered <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['freecover'], "Y").' name="freecover" id="customradio7" value="Y" required><label for="customradio7">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['freecover'], "T").' name="freecover" id="customradio8" value="T" required><label for="customradio8">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>-->

            <div class="form-group">
            <label class="col-sm-2 control-label">Upload Photo <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['uploadphoto'], "Y").' name="uploadphoto" id="customradio9" value="Y" required><label for="customradio9">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['uploadphoto'], "T").' name="uploadphoto" id="customradio10" value="T" required><label for="customradio10">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

            <div class="form-group">
            <label class="col-sm-2 control-label">Upload KTP <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['uploadktp'], "Y").' name="uploadktp" id="customradio11" value="Y" required><label for="customradio11">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['uploadktp'], "T").' name="uploadktp" id="customradio12" value="T" required><label for="customradio12">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

	      <div class="form-group">
            <label class="col-sm-2 control-label">Level Validation User <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['valuser'], "1").' name="valuser" id="customradio15" value="1" required><label for="customradio15">&nbsp;&nbsp;1&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($_REQUEST['valuser'], "2").' name="valuser" id="customradio16" value="2" required><label for="customradio16">&nbsp;&nbsp;2</label>
                    	<input type="radio"'.pilih($_REQUEST['valuser'], "3").' name="valuser" id="customradio17" value="3" required><label for="customradio17">&nbsp;&nbsp;3</label>
                    </span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">File PKS<span class="text-danger">*</span></label>
                <div class="col-sm-10"><input type="file" name="filePKS" accept="application/pdf"></div>
			</div>
			<div class="form-group">
            	<label class="col-sm-2 control-label">File Declaration</label>
            	<div class="col-sm-10"><input type="file" name="filedeklarasi" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet (.xlsx)"></div>
			</div>
		</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savemepolis">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
case "poledt":
echo '<div class="page-header-section"><h2 class="title semibold">Agreement</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=policy">'.BTN_BACK.'</a></div>
		</div>
	</div>';
$metPolicy = mysql_fetch_array($database->doQuery('SELECT * FROM ajkpolis WHERE id="'.$thisEncrypter->decode($_REQUEST['pid']).'"'));
if ($metPolicy['general']=="T") {	$_metCheckedT = "checked";	}else{	$_metCheckedT = "disabled";	}
if ($metPolicy['general']=="Y") {	$_metCheckedY = "checked";	}else{	$_metCheckedY = "disabled";	}
if ($metPolicy['general']=="YY") {	$_metCheckedYY = "checked";	}else{	$_metCheckedYY = "disabled";	}



$metClient = $database->doQuery('SELECT id, name FROM ajkclient WHERE del IS NULL ORDER BY name DESC');
if ($_REQUEST['met']=="editmepolis") {
/*
	if ($_FILES['filePKS']['size'] / 1024 > $FILESIZE_2)	{
		$metnotif .= '<div class="alert alert-dismissable alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Error!</strong> File PKS tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
                	</div>';
	}
	else{
*/
/*
	$deklarasi_file = $metPolicy['id'].'#'.$_FILES['filedeklarasi']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
	$sourceFILE = $_FILES['filedeklarasi']['tmp_name'];
	$direktori = '../'.$PathDokumen.''.$deklarasi_file;
	move_uploaded_file($sourceFILE,$direktori);
*/


	$nama_file =  'PKS_'.$metPolicy['idcost'].'_'.$metPolicy['idp'].'_'.$_FILES['filePKS']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
	$sourceFILE = $_FILES['filePKS']['tmp_name'];
	$direktori = '../'.$PathDokumen.'/'.$nama_file;
	move_uploaded_file($sourceFILE,$direktori);

	$PathUploadExcelDeklarasi= "../myFiles/_uploaddata/".$foldername."";
	if (!file_exists($PathUploadExcelDeklarasi)) 	{	mkdir($PathUploadExcelDeklarasi, 0777);	chmod($PathUploadExcelDeklarasi, 0777);	}
	$namafileuploadDeklarasi =  str_replace(" ", "_", $foldername."".$metPolicy['id'].'_'.$_FILES['filedeklarasi']['name']);
	$nama_fileuploadDeklarasi =  str_replace(" ", "_", $metPolicy['id'].'_'.$_FILES['filedeklarasi']['name']);
	$file_type = $_FILES['filedeklarasi']['type']; //tipe file
	$source = $_FILES['filedeklarasi']['tmp_name'];
	$direktori = "$PathUploadExcelDeklarasi$nama_fileuploadDeklarasi"; // direktori tempat menyimpan file
	move_uploaded_file($source,$direktori);

	if ($_REQUEST['ajkgeneral']=="AJK") {	$_metGen = "T";	}
	elseif ($_REQUEST['ajkgeneral']=="GENERAL") {	$_metGen = "Y";	}
	else {	$_metGen = "YY";	}

//CEK DATA PKS
if (!$_FILES['filePKS']['name']) 	{	$file_pksnya = '';	}else{	$file_pksnya = 'filepks="'.$nama_file.'",';	}
//CEK DATA PKS

//CEK DATA DEKLARASI
if (!$_FILES['filedeklarasi']['name']) {	$file_dekilarasinya = '';	}else{	$file_dekilarasinya = 'filedeklarasi="'.$namafileuploadDeklarasi.'",';	}
//CEK DATA DEKLARASI

//CEK ID GENERAL
if ($metPolicy['idgeneral'] !="") {
	$idgeneralnya = '';
}else{
	$idgeneralnya = 'idgeneral="'.$_REQUEST['typemedical'].'",';
}
//CEK ID GENERAL

	$metEditProduk = $database->doQuery('UPDATE ajkpolis SET idcost="'.$_REQUEST['pcompany'].'",
															 policymanual="'.strtoupper($_REQUEST['manualpolicy']).'",
															 produk="'.strtoupper($_REQUEST['productname']).'",
															 '.$idgeneralnya.'
															 typemedical="'.$_REQUEST['typemedical'].'",
															 typerate="'.$_REQUEST['typerate'].'",
															 byrate="'.$_REQUEST['byrate'].'",
															 byrategeneral="'.$_REQUEST['ratemethodgeneral'].'",
															 classgeneral="'.$_REQUEST['generalclass'].'",
															 general="'.$_metGen.'",
															 calculatedrate="'.$_REQUEST['ratecalculate'].'",
															 refundrate="'.$_REQUEST['raterefund'].'",
															 refundpercentage="'.$_REQUEST['percentageRefund'].'",
															 klaimrate="'.$_REQUEST['rateklaim'].'",
															 klaimpercentage="'.$_REQUEST['percentageClaim'].'",
															 jumlahharibatal="'.$_REQUEST['jumlahharibatal'].'",
															 start_date="'._convertDateEng2($_REQUEST['datefrom']).'",
															 end_date="'._convertDateEng2($_REQUEST['dateto']).'",
															 lastdayinsurance="'.$_REQUEST['bs-touchspin-basic'].'",
															 wpc="'.$_REQUEST['bs-touchspin-wpc'].'",
															 plafondstart="'.$_REQUEST['plafondfrom'].'",
															 plafondend="'.$_REQUEST['plafondto'].'",
															 minimumpremi="'.$_REQUEST['minpremi'].'",
															 agestart="'.$_REQUEST['agefrom'].'",
															 ageend="'.$_REQUEST['ageto'].'",
															 agecalculateday="'.$_REQUEST['agecalculateday'].'",
															 tenormin="'.$_REQUEST['tenormin'].'",
															 tenormax="'.$_REQUEST['tenormax'].'",
															 shareins="'.$_REQUEST['bs-touchspin-shareins'].'",
															 brokrage="'.$_REQUEST['bs-touchspin-brokrage'].'",
															 adminfee="'.$_REQUEST['adminfee'].'",
															 rmf="'.$_REQUEST['bs-touchspin-rmf'].'",
															 diskon="'.$_REQUEST['bs-touchspin-discount'].'",
															 ppn="'.$_REQUEST['bs-touchspin-ppn'].'",
															 pph="'.$_REQUEST['bs-touchspin-pph'].'",
															 bankdebitnote="'.$_REQUEST['dnbankname'].'",
															 bankdebitnotenama="'.$_REQUEST['dnbanknmaccount'].'",
															 bankdebitnotecabang="'.$_REQUEST['dnbankbranch'].'",
															 bankdebitnoteaccount="'.$_REQUEST['dnbankaccount'].'",
															 bankcreditnote="'.$_REQUEST['cnbankname'].'",
															 bankcreditnotenama="'.$_REQUEST['cnbanknmaccount'].'",
															 bankcreditnotecabang="'.$_REQUEST['cnbankbranch'].'",
															 bankcreditnoteaccount="'.$_REQUEST['cnbankaccount'].'",
															 paymentmode="'.$_REQUEST['paymentmode'].'",
															 phk="'.$_REQUEST['phk'].'",
															 wp="'.$_REQUEST['wp'].'",
															 setphoto="'.$_REQUEST['uploadphoto'].'",
															 setktp="'.$_REQUEST['uploadktp'].'",
															 levelvalidasi="'.$_REQUEST['valuser'].'",
															 '.$file_pksnya.'
															 '.$file_dekilarasinya.'
															 update_by="'.$q['id'].'",
															 update_date="'.$futgl.'"
							WHERE id="'.$metPolicy['id'].'"');
//freecover="'.$_REQUEST['freecover'].'",

	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=policy">
					<div class="alert alert-dismissable alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> Edit Agreement '.$metPolicy['policyauto'].'.
                 </div>';
//			}
}
echo '<script type="text/javascript">
	function ohYesOhNo() {	if (document.getElementById("customradio4").checked) {	document.getElementById("ifYes").style.display = "block";	}	else {	document.getElementById("ifYes").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function SikAsik() {	if (document.getElementById("customradio6").checked) {	document.getElementById("ifAsik").style.display = "block";	}	else {	document.getElementById("ifAsik").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function Gealgeol() {	if (document.getElementById("customradio18").checked) {	document.getElementById("ifGealgeol").style.display = "block";	}	else {	document.getElementById("ifGealgeol").style.display = "none";	}	}
	</script>

<script type="text/javascript">
$(document).ready(function(){
    $(\'input[type="radio"]\').click(function(){
        if($(this).attr("value")=="AJK")		{	$(".box").not(".AJK").hide();			$(".AJK").show();	}
        if($(this).attr("value")=="GENERAL")	{	$(".box").not(".GENERAL").hide();		$(".GENERAL").show();	}
        if($(this).attr("value")=="GENERALAJK")	{	$(".box").not(".GENERALAJK").hide();	$(".GENERALAJK").show();	}
    });
});
</script>
<style type="text/css">
    .box{
        display: none;
    }
</style>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Form Agreement</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="control-label col-sm-2">Type Product <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['general'], "T").' name="ajkgeneral" id="customradio19" value="AJK" required '.$_metCheckedT.'><label for="customradio19">&nbsp;&nbsp;AJK</label>
						<input type="radio"'.pilih($metPolicy['general'], "Y").' name="ajkgeneral" id="customradio18" value="GENERAL" required '.$_metCheckedY.'><label for="customradio18">&nbsp;&nbsp;GENERAL</label>
						<input type="radio"'.pilih($metPolicy['general'], "YY").' name="ajkgeneral" id="customradioga" value="GENERALAJK" required '.$_metCheckedYY.'><label for="customradioga" ">&nbsp;&nbsp;AJK + GENERAL</label>
                    </span>
				</div>
			</div>
			<div id="ifGealgeol" '.($_REQUEST["ajkgeneral"]=="customradio18" ? " style=\"display:block\"":" style=\"display:none\"").' required>
				<div class="form-group">
					<label class="col-sm-2 control-label">List General <span class="text-danger">*</span></label>
					<div class="col-sm-10">
<!--                            <p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="Flexas"'.pilih($_REQUEST['listgeneral'], "Flexas").'><span class="switch"></span><span class="text ml-xs">&nbsp; Flexas (Fire)</span></label></p>
                                <p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="Kendaraan Bermotor"'.pilih($_REQUEST['listgeneral'], "Kendaraan Bermotor").'><span class="switch"></span><span class="text ml-xs">&nbsp; Kendaraan Bermotor</span></label></p>
                                <p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="PHK"'.pilih($_REQUEST['listgeneral'], "PHK").'><span class="switch"></span><span class="text ml-xs">&nbsp; PHK</span></label></p>
                                <p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="Kredit Macet"'.pilih($_REQUEST['listgeneral'], "Kredit Macet").'><span class="switch"></span><span class="text ml-xs">&nbsp; Kredit Macet (Wanprestasi)</span></label></p>
-->

';
		$metGen = $database->doQuery('SELECT ajkgeneraltype.id,
									 ajkgeneraltype.idb,
									 ajkgeneraltype.type
							FROM ajkcobroker
							INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb
							WHERE ajkcobroker.id = "'.$q['idbroker'].'" AND ajkcobroker.del IS NULL
							ORDER BY ajkgeneraltype.type ASC');
		while ($metGen_ = mysql_fetch_array($metGen)) {
			$genList = mysql_fetch_array($database->doQuery('SELECT * FROM ajklistgeneral WHERE idproduk="'.$metPolicy['id'].'" AND idgeneral="'.$metGen_['id'].'"'));
			//echo $genList['idgeneral'].'<br />';
			if ($genList['idgeneral']) {
				echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).' checked disabled><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
			}else{
				echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).'><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
			}
		}
		echo '</div>
			</div>
		</div>
		<!-- SETUP KETENTUAN GENERAL -->
		<div class="GENERAL box">
            <label class="col-sm-2 control-label">&nbsp;</label>
			<div class=" col-sm-10">
				<div class="panel panel-success">
            		<div class="panel-heading"><h3 class="panel-title">General</h3></div>
					<div class="form-group">
					<label class="col-sm-2 control-label">List General <span class="text-danger">*</span></label>
						<div class="col-sm-10">';
						$metGen = $database->doQuery('SELECT ajkgeneraltype.id, ajkgeneraltype.idb, ajkgeneraltype.type FROM ajkcobroker INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb WHERE ajkcobroker.id = "'.$q['idbroker'].'" AND ajkcobroker.del IS NULL AND ajkgeneraltype.keterangan="GENERAL" ORDER BY ajkgeneraltype.type ASC');
						while ($metGen_ = mysql_fetch_array($metGen)) {
						$genList = mysql_fetch_array($database->doQuery('SELECT * FROM ajkpolis WHERE id="'.$metPolicy['id'].'" AND idgeneral="'.$metGen_['id'].'"'));
							if ($genList['idgeneral']) {
							echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).' checked disabled><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
							}else{
							echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).'><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
							}
						}
					echo '<div class="alert alert-dismissable alert-warning"><strong>Data already is check can not be unchecked</div>
						</div>
					</div>
					<div class="form-group">
            		<label class="col-sm-2 control-label">Rate Method By <span class="text-danger">*</span></label>
            			<div class="col-sm-10">
                    	<span class="radio custom-radio custom-radio-primary">
                    		<input type="radio"'.pilih($metPolicy['byrategeneral'], "Plafond").' name="ratemethodgeneral" id="customradioG1" value="Plafond"><label for="customradioG1">&nbsp;&nbsp;Plafond&nbsp;&nbsp;</label>
                    		<input type="radio"'.pilih($metPolicy['byrategeneral'], "Tenor").' name="ratemethodgeneral" id="customradioG2" value="Tenor"><label for="customradioG2">&nbsp;&nbsp;Tenor</label>
                    	</span>
						</div>
					</div>
					<div class="form-group">
            		<label class="col-sm-2 control-label">Use Type Class <span class="text-danger">*</span></label>
            			<div class="col-sm-10">
                    	<span class="radio custom-radio custom-radio-primary">
                    		<input type="radio"'.pilih($metPolicy['classgeneral'], "Ya").' name="generalclass" id="customradioG5" value="Ya"><label for="customradioG5">&nbsp;&nbsp;Ya&nbsp;&nbsp;</label>
                    		<input type="radio"'.pilih($metPolicy['classgeneral'], "Tidak").' name="generalclass" id="customradioG6" value="Tidak"><label for="customradioG6">&nbsp;&nbsp;Tidak</label>
                    	</span>
						</div>
					</div>
            	</div>
            </div>
		</div>
		<!-- SETUP KETENTUAN GENERAL -->


		<!-- SETUP KETENTUAN GENERAL + AJK -->
		<div class="GENERALAJK box">
<label class="col-sm-2 control-label">&nbsp;</label>
			<div class=" col-sm-10">
				<div class="panel panel-success">
            		<div class="panel-heading"><h3 class="panel-title">AJK + General</h3></div>
					<div class="form-group">
					<label class="col-sm-2 control-label">List General <span class="text-danger">*</span></label>
						<div class="col-sm-10">';
	$metGen = $database->doQuery('SELECT ajkgeneraltype.id,
										 ajkgeneraltype.idb,
										 ajkgeneraltype.type
								  FROM ajkcobroker
								  INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb
								  WHERE ajkcobroker.id = "'.$q['idbroker'].'" AND
								  		ajkcobroker.del IS NULL AND ajkgeneraltype.keterangan="GENERAL + AJK"
								  ORDER BY ajkgeneraltype.type ASC');
	while ($metGen_ = mysql_fetch_array($metGen)) {
		$genList = mysql_fetch_array($database->doQuery('SELECT * FROM ajkpolis WHERE id="'.$metPolicy['id'].'" AND idgeneral="'.$metGen_['id'].'"'));
		if ($genList['idgeneral']) {
			//echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).' checked disabled><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
			echo '<span class="radio custom-radio custom-radio-primary">
				  <input type="radio"'.pilih($metPolicy['idgeneral'], $genList['idgeneral']).' name="listgeneral" id="customradioGLG'.$metGen_['id'].'" value="'.$metGen_['id'].'"><label for="customradioGLG'.$metGen_['id'].'">&nbsp;&nbsp;'.$metGen_['type'].'&nbsp;&nbsp;</label>
				  </span>';
		}else{
			//echo '<p><label class="switch switch-sm switch-success"><input type="checkbox" name="listgeneral[]" value="'.$metGen_['id'].'"'.pilih($_REQUEST['listgeneral'], $metGen_['id']).'><span class="switch"></span><span class="text ml-xs">&nbsp; '.$metGen_['type'].'</span></label></p>';
			echo '<span class="radio custom-radio custom-radio-primary">
				  <input type="radio"'.pilih($metPolicy['idgeneral'], $genList['idgeneral']).' name="listgeneral" id="customradioGLG'.$metGen_['id'].'" value="'.$metGen_['id'].'" disabled><label for="customradioGLG'.$metGen_['id'].'">&nbsp;&nbsp;'.$metGen_['type'].'&nbsp;&nbsp;</label>
				  </span>';
		}
	}
	echo '</div>
				</div>
				<div class="form-group">
           		<label class="col-sm-2 control-label">Rate Method By <span class="text-danger">*</span></label>
           			<div class="col-sm-10">
                   	<span class="radio custom-radio custom-radio-primary">
                   		<input type="radio"'.pilih($metPolicy['byrategeneral'], "Plafond").' name="ratemethodgeneral" id="customradioG3" value="Plafond"><label for="customradioG3">&nbsp;&nbsp;Plafond&nbsp;&nbsp;</label>
                   		<input type="radio"'.pilih($metPolicy['byrategeneral'], "Tenor").' name="ratemethodgeneral" id="customradioG4" value="Tenor"><label for="customradioG4">&nbsp;&nbsp;Tenor</label>
                   	</span>
					</div>
				</div>
					<div class="form-group">
            		<label class="col-sm-2 control-label">Use Type Class <span class="text-danger">*</span></label>
            			<div class="col-sm-10">
                    	<span class="radio custom-radio custom-radio-primary">
                    		<input type="radio"'.pilih($metPolicy['classgeneral'], "Ya").' name="generalclass" id="customradioG7" value="Ya"><label for="customradioG7">&nbsp;&nbsp;Ya&nbsp;&nbsp;</label>
                    		<input type="radio"'.pilih($metPolicy['classgeneral'], "Tidak").' name="generalclass" id="customradioG8" value="Tidak"><label for="customradioG8">&nbsp;&nbsp;Tidak</label>
                    	</span>
						</div>
					</div>
            	</div>
            </div>
		</div>
		<!-- SETUP KETENTUAN GENERAL + AJK -->
			<div class="form-group">
				<label class="col-sm-2 control-label">Company <span class="text-danger">*</span></label>
				<div class="col-sm-10">
            	<select name="pcompany" class="form-control" required>
            		<option value="">Select Company</option>';
while ($metClient_ = mysql_fetch_array($metClient)) {
echo '<option value="'.$metClient_['id'].'"'._selected($metClient_['id'], $metPolicy['idcost']).'>'.$metClient_['name'].'</option>';
}
echo '			</select>
	        	</div>
        	</div>
			<div class="form-group">
					<label class="col-sm-2 control-label">Product Name <span class="text-danger">*</span></label>
					<div class="col-sm-10"><input type="text" name="productname" value="'.$metPolicy['produk'].'" class="form-control" placeholder="Product Name" required></div>
			</div>
			<div class="form-group">
            <label class="col-sm-2 control-label">Type Product <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['typemedical'], "SKKT").' name="typemedical" id="customradiotype1" value="SKKT" required><label for="customradiotype1">&nbsp;&nbsp;SKKT&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['typemedical'], "SPK").' name="typemedical" id="customradiotype2" value="SPK" required><label for="customradiotype2">&nbsp;&nbsp;SPK</label>
                    </span>
				</div>
			</div>
			<div class="form-group">
	            <label class="control-label col-sm-2">Agreement Number</label>
            	<div class="col-sm-10">
                <div class="row mb5"><div class="col-sm-12"><input name="autopolicy" value="'.$metPolicy['policyauto'].'" type="text" class="form-control" placeholder="Agreement System" disabled></div></div>
				<div class="row mb5"><div class="col-sm-12"><input name="manualpolicy" value="'.$metPolicy['policymanual'].'" type="text" class="form-control" placeholder="Agreement Manual"></div></div>
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-2 control-label">Date of Agreement <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<div class="row">
                    <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'._convertDate3($metPolicy['start_date']).'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'._convertDate3($metPolicy['end_date']).'" placeholder="to" required/></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Bank for Debit Note <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-3 pc5"><input name="dnbanknmaccount" value="'.$metPolicy['bankdebitnotenama'].'" type="text" class="form-control" placeholder="Name Account" required></div>
                	<div class="col-xs-3 pr5"><input name="dnbankname" value="'.$metPolicy['bankdebitnote'].'" type="text" class="form-control" placeholder="Bank" required></div>
                	<div class="col-xs-3 pc5"><input name="dnbankbranch" value="'.$metPolicy['bankdebitnotecabang'].'" type="text" class="form-control" placeholder="Branch" required></div>
                    <div class="col-xs-3 pl5"><input name="dnbankaccount" value="'.$metPolicy['bankdebitnoteaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Bank for Creditnote <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-3 pr5"><input name="cnbanknmaccount" value="'.$metPolicy['bankcreditnotenama'].'" type="text" class="form-control" placeholder="Name Account" required></div>
                	<div class="col-xs-3 pr5"><input name="cnbankname" value="'.$metPolicy['bankcreditnote'].'" type="text" class="form-control" placeholder="Bank" required></div>
                	<div class="col-xs-3 pc5"><input name="cnbankbranch" value="'.$metPolicy['bankcreditnotecabang'].'" type="text" class="form-control" placeholder="Branch" required></div>
                    <div class="col-xs-3 pl5"><input name="cnbankaccount" value="'.$metPolicy['bankcreditnoteaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Rate Premium<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-6 pr5">
                		<select name="typerate" class="form-control" required>
                        <option value="">Select Type Rate</option>
                        <option value="Decrease"'._selected($metPolicy["typerate"], "Decrease").'>Decrease</option>
                        <option value="Flat"'._selected($metPolicy["typerate"], "Flat").'>Flat</option>
                        </select>
					</div>
                	<div class="col-xs-6 pr5">
                		<select name="byrate" class="form-control" required>
                        <option value="">Select By Rate</option>
                        <option value="Age"'._selected($metPolicy["byrate"], "Age").'>Age</option>
                        <option value="Table"'._selected($metPolicy["byrate"], "Table").'>Table</option>
                        </select>
					</div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Age Birthday<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                		<select name="agecalculateday" class="form-control" required>
                        <option value="">Age Calculatede Day</option>
                        <option value="183"'._selected($metPolicy["agecalculateday"], "183").'>Nearest Birthday</option>
                        <option value="366"'._selected($metPolicy["agecalculateday"], "366").'>Last Birthday</option>
                        <option value="0"'._selected($metPolicy["agecalculateday"], "0").'>Next Birthday</option>
                        </select>
				</div>
			</div>
            <div class="form-group">
            <label class="col-sm-2 control-label">Calculated Rate <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['calculatedrate'], "100").' name="ratecalculate" id="customradio1" value="100" required><label for="customradio1">&nbsp;&nbsp;100 (persen)&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['calculatedrate'], "1000").' name="ratecalculate" id="customradio2" value="1000" required><label for="customradio2">&nbsp;&nbsp;1000 (permil)</label>
                    </span>
				</div>
			</div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Formula Refund<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['refundrate'], "Table").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio3" value="Table" required><label for="customradio3">&nbsp;&nbsp;by Table Rate Refund&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['refundrate'], "Percentage").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio4" value="Percentage" required><label for="customradio4">&nbsp;&nbsp;by Percentage</label>
	                    <div id="ifYes" '.($metPolicy["raterefund"]=="customradio3" ? " style=\"display:block\"":" style=\"display:none\"").' required>
						<div class="row mb5"><div class="col-sm-12"><input name="percentageRefund" class="form-control" value="'.$metPolicy['refundpercentage'].'" data-parsley-type="number" type="text" placeholder="Percentage Refund"></div></div>
						</div>
                    </span>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Formula Claim<span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['klaimrate'], "Table").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio5" value="Table" required><label for="customradio5">&nbsp;&nbsp;by Table Rate Claim&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['klaimrate'], "Percentage").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio6" value="Percentage" required><label for="customradio6">&nbsp;&nbsp;by Percentage</label>
	                    <div id="ifAsik" '.($metPolicy["raterefund"]=="customradio6" ? " style=\"display:block\"":" style=\"display:none\"").' required>
						<div class="row mb5"><div class="col-sm-12"><input name="percentageClaim" class="form-control" value="'.$metPolicy['klaimpercentage'].'" data-parsley-type="number" type="text" placeholder="Percentage Claim"></div></div>
						</div>
                    </span>
				</div>
            </div>

            <div class="form-group">
            	<label class="col-sm-2 control-label">Cancel Member <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="jumlahharibatal" value="'.$metPolicy['jumlahharibatal'].'" placeholder="Day" required></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Last Day Covered <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="bs-touchspin-basic" value="'.$metPolicy['lastdayinsurance'].'" placeholder="Day" required></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">W.P.C <span class="text-danger">*</span></label>
                <div class="col-sm-2"><input type="text" name="bs-touchspin-wpc" value="'.$metPolicy['wpc'].'" placeholder="Day" required></div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Plafond <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="plafondfrom" class="form-control" data-parsley-type="number" value="'.$metPolicy['plafondstart'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="plafondto" class="form-control" data-parsley-type="number" value="'.$metPolicy['plafondend'].'" placeholder="to" required/></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Age <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="agefrom" class="form-control" data-parsley-type="number" value="'.$metPolicy['agestart'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="ageto" class="form-control" data-parsley-type="number" value="'.$metPolicy['ageend'].'" placeholder="to" required/></div>
				</div>
				</div>
            </div>
			<div class="form-group">
	            <label class="control-label col-sm-2">Range Tenor <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-6"><input type="text" name="tenormin" class="form-control" data-parsley-type="number" value="'.$metPolicy['tenormin'].'" placeholder="Minimum Tenor" required/></div>
                    <div class="col-md-6"><input type="text" name="tenormax" class="form-control" data-parsley-type="number" value="'.$metPolicy['tenormax'].'" placeholder="Maksimum Tenor" required/></div>
				</div>
				</div>
            </div>
            <div class="form-group">
	            <label class="control-label col-sm-2">Minimum Premium</label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-12"><input type="text" name="minpremi" class="form-control" data-parsley-type="number" value="'.$metPolicy['minimumpremi'].'" placeholder="Minimum Premium" required/></div>
				</div>
				</div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Admin Fee</label>
            	<div class="col-sm-10">
				<div class="row">
                    <div class="col-md-12"><input type="text" name="adminfee" class="form-control" data-parsley-type="number" value="'.$metPolicy['adminfee'].'" placeholder="Admin Fee" required/></div>
				</div>
				</div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Discount</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-discount" value="'.$metPolicy['diskon'].'" placeholder="Discount"></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">Brokrage</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-brokrage" value="'.$metPolicy['brokrage'].'" placeholder="Brokrage"></div>
				<label class="col-sm-2 control-label">Gross Bank Premium</label>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">R.M.F <span class="text-danger">*</span></label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-rmf" value="'.$metPolicy['rmf'].'" placeholder="R.M.F" required></div>
				<label class="col-sm-2 control-label">Gross Bank Premium</label>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">PPN</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-ppn" value="'.$metPolicy['ppn'].'" placeholder="PPN"></div>
            </div>

			<div class="form-group">
            	<label class="col-sm-2 control-label">PPh</label>
                <div class="col-sm-3"><input type="text" name="bs-touchspin-pph" value="'.$metPolicy['pph'].'" placeholder="PPh"></div>
            </div>

            <div class="form-group">
            <label class="col-sm-2 control-label">Mode Payment <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['paymentmode'], "FrontPayment").' name="paymentmode" id="customradio13" value="FrontPayment" required><label for="customradio13">&nbsp;&nbsp;FrontPayment&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['paymentmode'], "BackPayment").' name="paymentmode" id="customradio14" value="BackPayment" required><label for="customradio14">&nbsp;&nbsp;BackPayment</label>
                    </span>
				</div>
			</div>

			<div class="form-group">
            <!--<label class="col-sm-2 control-label">P H K <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['phk'], "Y").' name="phk" id="customradio22" value="Y" required><label for="customradio22">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['phk'], "T").' name="phk" id="customradio23" value="T" required><label for="customradio23">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

			<div class="form-group">
            <label class="col-sm-2 control-label">Wanprestasi <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['wp'], "Y").' name="wp" id="customradio20" value="Y" required><label for="customradio20">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['wp'], "T").' name="wp" id="customradio21" value="T" required><label for="customradio21">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>-->

            <!--<div class="form-group">
            <label class="col-sm-2 control-label">Free Covered <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['freecover'], "Y").' name="freecover" id="customradio7" value="Y" required><label for="customradio7">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['freecover'], "T").' name="freecover" id="customradio8" value="T" required><label for="customradio8">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>-->

            <div class="form-group">
            <label class="col-sm-2 control-label">Upload Photo <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['setphoto'], "Y").' name="uploadphoto" id="customradio9" value="Y" required><label for="customradio9">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['setphoto'], "T").' name="uploadphoto" id="customradio10" value="T" required><label for="customradio10">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>

            <div class="form-group">
            <label class="col-sm-2 control-label">Upload KTP <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['setktp'], "Y").' name="uploadktp" id="customradio11" value="Y" required><label for="customradio11">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['setktp'], "T").' name="uploadktp" id="customradio12" value="T" required><label for="customradio12">&nbsp;&nbsp;No</label>
                    </span>
				</div>
			</div>
	      <div class="form-group">
            <label class="col-sm-2 control-label">Level Validation User <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metPolicy['levelvalidasi'], "1").' name="valuser" id="customradio15" value="1" required><label for="customradio15">&nbsp;&nbsp;1&nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metPolicy['levelvalidasi'], "2").' name="valuser" id="customradio16" value="2" required><label for="customradio16">&nbsp;&nbsp;2</label>
                    	<input type="radio"'.pilih($metPolicy['levelvalidasi'], "3").' name="valuser" id="customradio17" value="3" required><label for="customradio17">&nbsp;&nbsp;3</label>
                    </span>
				</div>
			</div>
		</div>';
if ($metPolicy['filepks'] !="") {
	$cekFilePks = '';
	$cekFilePks1 ='';
}else{
	$cekFilePks = 'required';
	$cekFilePks1 = '<span class="text-danger">*</span>';
}

if ($metPolicy['filedeklarasi'] !="") {
	$cekFileDekl = '';
	$cekFileDekl1 ='';
}else{
	$cekFileDekl = 'required';
	$cekFileDekl1 = '<span class="text-danger">*</span>';
}
echo '<div class="form-group">
		<label class="col-sm-2 control-label">File PKS '.$cekFilePks1.'</label>
		<div class="col-sm-10"><input type="file" name="filePKS" accept="application/pdf" '.$cekFilePks.'></div>
	</div>
	<div class="form-group">
    	<label class="col-sm-2 control-label">File Declaration '.$cekFileDekl1.'</label>
        <div class="col-sm-10"><input type="file" name="filedeklarasi" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet (.xlsx)" '.$cekFileDekl.'></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="editmepolis">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
case "polview":
$metPolicy = mysql_fetch_array($database->doQuery('SELECT *, IF(freecover="Y", "Ya", "Tidak") AS freecover,
															 IF(setphoto="Y", "Ya", "Tidak") AS setphoto,
															 IF(setktp="Y", "Ya", "Tidak") AS setktp
											 		FROM ajkpolis WHERE id="'.$thisEncrypter->decode($_REQUEST['pid']).'"'));
$metComp = mysql_fetch_array($database->doQuery('SELECT * FROM ajkclient WHERE id="'.$metPolicy['idcost'].'"'));
if ($metComp['logo']=="") {
	$logoclient = '<img class="img-circle img-bordered" src="../'.$PathPhoto.'logo.png" alt="" width="75px">';
}else{
	$logoclient = '<img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px">';
}
echo '<div class="page-header-section"><h2 class="title semibold">Agreement</h2></div>
	<div class="page-header-section">
	<div class="toolbar"><a href="ajk.php?re=client&op=policy">'.BTN_BACK.'</a></div>
	</div>
</div>';
echo '<div class="row">
		<div class="col-lg-12">
	        	<div class="tab-content">
	            	<div class="tab-pane active" id="profile">
	                <form class="panel form-horizontal form-bordered" name="form-profile" method="post" action="">
						<div class="panel-body pt0 pb0">
	                    	<div class="form-group header bgcolor-default">
	                        	<div class="col-md-12">
	            					<ul class="list-table">
	            					<li style="width:80px;">'.$logoclient.'</li>
									<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4></li>
									</ul>
								</div>
	                        </div>
							<div class="form-group">
	                            <div class="col-xs-6 col-sm-6 col-md-6">
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Agreement Number <code>System</code></a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['policyauto'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Product</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['produk'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Start Date</a></p></div></div>
	                                <div class="text-default"><p>'._convertDate($metPolicy['start_date']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Bank Debit Note</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['bankdebitnote'].'</p><p>'.$metPolicy['bankdebitnotecabang'].'</p><p>'.$metPolicy['bankdebitnoteaccount'].'</p></div>
	                            </div>
	                            <div class="col-xs-6 col-sm-6 col-md-6">
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Agreement Number <code>Manual</code></a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['policymanual'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">File Agreement</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['filepks'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">End Date</a></p></div></div>
	                                <div class="text-default"><p>'._convertDate($metPolicy['end_date']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Bank Creditnote</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['bankcreditnote'].'</p><p>'.$metPolicy['bankcreditnotecabang'].'</p><p>'.$metPolicy['bankcreditnoteaccount'].'</p></div>
	                            </div>
	                            <div class="col-xs-6 col-sm-6 col-md-6">
	                            </div>
	                        </div>

							<div class="form-group header bgcolor-default">
                            <div class="col-md-12"><h4 class="semibold text-primary nm">Rate Agreement</h4></div>
                            </div>
							<div class="form-group">
	                            <div class="col-xs-6 col-sm-6 col-md-6">
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Plafond From</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['plafondstart']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Calculated Rate</a></p></div></div>
	                                <div class="text-default"><p>/'.duit($metPolicy['calculatedrate']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Refund Rate</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['refundrate'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Age From</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['agestart']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Fee Admin</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['adminfee']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Brokrage</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['brokrage']).'%</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">PPN</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['ppn']).'%</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Last Date Insurance</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['lastdayinsurance'].' day</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Upload Photo dan KTP</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['setphoto'].' | '.$metPolicy['setktp'].'</p></div>
	                            </div>
	                            <div class="col-xs-6 col-sm-6 col-md-6">
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Plafond To</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['plafondend']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Free Covered</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['freecover'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Claim Rate</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['klaimrate'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Age To</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['ageend']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Minimum Premi</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['minimumpremi']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Share Insurance</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['shareins']).'%</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Discount</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['diskon']).'%</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">PPh</a></p></div></div>
	                                <div class="text-default"><p>'.duit($metPolicy['pph']).'%</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">W.P.C</a></p></div></div>
	                                <div class="text-default"><p>'.$metPolicy['wpc'].' days</p></div>
	                            </div>
	                            <div class="col-xs-6 col-sm-6 col-md-6">
	                            </div>
	                        </div>
	                    	<div class="form-group header bgcolor-default">
                            <div class="col-md-12"><h4 class="semibold text-primary nm">Share Premi Agreement to Insurance</h4></div>
                            </div>
                        	<div class="form-group">';
$metCekIns = mysql_fetch_array($database->doQuery('SELECT * FROM ajkinsuranceshare WHERE idpolicy = "'.$metPolicy['id'].'" AND del IS NULL'));
if (!$metCekIns['id']) {
echo '<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="alert alert-dismissable alert-warning text-center">
		<p class="mb10"><strong>Insurance not share!</strong> No insurance selected in Agreement setup.</p>
		<input type="hidden" name="pid" value="'.$thisEncrypter->encode($metPolicy['id']).'">
		<input type="hidden" name="op" value="polshareins">
		'.BTN_SHAREINS.'
		</div>
	  </div>';
}else{
echo '<div class="col-xs-12 col-sm-12 col-md-12">
		<input type="hidden" name="pid" value="'.$thisEncrypter->encode($metPolicy['id']).'">
		<input type="hidden" name="op" value="polshareins">
		'.BTN_SHAREINS.'
<table class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
		<th>Insurance</th>
		<th width="20%">Share</th>
		<th width="1%">Delete</th>
	</tr>
	</thead>
<tbody>';
$metShareAs = $database->doQuery('SELECT ajkinsuranceshare.id, ajkinsuranceshare.idpolicy, ajkinsurance.`name`, ajkinsuranceshare.sharepremi
								  FROM ajkinsuranceshare
								  INNER JOIN ajkinsurance ON ajkinsuranceshare.idins = ajkinsurance.id
								  WHERE ajkinsuranceshare.idpolicy = "'.$metPolicy['id'].'"');
while ($metShareAs_ = mysql_fetch_array($metShareAs)) {
echo '<tr>
	   	<td>'.$metShareAs_['name'].'</td>
   		<td align="center">'.$metShareAs_['sharepremi'].'</td>
   		<td align="center"><a href="ajk.php?re=exl&op=editfield&fid='.$thisEncrypter->encode($metShareAs_['id']).'">'.BTN_DEL.'</a></td>
    </tr>';
}
echo '</tbody>
    </table></div>';
}

echo '						</div>
						</div>
	                </form>
	                </div>
				</div>
	        </div>
	    </div>';
	;
	break;

case "polshareins":
echo '<div class="page-header-section"><h2 class="title semibold">Share Insurance</h2></div>
      	<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=client&op=polview&pid='.$_REQUEST['pid'].'">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$met = mysql_fetch_array($database->doQuery('SELECT ajkpolis.id, ajkclient.`name`, ajkpolis.policyauto, ajkpolis.policymanual, ajkpolis.produk, ajkpolis.shareins, ajkpolis.rmf, ajkclient.logo, ajkclient.idc
											 FROM ajkpolis
											 INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
											 WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['pid']).'"'));
if ($met['rmf']=="") {	$_rmf ='';	}else{	$_rmf = '<dt>R.M.F</dt><dd>'.$met['rmf'].'%</dd>';	}
echo '<div class="row">
		<div class="col-md-12">
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['logo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Company</dt><dd>'.$met['name'].'</dd>
				<dt>Agreement</dt><dd>'.$met['policyauto'].'</dd>
				<dt>Product</dt><dd>'.$met['produk'].'</dd>
				'.$_rmf.'
			</dl>
		</div>
	</div>

	<div class="row">
      	'.$metnotif.'
			<div class="col-md-12">';
echo '<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
		<div class="panel-heading"><h3 class="panel-title">Share Insurance</h3></div>
			<div class="panel-body">
<table class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
		<th>Insurance</th>
		<th width="20%">Share</th>
	</tr>
	</thead>
<tbody>';
$metAs = $database->doQuery('SELECT * FROM ajkinsurance WHERE idc="'.$met['idc'].'" ORDER BY name DESC');
echo '<input type="hidden" name="pid" value="'.$met['id'].'">
	<tr>
<td><select name="Ins" class="form-control">
		<option value="">Select Insurance</option>';
while ($metAs_ = mysql_fetch_array($metAs)) {
$metAsCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkinsuranceshare WHERE idpolicy="'.$met['id'].'" AND  idins="'.$metAs_['id'].'"'));
if ($metAsCek['id']) {
echo '<option value="'.$metAs_['id'].'"'._selected($_REQUEST['Ins'], $metAs_['id']).' disabled>'.$metAs_['name'].'</option>';
}else{
echo '<option value="'.$metAs_['id'].'"'._selected($_REQUEST['Ins'], $metAs_['id']).'>'.$metAs_['name'].'</option>';
}

}
echo '</select></td>
	<td align="center">
		<div class="form-group">
        <div class="col-sm-12"><input type="text" name="bs-touchspin-shareins" value="'.$_REQUEST['bs-touchspin-shareins'].'" placeholder="Insurance"></div>
        </div>
    </td>
	</tr>';

echo '</table>
            <div align="center" class="panel-footer"><input type="hidden" name="op" value="savemeShareIns">'.BTN_SUBMIT.'</div>
           </form>
		</div>
        </div>
    </div>
</div>';
	;
	break;

case "savemeShareIns":
if (!$_REQUEST['Ins'] OR !$_REQUEST['bs-touchspin-shareins']) {
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($_REQUEST['pid']).'">
	  <div class="alert alert-dismissable alert-danger">
		<strong>Error!</strong> Please selected insurance name or share for premi. !
	  </div>';
}else{
//$met = $database->doQuery('INSERT INTO ajkinsuranceshare SET idpolicy="'.$_REQUEST['pid'].'", idins="'.$_REQUEST['Ins'].'", sharepremi="'.duit($_REQUEST['bs-touchspin-shareins']).'", input_by="'.$q['id'].'", input_time="'.$futgl.'"');
$metCekShare = mysql_fetch_array($database->doQuery('SELECT ajkinsuranceshare.id,
															ajkinsuranceshare.idpolicy,
															ajkinsurance.`name`,
															SUM(ajkinsuranceshare.sharepremi) AS tSharePremi,
															ajkpolis.shareins
													FROM ajkinsuranceshare
													INNER JOIN ajkinsurance ON ajkinsuranceshare.idins = ajkinsurance.id
													INNER JOIN ajkpolis ON ajkinsuranceshare.idpolicy = ajkpolis.id
													WHERE ajkinsuranceshare.idpolicy = "'.$_REQUEST['pid'].'"
													GROUP BY ajkinsuranceshare.idpolicy'));
//echo $metCekShare['tSharePremi'].'-'.$metCekShare['shareins'].' - '.$_REQUEST['bs-touchspin-shareins'].'<br />';
//$mtTotalShare = $_REQUEST['bs-touchspin-shareins'] + $metCekShare['tSharePremi']; 31052016
$mtTotalShare = $_REQUEST['bs-touchspin-shareins'] + 100 ;
//echo $mtTotalShare;
	if (!$metCekShare['id']) {
		$met = $database->doQuery('INSERT INTO ajkinsuranceshare SET idpolicy="'.$_REQUEST['pid'].'", idins="'.$_REQUEST['Ins'].'", sharepremi="'.duit($_REQUEST['bs-touchspin-shareins']).'", input_by="'.$q['id'].'", input_time="'.$futgl.'"');
		echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($_REQUEST['pid']).'">
			  <div class="alert alert-dismissable alert-success">
				<strong>Success!</strong> Share premi insurance '.duit($_REQUEST['bs-touchspin-shareins']).'%.
			  </div>';
	}else{
	if ($mtTotalShare > $metCekShare['shareins']) {
	echo '<meta http-equiv="refresh" content="3; url=ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($_REQUEST['pid']).'">
		  <div class="alert alert-dismissable alert-danger">
			<strong>Error!</strong> Shere premi insurance is greather than 100%. !
	  	</div>';
	}else{
	$met = $database->doQuery('INSERT INTO ajkinsuranceshare SET idpolicy="'.$_REQUEST['pid'].'", idins="'.$_REQUEST['Ins'].'", sharepremi="'.duit($_REQUEST['bs-touchspin-shareins']).'", input_by="'.$q['id'].'", input_time="'.$futgl.'"');
	echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($_REQUEST['pid']).'">
		  <div class="alert alert-dismissable alert-success">
			<strong>Success!</strong> Share premi insurance '.duit($_REQUEST['bs-touchspin-shareins']).'%.
		  </div>';
	}
	}
}
	;
	break;

case "rtgeneral":
/*
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold">Agreement</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=client&op=policy">'.BTN_BACK.'</a></div>
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' [General]</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
$cekRateGen = mysql_fetch_array($database->doQuery('SELECT ajkpolis.id AS produkid, ajkrategeneral.id AS rateidgen FROM ajkpolis INNER JOIN ajkrategeneral ON ajkpolis.id = ajkrategeneral.idproduk WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.status="Aktif"'));
if (!$cekRateGen['rateidgen']) {
echo '<div class="panel-toolbar text-center">
		<div class="col-md-12">';
	if (isset($_REQUEST['met'])=="saverateGen") {
		$fNameUpload = 'RATEGENERAL_'.$DatePolis.'_B'.$_REQUEST['coBroker'].'_C'.$_REQUEST['coClient'].'_P'.$_REQUEST['coPolicy'].'_USER'.$q['id'].'_'.$_FILES['fileRate']['name'];
		$namafile =  $_FILES['fileRate']['tmp_name'];
		//echo $namafile;
		$ext = pathinfo($namafile, PATHINFO_EXTENSION);
		$file_info = pathinfo($namafile);
		$file_extension = $file_info["extension"];
		$namefile = $file_info["filename"].'.'.$file_extension;
		$inputFileName = $namafile;
		$_SESSION['file_temp'] = $namefile;
		$_SESSION['file_name'] = $_FILES['fileRate']['name'];
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());
		}

echo '<div class="col-md-12">
		<dl class="dl-horizontal">
			<div class="col-md-12"><dt class="text-left">Filename Upload</dt><dd class="text-left">'.$_FILES['fileRate']['name'].'</dd></div>
		</dl>
		<dl class="dl-vertical">
			<div class="col-md-4"><dt class="text-left">Location</dt><dd class="text-left">(1) = Kalimantan<br />(2) = Outside Kalimantan</dd></div>
			<div class="col-md-4"><dt class="text-left">Guarantee</dt><dd class="text-left">(1) = Home<br />(2) = Apartement<br />(3) = Occupational shop/store</dd></div>
			<div class="col-md-4"><dt class="text-left">Class</dt><dd class="text-left">(1) = Class 1<br />(2) = Class 2<br />(3) = Class 3</dd></div>
		</dl>
		<dl class="dl-vertical">
			<div class="col-md-12"><dt class="text-left">&nbsp;</dt></div>
		</dl>
	</div>';
		//Table used to display the contents of the file
echo '<div class="panel-body">
		<table class="table table-bordered table-hover" id="table-upload"  width="100%">';
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
echo '<thead >
	<tr class="primary"><th class="text-center">No</th>
						<th class="text-center">Tenor From (month)</th>
						<th class="text-center">Tenor To (month)</th>
						<th class="text-center">Location</th>
						<th class="text-center">Guarantee</th>
						<th class="text-center">Class</th>
						<th class="text-center">Rate Fire</th>
						<th class="text-center">Rate PA</th>
	</tr>
	</thead><tbody>';

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		for ($row = 2; $row <= $highestRow; $row++) {
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			echo "<tr>";
			$i = 0;
			foreach($rowData[0] as $k=>$v){
				$data[$i] = $v;
				$i++;
			}
			$today = date('Y-m-d');
			//$_data1 = $data[0];
			$_data2 = $data[1];	//TENOR FROM
			$_data3 = $data[2];	//TENOR TO
			$_data4 = $data[3];	//LOKASI
			$_data5 = $data[4];	//PERTANGGUNGAN
			$_data6 = $data[5];	//KELAS
			$_data7 = $data[6];	//RATE FIRE
			$_data8 = $data[7];	//RATE PA

			if ($_data2=="" OR !is_numeric($_data2)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = $_data2;	}
			if ($_data3=="" OR !is_numeric($_data3)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = $_data3;	}
			if ($_data4=="" OR !is_numeric($_data4)) {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = $_data4;	}
			if ($_data5=="" OR !is_numeric($_data5)) {	$ErrorEXL4 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = $_data5;	}
			if ($_data6=="" OR !is_numeric($_data6)) {	$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;	}else{	$dataEXL5 = $_data6;	}
			if ($_data7=="" OR !is_numeric($_data7)) {	$ErrorEXL6 = '<span class="label label-danger">Error</span>';	$dataEXL6 = $ErrorEXL6;	}else{	$dataEXL6 = $_data7;	}
			if ($_data8=="" OR !is_numeric($_data8)) {	$ErrorEXL7 = '<span class="label label-danger">Error</span>';	$dataEXL7 = $ErrorEXL7;	}else{	$dataEXL7 = $_data8;	}

			echo "<td>".++$no." </td>";
			echo "<td>".$dataEXL1." </td>";
			echo "<td>".$dataEXL2." </td>";
			echo "<td>".$dataEXL3." </td>";
			echo "<td>".$dataEXL4." </td>";
			echo "<td>".$dataEXL5." </td>";
			echo "<td>".$dataEXL6." </td>";
			echo "<td>".$dataEXL7." </td>";
			echo "</tr>";
		}
		if($ErrorEXL1 OR $ErrorEXL2 OR $ErrorEXL3 OR $ErrorEXL4 OR $ErrorEXL5 OR $ErrorEXL6 OR $ErrorEXL7){
		echo '<div align="center" class="col-md-12"><a href="ajk.php?re=exsist&exs=Xls2">'.BTN_UPLOADERROR.'</a></div>';
		}else{
			$direktori = "../$PathRate$fNameUpload"; // direktori tempat menyimpan file
			move_uploaded_file($namafile,$direktori);
			echo '<div align="right" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneraldel&idp='.$thisEncrypter->encode($metGen['clientid']).'&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_BACK2.'</a></div>
				  <div align="left" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneralsave&idb='.$thisEncrypter->encode($metGen['brokerid']).'&idc='.$thisEncrypter->encode($metGen['clientid']).'&idp='.$thisEncrypter->encode($metGen['produkid']).'&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_SUBMIT.'</a></div>';
		}
		echo '</tbody></table>
	</div></form>';
	}else{
		echo '<form method="post" action="#" data-parsley-validate enctype="multipart/form-data">
			<p class="mb10"><strong><code>Upload rate General from product '.$metGen['produk'].'.</code></p>
			<input type="hidden" name="idb" value="'.$thisEncrypter->encode($metGen['brokerid']).'">
			<input type="hidden" name="idc" value="'.$thisEncrypter->encode($metGen['clientid']).'">
			<input type="hidden" name="idp" value="'.$thisEncrypter->encode($metGen['produkid']).'">
			<div class="form-group">
				<div class="col-sm-12"><center><input type="file" name="fileRate" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></center></div>
				<div class="col-sm-12"><br /><input type="hidden" name="met" value="saverateGen">'.BTN_UPLRATEGENERAL.'</div>
			</form>';
	}
echo '</div><br />
	</div>';
}
else{
if (isset($_REQUEST['dis'])=="rategen") {
	$metGeneral = $database->doQuery('UPDATE ajkrategeneral SET status="NonAktif", update_by="'.$q['id'].'", update_time="'.$futgl.'" WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'"');
}
$met_= mysql_fetch_array($database->doQuery('SELECT ajkpolis.id, ajkpolis.idcost, ajkclient.idc
											 FROM ajkpolis
											 INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
											 INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
											 WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" '));
$metRateGeneral = $database->doQuery('SELECT * FROM ajkrategeneral WHERE idbroker="'.$met_['idc'].'" AND idclient="'.$met_['idcost'].'" AND idproduk="'.$met_['id'].'" AND status="Aktif"');
echo '<div class="col-xs-12 col-sm-12 col-md-12">
	<div class="panel panel-default" id="demo">
    	<div class="panel-heading"><h3 class="panel-title">Table Rate General</h3>
    		<div class="panel-toolbar text-right"><a href="ajk.php?re=client&op=rtgeneral&dis=rategen&idp='.$thisEncrypter->encode($met_['id']).'" onClick="if(confirm(\'Delete this rate general ?\')){return true;}{return false;}">'.BTN_DEL.'</a></div>
        </div>
        <table class="table table-striped table-bordered" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="10%">Tenor Start</th>
        <th width="10%">Tenor End</th>
        <th width="10%">Location</th>
        <th width="10%">Guarantee</th>
        <th width="10%">Class</th>
        <th width="10%">Rate Fire</th>
        <th width="10%">Rate PA</th>
        </tr>
    </thead>
    <tbody>';
while ($metRateGeneral_ = mysql_fetch_array($metRateGeneral)) {
echo '<tr>
	<td align="center">'.++$no.'</td>
	<td>'.$metRateGeneral_['tenorstart'].'</td>
	<td>'.$metRateGeneral_['tenorend'].'</td>
	<td align="center">'.$metRateGeneral_['lokasi'].'</td>
	<td align="center">'.$metRateGeneral_['quarantee'].'</td>
	<td align="center">'.$metRateGeneral_['kelas'].'</td>
	<td align="center"><span class="label label-primary">'.$metRateGeneral_['ratefire'].'</span></td>
	<td align="center"><span class="label label-primary">'.$metRateGeneral_['ratepa'].'</span></td>
</tr>';
}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Start"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="End"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Location"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Guaranteer"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Class"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
        </tr>
        </tfoot>
	</table>
        </div>
	</div>';
}
echo '</div>
</div>';


echo '</div>
		</div>
	</div>';
*/
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=policy">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM
ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkpolis.classgeneral,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));

echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
echo '<div class="col-md-12 text-center">&nbsp;</div>
<div class="col-md-12">
<ul class="nav nav-tabs nav-justified">
	<li class="btn btn-success btn-lg"><a href="#tabarea" data-toggle="tab"><font color="orange"><strong>Setup Area</strong></font></a></li>
    <li class="btn btn-primary btn-lg"><a href="#tabcategory" data-toggle="tab"><font color="orange"><strong>Setup Category</strong></font></a></li>
    <li class="btn btn-tale btn-lg"><a href="#tabclass" data-toggle="tab"><font color="orange"><strong>Setup Class</strong></font></a></li>
    <li class="btn btn-warning btn-lg"><a href="#tabrate" data-toggle="tab"><font color="orange"><strong>Upload Rate</button></strong></font></a></li>
    <li class="btn btn-danger btn-lg"><a href="#tabguarantee" data-toggle="tab"><font color="orange"><strong>Extendeed Coverage</strong></font></a></li>
</ul>
<div class="tab-content panel">
	<div class="tab-pane" id="tabarea">';
$cekAreaGnr = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralarea WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
if (!$cekAreaGnr['id']) {
echo '<div class="col-md-12">
		<div class="alert alert-danger fade in">
        <h4 class="semibold">Oucchh...Error data area!</h4>
        <p class="mb10">Data area from this product is empty.</p>
        <a href="ajk.php?re=client&op=gnrarea&idp='.$_REQUEST['idp'].'"><button type="button" class="btn btn-danger">Insert New Data</button></a>
        </div>
    </div>';
}else{
echo '<div class="toolbar"><a href="ajk.php?re=client&op=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_NEW.'</a></div>
	  <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="1%">Code</th>
        <th width="10%">Area</th>
        <th>Location</th>
        <th width="1%">Option</th>
        </tr>
    </thead>
    <tbody>';
$cekAreaGnr = $database->doQuery('SELECT * FROM ajkgeneralarea WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND del IS NULL');
while ($cekAreaGnr_ = mysql_fetch_array($cekAreaGnr)) {
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$cekAreaGnr_['kode'].'</td>
   	<td>'.$cekAreaGnr_['area'].'</td>
   	<td>'.$cekAreaGnr_['lokasi'].'</td>
   	<td align="center"><a href="ajk.php?re=client&op=gnareaedt&idp='.$_REQUEST['idp'].'&idarea='.$thisEncrypter->encode($cekAreaGnr_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
	<tfoot>
        <tr>
        	<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Code"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Area"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Location"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            </tr>
        </tfoot></table>';

}
echo '&nbsp;';
echo '    </div>';

echo '    <div class="tab-pane" id="tabcategory">';
$cekCategoryGnr = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralkategori WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND del IS NULL'));
if (!$cekCategoryGnr['id']) {
echo '<div class="col-md-12">
	<div class="alert alert-danger fade in">
	<h4 class="semibold">Oucchh...Error data category!</h4>
	<p class="mb10">Data category from this product is empty.</p>
	<a href="ajk.php?re=client&op=gncategory&idp='.$_REQUEST['idp'].'"><button type="button" class="btn btn-danger">Insert New Data</button></a>
	        </div>
	    </div>';
}else{
echo '<div class="toolbar"><a href="ajk.php?re=client&op=gncategory&idp='.$_REQUEST['idp'].'">'.BTN_NEW.'</a></div>
	  <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="1%">Code</th>
        <th width="10%">Type</th>
        <th width="1%">Option</th>
        </tr>
    </thead>
    <tbody>';
$cekCategoryGnr = $database->doQuery('SELECT * FROM ajkgeneralkategori WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND del IS NULL');
while ($cekCategoryGnr_ = mysql_fetch_array($cekCategoryGnr)) {
echo '<tr>
	   	<td align="center">'.++$no1.'</td>
	   	<td>'.$cekCategoryGnr_['kode'].'</td>
	   	<td>'.$cekCategoryGnr_['keterangan'].'</td>
	   	<td align="center"><a href="ajk.php?re=client&op=gncategoryedt&idp='.$_REQUEST['idp'].'&category='.$thisEncrypter->encode($cekCategoryGnr_['id']).'">'.BTN_EDIT.'</a></td>
	    </tr>';
			}
			echo '</tbody>
		<tfoot>
	        <tr>
	        	<th><input type="hidden" class="form-control" name="search_engine"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Code"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
	            <th><input type="hidden" class="form-control" name="search_engine"></th>
	            </tr>
	        </tfoot></table>';
}
echo '&nbsp;';
echo '    </div>';

echo '    <div class="tab-pane" id="tabclass">';
//$cekCategoryGnrCls = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralkelas WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND del IS NULL'));
if ($metGen['classgeneral']=="Tidak") {
echo '<div class="col-md-12">
		<div class="alert alert-danger fade in">
		<h4 class="semibold">No Class Data!</h4>
		<p class="mb10">Class data on this product is not selected</p>
        </div>
    </div>';
}else{
echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr><th width="1%">No</th>
	        <th width="100%">Class</th>
	        <th width="500%">Note</th>
	        <!--<th width="1%">Option</th>-->
	        </tr>
	    </thead>
	    <tbody>';
$cekCategoryGnrCls = $database->doQuery('SELECT * FROM ajkgeneralkelas');
while ($cekCategoryGnrCls_ = mysql_fetch_array($cekCategoryGnrCls)) {
echo '<tr><td align="center">'.++$no5.'</td>
		<td>'.$cekCategoryGnrCls_['kelas'].'</td>
		<td>'.$cekCategoryGnrCls_['keterangan'].'</td>
		<!--<td align="center"><a href="ajk.php?re=client&op=gncategoryedt&idp='.$_REQUEST['idp'].'&category='.$thisEncrypter->encode($cekCategoryGnrCls_['id']).'">'.BTN_EDIT.'</a></td>-->
	</tr>';
}
echo '</tbody></table>';
}
	echo '&nbsp;';
echo '</div>';

echo '<div class="tab-pane" id="tabrate">';
$cekRateGnr = mysql_fetch_array($database->doQuery('SELECT id, IF(type="CMP", "Comprehensive", "Total Loss Only") AS type FROM ajkrategeneral WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND status="Aktif"  AND del IS NULL'));
if (!$cekRateGnr['id']) {
echo '<div class="col-md-12">
	<div class="alert alert-danger fade in">
	<h4 class="semibold">Oucchh...Error data rate!</h4>
	<p class="mb10">Data rate from this product is empty.</p>
	<a href="ajk.php?re=client&op=gnrate&idp='.$_REQUEST['idp'].'"><button type="button" class="btn btn-danger">Upload New Rate</button></a> &nbsp;
	<a href="ajk.php?re=dlExcel&Rxls=rategeneral&idc='.$thisEncrypter->encode($metGen['clientid']).'&idp='.$thisEncrypter->encode($metGen['produkid']).'" target="_blank""><button type="button" class="btn btn-success">Download File Rate</button></a>
	        </div>
	    </div>';
}else{
echo '<table width="100%">
	  <tr><td width="50%">
	  	 <div class="toolbar"><a href="ajk.php?re=client&op=gnrate&idp='.$_REQUEST['idp'].'">'.BTN_NEW.'</a></div>
		 </td>
		 <td width="50%" align="right">
	  	 <div class="toolbar"><a href="ajk.php?re=client&op=gnratedel&idp='.$_REQUEST['idp'].'">'.BTN_DEL.'</a></div>
		 </td>
	  </tr>
	  </table>
	  <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="1%">Type</th>
        <th width="1%">Tenor Start</th>
        <th width="1%">Tenor End</th>
        <th width="1%">Plafond Start</th>
        <th width="1%">Plafond End</th>
        <th width="1%">Location</th>
        <th width="1%">Category</th>
        <th width="1%">Class</th>
        <th width="1%">Rate</th>
        <th width="1%">Option</th>
        </tr>
    </thead>
    <tbody>
    <div class="col-md-10">&nbsp;</div>';
$cekRateGnr = $database->doQuery('SELECT ajkrategeneral.id,
										 ajkrategeneral.idbroker,
										 ajkrategeneral.idclient,
										 ajkrategeneral.idproduk,
										 IF(ajkrategeneral.tenorstart IS NULL, "#", ajkrategeneral.tenorstart) AS tenorstart,
										 IF(ajkrategeneral.tenorend IS NULL, "#", ajkrategeneral.tenorend) AS tenorend,
										 IF(ajkrategeneral.plafondstart IS NULL, "#", ajkrategeneral.plafondstart) AS plafondstart,
										 IF(ajkrategeneral.plafondend IS NULL, "#", ajkrategeneral.plafondend) AS plafondend,
										 IF(ajkrategeneral.kelas IS NULL, "#", ajkrategeneral.kelas) AS kelas,
										 ajkrategeneral.rate,
										 ajkrategeneral.type,
										 ajkrategeneral.status,
										 ajkgeneralarea.lokasi AS lokasi,
										 ajkgeneralkategori.keterangan,
										 ajkgeneralkelas.kelas,
										 ajkgeneralkelas.keterangan AS ketkelas
								FROM ajkrategeneral
								INNER JOIN ajkgeneralarea ON ajkrategeneral.lokasi = ajkgeneralarea.id
								INNER JOIN ajkgeneralkategori ON ajkrategeneral.quarantee = ajkgeneralkategori.id
								LEFT JOIN ajkgeneralkelas ON ajkrategeneral.kelas = ajkgeneralkelas.id
								WHERE ajkrategeneral.idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.status="Aktif"');
while ($cekRateGnr_ = mysql_fetch_array($cekRateGnr)) {
if ($cekRateGnr_['type']=="CMP") {
	$typeRategeneral = 'Comprehensive';
}elseif ($cekRateGnr_['type']=="TLO") {
	$typeRategeneral = 'Total Loss Only';
}else{
	$typeRategeneral = '#';
}
echo '<tr>
	<td align="center">'.++$no2.'</td>
	<td align="center"><span class="label label-info"><strong>'.$typeRategeneral.'</strong></span></td>
	<td align="center">'.$cekRateGnr_['tenorstart'].'</td>
	<td align="center">'.$cekRateGnr_['tenorend'].'</td>
	<td align="right">'.duit($cekRateGnr_['plafondstart']).'</td>
	<td align="right">'.duit($cekRateGnr_['plafondend']).'</td>
	<td>'.$cekRateGnr_['lokasi'].'</td>
	<td>'.$cekRateGnr_['keterangan'].'</td>
	<td align="center">'.$cekRateGnr_['ketkelas'].'</td>
	<td align="center"><span class="label label-primary">'.$cekRateGnr_['rate'].'</span></td>
	<td align="center"><a href="ajk.php?re=client&op=gncategoryedt&idp='.$_REQUEST['idp'].'&idr='.$thisEncrypter->encode($cekRateGnr_['id']).'">'.BTN_EDIT.'</a></td>
	</tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor Start"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor End"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Plafond End"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Plafond Start"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Location"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Quarantee"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Class"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		</tr>
	</tfoot></table>';
}
echo '&nbsp;';
echo '</div>

<div class="tab-pane" id="tabguarantee">';
$cekGuarnateeGnr = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneraljaminan WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkgeneraljaminan.del IS NULL'));
if (!$cekGuarnateeGnr['id']) {
echo '<div class="col-md-12">
	<div class="alert alert-danger fade in">
	<h4 class="semibold">Oucchh...Error data guarantee!</h4>
	<p class="mb10">Data guarantee from this product is empty.</p>
	<a href="ajk.php?re=client&op=gnguarantee&idp='.$_REQUEST['idp'].'"><button type="button" class="btn btn-danger">Insert New Data</button></a>
        </div>
    </div>';
}else{
echo '<div class="toolbar"><a href="ajk.php?re=client&op=gnguarantee&idp='.$_REQUEST['idp'].'">'.BTN_NEW.'</a></div>
	  <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="1%">Name</th>
        <th width="1%">Region</th>
        <th width="1%">Contribution</th>
        <th width="1%">Risk</th>
        <th width="1%">View</th>
        <th width="1%">Add</th>
        <th width="1%">Edit</th>
        </tr>
    </thead>
    <tbody>
    <div class="col-md-10">&nbsp;</div>';
$cekGuaranteeGnr = $database->doQuery('SELECT ajkgeneraljaminan.id,
											  ajkgeneraljaminan.idbroker,
											  ajkgeneraljaminan.idpartner,
											  ajkgeneraljaminan.idproduk,
											  ajkgeneraljaminan.idguarantee,
											  ajkgeneraljaminan.wilayah,
											  ajkgeneraljaminan.carahitungkontribusi,
											  ajkgeneraljaminan.carahitungresiko,
											  ajkgeneralnamajaminan.namajaminan
										FROM ajkgeneraljaminan
										INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
										WHERE ajkgeneraljaminan.idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkgeneraljaminan.del IS NULL');
while ($cekGuaranteeGnr_ = mysql_fetch_array($cekGuaranteeGnr)) {
echo '<tr>
	<td align="center">'.++$no3.'</td>
	<td><span class="label label-info"><strong>'.$cekGuaranteeGnr_['namajaminan'].'</strong></span></td>
	<td align="center">'.$cekGuaranteeGnr_['wilayah'].'</td>
	<td align="center">'.$cekGuaranteeGnr_['carahitungkontribusi'].'</td>
	<td align="center">'.$cekGuaranteeGnr_['carahitungresiko'].'</td>
	<td align="center"><a href="plugins/mypop.php?err=guranteeext&idge='.$cekGuaranteeGnr_['id'].'" data-toggle="modal" data-target="#bs-modal-lg">'.BTN_ADDGUARANTEEVIEW.'</a></td>
	<td align="center"><a href="ajk.php?re=client&op=gnguaranteeadd&idp='.$_REQUEST['idp'].'&idg='.$thisEncrypter->encode($cekGuaranteeGnr_['id']).'">'.BTN_ADDGUARANTEE.'</a></td>
	<td align="center"><a href="ajk.php?re=client&op=gnguaranteeedt&idp='.$_REQUEST['idp'].'&idg='.$thisEncrypter->encode($cekGuaranteeGnr_['id']).'">'.BTN_EDIT.'</a></td>
	</tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Region"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Contribution"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Risk"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		</tr>
	</tfoot></table>';

			echo '<div id="bs-modal-lg" class="modal fade">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <div class="ico-shield3 mb15 mt15" style="font-size:36px;"></div>
                                <h3 class="semibold modal-title text-success">Shield Activated</h3>
                                <p class="text-muted">Excepteur sint occaecat cupidatat non proident.</p>
                            </div>
                            <div class="modal-body">

                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>';

}
echo '&nbsp;';
echo '</div>
    </div>
</div>';
echo '</div></div>';
echo '</div></div></div>';
	;
	break;

/*GENERAL AREA */
case "gnrarea":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Area</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
if ($_REQUEST['met']=="saveme") {
$metArea = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralarea WHERE del IS NULL ORDER BY id DESC'));
$kodeArea = $metArea['id'] + 1;
$kodeArea_ = $metGen['produkid'].''.$kodeArea;
$metGenIns = $database->doQuery('INSERT INTO ajkgeneralarea SET idbroker="'.$metGen['brokerid'].'",
																idpartner="'.$metGen['clientid'].'",
																idproduk="'.$metGen['produkid'].'",
																area="'.strtoupper($_REQUEST['areaname']).'",
																lokasi="'.strtoupper($_REQUEST['restricarea']).'",
																kode="'.$kodeArea_.'",
																inputby="'.$q['id'].'",
																inputdate="'.$futgl.'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
	<div class="alert alert-dismissable alert-success">
    <strong>Success!</strong> Input area.
    </div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';
echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Area</h3></div>
		<div class="panel-body">
	<div class="form-group">
		<label class="col-sm-2 control-label">Area <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="areaname" value="'.$_REQUEST['areaname'].'" class="form-control" placeholder="Area" required></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Restriction <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="restricarea" value="'.$_REQUEST['restricarea'].'" class="form-control" placeholder="Restriction Area" required></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
echo '</div></div>';
echo '</div></div></div>';
		;
		break;
case "gnareaedt":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Area</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneralarea.id,
ajkgeneralarea.area,
ajkgeneralarea.lokasi
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneralarea ON ajkpolis.id = ajkgeneralarea.idproduk
WHERE ajkpolis.id = '.$thisEncrypter->decode($_REQUEST['idp']).' AND ajkgeneralarea.id = '.$thisEncrypter->decode($_REQUEST['idarea']).''));
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneralarea.id,
ajkgeneralarea.area,
ajkgeneralarea.lokasi
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneralarea ON ajkpolis.id = ajkgeneralarea.idproduk
WHERE ajkpolis.id = '.$thisEncrypter->decode($_REQUEST['idp']).' AND ajkgeneralarea.id = '.$thisEncrypter->decode($_REQUEST['idarea']).''));
if ($_REQUEST['met']=="updme") {
$metGenIns = $database->doQuery('UPDATE ajkgeneralarea SET area="'.strtoupper($_REQUEST['areaname']).'",
														   lokasi="'.strtoupper($_REQUEST['restricarea']).'",
														   updateby="'.$q['id'].'",
														   updatedate="'.$futgl.'"
								 WHERE id="'.$thisEncrypter->decode($_REQUEST['idarea']).'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
	<div class="alert alert-dismissable alert-success">
    <strong>Success!</strong> Edit area.
    </div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';
echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Form Area</h3></div>
		<div class="panel-body">
	<div class="form-group">
		<label class="col-sm-2 control-label">Area <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="areaname" value="'.$metGen['area'].'" class="form-control" placeholder="Area" required></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Restriction <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="restricarea" value="'.$metGen['lokasi'].'" class="form-control" placeholder="Restriction Area" required></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="updme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
echo '</div></div>';
echo '</div></div></div>';
	;
	break;

case "gncategory":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Category</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
if ($_REQUEST['met']=="saveme") {
$metCategory = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralkategori WHERE del IS NULL ORDER BY id DESC'));
$kodeCategory = $metCategory['id'] + 1;
$kodeCategory_ = $metGen['produkid'].''.$kodeCategory;
$metGenIns = $database->doQuery('INSERT INTO ajkgeneralkategori SET idbroker="'.$metGen['brokerid'].'",
																idpartner="'.$metGen['clientid'].'",
																idproduk="'.$metGen['produkid'].'",
																kode="'.$kodeCategory_.'",
																keterangan="'.strtoupper($_REQUEST['keterangan']).'",
																inputby="'.$q['id'].'",
																inputdate="'.$futgl.'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
	<div class="alert alert-dismissable alert-success">
    <strong>Success!</strong> Input data category.
    </div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';
echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Category</h3></div>
		<div class="panel-body">
	<div class="form-group">
		<label class="col-sm-2 control-label">Type Category <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="keterangan" value="'.$_REQUEST['keterangan'].'" class="form-control" placeholder="Type Category" required></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
echo '</div></div>';
echo '</div></div></div>';
	;
	break;

/*GENERAL CATEGORY */
case "gncategoryedt":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Category</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp=UmY=">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneralkategori.keterangan AS jenis
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneralkategori ON ajkpolis.id = ajkgeneralkategori.idproduk
WHERE ajkpolis.id = '.$thisEncrypter->decode($_REQUEST['idp']).' AND ajkgeneralkategori.id = '.$thisEncrypter->decode($_REQUEST['category']).''));
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneralkategori.keterangan AS jenis
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneralkategori ON ajkpolis.id = ajkgeneralkategori.idproduk
WHERE ajkpolis.id = '.$thisEncrypter->decode($_REQUEST['idp']).' AND ajkgeneralkategori.id = '.$thisEncrypter->decode($_REQUEST['category']).''));
if ($_REQUEST['met']=="saveme") {
	$metCategory = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralkategori WHERE del IS NULL ORDER BY id DESC'));
	$kodeCategory = $metArea['id'] + 1;
	$kodeCategory_ = $metGen['produkid'].''.$kodeCategory;
	$metGenIns = $database->doQuery('UPDATE ajkgeneralkategori SET keterangan="'.strtoupper($_REQUEST['keterangan']).'", updateby="'.$q['id'].'", updatedate="'.$futgl.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['category']).'"');
			$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
				<div class="alert alert-dismissable alert-success">
			    <strong>Success!</strong> Edit data category.
			    </div>';
		}
		echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
		echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';
		echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Form Category</h3></div>
		<div class="panel-body">
	<div class="form-group">
		<label class="col-sm-2 control-label">Type Category <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="keterangan" value="'.$metGen['jenis'].'" class="form-control" placeholder="Type Category" required></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	echo '</div></div>';
	echo '</div></div></div>';
	;
	break;

case "gnrate":
include_once('./phpexcel/PHPExcel/IOFactory.php');
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Rate</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkpolis.byrategeneral,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
*/
$metGen = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkpolis.byrategeneral,
ajkpolis.classgeneral,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
echo '<div class="panel-body">
	<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['brokerlogo'].'" alt="" width="75px" height="55px"></div>
		<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$metGen['brokername'].'</dd>
				<dt>Company</dt><dd>'.$metGen['clientname'].'</dd>
				<dt>Product</dt><dd><span class="label label-primary">'.$metGen['produk'].' ('.$metGen['keterangan'].')</span></dd>
			</dl>
		</div>
	<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGen['clientlogo'].'" alt="" width="75px" height="55px"></div>';
$cekRateGen = mysql_fetch_array($database->doQuery('SELECT ajkpolis.id AS produkid,
														   ajkrategeneral.id AS rateidgen
													FROM ajkpolis
													INNER JOIN ajkrategeneral ON ajkpolis.id = ajkrategeneral.idproduk
													WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.status="Aktif"'));
if (!$cekRateGen['rateidgen']) {
echo '<div class="panel-toolbar text-center">
	<div class="col-md-12">';
	if (isset($_REQUEST['met'])=="saverateGen") {
	if ($_REQUEST['guangeeral'] == "CMP") {	$rGuaranteed = 'Comprehensive';	}
	if ($_REQUEST['guangeeral'] == "TLO") {	$rGuaranteed = 'Total Loss Only';	}
	else{	$rGuaranteed = '';	}

echo '<div class="col-md-12">
	<div class="panel panel-success">
    	<div class="panel-heading">
        <h4 class="panel-title"><i class="ico-file-excel mr5"></i> '.$_FILES['fileRate']['name'].'<br />'.$rGuaranteed.'</h4>
    </div>
    <div class="panel-collapse pull out">
    	<div class="panel-body">
			<input type="hidden" name="idb" value="'.$thisEncrypter->encode($metGen['brokerid']).'">
			<input type="hidden" name="idc" value="'.$thisEncrypter->encode($metGen['clientid']).'">
			<input type="hidden" name="idp" value="'.$thisEncrypter->encode($metGen['produkid']).'">';
		$fNameUpload = str_replace(" ","_", 'RATEGENERAL_'.$DatePolis1.'_B'.$_REQUEST['coBroker'].'_C'.$_REQUEST['coClient'].'_P'.$_REQUEST['coPolicy'].'_USER'.$q['id'].'_'.$_FILES['fileRate']['name']);
		$namafile =  $_FILES['fileRate']['tmp_name'];
		//echo $namafile;
		$ext = pathinfo($namafile, PATHINFO_EXTENSION);
		$file_info = pathinfo($namafile);
		$file_extension = $file_info["extension"];
		$namefile = $file_info["filename"].'.'.$file_extension;
		$inputFileName = $namafile;
		$_SESSION['file_temp'] = $namefile;
		$_SESSION['file_name'] = $_FILES['fileRate']['name'];
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {	die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());	}
//Table used to display the contents of the file
echo '
	<table class="table table-bordered table-hover" id="table-upload"  width="100%">';
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
echo '<thead >
	<tr class="primary"><th class="text-center">No</th>
						<th class="text-center">Tenor From (month)</th>
						<th class="text-center">Tenor To (month)</th>
						<th class="text-center">Plafond Start</th>
						<th class="text-center">Plafond End</th>
						<th class="text-center">Location</th>
						<th class="text-center">Guarantee</th>
						<th class="text-center">Class</th>
						<th class="text-center">Rate General</th>
	</tr>
	</thead><tbody>';

	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	for ($row = 2; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	echo "<tr>";
	$i = 0;
	foreach($rowData[0] as $k=>$v){
		$data[$i] = $v;
		$i++;
	}
	$today = date('Y-m-d');
	$_data1 = $data[1];	//TENOR FROM
	$_data2 = $data[2];	//TENOR TO
	$_data3 = $data[3];	//PLAFOND START
	$_data4 = $data[4];	//PLAFOND END
	$_data5 = $data[5];	//LOKASI
	$_data6 = $data[6];	//PERTANGGUNGAN
	$_data7 = $data[7];	//KELAS
	$_data8 = $data[8];	//RATE

//CEK KETENTUAN TENOR
if ($metGen['byrategeneral']=="Tenor") {
	if ($_data1=="" OR !is_numeric($_data1)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = $_data1;	}
	if ($_data2=="" OR !is_numeric($_data2)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = $_data2;	}
	$__kolomtenor = "<td>".$dataEXL1." </td><td>".$dataEXL2." </td>";
	$__kolomplafond = "<td>###</td><td>###</td>";
}elseif ($metGen['byrategeneral']=="Plafond") {
	$__kolomtenor = "<td>###</td><td>###</td>";
	if ($_data3=="" OR !is_numeric($_data3)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = $_data3;	}
	if ($_data4=="" OR !is_numeric($_data4)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = $_data4;	}
	$__kolomplafond = "<td>".$dataEXL3." </td><td>".$dataEXL4." </td>";
}else{
	if ($_data1=="" OR !is_numeric($_data1)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = $_data1;	}
	if ($_data2=="" OR !is_numeric($_data2)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = $_data2;	}
	$__kolomplafond = "<td>".$dataEXL1."</td><td>".$dataEXL2."</td>";

	if ($_data3=="" OR !is_numeric($_data3)) {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = $_data3;	}
	if ($_data4=="" OR !is_numeric($_data4)) {	$ErrorEXL4 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = $_data4;	}
	$__kolomtenor = "<td>".$dataEXL3."</td><td>".$dataEXL4."</td>";
}
//CEK KETENTUAN TENOR

//CEK KETENTUAN LOKASI
$metGenWilayah = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralarea WHERE ajkgeneralarea.idproduk ="'.$metGen['produkid'].'" AND del IS NULL'));
if ($metGenWilayah['idproduk']) {
	if ($_data5=="") {	$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;
	$__kolomlokasi = "<td>".$dataEXL5."</td>";
	}else{
		$metGenWilayahData = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralarea WHERE ajkgeneralarea.idproduk ="'.$metGen['produkid'].'" AND kode="'.$_data5.'" AND del IS NULL'));
		if ($metGenWilayahData['kode']) {
			$__kolomlokasi =  "<td>".$metGenWilayahData['lokasi']."</td>";
		}else{
			$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;
			$__kolomlokasi = "<td>".$dataEXL5."</td>";
		}
	}
}else{
$__kolomlokasi = "<td>###</td>";
}
//CEK KETENTUAN LOKASI

//CEK KETENTUAN GUARANTEE / OBJEK PERTANGGUNGAN
$metGenKategori = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralkategori WHERE ajkgeneralkategori.idproduk ="'.$metGen['produkid'].'" AND del IS NULL'));
if ($metGenKategori['idproduk']) {
	if ($_data6=="") {	$ErrorEXL6 = '<span class="label label-danger">Error</span>';	$dataEXL6 = $ErrorEXL6;
		$__kolombangunan = "<td>".$dataEXL6."</td>";
	}else{
		$metGenKategoriData = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralkategori WHERE ajkgeneralkategori.idproduk ="'.$metGen['produkid'].'" AND kode="'.$_data6.'" AND del IS NULL'));
		if ($metGenKategoriData['kode']) {
			$__kolombangunan = "<td>".$metGenKategoriData['keterangan']."</td>";
		}else{
			$ErrorEXL6 = '<span class="label label-danger">Error</span>';	$dataEXL6 = $ErrorEXL6;
			$__kolombangunan = "<td>".$dataEXL6."</td>";
		}
	}
}else{
$__kolombangunan = "<td>###</td>";
}
//CEK KETENTUAN GUARANTEE / OBJEK PERTANGGUNGAN

//CEK KETENTUAN KELAS
if ($metGen['classgeneral']=="Ya") {
	if ($_data7=="" OR !is_numeric($_data7)) {	$ErrorEXL7 = '<span class="label label-danger">Error</span>';	$dataEXL7 = $ErrorEXL7;
	$__kolomkelas = "<td>".$dataEXL7." </td>";
	}else{
		$metGenKelas = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralkelas WHERE ajkgeneralkelas.id ="'.$_data7.'"'));
		if ($metGenKelas['id']) {
			$__kolomkelas = "<td>".$metGenKelas['keterangan']."</td>";
		}else{
			$ErrorEXL7 = '<span class="label label-danger">Error</span>';	$dataEXL7 = $ErrorEXL7;
			$__kolomkelas = "<td>".$dataEXL7." </td>";
		}
	}
}else{
$__kolomkelas = "<td>###</td>";
}
//CEK KETENTUAN KELAS

//CEK RATE
if ($_data8=="" OR !is_numeric($_data8)) {	$ErrorEXL8 = '<span class="label label-danger">Error</span>';	$dataEXL8 = "<td>".$ErrorEXL8." </td>";	}else{	$dataEXL8 =  "<td>".$_data8." </td>";	}
//CEK RATE

echo "<td>".++$no." </td>";
echo $__kolomtenor;
echo $__kolomplafond;
echo $__kolomlokasi;
echo $__kolombangunan;
echo $__kolompkelas;
echo $__kolomkelas;
echo $dataEXL8;
echo "</tr>";
}

if($ErrorEXL1 OR $ErrorEXL2 OR $ErrorEXL3 OR $ErrorEXL4 OR $ErrorEXL5 OR $ErrorEXL6 OR $ErrorEXL7 OR $ErrorEXL8){
echo '<div align="center" class="col-md-12"><a href="ajk.php?re=client&op=gnrate&idp='.$_REQUEST['idp'].'">'.BTN_UPLOADERROR.'</a></div>';
}else{
	$PathRate		= "../myFiles/_rate/".$foldername."";
	if (!file_exists($PathRate)) 	{	mkdir($PathRate, 0777);	chmod($PathRate, 0777);	}
	$direktori = $PathRate.''.$fNameUpload; // direktori tempat menyimpan file
	move_uploaded_file($namafile,$direktori);
	echo '<div align="right" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneraldel&idp='.$_REQUEST['idp'].'&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_BACK2.'</a></div>
		  <div align="left" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneralsave&idb='.$thisEncrypter->encode($metGen['brokerid']).'&idc='.$thisEncrypter->encode($metGen['clientid']).'&idp='.$thisEncrypter->encode($metGen['produkid']).'&fname='.$thisEncrypter->encode($fNameUpload).'&type='.$thisEncrypter->encode($_REQUEST['guangeeral']).'">'.BTN_SUBMIT.'</a></div>';
}
echo '</tbody></table>
	</div></form>
	    	</div>
    	<div class="indicator"><span class="spinner"></span></div>
    </div>
    </div>';
}
else{
echo '<div class="col-md-12">
	<div class="panel panel-success">
    	<div class="panel-heading"><h3 class="panel-title"><i class="ico-file-excel mr5"></i> Upload Rate By Excel</h3></div>
    	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<input type="hidden" name="idb" value="'.$thisEncrypter->encode($metGen['brokerid']).'">
		<input type="hidden" name="idc" value="'.$thisEncrypter->encode($metGen['clientid']).'">
		<input type="hidden" name="idp" value="'.$thisEncrypter->encode($metGen['produkid']).'">
    		<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Rate By</label>
				<div class="col-sm-10"><input type="text" name="areaname" value="'.$metGen['byrategeneral'].'" class="form-control" disabled></div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Guaranteed By <span class="text-danger">*</span></label>
				<div class="col-sm-10"><select name="guangeeral" class="form-control" required>
										<option value="">Select Guaranteed</option>
										<option value="CMP"'._selected($_REQUEST['guangeeral'], "CMP").'>Comprehensive</option>
										<option value="TLO"'._selected($_REQUEST['guangeeral'], "TLO").'>Total Loss Only</option>
										<option value=" "'._selected($_REQUEST['guangeeral'], " ").'>Non Comprehensive/TLO</option>
										</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label">File Upload</label>
				<div class="col-sm-10"><input type="file" name="fileRate" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></div>
				</div>

				<div class="form-group">
				<div class="col-sm-12 text-left"><input type="hidden" name="met" value="saverateGen">'.BTN_UPLRATEGENERAL.'</div>
				</div>
			</div>
    		<div class="indicator"><span class="spinner"></span></div>
		</form>
    </div>';
}
echo '</div><br />
	</div>';
}
else{
/*
	if (isset($_REQUEST['dis'])=="rategen") {
		$metGeneral = $database->doQuery('UPDATE ajkrategeneral SET status="NonAktif", update_by="'.$q['id'].'", update_time="'.$futgl.'" WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'"');
	}
	$met_= mysql_fetch_array($database->doQuery('SELECT ajkpolis.id, ajkpolis.idcost, ajkclient.idc
											 FROM ajkpolis
											 INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
											 INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
											 WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" '));
	$metRateGeneral = $database->doQuery('SELECT * FROM ajkrategeneral WHERE idbroker="'.$met_['idc'].'" AND idclient="'.$met_['idcost'].'" AND idproduk="'.$met_['id'].'" AND status="Aktif"');
	echo '<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="panel panel-default" id="demo">
		    	<div class="panel-heading"><h3 class="panel-title">Table Rate General</h3>
		    		<div class="panel-toolbar text-right"><a href="ajk.php?re=client&op=rtgeneral&dis=rategen&idp='.$thisEncrypter->encode($met_['id']).'" onClick="if(confirm(\'Delete this rate general ?\')){return true;}{return false;}">'.BTN_DEL.'</a></div>
		        </div>
		      <table class="table table-striped table-bordered" id="column-filtering">
		      <thead>
		      	<tr>
		        <th width="1%">No</th>
		        <th width="10%">Tessnor Start</th>
		        <th width="10%">Tenor End</th>
		        <th width="10%">Location</th>
		        <th width="10%">Guarantee</th>
		        <th width="10%">Class</th>
		        <th width="10%">Rate Fire</th>
		        <th width="10%">Rate PA</th>
		        </tr>
		    </thead>
	    <tbody>';
while ($metRateGeneral_ = mysql_fetch_array($metRateGeneral)) {
	echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metRateGeneral_['tenorstart'].'</td>
		<td>'.$metRateGeneral_['tenorend'].'</td>
		<td align="center">'.$metRateGeneral_['lokasi'].'</td>
		<td align="center">'.$metRateGeneral_['quarantee'].'</td>
		<td align="center">'.$metRateGeneral_['kelas'].'</td>
		<td align="center"><span class="label label-primary">'.$metRateGeneral_['ratefire'].'</span></td>
		<td align="center"><span class="label label-primary">'.$metRateGeneral_['ratepa'].'</span></td>
	</tr>';
	}
	echo '</tbody>
			<tfoot>
	        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Start"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="End"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Location"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Guaranteer"></th>
	            <th><input type="search" class="form-control" name="search_engine" placeholder="Class"></th>
	            <th><input type="hidden" class="form-control" name="search_engine"></th>
	            <th><input type="hidden" class="form-control" name="search_engine"></th>
	        </tr>
	        </tfoot>
		</table>
	        </div>
		</div>';
*/

if ($_REQUEST['mett']=="saverateGenOt") {
	if ($_REQUEST['guangeeral'] == "CMP") {	$rGuaranteed = 'Comprehensive';	}	else{	$rGuaranteed = 'Total Loss Only';	}

				echo '<div class="col-md-12">
	<div class="panel panel-success">
    	<div class="panel-heading">
        <h4 class="panel-title"><i class="ico-file-excel mr5"></i> '.$_FILES['fileRate']['name'].'<br />'.$rGuaranteed.'</h4>
    </div>
    <div class="panel-collapse pull out">
    	<div class="panel-body">
			<input type="hidden" name="idb" value="'.$thisEncrypter->encode($metGen['brokerid']).'">
			<input type="hidden" name="idc" value="'.$thisEncrypter->encode($metGen['clientid']).'">
			<input type="hidden" name="idp" value="'.$thisEncrypter->encode($metGen['produkid']).'">';
	$fNameUpload = str_replace(" ","_", 'RATEGENERAL_'.$DatePolis1.'_B'.$_REQUEST['coBroker'].'_C'.$_REQUEST['coClient'].'_P'.$_REQUEST['coPolicy'].'_USER'.$q['id'].'_'.$_FILES['fileRate']['name']);
	$namafile =  $_FILES['fileRate']['tmp_name'];
	//echo $namafile;
	$ext = pathinfo($namafile, PATHINFO_EXTENSION);
	$file_info = pathinfo($namafile);
	$file_extension = $file_info["extension"];
	$namefile = $file_info["filename"].'.'.$file_extension;
	$inputFileName = $namafile;
	$_SESSION['file_temp'] = $namefile;
	$_SESSION['file_name'] = $_FILES['fileRate']['name'];
	//  Read your Excel workbook
	try {
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch (Exception $e) {	die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());	}
	//Table used to display the contents of the file
				echo '
	<table class="table table-bordered table-hover" id="table-upload"  width="100%">';
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
				echo '<thead >
	<tr class="primary"><th class="text-center">No</th>
						<th class="text-center">Tenor From (month)</th>
						<th class="text-center">Tenor To (month)</th>
						<th class="text-center">Plafond Start</th>
						<th class="text-center">Plafond End</th>
						<th class="text-center">Location</th>
						<th class="text-center">Guarantee</th>
						<th class="text-center">Class</th>
						<th class="text-center">Rate General</th>
	</tr>
	</thead><tbody>';

	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	for ($row = 2; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		echo "<tr>";
		$i = 0;
		foreach($rowData[0] as $k=>$v){
			$data[$i] = $v;
			$i++;
		}
		$today = date('Y-m-d');
		$_data1 = $data[1];	//TENOR FROM
		$_data2 = $data[2];	//TENOR TO
		$_data3 = $data[3];	//PLAFOND START
		$_data4 = $data[4];	//PLAFOND END
		$_data5 = $data[5];	//LOKASI
		$_data6 = $data[6];	//PERTANGGUNGAN
		$_data7 = $data[7];	//KELAS
		$_data8 = $data[8];	//RATE

$metKategory = mysql_fetch_array($database->doQuery('SELECT
ajkclient.`name`,
ajkpolis.produk,
ajkgeneralarea.kode AS kodearea,
ajkgeneralarea.area,
ajkgeneralarea.lokasi,
ajkgeneralkategori.kode AS kodekategorri,
ajkgeneralkategori.keterangan
FROM ajkclient
INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
INNER JOIN ajkgeneralarea ON ajkclient.id = ajkgeneralarea.idpartner AND ajkpolis.id = ajkgeneralarea.idproduk
INNER JOIN ajkgeneralkategori ON ajkclient.id = ajkgeneralkategori.idpartner AND ajkpolis.id = ajkgeneralkategori.idproduk
WHERE
ajkclient.id = "'.$metGen['clientid'].'" AND
ajkpolis.id = "'.$metGen['produkid'].'" AND
ajkgeneralarea.kode = "'.$_data5.'" AND
ajkgeneralkategori.kode = "'.$_data6.'"'));

//CEK RATE GENERAL DENGAN PLAFOND ATAU TENOR
if ($metGen['byrategeneral']=="Tenor") {
	if ($_data1=="" OR !is_numeric($_data1)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = $_data1;	}
	if ($_data2=="" OR !is_numeric($_data2)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = $_data2;	}
	$__kolomtenor = "<td>".$dataEXL1." </td><td>".$dataEXL2." </td>";
}else{
	$__kolomtenor = "<td>###</td><td>###</td>";
}
//CEK RATE GENERAL DENGAN PLAFOND ATAU TENOR

//CEK RATE GENERAL DENGAN PERHITAUNGAN KELAS
if ($metGen['classgeneral']=="Ya") {
	if ($_data7=="" OR !is_numeric($_data7)) {	$ErrorEXL7 = '<span class="label label-danger">Error</span>';	$dataEXL7 = $ErrorEXL7;	}else{	$dataEXL7 = $_data7;	}
	$__kolompkelas = "<td>".$ErrorEXL7." </td>";
}else{
	$__kolompkelas = "<td>###</td>";
}
//CEK RATE GENERAL DENGAN PERHITAUNGAN KELAS
//if ($_data1=="" OR !is_numeric($_data1)) {	$ErrorEXL1 = '<span class="label label-danger">Error</span>';	$dataEXL1 = $ErrorEXL1;	}else{	$dataEXL1 = $_data1;	}
//if ($_data2=="" OR !is_numeric($_data2)) {	$ErrorEXL2 = '<span class="label label-danger">Error</span>';	$dataEXL2 = $ErrorEXL2;	}else{	$dataEXL2 = $_data2;	}
if ($metGen['byrategeneral']=="Plafond") {
	if ($_data3=="" OR !is_numeric($_data3)) {	$ErrorEXL3 = '<span class="label label-danger">Error</span>';	$dataEXL3 = $ErrorEXL3;	}else{	$dataEXL3 = duit($_data3);	}
	if ($_data4=="" OR !is_numeric($_data4)) {	$ErrorEXL4 = '<span class="label label-danger">Error</span>';	$dataEXL4 = $ErrorEXL4;	}else{	$dataEXL4 = duit($_data4);	}
	$__kolomplafond = "<td>".$dataEXL3." </td><td>".$dataEXL4." </td>";
}else{
	$__kolomplafond = "<td>###</td><td>###</td>";
}
if ($_data5=="" OR !is_numeric($_data5) OR !$metKategory['kodearea']) {	$ErrorEXL5 = '<span class="label label-danger">Error</span>';	$dataEXL5 = $ErrorEXL5;	}else{	$dataEXL5 = $metKategory['lokasi'];	}
if ($_data6=="" OR !is_numeric($_data6) OR !$metKategory['kodekategorri']) {	$ErrorEXL6 = '<span class="label label-danger">Error</span>';	$dataEXL6 = $ErrorEXL6;	}else{	$dataEXL6 = $metKategory['keterangan'];	}
//if ($_data7=="" OR !is_numeric($_data7)) {	$ErrorEXL7 = '<span class="label label-danger">Error</span>';	$dataEXL7 = $ErrorEXL7;	}else{	$dataEXL7 = $_data7;	}
if ($_data8=="" OR !is_numeric($_data8)) {	$ErrorEXL8 = '<span class="label label-danger">Error</span>';	$dataEXL8 = $ErrorEXL8;	}else{	$dataEXL8 = $_data8;	}
		echo "<td>".++$no." </td>";
		echo $__kolomtenor;
		echo $__kolomplafond;
		echo "<td>".$dataEXL5." </td>";
		echo "<td>".$dataEXL6." </td>";
		echo $__kolompkelas;
		echo "<td>".$dataEXL8." </td>";
		echo "</tr>";
}
if($ErrorEXL1 OR $ErrorEXL2 OR $ErrorEXL3 OR $ErrorEXL4 OR $ErrorEXL5 OR $ErrorEXL6 OR $ErrorEXL7 OR $ErrorEXL8){
	echo '<div align="center" class="col-md-12"><a href="ajk.php?re=client&op=gnrate&idp='.$_REQUEST['idp'].'">'.BTN_UPLOADERROR.'</a></div>';
}else{
	$PathRate		= "../myFiles/_rate/".$foldername."";
	if (!file_exists($PathRate)) 	{	mkdir($PathRate, 0777);	chmod($PathRate, 0777);	}
	$direktori = $PathRate.''.$fNameUpload; // direktori tempat menyimpan file
	move_uploaded_file($namafile,$direktori);
	echo '<div align="right" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneraldel&idp='.$_REQUEST['idp'].'&fname='.$thisEncrypter->encode($fNameUpload).'">'.BTN_BACK2.'</a></div>
		  <div align="left" class="col-md-6"><a href="ajk.php?re=client&op=rtgeneralsave&idb='.$thisEncrypter->encode($metGen['brokerid']).'&idc='.$thisEncrypter->encode($metGen['clientid']).'&idp='.$thisEncrypter->encode($metGen['produkid']).'&fname='.$thisEncrypter->encode($fNameUpload).'&type='.$thisEncrypter->encode($_REQUEST['guangeeral']).'">'.BTN_SUBMIT.'</a></div>';
	}
	echo '</tbody></table>
	</div></form>
	    	</div>
    	<div class="indicator"><span class="spinner"></span></div>
    </div>
    </div>';
}else{
echo '<div class="col-md-12">
	<div class="panel panel-success">
    	<div class="panel-heading"><h3 class="panel-title"><i class="ico-file-excel mr5"></i> Upload Rate By Excel</h3></div>
    	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<input type="hidden" name="idb" value="'.$thisEncrypter->encode($metGen['brokerid']).'">
		<input type="hidden" name="idc" value="'.$thisEncrypter->encode($metGen['clientid']).'">
		<input type="hidden" name="idp" value="'.$thisEncrypter->encode($metGen['produkid']).'">
    		<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Rate By</label>
				<div class="col-sm-10"><input type="text" name="areaname" value="'.$metGen['byrategeneral'].'" class="form-control" disabled></div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Guaranteed By <span class="text-danger">*</span></label>
				<div class="col-sm-10"><select name="guangeeral" class="form-control" required>
										<option value="">Select Guaranteed</option>';
$_cektyperateCMP = mysql_fetch_array($database->doQuery('SELECT ajkrategeneral.id, ajkrategeneral.type FROM ajkrategeneral WHERE ajkrategeneral.idproduk = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.type="CMP" AND status="Aktif" GROUP BY ajkrategeneral.type'));
	if ($_cektyperateCMP['type']=="CMP") {
		echo '<option value="CMP"'._selected($_REQUEST['guangeeral'], "CMP").' disabled>Comprehensive</option>';
	}else{
		echo '<option value="CMP"'._selected($_REQUEST['guangeeral'], "CMP").'>Comprehensive</option>';
	}

	$_cektyperateTLO = mysql_fetch_array($database->doQuery('SELECT ajkrategeneral.id, ajkrategeneral.type FROM ajkrategeneral WHERE ajkrategeneral.idproduk = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.type="TLO" AND status="Aktif" GROUP BY ajkrategeneral.type'));
	if ($_cektyperateTLO['type']=="TLO") {
		echo '<option value="TLO"'._selected($_REQUEST['guangeeral'], "TLO").' disabled>Total Loss Only</option>';
	}else{
		echo '<option value="TLO"'._selected($_REQUEST['guangeeral'], "TLO").'>Total Loss Only</option>';
	}

	$_cektyperateTLO = mysql_fetch_array($database->doQuery('SELECT ajkrategeneral.id, ajkrategeneral.type FROM ajkrategeneral WHERE ajkrategeneral.idproduk = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkrategeneral.type="TLO" AND status="Aktif" GROUP BY ajkrategeneral.type'));
	if ($_cektyperateTLO['type']=="") {
		echo '<option value=""'._selected($_REQUEST['guangeeral'], "").' disabled>Non Comprehensive/TLO</option>';
	}else{
		echo '<option value=""'._selected($_REQUEST['guangeeral'], "").'>Non Comprehensive/TLO</option>';
	}




	echo '</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label">File Upload</label>
				<div class="col-sm-10"><input type="file" name="fileRate" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></div>
				</div>

				<div class="form-group">
				<div class="col-sm-12 text-left"><input type="hidden" name="mett" value="saverateGenOt">'.BTN_UPLRATEGENERAL.'</div>
				</div>
			</div>
    		<div class="indicator"><span class="spinner"></span></div>
		</form>
    </div>';
	}
}
echo '</div>
	</div>';

echo '</div>
	</div>
</div>';
	;
	break;

case "gnratedel":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Rate</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&s_area=gnrarea&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
echo $thisEncrypter->decode($_REQUEST['idp']);
$metRateGen = $database->doQuery('UPDATE ajkrategeneral SET status="NonAktif" WHERE idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'"');
header('location:ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'');
echo '</div>
	</div>
</div>';
	;
	break;


case "rtgeneraldel":
$idp_ = $thisEncrypter->decode($_REQUEST['idp']);
$PathRate		= "../myFiles/_rate/".$foldername."";
unlink($PathRate.''.$thisEncrypter->decode($_REQUEST['fname']));
header('location:ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'');
	;
	break;

case "rtgeneralsave":
include_once('./phpexcel/PHPExcel/IOFactory.php');
//echo $thisEncrypter->decode($_REQUEST['idb']).'<br />';
//echo $thisEncrypter->decode($_REQUEST['idc']).'<br />';
//echo $thisEncrypter->decode($_REQUEST['idp']).'<br />';
//echo $thisEncrypter->decode($_REQUEST['fname']).'<br />';
$PathRate		= "../myFiles/_rate/".$foldername."";
$metFileNameExisting =$PathRate.''.$thisEncrypter->decode($_REQUEST['fname']);
$file_temp = $_SESSION['file_temp'];
$file_name = $_SESSION['file_name'];
//echo $metFileNameExisting.'<br />';
//echo $file_temp.'<br />';
//echo $file_name.'<br />';

try {
	$inputFileType = PHPExcel_IOFactory::identify($metFileNameExisting);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($metFileNameExisting);
} catch (Exception $e) {
	die('Error loading file "' . pathinfo($metFileNameExisting, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}

$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$newfilename = date('ymd_his').'_'.$file_name;

	for ($row = 2; $row <= $highestRow; $row++) {
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		echo "<tr>";
		$i = 0;
		foreach($rowData[0] as $k=>$v){
			$data[$i] = $v;
			$i++;
		}
		$today = date('Y-m-d');
		$_data1 = $data[1];	//TENOR FROM
		$_data2 = $data[2];	//TENOR TO
		$_data3 = $data[3];	//PLAFOND START
		$_data4 = $data[4];	//PLAFOND END
		$_data5 = $data[5];	//LOKASI
		$_data6 = $data[6];	//PERTANGGUNGAN
		$_data7 = $data[7];	//KELAS
		$_data8 = $data[8];	//RATE

		//echo $_data2.'-'.$_data3.'-'.$_data4.'-'.$_data5.'-'.$_data6.'-'.$_data7.'-'.$_data8.'<br />';
		if ($_data1 <=0) {	$tenorstart = "";	}	else	{
			$tenorstart = 'tenorstart="'.$_data1.'",';
		}
		if ($_data2 <=0) {	$tenorend = "";		}	else	{
			$tenorend = 'tenorend="'.$_data2.'",';
		}
		if ($_data3 <=0) {	$plafondstart = "";	}	else	{
			$plafondstart = 'plafondstart="'.$_data3.'",';
		}
		if ($_data4 <=0) {	$plafondend = "";	}	else	{
			$plafondend = 'plafondend="'.$_data4.'",';
		}
		if ($_data5 <=0) {	$lokasi = "";		}	else	{
			$setLokasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralarea WHERE kode="'.$_data5.'"'));
			$lokasi = 'lokasi="'.$setLokasi['id'].'",';
		}
		if ($_data6 <=0) {	$quarantee = "";	}	else	{
			$setQuarantee = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneralkategori WHERE kode="'.$_data6.'"'));
			$quarantee = 'quarantee="'.$setQuarantee['id'].'",';
		}
		if ($_data7 <=0) {	$kelas = "";		}	else	{
			$kelas = 'kelas="'.$_data7.'",';
		}

		$metRateGeneral__ = $database->doQuery('INSERT INTO ajkrategeneral SET idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'",
																			   idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'",
																			   idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'",
																			   '.$tenorstart.'
																			   '.$tenorend.'
																			   '.$plafondstart.'
																			   '.$plafondend.'
																			   '.$lokasi.'
																			   '.$quarantee.'
																			   '.$kelas.'
																			   rate="'.$_data8.'",
																			   type="'.$thisEncrypter->decode($_REQUEST['type']).'",
																			   fname="'.$thisEncrypter->decode($_REQUEST['fname']).'",
																			   input_by="'.$q['id'].'",
																			   input_time="'.$futgl.'"');
	}

echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=policy">
	<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Success!</strong> Upload rate general.</div>';
	;
	break;

case "gnguarantee":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup General Guarantee</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';

/*
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
INNER JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
*/
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan
FROM ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
WHERE ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"'));
if ($_REQUEST['met']=="savemeG") {
$metGenGuan = $database->doQuery('INSERT INTO ajkgeneraljaminan SET idbroker="'.$metGuanrantee['brokerid'].'",
																idpartner="'.$metGuanrantee['clientid'].'",
																idproduk="'.$metGuanrantee['produkid'].'",
																wilayah="'.$_REQUEST['guarantee_region'].'",
																idguarantee="'.$_REQUEST['coGuarantee'].'",
																carahitungkontribusi="'.$_REQUEST['guarantee_calcK'].'",
																carahitungresiko="'.$_REQUEST['guarantee_calcR'].'",
																inputby="'.$q['id'].'",
																inputdate="'.$futgl.'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
		<div class="alert alert-dismissable alert-success">
	    <strong>Success!</strong> Input Extendeed Coverage.
	    </div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGuanrantee['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGuanrantee['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGuanrantee['produk'].' ('.$metGuanrantee['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['clientlogo'].'" alt="" width="75px" height="55px"></div>';
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';

echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Guarantee</h3></div>
		<div class="panel-body">
		<div class="form-group">
		<label class="col-sm-3 control-label">Extendeed Coverage Name<span class="text-danger">*</span></label>
		<div class="col-sm-9"><select name="coGuarantee" class="form-control" required>
		            		<option value="">Select Guarantee</option>';
$metListGen = $database->doQuery('SELECT * FROM ajkgeneralnamajaminan WHERE idbroker="'.$metGuanrantee['brokerid'].'" AND del IS NULL');
while ($metListGen_ = mysql_fetch_array($metListGen)) {
$cekGuarantee = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneraljaminan WHERE idbroker="'.$metGuanrantee['brokerid'].'" AND idpartner="'.$metGuanrantee['clientid'].'" AND idproduk="'.$metGuanrantee['produkid'].'" AND idguarantee="'.$metListGen_['id'].'" AND del IS NULL'));
	if ($cekGuarantee['id']) {
	echo '<option value="'.$metListGen_['id'].'"'._selected($_REQUEST['coGuarantee'], $metListGen_['id']).' disabled>'.$metListGen_['namajaminan'].'</option>';
	}else{
	echo '<option value="'.$metListGen_['id'].'"'._selected($_REQUEST['coGuarantee'], $metListGen_['id']).'>'.$metListGen_['namajaminan'].'</option>';
	}
}
echo '</select></div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Region Guarantee<span class="text-danger">*</span></label>
    	<div class="col-sm-9">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($_REQUEST['guarantee_region'], "Ya").' name="guarantee_region" id="customradio1" value="Ya" required><label for="customradio1">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($_REQUEST['guarantee_region'], "Tidak").' name="guarantee_region" id="customradio2" value="Tidak" required><label for="customradio2">&nbsp;&nbsp;No</label>
        </span>
		</div>
	</div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Calculated Contribution (CMP & TLO)<span class="text-danger">*</span></label>
    	<div class="col-sm-4">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($_REQUEST['guarantee_calcK'], "Rate").' name="guarantee_calcK" id="customradio3" value="Rate" required><label for="customradio3">&nbsp;&nbsp;By Rate(%)&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($_REQUEST['guarantee_calcK'], "Plafond").' name="guarantee_calcK" id="customradio4" value="Plafond" required><label for="customradio4">&nbsp;&nbsp;Betwwen Plafond</label>
            <input type="radio"'.pilih($_REQUEST['guarantee_calcK'], "Percentage").' name="guarantee_calcK" id="customradio5" value="Percentage" required><label for="customradio5">&nbsp;&nbsp;By Percentage</label>
        </span>
		</div>
	</div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Calculated Risk (CMP & TLO)<span class="text-danger">*</span></label>
    	<div class="col-sm-4">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($_REQUEST['guarantee_calcR'], "Rate").' name="guarantee_calcR" id="customradio6" value="Rate" required><label for="customradio6">&nbsp;&nbsp;By Rate(%)&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($_REQUEST['guarantee_calcR'], "Plafond").' name="guarantee_calcR" id="customradio7" value="Plafond"  required><label for="customradio7">&nbsp;&nbsp;Betwwen Plafond</label>
            <input type="radio"'.pilih($_REQUEST['guarantee_calcR'], "Percentage").' name="guarantee_calcR" id="customradio8" value="Percentage"  required><label for="customradio8">&nbsp;&nbsp;By Percentage</label>
        </span>
		</div>
	</div>
	</form>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savemeG">'.BTN_SUBMIT.'</div>
</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	echo '</div></div>';
	echo '</div></div></div>';
	;
	break;

case "gnguaranteeedt":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Setup Edit General Guarantee</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';

/*
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneraljaminan.id,
ajkgeneraljaminan.wilayah,
ajkgeneraljaminan.idguarantee,
ajkgeneraljaminan.carahitungkontribusi,
ajkgeneraljaminan.carahitungresiko,
ajkgeneralnamajaminan.namajaminan
FROM
ajkgeneraljaminan
LEFT JOIN ajkcobroker ON ajkgeneraljaminan.idbroker = ajkcobroker.id
LEFT JOIN ajkclient ON ajkgeneraljaminan.idpartner = ajkclient.id
LEFT JOIN ajkpolis ON ajkgeneraljaminan.idproduk = ajkpolis.id
LEFT JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
LEFT JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
LEFT JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE
ajkgeneraljaminan.idproduk = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND
ajkgeneraljaminan.id = "'.$thisEncrypter->decode($_REQUEST['idg']).'"'));
*/
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkpolis.idgeneral,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneraljaminan.idguarantee,
ajkgeneraljaminan.wilayah,
ajkgeneraljaminan.carahitungkontribusi,
ajkgeneraljaminan.carahitungresiko,
ajkgeneralnamajaminan.namajaminan
FROM
ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneraljaminan ON ajkpolis.id = ajkgeneraljaminan.idproduk
INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE
ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkgeneraljaminan.id = "'.$thisEncrypter->decode($_REQUEST['idg']).'"'));
if ($_REQUEST['met']=="savemeG") {
$metGenGuan = $database->doQuery('UPDATE ajkgeneraljaminan SET idbroker="'.$metGuanrantee['brokerid'].'",
																idpartner="'.$metGuanrantee['clientid'].'",
																idproduk="'.$metGuanrantee['produkid'].'",
																wilayah="'.$_REQUEST['guarantee_region'].'",
																carahitungkontribusi="'.$_REQUEST['guarantee_calcK'].'",
																carahitungresiko="'.$_REQUEST['guarantee_calcR'].'",
																updateby="'.$q['id'].'",
																updatedate="'.$futgl.'"
									WHERE id="'.$thisEncrypter->decode($_REQUEST['idg']).'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
			  <div class="alert alert-dismissable alert-success">
	    	  <strong>Success!</strong> Update Extendeed Coverage.
	    	  </div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGuanrantee['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGuanrantee['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGuanrantee['produk'].' ('.$metGuanrantee['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['clientlogo'].'" alt="" width="75px" height="55px"></div>';
		echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';

		echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Guarantee</h3></div>
		<div class="panel-body">
	<div class="form-group">
		<label class="col-sm-3 control-label">Extendeed Coverage Name<span class="text-danger">*</span></label>
		<div class="col-sm-9"><input type="text" name="guarantee_ex" value="'.$metGuanrantee['namajaminan'].'" class="form-control" placeholder="Nama Perluasan Jaminan" disabled></div>
	</div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Region Guarantee<span class="text-danger">*</span></label>
    	<div class="col-sm-9">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($metGuanrantee['wilayah'], "Ya").' name="guarantee_region" id="customradio1" value="Ya" required><label for="customradio1">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metGuanrantee['wilayah'], "Tidak").' name="guarantee_region" id="customradio2" value="Tidak" required><label for="customradio2">&nbsp;&nbsp;No</label>
        </span>
		</div>
	</div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Calculated Contribution (CMP & TLO)<span class="text-danger">*</span></label>
    	<div class="col-sm-4">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($metGuanrantee['carahitungkontribusi'], "Rate").' name="guarantee_calcK" id="customradio3" value="Rate" required><label for="customradio3">&nbsp;&nbsp;By Rate(%)&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metGuanrantee['carahitungkontribusi'], "Plafond").' name="guarantee_calcK" id="customradio4" value="Plafond" required><label for="customradio4">&nbsp;&nbsp;Betwwen Plafond</label>
            <input type="radio"'.pilih($metGuanrantee['carahitungkontribusi'], "Percentage").' name="guarantee_calcK" id="customradio5" value="Percentage" required><label for="customradio5">&nbsp;&nbsp;By Percentage</label>
        </span>
		</div>
	</div>
	<div class="form-group">
    <label class="col-sm-3 control-label">Calculated Risk (CMP & TLO)<span class="text-danger">*</span></label>
    	<div class="col-sm-4">
        <span class="radio custom-radio custom-radio-primary">
        	<input type="radio"'.pilih($metGuanrantee['carahitungresiko'], "Rate").' name="guarantee_calcR" id="customradio6" value="Rate" required><label for="customradio6">&nbsp;&nbsp;By Rate(%)&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metGuanrantee['carahitungresiko'], "Plafond").' name="guarantee_calcR" id="customradio7" value="Plafond"  required><label for="customradio7">&nbsp;&nbsp;Betwwen Plafond</label>
            <input type="radio"'.pilih($metGuanrantee['carahitungresiko'], "Percentage").' name="guarantee_calcR" id="customradio8" value="Percentage"  required><label for="customradio8">&nbsp;&nbsp;By Percentage</label>
        </span>
		</div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savemeG">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	echo '</div></div>';
	echo '</div></div></div>';
	;
	break;

case "gnguaranteeadd":
echo '<div class="page-header-section"><h2 class="title semibold"><span class="figure"><i class="ico-file"></i></span> Add Calculted General Guarantee</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">'.BTN_BACK.'</a></div></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">';
/*
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneraljaminan.id,
ajkgeneraljaminan.wilayah,
ajkgeneraljaminan.idguarantee,
ajkgeneraljaminan.carahitungkontribusi,
ajkgeneraljaminan.carahitungresiko,
ajkgeneralnamajaminan.id AS idnamajaminan,
ajkgeneralnamajaminan.namajaminan
FROM ajkgeneraljaminan
LEFT JOIN ajkcobroker ON ajkgeneraljaminan.idbroker = ajkcobroker.id
LEFT JOIN ajkclient ON ajkgeneraljaminan.idpartner = ajkclient.id
LEFT JOIN ajkpolis ON ajkgeneraljaminan.idproduk = ajkpolis.id
LEFT JOIN ajklistgeneral ON ajkpolis.id = ajklistgeneral.idproduk
LEFT JOIN ajkgeneraltype ON ajklistgeneral.idgeneral = ajkgeneraltype.id
LEFT JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE ajkgeneraljaminan.idproduk = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkgeneraljaminan.id = "'.$thisEncrypter->decode($_REQUEST['idg']).'"'));
*/
$metGuanrantee = mysql_fetch_array($database->doQuery('SELECT
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkcobroker.logo AS brokerlogo,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkclient.logo AS clientlogo,
ajkpolis.id AS produkid,
ajkpolis.produk,
ajkpolis.idgeneral,
ajkgeneraltype.type,
ajkgeneraltype.kode,
ajkgeneraltype.keterangan,
ajkgeneraljaminan.id,
ajkgeneraljaminan.idguarantee,
ajkgeneraljaminan.wilayah,
ajkgeneraljaminan.carahitungkontribusi,
ajkgeneraljaminan.carahitungresiko,
ajkgeneralnamajaminan.namajaminan
FROM
ajkpolis
INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
INNER JOIN ajkgeneraltype ON ajkpolis.idgeneral = ajkgeneraltype.id
INNER JOIN ajkgeneraljaminan ON ajkpolis.id = ajkgeneraljaminan.idproduk
INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE
ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkgeneraljaminan.id = "'.$thisEncrypter->decode($_REQUEST['idg']).'"'));
if ($_REQUEST['met']=="savemeGx") {
//echo('INSERT INTO ajkgeneraljaminanrate SET idgeneraljaminan="'.$_REQUEST['idnamajaminan'].'",
//echo 'area ="'.$_REQUEST['gArea'].'"<br />';
//echo 'typejaminan ="'.$_REQUEST['mettypepremium'].'"<br />';
if ($_REQUEST['gArea']=="") {						$_metAreanya = '';	}else{	$_metAreanya = 'area="'.$_REQUEST['gArea'].'",';	}
if ($_REQUEST['cont_comp_rate']=="") {				$_met1 = '';	}else{	$_met1 = 'c_cpr_rate="'.$_REQUEST['cont_comp_rate'].'",';	}
if ($_REQUEST['cont_tlo_rate']=="") {				$_met2 = '';	}else{	$_met2 = 'c_tlo_rate="'.$_REQUEST['cont_tlo_rate'].'",';	}
if ($_REQUEST['risk_comp_rate']=="") {				$_met3 = '';	}else{	$_met3 = 'r_cpr_rate="'.$_REQUEST['risk_comp_rate'].'",';	}
if ($_REQUEST['tlo_comp_rate']=="") {				$_met4 = '';	}else{	$_met4 = 'r_tlo_rate="'.$_REQUEST['tlo_comp_rate'].'",';	}
if ($_REQUEST['cont_comp_plafondminimum']=="") {	$_met5 = '';	}else{	$_met5 = 'c_cpr_plafondstart="'.$_REQUEST['cont_comp_plafondminimum'].'",';	}
if ($_REQUEST['cont_comp_plafondmaximum']=="") {	$_met6 = '';	}else{	$_met6 = 'c_cpr_plafondend="'.$_REQUEST['cont_comp_plafondmaximum'].'",';	}
if ($_REQUEST['cont_comp_plafondpersentase']=="") {	$_met7 = '';	}else{	$_met7 = 'c_cpr_plafondpersen="'.$_REQUEST['cont_comp_plafondpersentase'].'",';	}
if ($_REQUEST['cont_tlo_plafondminimum']=="") {		$_met8 = '';	}else{	$_met8 = 'c_tlo_plafondstart="'.$_REQUEST['cont_tlo_plafondminimum'].'",';	}
if ($_REQUEST['cont_tlo_plafondmaximum']=="") {		$_met9 = '';	}else{	$_met9 = 'c_tlo_plafondend="'.$_REQUEST['cont_tlo_plafondmaximum'].'",';	}
if ($_REQUEST['cont_tlo_plafondpersentase']=="") {	$_met10 = '';	}else{	$_met10 = 'c_tlo_plafondpersen="'.$_REQUEST['cont_tlo_plafondpersentase'].'",';	}
if ($_REQUEST['risk_comp_plafondstart']=="") {		$_met11 = '';	}else{	$_met11 = 'r_cpr_plafondstart="'.$_REQUEST['risk_comp_plafondstart'].'",';	}
if ($_REQUEST['risk_comp_plafondend']=="") {		$_met12 = '';	}else{	$_met12 = 'r_cpr_plafondend="'.$_REQUEST['risk_comp_plafondend'].'",';	}
if ($_REQUEST['risk_comp_persentaseplafond']=="") {	$_met13 = '';	}else{	$_met13 = 'r_cpr_plafondpersen="'.$_REQUEST['risk_comp_persentaseplafond'].'",';	}
if ($_REQUEST['risk_tlo_plafondstart']=="") {		$_met14 = '';	}else{	$_met14 = 'r_tlo_plafondstart="'.$_REQUEST['risk_tlo_plafondstart'].'",';	}
if ($_REQUEST['risk_tlo_plafondend']=="") {			$_met15 = '';	}else{	$_met15 = 'r_tlo_plafondend="'.$_REQUEST['risk_tlo_plafondend'].'",';	}
if ($_REQUEST['risk_tlo_persentaseplafond']=="") {	$_met16 = '';	}else{	$_met16 = 'r_tlo_plafondpersen="'.$_REQUEST['risk_tlo_persentaseplafond'].'",';	}
if ($_REQUEST['cont_comp_persentase']=="") {		$_met17 = '';	}else{	$_met17 = 'c_cpr_nilaipersen="'.$_REQUEST['cont_comp_persentase'].'",';	}
if ($_REQUEST['cont_comp_persentasenilai']=="") {	$_met18 = '';	}else{	$_met18 = 'c_cpr_nilaiminimum="'.$_REQUEST['cont_comp_persentasenilai'].'",';	}
if ($_REQUEST['cont_tlo_persentase']=="") {			$_met19 = '';	}else{	$_met19 = 'c_tlo_nilaipersen="'.$_REQUEST['cont_tlo_persentase'].'",';	}
if ($_REQUEST['cont_tlo_persentasenilai']=="") {	$_met20 = '';	}else{	$_met20 = 'c_tlo_nilaiminimum="'.$_REQUEST['cont_tlo_persentasenilai'].'",';	}
if ($_REQUEST['risk_comp_persentase']=="") {		$_met21 = '';	}else{	$_met21 = 'r_cpr_nilaipersen="'.$_REQUEST['risk_comp_persentase'].'",';	}
if ($_REQUEST['risk_comp_persentasenilai']=="") {	$_met22 = '';	}else{	$_met22 = 'r_cpr_nilaiminimum="'.$_REQUEST['risk_comp_persentasenilai'].'",';	}
if ($_REQUEST['risk_tlo_persentase']=="") {			$_met23 = '';	}else{	$_met23 = 'r_tlo_nilaipersen="'.$_REQUEST['risk_tlo_persentase'].'",';	}
if ($_REQUEST['risk_tlo_persentasenilai']=="") {	$_met24 = '';	}else{	$_met24 = 'r_tlo_nilaiminimum="'.$_REQUEST['risk_tlo_persentasenilai'].'",';	}

/*
echo 'c_cpr_rate="'.$_REQUEST['cont_comp_rate'].'"<br>';
echo 'c_tlo_rate="'.$_REQUEST['cont_tlo_rate'].'"<br>';
echo 'r_cpr_rate="'.$_REQUEST['risk_comp_rate'].'"<br>';
echo 'r_tlo_rate="'.$_REQUEST['tlo_comp_rate'].'"<br>';
echo 'c_cpr_plafondstart="'.$_REQUEST['cont_comp_plafondminimum'].'"<br>';
echo 'c_cpr_plafondend="'.$_REQUEST['cont_comp_plafondmaximum'].'"<br>';
echo 'c_cpr_plafondpersen="'.$_REQUEST['cont_comp_plafondpersentase'].'"<br>';
echo 'c_tlo_plafondstart="'.$_REQUEST['cont_tlo_plafondminimum'].'"<br>';
echo 'c_tlo_plafondend="'.$_REQUEST['cont_tlo_plafondmaximum'].'"<br>';
echo 'c_tlo_plafondpersen="'.$_REQUEST['cont_tlo_plafondpersentase'].'"<br>';
echo 'r_cpr_plafondstart="'.$_REQUEST['risk_comp_plafondstart'].'"<br>';
echo 'r_cpr_plafondend="'.$_REQUEST['risk_comp_plafondend'].'"<br>';
echo 'r_cpr_plafondpersen="'.$_REQUEST['risk_comp_persentaseplafond'].'"<br>';
echo 'r_tlo_plafondstart="'.$_REQUEST['risk_tlo_plafondstart'].'"<br>';
echo 'r_tlo_plafondend="'.$_REQUEST['risk_tlo_plafondend'].'"<br>';
echo 'r_tlo_plafondpersen="'.$_REQUEST['risk_tlo_persentaseplafond'].'"<br>';
echo 'c_cpr_nilaipersen="'.$_REQUEST['cont_comp_persentase'].'"<br>';
echo 'c_cpr_nilaiminimum="'.$_REQUEST['cont_comp_persentasenilai'].'"<br>';
echo 'c_tlo_nilaipersen="'.$_REQUEST['cont_tlo_persentase'].'"<br>';
echo 'c_tlo_nilaiminumum="'.$_REQUEST['cont_tlo_persentasenilai'].'"<br>';
echo 'r_cpr_nilaipersen="'.$_REQUEST['risk_comp_persentase'].'"<br>';
echo 'r_cpr_nilaiminimum="'.$_REQUEST['risk_comp_persentasenilai'].'"<br>';
echo 'r_tlo_nilaipersen="'.$_REQUEST['risk_tlo_persentase'].'"<br>';
echo 'r_tlo_nilaiminumum="'.$_REQUEST['risk_tlo_persentasenilai'].'"<br>';
*/

$metGenGuan = $database->doQuery('INSERT INTO ajkgeneraljaminanrate SET idgeneraljaminan="'.$_REQUEST['idnamajaminan'].'",
																		'.$_metAreanya.'
																		typejaminan="'.$_REQUEST['mettypepremium'].'",
																		'.$_met1.'
																		'.$_met2.'
																		'.$_met3.'
																		'.$_met4.'
																		'.$_met5.'
																		'.$_met6.'
																		'.$_met7.'
																		'.$_met8.'
																		'.$_met9.'
																		'.$_met10.'
																		'.$_met11.'
																		'.$_met12.'
																		'.$_met13.'
																		'.$_met14.'
																		'.$_met15.'
																		'.$_met16.'
																		'.$_met17.'
																		'.$_met18.'
																		'.$_met19.'
																		'.$_met20.'
																		'.$_met21.'
																		'.$_met22.'
																		'.$_met23.'
																		'.$_met24.'
																		inputby="'.$q['id'].'",
																		inputdate="'.$futgl.'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=client&op=rtgeneral&idp='.$_REQUEST['idp'].'">
			  <div class="alert alert-dismissable alert-success"><strong>Success!</strong> Update Extendeed Coverage.</div>';
}
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['brokerlogo'].'" alt="" width="75px" height="55px"></div>
			<div class="col-md-10">
				<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metGuanrantee['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metGuanrantee['clientname'].'</dd>
					<dt>Product</dt><dd><span class="label label-primary">'.$metGuanrantee['produk'].' ('.$metGuanrantee['keterangan'].')</span></dd>
				</dl>
			</div>
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metGuanrantee['clientlogo'].'" alt="" width="75px" height="55px"></div>';
	echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'';

	echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Add Calculated Guarantee</h3></div>
	<input type="hidden" name="idnamajaminan" value="'.$metGuanrantee['id'].'">
	<div class="panel-body">
		<div class="form-group">
		<label class="col-sm-2 control-label">Guarantee Name</label>
		<div class="col-sm-10"><input type="text" name="guarantee_ex" value="'.$metGuanrantee['namajaminan'].'" class="form-control" placeholder="Nama Perluasan Jaminan" disabled></div>
		</div>
	<!--<div class="form-group">
	<label class="col-sm-2 control-label">Type Premium <span class="text-danger">*</span></label>
		<div class="col-sm-10">
		<span class="radio custom-radio custom-radio-primary">
		    <input type="radio"'.pilih($_REQUEST['mettypepremium'], "Resiko").' name="mettypepremium" id="customradio1" value="Resiko" required><label for="customradio1">&nbsp;&nbsp;Own Risk&nbsp;&nbsp;</label>
			<input type="radio"'.pilih($_REQUEST['mettypepremium'], "Kontribusi").' name="mettypepremium" id="customradio2" value="Kontribusi" required><label for="customradio2">&nbsp;&nbsp;Minimum Contribution</label>
		</span>
		</div>
	</div>-->';
if ($metGuanrantee['wilayah']=="Ya") {
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Region<span class="text-danger">*</span></label>
		<div class="col-sm-10"><select name="gArea" class="form-control" required>
		            		<option value="">Select Region</option>';
$metListArea = $database->doQuery('SELECT * FROM ajkgeneralarea WHERE idbroker="'.$metGuanrantee['brokerid'].'" AND idpartner="'.$metGuanrantee['clientid'].'" AND idproduk="'.$metGuanrantee['produkid'].'" AND del IS NULL');
while ($metListArea_ = mysql_fetch_array($metListArea)) {
$metCekGenRegioan = mysql_fetch_array($database->doQuery('SELECT
ajkgeneraljaminanrate.id
FROM
ajkgeneraljaminanrate
INNER JOIN ajkgeneraljaminan ON ajkgeneraljaminanrate.idgeneraljaminan = ajkgeneraljaminan.id
INNER JOIN ajkgeneralarea ON ajkgeneraljaminanrate.area = ajkgeneralarea.id
INNER JOIN ajkclient ON ajkgeneraljaminan.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajkgeneraljaminan.idproduk = ajkpolis.id
INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE ajkgeneraljaminan.idproduk="'.$metGuanrantee['produkid'].'" AND ajkgeneraljaminanrate.area="'.$metListArea_['id'].'" AND ajkgeneraljaminan.idguarantee="'.$metGuanrantee['idnamajaminan'].'"'));
if ($metCekGenRegioan['id']) {
		echo '<option value="'.$metListArea_['id'].'"'._selected($_REQUEST['gArea'], $metListArea_['id']).' disabled>'.$metListArea_['area'].': '.$metListArea_['lokasi'].'</option>';
}else{
	echo '<option value="'.$metListArea_['id'].'"'._selected($_REQUEST['gArea'], $metListArea_['id']).'>'.$metListArea_['area'].': '.$metListArea_['lokasi'].'</option>';
}
}
echo '</select>
	</div>';
}else{	echo '<div class="form-group">';	}

echo '</div>
	<div class="row bgcolor-white">
    	<div class="col-sm-3">
        <div class="note note-primary mb15"><strong>Premium Contribution</strong></div>
        </div>
        <div class="col-md-12">';
if ($metGuanrantee['carahitungkontribusi']=="Rate") {
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive</label>
       	<div class="col-sm-10"><input type="text" value="'.$_REQUEST['cont_comp_rate'].'" name="cont_comp_rate"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only</label>
       	<div class="col-sm-10"><input type="text" value="'.$_REQUEST['cont_tlo_rate'].'" name="cont_tlo_rate"></div>
	</div>';
}elseif ($metGuanrantee['carahitungkontribusi']=="Plafond") {
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive Minimum UP</label>
        <div class="col-sm-2"><input type="text" name="cont_comp_plafondminimum" value="'.$_REQUEST['cont_comp_plafondminimum'].'" class="form-control" data-parsley-type="number" placeholder="Minimum UP"></div>
        <label class="col-sm-2 control-label">Comprehensive Maximum UP</label>
        <div class="col-sm-2"><input type="text" name="cont_comp_plafondmaximum" value="'.$_REQUEST['cont_comp_plafondmaximum'].'" class="form-control" data-parsley-type="number" placeholder="Maximum UP"></div>
        <label class="col-sm-2 control-label">Comprehensive Percentage</label>
        <div class="col-sm-2"><input type="text" name="cont_comp_plafondpersentase" value="'.$_REQUEST['cont_comp_plafondpersentase'].'" class="form-control" data-parsley-type="number" placeholder="Percentage"></div>
    </div>
    <div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only Minimum UP</label>
        <div class="col-sm-2"><input type="text" name="cont_tlo_plafondminimum" value="'.$_REQUEST['cont_tlo_plafondminimum'].'" class="form-control" data-parsley-type="number" placeholder="Minimum UP"></div>
        <label class="col-sm-2 control-label">Total Loss Only Maximum UP</label>
        <div class="col-sm-2"><input type="text" name="cont_tlo_plafondmaximum" value="'.$_REQUEST['cont_tlo_plafondmaximum'].'" class="form-control" data-parsley-type="number" placeholder="Maximum UP"></div>
        <label class="col-sm-2 control-label">Total Loss Only Percentage</label>
        <div class="col-sm-2"><input type="text" name="cont_tlo_plafondpersentase" value="'.$_REQUEST['cont_tlo_plafondpersentase'].'" class="form-control" data-parsley-type="number" placeholder="Percentage"></div>
    </div>';

}else{
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive Percentage</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['cont_comp_persentase'].'" name="cont_comp_persentase"></div>
		<label class="col-sm-2 control-label">Comprehensive Minimum Value</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['cont_comp_persentasenilai'].'" name="cont_comp_persentasenilai" class="form-control" data-parsley-type="number" placeholder="Minimum Value"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only Percentage</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['cont_tlo_persentase'].'" name="cont_tlo_persentase"></div>
		<label class="col-sm-2 control-label">Total Loss Only Minimum Value</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['cont_tlo_persentasenilai'].'" name="cont_tlo_persentasenilai" class="form-control" data-parsley-type="number" placeholder="Minimum Value"></div>
	</div>';
}
echo '</div>

		<div class="col-sm-3">
        <div class="note note-info mb15"><strong>Premium Risk</strong></div>
        </div>
		<div class="col-md-12">';
if ($metGuanrantee['carahitungresiko']=="Rate") {
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive</label>
       	<div class="col-sm-10"><input type="text" value="'.$_REQUEST['risk_comp_rate'].'" name="risk_comp_rate"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only</label>
       	<div class="col-sm-10"><input type="text" value="'.$_REQUEST['risk_tlo_rate'].'" name="risk_tlo_rate"></div>
	</div>';
		}elseif ($metGuanrantee['carahitungresiko']=="Plafond") {
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive Minimum UP</label>
        <div class="col-sm-2"><input type="text" name="risk_comp_plafondstart" value="'.$_REQUEST['risk_comp_plafondstart'].'" class="form-control" data-parsley-type="number" placeholder="Minimum UP"></div>
        <label class="col-sm-2 control-label">Comprehensive Maximum UP</label>
        <div class="col-sm-2"><input type="text" name="risk_comp_plafondend" value="'.$_REQUEST['risk_comp_plafondend'].'" class="form-control" data-parsley-type="number" placeholder="Maximum UP"></div>
        <label class="col-sm-2 control-label">Comprehensive Percentage</label>
        <div class="col-sm-2"><input type="text" name="risk_comp_persentaseplafond" value="'.$_REQUEST['risk_comp_persentaseplafond'].'" class="form-control" data-parsley-type="number" placeholder="Percentage"></div>
    </div>
    <div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only Minimum UP</label>
        <div class="col-sm-2"><input type="text" name="risk_tlo_plafondstart" value="'.$_REQUEST['risk_tlo_plafondstart'].'" class="form-control" data-parsley-type="number" placeholder="Minimum UP"></div>
        <label class="col-sm-2 control-label">Total Loss Only Maximum UP</label>
        <div class="col-sm-2"><input type="text" name="risk_tlo_plafondend" value="'.$_REQUEST['risk_tlo_plafondend'].'" class="form-control" data-parsley-type="number" placeholder="Maximum UP"></div>
        <label class="col-sm-2 control-label">Total Loss Only Percentage</label>
        <div class="col-sm-2"><input type="text" name="risk_tlo_persentaseplafond" value="'.$_REQUEST['risk_tlo_persentaseplafond'].'" class="form-control" data-parsley-type="number" placeholder="Percentage"></div>
    </div>';

		}else{
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Comprehensive Percentage</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['risk_comp_persentase'].'" name="risk_comp_persentase"></div>
		<label class="col-sm-2 control-label">Comprehensive Minimum Value</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['risk_comp_persentasenilai'].'" name="risk_comp_persentasenilai" class="form-control" data-parsley-type="number" placeholder="Minimum Value"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Total Loss Only Percentage</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['risk_tlo_persentase'].'" name="risk_tlo_persentase"></div>
		<label class="col-sm-2 control-label">Total Loss Only Minimum Value</label>
       	<div class="col-sm-4"><input type="text" value="'.$_REQUEST['risk_tlo_persentasenilai'].'" name="risk_tlo_persentasenilai" class="form-control" data-parsley-type="number" placeholder="Minimum Value"></div>
	</div>';
		}
echo '</div>


	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savemeGx">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>
</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	echo '</div></div>';
	echo '</div></div></div>';
	;
	break;

case "d":
		;
		break;

	default:
		;
} // switch

echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>
