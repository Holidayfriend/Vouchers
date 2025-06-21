<?php
include '../util_config.php';
include '../util_session.php';

if (isset($_POST['live_client_id'])) {
    $live_client_id = trim(str_replace("'", "`", $_POST['live_client_id']));
}
if (isset($_POST['live_client_secret'])) {
    $live_client_secret = trim(str_replace("'", "`", $_POST['live_client_secret']));
}
if (isset($_POST['sandbox_client_id'])) {
    $sandbox_client_id = trim(str_replace("'", "`", $_POST['sandbox_client_id']));
}
if (isset($_POST['sandbox_client_secret'])) {
    $sandbox_client_secret = trim(str_replace("'", "`", $_POST['sandbox_client_secret']));
}
if (isset($_POST['mode_is'])) {
    $mode_is = trim(str_replace("'", "`", $_POST['mode_is']));
}
$sql = "UPDATE `tbl_user` SET `live_client_id`='$live_client_id',`live_client_secret`='$live_client_secret',`sandbox_client_id`='$sandbox_client_id',
`sandbox_client_secret`='$sandbox_client_secret',`is_paypal_live`='$mode_is' WHERE `user_id` = $my_user_id_is";


$result = $conn->query($sql);
if ($result) {
    echo '1';

} else {
    echo '0';
}
?>