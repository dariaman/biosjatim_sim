<?php
include "../param.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (($level==9 or $level == 99) and $cabang == 1) {
    $cabangverifikasi = "'1' as cpass";
} else {
    $cabangverifikasi = ' case when (uac.branch = '.$cabang.') then 1 else 0 end as cpass';
}

$query = mysql_query('SELECT uac.id,aul.session,uac.username,uac.firstname,uac.lastname,aul.id as aulid,uac.level,'.$cabangverifikasi.'
  FROM useraccess uac
  LEFT JOIN ajkuserlogin aul ON aul.user_id=uac.id
  WHERE (uac.username LIKE "%'.$search.'%"
  OR uac.username LIKE "%'.$search.'%"
  OR uac.firstname LIKE "%'.$search.'%"
  OR uac.lastname LIKE "%'.$search.'%") AND
  uac.id != '.$iduser.' AND
  uac.idbroker = '.$idbro.' AND
  uac.idclient = '.$idclient.'
  ORDER BY case when uac.tipe = "Admin" then 0 else 1 end asc,
  case when uac.branch = 1 and level = 9 then 0 else 1 end asc,
  case when cpass = 1 then 0 else 1 end asc,
  aul.id DESC, uac.branch ASC, uac.level ASC');

while ($row = mysql_fetch_array($query)) {
    ?>
    <a href="javascript:void(0)" class="list-group-item active bg-inverse" style="border-color: #fff !important;">
      <p class="list-group-item-heading">
        <?php
          if ($row['id']!=$_SESSION['uid'] && $row['aulid']!='') {
              ?>
        <span style="border:none !important" onclick="chatWith('<?= $row['username'] ?>')"><?= $row['username'] ?></span>
          <?php
            if ($row['cpass']==1 and ($level==9 or $level == 99)) {
                ?>
            <span class="pull-right label label-warning" onclick="changePassword('<?= base64_encode($row['id']) ?>','<?= $row['username'] ?>')">Change Password</span>
            <span class="label label-success" onclick="setOffline('<?= $row['id'] ?>','<?= $row['username'] ?>')" title="Klik di sini untuk set offline">Online</span>
           <?php
            } else {
                ?>
            <span class="label label-success">Online</span>
           <?php
            } ?>
        <?php
          } elseif ($row['aulid']=='') {
              ?>
        <span style="border:none !important"><?= $row['username'] ?></span>
        <?php
          if ($row['cpass']==1 and ($level==9 or $level == 99)) {
              ?>
        <span class="pull-right label label-warning" onclick="changePassword('<?= base64_encode($row['id']) ?>','<?= $row['username'] ?>')">Change Password</span>
        <?php
          } ?>
        <span class="label label-default">Offline</span>
        <?php
          } elseif ($row['id']==$_SESSION['uid']) {
              ?>
        <span style="border:none !important"><?= $row['username'] ?></span>
        <span class="label label-success">Online</span>
        <?php
          } ?>
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
