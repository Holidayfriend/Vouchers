<?php 
include '../util_config.php';
include '../util_session.php';


$type = "";
if(isset($_POST['type'])){
    $type = $_POST['type'];
}


$sql="UPDATE `tbl_user` SET `language`='$type' WHERE `user_id` =  $my_user_id_is";

$result = $conn->query($sql);
if($result){
    echo '1';


    $_SESSION['my_language_is'] = $type;

}else{ 
    echo 'error';
}


