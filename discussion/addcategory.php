<?php
include "../param.php";
session_start();
$user = $_SESSION["User"];
if ($user=="") {
    header("location:../login?pesan=Silahkan login kembali");
}
$queryuser = mysql_query("select * from useraccess where username = '".$user."'");
$rowuser = mysql_fetch_array($queryuser);
$username = $rowuser['username'];
$userid = $rowuser['id'];
$userphoto = $rowuser['photothumb'];
$dept = $rowuser['branch'];
$userlevel = $rowuser['level'];
$usergroup = $rowuser['supervisor'];

// if ($userlevel<50) {
//     header("location:../discussion?pesan=Anda tidak mempunyai akses halaman ini");
// }

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
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<?php
    _header($user, $namauser, $photo, $logo, $logoklient);
    _sidebar($user, $namauser, '', '');
        ?>

		<!-- begin #content -->
		<div id="content" class="content" style="margin-left: 0 !important">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right" style="z-index: 2000;">
				<li><a href="../dashboard">Home</a></li>
				<li><a href="../discussion">View Discussion</a></li>
				<li><a href="../discussion/addcategory.php">Add Categories</a></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Forum </h1>
			<!-- end page-header -->

			<!-- begin row -->
            <div class="row">
                    <!-- end pagination -->
					<!--	<div id="topicspost"></div> -->
                    <!-- begin comment-section -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">Add Categories</h4>
                        </div>
                        <div class="panel-body">
                            <form action="doaddcategory.php" id="myform" name="myform" method="POST" enctype="multipart/form-data">
                            	<div class="form-group m-b-15">
			                    <label class="control-label">Categories Name </label>
									<input id="namakategori" name="namakategori" class="form-control" type="text">
								</select>
								</div>
                  <label class="control-label"></label>Description</label>
                    <div class="form-group m-b-15">
									<textarea class="form-control" id="description" name="description" placeholder="Enter text ..." rows="3"></textarea>
								</div>

								<div class="form-group m-b-15">
                  <label class="control-label">Type</label>
									<select  id="typecat" name="typecat" class="form-control">
  									<option value="">- Select Type -</option>
  									<option value="Official Management District">Official Management District</option>
  									<option value="General Discussion">General Discussion</option>
  									<option value="Mobile Apps Discussion">Mobile Apps Discussion</option>
									</select>
								</div>

								<!-- <div class="form-group m-b-15">
				                    <label class="control-label">User Akses </label>
									<select  id="userakses" name="userakses" class="form-control">
									<option value="">-Pilih Akses-</option>
									<option value="All">All</option>
									<option value="APA">APA</option>
									<option value="ATS">ATS</option>
									<option value="AMK">AMK</option>
									<option value="APR">APR</option>
									<option value="HRD">HRD</option>
									<option value="Marketing">Marketing</option>
									</select>
								</div> -->

                <input type="hidden" name="userakses" value="ALL">

								<label class="control-label">Logo</label>
								<div class="form-group m-b-15">
                	<input id="files1" name="files1" class="form-control" type="file">
                </div>

              	<div class="row">
                  <div class="col-md-6">
                  	<div class="text-left m-t-10">
                      	<button type="submit" class="btn btn-primary">Submit <i class="fa fa-paper-plane"></i></button>
                  	</div>
                   </div>
  									<div class="col-md-6">
                    	<div class="text-right m-t-10">
                      	<a href="<?= $path.'/discussion' ?>" class="btn btn-success"><i class="fa fa-list-alt"></i> List Categories </a>
                      </div>
  									</div>
                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end comment-section -->
                    </div>
                    <!-- end panel-forum -->
                </div>
                <!-- end col-3 -->
            </div>
            <!-- end row -->
		</div>
		<!-- end #content -->


		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
  <?php _javascript(); ?>
	<script>
$(document).ready(function() {
	App.init();
	//$(".has_dashboard").removeClass("active");
	// var hasdsb = document.getElementById("has_dashboard");
	// hasdsb.classList.remove("active");
	// var dsb = document.getElementById("dashboard");
	// dsb.classList.remove("active");

	$(".active").removeClass("active");
	document.getElementById("has_forum").classList.add("active");
	// document.getElementById("sub_viewdiscussion").classList.add("active");
	//$('#navsubul_manage').css('display','block');
		$('#myform').bootstrapValidator({
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
						namakategori: {
			                validators: {
				 				notEmpty: {
				                    message: 'Nama Katagori harus diisi'
				                }
			                }
			            },
						description: {
			                validators: {
			                    notEmpty: {
				                    message: 'Deskripsi harus diisi'
				                }
			                }
			            },
						typecat: {
			                validators: {
			                    notEmpty: {
				                    message: 'Tipe katagori harus dipilih'
				                }
			                }
			            },


					}
				});

});
	</script>
</body>

</html>
