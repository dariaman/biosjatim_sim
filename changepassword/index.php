<?php
include "../param.php";
session_start();
// include "../koneksi.php";
$idraw = isset($_GET['param1']) ? $_GET['param1'] : '';
$id = isset($_GET['param1']) ? base64_decode($_GET['param1']) : '';
$username = isset($_GET['param2']) ? $_GET['param2'] : '';

$msg = '';
$rs = true;
if (isset($_POST['change_password'])) {
    if ($_POST['change_password']=='change_password') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password!=$confirm_password) {
            $rs = false;
            $msg = 'Confirm password harus sama dengan password!';
        }

        if ($rs) {
            $q2="UPDATE useraccess SET mamet = '".$new_password."', passw = '".md5($new_password)."' WHERE id = ".$id;
            $result2=mysql_query($q2);

            if ($result2) {
                $rs = true;
                $msg = 'Ganti password berhasil!';
                $_POST['change_password'] = '';
            } else {
                $rs = false;
                $msg = 'Ganti password gagal!';
            }
        }
    }
}

?>

<!DOCTYPE html>
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
				<h4 class="m-t-0">Change Password </h4>
								<div class="section-container section-with-top-border">
					<form action="<?= $_SERVER['PHP_SELF'].'?param1='.$idraw.'&param2='.$username ?>" id="change-pass-form" class="form-horizontal fv-form fv-form-bootstrap" method="post">
						<button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
						<?php if ($msg!='') {
                ?>
							<div class="alert alert-<?= $rs==false ? 'danger' : 'success' ?>" role="alert"><?= $msg ?></div>
						<?php
            } ?>
						<div class="form-group">
							<label class="control-label col-sm-3">Username </label>
							<div class="col-sm-6">
								<label class="control-label "><?= $username ?> </label>
							</div>
						</div>

						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">New Password <span class="text-danger">*</span></label>
							<div class="col-sm-6">
								<input type="password" name="new_password" id="new_password" class="form-control" required>
								<small class="help-block" data-bv-validator="notEmpty" style="display: none;"></small>
							</div>
						</div>

						<div class="form-group has-feedback">
							<label class="control-label col-sm-3">Confirm Password <span class="text-danger">*</span></label>
							<div class="col-sm-6">
								<input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
								<small class="help-block" data-bv-validator="notEmpty" style="display: none;"></small>
							</div>
						</div>
						<input type="hidden" name="change_password" value="change_password">

						<div class="form-group m-b-0">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success width-xs">Submit</button>
							</div>
						</div>

						<div id="progressbox" style="display:none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									<div id="statustxt" class="info"></div>
								</div>
							</div>
						</div>
					</form>

	            </div>
	            <!-- end section-container -->
	        </div>

				<!-- end row -->
	      <!-- begin #footer -->
	      <?php
          _footer();
          ?>
	      <!-- end #footer -->
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
		});
		</script>
	</body>

</html>
