<?php
include "../param.php";
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
				<h1 class="page-header">Change Password</h1>
				<!-- begin row -->

				<div class="row">
					<div class="col-lg-6">
						<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
              <h4 class="text-white m-t-0 m-b-10">
                  <i class="fa fa-snowflake-o text-success-light"></i> Kalkulator Premi
              </h4>
              <form id="frm-calc" width="100%" action="javascript:hitung();" class="form-horizontal">
								<div class="form-group">
					        <label class="control-label col-md-2">Karpot</label>
					        <div class="col-md-10">
					          <select class="form-control" id="karpot">
					          	<option value="">- Pilih -</option>
					          	<option value="4">Umum</option>
					          	<option value="5">Pensiunan</option>Fpre
					          	<option value="3.75">Komisaris,Direksi, dan pegawai Bank Jatim</option>
					          </select>
					        </div>
						    </div>
								<div class="form-group">
					        <label class="control-label col-md-2">Plafond</label>
					        <div class="col-md-10">
					          <input id="plafond" name="plafond" class="form-control" type="text" value="" placeholder="Plafond">
					        </div>
						    </div>
								<div class="form-group">
					        <label class="control-label col-md-2">Tenor (Tahun) </label>
					        <div class="col-md-10">
					          <input id="tenor" name="tenor" class="form-control" type="text" placeholder="Tenor">
					        </div>
						    </div>

						    <hr>
								<div class="form-group">
					        <label class="control-label col-md-2">Asumsi Premi</label>
					        <div class="col-md-10">
					          <input id="premi" name="premi" class="form-control" type="text" disabled="">
					        </div>
						    </div>
						    <div class="text-center">
						    	<button type="submit" class="btn btn-primary">Hitung</button>
						  	</div>
							</form>
	          </div>
					</div>

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

		</script>
	</body>

</html>
