
<?php
require_once '../util_config.php';
require_once '../util_session.php';

if (isset($_POST['analytics_google'])) {
    $analytics_google = trim(str_replace("'", "`", $_POST['analytics_google']));
    
    $sql = "UPDATE `tbl_user` SET `analytics_google`='$analytics_google' WHERE `user_id` = $my_user_id_is";
    $result = $conn->query($sql);
    
    echo $result ? '1' : '0';
}
?>
