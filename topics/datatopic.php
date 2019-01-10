<?php
    // Variables for the first page hit
	$start = 0;
	$page_counter = 1;
    $per_page = 10;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;

    // Check the page location with start value sent by get request and change variable values accordingly
	if(isset($_GET['start'])){
		$start = $_GET['start'];
		$page_counter =  $_GET['start'];
		$start = ($start-1) *  $per_page;
		$next = $page_counter + 1;
		$previous = $page_counter - 1;
	}
	$category = $_REQUEST['idcat'];

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

	foreach($arBytes as $arItem)
	{
		if($bytes >= $arItem["VALUE"])
		{
			$result = $bytes / $arItem["VALUE"];
			$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
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
            $queryuser = mysql_query("SELECT * FROM usermst WHERE UserID = '" . $topicsby . "'");
            $rowuser = mysql_fetch_array($queryuser);
            $userphoto = $rowuser['UserPhoto'];
				echo '<div class="modal fade" id="modal-sharetop-'.$topicsid.'">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
											<h4 class="modal-title">Sharing </h4>
										</div>';
											$querycat = mysql_query("SELECT * FROM sharedcattop WHERE catid = '".$category."' AND topid = '".$topicsid."'");

											while($rowcat = mysql_fetch_array($querycat)){
												$usershare = $rowcat['shared'];
												$queryuser = mysql_query("SELECT * FROM usermst WHERE UserID = '".$usershare."'");
												$rowuser = mysql_fetch_array($queryuser);
												$sharenama = $rowuser['UserName'];
												$sharephoto = $rowuser['UserPhoto'];
												$shareemail = $rowuser['UserEmail'];
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
                                                                <a title="delete" href="dodelete.php?idcat='.$category.'&idtop='.$topicsid.'&shareuser='.$usershare.'"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>';
											}

										echo'
											<form action="dosharetop.php?idcat='.$category.'&idtop='.$topicsid.'" method="post">
											<div class="EDlbXc-x3Eknd-xhiy4">
                                                        <div class="EDlbXc-x3Eknd-xhiy4-Bz112c-VtOx3e">
                                                            <span class="fa-stack fa-2x ">
																<i class="fa fa-circle fa-stack-2x"></i>
																<i class="fa fa-user-plus fa-stack-1x fa-inverse"></i>
															</span>
                                                        </div>
                                                        <div class="EDlbXc-x3Eknd-xhiy4-fmcmS-haAclf">
                                                            <input name="userid" id="userid" autocomplete="off" placeholder="Person or email to share with" class="form-control" type="text">
                                                            </div>

                                                        </div>
	                                            <div class="EDlbXc-L9AdLc-yePe5c">
														<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Close</a>
														<input type="submit" class="btn btn-sm btn-danger" value="Save">
	                                            </div>
	                                            </form>
									</div>
								</div>
							</div>
							<script>
							$(document).ready(function() {
								$.ajax({
									type: "POST",
									url: "../data.php",
									data: {functionname: "autocomplete"},
									success:function(data) {
										var e = JSON.parse("[" + data + "]");
										$("#userid").autocomplete({
											source: e

										})
									}
								});
							});
							</script>
	';

        	if($topicmode == "Sticky"){
        		$classsticky = 'bg-danger';
        		$iconsticky = '<i class="glyphicon glyphicon-pushpin fa-2x text-danger"></i>';
        	}else{
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
            } elseif ($days == '1' AND $months == '0' AND $years == '0') {
                $postdate = 'Kemarin ' . $posttime;
            } elseif ($days == '7' AND $months == '0' AND $years == '0') {
                $postdate = '1 Minggu yang lalu ' . $posttime;
            } elseif ($days == '14' AND $months == '0' AND $years == '0') {
                $postdate = '2 Minggu yang lalu ' . $posttime;
            } elseif ($days == '21' AND $months == '0' AND $years == '0') {
                $postdate = '3 Minggu yang lalu ' . $posttime;
            } elseif ($days == '28' AND $months == '0' AND $years == '0') {
                $postdate = '4 Minggu yang lalu ' . $posttime;
            } elseif ($months == '1' AND $months == '0' AND $years == '0') {
                $postdate = '1 Bulan yang lalu ' . $posttime;
            } elseif ($months == '2' AND $years == '0') {
                $postdate = '2 Bulan yang lalu ' . $posttime;
            } elseif ($months == '3' AND $years == '0') {
                $postdate = '3 Bulan yang lalu ' . $posttime;
            } elseif ($months == '4' AND $years == '0') {
                $postdate = '4 Bulan yang lalu ' . $posttime;
            } elseif ($months == '5' AND $years == '0') {
                $postdate = '5 Bulan yang lalu ' . $posttime;
            } elseif ($months == '6' AND $years == '0') {
                $postdate = '6 Bulan yang lalu ' . $posttime;
            } elseif ($months == '7' AND $years == '0') {
                $postdate = '7 Bulan yang lalu ' . $posttime;
            } elseif ($months == '8' AND $years == '0') {
                $postdate = '8 Bulan yang lalu ' . $posttime;
            } elseif ($months == '9' AND $years == '0') {
                $postdate = '9 Bulan yang lalu ' . $posttime;
            } elseif ($months == '10' AND $years == '0') {
                $postdate = '10 Bulan yang lalu ' . $posttime;
            } elseif ($months == '11' AND $years == '0') {
                $postdate = '11 Bulan yang lalu ' . $posttime;
            } elseif ($months == '12' AND $years == '0') {
                $postdate = '12 Bulan yang lalu ' . $posttime;
            } elseif ($years == '1') {
                $postdate = '1 Tahun yang lalu ' . $posttime;
            }
        	if($num_post>0){
        		$num_post = $num_post-1;
        	}
        	$queryshare = mysql_query("SELECT * FROM sharedcattop WHERE shared = '".$user."' and catid = '".$category."' and topid = '".$topicsid."'");
        	$valid =null;
        	while($rowshare = mysql_fetch_array($queryshare)){
        		$valid = '1';
        	}

        	if($userlevel >= 50){
        		$lockclass = '';
        		$link = '../topics?idcat=' . $idcat . '';
        		$linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
        		$share = '<a id="share" href="?idcat='.$idcat.'#modal-share-'.$idcat.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';

        	}elseif($valid=='1'){
        		$lockclass = '';
        		$link = '../topics?idcat=' . $idcat . '';
        		$linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
        		$share = '';

        	}else{
        		if($user == $topicsby OR $catgroup == 'ALL' OR $catgroup == 'HRD'){
        			$lockclass = '';
        			$link = '../topics?idcat=' . $idcat . '';
        			$linktopic = 'viewpost.php?idcat='.$category.'&top=' . $topicsid . '';
        			$share = '';
        		}else{
        			$lockclass = '<i class="fa fa-lock"></i>';
        			$link = "javascript:;";
        			$linktopic = 'javascript:;';
        			$share = '';
        		}
        	}
            if ($li_row == 1) {
                $data = '<ul class="forum-list forum-topic-list">';
            }
            $data .= '<li class=" '.$classsticky.'">
                                <!-- begin media -->
                                <div class="media">
                                    <img src="../assets/img/avatar/' . $userphoto . '" alt="" />
                                </div>
                                <!-- end media -->
                                <!-- begin info-container -->
                                <div class="info-container">
                                    <div class="info">
                                        <h4 class="title"><a href="'.$linktopic.'">' . $topicsname . '</a> '.$lockclass.'</h4>
										'.$iconsticky.'
                                        <ul class="info-start-end">
                                            <li>post by <a href="'.$linktopic.'">' . $topicsby . '</a></li>';
								        	if($num_post>0){
								        		$data .='<li>latest reply <a href="'.$linktopic.'">' . $postby . '</a></li>';
								        	}
        									if($topicsby==$user)
        									$data .= '<a id="share" href="?idcat='.$category.'&top=' . $topicsid . '#modal-sharetop-'.$topicsid.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
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
	$count_query = "SELECT * FROM topics WHERE topic_cat = '" . $category . "'";

	$query = mysql_query($count_query);


	$count = mysql_num_rows($query);

    // calculate number of paginations required based on row count
	$paginations = ceil($count / $per_page);


?>