<?php
if (!session_id()) {
    session_start();
}
$my_language_is = $_SESSION['my_language_is'] ?? 'EN';
$my_name_is = $_SESSION['my_name_is'] ?? '';
$my_surname_is = $_SESSION['my_surname_is'] ?? '';
$my_email_is = $_SESSION['my_email_is'] ?? '';
$my_user_type_is = $_SESSION['my_user_type_is'] ?? '';
$my_image_is = $_SESSION['my_image_is'] ?? '';
$my_hotel_name_is = $_SESSION['my_hotel_name_is'] ?? '';
$my_hotel_website_is = $_SESSION['my_hotel_website_is'] ?? '';
$my_user_id_is = $_SESSION['my_user_id_is'] ?? ''
?>