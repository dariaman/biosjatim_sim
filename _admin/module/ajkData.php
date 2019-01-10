<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// Copyright (C) 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '
<section id="main" role="main">
<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
  <div class="page-header page-header-block">';
switch ($_REQUEST['dt']) {

  case "edit":
    $idpeserta = $_REQUEST['id'];
    $query = "SELECT * FROM vpeserta where id = '".$idpeserta."'";
    $qpes = mysql_fetch_array(mysql_query($query));
    $qpekerjaan = mysql_query("SELECT * FROM ajkprofesi");
    $qins = mysql_query("SELECT * FROM ajkinsurance");

    if($_REQUEST['btnedit']=="procedit"){
      $newas = $_REQUEST['asuransi']; 
      $newprofesi = $_REQUEST['pekerjaan'];
      $newrate = $_REQUEST['rate'];
      $newbunga = $_REQUEST['bunga_bank'];
      $tgltransaksi = $_REQUEST['tgltransaksi'];
      $newpremi = str_replace(",","",$_POST['premi']); 
      $newketerangan = $_REQUEST['keterangan'];
	  
	  
		$target_dir    = "../image/bukti_gambar/";
		$namafile      = date('dmYHis').str_replace(" ", "", basename($_FILES["bukti_gambar"]["name"]));
		$target_file   = $target_dir . $namafile;
		$uploadOk      = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
 
		$check = getimagesize($_FILES["bukti_gambar"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1; $bukti_gambar = $namafile;
		} else {
			$uploadOk = 0; $bukti_gambar = $qpes['bukti_gambar'];
		}
 
		if ($uploadOk == 1) { 
			move_uploaded_file($_FILES["bukti_gambar"]["tmp_name"], $target_file); 
		}
		if(isset($newbunga) or $newbunga != ""){
      
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

      $query = "call sp_reset_cadangan('".$qpes['idpeserta']."','".$newbunga."')";
      // echo $query;
      $result = mysqli_query($link,$query);
    
      while ($row = mysqli_fetch_array($result)){   
          echo $row[0] . " - " . + $row[1]; 
      }
    
      mysqli_close($link);
      
    }	  

    $queryold = "INSERT INTO ajkhispeserta 
                SET idpeserta='".$qpes['idpeserta']."',his='OLD',bungabank= '".$qpes['bunga_bank']."',asuransi='".$qpes['asuransi']."',tgltransaksi='".$qpes['tgltransaksi']."',pekerjaan='".$qpes['pekerjaan']."',rate='".$qpes['premirate']."',premi='".$qpes['premi']."',keterangan='".$qpes['keterangan']."',input_by = '".$q['username']."',input_date=now();";
    $querynew = "INSERT INTO ajkhispeserta 
                SET idpeserta='".$qpes['idpeserta']."',his='NEW',bungabank='$newbunga',asuransi='$newas',tgltransaksi='$tgltransaksi',pekerjaan='$newprofesi',rate='$newrate',premi='$newpremi',keterangan='$newketerangan',input_by='".$q['username']."',input_date=now();";      
            
	  $queryupdate = "UPDATE ajkpeserta
                      SET asuransi = '".$newas."',
                          pekerjaan = '".$newprofesi."',
                          premirate = '".$newrate."',
                          premi = '".$newpremi."',
                          tgltransaksi ='".$tgltransaksi."',
                          totalpremi = '".$newpremi."',
                          keterangan = '".$newketerangan."',
						              bukti_gambar = '".$bukti_gambar."'
                      WHERE idpeserta = '".$qpes['idpeserta']."'";
					  
      // echo $queryold;
      // echo '<br>'.$querynew;
      // echo '<br>'.$queryupdate;
      mysql_query($queryold);
      mysql_query($querynew);
      mysql_query($queryupdate);
      echo '
      <meta http-equiv="refresh" content="2; url=ajk.php?re=data&dt=edtdata">
        <div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Update Success!
        </div>';      
    }
    while ($qpekerjaan_ = mysql_fetch_array($qpekerjaan)) {
      if($qpekerjaan_['ref_mapping'] == $qpes['pekerjaan']){
        $selected = 'selected';
      }else{
        $selected = '';
      }
      $listpekerjaan = $listpekerjaan.'<option value="'.$qpekerjaan_['ref_mapping'].'" '.$selected.'>'.$qpekerjaan_['ref_mapping'].' - '.$qpekerjaan_['nm_profesi'].'</option>';
    }
    
    while ($qins_ = mysql_fetch_array($qins)) {
      if($qins_['id'] == $qpes['asuransi']){
        $selected = 'selected';
      }else{
        $selected = '';
      }
      $listins = $listins.'<option value="'.$qins_['id'].'" '.$selected.'>'.$qins_['name'].'</option>';
    }    

    if($qpes['pekerjaan'] == "BJ"){
      $bunga_bank = '
      <div class="form-group">
        <label class="col-sm-2 control-label">Bunga Bank</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="bunga_bank" value="'.$qpes['bunga_bank'].'" required>
        </div>
      </div>';
    }

    echo '
      <div class="page-header-section"><h2 class="title semibold">'.$qpes['nama'].' - ['.$qpes['idpeserta'].']</h2></div>
        <div class="page-header-section"></div>
      </div>  
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <form action="#" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="col-sm-2 control-label">Plafond</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="plafond" value="'.duit($qpes['plafond']).'" readonly>
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-2 control-label">Tenor</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="tenor" value="'.$qpes['tenor'].'" readonly>
                </div>
              </div>
              <br><hr>
              <br>
              <div class="form-group">
                <label class="col-sm-2 control-label">Asuransi</label>
                <div class="col-sm-10">
                  <!--<input type="text" class="form-control" name="asuransi" id="asuransi" value="'.$qpes['nm_asuransi'].'" required>-->
                  <select name="asuransi" class="form-control" required>
                    '.$listins.'
                  </select>                  
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Pekerjaan</label>
                <div class="col-sm-10">
                  <select name="pekerjaan" class="form-control" required>
                    '.$listpekerjaan.'
                  </select>
                </div>
              </div>
              '.$bunga_bank.'
              <div class="form-group">
                <label class="col-sm-2 control-label">Rate</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="rate" value="'.$qpes['premirate'].'" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Premi</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="premi" name="premi" value="'.$qpes['premi'].'" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Tgl Transaksi</label>
                <div class="col-sm-10">
                  <input type="date" class="form-control" id="tgltransaksi" name="tgltransaksi" value="'.$qpes['tgltransaksi'].'" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Keterangan <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="keterangan" required>'.$qpes['keterangan'].'</textarea>
                </div>
              </div>  
				  
			  <div class="form-group">
                <label class="col-sm-2 control-label">Bukti Gambar</label>
                <div class="col-sm-10">
				   <input type="file" class="form-control" id="bukti_gambar" name="bukti_gambar">
				';
				
				if($qpes['bukti_gambar']){ echo '<br><br><img src="../image/bukti_gambar/'.$qpes['bukti_gambar'].'" style="max-width:250px;"><br><br>'; }
				
			echo '</div>
              </div>';  
			  
            echo  '<input type="hidden" id="btnedit" name="btnedit" value="procedit">

              <div class="panel-footer text-center">
                <button type="submit" class="btn btn-success text-center"><i class="ico-save"></i> Save</button>                    
                <a href="ajk.php?re=data&dt=edtdata" class="btn btn-danger" ><i class="ico-close"></i> Close</a>
              </div>
            </form>
          </div>
        </div>
      </div>    
    </div>

    <script type="text/javascript" src="templates/{template_name}/javascript/jquery.inputmask.bundle.js"></script>
    <script type="text/javascript">  

      $("#premi").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: "", //No Space, this will truncate the first character
        rightAlign: false,
        oncleared: function () { self.Value(""); }
      });  
    </script>';    
  break;

	case "edtdata":
    echo '
      <div class="page-header-section"><h2 class="title semibold">Members</h2></div>
        <div class="page-header-section"></div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
              <thead>
              <tr>
                <th width="1%">No</th>
                <th width="1%">Action</th>
                <th>Product</th>
                <th width="1%">ID Peserta</th>
                <th width="1%">No Pinjaman</th>
                <th>Name</th>
                <th width="1%">DOB</th>
                <th width="1%">Age</th>
                <th width="10%">Plafond</th>
                <th width="10%">Tenor</th>              
                <th width="10%">Tgl Akad</th>
                <th width="10%">Tgl Akhir</th>
                <th width="1%">Premi</th>
                <th>Status</th>
                <th width="1%">Cabang</th>
                <th width="1%">Asuransi</th>
              </tr>
              </thead>
              <tbody>';
                $query = "SELECT * FROM vpeserta WHERE statusaktif in ('Pending','Approve')";
                $metData = $database->doQuery($query);
                while ($metData_ = mysql_fetch_array($metData)) {
                  $idpes = $metData_['id'];
                  echo '
                  <tr>
                    <td align="center">'.++$no.'</td>
                    <td align="center"><a href="ajk.php?re=data&dt=edit&id='.$idpes.'">'.BTN_EDIT.'</a></td>
                    <td align="center">'.$metData_['produk'].'</td>
                    <td align="center">'.$metData_['idpeserta'].'</td>
                    <td align="center">'.$metData_['nopinjaman'].'</td>
                    <td>'.$metData_['nama'].'</td>
                    <td align="center">'._convertDate($metData_['tgllahir']).'</td>
                    <td align="center">'.$metData_['usia'].'</td>
                    <td align="right">'.duit($metData_['plafond']).'</td>
                    <td align="center">'.$metData_['tenor'].'</td>
                    <td align="center">'._convertDate($metData_['tglakad']).'</td>
                    <td align="center">'._convertDate($metData_['tglakhir']).'</td>
                    <td align="right">'.duit($metData_['totalpremi']).'</td>                  
                    <td align="center">'.$metData_['statusaktif'].'</td>
                    <td>'.$metData_['nmcabang'].'</td>
                    <td>'.$metData_['nm_asuransi'].'</td>
                  </tr>';
                }
                echo '
              </tbody>         
            </table>
          </div>
        </div>
      </div>
    </div>';
	break;

	case "pending":
    echo '<div class="page-header-section"><h2 class="title semibold">Pending/Medical Members</h2></div>
          	<div class="page-header-section">
    		</div>
          </div>';

    		//echo '<div class="table-responsive panel-collapse pull out">
    echo '<div class="row">
          	<div class="col-md-12">
            	<div class="panel panel-default">

    <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
    <thead>
    <tr><th width="1%">No</th>
    	<th width="1%">Broker</th>
    	<th>Partner</th>
    	<th>Product</th>
    	<th>Name</th>
    	<th width="1%">DOB</th>
    	<th width="1%">Age</th>
    	<th width="10%">Plafond</th>
    	<th width="10%">Tgl Akad</th>
    	<th width="1%">Tenor</th>
    	<th width="10%">Tgl Akhir</th>
    	<th width="1%">Premium</th>
    	<th>Medical</th>
    	<th>Status</th>
    	<th width="1%">Branch</th>
    </tr>
    </thead>
    <tbody>';
    $metData = $database->doQuery('SELECT ajkpeserta.id,
    ajkcobroker.`name` AS namebroker,
    ajkclient.`name` AS nameclient,
    ajkpolis.produk,
    ajkpeserta.idpeserta,
    ajkpeserta.nomorktp,
    ajkpeserta.nama,
    ajkpeserta.tgllahir,
    ajkpeserta.usia,
    ajkpeserta.plafond,
    ajkpeserta.tglakad,
    ajkpeserta.tenor,
    ajkpeserta.tglakhir,
    ajkpeserta.totalpremi,
    ajkpeserta.astotalpremi,
    ajkpeserta.statusaktif,
    ajkpeserta.medical,
    ajkcabang.`name` AS cabang
    FROM
    ajkpeserta
    INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
    INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
    INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
    INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
    WHERE ajkpeserta.iddn IS NULL AND ajkpeserta.del IS NULL AND ajkpeserta.statusaktif="Pending" '.$q___1.'
    ORDER BY ajkpeserta.input_time DESC');
    while ($metData_ = mysql_fetch_array($metData)) {
    echo '<tr>
       	<td align="center">'.++$no.'</td>
       	<td>'.$metData_['namebroker'].'</td>
       	<td>'.$metData_['nameclient'].'</td>
       	<td align="center">'.$metData_['produk'].'</td>
       	<td>'.$metData_['nama'].'</td>
       	<td align="center">'._convertDate($metData_['tgllahir']).'</td>
       	<td align="center">'.$metData_['usia'].'</td>
       	<td align="right">'.duit($metData_['plafond']).'</td>
       	<td align="center">'._convertDate($metData_['tglakad']).'</td>
       	<td align="center">'.$metData_['tenor'].'</td>
       	<td align="center">'._convertDate($metData_['tglakhir']).'</td>
       	<td align="right">'.duit($metData_['totalpremi']).'</td>
       	<td align="center"><span class="label label-warning">'.$metData_['medical'].'</span></td>
       	<td align="center"><span class="label label-danger">'.$metData_['statusaktif'].'</span></td>
       	<td>'.$metData_['cabang'].'</td>
        </tr>';
    }
    echo '</tbody>
    		<tfoot>
            <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="hidden" class="form-control" name="search_engine" placeholder="Age"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Plafond"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Medical"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
            </tr>
            </tfoot></table>
        	</div>
    		</div>
        </div>
    </div>';
    		;
  break;

  case "ApproveIns":
    $qmember = "SELECT idpeserta,
                       nama,
                       nomorpk,
                       tglakad,
                       plafond,
                       tenor,
                       premi,
                       premirate,
                       aspremi,
                       aspremirate,
                       astotalpremi,
                       tgllunas,
                       statuslunas,
                       usia,
                       tglakhir,
                       tgllahir,
                       ajkpolis.produk,
                       ajkcabang.name as nmcabang,
                       nopinjaman,
                       nm_kategori_profesi,
                       ajkinsurance.name as nmasuransi,
                       round(premi*ajkinsurance.bf/100,2)as bf,
                       round(premi*ajkinsurance.cad_klaim/100,2)as cad_klaim,
                       round(premi*ajkinsurance.cad_premi/100,2)as cad_premi
                FROM ajkpeserta
                INNER JOIN ajkpolis
                ON ajkpolis.id = ajkpeserta.idpolicy
                INNER JOIN ajkcabang
                ON ajkcabang.er = ajkpeserta.cabang
                LEFT JOIN ajkinsurance
                ON ajkinsurance.id = ajkpeserta.asuransi
                LEFT JOIN ajkprofesi
                ON ajkprofesi.ref_mapping = ajkpeserta.pekerjaan
                LEFT JOIN ajkkategoriprofesi
                ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
                WHERE statusaktif = 'Inforce' and
                      checker_by is null";
      $_SESSION['lprmemberasviewapp'] = $thisEncrypter->encode($qmember);

    if ($_REQUEST['btnsubmit']=="submit") {
      $query = '';
      foreach($_REQUEST['idtemp'] as $k => $val){
        $query = "UPDATE ajkpeserta SET checker_by = '".$q['id']."',checker_time='".$today."' WHERE idpeserta = '".$val."'; ";
        mysql_query($query);
      }
    }
    
    echo '<script type="text/javascript" src="templates/{template_name}/javascript/jquery.inputmask.bundle.js"></script>';
    echo '<script>
        $(function(){
          $(".datepicker").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
        });
        </script>';
         
    echo '
      <div class="page-header-section"><h2 class="title semibold">List View Insurance</h2></div>
      <div class="page-header-section"></div>
    </div>
    <div class="row">
    <div class="col-md-12">
    <div class="panel panel-default">
    <a href="ajk.php?re=dlExcel&Rxls=lprviewappins" target="_blank"><img src="../image/excel.png" width="20"><br>Excel</a>

    <form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
    <table class="table table-hover table-bordered table-striped table-responsive" id="">
      <thead>
        <tr>
          <th width="5%"><input type="checkbox" id="selectall"/></th>         
          <th width="5%">ID Peserta</th>
          <th width="5%">Nomor Pinjaman</th>
          <th width="5%">Nama</th>          
          <th width="5%">Tgl Akad</th>
          <th width="2%">Plafond</th>
          <th width="1%">Tenor</th>
          <th width="1%">Pekerjaan</th>
          <th width="1%">Rate As</th>
          <th width="1%">Premi As</th>
          <th width="5%">Cabang</th> 
          <th width="5%">Asuransi</th>          
        </tr>
      </thead>
      <tbody>';

    $metMember = $database->doQuery($qmember);
    while ($metMember_ = mysql_fetch_array($metMember)) {     
      $dataceklist = '<input type="checkbox" class="case" name="idtemp[]" value="'.$metMember_['idpeserta'].'">';
      echo '
      <tr>
        <td align="center">'.$dataceklist.' '.++$no.'</td>
        <td align="center">'.$metMember_['idpeserta'].'</td>
        <td align="center">'.$metMember_['nopinjaman'].'</td>
        <td>'.$metMember_['nama'].'</td>
        <td align="center">'._convertDate($metMember_['tglakad']).'</td>
        <td align="right">'.duit($metMember_['plafond']).'</td>
        <td align="center">'.$metMember_['tenor'].'</td>
        <td align="center">'.$metMember_['nm_kategori_profesi'].'</td>
        <td align="center">'.ROUND($metMember_['aspremirate'],2).'</td>
        <td align="center">'.duit($metMember_['astotalpremi']).'</td>
        <td>'.$metMember_['nmcabang'].'</td>
        <td align="center">'.$metMember_['nmasuransi'].'</td>
      </tr>';  
            
    }
        echo '
              </tbody>
            </table>
            <div class="panel-footer"><input type="hidden" name="btnsubmit" value="submit">'.BTN_SUBMIT.'</div>
            </form>
          </div>
                
          </div>

        </div>
      </div>
    </div> 

    <script language="javascript">
      $(function(){
          $("#selectall").click(function () { $(\'.case\').attr(\'checked\', this.checked); });         // add multiple select / deselect functionality
          $(".case").click(function(){                                  // if all checkbox are selected, check the selectall checkbox // and viceversa
              if($(".case").length == $(".case:checked").length) {
                  $("#selectall").attr("checked", "checked");
              } else {
                  $("#selectall").removeAttr("checked");
              }

          });
      });
    </script>'; 
  break;

  default:
    echo '<div class="page-header-section"><h2 class="title semibold">Members</h2></div>
          	<div class="page-header-section">
    		</div>
          </div>
          <div class="row">
          	<div class="col-md-12">
            	<div class="panel panel-default">
    <table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
    <thead>
    <tr><th width="1%">No</th>
    	<th>Product</th>
    	<th width="1%">Debit Note</th>
    	<th width="1%">ID Member</th>
    	<th>Name</th>
    	<th width="1%">DOB</th>
    	<th width="1%">Age</th>
    	<th width="10%">Plafond</th>
    	<th width="10%">Start Insurance</th>
    	<th width="10%">Tenor</th>
    	<th width="10%">Start Insurance</th>
    	<th width="1%">Premium (Bank)</th>
    	<th width="1%">Premium (Ins)</th>
    	<th>Status</th>
    	<th width="1%">Branch</th>
    </tr>
    </thead>
    <tbody>';
    $metData = $database->doQuery('SELECT
    ajkpeserta.id,
    ajkcobroker.`name` AS namebroker,
    ajkclient.`name` AS nameclient,
    ajkpolis.produk,
    ajkdebitnote.nomordebitnote,
    ajkdebitnote.tgldebitnote,
    ajkpeserta.idpeserta,
    ajkpeserta.nomorktp,
    ajkpeserta.nama,
    ajkpeserta.tgllahir,
    ajkpeserta.usia,
    ajkpeserta.plafond,
    ajkpeserta.tglakad,
    ajkpeserta.tenor,
    ajkpeserta.tglakhir,
    ajkpeserta.totalpremi,
    ajkpeserta.astotalpremi,
    ajkpeserta.statusaktif,
    ajkcabang.`name` AS cabang
    FROM ajkpeserta
    INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
    INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
    INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
    INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
    INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
    WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif!="Pending" OR ajkpeserta.statusaktif!="Upload") '.$q___1.'
    ORDER BY ajkdebitnote.tgldebitnote DESC');
    //WHERE ajkpeserta.iddn IS NOT NULL AND ajkpeserta.del IS NULL AND (ajkpeserta.statusaktif="Inforce" OR ajkpeserta.statusaktif="Lapse" OR ajkpeserta.statusaktif="Maturity") '.$q___1.'
    while ($metData_ = mysql_fetch_array($metData)) {
    if ($metData_['statusaktif'] == "Lapse" OR $metData_['statusaktif'] == "Batal" OR $metData_['statusaktif'] == "Claim") {
    	$metStatus='<span class="label label-danger">'.$metData_['statusaktif'].'</span>';
    }elseif ($metData_['statusaktif']=="Maturity") {
    	$metStatus='<span class="label label-warning">'.$metData_['statusaktif'].'</span>';
    }elseif ($metData_['statusaktif']=="Request") {
    	$metStatus='<span class="label label-teal">'.$metData_['statusaktif'].'</span>';
    }else{
    	$metStatus='<span class="label label-primary">'.$metData_['statusaktif'].'</span>';
    }
    echo '<tr>
       	<td align="center">'.++$no.'</td>
       	<td align="center">'.$metData_['produk'].'</td>
       	<td>'.$metData_['nomordebitnote'].'</td>
       	<td align="center">'.$metData_['idpeserta'].'</td>
       	<td>'.$metData_['nama'].'</td>
       	<td align="center">'.$metData_['tgllahir'].'</td>
       	<td align="center">'.$metData_['usia'].'</td>
       	<td align="right">'.duit($metData_['plafond']).'</td>
       	<td align="center">'.$metData_['tglakad'].'</td>
       	<td align="center">'.$metData_['tenor'].'</td>
       	<td align="center">'.$metData_['tglakhir'].'</td>
       	<td align="right">'.duit($metData_['totalpremi']).'</td>
       	<td align="right">'.duit($metData_['astotalpremi']).'</td>
       	<td align="center">'.$metStatus.'</td>
       	<td>'.$metData_['cabang'].'</td>
        </tr>';
    	}
    echo '</tbody>
    		<tfoot>
            <tr>
            	<th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Debit Note"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="ID Member"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="hidden" class="form-control" name="search_engine" placeholder="Age"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Plafond"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="hidden" class="form-control" name="search_engine"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
                <th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
                </tr>
            </tfoot></table>
        	</div>
    		</div>
        </div>
    </div>';
    		;
} // switch

echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>