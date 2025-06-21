<?php
include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');

$filename = "";
$filepath = "";
$title = "";
$amount = "";
$active_from = "";
$active_to = "";
$description = "";
$save_lang = "";
$cat_id = "";
$type = "";
$is_recurring = 0;
$recurring_start_month = "";
$recurring_start_day = "";
$recurring_end_month = "";
$recurring_end_day = "";
$id = 0;
$is_image_chnaged = '';
$editModeImageUrl = '';

if (isset($_POST['title'])) {
    $title = trim(str_replace("'", "`", $_POST['title']));
}
if (isset($_POST['amount'])) {
    $amount = trim(str_replace("'", "`", $_POST['amount']));
}
if (isset($_POST['active_from'])) {
    $active_from = trim(str_replace("'", "`", $_POST['active_from']));
}
if (isset($_POST['active_to'])) {
    $active_to = trim(str_replace("'", "`", $_POST['active_to']));
}
if (isset($_POST['description'])) {
    $description = trim(str_replace("'", "`", $_POST['description']));
}
if (isset($_POST['save_lang'])) {
    $save_lang = trim(str_replace("'", "`", $_POST['save_lang']));
}
if (isset($_POST['selectedCategoryId'])) {
    $cat_id = trim(str_replace("'", "`", $_POST['selectedCategoryId']));
}
if (isset($_POST['type'])) {
    $type = trim(str_replace("'", "`", $_POST['type']));
}
if (isset($_POST['is_recurring'])) {
    $is_recurring = (int)$_POST['is_recurring'];
}
if (isset($_POST['recurring_start_month'])) {
    $recurring_start_month = trim(str_replace("'", "`", $_POST['recurring_start_month']));
}
if (isset($_POST['recurring_start_day'])) {
    $recurring_start_day = trim(str_replace("'", "`", $_POST['recurring_start_day']));
}
if (isset($_POST['recurring_end_month'])) {
    $recurring_end_month = trim(str_replace("'", "`", $_POST['recurring_end_month']));
}
if (isset($_POST['recurring_end_day'])) {
    $recurring_end_day = trim(str_replace("'", "`", $_POST['recurring_end_day']));
}
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
}
if (isset($_POST['is_image_chnaged'])) {
    $is_image_chnaged = $_POST['is_image_chnaged'];
}
if (isset($_POST['editModeImageUrl'])) {
    $editModeImageUrl = $_POST['editModeImageUrl'];
}

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $filepath = $_FILES['file']['tmp_name'];
}
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$target_dir = "./images/profile_img/profile-" . time() . "-" . '909' . "." . $ext;
$image_path = $target_dir;
move_uploaded_file($filepath, '.' . $image_path);
if ($filename == "") {
    $img_url = $editModeImageUrl;
} else {
    $img_url = (string)$target_dir;
}

$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");

// For recurring vouchers, set active_from/active_to to current year
if ($is_recurring && $recurring_start_month && $recurring_start_day && $recurring_end_month && $recurring_end_day) {
    $current_year = date("Y");
    $active_from = "$current_year-$recurring_start_month-$recurring_start_day";
    $active_to = "$current_year-$recurring_end_month-$recurring_end_day";
}

if ($id == 0) {
    $sql = "INSERT INTO `tbl_voucher` (`user_id`, `title`, `title_it`, `title_de`, `status`, `description`, `description_it`, `description_de`, `amount`, `active_from`, `active_to`, `is_delete`, `create_at`, `update_at`, `image`, `cat_id`, `type`, `is_recurring`, `recurring_start_month`, `recurring_start_day`, `recurring_end_month`, `recurring_end_day`)
            VALUES ('$my_user_id_is', '$title', '$title', '$title', 'ACTIVE', '$description', '$description', '$description', '$amount', '$active_from', '$active_to', '0', '$entry_time', '$entry_time', '$img_url', '$cat_id', '$type', '$is_recurring', '$recurring_start_month', '$recurring_start_day', '$recurring_end_month', '$recurring_end_day')";
} else {
    if ($save_lang == 'en') {
        $sql = "UPDATE `tbl_voucher` SET `title`='$title', `description`='$description', `amount`='$amount', `active_from`='$active_from', `active_to`='$active_to', `update_at`='$entry_time', `image`='$img_url', `cat_id`='$cat_id', `type`='$type', `is_recurring`='$is_recurring', `recurring_start_month`='$recurring_start_month', `recurring_start_day`='$recurring_start_day', `recurring_end_month`='$recurring_end_month', `recurring_end_day`='$recurring_end_day'
                WHERE `id` = $id";
    } else if ($save_lang == 'it') {
        $sql = "UPDATE `tbl_voucher` SET `title_it`='$title', `description_it`='$description', `amount`='$amount', `active_from`='$active_from', `active_to`='$active_to', `update_at`='$entry_time', `image`='$img_url', `cat_id`='$cat_id', `type`='$type', `is_recurring`='$is_recurring', `recurring_start_month`='$recurring_start_month', `recurring_start_day`='$recurring_start_day', `recurring_end_month`='$recurring_end_month', `recurring_end_day`='$recurring_end_day'
                WHERE `id` = $id";
    } else if ($save_lang == 'de') {
        $sql = "UPDATE `tbl_voucher` SET `title_de`='$title', `description_de`='$description', `amount`='$amount', `active_from`='$active_from', `active_to`='$active_to', `update_at`='$entry_time', `image`='$img_url', `cat_id`='$cat_id', `type`='$type', `is_recurring`='$is_recurring', `recurring_start_month`='$recurring_start_month', `recurring_start_day`='$recurring_start_day', `recurring_end_month`='$recurring_end_month', `recurring_end_day`='$recurring_end_day'
                WHERE `id` = $id";
    }
}

$result = $conn->query($sql);
if ($result) {
    echo '1';
} else {
    echo $sql;
}
?>