<?php
include "../param.php";

$today = date('Y-m-d H:i:s');
$nospk = $_REQUEST['nospk'];
$alasan = $_REQUEST['txtalasan'];
$typedata = AES::encrypt128CBC("dataspk",ENCRYPTION_KEY);

if($alasan!=""){
	mysql_query("UPDATE ajkspk SET update_date = '".$today."', update_by = '".$iduser."',statusspk = 'Batal', statusnote = '".$alasan."'
	WHERE nomorspk = '".$nospk."'") or die("Error message = ".mysql_error());
}

header("location:../validasi/?type=".$typedata);

?>