<?php
include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');


$filename = "";
$filepath = "";
$type = "";
$companyname = "";
$companytelephone = "";
$contactperson_name = "";
$contectperson_telephone = "";
$contectperson_email = "";
$city = 0;
$additional_info = "";
$text_info = "";
$surname = "";
$citytelephone = "";
$city_email = "";
$address = "";
$imprint = "";
$privacy = "";
$tax_number = "";

if (isset($_POST['save_lang'])) {
    $language = trim(str_replace("'", "`", $_POST['save_lang']));
}
if (isset($_POST['adminname'])) {
    $name = trim(str_replace("'", "`", $_POST['adminname']));
}
if (isset($_POST['adminsurname'])) {
    $surname = trim(str_replace("'", "`", $_POST['adminsurname']));
}
if (isset($_POST['admin_tel'])) {
    $person_phone = trim(str_replace("'", "`", $_POST['admin_tel']));
}

if (isset($_POST['adminemail'])) {
    $email = trim(str_replace("'", "`", $_POST['adminemail']));
}

if (isset($_POST['hotelname'])) {
    $hotel_name = trim(str_replace("'", "`", $_POST['hotelname']));
}
if (isset($_POST['website'])) {
    $hotel_website = trim(str_replace("'", "`", $_POST['website']));
}
$hotel_website = mysqli_real_escape_string($conn, $hotel_website);

$id = 0;

$is_image_chnaged = '';
$editModeImageUrl = '';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
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
    $img_url = (string) $target_dir;
}



//    'SUPER_ADMIN','BENEFIT_PROVIDER','BENEFIT_HOLDER','EMOLOYEE'

$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");

$password_plan = generateRandomPassword(8);

$user_code = generateUserCode();



$password_plan = '123456';
$password = md5($password_plan);

if ($id == 0) {
    $sql1 = "SELECT * FROM `tbl_user` WHERE `email` = '$email' AND is_delete = 0 ";
} else {
    $sql1 = "SELECT * FROM `tbl_user` WHERE `email` = '$email' AND user_id != $id AND is_delete = 0 ";
}

$result1 = $conn->query($sql1);
$m_type = 0;
$m_id = 0;
if ($result1 && $result1->num_rows > 0) {

    $output = "EXIT";
    echo $output;
} else {



    $result_bh = true;



    if ($result_bh) {

        if ($id == 0) {
            $sql = "INSERT INTO `tbl_user`( `user_type`, `name`, `surname`, `email`, `password`, `person_phone`, `image`, `status`,
             `is_delete`, `hotel_name`, `hotel_website`, `language`,`user_code`, `entry_by_time`, `entry_by_id`, `edit_by_time`, `edit_by_id`,`logo`) 
              VALUES ('NORMAL','$name','$surname','$email','$password',
              '$person_phone','$img_url','ACTIVE','0','$hotel_name','$hotel_website','$language','$user_code','$entry_time','$my_user_id_is ',
              '$entry_time','$my_user_id_is','$img_url')";
        } else {
            $sql = "UPDATE `tbl_user` SET `name`='$name',`surname`='$surname',`email`='$email',
            `person_phone`='$person_phone',`image`='$img_url',`status`='ACTIVE',
            `hotel_name`='$hotel_name',`hotel_website`='$hotel_website',
            `edit_by_time`='$entry_time',`edit_by_id`='$my_user_id_is ' WHERE `user_id` = $id";
        }
        $result = $conn->query($sql);
        if ($result) {
            if ($id == 0) {
                if ($id == 0) {
                    // Insert email job into tbl_other_email_jobs
                    $email_subject = "Welcome to QualityFriend Vouchers";
                    $email_body = "Dear $name,<br><br>Welcome to QualityFriend Vouchers! Your account has been created successfully.<br>Login URL: https://vouchers.qualityfriend.solutions/<br>Username: $email<br>Password: $password_plan<br><br>Best regards,<br>QualityFriend Team";
                    $email_body = mysqli_real_escape_string($conn, $email_body);
                    $create_at = date("Y-m-d H:i:s");
                    $lang = $language ?: 'en';
                    $sql_email = "INSERT INTO `tbl_other_email_jobs` (`email`, `email_text`, `subject`, `create_at`, `lang`, `is_run`) 
                              VALUES ('$email', '$email_body', '$email_subject', '$create_at', '$lang', '0')";
                    $conn->query($sql_email);
                    echo '1';
                } else {
                    echo '1';
                }

              
            } else {
                echo '1';
            }
        } else {
            echo  $sql;
        }
    } else {
        echo 'error1';
    }
}


function generateRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $charactersLength - 1)];
    }

    return $password;
}


function generateUserCode($length = 14)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
