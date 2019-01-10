<?php
  include_once('../includes/fu6106.php');

  switch ($_REQUEST['Rtxt']){
    case "putjatim03":
      $query = "SELECT nopinjaman,idpeserta
                FROM ajkpeserta
                WHERE DATE_FORMAT(input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')";
      $put03 = mysql_query($query);

      while ($put03_ = mysql_fetch_array($put03)) {
        $txt = $txt.$put03_['nopinjaman'].'|'.$put03_['idpeserta']."\n";
      }

      $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-03.txt", "x");
      // $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-03.csv", "x");
      fwrite($myfile, $txt);
      fclose($myfile);
      echo "<script>window.close();</script>";
    break;

    case "putjatim04":

  		$query = "SELECT nopinjaman,
  											nilaiclaimclient
  							FROM ajkcreditnote
  									 INNER JOIN ajkpeserta
  									 ON ajkpeserta.id = ajkcreditnote.idpeserta
  							WHERE tipeklaim = 'Restitusi' and
  										DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')";
  		$put04 = mysql_query($query);

  		while ($put04_ = mysql_fetch_array($put04)) {
        $txt = $txt.$put04_['nopinjaman'].'|'.$put04_['nilaiclaimclient']."\n";
  		}

      $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-04.txt", "x");
      // $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-04.csv", "x");
      fwrite($myfile, $txt);
      fclose($myfile);
      echo "<script>window.close();</script>";
  	break;

  	case "putjatim05":

  		$query = "SELECT nopinjaman,
  											status
  							FROM ajkcreditnote
  									 INNER JOIN ajkpeserta
  									 ON ajkpeserta.id = ajkcreditnote.idpeserta
  							WHERE tipeklaim = 'Claim' and
  										DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')";
  		$put05 = mysql_query($query);

  		while ($put05_ = mysql_fetch_array($put05)) {
        $txt = $txt.$put05_['nopinjaman'].'|'.$put05_['status'];
  			$baris++;
  		}

      $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-05.txt", "x");
      // $myfile = fopen("../ftpjatim/adonai".date("Ymd")."-05.csv", "x");
      fwrite($myfile, $txt);
      fclose($myfile);
      echo "<script>window.close();</script>";
  	break;
  }
?>
