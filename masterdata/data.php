<?php
include "../param.php";

if(isset($_POST['action']))
{
	$action = $_POST['action'];
}else if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
if($action == 'datapeserta')
{
	
	
	// storing  request (ie, get/post) global array to a variable
	$requestData= $_REQUEST;
	
	///echo $requestData['search']['value']; exit;
	
	
	$aColumns = array('ajkpeserta.idpeserta', 'produk','ajkpeserta.nopinjaman','ajkpeserta.idpeserta','nama','tgllahir','usia','plafond','tglakad','tenor','tglakhir','totalpremi','status','cabang','asuransi');
	$columns = array(
		// datatable column index  => database column name
		0 =>'ajkpeserta.id',	
		1 =>'produk',
		2 =>'ajkpeserta.nopinjaman',
		3 =>'ajkpeserta.idpeserta',
		4 =>'nama',
		5 =>'tgllahir',
		6 =>'usia',
		7 =>'plafond',
		8 =>'tglakad',
		9 =>'tenor',
		10 =>'tglakhir',
		11 =>'totalpremi',
		12 =>'status',
		13 =>'cabang',
		14 =>'asuransi'
	);

	$cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idclient.'" AND er="'.$cabang.'"'));
	if ($cekCabang['level'] == 1) {
		$cabangverifikasi = '';
	}elseif($cekCabang['level'] == 2){
		$cabangverifikasi = " AND ajkpeserta.regional = '".$cekCabang['idreg']."'";
	}else{
		$cabangverifikasi = " AND ajkpeserta.cabang = '".$cabang."'";
	}
		$sql = "SELECT
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
				ajkpeserta.statuspeserta,
				ajkpeserta.nopinjaman,
				ajkcabang.`name` AS cabang,
				ajkpeserta.idpolicy,
				ajkclient.id,
				ajkinsurance.name as asuransi
				FROM ajkpeserta
				INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
				INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				LEFT JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
				INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
				LEFT JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
				WHERE ajkpeserta.del IS NULL
				AND ajkpeserta.idbroker = '".$idbro."' AND 
						ajkpeserta.idclient = '".$idclient."' 
						".$cabangverifikasi."";
		
		 
	$query=mysql_query($sql);
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	/*
	   * Paging
	*/
	$sLimit = "";
	if ( isset( $requestData['start'] ) && $requestData['length'] != '-1' )
	{
		//$sLimit = " LIMIT ".$requestData['start']." ,".$requestData['length']."  " ;
		$sLimit = " LIMIT ".intval( $requestData['start'] ).", ".
			intval( $requestData['length'] );
	}


	/*
	   * Ordering
	*/
	$sOrder = "";
	if ( isset( $requestData['iSortCol_0'] ) ){
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $requestData['iSortingCols'] ) ; $i++ ){
			if ( $requestData[ 'bSortable_'.intval($requestData['iSortCol_'.$i]) ] == "true" ){
				$sOrder .= $aColumns[ intval( $requestData['iSortCol_'.$i] ) ]."".($requestData['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}

		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" ){
			$sOrder = "";
		}
	}

	if( $requestData['search']['value']!=="" ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( ajkcobroker.`name` LIKE '%".$requestData['search']['value']."%'  ";
		$sql.=" OR produk LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR nomordebitnote LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR idpeserta LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR ajkpeserta.nama LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR tgllahir LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR usia LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR plafond LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR tglakad LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR tenor LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR statusaktif LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR tglakhir LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR REPLACE(totalpremi,',','') LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR cabang LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR ajkpeserta.nopinjaman LIKE '%".$requestData['search']['value']."%' ";		
	    $sql.=" OR ajkinsurance.name LIKE '%".$requestData['search']['value']."%' )";
	}

	 
	
	
	$query=mysql_query($sql);
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	$sql.="ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."$sLimit";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$totalpremi = 0;
	$totalpflafon = 0;
	$li_row = $requestData['start']+1;
	$query=mysql_query($sql);

	$data = array();
	while( $row=mysql_fetch_array($query) ) {  // preparing an array
		$nestedData=array();
		$idproduk = $row['idpolicy'];
		$klientidpeserta = $row['id'];
		$pesertabroker = $row['namebroker'];
		$pesertapartner = $row['nameclient'];
		$namaprod = $rowprod['produk'];
		$nomordn = $row['nomordebitnote'];
		$idmember = $row['idpeserta'];
		$namapeserta = $row['nama'];
		$tgllahir = $row['tgllahir'];
		$tgllahir = date('d-m-Y', strtotime($tgllahir));
		$usia = $row['usia'];
		$plafond = $row['plafond'];
		$plafond_format = number_format($plafond,0,'.',',');
		$tglakad = $row['tglakad'];
		$tglakad = date('d-m-Y', strtotime($tglakad));
		$tenor = $row['tenor'];
		$tenor_format = number_format($tenor,0,'.',',');
		$tglakhir = $row['tglakhir'];
		$tglakhir = date('d-m-Y', strtotime($tglakhir));
		$totalpremi = $row['totalpremi'];
		$totalpremi_format = number_format($totalpremi,0,'.',',');
		$statusaktif = $row['statusaktif'];
		$statuspeserta = $row['statuspeserta'];

		$queryprod = mysql_query("SELECT * FROM ajkpolis WHERE id = '".$idproduk."' AND idcost = '".$klientidpeserta."'");
		$rowprod = mysql_fetch_array($queryprod);		

		$querycn = mysql_query("SELECT * FROM ajkcreditnote WHERE idpeserta = '".$idmember."' and del is NULL");
		$rowcn = mysql_num_rows($querycn);		



		if($statuspeserta != ""){
			// $stat = ' - '.$statuspeserta;
			$status = '<span class="label label-warning">'.$statusaktif.' - '.$statuspeserta.'</span>';
		}else{
			if($rowcn > 0){
				// $stat = ' - Req Meninggal';
				$status = '<span class="label label-warning">'.$statusaktif.' - Req Meninggal</span>';
			}else{
				// $stat = '';	
				if($statusaktif=='Inforce'){
					$status = '<span class="label label-success">'.$statusaktif.' '.$stat.'</span>';
				}else{
					$status = '<span class="label label-danger">'.$statusaktif.' '.'</span>';
				}				
			}
		}

		// if($statusaktif=='Inforce'){
		// 	$status = '<span class="label label-success">'.$statusaktif.' '.$stat.'</span>';
		// }else{
		// 	$status = '<span class="label label-danger">'.$statusaktif.' '.'</span>';
		// }

		$nestedData[] = $li_row;
		$nestedData[] = $row["produk"];
		$nestedData[] = $row["nopinjaman"];
		$nestedData[] = $row["idpeserta"];
		$nestedData[] = $row["nama"];
		$nestedData[] = _convertDate($row['tgllahir']);
		$nestedData[] = $row["usia"];
		$nestedData[] = duit($row['plafond']);
		$nestedData[] = _convertDate($row['tglakad']);
		$nestedData[] = $row["tenor"];
		$nestedData[] = _convertDate($row['tglakhir']);
		$nestedData[] = duit($row['totalpremi']);
		$nestedData[] = $status;
		$nestedData[] = $row["cabang"];
		$nestedData[] = $row["asuransi"];
		// $nestedData[] = $sql;

		$data[] = $nestedData;
		$li_row++;
	}

	$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);  // send data as json format
}elseif($action == 'datadebitnote') {
  $requestData= $_REQUEST;
  // echo $requestData['search']['value']; exit;
  $aColumns = array('ajkdebitnote.id','produk','ajkinsurance.name','tgldebitnote','nomordebitnote','jData','premiclient','paidstatus','paidtanggal','cabang');
  $columns = array(
    // datatable column index  => database column name
    0 =>'ajkdebitnote.id',
    1 =>'produk',
    2 =>'ajkinsurance.name',
    3 =>'tgldebitnote',
    4 =>'nomordebitnote',
    5 =>'jData',
    6 =>'premiclient',
    7 =>'paidstatus',
    8 =>'paidtanggal',
    9 =>'cabang'
  );  
  
  $cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idclient.'" AND er="'.$cabang.'"'));
  if ($cekCabang['level'] == 1) {
    $cabangdebitnote = '';
  } elseif ($cekCabang['level'] == 2) {
    $cabangdebitnote = " AND ajkdebitnote.idregional = '".$cekCabang['idreg']."'";
  } else {
    $cabangdebitnote = " AND ajkdebitnote.idcabang = '".$cabang."'";
  }

  $sql = "SELECT
  Count(ajkpeserta.nama) AS jData,
  ajkcobroker.`name` AS namebroker,
  ajkclient.`name` AS nameclient,
  ajkpolis.produk,
  ajkcabang.`name` AS cabang,
  ajkdebitnote.id,
  ajkdebitnote.nomordebitnote,
  ajkdebitnote.premiclient,
  ajkdebitnote.paidstatus,
  ajkdebitnote.paidtanggal,
  ajkdebitnote.tgldebitnote,
  ajkdebitnote.idproduk,
  ajkclient.id AS idclient,
  ajkinsurance.name as asname
  FROM ajkdebitnote
  INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
  INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
  INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
  INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
  INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
  INNER JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
  WHERE ajkdebitnote.del IS NULL
  AND ajkdebitnote.idbroker = ".$idbro." AND ajkdebitnote.idclient = ".$idclient." ".$cabangdebitnote." GROUP BY ajkdebitnote.id ";

  $query=mysql_query($sql);
  $totalData = mysql_num_rows($query);
  $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

  /*
     * Paging
  */
  $sLimit = "";
  if (isset($requestData['start']) && $requestData['length'] != '-1') {
      //$sLimit = " LIMIT ".$requestData['start']." ,".$requestData['length']."  " ;
      $sLimit = " LIMIT ".intval($requestData['start']).", ".
          intval($requestData['length']);
  }

  /*
     * Ordering
  */
  $sOrder = "";
  if (isset($requestData['iSortCol_0'])) {
    $sOrder = " ORDER BY  ";
    for ($i=0 ; $i<intval($requestData['iSortingCols']) ; $i++) {
        if ($requestData[ 'bSortable_'.intval($requestData['iSortCol_'.$i]) ] == "true") {
            $sOrder .= $aColumns[ intval($requestData['iSortCol_'.$i]) ]."".($requestData['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
        }
    }

    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == " ORDER BY") {
        $sOrder = "";
    }
  }
  
  if ($requestData['search']['value']!=="") {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql.=" AND ( ajkcobroker.`name` LIKE '%".$requestData['search']['value']."%'  ";
    $sql.=" OR ajkclient.`name` LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkpolis.produk LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkcabang.`name` LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkdebitnote.nomordebitnote LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR REPLACE(ajkdebitnote.premiclient,',','') LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkdebitnote.paidstatus LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkdebitnote.paidtanggal LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkdebitnote.tgldebitnote LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ajkinsurance.name LIKE '%".$requestData['search']['value']."%' )";
  }

  $query=mysql_query($sql);
  $totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
  $sql.="  ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."$sLimit";
  
  // $totalpremi = 0;
  
  

  $query=mysql_query($sql);

  $data = array();
  $i = $requestData['start']+1;
  
  while ($row = mysql_fetch_array($query)) {
    $nestedData = array();    
    $idprod = $row['idproduk'];
    $dnklient = $row['id'];
    $namaprod = $row['produk'];    
    $iddn = $row['id'];
    $nomordn = '<a href="../_admin/ajk.php?re=dlPdf&pID='.metEncrypt($iddn).'&logDN='.metEncrypt("B").'" target="_blank">'.$row['nomordebitnote'].'</a>';
    $tgldn = $row['tgldebitnote'];
    $tgldn = date('d-m-Y', strtotime($tgldn));
    $statupaid = $row['paidstatus'];
    $tglpaid = $row['paidtanggal'];
    $asname = $row['asname'];
    if ($tglpaid=="" or $tglpaid == null or $tglpaid == '0000-00-00') {
        $tglpaid = '';
    } else {
        $tglpaid = date('d-m-Y', strtotime($tglpaid));
    }
    $jdata = '<a href="../_admin/ajk.php?re=dlPdf&pdf=member&pID='.metEncrypt($iddn).'&logMB='.metEncrypt("B").'" target="_blank">'.$row['jData'].'</a>';
    // $jdata = $row['jData'];
    $premiclient = $row['premiclient'];
    $premiasuransi = $row['premiasuransi'];
    $dncabang = $row['cabang'];
    if ($statupaid=="Unpaid") {
        $statusp = '<span class="label label-danger">'.$statupaid.'</span>';
    } else {
        $statusp = '<span class="label label-success">'.$statupaid.'</span>';
    }         

    $nestedData[] = $i;
    $nestedData[] = $namaprod;
    $nestedData[] = $asname;
    $nestedData[] = $tgldn;
    $nestedData[] = $nomordn;
    $nestedData[] = $jdata;
    $nestedData[] = duit($premiclient);
    $nestedData[] = $statusp;
    $nestedData[] = $tglpaid;
    $nestedData[] = $dncabang;

    $data[] = $nestedData;
    $i++;
  }
  // echo "<pre>";
  // print_r($data);exit;
        

  $json_data = array(
    "draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal"    => intval($totalData),  // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
  );   
  echo json_encode($json_data);  // send data as json format
}
else
{
	$json['err_no'] = '1';
	$json['err_msg'] = 'No action found.'.$action;

	echo json_encode($json);
}


?>
