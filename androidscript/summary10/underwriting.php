<?php

include_once "../config.php";
connect_db();

$nmproduk 	= $_POST['nmproduk'];
$age 		= $_POST['age'];
$si 		= $_POST['si'];

$query_percepatan = "
					SELECT * FROM fu_ajk_polis WHERE nmproduk = '$nmproduk'
					";
					
$result_percepatan = query_db($query_percepatan);
$row_pct = mysql_fetch_assoc($result_percepatan);

$id_polis = $row_pct['id'];
$type_produk = $row_pct['type_produk'];

$query = "
			SELECT * FROM fu_ajk_medical 
			WHERE $si BETWEEN si_from AND si_to
			AND $age BETWEEN age_from AND age_to
			AND id_polis = '$id_polis'
		";
$result = query_db($query);
$row = mysql_fetch_assoc($result);

$data['id'] 			= $row['id']; 
$data['id_cost'] 		= $row['id_cost']; 
$data['id_polis']		= $id_polis; 
$data['type_medical'] 	= $row['type_medical']; 
$data['age'] 			= $age; 
$data['age_range'] 		= $row['age_from']." - ".$row['age_to']; 
$data['si'] 			= $si;
$data['si_range'] 		= $row['si_from']." - ".$row['si_to'];
$data['age_from'] 		= $row['age_from']; 
$data['age_to'] 		= $row['age_to']; 
$data['si_from'] 		= $row['si_from']; 
$data['si_to'] 			= $row['si_to']; 
$data['filename'] 		= $row['filename'];

echo json_encode($data);
?>