<?php

$fileName = 'data_'.date('Y-m-d').'.xls';
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
			
                echo '
				<table border="1">
				<thead>
				<tr><th width="1%">No</th>
					<th width="1%">Broker</th>
					<th>Partner</th>
					<th>Product</th>
					<th width="1%">Credit Note</th>
					<th width="1%">ID Member</th>
					<th width="1%">Name</th>
					<th width="1%">Plafond</th>
					<th width="1%">Start Insurance</th>
					<th width="1%">Tenor</th>
					<th width="1%">Last Insurance</th>
					<th width="1%">Date Claim</th>
					<th width="10%">Payment Claim</th>
					<th width="10%">Status</th>
					<th width="10%">Branch</th>
				</tr>
				</thead>
				<tbody>';
                $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS namebroker,
		ajkclient.`name` AS nameclient,
		ajkpolis.produk,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.nomorcreditnote,
		ajkcreditnote.`status`,
		ajkcreditnote.tglbayar,
		ajkcreditnote.tglklaim,
		ajkcreditnote.status,
		ajkcreditnote.nilaiclaimclient
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.status != "Request" AND ajkcreditnote.tipeklaim IN ("Refund","Topup") AND ajkcreditnote.del IS NULL '.$q___1.'
		ORDER BY ajkcreditnote.id DESC');
                while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
                    if ($metCreditnote_['status']=="Process") {
                        $metglow = 'info';
                    } elseif ($metCreditnote_['status']=="Batal" or $metCreditnote_['status']=="Cancel") {
                        $metglow = 'danger';
                    } elseif ($metCreditnote_['status']=="Investigation") {
                        $metglow = 'warning';
                    } elseif ($metCreditnote_['status']=="Approve Unpaid") {
                        $metglow = 'primary';
                    } elseif ($metCreditnote_['status']=="Approve Paid") {
                        $metglow = 'success';
                    } else {
                        $metglow = 'warning';
                    }
                    echo '<tr>
				   	<td align="center">'.++$no.'</td>
				   	<td>'.$metCreditnote_['namebroker'].'</td>
				   	<td>'.$metCreditnote_['nameclient'].'</td>
				   	<td align="center">'.$metCreditnote_['produk'].'</td>
				   	<td align="center"><a href="ajk.php?re=dlPdf&pdf=dlPdfcn&cID='.$thisEncrypter->encode($metCreditnote_['nomorcreditnote']).'&idc='.$thisEncrypter->encode($metCreditnote_['id']).'" target="blank">'.$metCreditnote_['nomorcreditnote'].'</a></td>
				   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
				   	<td align="center">'.$metCreditnote_['nama'].'</td>
				   	<td align="right">'.duit($metCreditnote_['plafond']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakad']).'</td>
				   	<td align="center">'.$metCreditnote_['tenor'].'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakhir']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
				   	<td align="right">'.duit($metCreditnote_['nilaiclaimclient']).'</td>
				   	<td align="center">'.$metCreditnote_['status'].'</td>
				   	<td>'.$metCreditnote_['cabang'].'</td>
				    </tr>';
                }
                echo '</tbody> </table>';
				
?>