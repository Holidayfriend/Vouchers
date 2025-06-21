<?php 
include '../util_config.php';
include '../util_session.php';


$id = "";
if(isset($_POST['id'])){
    $id = $_POST['id'];
}
$entry_time = date("Y-m-d H:i:s");

$sql="UPDATE `tbl_users_vouchers` SET `redaem_time`='$entry_time' WHERE `user_id` =  $my_user_id_is and `id` = $id";

$result = $conn->query($sql);
if($result){
    echo '1';


   

}else{ 
    echo 'error';
}


