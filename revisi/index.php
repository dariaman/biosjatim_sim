<?php
// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
include "../param.php";

$rs = isset($_GET['rs']) ? $_GET['rs'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<?php
    _head($user, $namauser, $photo, $logo);
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->


	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
        _header($user, $namauser, $photo, $logo, $logoklient);
        _sidebar($user, $namauser, '', '');
        ?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30"><h4 class="m-t-0">FAQ</h4>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
			    <form action="doemail.php" id="sendemail" class="form-horizontal" method="post" enctype="multipart/form-data">
						<?php if ($rs==0 && $msg!='') {
            ?>
							<div class="alert alert-danger" role="alert"><?= $msg ?></div>
						<?php
        } elseif ($rs==1 && $msg!='') {
            ?>
							<div class="alert alert-success" role="alert"><?= $msg ?></div>
						<?php
        } ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-10">
                	<textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Attachment 1</label>
                <div class="col-sm-10">
                	<input name="attachment1" id="attachment1" class="form-control" type="file">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Email</label>
                <div class="col-sm-10">
                    <input name="email" id="email" class="form-control"  type="email">
                </div>
            </div>
            <div class="form-group m-b-0">
              <div class="col-sm-12 text-center">
                <button type="submit" id="load" class="btn btn-success width-xs" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading..">Submit</button>
              </div>
            </div>
          </form>
	      </div>
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

			$(".active").removeClass("active");
			document.getElementById("has_revisi").classList.add("active");

			$('#sendemail').submit(function(){

			})
		});
		function f_ok(){
			// var email = $("#email").val();
			// var keterangan = $("#keterangan").val();
			// var cabang = <?php //$namacabang;?>
			// msgbox(cabang);
			//msgbox("Data Berhasil Dikirim, Tunggu balasan lewat email.");
			//window.location = "../dashboard";
		}
	</script>
</body>

</html>
