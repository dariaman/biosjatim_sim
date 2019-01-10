<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../koneksi.php";
	include "../sendmail/sendmail.php";

	session_start();
	$userid = $_REQUEST["userid"];
	$catid = $_REQUEST["idcat"];
	$topicid = $_REQUEST["idtop"];
	$user = $_SESSION["Username"];
	$icon = '<i class="fa fa-forumbee media-object bg-green"></i>';
	$today = date("Y-m-d H:i:s");
	$subject = 'Sharing Topics';

		mysql_query("INSERT INTO sharedcattop (shared,catid,topid, usersharedby, inputdate)
		VALUES ('".$userid."','".$catid."','".$topicid."','".$user."','".$today."')");

	$querytop = mysql_query("SELECT * FROM topics WHERE topic_id = '".$topicid."' AND topic_cat = '".$catid."'");
	$rowtop = mysql_fetch_array($querytop);
	$topicdesc = $rowtop['topic_subject'];
	$notifdesc = $topicdesc.' by '.$user;
	mysql_query("INSERT INTO notification (namanotif,description,icon,noticdate,notifuser,notiffrom)
	VALUES ('".$subject."','".$topicdesc."','".$icon."','".$today."','".$userid."','".$user."')");


	$link = "http://".$_SERVER['HTTP_HOST']."/adnoffice/topics/viewpost.php?idcat=".$catid."&top=".$topicid;
	$queryfromshare = mysql_query("SELECT * FROM usermst WHERE (UserID = '".$user."' OR UserGroup ='".$user."')");
	$rowfromshare = mysql_fetch_array($queryfromshare);
	$fnamefrom = $rowfromshare["UserName"];
	$emailfrom = $rowfromshare["UserEmail"];

	$querytoshare = mysql_query("SELECT * FROM usermst WHERE (UserID = '".$userid."' OR UserGroup ='".$userid."') AND UserEmail <> ''");
	$li_row = 1;
	while($rowshare = mysql_fetch_array($querytoshare)){
		$fname = $rowshare["UserName"];
		$email = $rowshare["UserEmail"];
		$datanama[] = $fname;
		$dataemail[] = $email;

		$fnameshare = explode("|", $data);
		$emailshare = explode("|", $email);
		$li_row++;
	};
	$fnameshare = json_encode($datanama);
	$fnameshare = str_replace(']','',str_replace('[','',$fnameshare));
	$fnameshare = str_replace('"','',str_replace(',','|',$fnameshare));

	$emailshare = json_encode($dataemail);
	$emailshare = str_replace(']','',str_replace('[','',$emailshare));
	$emailshare = str_replace('"','',str_replace(',','|',$emailshare));

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
                                                                <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">'.$fnamefrom.' ('.$emailfrom.') shared a topics with you.</div>
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="font-size:0pt;line-height:0pt;text-align:left" height="20"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left"><a href="'.$link.'">'.$topicdesc.'</a></div>
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
                                                                                        <a href="'.$link.'"><span style="color:#fff;text-decoration:none">Open Topics</span></a>
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
                                    <img src="https://ci6.googleusercontent.com/proxy/BK7AIY-0XraZZ2XPgZyQJK_qHXMekI9EcLgWkO5TxA8DmUxK6Wx9Jq4Brg178-kdQ4JJ60VRTXFTN2rnNHPXhrFpQ7V5njXopibJBJ_-8-shYnioX1_BmizkRQ=s0-d-e1-ft#http://contentz.mkt3416.com/ra/2015/38068/03/48297304/empty.gif_6.gif" width="640" height="1" style="min-width:640px" alt="" border="0">
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
	$ls_subject = "[App E-Office] ".$user." shared a topics with you - ".$topicdesc;
	$ls_countemail = $li_row-1;
	$ls_fromname = $fnamefrom;
	$ls_fromemail = $emailfrom;
	$ls_ccname = '';
	$ls_ccmail = '';
	$li_countcc = 0;
	if($emailshare!="" AND $emailshare != 'null'){
		kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
	}

	header("location:../topics?idcat=".$catid);
?>