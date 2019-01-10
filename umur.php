<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
function birthday($birthday, $today){
    $age = strtotime($birthday);
	$now = strtotime($today);
    if($age === false){
        return false;
    }

    list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));

    list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));

    $diifyear = $y2 - $y1;

	if((int)($m1) > (int)($m2))
	{
		$diifyear = $diifyear -1;
	}

	$clinetage = $y1 + $diifyear;
	$birthdate = $clinetage.'-'.$m1.'-'.$d1;
	$now = $y2.'-'.$m2.'-'.$d2;
	$diffday = (strtotime($birthdate) - strtotime($now))/  ( 60 * 60 * 24 )*-1;

	if($diffday>=183)
	{
		$diifyear = $diifyear + 1;
	}
	else
	{
		$diifyear = $diifyear;
	}


    return $diifyear;


}


