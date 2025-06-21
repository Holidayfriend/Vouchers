<?php 
include '../util_config.php';
include '../util_session.php';


$language = "";
if(isset($_POST['language'])){
    $language = $_POST['language'];
}
if(isset($_POST['language'])){
    $value = $_POST['value'];
}


$sql = "UPDATE `tbl_city` SET $language = '$value' where city_id = $saved_user_id";
if ($conn->query($sql) === TRUE) {
    // Database update successful
    echo "1";
} else {
    // Database update failed
    echo 'error';
}