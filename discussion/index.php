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
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Forum </h1>

			<?php
            // if ($userlevel<=50) {
                echo '<a href="addcategory.php" class="btn btn-primary">Create New Categories</a><br /><br />';
            // }
            ?>

			<div id="category"></div>

		<?php
            $querysubcat = mysql_query("SELECT * FROM categories WHERE del=0");
            while ($rowsubcat = mysql_fetch_array($querysubcat)) {
                $idcat = $rowsubcat['cat_id'];
                $catname = $rowsubcat['cat_name'];
                $catdesc = $rowsubcat['cat_description'];
                $catimg = $rowsubcat['cat_images'];
                $catgroup = $rowsubcat['cat_group'];

                echo '<div class="modal fade" id="modal-share-'.$idcat.'">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
											<h4 class="modal-title">Sharing </h4>
										</div>';
                $querycat = mysql_query("SELECT * FROM sharedcat WHERE catid = '".$idcat."'");

                while ($rowcat = mysql_fetch_array($querycat)) {
                    $usershare = $rowcat['shared'];
                    $queryuser = mysql_query("SELECT * FROM useraccess WHERE username = '".$usershare."'");
                    $rowuser = mysql_fetch_array($queryuser);
                    $sharenama = $rowuser['username'];
                    $sharephoto = $rowuser['photothumb'];
                    $shareemail = $rowuser['email'];
                    if ($usershare=="APA") {
                        $sharephoto = 'grouplogo.png';
                        $sharenama = 'Adonai Pialang Asuransi';
                        $shareemail = 'adonai.co.id';
                    } elseif ($usershare=="ATS") {
                        $sharephoto = 'grouplogo.png';
                        $sharenama = 'Adonai Total Solusi';
                        $shareemail = 'adonaits.co.id';
                    }
                    echo '<div class="EDlbXc-x3Eknd-rymPhb">
	                            <div class="EDlbXc-x3Eknd">
	                                <div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-HiaYvf-haAclf">
	                                    <div class="EDlbXc-x3Eknd-HiaYvf">
	                                        <div style="background-image: url(../assets/img/avatar/'.$sharephoto.');" class="EDlbXc-x3Eknd-HiaYvf-bN97Pc"></div>
	                                    </div>
	                                </div>
	                                <div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-fmcmS-haAclf">
	                                    <div class="EDlbXc-x3Eknd-fmcmS-k77Iif-haAclf">
	                                        <span class="EDlbXc-x3Eknd-fmcmS-k77Iif">'.$sharenama.'</span>
	                                        <!--<span class="EDlbXc-x3Eknd-fmcmS-k77Iif-UjZuef">(Owner)</span>-->
	                                    </div>
	                                    <div class="EDlbXc-x3Eknd-fmcmS-K4efff">'.$shareemail.'</div>
	                                </div>
	                                <div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-VkLyEc-haAclf">
	                                    <a title="delete" href="dodelete.php?idcat='.$idcat.'&shareuser='.$usershare.'"><i class="fa fa-times" aria-hidden="true"></i></a>
	                                </div>
	                            </div>
	                        </div>';
                }

                echo'
											<form action="doshare.php?idcat='.$idcat.'" method="post">
											<div class="EDlbXc-x3Eknd-xhiy4">
                          <div class="EDlbXc-x3Eknd-xhiy4-Bz112c-VtOx3e">
                              <span class="fa-stack fa-2x ">
																<i class="fa fa-circle fa-stack-2x"></i>
																<i class="fa fa-user-plus fa-stack-1x fa-inverse"></i>
															</span>
                          </div>
                          <div class="EDlbXc-x3Eknd-xhiy4-fmcmS-haAclf" id="the-basics">
                          	<input name="userid" id="userid" maxlength="320" aria-label="UserId to share with" placeholder="Person to share with" class="form-control autcomplete" type="text">
                              </div>

                          </div>
                					<div class="EDlbXc-L9AdLc-yePe5c">
														<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
														<input type="submit" class="btn btn-sm btn-danger" value="Save">
                          </div>
                      </form>
									</div>
								</div>
							</div>';
            }
            ?>
		</div>
		<!-- end #content -->


		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->

	<?php _javascript(); ?>
	<script>
  function removeCat(cat){
     swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin ingin menghapus kategori ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya',
        cancelButtonText: "Tidak"
     }).then(function(){
        window.location = 'deletecat.php?idcat='+cat;
       }).catch(swal.noop);
  }

$(document).ready(function() {
	App.init();

	$(".active").removeClass("active");
	//$(".open").removeClass("open");
	document.getElementById("has_forum").classList.add("active");
	// document.getElementById("sub_viewdiscussion").classList.add("active");
	//$('#navsubul_manage').css('display','block');

	category();
	function category(){
		$.ajax({
			url: '<?= $path ?>/discussion/data.php',
			// global: false,
			crossDomain: true,
			type:"POST",
			data:"dataPost",
			data: {functionname: 'category'},
			success: function(data){
				document.getElementById("category").innerHTML = data;
			}
		});
	}

	$.ajax({
		type: "POST",
		url: '<?= $path ?>/discussion/data.php',
		crossDomain: true,
		data: {functionname: 'autocomplete'},
		success:function(data) {
			var e = JSON.parse("[" + data + "]");
			$(".autcomplete").autocomplete({
				source: e

			})
		}
	});
});


	</script>
</body>

</html>
