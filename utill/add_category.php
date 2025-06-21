<?php
include '../util_config.php';
include '../util_session.php';

if (isset($_POST['name'])) {
    $title = trim(str_replace("'", "`", $_POST['name']));
}
if (isset($_POST['fixed'])) {
    $fixed = trim(str_replace("'", "`", $_POST['fixed']));
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
if (isset($_POST['save_lang'])) {
    $save_lang = trim(str_replace("'", "`", $_POST['save_lang']));
}

$is_delete = 0;
$last_update = date('Y-m-d H:i:s');
if ($id == 0) {
    // SQL query with prepared statement
    $sql = "INSERT INTO `tbl_category` (`name`, `name_it`, `name_de`, `is_fixed`, `user_id`, `is_delete`, `last_update`) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiis", $title, $title, $title, $fixed, $my_user_id_is, $is_delete, $last_update);

    // Execute query
    if ($stmt->execute()) {
        echo "1";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    if ($save_lang == 'en') {
        $sql = "UPDATE `tbl_category` 
    SET `name` = ?, `is_fixed` = ?, `last_update` = ? 
    WHERE `id` = ?";
    } else if ($save_lang == 'it') {
        $sql = "UPDATE `tbl_category` 
        SET `name_it` = ?, `is_fixed` = ?, `last_update` = ? 
        WHERE `id` = ?";

    } else if ($save_lang == 'de') {
        $sql = "UPDATE `tbl_category` 
        SET `name_de` = ?, `is_fixed` = ?, `last_update` = ? 
        WHERE `id` = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $title, $fixed, $last_update, $id);

    if ($stmt->execute()) {
        echo "1";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

}
?>