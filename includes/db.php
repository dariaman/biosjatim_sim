<?php
// ----------------------------------------------------------------------------------
// Copyright (C) 2016 APLIKASI AJK
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
class db{
	var $dbConnection;
	var $dbQuery;
	var $rowResult;

	/**
	 *
	 * @access public
	 * @return query result
	 **/
	function doQuery($sql = '', $dbName = ''){
	if (isset($_REQUEST['debug'])==1) {
	    			echo $sql.'<br />';
	}
	if ($dbName == '') {
		$dbName = dbname;
	}
		if (strtoupper(substr($sql,0,6 )) != 'SELECT') {
		  //$query = mysql_query('INSERT INTO logs (PIC, WAKTU, OPERATION, KOMP) VALUES("'.$_SESSION['username'] .'",NOW(), \''.$sql.'\', "'.$_SERVER['REMOTE_ADDR'].'")');
			//echo mysql_error();
			//exit;
			}

		if ($this->dbQuery = @mysql_db_query($dbName, $sql)) {
      /*if (mysql_num_rows($this->dbQuery)) {
		        $this->rowResult = mysql_num_rows($this->dbQuery);
		    }else{
				$this->rowResult = 0;
			}*/
			return $this->dbQuery;
		}else{
			echo '<i>'.$sql.'</i><br />';
			echo @mysql_error();
			return 0;
		}

	}


	/**
     * Constructor
     * @access protected
     */
	function db(){
//	include_once('configuration.php');
		if ($this->dbConnection = @mysql_connect(hostname, username, password)) {
			if ($selectDB = @mysql_select_db(dbname, $this->dbConnection)) {
			    return 1;
			}else{
				return 0;
			}
		}else{
		    echo @mysql_error();
			return 0;
		}

	}
}
?>