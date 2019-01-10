<?php
// header('Access-Control-Allow-Origin: *');
/**
 * DESC  : Create by satrya;
 * EMAIL : satryaharahap@gmail.com;
 * Create Date : 2015-04-08
 */
include "../param.php";

switch ($_POST['functionname']) {
    case 'category':
        session_start();
        $rs = mysql_query("SELECT * FROM categories GROUP BY cat_type");
        $num_row = mysql_num_rows($rs);
        $li_row = 1;
        $data = "";

        while ($row = mysql_fetch_array($rs)) {
            $cattyep = $row['cat_type'];
            echo '<!-- begin panel-forum -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                	<div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-lime" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">' . $cattyep . '</h4>
                </div>
                <!-- end panel-heading -->

                <div class="panel-body">
                <!-- begin forum-list -->
                <ul class="forum-list">';
            $querysubcat = mysql_query("SELECT * FROM categories WHERE cat_type = '" . $cattyep . "' AND (del = '0' OR del is null)");
            while ($rowsubcat = mysql_fetch_array($querysubcat)) {
                $idcat = $rowsubcat['cat_id'];
                $catname = $rowsubcat['cat_name'];
                $catdesc = $rowsubcat['cat_description'];
                $catimg = $rowsubcat['cat_images'] && file_exists('../assets/img/category/'.$rowsubcat['cat_images']) ? 'category/'.$rowsubcat['cat_images'] : 'chat.png';
                $catgroup = $rowsubcat['cat_group'];
                $catuserinput = $rowsubcat['cat_userinput'];
                /*
                                echo '<div class="modal fade" id="modal-share">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                            <h4 class="modal-title">Sharing </h4>
                                                        </div>';
                                                            $querycat = mysql_query("SELECT * FROM sharedcat WHERE catid = '".$idcat."'");

                                                            while($rowcat = mysql_fetch_array($querycat)){
                                                                $usershare = $rowcat['shared'];
                                                                $queryuser = mysql_query("SELECT * FROM usermst WHERE UserID = '".$usershare."'");
                                                                $rowuser = mysql_fetch_array($queryuser);
                                                                $usernama = $rowuser['UserName'];
                                                                $userphoto = $rowuser['UserPhoto'];
                                                                $useremail = $rowuser['UserEmail'];
                                                                echo '<div class="EDlbXc-x3Eknd-rymPhb">
                                                                        <div class="EDlbXc-x3Eknd">
                                                                            <div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-HiaYvf-haAclf">
                                                                                <div class="EDlbXc-x3Eknd-HiaYvf">
                                                                                    <div style="background-image: url(../assets/img/avatar/'.$userphoto.');" class="EDlbXc-x3Eknd-HiaYvf-bN97Pc"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="EDlbXc-x3Eknd-ibnC6b EDlbXc-x3Eknd-fmcmS-haAclf">
                                                                                <div class="EDlbXc-x3Eknd-fmcmS-k77Iif-haAclf">
                                                                                    <span class="EDlbXc-x3Eknd-fmcmS-k77Iif">'.$usernama.'</span>
                                                                                    <!--<span class="EDlbXc-x3Eknd-fmcmS-k77Iif-UjZuef">(Owner)</span>-->
                                                                                </div>
                                                                                <div class="EDlbXc-x3Eknd-fmcmS-K4efff">'.$useremail.'</div>
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
                                                                            <input name="userid" id="userid" maxlength="320" aria-label="UserId to share with" placeholder="Person or email to share with" class="form-control addresspicker" type="text">
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
                   */
                $querytopic = mysql_query("SELECT * FROM topics WHERE topic_cat = '" . $idcat . "' ORDER BY topic_date DESC");
                $num_topics = mysql_num_rows($querytopic);
                $rowtopics = mysql_fetch_array($querytopic);
                $namatopics = $rowtopics['topic_subject'];
                $idtopics = $rowtopics['topic_id'];
                $topicsby = $rowtopics['topic_by'];
                $topicsdate = $rowtopics['topic_date'];
                $topicstime = date("H:i", strtotime($topicsdate));
                $today = date("Y-m-d");
                $diff = abs(strtotime($topicsdate) - strtotime($today));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                if (date("Y-m-d", strtotime($topicsdate)) == date("Y-m-d")) {
                    $topicsdate = 'Hari Ini ' . $topicstime;
                } elseif ($days == '1' and $months == '0' and $years == '0') {
                    $topicsdate = 'Kemarin ' . $topicstime;
                } elseif ($days == '7' and $months == '0' and $years == '0') {
                    $topicsdate = '1 Minggu yang lalu ' . $topicstime;
                } elseif ($days == '14' and $months == '0' and $years == '0') {
                    $topicsdate = '2 Minggu yang lalu ' . $topicstime;
                } elseif ($days == '21' and $months == '0' and $years == '0') {
                    $topicsdate = '3 Minggu yang lalu ' . $topicstime;
                } elseif ($days == '28' and $months == '0' and $years == '0') {
                    $topicsdate = '4 Minggu yang lalu ' . $topicstime;
                } elseif ($months == '1' and $months == '0' and $years == '0') {
                    $topicsdate = '1 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '2' and $years == '0') {
                    $topicsdate = '2 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '3' and $years == '0') {
                    $topicsdate = '3 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '4' and $years == '0') {
                    $topicsdate = '4 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '5' and $years == '0') {
                    $topicsdate = '5 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '6' and $years == '0') {
                    $topicsdate = '6 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '7' and $years == '0') {
                    $topicsdate = '7 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '8' and $years == '0') {
                    $topicsdate = '8 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '9' and $years == '0') {
                    $topicsdate = '9 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '10' and $years == '0') {
                    $topicsdate = '10 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '11' and $years == '0') {
                    $topicsdate = '11 Bulan yang lalu ' . $topicstime;
                } elseif ($months == '12' and $years == '0') {
                    $topicsdate = '12 Bulan yang lalu ' . $topicstime;
                } elseif ($years == '1') {
                    $topicsdate = '1 Tahun yang lalu ' . $topicstime;
                }
                $totalpost = mysql_query("SELECT * FROM posts
					LEFT JOIN topics ON topic_id = post_topic
					WHERE topic_cat = '" . $idcat . "' AND post_num > 1");
                $num_post = mysql_num_rows($totalpost);
                if ($num_post>0) {
                    $num_post = $num_post;
                }

                $user = $_SESSION["User"];
                $queryuser = mysql_query("select * from useraccess where username = '".$user."'");
                $rowuser = mysql_fetch_array($queryuser);
                $username = $rowuser['username'];
                $userid = $rowuser['id'];
                $userphoto = $rowuser['photothumb'];
                $dept = $rowuser['branch'];
                $userlevel = $rowuser['level'];
                $usergroup = $rowuser['supervisor'];
                $queryshare = mysql_query("SELECT * FROM sharedcat WHERE (shared = '".$user."' OR shared ='".$usergroup."') and catid = '".$idcat."'");
                $valid =null;
                while ($rowshare = mysql_fetch_array($queryshare)) {
                    $valid = '1';
                }

                if ($userlevel >= 50) {
                    $lockclass = '';
                    $link = '../topics?idcat=' . $idcat . '';
                    $linktopic = '../topics/viewpost.php?idcat=' . $idcat . '&top='.$idtopics.'';
                    $share = '';
                // $share = '<a id="share" href="?idcat='.$idcat.'#modal-share-'.$idcat.'" data-toggle="modal"> <i class="fa fa-user-plus fa-lg"></i></a>';
                } elseif ($valid=='1') {
                    $lockclass = '';
                    $link = '../topics?idcat=' . $idcat . '';
                    $linktopic = '../topics/viewpost.php?idcat=' . $idcat . '&top='.$idtopics.'';
                    $share = '';
                } else {
                    $lockclass = '';
                    $link = '../topics?idcat=' . $idcat . '';
                    $linktopic = '../topics/viewpost.php?idcat=' . $idcat . '&top='.$idtopics.'';
                    $share = '';
                    // if ($usergroup == $catgroup or $catgroup == 'ALL' or $catgroup == 'HRD') {
                    //     $lockclass = '';
                    //     $link = '../topics?idcat=' . $idcat . '';
                    //     $linktopic = '../topics/viewpost.php?idcat=' . $idcat . '&top='.$idtopics.'';
                    //     $share = '';
                    // } else {
                    //     $lockclass = '<i class="fa fa-lock"></i>';
                    //     $link = "javascript:;";
                    //     $linktopic = 'javascript:;';
                    //     $share = '';
                    // }
                }
                if ($user == $catuserinput or $userlevel >= 99) {
                    $classedit = '<a href="editcat.php?idcat='.$idcat.'"><i class="fa fa-edit text-primary"></i></a>';
                    $classremove = ' <a href="#" onclick="removeCat('.$idcat.')"><i class="fa fa-remove text-danger"></i></a>';
                } else {
                    $classedit = '';
                    $classremove = '';
                }
                echo '<li>
                        <!-- begin media -->
                        <div class="media">
                            <img src="../assets/img/' . $catimg . '" alt="" />
                        </div>
                        <!-- end media -->
                        <!-- begin info-container -->
                        <div class="info-container">
                            <div class="info">
                                <h4 class="title"><a href="'.$link.'">' . $catname . ' </a> '.$lockclass.' '.$classedit.$classremove.'</h4>
                                <p class="desc">
                                    ' . $catdesc . '
                                </p>
                                '.$share.'
                            </div>
                            <div class="total-count">
                                <span class="total-post">' . $num_topics . '</span> <span class="divider">/</span> <span class="total-comment">' . $num_post . '</span>
                            </div>
                            <div class="latest-post">';
                if ($namatopics != "") {
                    echo '<h4 class="title"><a href="'.$linktopic.'">' . $namatopics . '</a></h4>
                                <p class="time">' . $topicsdate . ' by <a href="'.$linktopic.'" class="user"> ' . $topicsby . '</a></p>';
                }

                echo'</div>
                        </div>
                        <!-- end info-container -->
                    </li>';
            }

            echo'</ul>
                <!-- end forum-list -->
                </div>
            </div>
            <!-- end panel-forum -->';
        }
        break;

    case 'topicslist':
        $rs = mysql_query("SELECT * FROM topics WHERE topic_cat = '" . $_POST['cat'] . "' ORDER BY topic_date DESC");
        $num_row = mysql_num_rows($rs);
        $li_row = 1;
        while ($row = mysql_fetch_array($rs)) {
            $topicsname = $row['topic_subject'];
            $topicsby = $row['topic_by'];
            $topicsid = $row['topic_id'];
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
            if ($num_post>0) {
                $num_post = $num_post-1;
            }

            if ($li_row == 1) {
                $data = '<ul class="forum-list forum-topic-list">';
            }
            $data .= '<li>
                                <!-- begin media -->
                                <div class="media">
                                    <img src="../assets/img/avatar/' . $userphoto . '" alt="" />
                                </div>
                                <!-- end media -->
                                <!-- begin info-container -->
                                <div class="info-container">
                                    <div class="info">
                                        <h4 class="title"><a href="viewpost.php?idcat='.$_POST['cat'].'&top=' . $topicsid . '">' . $topicsname . '</a></h4>
                                        <ul class="info-start-end">
                                            <li>post by <a href="detail.html">' . $topicsby . '</a></li>';
            if ($num_post>0) {
                $data .='<li>latest reply <a href="viewpost.php?idcat='.$_POST['cat'].'&top=' . $topicsid . '#'.$postnum.'">' . $postby . '</a></li>';
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
        break;

    case 'topicspost':
        $rs = mysql_query("SELECT * FROM posts WHERE post_topic = '" . $_POST['topicsid'] . "'  ORDER BY post_id ASC");
        $num_row = mysql_num_rows($rs);
        $li_row = 1;
        while ($row = mysql_fetch_array($rs)) {
            $postby = $row['post_by'];
            $postcontent = $row['post_content'];
            $postdate = $row['post_date'];
            $postid = $row['post_id'];
            $postnum = $row['post_num'];
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
            $userphoto = $rowuser['UserPhoto'];
            $username = $rowuser['UserName'];
            $userjabatan = $rowuser['UserJabatan'];

            $querytopic = mysql_query("SELECT * FROM topics WHERE topic_id = '" . $_POST['topicsid'] . "'");
            $rowtopic = mysql_fetch_array($querytopic);
            $catid = $rowtopic['topic_cat'];
            if ($li_row == 1) {
                $data = '<ul class="forum-list forum-detail-list">';
            }

            $data .= '<li><section id="'.$postnum.'"><h2 class="page-header"><a href="#'.$postnum.'"></a></h2>
                            <!-- begin media -->
                            <div class="media">
                                <img src="../assets/img/avatar/'.$userphoto.'" alt="" />
                                <span class="label-control label label-danger">'.$userjabatan.'</span>
                            </div>
                            <!-- end media -->
                            <!-- begin info-container -->
                            <div class="info-container">
                            	<div class="text-right">
                            		<a href="#'.$postnum.'">#'.$postnum.'</a>
				                </div>
                                <div class="post-user"><a href="#">'.$username.'</a></div>
                                <div class="post-content">
                                    '.$postcontent.'
                                </div>
                                <div class="post-time">'.$postdate.'</div>
                            </div>
                            <!-- end info-container -->
                        </section></li>';
            if ($num_row == $li_row) {
                $data .= '</ul>';
            }
            $li_row++;
        }
        echo $data;
        break;

    case 'autocomplete':
        $rs=mysql_query("SELECT username FROM useraccess");
        $num_row = mysql_num_rows($rs);
        $li_row =1;
        $data ="";
        while ($row=mysql_fetch_array($rs)) {
            $nama  = $row['username'];

            $li_row++;

            $data[] = $nama;
            $post_data = json_encode($data);
            $post_data = str_replace(']', '', str_replace('[', '', $post_data));
        }
        echo $post_data;
        break;

  case 'readall':
    session_start();
    $user = $_SESSION['User'];
    $today = date('Y-m-d h:i:s');
    $query = "update notification set readdate = '".$today."' where notifuser = '".$user."'";
    //echo $query;
    $hasil = mysql_query($query);
    if ($hasil) {
        echo '';
    } else {
        echo mysql_error();
    }
  break;

  case 'notif':
    session_start();
    $user = $_SESSION['User'];
    $query = "SELECT * FROM notification WHERE notifuser = '".$user."' and readdate is null ORDER BY noticdate DESC";
    $result = mysql_query($query);
    $count = mysql_num_rows($result);
    if ($result) {
        echo $count;
    }
  break;

  case 'htmlnotif':
    session_start();
    $user = $_SESSION['User'];
    $query = mysql_query("SELECT *,DATEDIFF(now(),noticdate)as datediff,TIMEDIFF(now(),noticdate)as timediff FROM notification WHERE notifuser = '".$user."' and readdate is null ORDER BY noticdate DESC");
    $count = mysql_num_rows($query);
    $html = '<li class="dropdown-header">Notifications ('.$count.')</li> ';

    if ($count > 4) {
        $query_lama = "SELECT *,DATEDIFF(now(),notifdate)as datediff,TIMEDIFF(now(),notifdate)as timediff
                              FROM(
                                SELECT namanotif,
                                      count(namanotif)as count,
                                      icon,
                                      (SELECT noticdate
                                      FROM notification a
                                      WHERE a.notifuser = notification.notifuser and
                                            a.readdate is NULL and
                                            a.namanotif = notification.namanotif
                                            ORDER BY noticdate DESC limit 1)as notifdate
                                FROM notification
                                WHERE notifuser = '".$user."' and
                                      readdate is NULL
                                GROUP BY namanotif
                              )as temp";
        $query = "SELECT namanotif,
                      count(namanotif)as count,
                      icon
                FROM notification
                WHERE notifuser = '".$user."' and
                      readdate is NULL
                GROUP BY namanotif";

        $qnotif = mysql_query($query);
        while ($qnotifr = mysql_fetch_array($qnotif)) {
            $icon = $qnotifr['icon'];
            $namanotif = 'You Have '.$qnotifr['count'].' '.$qnotifr['namanotif'].' Unread';
            /*
            // NOTIFICATION DATE TIME
            $datediff = $qnotifr['datediff'];
            $timediff = $qnotifr['timediff'];
            if($datediff/360 >= 1){
              $notifdate = (string)(round($datediff/360)).' years ago';
            }elseif($datediff/30 >= 1 ){
              $notifdate = (string)(round($datediff/30)).' months ago';
            }elseif($datediff/7 >= 1 ){
              $notifdate = (string)(round($datediff/7)).' weeks ago';
            }elseif($datediff >= 1 ){
              $notifdate = (string)(round($datediff)).' days ago';
            }else{
              if(substr($timediff,0,2) >= 1){
                $notifdate = round(substr($timediff,0,2)).' hours ago';
              }else{
                $notifdate = round(substr($timediff,3,2)).' minutes ago';
              }
            }*/
            $html = $html.'<li class="media">
                            <a href="javascript:;">
                                <div class="media-left">'.$icon.'</div>
                                <div class="media-body">
                                    <h6 class="media-heading">'.$namanotif.'</h6>
                                    <div class="text-muted f-s-11">'.$notifdate.'</div>
                                </div>
                            </a>
                        </li> ';
        }
    } else {
        while ($queryr = mysql_fetch_array($query)) {
            $namanotif = $queryr['namanotif'];
            $namafrom = $queryr['notiffrom'];
            $desc = $queryr['description'];
            $icon = $queryr['icon'];
            //$notifdate = $queryr['noticdate'];
            //$notifdate = date("d-m-Y", strtotime($notifdate));

            $datediff = $queryr['datediff'];
            $timediff = $queryr['timediff'];
            if ($datediff/360 >= 1) {
                $notifdate = (string)(round($datediff/360)).' years ago';
            } elseif ($datediff/30 >= 1) {
                $notifdate = (string)(round($datediff/30)).' months ago';
            } elseif ($datediff/7 >= 1) {
                $notifdate = (string)(round($datediff/7)).' weeks ago';
            } elseif ($datediff >= 1) {
                $notifdate = (string)(round($datediff)).' days ago';
            } else {
                if (substr($timediff, 0, 2) >= 1) {
                    $notifdate = round(substr($timediff, 0, 2)).' hours ago';
                } else {
                    $notifdate = round(substr($timediff, 3, 2)).' minutes ago';
                }
            }

            $html = $html.'<li class="media">
                            <a href="javascript:;">
                                <div class="media-left">'.$icon.'</div>
                                <div class="media-body">
                                    <h6 class="media-heading">'.$namanotif.'</h6>
                                    <p>'.$desc.'</p>
                                    <div class="text-muted f-s-11">'.$notifdate.'</div>
                                </div>
                            </a>
                        </li> ';
        }
    }
    $html=$html.'<li class="dropdown-footer text-center">
                          <a href="../profile?type=profile&tab=notif">View more</a>
                  </li>';
    echo $html;
  break;

  case 'tablenotif':
    session_start();
    $user = $_SESSION['User'];
    $no = 1;
    $qnotif = mysql_query("SELECT * FROM notification WHERE notifuser = '".$user."' ORDER BY noticdate DESC");

    while ($qnotifr = mysql_fetch_array($qnotif)) {
        if (is_null($qnotifr['readdate'])) {
            if (isset($qnotifr['link']) and $qnotifr['link'] != "") {
                $bold = '<a href="../../'.$qnotifr['link'].'" target="_blank" onclick="read('.$qnotifr['id'].')" title="Mark as Read"><b>';
            } else {
                $bold = '<a href="#" onclick="read('.$qnotifr['id'].')" title="Mark as Read"><b>';
            }

            $boldend = '</b></a>';
        } else {
            $bold = '';
            $boldend = '';
        }
        $html = $html.' <tr>
                        <td class="text-center">'.$no.'</td>
                        <td>'.$bold.$qnotifr['namanotif'].$boldend.'</td>
                        <td>'.$bold.$qnotifr['description'].$boldend.'</td>
                        <td class="text-center">'.$bold.$qnotifr['noticdate'].$boldend.'</td>
                        <td class="text-center">'.$bold.$qnotifr['notiffrom'].$boldend.'</td>
                      <tr>';
        $no++;
    }
    echo $html;
  break;

  case "read":
    session_start();
    $user = $_SESSION['User'];
    $id = $_REQUEST['id'];
    $today = date('Y-m-d h:i:s');
    $query = "update notification set readdate = '".$today."' where id = ".$id;
    //echo $query;
    $hasil = mysql_query($query);
    if ($hasil) {
        echo '';
    } else {
        echo mysql_error();
    }
  break;
}
