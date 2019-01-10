<?php
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
    $category = $_REQUEST['idcat'];
    $topicsid = $_REQUEST['top'];

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
    $query = mysql_query("SELECT * FROM posts WHERE post_topic = '" . $topicsid . "'  ORDER BY post_id ASC limit $start, $per_page");

    //placeholder variable to store result
    $result = null;
    $num_row = mysql_num_rows($query);
        $li_row = 1;
        while ($row = mysql_fetch_array($query)) {
            $postby = $row['post_by'];
            $postcontent = $row['post_content'];
            $postdate = $row['post_date'];
            $postupdate = $row['post_update'];
            if ($postupdate!="" or $postupdate != null) {
                $lastmodify = 'Last edit '.date("Y-m-d H:i:s", strtotime($postupdate));
            } else {
                $lastmodify = "";
            }
            $foldername = date("M", strtotime($postdate)).date("y", strtotime($postdate));
            $postid = $row['post_id'];
            $postnum = $row['post_num'];
            $attachment1 = $row['post_attachment1'];
            $attachmentname1 = $row['post_attachmentname1'];
            $attachment2 = $row['post_attachment2'];
            $attachmentname2 = $row['post_attachmentname2'];
            $posttime = date("H:i", strtotime($postdate));
            $today = date("Y-m-d");
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

            $queryuser = mysql_query("SELECT * FROM useraccess WHERE username = '" . $postby . "'");
            $rowuser = mysql_fetch_array($queryuser);
            $userphoto = $rowuser['photothumb'];
            $userid = $rowuser['id'];
            $username = $rowuser['username'];
            $userjabatan = getLevelText($rowuser['level']);
            $UserCreateDate = $rowuser['input_time'];
            $UserCreateDate = date("d-m-Y", strtotime($UserCreateDate));

            $querytopic = mysql_query("SELECT * FROM topics WHERE topic_id = '" . $topicsid . "'");
            $rowtopic = mysql_fetch_array($querytopic);
            $catid = $rowtopic['topic_cat'];
            if ($li_row == 1) {
                $data = '<ul class="forum-list forum-detail-list">';
            }
            // $cekonline = mysql_fetch_array(mysql_query("SELECT count(*) as online FROM ajkuserlogin WHERE user_id = '".$userid."'"));
            // if ($cekonline['online']>0) {
            //     $classonline = '<a data-toggle="tooltip" title="Online"> <i class="fa fa-smile-o text-success fa-2x"></i></a>';
            // } else {
            //     $classonline = '<a data-toggle="tooltip" title="Offline"><i class="fa fa-frown-o text-inverse fa-2x"></i></a>'.$cekonline['online'];
            // }
            $data .= '<li><section id="'.$postnum.'"><h2 class="page-header"><a href="#'.$postnum.'"></a></h2>
                            <!-- begin media -->
                            <div class="media">
                                <img src="../assets/img/'.$userphoto.'" alt="" />
                                <span class="label-control label label-danger">'.$userjabatan.'</span>
                            </div>
                            <!-- end media -->

                            <!-- begin info-container -->
                            <div class="info-container">
                            <div class="panel panel-forum">
                            	<div class="panel-heading">
                            		<a href="#">'.$username.'</a> ('.$userjabatan.') .
									<div class="panel-heading-btn">
										<a class="btn btn-xs btn-icon btn-circle btn-warning" href="#'.$postnum.'">#'.$postnum.'</a>
									</div>
									<br>
									<label class="label-control">Join : '.$UserCreateDate.' '.'</label>
									<div class="panel-heading-btn">';

            $data .= $classonline.'
									</div>
                            	</div>

		                    </div>

                                <div class="post-content">
                                    '.$postcontent.'
                                </div>';
            if ($attachment1!="" or $attachment2!="") {
                $filesize1 = filesize('../assets/documents/discussion/'.$foldername.'/'.$attachment1);
                $filesize2 = filesize('../assets/documents/discussion/'.$foldername.'/'.$attachment2);
                if ($attachment1=="" and $attachment2!="") {
                    $attacment = 1;
                } elseif ($attachment2=="" and $attachment1!="") {
                    $attacment = 1;
                } else {
                    $attacment = 0;
                }
                if ($attachment1!="" and $attachment2!="") {
                    $attacment = 2;
                }
                $jml = $attacment;
                $data .=' <div class="forum-attachment">
										'.$jml.' attachment(s)
										<ul class="list-inline margin-top-10">';
                if ($attachment1!="") {
                    $data .='<li class="well well-sm padding-5">
													<strong>'.$attachmentname1.'</strong>
													<br>
													'.FileSizeConvert($filesize1).'
													<br>
													<a href="../assets/documents/discussion/'.$foldername.'/'.$attachment1.'"> Download</a>
											</li>';
                }
                if ($attachment2!="") {
                    $data .='	<li class="well well-sm padding-5">
													<strong>'.$attachmentname2.'</strong>
													<br>
													'.FileSizeConvert($filesize2).'
													<br>
													<a href="../assets/documents/discussion/'.$foldername.'/'.$attachment2.'"> Download</a>
											</li>';
                }
                $data .='</ul>
									</div>';
            }
            $data .='<div class="row">
											<div class="col-md-6">
												<div class="post-time">'.$postdate.'</div>
											</div>
											<div class="col-md-6">
												<div class="text-right">
												<div class="col-md-6">
													<div>'.$lastmodify.'</div>
												</div>
													<!-- <a href="form_editable3366.html?c=inline" class="btn btn-success btn-xs"><i class="fa fa-comment"></i> Qoute</a> -->';
            if ($user==$postby || $userLevel==99) {
                $data .= ' <a href="editpost.php?idcat='.$category.'&top='.$topicsid.'&start='.$page_counter.'&postid='.$postid.'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>';
                $data .= ' <a href="#" class="btn btn-danger btn-xs" onclick="removePost('.$category.','.$topicsid.','.$postid.')"><i class="fa fa-remove"></i> Remove</a>';
            }

            $data.='</div>
											</div>
										</div>
                            </div>
                            <!-- end info-container -->
                        </section></li>
                        ';
            if ($num_row == $li_row) {
                $data .= '</ul>';
            }
            $data .='<div class="modal fade" id="modal-repu" tabindex="-1" role="dialog" aria-hidden="true">
				                                <div class="modal-dialog">
				                                    <div class="modal-content">
				                                        <div class="modal-header">
				                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
				                                            <h4 class="modal-title">Berikan Reputasi</h4>
				                                        </div>
					                                    <form action="doreputasi.php" id="form-repu" class="form-repu" method="post">
						                                    <div class="modal-body">
						                                    	<center>
																<div class="form-group">
											                        <label class="radio-inline">
											                        <input name="tiperepu" value="Baik" checked="" type="radio">
											                            <i class="fa fa-square text-success fa-4x aria-hidden="true"></i>
											                        </label>
											                        <label class="radio-inline">
											                        <input name="tiperepu" value="Buruk" type="radio">
											                           <i class="fa fa-square text-danger fa-4x aria-hidden="true"></i>
											                        </label>
											                    </div>
											                    </center>
																<div class="form-group">
													                <textarea class="form-control alasan" id="desrepu" name="desrepu"></textarea>
													            </div>
													            	<input type="hidden" name="idtop" id="idtop" value="'.$topicsid.'"/>
													            	<input type="hidden" name="idcat" id="idcat" value="'.$category.'"/>
													            	<input type="hidden" name="reputasi" id="reputasi"/>
							                                </div>
						                                    <div class="modal-footer">
						                                    <center>
						                                        <input type="submit" id="btnreputasi" name="btnreputasi"  class="btn width-100 btn-primary" value="Beri Dah..."/>
						                                    </center>
						                                    </div>
						                                </form>
				                                	</div>
				                            	</div>
				                            </div>';
            $li_row++;
        }
        echo $data;
    // query to get total number of rows in messages table
    $count_query = "SELECT * FROM posts WHERE post_topic = '" . $topicsid . "'";

    $query = mysql_query($count_query);


    $count = mysql_num_rows($query);

    // calculate number of paginations required based on row count
    $paginations = ceil($count / $per_page);
