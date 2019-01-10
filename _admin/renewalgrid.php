<?php
  include "../includes/jjt1502.php";

  session_start();

  $path="https://".$_SERVER['SERVER_NAME']."/";
  $user = $_SESSION['username'];

  $search = isset($_GET['search']) ? $_GET['search'] : '';

  $query = "SELECT a.idpeserta,
                   a.nopinjaman,
                   a.nama,
                   a.nmcabang,
                   ajkcadanganas.duedate,
                   statusaktif,idbroker,
                   ajkcadanganas.nilai_cicilan,
                   DATEDIFF(duedate,CURRENT_DATE())as hari
            FROM vpeserta a
            INNER JOIN ajkcadanganas on ajkcadanganas.idpeserta = a.idpeserta
            WHERE ((statusaktif='Pending' and idbroker = 2) or (statusaktif='Inforce' and idbroker = 1)) and
                  duedate <= DATE_ADD(CURRENT_DATE(),INTERVAL 30 DAY) AND 
                  duedate >= '2018-07-01' and 
                  ajkcadanganas.del is null and
                  (a.nama LIKE '%".$search."%' OR 
                  a.nopinjaman LIKE '%".$search."%' OR 
                  a.nmcabang LIKE '%".$search."%')
            order by duedate,a.nmcabang";
    $_SESSION['rekapjatuhtempo'] = $query;
    $result = mysql_query($query);
    echo '
    <table class="table table-hover">
      <thead>
        <tr>
          <td>No</td>
          <td>No Pinjaman</td>
          <td>Nama</td>
          <td>Cabang</td>
          <td>Tgl. Jth Tempo</td>
          <td>Hari</td>
          <td>Option</td>
        </tr>
      </thead>
      <tbody>';
      $no=1;
      while ($row = mysql_fetch_array($result)) {
        echo 
        '<tr>
          <td>'.$no.'</td>
          <td>'.$row['nopinjaman'].'</td>
          <td>'.$row['nama'].'</td>
          <td>'.$row['nmcabang'].'</td>
          <td>'.$row['duedate'].'</td>
          <td>'.$row['hari'].'</td>
          <!--<td><a href="../api/api.php?id='.$row['idpeserta'].'" class="btn btn-success">Renewal</a></td>-->
          <td><a href="javascript:;" onclick="f_renewal('.$row['idpeserta'].');" class="btn btn-success">Renewal</a></td>
        </tr>';
        $no++;
      }  
      echo  
      '</tbody>
    </table>
    ';
?>