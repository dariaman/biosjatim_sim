<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
    include "../koneksi.php";
    include "../includes/functions.php"; //added by chrismanuel at 20180312
    ini_set('session.gc_maxlifetime', 84600);
    $expireTime = 60*60*24*100; // 100 days
    session_cache_expire(84600);
    session_set_cookie_params($expireTime);
    session_start();
    $username = $_REQUEST["username"];
    $password = md5($_REQUEST["password"]);
    $today = date("Y-m-d");
    $time = date("H:i:s");

    $isSessionExist = isset($_SESSION["uid"]) ? $_SESSION["uid"] : '';
    setUserLog($isSessionExist, 'logout');

    $rs = mysql_query("SELECT * FROM useraccess
    	WHERE  username = '".$username."'
    	AND passw = '".$password."'
    	AND (idbroker is null AND idclient is null)
    	UNION
    	SELECT * FROM useraccess
    	WHERE  username = '".$username."'
    	AND passw = '".$password."'
	AND idclient is not null ");
    if ($row = mysql_fetch_array($rs)) {
        $userid =$row['username'];
        $uid =$row['id'];
        $_SESSION["User"] = $userid;
        $_SESSION["uid"] = $uid;
        $_SESSION['EXPIRED'] = time();
        $_SESSION["level"] = $row['level'];

        $qry = mysql_query("SELECT * FROM ajkuserlogin WHERE user_id = ".$uid.' AND level <> 99');
        $isOnline = mysql_num_rows($qry);

        if ($isOnline > 0) {
            session_destroy();
            $pesan = 'Anda sudah login di browser lain.';
            header("location:../login?pesan=".$pesan);
        } else {
            setUserLog($_SESSION["uid"], 'login');
            header("location:../dashboard");
        }
    } else {
        //$pesan = AES::encrypt128CBC('Anda tidak punya akses dihalaman ini silahkan kontak administrator',ENCRYPTION_KEY);
        // $pesan = $username;
        $pesan = 'Username atau password anda salah.';
        //$pesan = "OK";
        header("location:../login?pesan=".$pesan);
    }
