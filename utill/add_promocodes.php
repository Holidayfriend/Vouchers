<?php
include '../util_config.php';
include '../util_session.php';

$code = "";
$discount_type = "";
$discount_value = "";
$max_uses = 0;
$start_date = "";
$end_date = "";
$what = "";
$voucher_id = null;
$category_id = null;
$save_lang = "";
$id = 0;

if (isset($_POST['code'])) {
    $code = trim(str_replace("'", "`", $_POST['code']));
}
if (isset($_POST['discount_type'])) {
    $discount_type = trim(str_replace("'", "`", $_POST['discount_type']));
}
if (isset($_POST['discount_value'])) {
    $discount_value = trim(str_replace("'", "`", $_POST['discount_value']));
}
if (isset($_POST['max_uses'])) {
    $max_uses = trim(str_replace("'", "`", $_POST['max_uses']));
}
if (isset($_POST['start_date'])) {
    $start_date = trim(str_replace("'", "`", $_POST['start_date']));
}
if (isset($_POST['end_date'])) {
    $end_date = trim(str_replace("'", "`", $_POST['end_date']));
}
if (isset($_POST['what'])) {
    $what = trim(str_replace("'", "`", $_POST['what']));
}
if (isset($_POST['voucher_id']) && $_POST['voucher_id'] !== '') {
    $voucher_id = intval($_POST['voucher_id']);
}
if (isset($_POST['category_id']) && $_POST['category_id'] !== '') {
    $category_id = intval($_POST['category_id']);
}
if (isset($_POST['save_lang'])) {
    $save_lang = trim(str_replace("'", "`", $_POST['save_lang']));
}
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
}

$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
$user_id = $my_user_id_is;

// Validate: if what is voucher or category, respective ID must be provided
if ($what === 'voucher' && $voucher_id === null) {
    echo "Error: Voucher ID is required when apply type is voucher.";
    exit;
}
if ($what === 'category' && $category_id === null) {
    echo "Error: Category ID is required when apply type is category.";
    exit;
}

if ($id == 0) {
    // Create new promo code
    $sql = "INSERT INTO `tbl_promocodes` (`user_id`, `code`, `discount_type`, `discount_value`, `max_uses`, `current_uses`, `start_date`, `end_date`, `voucher_id`, `category_id`, `what`, `is_delete`, `created_at`, `updated_at`)
            VALUES ('$user_id', '$code', '$discount_type', '$discount_value', '$max_uses', 0, '$start_date', '$end_date', " . ($voucher_id !== null ? "'$voucher_id'" : "NULL") . ", " . ($category_id !== null ? "'$category_id'" : "NULL") . ", '$what', 0, '$entry_time', '$entry_time')";
} else {
    // Update existing promo code
    $sql = "UPDATE `tbl_promocodes` SET 
            `code` = '$code',
            `discount_type` = '$discount_type',
            `discount_value` = '$discount_value',
            `max_uses` = '$max_uses',
            `start_date` = '$start_date',
            `end_date` = '$end_date',
            `voucher_id` = " . ($voucher_id !== null ? "'$voucher_id'" : "NULL") . ",
            `category_id` = " . ($category_id !== null ? "'$category_id'" : "NULL") . ",
            `what` = '$what',
            `updated_at` = '$entry_time'
            WHERE `promo_code_id` = $id AND `user_id` = $user_id AND `is_delete` = 0";
}

$result = $conn->query($sql);
if ($result) {
    echo '1';
} else {
    echo $conn->error;
}
?>