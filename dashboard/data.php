<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
// require_once('../includes/fu6106.php');
// require_once('../han.php');
require_once('../includes/fu6106.php');
session_start();
$user = $_SESSION['User'];
$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$user."'");
$rowuser = mysql_fetch_array($queryuser);
$cabang = $rowuser['branch'];
$idc = $rowuser['idclient'];

function duit($value)
{
    $orro = number_format($value, 0, ',', '.');
    return $orro;
}


$cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idc.'" AND er="'.$cabang.'"'));
if ($cekCabang['level'] == 1) {
  $cabangverifikasi = '';
}elseif($cekCabang['level'] == 2){
  $cabangverifikasi = " AND regional = '".$cekCabang['idreg']."' ";
}else{
  $cabangverifikasi = " AND cabang = '".$cabang."'";
}

  switch($_POST["functionname"]){
 case 'piedata':
      $qry = 'SELECT count(*) AS jml_peserta, statusaktif 
          FROM vpeserta
          WHERE MONTH(input_time) <= MONTH(NOW())
          AND YEAR(input_time) = YEAR(NOW())
          AND idbroker = "'.$_POST['idbro'].'" 
          AND idclient = "'.$_POST['idclient'].'"
          '.$cabangverifikasi.' 
          GROUP BY statusaktif
          ORDER BY jml_peserta DESC';

      $cnt = mysql_num_rows(mysql_query($qry));
      if($cnt > 0){
        $sql = mysql_query($qry);

        while($row = mysql_fetch_assoc($sql)){
         $jmlpeserta = $row['jml_peserta'];       
          $datapost[] = $jmlpeserta;
          $post = json_encode($datapost);
          $post = str_replace('"','',$post);
        }
      }else{
        $datapost[] = 0;
        $post = json_encode($datapost);
        $post = str_replace('"','',$post);
      }
      echo $post;
    break;

    case 'pielabel':
      $qry = 'SELECT count(*) AS jml_peserta, statusaktif FROM vpeserta
      WHERE MONTH(input_time) <= MONTH(NOW())
      AND YEAR(input_time) = YEAR(NOW())
      AND idbroker = "'.$_POST['idbro'].'" AND idclient = "'.$_POST['idclient'].'"
      '.$cabangverifikasi.'
      GROUP BY statusaktif
      ORDER BY jml_peserta DESC';

      $cnt = mysql_num_rows(mysql_query($qry));
      if($cnt > 0){

        $sql = mysql_query($qry);
        while($row = mysql_fetch_assoc($sql)){
          $statusaktif = $row['statusaktif'];

          $datapost[] = $statusaktif;

          $post = json_encode($datapost);
        }
      }else{
        $datapost[] = 'Null';        
        $post = json_encode($datapost);
      }
      echo $post;
    break;

    case 'piebg':
      $qry = 'SELECT count(*) AS jml_peserta, statusaktif 
              FROM vpeserta
              WHERE MONTH(input_time) <= MONTH(NOW())
                    AND YEAR(input_time) = YEAR(NOW())
                    AND idbroker = "'.$_POST['idbro'].'" AND idclient = "'.$_POST['idclient'].'"
                    '.$cabangverifikasi.'
                    GROUP BY statusaktif
                    ORDER BY jml_peserta DESC';
      $cnt = mysql_num_rows(mysql_query($qry));
      if($cnt > 0){

        $sql = mysql_query($qry);
        $li_row=1;

        while($row = mysql_fetch_assoc($sql)){
          if($li_row==1){
            $bgcolor = '#17B6A4';
          }elseif($li_row==2){
            $bgcolor = '#F04B46';
          }elseif($li_row==3){
            $bgcolor = '#2184DA';
          }elseif($li_row==4){
            $bgcolor = '#ca8c34';
          }elseif($li_row==5){
            $bgcolor = '#F04B46';
          }elseif($li_row==6){
            $bgcolor = '#9b59b6';
          }elseif($li_row==7){
            $bgcolor = '#ca8c34';
          }elseif($li_row==8){
            $bgcolor = '#F04B46';
          }elseif($li_row==9){
            $bgcolor = '#38AFD3';
          }elseif($li_row==10){
            $bgcolor = '#aab3ba';
          }else{
            $bgcolor = '#6FBDD5';
          }
          $datapost[] = $bgcolor;

          $post = json_encode($datapost);
          $li_row++;
        }
      }else{
        $datapost[] = '#6FBDD5';        
        $post = json_encode($datapost);
      }
      echo $post;
    break;

    case 'grapbulan':
      $qry = 'SELECT bulanname, IFNULL(sum(premiclient),0) as totalpremi 
              FROM mstbulan
              LEFT JOIN vpeserta ON idbroker = "'.$_POST['idbro'].'" AND idclient = "'.$_POST['idclient'].'" AND
              mstbulan.bulan = MONTH(tglakad)
              GROUP BY bulanname
              ORDER BY bulan ASC';
      $sql = mysql_query($qry);

      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalpremi = $row['totalpremi'];
        if($totalpremi==null){
          $totalpremi = 0;
        }
        $datapost[] = $bulanname;
        $premipost[] = $totalpremi;

        $post_bln = json_encode($datapost);
      }
      echo $post_bln;
    break;

    case 'grappremium':
      $qry = 'SELECT bulanname, (SELECT SUM(totalpremi) AS totalpremi 
              FROM vpeserta
              WHERE idbroker = "'.$_POST['idbro'].'" AND idclient =  "'.$_POST['idclient'].'"
                    AND id !=""
                    '.$cabangverifikasi.'
                    AND MONTH(tglakad) = mstbulan.bulan
                    AND YEAR(tglakad) = YEAR(NOW()) ) as totalpremi
                    FROM mstbulan
                    GROUP BY bulanname
                    ORDER BY bulan ASC';
      $sql = mysql_query($qry);
      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalpremi = $row['totalpremi'];

        $premipost[] = $totalpremi;

        $post_premi = json_encode($premipost);
      }
      echo $post_premi;
    break;

    case 'grappremiumpaid':
      $qry = 'SELECT bulanname, 
                    (SELECT sum(ifnull(nilaibayar,0)) 
                      FROM vpeserta
                      WHERE idbroker = 1 
                      AND idclient =  1
                      AND MONTH(tglakad) = mstbulan.bulan
                      AND YEAR(tglakad) = YEAR(NOW())) as totalpremi
              FROM mstbulan
              GROUP BY bulanname
              ORDER BY bulan ASC';

      $sql = mysql_query($qry);

      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalpremi = $row['totalpremi'];

        $premipost[] = $totalpremi;

        $post_premi = json_encode($premipost);
      }
      echo $post_premi;
    break;

    case 'grappremiumunpaid':
      $qry = 'SELECT bulanname, 
                    (SELECT sum(premi - ifnull(nilaibayar,0)) 
                      FROM vpeserta
                      WHERE idbroker = 1 
                      AND idclient =  1
                      AND MONTH(tglakad) = mstbulan.bulan
                      AND YEAR(tglakad) = YEAR(NOW())) as totalpremi
              FROM mstbulan
              GROUP BY bulanname
              ORDER BY bulan ASC';    
      // $qry = 'SELECT bulanname, (SELECT IFNULL(SUM(totalpremi),0) AS totalpremi 
      //         FROM ajkdebitnote
      //         LEFT JOIN ajkpeserta ON ajkpeserta.iddn = ajkdebitnote.id
      //         WHERE ajkdebitnote.idbroker = "'.$_POST['idbro'].'" AND ajkdebitnote.idclient =  "'.$_POST['idclient'].'"
      //               AND ajkpeserta.id !=""
      //               '.$cabangverifikasi.'
      //               AND MONTH(tgldebitnote) = mstbulan.bulan
      //               AND YEAR(tgldebitnote) = YEAR(NOW())
      //               AND statuslunas = "0") as totalpremi
      //               FROM mstbulan
      //               GROUP BY bulanname
      //               ORDER BY bulan ASC';
      $sql = mysql_query($qry);

      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalpremi = $row['totalpremi'];

        $premipost[] = $totalpremi;

        $post_premi = json_encode($premipost);
      }
      echo $post_premi;
    break;

    case 'grapplafon':
      $qry = 'SELECT bulanname, IFNULL(sum(plafond),0) as totalplafond 
              FROM mstbulan
              LEFT JOIN vpeserta ON idbroker = "'.$_POST['idbro'].'" AND idclient = "'.$_POST['idclient'].'" AND mstbulan.bulan = MONTH(tglakad)
              WHERE bulan <= MONTH(NOW())
              '.$cabangverifikasi.'
              GROUP BY bulanname
              ORDER BY bulan ASC';

      $sql = mysql_query($qry);
      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalplafond = $row['totalplafond'];

        $plafondpost[] = $totalplafond;

        $post_plafon = json_encode($plafondpost);
      }
      echo $post_plafon;
    break;

    case 'grappeserta':
      $qry = 'SELECT bulanname, IFNULL(count(nama),0) as totalpeserta  
              FROM mstbulan
              LEFT JOIN vpeserta ON idbroker = "'.$_POST['idbro'].'" AND idclient = "'.$_POST['idclient'].'" AND mstbulan.bulan = MONTH(tglakad)
              WHERE bulan <= MONTH(NOW())
              '.$cabangverifikasi.'
              GROUP BY bulanname
              ORDER BY bulan ASC';

      $sql = mysql_query($qry);
      while($row = mysql_fetch_assoc($sql)){
        $bulanname = $row['bulanname'];
        $totalpeserta = $row['totalpeserta'];

        $pertapost[] = $totalpeserta;

        $post_peserta = json_encode($pertapost);
      }

      echo $post_peserta;
    break;
    case 'tipepinjaman':
      
      $produk = mysql_query("SELECT * FROM ajkpolis WHERE del is null");

      $hasil = '<select class="form-control" id="tipepinjaman">';
      while($qproduk = mysql_fetch_array($produk)){
        $hasil = $hasil.'<option value="'.$qproduk['id'].'">'.$qproduk['produk'].'</option>';
      }              
      $hasil = $hasil.'</select>';

      echo $hasil;
    break;

    case 'kalkulatorhitung':
      $karpot = $_POST['valkarpot'];
      $tenor = $_POST['valtenor'];
      $plafond = $_POST['valplafond'];

      $tenor = $tenor * 12;

      $query = 'SELECT * FROM ajkratepremi WHERE '.$tenor.' between tenorfrom and tenorto and idkategoriprofesi = "'.$karpot.'"';
        
      $rate = mysql_fetch_array(mysql_query($query));
      $premi = $rate['rate']/1000*$plafond;
      echo $premi;
    break;
  }
?>