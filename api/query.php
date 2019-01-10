<?php 
include "../param.php";
include_once('../includes/functions.php');

   
	$lines = file('data_bjtm_pusat.txt'); 
	echo "<h3>list peserta</h3><hr>";
	foreach ($lines as $line_num => $line){
		/*print $line ."<br>"; */

    $sql = 'SELECT nopinjaman, idpeserta, nama FROM ajkpeserta where nopinjaman = '.$line;
    $result = $conn->query($sql);
    }
    while ($baris = mysql_fetch_array($querygw)) {
        print $line;
    }


?>