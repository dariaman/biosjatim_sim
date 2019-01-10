<?php
include "../param.php";
session_start();
$user = $_SESSION["User"];
$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
if ($user=="") {
    header("location:../login?redir=".$url."&pesan=Silahkan login kembali");
}
$queryuser = mysql_query("select * from useraccess where username = '".$user."'");
$rowuser = mysql_fetch_array($queryuser);
$username = $rowuser['username'];
$userid = $rowuser['id'];
$userphoto = $rowuser['photothumb'];
$dept = $rowuser['branch'];

if (isset($_REQUEST['idcat']) and $_REQUEST['idcat'] !="" and $_REQUEST['idcat'] !="0") {
    $category = $_REQUEST['idcat'];
} else {
    header("location:../discussion");
}
$querycat = mysql_query("SELECT * FROM categories WHERE cat_id = '".$category."'");
$rowcat = mysql_fetch_array($querycat);
$namacat = $rowcat['cat_name'];
if (isset($_REQUEST['top'])) {
    $topicsid = $_REQUEST['top'];
} else {
    header("location:../topics?idcat=$category");
}
if (isset($_REQUEST['postid'])) {
    $postid = $_REQUEST['postid'];
}
if (isset($_REQUEST['start'])) {
    $startpage = $_REQUEST['start'];
}
$querypost = mysql_query("SELECT * FROM posts WHERE post_id = '".$postid."'");
$rowpost = mysql_fetch_array($querypost);
$postcontent = $rowpost['post_content'];
$querytopics = mysql_query("SELECT * FROM topics WHERE topic_id = '".$topicsid."'");
$rowtopics = mysql_fetch_array($querytopics);
$namatopic = $rowtopics['topic_subject'];


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
				<li><a href="../topics/viewpost.php?idcat=<?php echo $category ?>&top=<?php echo $topicsid ?>"><?php echo $namatopic ?></a></li>
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
                            <form action="doedit.php?idcat=<?php echo $category ?>&idtop=<?php echo $topicsid ?>&start=<?php echo $startpage?>&postid=<?php echo $postid ?>" name="summernote" method="POST" enctype="multipart/form-data">
			                    <textarea class="textarea form-control" id="summernote" name="postcomment" placeholder="Enter text ..." rows="8" data-height="300"><?php echo $postcontent ?></textarea>
			                    <label class="control-label">File 1 </label>
			                    <input id="files1" name="files1" class="form-control" type="file">
			                    <label class="control-label">File 2 </label>
			                    <input id="files2" name="files2" class="form-control" type="file">
                                <div class="m-t-10">
                                    <button type="submit" class="btn btn-primary">Post Comment <i class="fa fa-paper-plane"></i></button>
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
	PageDemo.init();
	//$(".has_dashboard").removeClass("active");
	// var hasdsb = document.getElementById("has_dashboard");
	// hasdsb.classList.remove("active");
	// var dsb = document.getElementById("dashboard");
	// dsb.classList.remove("active");

	$(".active").removeClass("active");
	document.getElementById("has_forum").classList.add("active");
	// document.getElementById("sub_viewdiscussion").classList.add("active");
	//$('#navsubul_manage').css('display','block');

  $('#summernote').summernote({
    height: 150,
  });

});
	</script>
</body>

</html>
