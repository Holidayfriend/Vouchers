<?php
require_once '../util_config.php';
require_once '../util_session.php';

if (!isset($my_user_id_is)) {
    echo '0';
    exit;
}

$header_color = $_POST['header_color'] ?? '';
$link_color = $_POST['link_color'] ?? '';
$button_color = $_POST['button_color'] ?? '';
$font_family = $_POST['font_family'] ?? '';
$text_color = $_POST['text_color'] ?? '';
$button_text_color = $_POST['button_text_color'] ?? '';

$is_image_chnaged = isset($_POST['is_logo_changed']) ? $_POST['is_logo_changed'] : '0';
$editModeImageUrl = isset($_POST['editModeLogoUrl']) ? $_POST['editModeLogoUrl'] : '';

$img_url = $editModeImageUrl;

if ($is_image_chnaged == '1' && isset($_FILES['logo']['name']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
    $filename = $_FILES['logo']['name'];
    $filepath = $_FILES['logo']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_dir = "./images/profile_img/profile-" . time() . "-909." . $ext;
    $image_path = $target_dir;

    $upload_dir = dirname($target_dir);
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($filepath, '.' . $image_path)) {
        if ($editModeImageUrl && file_exists('.' . $editModeImageUrl)) {
            unlink('.' . $editModeImageUrl);
        }
        $img_url = (string) $target_dir;
    } else {
        echo '0';
        exit;
    }
} elseif ($is_image_chnaged == '1' && (!isset($_FILES['logo']['name']) || $_FILES['logo']['name'] == '')) {
    $img_url = '';
    if ($editModeImageUrl && file_exists('.' . $editModeImageUrl)) {
        unlink('.' . $editModeImageUrl);
    }
}

$sql = "UPDATE tbl_user SET 
        header_color = ?, 
        link_color = ?, 
        button_color = ?, 
        font_family = ?, 
        text_color = ?, 
        button_text_color = ?, 
        logo = ?,
        image = ? 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo '0';
    exit;
}
$stmt->bind_param('ssssssssi', $header_color, $link_color, $button_color, $font_family, $text_color,$button_text_color, $img_url,$img_url, $my_user_id_is);

if ($stmt->execute()) {
    echo '1';
} else {
    echo '0';
}
$stmt->close();
$conn->close();
?>