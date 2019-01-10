<?php
// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
include "../param.php";

// session_start();
$user = $_SESSION["User"];
$userLevel = $_SESSION["level"];
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
$userlevel = $rowuser['level'];
$usergroup = $rowuser['supervisor'];

if (isset($_REQUEST['idcat']) and $_REQUEST['idcat'] !="" and $_REQUEST['idcat'] !="0") {
    $category = $_REQUEST['idcat'];
} else {
    header("location:../discussion");
}
$querycat = mysql_query("SELECT * FROM categories WHERE cat_id = '".$category."'");
$rowcat = mysql_fetch_array($querycat);
$namacat = $rowcat['cat_name'];
$catgroup = $rowcat['cat_group'];
if (isset($_REQUEST['top'])) {
    $topicsid = $_REQUEST['top'];
} else {
    header("location:../topics?idcat=$category");
}

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
				<li class="active"><a href="../topics/viewpost.php?idcat=<?php echo $category ?>&top=<?php echo $topicsid ?>"><?php echo $namatopic ?></a></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Forum </h1>
			<!-- end page-header -->

			<!-- begin row -->
            <div class="row">
                <!-- begin col-9 -->
                <div class="col-md-12">

                    	<?php
                            include('data.php');
                        ?>
						<!-- begin pagination -->
                    <div class="text-right">
                    	<ul class="pagination">
                          <?php

                                $butonpaging = 0;
                                if (isset($_GET['start'])) {
                                    $pageactive = $_GET['start'];
                                    if ($pageactive >= $butonpaging) {
                                        $butonpaging = $pageactive + 8;
                                    } else {
                                        $butonpaging = 0;
                                        $classdisplay =  "";
                                    }
                                } else {
                                    $pageactive = 1;
                                    $butonpaging = $pageactive + 8;
                                }


                                if ($page_counter == 1) {
                                    echo "<li class='active'><a href=?idcat=$category&top=$topicsid&start=1>1</a></li>";
                                    for ($j=2; $j <= $paginations; $j++) {
                                        if ($j >= $pageactive and $j <= $butonpaging) {
                                            $classdisplay =  "";
                                        } else {
                                            $classdisplay =  "style='display: none'";
                                        }
                                        echo "<li $classdisplay><a href=?idcat=$category&top=$topicsid&start=$j>".$j."</a></li>";
                                    }
                                } else {
                                    echo "<li><a href=?idcat=$category&top=$topicsid&start=1><i class='fa fa-angle-left'></i><i class='fa fa-angle-left'></i></a></li>";
                                    echo "<li><a href=?idcat=$category&top=$topicsid&start=$previous><i class='fa fa-angle-left'></i></a></li>";

                                    for ($j=1; $j <= $paginations; $j++) {
                                        if ($j == $page_counter) {
                                            echo "<li class='active'><a href=?idcat=$category&top=$topicsid&start=$j>".$j."</a></li>";
                                        } else {
                                            if ($j >= $pageactive-4 and $j <= $butonpaging) {
                                                $classdisplay =  "";
                                            } else {
                                                $classdisplay =  "style='display: none'";
                                            }
                                            if ($j >= $pageactive+5) {
                                                $classdisplay =  "style='display: none'";
                                            }

                                            echo "<li $classdisplay><a href=?idcat=$category&top=$topicsid&start=$j>".$j."</a></li>";
                                        }
                                    }
                                    if ($j != $page_counter+1) {
                                        echo "<li><a href=?idcat=$category&top=$topicsid&start=$next><i class='fa fa-angle-right'></i></a></li>";
                                    }
                                }
                                if ($page_counter != $paginations) {
                                    echo "<li><a href=?idcat=$category&top=$topicsid&start=$paginations><i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i></a></li>";
                                }

                          ?>
                        </ul>
                    </div>
                    <!-- end pagination -->
					<!--	<div id="topicspost"></div> -->
                    <!-- begin comment-section -->
                    <?php
                    if ($userlevel >= 50) {
                        echo '<div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">Comment</h4>
                        </div>
                        <div class="panel-body">
                            <form action="dopost.php?idcat='.$category.'&idtop='.$topicsid.'" name="summernote" method="POST" enctype="multipart/form-data">
			                    <textarea class="textarea form-control" id="summernote" name="postcomment" placeholder="Enter text ..." rows="8" data-height="300"></textarea>
			                    <label class="control-label">File 1 </label>
			                    <input id="files1" name="files1" class="form-control" type="file">
			                    <label class="control-label">File 2 </label>
			                    <input id="files2" name="files2" class="form-control" type="file">
                                <div class="m-t-10">
                                    <button type="submit" class="btn btn-primary">Post Comment <i class="fa fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>';
                    } else {
                        $queryshare = mysql_query("SELECT * FROM sharedcat WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$category."'");
                        $valid =null;
                        while ($rowshare = mysql_fetch_array($queryshare)) {
                            $valid = '1';
                        }
                        if ($usergroup == $catgroup or $catgroup == 'ALL' or $valid == '1') {
                            echo '<div class="panel panel-inverse">
					                        <div class="panel-heading">
					                            <h4 class="panel-title">Comment</h4>
					                        </div>
					                        <div class="panel-body">
					                            <form action="dopost.php?idcat='.$category.'&idtop='.$topicsid.'" name="summernote" method="POST" enctype="multipart/form-data">
								                    <input id="usertemp" name="usertemp" class="form-control" type="hidden" value="'.$user.'">
													<textarea class="textarea form-control" id="summernote" name="postcomment" placeholder="Enter text ..." rows="15" data-height="300"></textarea>
								                    <label class="control-label">File 1 </label>
								                    <input id="files1" name="files1" class="form-control" type="file">
								                    <label class="control-label">File 2 </label>
								                    <input id="files2" name="files2" class="form-control" type="file">
					                                <div class="m-t-10">
					                                    <button type="submit" class="btn btn-primary">Post Comment <i class="fa fa-paper-plane"></i></button>
					                                </div>
					                            </form>
					                        </div>
					                    </div>';
                        } else {
                            echo '<div class="comment-banner-msg">
                        Posting to the forum is only allowed for '.$catgroup.' with active accounts.
                    </div>';
                        }
                    }
                    ?>
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
  function removePost(cat,top,post){
     swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin ingin menghapus komen ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya',
        cancelButtonText: "Tidak"
     }).then(function(){
        window.location = 'deletepost.php?idcat='+cat+'&top='+top+'&postid='+post;
       }).catch(swal.noop);
  }
$(document).ready(function() {
	App.init();
	//PageDemo.init();

	$(".active").removeClass("active");
	document.getElementById("has_forum").classList.add("active");

	$('#modal-repu').on('show.bs.modal', function(e) {
		idreputasi = $(e.relatedTarget).data('id');
		reputasi = document.getElementById("reputasi");
		reputasi.value = idreputasi;

	});

	$('#form-repu').bootstrapValidator({
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
			desrepu: {
				validators: {
					notEmpty: {
						message: 'Silahkan isi alasan berikan reputasi'
					}
				}
			}
		}
	});
  $('#summernote').summernote({
    height: 150,
  });
});
	</script>
</body>

</html>
