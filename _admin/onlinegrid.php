<?php
// echo "asd";exit;
// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
include "../includes/jjt1502.php";

session_start();

// print_r($_SESSION);exit;

$path="https://".$_SERVER['SERVER_NAME']."/";
$user = $_SESSION['username'];

$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$user."'");
$rowuser = mysql_fetch_array($queryuser);
// $namauser = $rowuser['firstname'];
// $lastname = $rowuser['lastname'];
// $emailuser = $rowuser['email'];
$iduser = $rowuser['id'];
// $idsupervisor = $rowuser['supervisor'];
// $photo = $rowuser['photo'];
// $idbro = $rowuser['idbroker'];
// $idas = $rowuser['idas'];
// $cabang = $rowuser['branch'];
// $idclient = $rowuser['idclient'];
// $qklient = mysql_query("SELECT * FROM ajkclient WHERE id= '".$idclient."'");
// $rklient = mysql_fetch_array($qklient);
// $namaklient = $rklient['name'];
// $logoklient =  $rklient['logo'];
// $useremail = $rowuser['email'];
// $level = $rowuser['level'];
// $gender = $rowuser['gender'];
// // $lastdayakad = $rowuser['lastdayinsurance'];
// $branchid = $rowuser['branch'];
// if ($gender=="L") {
//     $jeniskelamin = "Laki-Laki";
// } else {
//     $jeniskelamin = "Perempuan";
// }


$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = mysql_query('SELECT uac.id,aul.session,uac.username,uac.firstname,uac.lastname,aul.id as aulid,uac.level
  FROM useraccess uac
  LEFT JOIN ajkuserlogin aul ON aul.user_id=uac.id
  WHERE (uac.username LIKE "%'.$search.'%"
  OR uac.username LIKE "%'.$search.'%"
  OR uac.firstname LIKE "%'.$search.'%"
  OR uac.lastname LIKE "%'.$search.'%")
  AND uac.id != '.$iduser.' AND aul.user_id is not null');

while ($row = mysql_fetch_array($query)) {
    ?>
    <a href="javascript:void(0)" class="list-group-item" style="border-color: #fff !important;"  onclick="chatWith('<?= $row['username'] ?>')">
      <p class="list-group-item-heading">
        <span style="border:none !important"><?= $row['username'] ?></span>
      </p>
      <p class="list-group-item-text"><small><?= $row['firstname'] ?> - <?= $row['lastname'] ?></small></p>
    </a>
<?php
} ?>

<script>
function setOffline(id,username){
    swal({
    title: 'Are you sure?',
    text: "Set offline user "+username+' ?',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirm!'
  }).then(function(){
    $.ajax({
      type  : 'POST',
      dataType: 'html',
      url   	: "setoffline.php",
      data  : {
        "id"	: id,
      },
      success : function(msg) {
        getOnline();
      },
      beforeSend: function( xhr ) {
        $( "#online-grid" ).html("<div class='text-center'><i class='fa fa-cog fa-spin fa-2x'></div>");
      }
    });
  }).catch(function(reason){
      // alert("The alert was dismissed by the user: ");
  });
}

function changePassword(id,username){
  url = '../changepassword/index.php?param1='+id+'&param2='+username;
  var win = window.open(url, '_blank');
  win.focus();
}

</script>
