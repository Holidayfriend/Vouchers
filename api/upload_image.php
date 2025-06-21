<?php
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "./images/profile_img/"; // Ensure this directory exists
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $uploadFile ='.' . $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
        echo $uploadFile; // Return new image URL
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded.";
}
?>
