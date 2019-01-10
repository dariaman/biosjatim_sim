<?php
include "../param.php";
$typeuploadnya = AES::decrypt128CBC($_REQUEST['xq'],ENCRYPTION_KEY);
$message = $_REQUEST['pesan'];
if ($typeuploadnya=="uploadnonspk") {
	$tipeupload = 'Member ';
	$setlokasi = '<form action="uploadmember.php" id="uploadmember" class="form-horizontal" method="post" enctype="multipart/form-data">';
	$typeupload = 'Excel';
	$valupload = 'accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"';
}elseif($typeuploadnya=="uploadspk") {
	$tipeupload = 'Member SPK';
	$setlokasi = '<form action="uploadmemberspk.php" id="uploadmemberspk" class="form-horizontal" method="post" enctype="multipart/form-data">';
	$typeupload = 'Excel';
}elseif($typeuploadnya=="uploadcsf") {
	$tipeupload = 'Certificate Member';
	$setlokasi = '<form action="uploadcsf.php" id="uploadcsf" class="form-horizontal" method="post" enctype="multipart/form-data">';
	$typeupload = 'txt';
	$valupload = 'accept="text/plain"';
}else{
	$tipeupload = '';
	$setlokasi = '<form action="#" id="#" class="form-horizontal" method="post" enctype="multipart/form-data">';
}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user,$namauser,$photo,$logo);
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
		_header($user,$namauser,$photo,$logo,$logoklient);
		_sidebar($user,$namauser,'','');
		?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
				<h4 class="m-t-0">Upload Data <?php echo $tipeupload;	?></h4>
				<?php if(isset($message)){echo '<h1 class="text-center">'.$message.'</h1>';} ?>
				<div class="section-container section-with-top-border">
					<?php echo $setlokasi;	?>
		      	<input type="hidden" name="xq" value="<?php echo $typeuploadnya;	?>"/>
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Partner </label>
							<div class="col-sm-6">
								<label class="control-label "><?php echo $namaklient ?> </label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-3">Silakan Pilih File <?php echo $typeupload ?> <span class="text-danger">*</span></label>
							<div class="col-sm-6">
								<input type="file" name="fileupload" id="fileupload" class="form-control" <?php echo $valupload ?>  />
							</div>
						</div>

						<div class="form-group m-b-0">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success width-xs">Import</button>
							</div>
						</div>

						<div id="progressbox" style="display:none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									<div id="statustxt" class="info"></div>
								</div>
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
		    Demo.init();
			<?php
			if($typeuploadnya == 'uploadnonspk'){
			?>
			$(".active").removeClass("active");
			document.getElementById("has_input").classList.add("active");
			document.getElementById("idhas_input").classList.add("active");
			document.getElementById("idsub_databaruajk").classList.add("active");
			<?php
			}else{
			?>
			$(".active").removeClass("active");
			document.getElementById("has_input").classList.add("active");
			document.getElementById("idhas_input").classList.add("active");
			document.getElementById("idsub_databaru_spk").classList.add("active");
			<?php
			}
			?>
			$('#uploadmember').bootstrapValidator({
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
					namaproduk: {
						validators: {
							notEmpty: {
								message: 'Silahkan pilih nama produk'
							}
						}
					},
					fileupload: {
						validators: {
							notEmpty: {
								message: 'Silahkan pilih nama files Excel'
							}
						}
					}
				}
			});
			$('#uploadmemberspk').bootstrapValidator({
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
					namaproduk: {
						validators: {
							notEmpty: {
								message: 'Silahkan pilih nama produk'
							}
						}
					},
					fileupload: {
						validators: {
							notEmpty: {
								message: 'Silahkan pilih nama files Excel'
							}
						}
					}
				}
			});
		});
	</script>
</body>

</html>
