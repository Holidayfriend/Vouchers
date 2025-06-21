
<?php 
include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');


$id = "";

if(isset($_POST['id'])){
    $id = $_POST['id'];
}
if(isset($_POST['what'])){
    $what=trim(str_replace("'","`",$_POST['what']));
}
if(isset($_POST['base'])){
    $base=trim(str_replace("'","`",$_POST['base']));
}
if(isset($_POST['name'])){
    $name=trim(str_replace("'","`",$_POST['name']));
}

//    'SUPER_ADMIN','BENEFIT_PROVIDER','BENEFIT_HOLDER','EMOLOYEE'

$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

$base = 'tbl_'.$base;



$sql_bh="UPDATE `$base` SET  `status`='$what' WHERE `$name` = '$id'";
$result_bh = $conn->query($sql_bh);
if($result_bh){
    echo 'done';
}else{
    echo 'error';
}




?>