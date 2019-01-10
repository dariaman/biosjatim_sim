<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['er']) {
case "mobnotif":
echo '<div class="page-header-section"><h2 class="title semibold">Mobile Notification</h2></div>
      </div>
      <script type="text/javascript">
$(document).ready(function(){
    $(\'input[type="radio"]\').click(function(){
        if($(this).attr("value")=="AJK")			{	$(".box").not(".AJK").hide();				$(".AJK").show();	}
        if($(this).attr("value")=="USER_SEGMEN")	{	$(".box").not(".USER_SEGMEN").hide();		$(".USER_SEGMEN").show();	}
        if($(this).attr("value")=="SINGLE_DEVICE")	{	$(".box").not(".SINGLE_DEVICE").hide();		$(".SINGLE_DEVICE").show();	}
    });
});
</script>
<style type="text/css">
    .box{
        display: none;
    }
</style>';

if ($_REQUEST['met']=="saveme") {
	foreach ($_REQUEST['packagenamenya'] as $a){
	$packagenya .= '"'.$a.'",';
	}
$packagenya_ = substr($packagenya,0,-1);
if ($_REQUEST['TargetMsg']=="USER_SEGMEN") {
	$metSegmen = $database->doQuery('SELECT user_mobile_token.id,
											user_mobile_token.UserID,
											user_mobile_token.UserToken,
											user_mobile_token.UserImei,
											user_mobile_token.packagename,
											CONCAT(useraccess.firstname," ",useraccess.lastname) AS nameuser,
											useraccess.email
									FROM user_mobile_token
									INNER JOIN useraccess ON user_mobile_token.UserID = useraccess.id
									WHERE user_mobile_token.packagename IN ('.$packagenya_.')');
	while ($metSegmen_ = mysql_fetch_array($metSegmen)) {
	$data = array("post_title" => $_REQUEST['metMsg1'],
				  "post_msg" => $_REQUEST['metMsg2']);
	_sendnotif($metSegmen_['UserToken'],$data);
	}
}else{
$metPackUser = $database->doQuery('SELECT CONCAT(useraccess.firstname," ",useraccess.lastname) AS nameuser,
										  user_mobile_token.UserToken,
										  user_mobile_token.UserImei,
										  user_mobile_token.packagename
									FROM user_mobile_token
									INNER JOIN useraccess ON user_mobile_token.UserID = useraccess.id
									WHERE user_mobile_token.UserID = "'.$_REQUEST['packageusernya'].'"');
while ($metPackUser_ = mysql_fetch_array($metPackUser)) {
	//echo $metPackUser_['nameuser'];
	$data = array("post_title" => $_REQUEST['metMsg1'],
				  "post_msg" => $_REQUEST['metMsg2']);
	_sendnotif($metPackUser_['UserToken'],$data);
}
}
header('location:ajk.php?re=utilities&er=mobnotif');
}
$metPackage = $database->doQuery('SELECT * FROM user_mobile_token GROUP BY packagename ORDER BY packagename DESC');
$metDeviceUser = $database->doQuery('SELECT useraccess.username,
											user_mobile_token.UserID,
											user_mobile_token.packagename,
											user_mobile_token.UserToken
									 FROM user_mobile_token
									 INNER JOIN useraccess ON user_mobile_token.UserID = useraccess.id
									 GROUP BY user_mobile_token.UserID
									 ORDER BY useraccess.username DESC');
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">Form Header Invoice</h3></div>
			<div class="panel-body">
				<div class="form-group">
		            <label class="control-label col-sm-2">Message text <span class="text-danger">*</span></label>
	            	<div class="col-sm-10">
	                <div class="row mb5"><div class="col-sm-12"><input name="metMsg1" value="'.$_REQUEST['metMsg1'].'" type="text" class="form-control" placeholder="Message text" required></div></div>
	                </div>
	            </div>
	            <div class="form-group">
		            <label class="control-label col-sm-2">Message label <span class="text-danger">*</span></label>
	            	<div class="col-sm-10">
	                <div class="row mb5"><div class="col-sm-12"><input name="metMsg2" value="'.$_REQUEST['metMsg2'].'" type="text" class="form-control" placeholder="Message label" required></div></div>
	                </div>
	            </div>
				<div class="form-group">
            		<label class="col-sm-2 control-label">Target <span class="text-danger">*</span></label>
            		<div class="col-sm-10">
                    	<span class="radio custom-radio custom-radio-primary">
                    	<input type="radio"'.pilih($_REQUEST['TargetMsg'], "USER_SEGMEN").' name="TargetMsg" id="customradio1" value="USER_SEGMEN" required><label for="customradio1">&nbsp;&nbsp;User Segmen</label>
						<input type="radio"'.pilih($_REQUEST['TargetMsg'], "SINGLE_DEVICE").' name="TargetMsg" id="customradio2" value="SINGLE_DEVICE" required><label for="customradio2">&nbsp;&nbsp;Single Device</label>
                    	</span>
					</div>
				</div>



			<div class="USER_SEGMEN box">
			<label class="col-sm-2 control-label">&nbsp;</label>
				<div class=" col-sm-10">
					<div class="panel panel-success">
            			<div class="panel-heading"><h3 class="panel-title">User Segmen</h3></div>
            			<div class="panel-body">
						<div class="form-group">
        				<label class="col-sm-2 control-label">Package Name </label>
        					<div class="col-sm-10">
            				<select id="selectize-selectmultiple" name="packagenamenya[]" class="form-control" placeholder="Select package..." multiple>';
                        	while ($metPackage_ = mysql_fetch_array($metPackage)) {
                          		echo '<option value="'.$metPackage_['packagename'].'"'._selected($_REQUEST['packagenamenya'], $metPackage_['packagename']).'>'.$metPackage_['packagename'].'</option>';
                          	}
            			echo '</select>
							</div>
						</div>
					</div>
					</div>
    			</div>
			</div>

			<div class="SINGLE_DEVICE box">
			<label class="col-sm-2 control-label">&nbsp;</label>
				<div class=" col-sm-10">
					<div class="panel panel-success">
            			<div class="panel-heading"><h3 class="panel-title">Single Device</h3></div>
            			<div class="panel-body">
						<div class="form-group">
        				<label class="col-sm-2 control-label">Username </label>
        					<div class="col-sm-10">
							<select class="form-control" name="packageusernya" placeholder="Select a person...">
							<option value="">Select a person...</option>';
							while ($metDeviceUser_ = mysql_fetch_array($metDeviceUser)) {
							echo '<option value="'.$metDeviceUser_['UserID'].'">'.$metDeviceUser_['username'].'</option>';
							}
						echo '</select>
							</div>
						</div>
						</div>
					</div>
    			</div>
			</div>

		<div class="panel-footer text-center"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
		</form>
	</div>
	</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

case "dd":
	;
	break;

	case "edtpdfinvoice":
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Website Edit Invoice PDF</h2></div>
      <div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=utilities">'.BTN_BACK.'</a></div></div>
      </div>';
$metUtilities = mysql_fetch_array($database->doQuery('SELECT * FROM ajkutilities WHERE id="'.$thisEncrypter->decode($_REQUEST['idhead']).'"'));
if ($_REQUEST['met']=="saveme") {
	if ($_FILES['fileImage']['name']) {
		$nama_file =  strtolower('PDVINVOICE_'.$DatePolis1.'_'.$_FILES['fileImage']['name']);
		$sourceFILE = $_FILES['fileImage']['tmp_name'];
		$direktori = '../'.$PathPhoto.'/'.$nama_file;
		move_uploaded_file($sourceFILE,$direktori);
		$metUodateLogo = ' logo="'.$nama_file.'",';
	}else{	}
	$metHeadInv = $database->doQuery('UPDATE ajkutilities SET '.$metUodateLogo.'
												  			  logoposisix="'.$_REQUEST['logoposisix'].'",
												  			  logoposisiy="'.$_REQUEST['logoposisiy'].'",
												  			  nama1="'.$_REQUEST['namaheader1'].'",
												  			  nama2="'.$_REQUEST['namaheader2'].'",
												  			  status="'.$_REQUEST['status'].'"
									WHERE id="'.$metUtilities['id'].'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=utilities"><div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Success!</strong> Edit setup header invoice for data pdf.</div>';
}
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">Form Edit Header Invoice</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="cobroker" class="form-control" required>
			            		<option value="">Select Broker</option>';
$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
while ($metBroker_ = mysql_fetch_array($metBroker)) {
echo '<option value="'.$metBroker_['id'].'"'._selected($metUtilities['idbroker'], $metUtilities['idbroker']).'>'.$metBroker_['name'].'</option>';
}
echo '</select>
				</div>
			</div>

			<div class="form-group">
			<label class="control-label col-sm-2">Name <span class="text-danger">*</span></label>
		    	<div class="col-sm-10">
		        <div class="row mb5"><div class="col-sm-12"><input name="namaheader1" value="'.$metUtilities['nama1'].'" type="text" class="form-control" placeholder="Street Address 1" required></div></div>
				<div class="row mb5"><div class="col-sm-12"><input name="namaheader2" value="'.$metUtilities['nama2'].'" type="text" class="form-control" placeholder="Street Address 2"></div></div>
		        </div>
		    </div>

			<div class="form-group">
			<label class="col-sm-2 control-label">Logo</label>
		    	<div class="col-sm-2"><img src="../'.$PathPhoto.'/'.$metUtilities['logo'].'" width="80">
				<input type="file" name="fileImage" accept="image/*"></div>
		        <div class="col-xs-4 pr5"><input type="text" name="logoposisix" value="'.$metUtilities['logoposisix'].'" placeholder="X : " required></div>
		        <div class="col-xs-4 pc5"><input type="text" name="logoposisiy" value="'.$metUtilities['logoposisiy'].'" placeholder="Y : " required></div>
			</div>

			<div class="form-group">
            <label class="col-sm-2 control-label">Status</label>
            	<div class="col-sm-10">
                    <span class="radio custom-radio custom-radio-primary">
						<input type="radio"'.pilih($metUtilities['status'], "Active").' name="status" id="customradio18" value="Active" required><label for="customradio18"> &nbsp; Active</label>
						<input type="radio"'.pilih($metUtilities['status'], "NonActive").' name="status" id="customradioga" value="NonActive" required><label for="customradioga" "> &nbsp; Non Active</label>
                    </span>
				</div>
			</div>
			</div>
		<div class="panel-footer text-center"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
		</form>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	case "pdfinvoice":
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Website Invoice PDF</h2></div>
      <div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=utilities">'.BTN_BACK.'</a></div></div>
      </div>';
if ($_REQUEST['met']=="saveme") {
	$nama_file =  strtolower('PDVINVOICE_'.$DatePolis1.'_'.$_FILES['fileImage']['name']);
	$sourceFILE = $_FILES['fileImage']['tmp_name'];
	$direktori = '../'.$PathPhoto.'/'.$nama_file;
	move_uploaded_file($sourceFILE,$direktori);

	$metHeadInv = $database->doQuery('INSERT INTO ajkutilities SET idbroker="'.$_REQUEST['cobroker'].'",
												  apl="Website",
												  posisi="HEADER",
												  type="PDF",
												  logo="'.$nama_file.'",
												  logoposisix="'.$_REQUEST['logoposisix'].'",
												  logoposisiy="'.$_REQUEST['logoposisiy'].'",
												  nama1="'.$_REQUEST['namaheader1'].'",
												  nama2="'.$_REQUEST['namaheader2'].'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=utilities"><div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Success!</strong> Setup header invoice for data pdf.</div>';
}
echo '<div class="row">
			<div class="col-md-12">
			'.$metnotif.'
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Form Header Invoice</h3></div>
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
						<div class="col-sm-10">
						<select name="cobroker" class="form-control" required>
				            		<option value="">Select Broker</option>';
		$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
		while ($metBroker_ = mysql_fetch_array($metBroker)) {
			echo '<option value="'.$metBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
		}
		echo '</select>
					    </div>
				    </div>

					<div class="form-group">
			            <label class="control-label col-sm-2">Name <span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
		                <div class="row mb5"><div class="col-sm-12"><input name="namaheader1" value="'.$_REQUEST['namaheader1'].'" type="text" class="form-control" placeholder="Street Address 1" required></div></div>
						<div class="row mb5"><div class="col-sm-12"><input name="namaheader2" value="'.$_REQUEST['namaheader2'].'" type="text" class="form-control" placeholder="Street Address 2"></div></div>
		                </div>
		            </div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Logo <span class="text-danger">*</span></label>
		                <div class="col-sm-2"><input type="file" name="fileImage" accept="image/*" required></div>
		                <div class="col-xs-4 pr5"><input type="text" name="logoposisix" value="'.$_REQUEST['logoposisix'].'" placeholder="X : " required></div>
		                <div class="col-xs-4 pc5"><input type="text" name="logoposisiy" value="'.$_REQUEST['logoposisiy'].'" placeholder="Y : " required></div>
					</div>

				</div>
			<div class="panel-footer text-center"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Website </h2></div>
      	<div class="page-header-section"><div class="toolbar"></div></div>
      </div>';
$metCekRInvoice = mysql_fetch_array($database->doQuery('SELECT * FROM ajkutilities WHERE apl="Website" AND posisi="HEADER" AND type="PDF" '.$q___.''));
if ($metCekRInvoice['id']) {
	$erheaderinvoice = '<a href="ajk.php?re=utilities&er=edtpdfinvoice&idhead='.$thisEncrypter->encode($metCekRInvoice['id']).'"><button type="button" class="btn btn-success">Edit</button></a>';
	$metKontentInfo = '
    	<div class="panel-body">
        	<div class="col-sm-2 control-label">Status</div>
        	<div class="col-sm-10"><input type="text" class="form-control" value="'.$metCekRInvoice['status'].'" disabled></div>
        </div>
		<div class="panel-body">
        	<div class="col-sm-2 control-label">Header Name</div>
        	<div class="col-sm-5"><input type="text" class="form-control" value="'.$metCekRInvoice['nama1'].'" disabled></div>
        	<div class="col-sm-5"><input type="text" class="form-control" value="'.$metCekRInvoice['nama2'].'" disabled></div>
        </div>
    	<div class="panel-body">
        	<div class="col-sm-2 control-label">Logo</div>
        	<div class="col-sm-10"><img src="../'.$PathPhoto.'/'.$metCekRInvoice['logo'].'" width="200"></div>
        </div>
 		<div class="panel-body">
        	<div class="col-sm-2 control-label">Position</div>
        	<div class="col-sm-5"><input type="text" class="form-control" value="x: '.$metCekRInvoice['logoposisix'].'" disabled></div>
        	<div class="col-sm-5"><input type="text" class="form-control" value="y: '.$metCekRInvoice['logoposisiy'].'" disabled></div>
        </div>';
}else{
	$erheaderinvoice = '<a href="ajk.php?re=utilities&er=pdfinvoice"><button type="button" class="btn btn-primary">New</button></a>';
	$metKontentInfo = '
		<div class="panel-body">
		<div class="alert alert-dismissable alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<strong> There is no data on to setup</strong>
        </div></div>';
}
echo '<div class="col-lg-12">
      	<div class="tab-content">
        	<div class="tab-pane active" id="profile">
            <form class="panel form-horizontal form-bordered" name="form-profile">
            	<div class="panel-body pt0 pb0">
                	<div class="form-group header bgcolor-default">
                    	<div class="col-md-11">
                        <h4 class="semibold text-primary mt0 mb5">Logo Header Invoice (PDF)</h4>
                        <p class="text-default nm">This information appears on your report invoice on file .pdf.</p>
                        </div>
                        <div class="col-md-1">
                        <div class="text-right">'.$erheaderinvoice.'</div>
						</div>
                    </div>
                    '.$metKontentInfo.'
                    <!--<div class="form-group header bgcolor-default">
	                	<div class="col-md-12"><h4 class="semibold text-primary nm">Description</h4></div>
                    </div>
                    <div class="form-group">
                    	<div class="col-sm-12">
                        <textarea class="form-control" rows="3" placeholder="Describe about aplication mobile"></textarea>
                        <p class="help-block">Description for aplication mobile</p>
                        </div>
                    </div>-->
                </div>
                <div class="panel-footer">
                <!--<button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Save change</button>-->
            </div>
        </form>
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