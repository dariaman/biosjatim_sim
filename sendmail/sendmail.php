<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/

// include "../param.php";

// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');

require 'class.phpmailer.php';
require 'class.smtp.php';
function kirimemail($sender, $to, $recipients, $cc=[], $bcc=[], $subject, $body, $attachment=[], $return='')
{
    $path = "https://".$_SERVER['SERVER_NAME'];
    $mail = new PHPMailer();

    $mail->IsSMTP(); //
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;
    $mail->Host       = 'surat.adonai.co.id';
    $mail->Port       = 25;
    // $mail->SMTPSecure = 'tls';
    $mail->Username = 'notifikasi@jatim.adonai.co.id';  // SMTP username
    $mail->Password = 'Kodok123'; // SMTP password
    $mail->Timeout = 3600;

    // $mail->From = $fromemail;
    // $mail->FromName = $fromname;

    // $mail->setFrom($fromemail, $fromname);

    $from = '';
    foreach ($sender as $email => $name) {
        $mail->setFrom($email, $name);
        $from .= $name;
    }

    foreach ($recipients as $email => $name) {
        $mail->AddAddress($email, $name);
    }

    if (count($cc) > 0) {
        foreach ($cc as $email => $name) {
            $mail->AddCC($email, $name);
        }
    }

    if (count($bcc) > 0) {
        foreach ($bcc as $email => $name) {
            $mail->AddBCC($email, $name);
        }
    }

    // $mail->addAddress($toemail, $toname);

    if ($attachment[0]) {
        $mail->addAttachment($attachment[0], $attachment[1]);
    }

    //$mail->AddAddress('$toemail', '$toname');
    //$mail->AddReplyTo('info@example.com', 'Information');

    //$mail->WordWrap = 50;                                 // set word wrap to 50 characters
    //$mail->AddAttachment('/var/tmp/file.tar.gz');         // add attachments
    //$mail->AddAttachment('/tmp/image.jpg', 'new.jpg');    // optional name
    $mail->IsHTML(true);                                  // set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = "<!DOCTYPE html>
		<html>
		<head>
		    <title>Welcome Email</title>
		</head>

		<body>

		<table class='body-wrap'
		       style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;height: 100%;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;'>
		    <tr style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;'>
		        <td class='container'
		            style='margin: 0 auto !important;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;'>

		            <!-- Message start -->
		            <table style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;'>
		                <tr style='margin: 0;padding: 0;font-size: 100%;font-family:Helvetica, Arial, sans-serif;line-height: 1.65;'>
		                    <td align='center' class='masthead' style='margin: 0;padding: 40px 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;background:#d5d8da;color: white;'>
				                  <img src='".$path."/sendmail/logojatim.png' style='height: 100px;'>
		                    </td>
		                </tr>
		                <tr style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;'>
		                    <td class='content'
		                        style='margin: 0;padding: 30px 35px;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;background: white;'>

		                        <p style='margin: 0;padding: 0;font-size: 12px;font-family: Helvetica, Arial, sans-serif;line-height: 1.25;margin-bottom: 20px; font-weight:bold'>
		                            Dear ".$to.",</p>

		                        <p>You have a request from ".$from."</b>.</p>

		                        <p>".$body."</p>

		                        <p>Thank you.</p>

		                    </td>
		                </tr>
		            </table>

		        </td>
		    </tr>
		    <tr style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;'>
		        <td class='container'
		            style='margin: 0 auto !important;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;display: block !important;clear: both !important;max-width: 580px !important;'>

		            <!-- Message start -->
		            <table style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;border-collapse: collapse;width: 100% !important;'>
		                <tr style='margin: 0;padding: 0;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;'>
		                    <td class='content footer' align='center'
		                        style='margin: 0;padding: 30px 35px;font-size: 100%;font-family: Helvetica, Arial, sans-serif;line-height: 1.65;background: none;'>


		                        </p>
		                    </td>
		                </tr>
		            </table>

		        </td>
		    </tr>
		</table>

		</body>

		</html>
";
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        if ($return!='') {
            echo "<script>window.location.href='".$path.$return."?rs=0&msg='".$mail->ErrorInfo."</script>";
        }
    } else {
        if ($return!='') {
            echo "<script>window.location.href='".$path.$return."?rs=1&msg=Email berhasil dikirim.'</script>";
        }
    }
}
