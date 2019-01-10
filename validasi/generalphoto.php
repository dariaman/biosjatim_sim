<?php
include "../param.php";
if(isset($_REQUEST['idk'])){
	$idktp = $_REQUEST['idk'];
}else{
	$idktp = '';
}
if(isset($_REQUEST['inpt'])){
	$inputtime = $_REQUEST['inpt'];
}else{
	$inputtime = '';
}
//$idktp = AES::decrypt128CBC($idktp, ENCRYPTION_KEY);

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

		$querytemppeserta = mysql_query("SELECT * FROM ajkpeserta_temp WHERE nomorktp = '".$idktp."'");
		$rowtemppeserta = mysql_fetch_array($querytemppeserta);
		$namatertanggung = $rowtemppeserta['nama'];
		$nomorktp = $rowtemppeserta['nomorktp'];
		?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
				    <h4 class="m-t-0">Upload Foto Member</h4>
				    <form action="doupload.php?idp=<?php echo $inputtime ?>&idk=<?php echo $idktp ?>" id="uploadfoto" class="form-horizontal" method="post" enctype="multipart/form-data">
	                    <div class="form-group">
	                        <label class="control-label col-sm-3">Cabang </label>
	                        <div class="col-sm-6">
	                        	<label class="control-label "><?php echo $namacabang ?> </label>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label col-sm-3">Nama Tertanggung </label>
	                        <div class="col-sm-6">
	                        	<label class="control-label "><?php echo $namatertanggung ?> </label>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label col-sm-3">Nomor KTP </label>
	                        <div class="col-sm-6">
	                        	<label class="control-label "><?php echo $nomorktp ?> </label>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label col-sm-3">Upload Foto <span class="text-danger">*</span></label>
	                        <div class="col-sm-4">
	                            <input id="file" name="files[]" multiple="multiple" accept="image/*" class="form-control" type="file">
	                        </div>
	                    </div>
	                    <div class="form-group m-b-0">
	                        <label class="control-label col-sm-3"></label>
	                        <div class="col-sm-6">
	                            <button type="submit" class="btn btn-success width-xs">Upload</button>
	                        </div>
	                    </div>

	                    <div id="progressbox" style="display:none;">
					<div class="progress">
						<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar"
						aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
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
		    //Demo.init();
			$(".active").removeClass("active");
			//$(".open").removeClass("open");
			document.getElementById("has_input").classList.add("active");
			//$('#navsubul_manage').css('display','block');
			$('#inputmember').bootstrapValidator({
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
					plafon: {
						validators: {
							notEmpty: {
								message: 'Silahkan input plafon'
							}
						}
					}
				}
			});

		});
	</script>
</body>

</html>
