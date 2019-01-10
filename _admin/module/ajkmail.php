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
switch ($_REQUEST['mail']) {
	case "sendmail":
$subject = "Test Email AJK Broker";
$message = "do not reply this message";

$ajkmailto = $_REQUEST['emailme'];
$ajkmailnameto = "usermail";
$ls_subject = "[App Credit Life Insurance]";
$ls_countemail = 1;
$ajkmailfromname = "hansen";
$ajkmailfrom = "hansen@adonai.co.id";
$ajkmailccname = 'hansen';
$ajkmailccmail = "hansen@adonai.co.id";
$ajkmailcccount = 0;
kirimemail($ajkmailfromname,$ajkmailfrom,$ajkmailnameto, $ajkmailto,$ls_countemail, $ajkmailccname, $ajkmailccmail, $ajkmailcccount, $subject,$message);

echo '<div class="page-header-section"><h2 class="title semibold">Email</h2></div>
		<div class="page-header-section">
		</div>
	</div>
<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Test Mail</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<div class="col-sm-12">Send mail to : '.$_REQUEST['emailme'].'</div>
			</div>
		</div>
	</form>
</div>
</div>';
		;
		break;
	case "S":
		;
		break;
	default:
echo '<div class="page-header-section"><h2 class="title semibold">Email</h2></div>
		<div class="page-header-section">
		</div>
	</div>
<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Test Mail</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label">Email <span class="text-danger">*</span></label>
				<div class="col-sm-10"><input type="text" name="emailme" value="'.$_REQUEST['emailme'].'" class="form-control" placeholder="Email" required></div>
			</div>

		</div>
	<div class="panel-footer"><input type="hidden" name="mail" value="sendmail">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
		;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>