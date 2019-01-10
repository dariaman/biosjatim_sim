<?php
  include "../param.php";
  include_once('../includes/functions.php');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
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
				<!-- begin section-container -->
        <h4 class="m-t-0">RESTURNO</h4>
				<div class="section-container section-with-top-border">
			    
			    <form action="../api/apijatim.php" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
            <input type="hidden" name="han" value="newresturno">
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Cabang </label>
							<div class="col-sm-6">
								<select class="form-control" name="cabang" required>
                  <option value="">- Pilih -</option>
									<?php                      										
                      $querycabang = mysql_query("SELECT * FROM ajkcabang WHERE del IS NULL ");
                      //$querycabang = mysql_query("SELECT * FROM ajkcabang ".$cabangverifikasi);
                      while ($rowcab = mysql_fetch_array($querycabang)) {
                          $idcab = $rowcab['er'];
                          $namacab = $rowcab['name'];
                          echo '<option value="'.$idcab.'" '.$selected.'>'.$namacab.'</option>';
                      }
                  ?>
								</select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-3">Tgl Bayar <span class="text-danger">*</span></label>
              <div class="col-sm-3">
						<div class="input-group date">
							<input type="text" name="paiddate" id="paiddate" class="form-control tgl-jawa" placeholder="Tgl Bayar" required autocomplete="off"/>
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-3">Nilai Bayar <span class="text-danger">*</span></label>
              <div class="col-sm-6">

              	<input class="form-control" type="text" id="amount" name="amount" autocomplete="off" required/>
              </div>
            </div>
            
				<div class="form-group">
					<label class="control-label col-sm-3">Periode <span class="text-danger">*</span></label>
					<div class="col-sm-3">
						<div class="input-group date">
							<input type="text" name="startdate" id="startdate" class="form-control date-picker" placeholder="Start Date" required autocomplete="off"/>
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>

					<div class="col-sm-3">
						<div class="input-group date">
							<input type="text" name="enddate" id="enddate" class="form-control date-picker" placeholder="End Date" required autocomplete="off"/>
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
            <div class="form-group">
              <label class="control-label col-sm-3">Keterangan <span class="text-danger">*</span></label>
              <div class="col-sm-6">
              <textarea name="keterangan" id="keterangan" class="form-control" autocomplete="off" required></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-3">Attachment <span class="text-danger">*</span></label>
              <div class="col-sm-6">
              <input type="file" id="attachment" name="attachment" required/>
              </div>
            </div>
						<div class="form-group m-b-0">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-6">
								<input type="hidden" id="hidType">
								<button type="submit" class="btn btn-success width-xs">Submit</button>
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
		  $('#amount').mask('000,000,000,000,000' , {reverse: true});

      $('.date-picker').datepicker( {
        format: 'mm-yyyy',
        startView: "months", 
        minViewMode: "months",
        autoclose: true
      });

      $('.tgl-jawa').datepicker( {
        format: 'dd/mm/yyyy',
        todayHighlight: !0,
        autoclose: true
      });
    });
	</script>
</body>

</html>
