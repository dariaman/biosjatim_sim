<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2016-05-26

 ********************************************************************/
include "../koneksi.php";
session_start();
$isAvailable = true;

switch ($_POST['type']) {
    case 'username':
        $username = $_POST['username'];
          $rs=mysql_query("SELECT * FROM  useraccess WHERE  username = '".$username."' ");
        if ($row=mysql_fetch_array($rs)) {
            $userid =$row['username'];
            $isAvailable = true;
            $_SESSION["User"] = $userid;
            break;
        } else {
            $isAvailable = false; // or false
            break;
        }
        // no break
    case 'password':
        $pass = md5($_POST["password"]);
        $ls_user = $_SESSION['User'];
        $rs = mysql_query("SELECT * FROM useraccess WHERE username = '".$ls_user."' AND passw = '".$pass."'");
        if (mysql_fetch_array($rs)) {
            $isAvailable = true;
            break;
        } else {
            $isAvailable = false; // or false
            break;
        }
}

// Finally, return a JSON
echo json_encode(array(
    'valid' => $isAvailable,
));
