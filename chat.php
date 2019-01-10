<?php

/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/

include "koneksi.php";

session_start();



if ($_GET['action'] == "chatheartbeat") {
    chatHeartbeat();
}
if ($_GET['action'] == "sendchat") {
    sendChat();
}
if ($_GET['action'] == "closechat") {
    closeChat();
}
if ($_GET['action'] == "startchatsession") {
    startChatSession();
}

if (!isset($_SESSION['chatHistory'])) {
    $_SESSION['chatHistory'] = array();
}

if (!isset($_SESSION['openChatBoxes'])) {
    $_SESSION['openChatBoxes'] = array();
}

function chatHeartbeat()
{
    $sql = "select *
	from chatuser
	LEFT JOIN useraccess ON username = chatuser.from
	where (chatuser.to = '".mysql_real_escape_string($_SESSION['User'])."' AND recd = 0) order by chatuser.id ASC";
    $query = mysql_query($sql);
    $items = '';

    $chatBoxes = array();

    while ($chat = mysql_fetch_array($query)) {
        if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
            $items = $_SESSION['chatHistory'][$chat['from']];
        }

        $chat['message'] = sanitize($chat['message']);

        $items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"d": "{$chat['username']}",
			"m": "{$chat['message']}"
	   },
EOD;

        if (!isset($_SESSION['chatHistory'][$chat['from']])) {
            $_SESSION['chatHistory'][$chat['from']] = '';
        }

        $_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"d": "{$chat['username']}",
			"m": "{$chat['message']}"
	   },
EOD;

        unset($_SESSION['tsChatBoxes'][$chat['from']]);
        $_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
    }

    if (!empty($_SESSION['openChatBoxes'])) {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
            if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                $now = time()-strtotime($time);
                $time = date('g:iA M dS', strtotime($time));

                $message = "Sent at $time";
                if ($now > 169) {
                    $items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

                    if (!isset($_SESSION['chatHistory'][$chatbox])) {
                        $_SESSION['chatHistory'][$chatbox] = '';
                    }

                    $_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
                    $_SESSION['tsChatBoxes'][$chatbox] = 1;
                }
            }
        }
    }

    $sql = "update chatuser set recd = 1 where chatuser.to = '".mysql_real_escape_string($_SESSION['User'])."' and recd = 0";
    $query = mysql_query($sql);

    if ($items != '') {
        $items = substr($items, 0, -1);
    }
    header('Content-type: application/json'); ?>
{
		"items": [
			<?php echo $items; ?>
        ]
}

<?php
            exit(0);
}

function chatBoxSession($chatbox)
{
    $items = '';

    if (isset($_SESSION['chatHistory'][$chatbox])) {
        $items = $_SESSION['chatHistory'][$chatbox];
    }

    return $items;
}

function startChatSession()
{
    $items = '';
    if (!empty($_SESSION['openChatBoxes'])) {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
            $items .= chatBoxSession($chatbox);
        }
    }


    if ($items != '') {
        $items = substr($items, 0, -1);
    }

    header('Content-type: application/json');
    $quserchat = mysql_fetch_array(mysql_query("select * from useraccess where username = '".$_SESSION['User']."'")); ?>
{
		"username": "<?php echo $quserchat['username']; ?>",
		"items": [
			<?php echo $items; ?>
        ]
}

<?php


    exit(0);
}

function sendChat()
{
    $from = $_SESSION['User'];
    $to = $_POST['to'];
    $message = $_POST['message'];

    $data = [
      "post_title" => $from,
      "post_msg" => $message,
      "time" => date('Y-m-d H:i:s')
    ];

    $q = "select * from user_mobile_token umt
            join useraccess uac on uac.id=umt.UserID
            where uac.username='".$to."'";
    $result = mysql_query($q);
    $token = mysql_fetch_object($result);

    // kirim notif
    _sendnotification($token->UserToken, $data);

    $_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());

    $messagesan = sanitize($message);

    if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
        $_SESSION['chatHistory'][$_POST['to']] = '';
    }

    $_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


    unset($_SESSION['tsChatBoxes'][$_POST['to']]);

    $sql = "insert into chatuser (chatuser.from,chatuser.to,message,sent) values ('".mysql_real_escape_string($from)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."',NOW())";
    $query = mysql_query($sql);
    // print_r($q);
    echo "1";
    exit(0);
}

function closeChat()
{
    unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);

    echo "1";
    exit(0);
}

function sanitize($text)
{
    $text = htmlspecialchars($text, ENT_QUOTES);
    $text = str_replace("\n\r", "\n", $text);
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\n", "<br>", $text);
    return $text;
}

function _sendnotification($registatoin_ids, $data)
{
    // google cloud messaging GCM_API url
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
                    'to' => $registatoin_ids,
                    'data' => $data
                    );
    // Google Cloud Messaging GCM API key
    define("GOOGLE_API_KEY", "AAAAYkMMcpU:APA91bH9RAM0_yac0Oc152TkCi1_cdQrGBN6JzWp5Ki0Ro4u6NOP2nXO1CN3yvjtu1_3-5D2rD-SpEG1R1QcY2E7QBVMCbCowE3jD0ppA5XCmUXutnX0yPDp0ptqsshq-ciETi-TUYVg");

    $headers = array(
              'Authorization: key=' . GOOGLE_API_KEY,
              'Content-Type: application/json'
              );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if ($result === false) {
        die('Curl failed : ' . curl_error($ch));
    }

    curl_close($ch);

    return $result;
}
