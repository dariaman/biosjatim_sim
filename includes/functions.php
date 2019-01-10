<?php
/*
 ----------------------------------------------------------------------------------
Copyright (C) JANUARI 2016 APLIKASI AJK PENSIUN
Original Author Of File : Rahmad
E-mail :kepodank@gmail.com
YM(Yahoo Messenger) : penting_kaga
----------------------------------------------------------------------------------
*/

include_once('../includes/setupConfigured.php');
//penambahan tanggal //
function add_days($my_date, $numdays)
{
    $date_t = strtotime($my_date.' UTC');
    return gmdate('Y-m-d', $date_t + ($numdays*86400));
}
//penambahan tanggal //
// DOLLAR //
function num2words($num, $c=1)
{
    $ZERO = 'zero';
    $MINUS = 'minus';
    $lowName = array(
         /* zero is shown as "" since it is never used in combined forms */
         /* 0 .. 19 */
         "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

    $tys = array(
         /* 0, 10, 20, 30 ... 90 */
         "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

    $groupName = array(
         /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

    $divisor = array(
         /* How many of this group is needed to form one of the succeeding group. */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

    $num = str_replace(",", "", $num);
    $num = number_format($num, 2, '.', '');
    $cents = substr($num, strlen($num)-2, strlen($num)-1);
    $num = (int)$num;

    $s = "";

    if ($num == 0) {
        $s = $ZERO;
    }
    $negative = ($num < 0);
    if ($negative) {
        $num = -$num;
    }
    // Work least significant digit to most, right to left.
    // until high order part is all 0s.
    for ($i=0; $num>0; $i++) {
        $remdr = (int)($num % $divisor[$i]);
        $num = $num / $divisor[$i];
        // check for 1100 .. 1999, 2100..2999, ... 5200..5999
        // but not 1000..1099,  2000..2099, ...
        // Special case written as fifty-nine hundred.
        // e.g. thousands digit is 1..5 and hundreds digit is 1..9
        // Only when no further higher order.
        if ($i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5) {
            if ($remdr > 0) {
                $remdr = ($num * 10);
                $num = 0;
            } // end if
        } // end if
       if ($remdr == 0) {
           continue;
       }
        $t = "";
        if ($remdr < 20) {
            $t = $lowName[$remdr];
        } elseif ($remdr < 100) {
            $units = (int)$remdr % 10;
            $tens = (int)$remdr / 10;
            $t = $tys [$tens];
            if ($units != 0) {
                $t .= "-" . $lowName[$units];
            }
        } else {
            $t = num2words($remdr, 0);
        }
        $s = $t." ".$groupName[$i]." ".$s;
        $num = (int)$num;
    } // end for
    $s = trim($s);
    if ($negative) {
        $s = $MINUS . " " . $s;
    }

    // SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
    if ($c == 1) {
        $s .= " dollars";
    }

    return $s;
} // end num2words
// DOLLAR //
// DOLLAR //
function num2wordsdollar($num, $c=1)
{
    $ZERO = 'zero';
    $MINUS = 'minus';
    $lowName = array(
         /* zero is shown as "" since it is never used in combined forms */
         /* 0 .. 19 */
         "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

    $tys = array(
         /* 0, 10, 20, 30 ... 90 */
         "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

    $groupName = array(
         /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

    $divisor = array(
         /* How many of this group is needed to form one of the succeeding group. */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

    $num = str_replace(",", "", $num);
    $num = number_format($num, 2, '.', '');
    $cents = substr($num, strlen($num)-2, strlen($num)-1);
    $num = (int)$num;

    $s = "";

    if ($num == 0) {
        $s = $ZERO;
    }
    $negative = ($num < 0);
    if ($negative) {
        $num = -$num;
    }
    // Work least significant digit to most, right to left.
    // until high order part is all 0s.
    for ($i=0; $num>0; $i++) {
        $remdr = (int)($num % $divisor[$i]);
        $num = $num / $divisor[$i];
        // check for 1100 .. 1999, 2100..2999, ... 5200..5999
        // but not 1000..1099,  2000..2099, ...
        // Special case written as fifty-nine hundred.
        // e.g. thousands digit is 1..5 and hundreds digit is 1..9
        // Only when no further higher order.
        if ($i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5) {
            if ($remdr > 0) {
                $remdr = ($num * 10);
                $num = 0;
            } // end if
        } // end if
        if ($remdr == 0) {
            continue;
        }
        $t = "";
        if ($remdr < 20) {
            $t = $lowName[$remdr];
        } elseif ($remdr < 100) {
            $units = (int)$remdr % 10;
            $tens = (int)$remdr / 10;
            $t = $tys [$tens];
            if ($units != 0) {
                $t .= "-" . $lowName[$units];
            }
        } else {
            $t = num2words($remdr, 0);
        }
        $s = $t." ".$groupName[$i]." ".$s;
        $num = (int)$num;
    } // end for
    $s = trim($s);
    if ($negative) {
        $s = $MINUS . " " . $s;
    }

    // SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
    if ($c == 1) {
        $s .= "";
    }

    return $s;
}// end num2words
// DOLLAR //
//DOLLAR KOMA//
function num2wordskoma($num, $c=1)
{
    $ZERO = 'zero';
    $MINUS = 'minus';
    $lowName = array(
         /* zero is shown as "" since it is never used in combined forms */
         /* 0 .. 19 */
         "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

    $tys = array(
         /* 0, 10, 20, 30 ... 90 */
         "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

    $groupName = array(
         /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

    $divisor = array(
         /* How many of this group is needed to form one of the succeeding group. */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

    $num = str_replace(",", "", $num);
    $num = number_format($num, 2, '.', '');
    $cents = substr($num, strlen($num)-2, strlen($num)-1);
    $num = (int)$num;

    $s = "";

    if ($num == 0) {
        $s = $ZERO;
    }
    $negative = ($num < 0);
    if ($negative) {
        $num = -$num;
    }
    // Work least significant digit to most, right to left.
    // until high order part is all 0s.
    for ($i=0; $num>0; $i++) {
        $remdr = (int)($num % $divisor[$i]);
        $num = $num / $divisor[$i];
        // check for 1100 .. 1999, 2100..2999, ... 5200..5999
        // but not 1000..1099,  2000..2099, ...
        // Special case written as fifty-nine hundred.
        // e.g. thousands digit is 1..5 and hundreds digit is 1..9
        // Only when no further higher order.
        if ($i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5) {
            if ($remdr > 0) {
                $remdr = ($num * 10);
                $num = 0;
            } // end if
        } // end if
        if ($remdr == 0) {
            continue;
        }
        $t = "";
        if ($remdr < 20) {
            $t = $lowName[$remdr];
        } elseif ($remdr < 100) {
            $units = (int)$remdr % 10;
            $tens = (int)$remdr / 10;
            $t = $tys [$tens];
            if ($units != 0) {
                $t .= "-" . $lowName[$units];
            }
        } else {
            $t = num2words($remdr, 0);
        }
        $s = $t." ".$groupName[$i]." ".$s;
        $num = (int)$num;
    } // end for
    $s = trim($s);
    if ($negative) {
        $s = $MINUS . " " . $s;
    }

    // SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
    if ($c == 1) {
        $s .= " cents";
    }

    return $s;
} // end num2words
//DOLLAR KOMA//
//RUPIAH//
function mametbilang($x)
{
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima","enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12) {
        $temp = " ". $angka[$x];
    } elseif ($x <20) {
        $temp = mametbilang($x - 10). " belas";
    } elseif ($x <100) {
        $temp = mametbilang($x/10)." puluh". mametbilang($x % 10);
    } elseif ($x <200) {
        $temp = " seratus" . mametbilang($x - 100);
    } elseif ($x <1000) {
        $temp = mametbilang($x/100) . " ratus" . mametbilang($x % 100);
    } elseif ($x <2000) {
        $temp = " seribu" . mametbilang($x - 1000);
    } elseif ($x <1000000) {
        $temp = mametbilang($x/1000) . " ribu" . mametbilang($x % 1000);
    } elseif ($x <1000000000) {
        $temp = mametbilang($x/1000000) . " juta" . mametbilang($x % 1000000);
    } elseif ($x <1000000000000) {
        $temp = mametbilang($x/1000000000) . " milyar" . mametbilang(fmod($x, 1000000000));
    } elseif ($x <1000000000000000) {
        $temp = mametbilang($x/1000000000000) . " trilyun" . mametbilang(fmod($x, 1000000000000));
    }
    return $temp;
}
//RUPIAH//

function initCalendar()
{
    $teks = <<<HEREDOC
    			<style type="text/css">@import url("javascript/jscalendar/calendar.css");</style>
    			<script src="javascript/jscalendar/calendar.js" type="text/javascript"></script>
    			<script src="javascript/jscalendar/lang/calendar-id.js" type="text/javascript"></script>
    			<script src="javascript/jscalendar/calendar-setup.js" type="text/javascript"></script>
HEREDOC;
    return $teks;
}
function calendarBox($id = 'tanggal', $button = 'trigger', $default = '', $str = '<img border="0" src="image/b_calendar.png" width="10" height="10">', $act='')
{
    $teks = '';
    $teks .= "\t<input type=\"text\" id=\"$id\" name=\"$id\" \"$act\" value=\"$default\" class=\"input-xmini\" />\n";
    $teks .= "\t<button id=\"$button\">$str</button>\n";
    $teks .= "\t<script type=\"text/javascript\">\n";
    $teks .= "\t\tCalendar.setup({inputField: \"$id\", ifFormat: \"%d-%m-%Y\", button: \"$button\"});\n";
    $teks .= "\t</script>\n";
    return $teks;
}

//Indonesia dd/mm/yyyy
function _convertDate($date)
{
    if (empty($date)) {
        return null;
    }
    $date = explode("-", $date);
    return
    $date[2] . '-' . $date[1] . '-' . $date[0];
}
function _convertDate2($date)
{
    if (empty($date)) {
        return null;
    }
    $date = explode("/", $date);
    return
    $date[2] . '-' . $date[1] . '-' . $date[0];
}
function _convertDate3($date)
{
    if (empty($date)) {
        return null;
    }

    $date = explode("-", $date);
    return
    $date[2] . '/' . $date[1] . '/' . $date[0];
}

function _convertDate4($date)
{
    if (empty($date)) {
        return null;
    }
    return substr($date, -8, 4).'-'.substr($date, 4, -2).'-'.substr($date, -2);
}

//English mm/dd/yyyy
function _convertDateEng($date)
{
    if (empty($date)) {
        return null;
    }
    $date = explode("-", $date);
    return
    $date[2] . '-' . $date[1] . '-' . $date[0];
}
function _convertDateEng2($date)
{
    if (empty($date)) {
        return null;
    }
    $date = explode("/", $date);
    return
    $date[2] . '-' . $date[0] . '-' . $date[1];
}
function _convertDateEng3($date)
{
    if (empty($date)) {
        return null;
    }

    $date = explode("-", $date);
    return
    $date[1] . '/' . $date[2] . '/' . $date[0];
}

function viewBulan($date)
{
    $bulan=array("01"=>"Jan","02"=>"Feb","03"=>"Mrc","04"=>"Apr","05"=>"May","06"=>"Jun",
             "07"=>"Jul","08"=>"Agst","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Desc");
    if (empty($date)) {
        return null;
    }

    $date = explode("-", $date);
    $buln=$bulan[$date[1]];
    return
    $date[2] . '-' . $buln . '-' . $date[0];
}
function arrayCombine($a = array(), $b = array())
{
    foreach ($a as $key => $value) {
        $c[$value] = $b[$key];
    }
    return $c;
}
function isValidDate($date)
{
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
        if (checkdate($matches[2], $matches[3], $matches[1])) {
            return true;
        }
    }
}
function connect()
{
    $GLOBALS["connect"] = @mysql_connect(hostname, username, password) or die("Can't connect to database" . mysql_error());
    mysql_select_db(dbname);
} //function connect
function query($command)
{
    if (!isset($GLOBALS["connect"])) {
        connect();
    } // if
    if ($_REQUEST['debug'] == 1) {
        // $query = ('INSERT INTO logs (username, waktu, operation, PC) VALUES("' . $_SESSION['nama'] . '","' . date('D,d F Y  H:i:s') . '", "' . $command . '", "' . $_SERVER['REMOTE_ADDR'] . '")');
        echo '<pre>' . $command . '<br>' . $query . '</pre>';
    }
    if (strtoupper(substr($command, 0, 6)) != 'SELECT') {
        // $query = mysql_query('INSERT INTO logs (username, waktu, operation, PC) VALUES("' . $_SESSION['nama'] . '","' . date('D,d F Y  H:i:s') . '","' . $command . '","' . $_SERVER['REMOTE_ADDR'] . '")');
        // echo mysql_error();
        // exit;
    }

    $query = mysql_query($command);
    return $query;
} //function query
function back()
{
    return "<a href=\"javascript:history.back(1)\"><img src=\"image/Backward-64.png\" width=\"20\"></a>";
}
function alerte()
{
    ob_start('ob_tidyhandler');
    echo '<script language="JavaScript"> window.location=\'' . $_ENV['HTTP_REFERER'] . '\' window.alert(\'Anda tidak berhak melakukan operasi ini !\'); </script>';
    ob_end_flush();
}
function anti_injection($data)
{
    $filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter;
}
function metEncrypt($str)
{
    $kuncen = '1234567890-=QWERTYUIOP{}|ASDFGHJKL:"ZXCVBNM<>?/.,mnbvcxz\';lkjhgfdsa\][poiuytrewq+_)(*&^%$#@!';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kuncen, ($i % strlen($kuncen))-1, 1);
        $karakter = chr(ord($karakter)+ord($kuncikarakter));
        $hasil .= $karakter;
    }
    return urlencode(base64_encode($hasil));
}
function metDecrypt($str)
{
    $str = base64_decode(urldecode($str));
    $hasil = '';
    $kuncen = '1234567890-=QWERTYUIOP{}|ASDFGHJKL:"ZXCVBNM<>?/.,mnbvcxz\';lkjhgfdsa\][poiuytrewq+_)(*&^%$#@!';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kuncen, ($i % strlen($kuncen))-1, 1);
        $karakter = chr(ord($karakter)-ord($kuncikarakter));
        $hasil .= $karakter;
    }
    return $hasil;
}
function showHead()
{
    return '
<link href="themes/' . theme . '/styles/style.css" rel="stylesheet" type="text/css">
<script src="includes/js/TreeView/ua.js" type="text/javascript"></script>
<script src="includes/js/TreeView/ftiens4.js" type="text/javascript"></script>
<script src="includes/js/TreeView/links.js" type="text/javascript"></script>

';
}

function createPageNavigations($file = '', $total = 0, $psDeh = 10, $anchor = '', $perPage = 12)
{
    $tmp = '<table align="center" cellpadding="0" cellspacing="0">
			<tr><td>';
    $perPage == 0 ? $rowPage = rowsPerPage : $rowPage = $perPage;
    $pages = '';
    $m = 0;
    strpos($file, '?') ? $file = explode('?', $file) : $file[0] = $file;

    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    $_REQUEST['sp'] ? $ps = $_REQUEST['sp'] : $ps = 1;
    $anchor == '' ? $anchor = '' : $anchor = '#' . $anchor;

    if ($ps == 1) {
        $prev = '';
        $end = '<a href="' . $file[0] . '?sp=' . ($ps + 1) . '&x=11' . $anchor . '">' . _NEXT . ' </a>';
    } else {
        $prev = '<a href="' . $file[0] . '?sp=1&x=1&' . $file[1] . $anchor . '">1 ... </a>&nbsp;
				<a href="' . $file[0] . '?sp=' . ($ps-1) . '&x=' . (($ps-1) * $psDeh - $psDeh) . '&' . $file[1] . $anchor . '">' . _PREV . '</a> |';
    }

    if ($ps < ceil($total / $rowPage / $psDeh)) {
        $end = '<a href="' . $file[0] . '?sp=' . ($ps + 1) . '&x=' . ($ps * $psDeh) . '&' . $file[1] . $anchor . '">' . _NEXT . '</a>...
				 <a href="' . $file[0] . '?sp=' . (ceil($total / $rowPage / $psDeh)) . '&x=' . (ceil($total / $rowPage)) . '&' . $file[1] . $anchor . '">' . ceil($total / $rowPage) .'</a>';
    } else {
        $end = '';
    }

    for ($i = ($ps-1) * 10 ; $i <= (($ps-1) * 10) + 10 && $i <= ceil($total / $rowPage); $i++) {
        if ($i <> 0) {
            if ($i == $pageNow) {
                $pages .= '<span style="background-color: #AAAAFF; font-weight: bold;">' . $i . '</span> | ';
            } else {
                $pages .= '<a href="' . $file[0] . '?x=' . $i . '&sp=' . $ps . '&' . $file[1] . $anchor . '">' . $i . '</a> | ';
            }
        }
    } // for
    // initialization gitu loh
    $tmp .= $prev . $pages . $end;
    $tmp .= '</td></tr></table>';
    return $tmp;
}
function rowClass($i, $j = 0)
{
    if ($i % 2 == 1) {
        $clash = "tableentry1";
    } else {
        $clash = "tableentry2";
    }
    if ($j == 1) {
        if ($i % 2 == 1) {
            $clash = "#FEFEFE";
        } else {
            $clash = "#EAF7FF";
        }
    }
    return $clash;
}
function pilih($data, $value)
{
    if ($data == $value) {
        return 'checked';
    }
    return;
}
function _selected($x, $y)
{
    return ($x==$y)?'selected':'';
}
function duit($value)
{
    $orro = number_format($value, 0, ',', '.');
    return $orro;
}
function duitkoma($value)
{
    $orro = number_format($value, 0, ',', '.');
    return $orro;
}
function duitdollar($amount)
{
    return number_format($amount, 2, '.', ',');
}
/*
function duitdollar($number) {
   if ($number < 0) {
     $print_number = "( " . str_replace('-', '', number_format ($number, 2, ".", ",")) . ")";
    } else {
     $print_number = " " .  number_format ($number, 0, ".", ",") ;
   }
   return $print_number;
}
*/
function generateHTML($value, $queryResult, $op = 'view', $name = '', $editable = '')
{
    if ($op == 'edit') {
        switch ($value['type']) {
            case 'date':
                if ($value['format']) {
                    $temp = createDateSelector($value['field'] . $name, 15, date($value['format'], strtotime($queryResult[$value['field']])));
                } else {
                    $temp = createDateSelector($value['field'] . $name, 15, $queryResult[$value['field']]);
                }

                break;
            case 'none':
                $temp = '<' . $value['view'] . '>' . $queryResult[$value['field']] . '</' . $value['view'] . '> <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            case 'radio':
                foreach ($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= '<input name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '" type="radio" value="' . $key . '" checked onClick="' . $value['event'] . '" "' . $editable . '">' . $i . ' &nbsp; ';
                    } else {
                        $option .= '<input name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '"  type="radio" value="' . $key . '" onClick="' . $value['event'] . '" "' . $editable . '">' . $i . ' &nbsp; ';
                    }
                }

                $temp = $option . '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            case 'select':
                foreach ($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= '<option value="' . $key . '" selected>' . $i . '</option>';
                    } else {
                        $option .= '<option value="' . $key . '">' . $i . '</option>';
                    }
                }
                $temp = '<select name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '" ' . $value['on'] . '>' . $option . '</select> <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            // nyelipin dikit aah !:D
            case 'file':
                $temp = '<input type="file" name="' . $value['field'] . $name . '" value="' . $queryResult[$value['field'] . $name] . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" "' . $editable . '" > <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            default:
            case 'text':

                $temp = '<input type="text" name="' . $value['field'] . $name . '" value="' . $queryResult[$value['field'] . $name] . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" ' . $value[onEvent] . '> ';
                if ($value['add']) {
                    $temp .= '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                }

                break;

            case 'password':

                $temp = '<input type="password" name="' . $value['field'] . $name . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" ' . $value[onEvent] . '> <span style="font-size:8.5px;">' . $value['add'] . '</span>';

                break;

            case 'textarea':
                $temp = '<textarea name="' . $value['field'] . $name . '" cols="' . $value['size'] . '">' . $queryResult[$value['field']] . '</textarea> ' . $value['add'] . '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;

            case 'hide':
                $temp = $queryResult[$value['field']];
                break;
            case 'checkbox':
                foreach ($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= $value['add'] . '<div id="' . $key . '"><input name="' . $key . $name . '"   type="checkbox" value="' . $key . '" checked onClick="' . $value['event'] . '" "' . $editable . '">' . $i . $value['addc'] . '</div>';
                    } else {
                        $option .= $value['add'] . '<div id="' . $key . '"><input name="' . $key . $name . '"    type="checkbox" value="' . $key . '" onClick="' . $value['event'] . '" "' . $editable . '">' . $i . $value['addc'] . '</div>';
                    }
                }

                $temp = $option;
                break;
        } // switch
    } else {
        if (is_array($value)) {
            if (array_key_exists('view', $value)) {
                $temp = '<' . $value['view'] . '>' . $queryResult[$value['field']] . '</' . $value['view'] . '> ' . $value['add'];
            } elseif ($value['type'] == 'date') {
                if ($queryResult[$value['field']]) {
                    $temp = dateFormat($queryResult[$value['field']]) ;
                } else {
                    $temp = '' ;
                }
            } elseif ($value['sign']) {
                $temp = $value['sign'] . ' ' . number_format($queryResult[$value['field'] . $name], '', ',', '.') . ' ' . $value['add'];
            } else {
                // $value['add'] != ''? $temp = '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="right" valign="bottom" width="50%">' . $queryResult[$value['field'] . $name] . '&nbsp;</td><td valign="bottom">&nbsp;' . $value['add'] . '</tr></table>' : $temp = $queryResult[$value['field'] . $name];
            }
        } else {
            $temp = $queryResult[$value];
        }
    }
    return $temp;
}

function pagination($table, $order, $searchstring, $pre, $pos, $nav, $page, $pages)
{
    ///////////////////////
    //  Get Current Url  //
    ///////////////////////
    $webpage = basename($_SERVER['PHP_SELF']);
    global $webpage;

    ////////////////////////
    //  Sorter and Pagination Query Begin  //
    /////////////////////////////////////////
    //$pre = $_REQUEST['pre'];
    //$pos = $_REQUEST['pos'];
    //$nav = $_REQUEST['nav'];
    //$page = $_REQUEST['page'];
    //$pages = $_REQUEST['pages'];


    ///////////////////////////////////////////
    //  Set Initial Pre Pos and Page Limits  //
    ///////////////////////////////////////////
    if ($pre == "" and $pos == "" and $page == "") {
        $pre = 0;
        $pos = 9;
        $page = 1;
    }


    ///////////////////////////////
    //  User Navigates Previous  //
    ///////////////////////////////
    if ($nav == "prev") {
        $pre = ($pre - 10);
        $pos = ($pos - 10);
        $page = ($page - 1);
    }


    ///////////////////////////
    //  User Navigates Next  //
    ///////////////////////////
    if ($nav == "next") {
        $pre = ($pre + 10);
        $pos = ($pos + 10);
        $page = ($page + 1);
    }


    /////////////////////////////
    //  If page number to low  //
    /////////////////////////////
    if ($page < 1) {
        $pre = 0;
        $pos = 9;
        $page = 1;
    }

    //////////////////////////////
    //  If page number to high  //
    //////////////////////////////
    if ($page > $pages) {
        $pre = 0;
        $pos = 9;
        $page = 1;
    }


    //////////////////////////////////////////
    //  Select for total number or results  //
    //////////////////////////////////////////
    $r = "SELECT DISTINCT * FROM $table $searchstring";
    $re = mysql_query($r) or die("error 12547");
    $nums = mysql_num_rows($re);


    ////////////////////////////////////////////
    //  Select for current displayed results  //
    ////////////////////////////////////////////
    $request = "SELECT DISTINCT * FROM $table $searchstring ORDER BY $order DESC LIMIT $pre, 10";
    $result = mysql_query($request) or die("error 25352");
    $num = mysql_num_rows($result);


    ///////////////////////////////////////
    //  Determine total number of pages  //
    ///////////////////////////////////////
    $pages = ceil($nums/10);


    /////////////////////////////////
    //  Create Navigation Display  //
    /////////////////////////////////
    $navigation_old = "
 $nums entries on $pages Page(s)<br>
 <a href=\"$webpage?page=$page&nav=prev&pre=$pre&pos=$pos&pages=$pages&view=view\">Previous</a> |
 Page $page |
 <a href=\"$webpage?page=$page&nav=next&pre=$pre&pos=$pos&pages=$pages&view=view\">Next</a><br>
 Results $pre
 ";

    $navigation = "
 $nums Record on $pages Page(s)<br>
 <a href=\"$webpage?page=$page&nav=prev&pre=$pre&pos=$pos&pages=$pages&view=view\">Previous</a> |
 Page $page |
 <a href=\"$webpage?page=$page&nav=next&pre=$pre&pos=$pos&pages=$pages&view=view\">Next</a><br>
 Results $request
 ";
    //Results $pre - $pos
    /////////////////////////////////
    //  Create Paginagtion Array   //
    /////////////////////////////////
    // result is the result of the limited query
    $pagination = array($navigation, $result, $num, $pre);


    /////////////////////////////////
    //  Return Paginagtion Array   //
    /////////////////////////////////
    return $pagination;
}//end function

function gambar_kecil($direktori, $file_type)
{
    list($width, $height)= getimagesize($direktori);

    $max_width = 800; // lebar maksimal
    $max_height = 800; // tinggi maksimal


    if ($width>$max_width) {
        $scale = (float)$max_width/(float)$width;
        $new_width = (int) $width*$scale;
        $new_height = (int) $height*$scale;
    }


    if ($height>$max_height) {
        $scale = (float)$max_height/(float)$height;
        $new_width = (int) $width*$scale;
        $new_height = (int) $height*$scale;
    }


    if ($width<2) {
        $new_width = 2;
    }
    if ($height<2) {
        $new_height = 2;
    }

    //---------- memeriksa tipe file --------------//
    if (($file_type=="image/jpg")or ($file_type=="image/pjpeg")or($file_type=="image/jpeg")) {
        $src_img = imagecreatefromjpeg($direktori);
    }
    if (($file_type=="image/png")or($file_type=="image/x-png")) {
        $src_img = imagecreatefrompng($direktori);
    }
    if ($file_type=="image/gif") {
        $src_img = imagecreatefromgif($direktori);
    }



    $image_p = imagecreatetruecolor($new_width, $new_height);
    imagecopyresized($image_p, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


    if (($file_type=="image/jpg")or ($file_type=="image/pjpeg")or($file_type=="image/jpeg")) {
        imagejpeg($image_p, $direktori);
    }
    if (($file_type=="image/png")or($file_type=="image/x-png")) {
        imagepng($image_p, $direktori);
    }
    if ($file_type=="image/gif") {
        imagegif($image_p, $direktori);
    }
}

function metImage($uploadName)
{
    $direktori          = "../myFiles/_photo/";
    $direktoriThumb     = "../myFiles/_photo/";
    $file               = $direktori.$uploadName;

    //simpan gambar ukuran sebenernya
    $realImagesName     = $_FILES['fileImage']['tmp_name'];
    move_uploaded_file($realImagesName, $file);

    //identitas file gambar
    $realImages             = imagecreatefromjpeg($file);
    $width                  = imageSX($realImages);
    $height                 = imageSY($realImages);

    //simpan ukuran thumbs
    $thumbWidth     = 150;
    $thumbHeight    = ($thumbWidth / $width) * $height;

    //mengubah ukuran gambar
    $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled($thumbImage, $realImages, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    //simpan gambar thumbnail
    imagejpeg($thumbImage, $direktoriThumb."thumb_".$uploadName);

    //hapus objek gambar dalam memori
    imagedestroy($realImages);
    imagedestroy($thumbImage);
}

//HURUF VOKAL DAN KONSONAN
function countConsonant($strString = "")
{
    $strBuffer = strtolower(preg_replace('/s-/', '', $strString));
    $intLen = strlen($strBuffer);
    $arrVowel = array("a", "i", "u", "e", "o");
    $intConsonant = 0;
    for ($i = 0; $i <= $intLen - 1; $i++) {
        if (!in_array($strBuffer[$i], $arrVowel)) {
            $intConsonant++;
        }
    }
    return $intConsonant;
}
function countVowel($strString = "")
{
    $strBuffer = strtolower(preg_replace('/s-/', '', $strString));
    $intLen = strlen($strBuffer);
    $intVowel = 0;
    $arrVowel = array("a", "i", "u", "e", "o");
    for ($i = 0; $i <= $intLen - 1; $i++) {
        if (in_array($strBuffer[$i], $arrVowel)) {
            $intVowel++;
        }
    }
    return $intVowel;
}
function countLetter($strString = "")
{
    return strlen(preg_replace('/s-/', '', $strString));
}
//HURUF VOKAL DAN KONSONAN

function createRandomPassword()
{
    $chars = "abcdefghijkmnopqrstuvwxyz023456789" ;
    srand((double) microtime()* 1000000);
    $i = 0 ;
    $pass = '' ;
    while ($i <= 8) {
        $num = rand() % 33 ;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp ;
        $i ++;
    }
    return $pass ;
}

/*
function datediff($tgl1, $tgl2){
    $tgl1 = strtotime($tgl1);
    $tgl2 = strtotime($tgl2);
    $diff_secs = abs($tgl1 - $tgl2);
    $base_year = min(date("Y", $tgl1), date("Y", $tgl2));
    $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
    return array( "years" => date("Y", $diff) - $base_year, "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1, "months" => date("n", $diff) - 1, "days_total" => floor($diff_secs / (3600 * 24)), "days" => date("j", $diff) - 1, "hours_total" => floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total" => floor($diff_secs / 60), "minutes" => (int) date("i", $diff), "seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff) );
}
*/

function datediffmonth($date_1, $date_2, $differenceFormat = '%y,%m,%d')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval = date_diff($datetime1, $datetime2);
    $result = $interval->format($differenceFormat);
    $rresult = explode(',', $result);
    return ($rresult[0]*12) + $rresult[1];
}



function datediff($time1, $time2, $precision = 6)
{
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
        $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
        $time2 = strtotime($time2);
    }

    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
        $ttime = $time1;
        $time1 = $time2;
        $time2 = $ttime;
    }

    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();

    // Loop thru all intervals
    foreach ($intervals as $interval) {
        // Create temp time from time1 and interval
        $ttime = strtotime('+1 ' . $interval, $time1);
        // Set initial values
        $add = 1;
        $looped = 0;
        // Loop until temp time is smaller than time2
        while ($time2 >= $ttime) {
            // Create new temp time from time1 and interval
            $add++;
            $ttime = strtotime("+" . $add . " " . $interval, $time1);
            $looped++;
        }

        $time1 = strtotime("+" . $looped . " " . $interval, $time1);
        $diffs[$interval] = $looped;
    }

    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
        // Break if we have needed precission
        if ($count >= $precision) {
            break;
        }
        // Add value and interval
        // if value is bigger than 0
        if ($value >= 0) {
            // Add s if value is not 1
            if ($value != 1) {
                $interval .= "s";
            }
            // Add value and interval to times array
            //$times[] = $value . " " . $interval;	// DEFAULT
            $times[] = $value;
            $count++;
        }
    }

    // Return string with times
    //return implode(", ", $times);	// DEFAULT
    return implode(",", $times);
}

//ARRAY DATA LOOPING / MULTIPLE
function reArrayFiles(&$file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}
//ARRAY DATA LOOPING / MULTIPLE
function duitterbilang($value)
{
    $orro = number_format($value, 0, ',', '');
    return $orro;
}

//added by chrismanuel at 20180312
function getDevice()
{
    $userAgent = $_SERVER["HTTP_USER_AGENT"];
    $devicesTypes = array(
      "pc" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
      "tablet"   => array("tablet", "tablet.*firefox"),
      "mobile"   => array("mobile ", "opera mobi", "opera mini"),
      "android"   => array("android.*mobile", "android"),
      "iphone"   => array("iphone"),
      "ipod"   => array("ipod"),
      "ipad"   => array("ipad"),
      "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
  );
    foreach ($devicesTypes as $deviceType => $devices) {
        foreach ($devices as $device) {
            if (preg_match("/" . $device . "/i", $userAgent)) {
                $deviceName = $deviceType;
            }
        }
    }
    return ucfirst($deviceName);
}

function get_ip_address()
{
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}

//added by chrismanuel at 20180312
function getIpAddress()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
}

//added by chrismanuel at 20180312
function getBrowsers()
{
    preg_match('/Trident\/(.*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
    if ($matches) {
        $version = intval($matches[1]) + 4;
        return 'Internet Explorer '.($version < 11 ? $version : 'Edge');
    }

    foreach (array('Firefox', 'OPR', 'Chrome', 'Safari') as $browser) {
        preg_match('/'.$browser.'/', $_SERVER['HTTP_USER_AGENT'], $matches);
        if ($matches) {
            return str_replace('OPR', 'Opera', $browser);
        }
    }
}

//added by chrismanuel at 20180312
function getOs()
{
    $userAgent = $_SERVER["HTTP_USER_AGENT"];

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10.0/i'    =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $userAgent)) {
            $os_platform    =   $value;
        }
    }

    return $os_platform;
}

//added by chrismanuel at 20180312
function setUserLog($userid, $log='')
{
    $os = getOs();
    $device = getDevice();
    $browser = getBrowsers();
    // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $ip = get_ip_address();
    $host = $_SERVER['SERVER_ADDR'];
    $date = date('Y-m-d H:i:s');
    $session=  session_id();

    if ($log=='login') {
        $q1="INSERT INTO ajkuserlogin (user_id, ip_address, host_name, session, os, browser, device, login_at)
         VALUES('".$userid."','".$ip."','".$host."','".$session."','".$os."','".$browser."','".$device."','".$date."')";
        $result1=mysql_query($q1);

        $q2="INSERT INTO ajkuserlog (user_id, device, ip_address, login_at)
         VALUES('".$userid."','".$device."','".$ip."','".$date."')";
        $result2=mysql_query($q2);
    } elseif ($log=='logout') {
        if ($userid) {
            $q1="DELETE FROM ajkuserlogin WHERE user_id = '".$userid."'";
            $result1=mysql_query($q1);

            $q2="UPDATE ajkuserlog SET logout_at = '".$date."' WHERE user_id = ".$userid;
            $result2=mysql_query($q2);
        } else {
            $q1="DELETE FROM ajkuserlogin WHERE session = '".$session."' AND ip_address = '".$ip."' AND browser='".$browser."' AND device='".$device."' AND os='".$os."'";
            $result1=mysql_query($q1);

            $q2="UPDATE ajkuserlog SET logout_at = '".$date."' WHERE session = '".$session."' AND ip_address = '".$ip."' AND device='".$device."' AND os='".$os."'";
            $result2=mysql_query($q2);
        }
    }
}

function getLevelText($level)
{
    if ($level == 99) {
        $text = 'Super Admin';
    } elseif ($level == 6) {
        $text = 'Staff';
    } elseif ($level == 8) {
        $text = 'Supervisor';
    } elseif ($level == 9) {
        $text = 'Kepala Cabang';
    } elseif ($level == 13) {
        $text = 'Admin Cabang';
    } else {
        $text = 'Non Staff';
    }

    return $text;
}
