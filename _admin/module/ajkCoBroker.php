<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['co']) {
	case "newcob":
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

		$file_type = $_FILES['fileImage']['type']; //tipe file
		$source = $_FILES['fileImage']['tmp_name'];
		$direktori = "../$PathPhoto$nama_file"; // direktori tempat menyimpan file
		move_uploaded_file($source,$direktori);
	//metImage($nama_file);
	$metCompany = $database->doQuery('INSERT INTO ajkcobroker SET name="'.strtoupper($_REQUEST['companyname']).'",
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
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=cob">
				 <div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> Input New Broker '.strtoupper($_REQUEST['companyname']).'.
                 </div>';
	}
}
echo '<div class="page-header-section"><h2 class="title semibold">Modul Co-Broker</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=cob">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New Form Co-Broker</h3></div>
		<div class="panel-body">
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
echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
    <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	case "cobedt":
$cobroker_ = $thisEncrypter->decode($_REQUEST['cid']);
$metCobE = mysql_fetch_array($database->doQuery('SELECT * FROM ajkcobroker WHERE id="'.$cobroker_.'"'));
if ($_REQUEST['met']=="Editme") {
	if ($_FILES['fileImage']['size'] / 1024 > $FILESIZE_2)	{
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div>';
	}
	else{
	$metCompany = $database->doQuery('UPDATE ajkcobroker SET name="'.strtoupper($_REQUEST['companyname']).'",
															  address1="'.ucwords($_REQUEST['street']).'",
															  address2="'.ucwords($_REQUEST['addressline']).'",
															  city="'.strtoupper($_REQUEST['city']).'",
															  postcode="'.$_REQUEST['postcode'].'",
															  phoneoffice="'.$_REQUEST['phoneoffice'].'",
															  phonehp="'.$_REQUEST['phonehp'].'",
															  phonefax="'.$_REQUEST['phonefax'].'",
															  masterbroker="'.$_REQUEST['mstbroker'].'",
															  update_by="'.$q['id'].'",
															  update_time="'.$futgl.'"
										WHERE id="'.$cobroker_.'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=cob">
					 <div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> Edit Broker '.strtoupper($_REQUEST['companyname']).'.
                 </div>';
	}
}
echo '<div class="page-header-section"><h2 class="title semibold">Modul Co-Broker</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=cob">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
	'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Form Co-Broker</h3></div>
		<div class="panel-body">
            <div class="form-group">
            <label class="col-sm-2 control-label">Master Broker</label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($metCobE['masterbroker'], "Y").' name="mstbroker" id="customradio1" value="Y" required><label for="customradio1">&nbsp;&nbsp;Ya &nbsp;&nbsp;</label>
                    	<input type="radio"'.pilih($metCobE['masterbroker'], "T").' name="mstbroker" id="customradio2" value="T" required><label for="customradio2">&nbsp;&nbsp;Tidak</label>
                    </span>
				</div>
			</div>
			<div class="form-group">
					<label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
					<div class="col-sm-10"><input type="text" name="companyname" value="'.$metCobE['name'].'" class="form-control" placeholder="Company Name" required></div>
			</div>

			<div class="form-group">
	            <label class="control-label col-sm-2">Address <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
                <div class="row mb5"><div class="col-sm-12"><input name="street" value="'.$metCobE['address1'].'" type="text" class="form-control" placeholder="Street Address" required></div></div>
				<div class="row mb5"><div class="col-sm-12"><input name="addressline" value="'.$metCobE['address2'].'" type="text" class="form-control" placeholder="Address Line 2"></div></div>
				<div class="row">
                	<div class="col-xs-6 pr5"><input name="city" value="'.$metCobE['city'].'" type="text" class="form-control" placeholder="City" required></div>
                    <div class="col-xs-6 pl5"><input name="postcode" value="'.$metCobE['postcode'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
				</div>
                </div>
            </div>

            <div class="form-group">
	            <label class="control-label col-sm-2">Phone <span class="text-danger">*</span></label>
            	<div class="col-sm-10">
				<div class="row">
                	<div class="col-xs-4 pr5"><input name="phoneoffice" value="'.$metCobE['phoneoffice'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Phone Office" required></div>
                	<div class="col-xs-4 pc5"><input name="phonehp" value="'.$metCobE['phonehp'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Handphone" required></div>
                    <div class="col-xs-4 pl5"><input name="phonefax" value="'.$metCobE['phonefax'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Fax" required></div>
				</div>
				</div>
            </div>

		</div>
	<div class="panel-footer"><input type="hidden" name="met" value="Editme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
    <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

	case "cobview":
$metComp = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.`name` AS brokername,
														ajkcobroker.logo AS brokerlogo,
														ajkclient.`name` AS clientname,
														ajkclient.logo AS clientlogo,
														ajkcabang.`name` AS cabang,
														leveluser.nama AS leveluser,
														useraccess.tipe,
														useraccess.username,
														useraccess.passw,
														useraccess.mamet,
														CONCAT(useraccess.firstname,useraccess.lastname," ") AS namauser,
														IF(useraccess.gender="L","Male", "Female") AS genderuser,
														useraccess.dob,
														useraccess.email,
														useraccess.`status`,
														useraccess.aktif,
														useraccess.photo
												FROM useraccess
												LEFT JOIN ajkcobroker ON useraccess.idbroker = ajkcobroker.id
												LEFT JOIN ajkclient ON useraccess.idclient = ajkclient.id
												LEFT JOIN ajkcabang ON useraccess.branch = ajkcabang.er
												LEFT JOIN leveluser ON useraccess.`level` = leveluser.er
												WHERE useraccess.id = "'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
echo '<div class="page-header-section"><h2 class="title semibold">Modul Co-Broker</h2></div>
			<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=cob">'.BTN_BACK.'</a></div>
			</div>
		</div>';
echo '<div class="row">
			<div class="col-lg-12">
	        	<div class="tab-content">
	            	<div class="tab-pane active" id="profile">
	                <form class="panel form-horizontal form-bordered" name="form-profile">
						<div class="panel-body pt0 pb0">
	                    	<div class="form-group header bgcolor-default">
	                        	<div class="col-md-6">
	            					<ul class="list-table">
	            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['brokerlogo'].'" alt="" width="75px"></li>
									<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['brokername'].'</h4></li>
									</ul>
								</div>
								<div class="col-md-6">
	            					<ul class="list-table">
	            					<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['clientname'].'</h4></li>
									<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['clientlogo'].'" alt="" width="75px"></li>
									</ul>
								</div>
	                        </div>
							<div class="form-group">
	                            <div class="col-md-8">
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Level</a></p></div></div><div class="text-default"><p>'.$metComp['leveluser'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Member Name</a></p></div></div><div class="text-default"><p>'.$metComp['namauser'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Gender</a></p></div></div><div class="text-default"><p>'.$metComp['genderuser'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">D.O.B</a></p></div></div><div class="text-default"><p>'._convertDate($metComp['dob']).'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Username</a></p></div></div><div class="text-default"><p>'.$metComp['username'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Password <font color="white">'.$metComp['mamet'].'</font></a></p></div></div><div class="text-default"><p>'.$metComp['passw'].'</p></div>
									<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Email</a></p></div></div><div class="text-default"><p><a href="mailto:'.$metComp['email'].'">'.$metComp['email'].'</a></p></div>
	                            	<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Branch</a></p></div></div><div class="text-default"><p>'.$metComp['cabang'].'</p></div>
								</div>
								<div class="col-md-4">
									<div class="table-layout mt1 mb0"><div class="col-sm-12 text-center"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['photo'].'" alt="" width="300px"></div></div>
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

	case "s":

		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">Modul Co-Broker</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=cob&co=newcob">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="row">
      	<div class="col-md-12">

        	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="1%">No</th>
        <th width="1%">Master</th>
        <th width="1%">Logo</th>
        <th>Broker</th>
        <th width="30%">Address</th>
        <th width="10%">Option</th>
        </tr>
    </thead>
    <tbody>';
$metCOB = $database->doQuery('SELECT * FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY id DESC');
while ($metCOB_ = mysql_fetch_array($metCOB)) {
	if ($metCOB_['logo']=="") {
	$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
	}else{
	$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.''.$metCOB_['logo'].'" alt="" class="img-circle"></div>';
	}

IF($metCOB_['masterbroker']=="Y"){
$mesterbroker = '<span class="label label-success">Ya</span>';
}else{
$mesterbroker = '<span class="label label-default">Tidak</span>';
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td align="center">'.$mesterbroker.'</td>
   	<td>'.$logoCOB.'</td>
   	<td><a href="ajk.php?re=cob&co=cobview&cid='.$thisEncrypter->encode($metCOB_['id']).'">'.$metCOB_['name'].'</a></td>
   	<td>'.$metCOB_['address1'].'</td>
   	<td align="center"><a href="ajk.php?re=cob&co=cobedt&cid='.$thisEncrypter->encode($metCOB_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
		<tfoot>
        <tr>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="y/t"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Address"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
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