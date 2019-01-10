<?php
// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
// exit;
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
    include "../param.php";
    // include "../sendmail/sendmail.php";
    session_start();
    $comment = $_REQUEST["postcomment"];
    $idtopics = $_REQUEST["idtop"];
    $category = $_REQUEST["idcat"];
    $user = $_REQUEST["usertemp"];
    if ($user=="") {
        $user = $_SESSION["User"];
    }

    $file_name1 = $_FILES['files1']['name'];
    $file_tmp1 = $_FILES['files1']['tmp_name'];
    $file_name2 = $_FILES['files2']['name'];
    $file_tmp2 = $_FILES['files2']['tmp_name'];

    $today = date("Y-m-d H:i:s");
    $new_file_name1 ="";
    $new_file_name2 ="";
    $foldername = date("M", strtotime($today)).date("y", strtotime($today));
    $path = '../assets/documents/discussion/'.$foldername;

    $querytop = mysql_query("SELECT * FROM topics WHERE topic_id = '".$idtopics."' AND topic_cat = '".$category."'");
    $rowtop = mysql_fetch_array($querytop);
    $topicdesc = $rowtop['topic_subject'];
    $topicby = $rowtop['topic_by'];

    $qtuser = mysql_query("SELECT * FROM useraccess WHERE username = '".$topicby."'");
    $rowtuser = mysql_fetch_array($qtuser);
    $topics_name = $rowtuser['username'];
    $topics_email = $rowtuser['email'];

    $queryfromuser = mysql_query("SELECT * FROM useraccess WHERE username = '".$user."'");
    $rowfromuser = mysql_fetch_array($queryfromuser);
    $fnamefrom = $rowfromuser["username"];
    $emailfrom = $rowfromuser["email"];

    $link = "http://".$_SERVER['HTTP_HOST']."/adnoffice/topics/viewpost.php?idcat=".$category."&top=".$idtopics;
if (!file_exists($path)) {
    mkdir($path, 0777);
    chmod($path, 0777);
}

    if ($comment !="") {
        if ($file_name1!="") {
            $file1_info = pathinfo($file_name1);
            $file1_extension = strtolower($file1_info["extension"]); //image extension
            $file1_name_only = strtolower($file1_info["filename"]);//file name only, no extension
            // $queryfilepost = mysql_query("SELECT * FROM posts WHERE post_by = '".$user."'");
            // $num_file = mysql_num_rows($queryfilepost);
            // $num_file = $num_file+1;
            $unique = uniqid();

            $new_file_name1 = $user.'-'.$file1_name_only. '-' .  $unique . '.' . $file1_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name1;
            move_uploaded_file($file_tmp1, $destination_folder) or die("Could not upload file!");
        }
        if ($file_name2!="") {
            $file2_info = pathinfo($file_name2);
            $file2_extension = strtolower($file2_info["extension"]); //image extension
            $file2_name_only = strtolower($file2_info["filename"]);//file name only, no extension
            $unique = uniqid();

            $new_file_name2 = $user.'-'.$file2_name_only. '-' .  $unique . '.' . $file2_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name2;
            move_uploaded_file($file_tmp2, $destination_folder) or die("Could not upload file!");
        }
        $querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$idtopics."'");
        $num_post = mysql_num_rows($querypost);
        $totalpost = $num_post +1;
        mysql_query("INSERT INTO posts (post_num,post_content, post_date, post_topic,post_attachment1,post_attachmentname1,post_attachment2,post_attachmentname2,post_by) VALUES ('".$totalpost."','".$comment."','".$today."','".$idtopics."','".$new_file_name1."','".$file_name1."','".$new_file_name2."','".$file_name2."','".$user."')");


        $querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$idtopics."' AND post_by <> '".$user."' GROUP BY post_by");
        $li_row = 1;
        while ($rowpost = mysql_fetch_array($querypost)) {
            $postby = $rowpost['post_by'];
            $quserpost = mysql_query("SELECT * FROM useraccess WHERE username = '".$postby."'");
            $qruserpost = mysql_fetch_array($quserpost);

            $fname = $qruserpost["username"];
            $email = $qruserpost["email"];
            $datanama[] = $fname;
            $dataemail[] = $email;

            $fnameshare = explode("|", $datanama);
            $emailshare = explode("|", $dataemail);
            $li_row++;
        };
        $fnameshare = json_encode($datanama);
        $fnameshare = str_replace(']', '', str_replace('[', '', $fnameshare));
        $fnameshare = str_replace('"', '', str_replace(',', '|', $fnameshare));

        $emailshare = json_encode($dataemail);
        $emailshare = str_replace(']', '', str_replace('[', '', $emailshare));
        $emailshare = str_replace('"', '', str_replace(',', '|', $emailshare));

        $ls_body ='<div class=msg>
    <div>
        <p>
            <span></span>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table width="600" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="27"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="10"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left">Aplikasi E-Office - Adonai</span>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="2"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td align="right" valign="bottom">&nbsp;</td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="20"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#454545"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#444444"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#434343"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#414141"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#404040"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3f3f3f"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3e3e3e"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="4"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="70"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left">
                                                            <img src="https://ci5.googleusercontent.com/proxy/tWRQYQFs_wApiLP45Pzc4slyFjYBwdz0NPux5uhiFPqFL9RGjFSqPH80w3Jwp57PB2Xqn06YE9adDuSnNbgkCOL9V6zNh3ZZIphWTjnbXzNNC13LCp_PZQprSrEuHq2HSJMS=s0-d-e1-ft#https://lmicreativeteam.files.wordpress.com/2015/02/arrow_image_wordpress.jpg" border="0" width="65" height="22" alt="">
                                                            </td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="40"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="19"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="14"></td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">'.$fnamefrom.' ('.$emailfrom.') reply a topics.</div>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="20"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">'.$comment.'</div>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="20"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table border="0" cellspacing="0" cellpadding="0" bgcolor="#4CB7EF">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="22"></td>
                                                                            <td>
                                                                                <div style="color:#a6a6a6;font-family:Arial;font-size:16px;line-height:20px;text-align:center;font-weight:bold">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:0pt;line-height:0pt;text-align:left" height="8"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <a name="-4908059960092879720_BodyButton1" style="color:#fff;text-decoration:none" rel="noreferrer">
                                                                                        <a href="'.$link.'"><span style="color:#fff;text-decoration:none">Comment</span></a>
                                                                                    </a>
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:0pt;line-height:0pt;text-align:left" height="8"></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td width="22"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="20"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#676767;font-family:Arial;font-size:10px;line-height:12px;text-align:left"></div>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="43"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="19"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="20"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#d7d7d7">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="20"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="15"></td>
                                                            <td width="526">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table width="97%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left">&nbsp;</td>
                                                                            <td align="right">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="17"></td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="22"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="18"></td>
                                                            <td width="560">
                                                                <table width="101%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="14">&nbsp;</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="25">
                                                                                <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left"> App E-Office Adonai All Right Reserved 2016 </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" width="22"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="font-size:0pt;line-height:0pt;text-align:left">
                                    <img src=https://ci6.googleusercontent.com/proxy/BK7AIY-0XraZZ2XPgZyQJK_qHXMekI9EcLgWkO5TxA8DmUxK6Wx9Jq4Brg178-kdQ4JJ60VRTXFTN2rnNHPXhrFpQ7V5njXopibJBJ_-8-shYnioX1_BmizkRQ=s0-d-e1-ft#http://contentz.mkt3416.com/ra/2015/38068/03/48297304/empty.gif_6.gif" width="640" height="1" style="min-width:640px" alt="" border="0">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span></span>
                </p>
                <img src="https://ci5.googleusercontent.com/proxy/KLhZ-o3tihSSlArUI-0TPdBQmbGrj_ZhdOxrlpPePi-I_8-oFO14Eyk-2P47lvc845gO8tb1JdJT6KziEu0JfhwgEngNTw3PTO7cIG36Us_Gz5TW0WRVDe2lgTxOJsvsGEb-DSshM1IU=s0-d-e1-ft#http://links.e.logmein.com/open/log/48297304/NzIwNDc4MDY1NTMS1/0/NjQzMDYzMDMyS0/1/0">
                </div>
            </div>';
        $ls_toemail = $emailshare;
        $ls_toname = $fnameshare;
        $ls_subject = "[App E-Office] ".$user." reply a topics - ".$topicdesc;
        $ls_countemail = $li_row-1;
        $ls_fromname = $fnamefrom;
        $ls_fromemail = $emailfrom;
        $ls_ccname = '';
        $ls_ccmail = '';
        $li_countcc = 0;
        // if ($topicby <> $user) {
        //     kirimemail($ls_fromname, $ls_fromemail, $ls_toname, $ls_toemail, $ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject, $ls_body);
        // }
    }


    header("location:viewpost.php?idcat=".$category."&top=".$idtopics."#".$totalpost);
