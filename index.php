<?php
	session_start();
	$user = $_SESSION['User'];
	if($user==""){
		header("location:login");
	}else{
		header("location:dashboard");
	}
?>