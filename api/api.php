<?php  
  $host = "localhost:3362";
  $user = "jatimsql";
  $pass = "ved+-18bios";
  $db   = "biosjatim_sim";

	$link = mysqli_connect($host, $user, $pass, $db);

	if (!$link) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}

	// echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
	// /echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

	$idpes = $_REQUEST['id'];
	if($_REQUEST['er'] == "renewal"){
		$query = "call sp_renewal_karyawan('".$idpes."')";
		// echo $query;
		$result = mysqli_query($link,$query);
	
		while ($row = mysqli_fetch_array($result)){   
				echo $row[0] . " - " . + $row[1]; 
		}
	
		mysqli_close($link);
		echo 'Success';
	}elseif($_REQUEST['er'] == "reset"){
		$bunga = $_REQUEST['bunga'];
		$query = "call sp_reset_cadangan('".$idpes."','".$bunga."')";
		// echo $query;
		$result = mysqli_query($link,$query);
	
		while ($row = mysqli_fetch_array($result)){   
				echo $row[0] . " - " . + $row[1]; 
		}
	
		mysqli_close($link);
		echo 'Success';

	}
	
?>