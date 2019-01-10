<?php

include "../koneksi.php";

if (isset($_REQUEST['pesan'])) {
    $pesan = AES::decrypt128CBC($_REQUEST['pesan'], ENCRYPTION_KEY);
} else {
    $pesan = '';
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title>A.J.K</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link rel="icon" type="image/png" href="../myFiles/_photo/logo_cli.png">

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,300,700" rel="stylesheet" id="fontFamilySrc" />
	<link href="../assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="../assets/css/animate.min.css" rel="stylesheet" />
	<link href="../assets/css/style.min.css" rel="stylesheet" />
	<link href="../assets/plugins/bootstrap-validation/css/bootstrapValidator.min.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="../assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!--[if lt IE 9]>
	    <script src="../assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
</head>
<body class="pace-top">
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container">
	    <!-- begin login -->
		<div class="login">
		    <!-- begin login-brand -->
            <div class="login-brand bg-success text-white">
                Login
            </div>
		    <!-- end login-brand -->
		    <!-- begin login-content -->
            <div class="login-content">
                <form action="dologin.php" method="POST" name="login_form" id="login_form" class="form-input-flat">
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Username" />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" />
                    </div>
                    <div class="row m-b-20">
                    	<div class="col-md-12"><span class='text-danger'><center><?= isset($_GET['pesan']) ? $_GET['pesan'] : '' ?></center></span> </div></div>
                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-lime btn-lg bg-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
		    <!-- end login-content -->
		</div>
		<!-- end login -->
	</div>
	<!-- end page container -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="../assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="../assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="../assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="../assets/crossbrowserjs/html5shiv.js"></script>
		<script src="../assets/crossbrowserjs/respond.min.js"></script>
	<![endif]-->
	<script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="../assets/js/demo.min.js"></script>
    <script src="../assets/js/apps.min.js"></script>
    <script src="../assets/plugins/bootstrap-validation/js/bootstrapValidator.min.js"></script>
    <script src="../assets/plugins/bootstrap-validation/js/formValidation.min.js"></script>
    <script src="../assets/plugins/bootstrap-validation/js/tooltipbootstrap.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>

		$(document).ready(function() {
		    App.init();
		    Demo.initThemePanel();

			$('#login_form').bootstrapValidator({
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
					username: {
						validators: {
							notEmpty: {
								message: 'Username harus diisi'
							}
						}
					},
					password: {
						validators: {
							notEmpty: {
								message: 'Password harus diisi'
							}
						}
					}
				}
			});
		});
	</script>
</body>

</html>
