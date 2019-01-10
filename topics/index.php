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
$userlevel = $rowuser['level'];
$usergroup = $rowuser['supervisor'];

if (isset($_REQUEST['idcat'])) {
    $category = $_REQUEST['idcat'];
} else {
    header("location:../discussion");
}
if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
} else {
    $search = "";
}
$querycat = mysql_query("SELECT * FROM categories WHERE cat_id = '".$category."'");
$rowcat = mysql_fetch_array($querycat);
$namacat = $rowcat['cat_name'];
$catgroup = $rowcat['cat_group'];
// Variables for the first page hit
$start = 0;
$page_counter = 1;
$per_page = 10;
$next = $page_counter + 1;
$previous = $page_counter - 1;

// Check the page location with start value sent by get request and change variable values accordingly
if (isset($_GET['start'])) {
    $start = $_GET['start'];
    $page_counter =  $_GET['start'];
    $start = ($start-1) *  $per_page;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;
}

// query to get total number of rows in messages table
$count_query =  "SELECT * FROM topics WHERE topic_cat = '" . $category . "'
AND (topic_by LIKE '%".$search."%' OR topic_subject LIKE '%".$search."%' OR topic_date LIKE '%".$search."%')
AND (del = '0' OR del is null)";

$query = mysql_query($count_query);


$count = mysql_num_rows($query);

// calculate number of paginations required based on row count
$paginations = ceil($count / $per_page);

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
				<div class="row">
					<div class="col-md-9">
					<form action="dosearch.php" class="">
					   <div class="input-group">
					       <input type="Search" name="search" id="search" placeholder="Search..." class="form-control input-md" />
					       <input type="hidden" name="idcat" id="idcat" value="<?php echo $category ?>" />
					       <div class="input-group-btn">
					           <button class="btn btn-info">
					           <span class="glyphicon glyphicon-search"></span>
					           </button>
					       </div>
					   </div>
					</form>
					</div>
				</div></br>
					<div class="row">
	                    <div class="col-md-3">
		                    <div class="text-left">
		                    <?php
                            if ($userlevel >= 50) {
                                echo '<a href="addthread.php?idcat='.$category.'" class="btn btn-primary"><i class="fa fa-edit"></i> Create New Thread</a>';
                            } else {
                                $queryshare = mysql_query("SELECT * FROM sharedcat WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$category."'");
                                $valid =null;
                                while ($rowshare = mysql_fetch_array($queryshare)) {
                                    $valid = '1';
                                }
                                if ($usergroup == $catgroup or $catgroup == 'ALL' or $valid=='1') {
                                    echo '<a href="addthread.php?idcat='.$category.'" class="btn btn-primary"><i class="fa fa-edit"></i> Create New Thread</a>';
                                }
                            }
                            ?>
		                    </div>
	                    	<!-- end pagination -->
	                    </div>
	                    <div class="col-md-6">
		                    <div class="text-right">
		                        <ul class="pagination m-t-0 m-b-15">
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
                                        if ($search=="") {
                                            $varsearch = '';
                                        } else {
                                            $varsearch = '&search='.$search;
                                        }

                                        if ($page_counter == 1) {
                                            echo "<li class='active'><a href=?idcat=$category&start=1$varsearch>1</a></li>";
                                            for ($j=2; $j <= $paginations; $j++) {
                                                if ($j >= $pageactive and $j <= $butonpaging) {
                                                    $classdisplay =  "";
                                                } else {
                                                    $classdisplay =  "style='display: none'";
                                                }
                                                echo "<li $classdisplay><a href=?idcat=$category&start=$j".$varsearch.">".$j."</a></li>";
                                            }
                                        } else {
                                            echo "<li><a href=?idcat=$category&start=1".$varsearch."><i class='fa fa-angle-left'></i><i class='fa fa-angle-left'></i></a></li>";
                                            echo "<li><a href=?idcat=$category&start=$previous".$varsearch."><i class='fa fa-angle-left'></i></a></li>";

                                            for ($j=1; $j <= $paginations; $j++) {
                                                if ($j == $page_counter) {
                                                    echo "<li class='active'><a href=?idcat=$category&start=$j".$varsearch.">".$j."</a></li>";
                                                } else {
                                                    if ($j >= $pageactive-4 and $j <= $butonpaging) {
                                                        $classdisplay =  "";
                                                    } else {
                                                        $classdisplay =  "style='display: none'";
                                                    }
                                                    if ($j >= $pageactive+5) {
                                                        $classdisplay =  "style='display: none'";
                                                    }

                                                    echo "<li $classdisplay><a href=?idcat=$category&start=$j".$varsearch.">".$j."</a></li>";
                                                }
                                            }
                                            if ($j != $page_counter+1) {
                                                echo "<li><a href=?idcat=$category&start=$next".$varsearch."><i class='fa fa-angle-right'></i></a></li>";
                                            }
                                        }
                                        if ($page_counter != $paginations) {
                                            echo "<li><a href=?idcat=$category&start=$paginations".$varsearch."><i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i></a></li>";
                                        }

                                  ?>
		                        </ul>
		                    </div>
	                    	<!-- end pagination -->
	                    </div>
	                </div>

			<!-- begin row -->
            <div class="row">
                <!-- begin col-9 -->

                <div class="col-md-9">
                    <div class="panel panel-forum">
                    	<?php


                    function FileSizeConvert($bytes)
                    {
                        $bytes = floatval($bytes);
                        $arBytes = array(
                                0 => array(
                                    "UNIT" => "TB",
                                    "VALUE" => pow(1024, 4)
                                ),
                                1 => array(
                                    "UNIT" => "GB",
                                    "VALUE" => pow(1024, 3)
                                ),
                                2 => array(
                                    "UNIT" => "MB",
                                    "VALUE" => pow(1024, 2)
                                ),
                                3 => array(
                                    "UNIT" => "KB",
                                    "VALUE" => 1024
                                ),
                                4 => array(
                                    "UNIT" => "B",
                                    "VALUE" => 1
                                ),
                            );

                        foreach ($arBytes as $arItem) {
                            if ($bytes >= $arItem["VALUE"]) {
                                $result = $bytes / $arItem["VALUE"];
                                $result = str_replace(".", ",", strval(round($result, 2)))." ".$arItem["UNIT"];
                                break;
                            }
                        }
                        return $result;
                    }

    // query to get messages from messages table
        //$query = mysql_query("SELECT * FROM topics WHERE topic_cat = '" . $category . "' ORDER BY topic_mode ASC limit $start, $per_page");
        $query = mysql_query("SELECT topic_by,topic_id,topic_mode,topic_subject,post_date FROM topics
		JOIN (SELECT post_date, post_topic FROM posts ORDER BY post_date DESC) as post ON post.post_topic = topic_id
		WHERE topic_cat = '" . $category . "'
		AND (topic_by LIKE '%".$search."%' OR topic_subject LIKE '%".$search."%' OR post_date LIKE '%".$search."%')
		AND (del = '0' OR del is null)
		GROUP BY topic_by,topic_id,topic_mode,topic_subject
		ORDER BY topic_mode,post_date DESC limit $start, $per_page");

        $num_row = mysql_num_rows($query);
        $li_row = 1;
        while ($row = mysql_fetch_array($query)) {
            $topicsname = $row['topic_subject'];
            $topicsby = $row['topic_by'];
            $topicsid = $row['topic_id'];
            $topicmode = $row['topic_mode'];
            $querypost = mysql_query("SELECT * FROM posts
					WHERE post_topic = '" . $topicsid . "' ORDER BY post_date DESC");
            $num_post = mysql_num_rows($querypost);
            $rowpost = mysql_fetch_array($querypost);
            $postid = $rowpost['post_id'];
            $postnum = $rowpost['post_num'];
            $postby = $rowpost['post_by'];
            $postdate = $rowpost['post_date'];
            $queryuser = mysql_query("SELECT * FROM useraccess WHERE username = '" . $topicsby . "'");
            $rowuser = mysql_fetch_array($queryuser);
            $userphoto = $rowuser['photothumb'];
            echo '<div class="modal fade" id="modal-sharetop-'.$topicsid.'">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
											<h4 class="modal-title">Sharing </h4>
										</div>';
            $querycat = mysql_query("SELECT * FROM sharedcattop WHERE catid = '".$category."' AND topid = '".$topicsid."' AND shared <> ''");

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
                                                                    <div style="background-image: url(../assets/img/'.$sharephoto.');" class="EDlbXc-x3Eknd-HiaYvf-bN97Pc"></div>
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
                                                                <a title="delete" href="dodelete.php?idcat='.$category.'&idtop='.$topicsid.'&shareuser='.$usershare.'"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>';
            }

            echo'
											<form action="dosharetop.php?idcat='.$category.'&idtop='.$topicsid.'" class="form-share" method="post">
											<div class="EDlbXc-x3Eknd-xhiy4">
                                                        <div class="EDlbXc-x3Eknd-xhiy4-Bz112c-VtOx3e">
                                                            <span class="fa-stack fa-2x ">
																<i class="fa fa-circle fa-stack-2x"></i>
																<i class="fa fa-user-plus fa-stack-1x fa-inverse"></i>
															</span>
                                                        </div>
                                                        <div class="EDlbXc-x3Eknd-xhiy4-fmcmS-haAclf form-group">
                                                            <input name="userid" id="userid" maxlength="320" aria-label="UserId to share with" placeholder="Person to share with" class="form-control autocomplete" type="text">
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

            if ($topicmode == "Sticky") {
                $classsticky = 'bg-danger';
                $iconsticky = '<i class="glyphicon glyphicon-pushpin fa-2x text-danger"></i>';
            } else {
                $classsticky = '';
                $iconsticky ='';
            }
            $posttime = date("H:i", strtotime($postdate));
            $today = date("Y-m-d");
            $postdate = date("Y-m-d", strtotime($postdate));
            $diff = abs(strtotime($postdate) - strtotime($today));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            if (date("Y-m-d", strtotime($postdate)) == date("Y-m-d")) {
                $postdate = 'Hari Ini ' . $posttime;
            } elseif ($days == '1' and $months == '0' and $years == '0') {
                $postdate = 'Kemarin ' . $posttime;
            } elseif ($days == '7' and $months == '0' and $years == '0') {
                $postdate = '1 Minggu yang lalu ' . $posttime;
            } elseif ($days == '14' and $months == '0' and $years == '0') {
                $postdate = '2 Minggu yang lalu ' . $posttime;
            } elseif ($days == '21' and $months == '0' and $years == '0') {
                $postdate = '3 Minggu yang lalu ' . $posttime;
            } elseif ($days == '28' and $months == '0' and $years == '0') {
                $postdate = '4 Minggu yang lalu ' . $posttime;
            } elseif ($months == '1' and $months == '0' and $years == '0') {
                $postdate = '1 Bulan yang lalu ' . $posttime;
            } elseif ($months == '2' and $years == '0') {
                $postdate = '2 Bulan yang lalu ' . $posttime;
            } elseif ($months == '3' and $years == '0') {
                $postdate = '3 Bulan yang lalu ' . $posttime;
            } elseif ($months == '4' and $years == '0') {
                $postdate = '4 Bulan yang lalu ' . $posttime;
            } elseif ($months == '5' and $years == '0') {
                $postdate = '5 Bulan yang lalu ' . $posttime;
            } elseif ($months == '6' and $years == '0') {
                $postdate = '6 Bulan yang lalu ' . $posttime;
            } elseif ($months == '7' and $years == '0') {
                $postdate = '7 Bulan yang lalu ' . $posttime;
            } elseif ($months == '8' and $years == '0') {
                $postdate = '8 Bulan yang lalu ' . $posttime;
            } elseif ($months == '9' and $years == '0') {
                $postdate = '9 Bulan yang lalu ' . $posttime;
            } elseif ($months == '10' and $years == '0') {
                $postdate = '10 Bulan yang lalu ' . $posttime;
            } elseif ($months == '11' and $years == '0') {
                $postdate = '11 Bulan yang lalu ' . $posttime;
            } elseif ($months == '12' and $years == '0') {
                $postdate = '12 Bulan yang lalu ' . $posttime;
            } elseif ($years == '1') {
                $postdate = '1 Tahun yang lalu ' . $posttime;
            }
            if ($num_post>0) {
                $num_post = $num_post-1;
            }
            $queryshare = mysql_query("SELECT * FROM sharedcattop WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$category."' and topid = '".$topicsid."'");
            $valid =null;
            while ($rowshare = mysql_fetch_array($queryshare)) {
                $valid = '1';
            }

            if ($userlevel >= 50) {
                $lockclass = '';
                $link = '../topics?idcat=' . $idcat . '';
                $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
                // $share = '<a id="share" href="?idcat='.$idcat.'#modal-share-'.$idcat.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
                $share = '';
            } elseif ($valid=='1') {
                $lockclass = '';
                $link = '../topics?idcat=' . $idcat . '';
                $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
                $share = '';
            } else {
                if ($user == $topicsby or $catgroup == 'ALL' or $catgroup == 'HRD') {
                    $lockclass = '';
                    $link = '../topics?idcat=' . $idcat . '';
                    $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
                    $share = '';
                } else {
                    $lockclass = '<i class="fa fa-lock"></i>';
                    $link = "javascript:;";
                    $linktopic = 'javascript:;';
                    $share = '';
                }
            }
            if ($li_row == 1) {
                $data = '<ul class="forum-list forum-topic-list">';
            }
            if ($user == $topicsby or $userlevel >= 90) {
                $classedit = '<a href="editthread.php?idcat='.$category.'&top='.$topicsid.'"><i class="fa fa-edit text-primary"></i></a>';
                $classremove = ' <a href="#" onclick="removeTop('.$category.','.$topicsid.')"><i class="fa fa-remove text-danger"></i></a>';
            } else {
                $classedit = '';
                $classremove = '';
            }
            $data .= '<li class=" '.$classsticky.'">
                                <!-- begin media -->
                                <div class="media">
                                    <img src="../assets/img/' . $userphoto . '" alt="" />
                                </div>
                                <!-- end media -->
                                <!-- begin info-container -->
                                <div class="info-container">
                                    <div class="info">
                                        <h4 class="title"><a href="'.$linktopic.'">' . $topicsname . '</a> '.$classedit.$classremove.' </h4>
										'.$iconsticky.'
                                        <ul class="info-start-end">
                                            <li>post by <a href="'.$linktopic.'">' . $topicsby . '</a></li>
                                            <li><br /></li>';

            if ($num_post>0) {
                $data .='<li>latest reply <a href="'.$linktopic.'">' . $postby . '</a></li>';
            }
            $querycat = mysql_query("SELECT * FROM sharedcattop
        										LEFT JOIN useraccess ON (useraccess.UserID = sharedcattop.shared OR sharedcattop.shared = useraccess.supervisor)
        										WHERE catid = '".$category."' AND topid = '".$topicsid."' AND shared <> ''
        										AND username <> ''
        										GROUP BY shared");
            $lirowcat = 1;
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
                if ($lirowcat==1) {
                    //$data .= '<div class="row">
                                                                //	<div class="col-md-9">';
                }

                $data .='<div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-HiaYvf-haAclf">
                                                                <div class="EDlbXc-x3Eknd-HiaYvf">
                                                                    <div data-value="navbar-inverse" data-click="header-theme-selector" data-toggle="tooltip" data-title="'.$sharenama.'" style="background-image: url(../assets/img/avatar/'.$sharephoto.');" class="EDlbXc-x3Eknd-HiaYvf-bN97Pc"></div>
                                                                </div>
                                                            </div>';
                $lirowcat++;
            }
            //$data .= '</div></div>';
            if ($userlevel >= 50) {
                // $data .= '<a id="share" href="?idcat='.$category.'&top=' . $topicsid . '#modal-sharetop-'.$topicsid.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
            } elseif ($topicsby==$user) {
                // $data .= '<a id="share" href="?idcat='.$category.'&top=' . $topicsid . '#modal-sharetop-'.$topicsid.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
            }

            $data .='</ul>
                                    </div>
                                    <div class="date-replies">
                                        <div class="time">
                                            ' . $postdate . '
                                        </div>
                                        <div class="replies">
                                            <div class="total">' . $num_post . '</div>
                                            <div class="text">REPLIES</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end info-container -->
                            </li>';
            if ($num_row == $li_row) {
                $data .= '</ul>';
            }
            $li_row++;
        }
        echo $data;
    // query to get total number of rows in messages table
    $count_query = "SELECT * FROM topics WHERE topic_cat = '" . $category . "'
	AND (topic_by LIKE '%".$search."%' OR topic_subject LIKE '%".$search."%' OR topic_date LIKE '%".$search."%')
	AND (del = '0' OR del is null)";

    $query = mysql_query($count_query);


    $count = mysql_num_rows($query);

    // calculate number of paginations required based on row count
    $paginations = ceil($count / $per_page);


?>
                    	<!-- <div id="topicslist"></div> -->

                    </div>
                    <!-- end panel-forum -->

					<div class="row">
	                    <div class="col-md-3">
		                    <div class="text-left">
		                    <?php
                            if ($userlevel >= 50) {
                                echo '<a href="addthread.php?idcat='.$category.'" class="btn btn-primary"><i class="fa fa-edit"></i> Create New Thread</a>';
                            } else {
                                $queryshare = mysql_query("SELECT * FROM sharedcat WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$category."'");
                                $valid =null;
                                while ($rowshare = mysql_fetch_array($queryshare)) {
                                    $valid = '1';
                                }
                                if ($usergroup == $catgroup or $catgroup == 'ALL' or $valid=='1' or $catgroup == 'HRD') {
                                    echo '<a href="addthread.php?idcat='.$category.'" class="btn btn-primary"><i class="fa fa-edit"></i> Create New Thread</a>';
                                }
                            }
                            ?>
		                    </div>
	                    	<!-- end pagination -->
	                    </div>
	                    <div class="col-md-9">
		                    <div class="text-right">
		                        <ul class="pagination m-t-0 m-b-15">
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
                                            echo "<li class='active'><a href=?idcat=$category&top=$topicsid&start=1".$varsearch.">1</a></li>";
                                            for ($j=2; $j <= $paginations; $j++) {
                                                if ($j >= $pageactive and $j <= $butonpaging) {
                                                    $classdisplay =  "";
                                                } else {
                                                    $classdisplay =  "style='display: none'";
                                                }
                                                echo "<li $classdisplay><a href=?idcat=$category&top=$topicsid&start=$j".$varsearch.">".$j."</a></li>";
                                            }
                                        } else {
                                            echo "<li><a href=?idcat=$category&top=$topicsid&start=1".$varsearch."><i class='fa fa-angle-left'></i><i class='fa fa-angle-left'></i></a></li>";
                                            echo "<li><a href=?idcat=$category&top=$topicsid&start=$previous".$varsearch."><i class='fa fa-angle-left'></i></a></li>";

                                            for ($j=1; $j <= $paginations; $j++) {
                                                if ($j == $page_counter) {
                                                    echo "<li class='active'><a href=?idcat=$category&top=$topicsid&start=$j".$varsearch.">".$j."</a></li>";
                                                } else {
                                                    if ($j >= $pageactive-4 and $j <= $butonpaging) {
                                                        $classdisplay =  "";
                                                    } else {
                                                        $classdisplay =  "style='display: none'";
                                                    }
                                                    if ($j >= $pageactive+5) {
                                                        $classdisplay =  "style='display: none'";
                                                    }

                                                    echo "<li $classdisplay><a href=?idcat=$category&top=$topicsid&start=$j".$varsearch.">".$j."</a></li>";
                                                }
                                            }
                                            if ($j != $page_counter+1) {
                                                echo "<li><a href=?idcat=$category&top=$topicsid&start=$next".$varsearch."><i class='fa fa-angle-right'></i></a></li>";
                                            }
                                        }
                                        if ($page_counter != $paginations) {
                                            echo "<li><a href=?idcat=$category&top=$topicsid&start=$paginations".$varsearch."><i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i></a></li>";
                                        }

                                  ?>
		                        </ul>
		                    </div>
	                    	<!-- end pagination -->
	                    </div>
	                </div>
                </div>
                <!-- end col-9 -->
                <!-- begin col-3 -->
                <div class="col-md-3">
                    <!-- begin panel-forum -->
                    <div class="panel panel-forum">
                        <div class="panel-heading">
                            <h4 class="panel-title">Active Threads</h4>
                        </div>
                        <!-- begin threads-list -->
                        <ul class="threads-list">
                        <?php
                        $queryactive = mysql_query("SELECT topic_by,topic_id,topic_mode,topic_subject,post_date FROM topics
						JOIN (SELECT post_date, post_topic FROM posts ORDER BY post_date DESC) as post ON post.post_topic = topic_id
						WHERE topic_cat = '" . $category . "'
						GROUP BY topic_by,topic_id,topic_mode,topic_subject
						ORDER BY topic_mode,post_date DESC LIMIT 6");
                        while ($rowactive = mysql_fetch_array($queryactive)) {
                            $topicid = $rowactive['topic_id'];
                            $topicname = $rowactive['topic_subject'];
                            $topicsby = $rowactive['topic_by'];

                            $querypost = mysql_query("SELECT * FROM posts
					WHERE post_topic = '" . $topicid . "' ORDER BY post_date DESC");
                            $num_post = mysql_num_rows($querypost);
                            $rowpost = mysql_fetch_array($querypost);
                            $postid = $rowpost['post_id'];
                            $postnum = $rowpost['post_num'];
                            $postby = $rowpost['post_by'];
                            $postdate = $rowpost['post_date'];
                            $queryuser = mysql_query("SELECT * FROM useraccess WHERE username = '" . $topicsby . "'");
                            $rowuser = mysql_fetch_array($queryuser);
                            $userphoto = $rowuser['photothumb'];
                            $usernamepost = $rowuser['username'];


                            if ($topicmode == "Sticky") {
                                $classsticky = 'bg-danger';
                                $iconsticky = '<i class="glyphicon glyphicon-pushpin fa-2x text-danger"></i>';
                            } else {
                                $classsticky = '';
                                $iconsticky ='';
                            }
                            $posttime = date("H:i", strtotime($postdate));
                            $today = date("Y-m-d");
                            $postdate = date("Y-m-d", strtotime($postdate));
                            $diff = abs(strtotime($postdate) - strtotime($today));
                            $years = floor($diff / (365 * 60 * 60 * 24));
                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                            if (date("Y-m-d", strtotime($postdate)) == date("Y-m-d")) {
                                $postdate = 'Hari Ini ' . $posttime;
                            } elseif ($days == '1' and $months == '0' and $years == '0') {
                                $postdate = 'Kemarin ' . $posttime;
                            } elseif ($days == '7' and $months == '0' and $years == '0') {
                                $postdate = '1 Minggu yang lalu ' . $posttime;
                            } elseif ($days == '14' and $months == '0' and $years == '0') {
                                $postdate = '2 Minggu yang lalu ' . $posttime;
                            } elseif ($days == '21' and $months == '0' and $years == '0') {
                                $postdate = '3 Minggu yang lalu ' . $posttime;
                            } elseif ($days == '28' and $months == '0' and $years == '0') {
                                $postdate = '4 Minggu yang lalu ' . $posttime;
                            } elseif ($months == '1' and $months == '0' and $years == '0') {
                                $postdate = '1 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '2' and $years == '0') {
                                $postdate = '2 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '3' and $years == '0') {
                                $postdate = '3 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '4' and $years == '0') {
                                $postdate = '4 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '5' and $years == '0') {
                                $postdate = '5 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '6' and $years == '0') {
                                $postdate = '6 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '7' and $years == '0') {
                                $postdate = '7 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '8' and $years == '0') {
                                $postdate = '8 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '9' and $years == '0') {
                                $postdate = '9 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '10' and $years == '0') {
                                $postdate = '10 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '11' and $years == '0') {
                                $postdate = '11 Bulan yang lalu ' . $posttime;
                            } elseif ($months == '12' and $years == '0') {
                                $postdate = '12 Bulan yang lalu ' . $posttime;
                            } elseif ($years == '1') {
                                $postdate = '1 Tahun yang lalu ' . $posttime;
                            }
                            if ($num_post>0) {
                                $num_post = $num_post-1;
                            }

                            $queryshare = mysql_query("SELECT * FROM sharedcattop WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$category."' and topid = '".$topicid."'");
                            $valid =null;
                            while ($rowshare = mysql_fetch_array($queryshare)) {
                                $valid = '1';
                            }

                            if ($userlevel >= 50) {
                                $lockclass = '';
                                $link = '../topics?idcat=' . $category . '';
                                $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicid . '';
                                $share = '<a id="share" href="?idcat='.$category.'#modal-share-'.$category.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
                            } elseif ($valid=='1') {
                                $lockclass = '';
                                $link = '../topics?idcat=' . $category . '';
                                $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicid . '';
                                $share = '';
                            } else {
                                if ($user == $topicsby or $catgroup == 'ALL' or $catgroup == 'HRD') {
                                    $lockclass = '';
                                    $link = '../topics?idcat=' . $category . '';
                                    $linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicid . '';
                                    $share = '';
                                } else {
                                    $lockclass = '<i class="fa fa-lock"></i>';
                                    $link = "javascript:;";
                                    $linktopic = 'javascript:;';
                                    $share = '';
                                }
                            }
                            echo '<li>
									<h4 class="title"><a href="'.$linktopic.'">' . $topicname . '</a> '.$lockclass.'</h4>

	                                last reply by <a href="#">'.$usernamepost.'</a> '.$postdate.'	                            </li>';
                        }
                        ?>
                        </ul>
                        <!-- end threads-list -->
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
  function removeTop(cat,topic){
     swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin ingin menghapus topik ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ya',
        cancelButtonText: "Tidak"
     }).then(function(){
        window.location = 'deletetop.php?idcat='+cat+'&top='+topic;
       }).catch(swal.noop);
  }
$(document).ready(function() {
	App.init();

	$(".active").removeClass("active");
	document.getElementById("has_forum").classList.add("active");

	$.ajax({
		type: "POST",
		url: '<?= $path ?>/topics/data.php',
		data: {functionname: 'autocomplete'},
		success:function(data) {
			var e = JSON.parse("[" + data + "]");
			$(".autocomplete").autocomplete({
				source: e
			})
		}
	});

	$('.form-share').bootstrapValidator({
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
					userid: {
						validators: {
							notEmpty: {
								message: 'Username harus diisi'
							}/*,
							remote: {
								message: 'Username tidak tersedia',
								url: 'cekuser.php',
								data: {
									type: 'username'
								},
								type: 'POST'

							}
*/
						}
					}
				}
	});


	$('.autocomplete')
	.on('focus', function (e) {
		$('.form-share').bootstrapValidator('revalidateField', 'userid');
	});

	var idcat = '<?php echo $category ?>';
	$.ajax({
		type: "POST",
		url: '<?= $path ?>/topics/data.php',
		data: {functionname: 'datatop',category:idcat},
		success:function(data) {
			var e = JSON.parse("[" + data + "]");
			$(".input-search").autocomplete({
				source: e
			})
		}
	});



});
	</script>
</body>

</html>
