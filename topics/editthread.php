<?php
include "../param.php";
ini_set('session.gc_maxlifetime', 84600);
$expireTime = 60*60*24*100; // 100 days
session_set_cookie_params($expireTime);
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

if (isset($_REQUEST['idcat']) and $_REQUEST['idcat'] !="" and $_REQUEST['idcat'] !="0") {
    $category = $_REQUEST['idcat'];
} else {
    header("location:../discussion");
}
if (isset($_REQUEST['top']) and $_REQUEST['top'] !="" and $_REQUEST['top'] !="0") {
    $topicid = $_REQUEST['top'];
} else {
    header("location:../discussion");
}
$querycat = mysql_query("SELECT * FROM categories WHERE cat_id = '".$category."'");
$rowcat = mysql_fetch_array($querycat);
$namacat = $rowcat['cat_name'];

$querytop = mysql_query("SELECT * FROM topics WHERE topic_id = '".$topicid."'");
$rowtop = mysql_fetch_array($querytop);
$namatopic = $rowtop['topic_subject'];
$topicmode = $rowtop['topic_mode'];

$querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$topicid."' AND post_num = '1' ");
$rowpost = mysql_fetch_array($querypost);
$postcontent = $rowpost['post_content'];

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
				<li><a href="../topics?idcat=<?php echo $category ?>"><?php echo $namacat ?></a></li>
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
                            <h4 class="panel-title">Comment</h4>
                        </div>
                        <div class="panel-body">
                            <form action="doeditthead.php?idcat=<?php echo $category ?>&top=<?php echo $topicid ?>" id="myform" name="myform" method="POST" enctype="multipart/form-data">
                            	<div class="form-group m-b-15">
				                    <label class="control-label">Topics Name </label>
									<input id="namatopics" name="namatopics" class="form-control" type="text" value="<?php echo $namatopic ?>">
									<input id="usertemp" name="usertemp" class="form-control" type="hidden" value="<?php echo $user ?>">
								</div>
									<label class="control-label">Post </label>
				                    <textarea class="textarea form-control" id="summernote" name="postcomment" placeholder="Enter text ..." rows="15" data-height="300"><?php echo $postcontent ?></textarea>
                            <br />
                          <?php
                                if ($topicmode == 'Sticky') {
                                    $ceksticky = 'checked';
                                } else {
                                    $ceksticky = '';
                                }
                                if ($userlevel >= 30) {
                                    echo '<div class="form-group m-b-15">
			                    	<label class="control-label">Type Topics :</label><br/>
			                    	<input type="checkbox" id="typetopics" name="typetopics" value="Sticky" data-render="switchery" data-theme="default" '.$ceksticky.'/>
			                    	<label class="control-label">Sticky </label>
			                    </div>';
                                }

                                ?>
			                    <label class="control-label">File 1 </label>
			                    <input id="files1" name="files1" class="form-control" type="file">
			                    <label class="control-label">File 2 </label>
			                    <input id="files2" name="files2" class="form-control" type="file">
                                <div class="m-t-10">
                                    <button type="submit" class="btn btn-primary">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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
  <?php
        _javascript();
        ?>
	<script>
$(document).ready(function() {
	App.init();
	// PageDemo.init();
	$(".active").removeClass("active");
	document.getElementById("has_forum").classList.add("active");

  $('#summernote').summernote({
    height: 150,
  });

	// FormSliderSwitcher.init();


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
			namatopics: {
				validators: {
					notEmpty: {
						message: 'Nama Topics harus diisi'
					}
				}
			},
			// postcomment: {
			// 	validators: {
			// 		notEmpty: {
			// 			message: 'Post comment harus diisi'
			// 		}
			// 	}
			// }
		}
	});

});
	</script>
</body>

</html>
