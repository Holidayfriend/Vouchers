<?php
require_once 'util_config.php';
require_once 'util_session.php';
include 'lang/translation.php';
$lang = strtolower($my_language_is);

if (isset($_SESSION['my_user_type_is'])) {
    if ($my_user_type_is == 'ADMIN') {
        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
    } else if ($my_user_type_is == 'NORMAL') {
        // echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
    } else {
        echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
} else {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
}


$sql = "SELECT * FROM `tbl_voucher`  WHERE `user_id` =  '$my_user_id_is' AND `is_delete` = 0 AND `id` = $id ";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {

        $user_id = $row['user_id'];

        if ($lang == 'en') {

            $title = $row['title'];
            $description = $row['description'];
        } else if ($lang == 'it') {
            $title = $row['title_it'];
            $description = $row['description_it'];

        } else {
            $title = $row['title_de'];
            $description = $row['description_de'];

        }


        $amount = $row['amount'];
        $active_from = $row['active_from'];
        $active_to = $row['active_to'];


        $image = $row['image'];
        $cat_id = $row['cat_id'];
        $type = $row['type'];
        $is_recurring = $row['is_recurring'];
        $recurring_start_month = $row['recurring_start_month'];
        $recurring_start_day = $row['recurring_start_day'];
        $recurring_end_month = $row['recurring_end_month'];
        $recurring_end_day = $row['recurring_end_day'];



    }
} else {

}




$page_text = getTranslation('edit', $lang);
$back = 'yes';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title>Edit Vocher</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--c3 plugins CSS -->
    <link href="./assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">

    <link href="dist/css/pages/file-upload.css" rel="stylesheet">
    <link href="./assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="./assets/node_modules/summernote/dist/summernote.css" rel="stylesheet" />
    <style>
        .non_recurring_dates,
        .recurring_dates {
            min-height: 150px;
            transition: opacity 0.3s ease;
        }

        .non_recurring_dates[hidden],
        .recurring_dates[hidden] {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .non_recurring_dates:not([hidden]),
        .recurring_dates:not([hidden]) {
            opacity: 1;
            visibility: visible;
            position: relative;
        }
    </style>

</head>

<body class="skin-default-dark fixed-layout mini-sidebar lock-nav">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include 'util_header.php'; ?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include 'hotel_utill_side_nav.php'; ?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="mobile-container-padding">
                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <span class="add_top_heading"></span>
                        </div>

                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10 temp_div">
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2 mb-2 temp_div">
                            <img class="p-2" src="<?php echo $image; ?>"
                                onerror="this.src='./assets/images/users/user.png'" alt="user"
                                style="width: 100%; height: 100%;" />
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10">
                            <div class="input-group">
                                <input type="file" class="form-control input_background" accept="image/png, image/jpeg"
                                    id="fileInput">

                            </div>
                            <div class="text-danger" id="errorFileInput"></div>
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2">
                            <div class="input-group-append">
                                <button class="btn btn-cancel btn-upload btn-full-width" id="uploadButton"><i id=""
                                        class="ti-arrow-up"></i>&nbsp;<?= getTranslation('image', $lang) ?></button>
                                <button class="btn btn-danger d-none btn-upload btn-full-width"
                                    id="removeButton"><?= getTranslation('remove', $lang) ?></button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('title', $lang) ?>:</label>
                                <input value="<?php echo $title; ?>" type="text" class="form-control input_background"
                                    id="title" placeholder="Brooklyn">
                                <div class="text-danger" id="errortitle"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('amount', $lang) ?>:</label>
                                <input value="<?php echo $amount ?>" type="text" class="form-control input_background"
                                    id="amount" placeholder="">
                                <div class="text-danger" id="erroramount"></div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-xlg-12 col-md-12">

                            <label class="my-lable"><?= getTranslation('description', $lang) ?></label>




                            <div class="summernote" id="description">
                                <?php echo $description; ?>

                            </div>

                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="input-container">
                                <label class="my-lable">
                                    <input type="checkbox" id="is_recurring" name="is_recurring" <?= ($is_recurring == 1) ? 'checked' : '' ?>>
                                    <?= getTranslation('recurring_voucher', $lang) ?>
                                </label>
                            </div>
                        </div>

                        <!-- Non-recurring dates -->
                        <div class="col-lg-6 col-xlg-6 col-md-6 non_recurring_dates" <?= ($is_recurring == 1) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('active_from', $lang) ?>:</label>
                                <input value="<?php echo htmlspecialchars($active_from); ?>" type="date"
                                    class="form-control input_background" id="active_from" placeholder="">
                                <div class="text-danger" id="errorfrom"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 non_recurring_dates" <?= ($is_recurring == 1) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('active_to', $lang) ?>:</label>
                                <input value="<?php echo htmlspecialchars($active_to); ?>" type="date"
                                    class="form-control input_background" id="active_to" placeholder="">
                                <div class="text-danger" id="errorto"></div>
                            </div>
                        </div>

                        <!-- Recurring dates -->
                        <div class="col-lg-6 col-xlg-6 col-md-6 recurring_dates" <?= ($is_recurring == 0) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('start_month', $lang) ?>:</label>
                                <select class="form-control input_background" id="recurring_start_month">
                                    <option value="">Select Month</option>
                                    <?php
                                    $months = [
                                        1 => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December'
                                    ];
                                    foreach ($months as $num => $name) {
                                        $selected = ($recurring_start_month == $num) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$name</option>";
                                    }
                                    ?>
                                </select>
                                <div class="text-danger" id="error_start_month"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6 recurring_dates" <?= ($is_recurring == 0) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('start_day', $lang) ?>:</label>
                                <select class="form-control input_background" id="recurring_start_day">
                                    <option value="">Select Day</option>
                                    <?php for ($i = 1; $i <= 31; $i++) {
                                        $selected = ($recurring_start_day == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    } ?>
                                </select>
                                <div class="text-danger" id="error_start_day"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6 recurring_dates" <?= ($is_recurring == 0) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('end_month', $lang) ?>:</label>
                                <select class="form-control input_background" id="recurring_end_month">
                                    <option value="">Select Month</option>
                                    <?php foreach ($months as $num => $name) {
                                        $selected = ($recurring_end_month == $num) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$name</option>";
                                    } ?>
                                </select>
                                <div class="text-danger" id="error_end_month"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 recurring_dates" <?= ($is_recurring == 0) ? 'hidden' : '' ?>>
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('end_day', $lang) ?>:</label>
                                <select class="form-control input_background" id="recurring_end_day">
                                    <option value="">Select Day</option>
                                    <?php for ($i = 1; $i <= 31; $i++) {
                                        $selected = ($recurring_end_day == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    } ?>
                                </select>
                                <div class="text-danger" id="error_end_day"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('select_category', $lang) ?>:</label>


                                <select class="select m-b-10` form-control custom-select" style="width: 100%"
                                    id="category_id" name="category">

                                    <?php

                                    $sql = "SELECT * FROM `tbl_category` WHERE `user_id` = $my_user_id_is AND is_delete = 0";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $selected = ($row[0] == $cat_id) ? "selected" : ""; // Check if the option should be selected
                                            ?>
                                            <option value="<?php echo $row[0]; ?>" <?php echo $selected; ?>>
                                                <?php echo $row[1]; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>


                                <div class="text-danger" id="errorto"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('type', $lang) ?>:</label>


                                <select class=" m-b-10  form-control custom-select" style="width: 100%" id="type"
                                    name="type">
                                    <option value="NORMAL" <?= ($type == 'NORMAL') ? 'selected' : '' ?>>
                                        <?= getTranslation('NORMAL', $lang) ?>
                                    </option>
                                    <option value="INTERNAL" <?= ($type == 'INTERNAL') ? 'selected' : '' ?>>
                                        <?= getTranslation('INTERNAL', $lang) ?>
                                    </option>



                                </select>

                                <div class="text-danger" id="errortoo"></div>
                            </div>
                        </div>





                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-cancel btn-full-width"
                                id="cancelButton"><?= getTranslation('cancel', $lang) ?></button>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-create btn-full-width"
                                id="createButton"><?= getTranslation('edit', $lang) ?></button>
                        </div>


                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include 'util_footer.php'; ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="./assets/node_modules/raphael/raphael.min.js"></script>
    <script src="./assets/node_modules/morrisjs/morris.min.js"></script>
    <script src="./assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!--c3 JavaScript -->
    <script src="./assets/node_modules/d3/d3.min.js"></script>
    <script src="./assets/node_modules/c3-master/c3.min.js"></script>


    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <!-- jQuery peity -->
    <script src="./assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="./assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
    <script src="./assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>



    <script src="./assets/node_modules/summernote/dist/summernote.min.js"></script>
    <script>
        jQuery(document).ready(function () {

            $('.summernote').summernote({
                height: 350, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: false // set focus to editable area after initializing summernote
            });

            $('.inline-editor').summernote({
                airMode: true
            });

        });

        window.edit = function () {
            $(".click2edit").summernote()
        },
            window.save = function () {
                $(".click2edit").summernote('destroy');
            }
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var is_image_chnaged = 0;
            // JavaScript code to handle file input, button visibility, and file upload
            const fileInput = document.getElementById('fileInput');
            const uploadButton = document.getElementById('uploadButton');
            const removeButton = document.getElementById('removeButton');
            const createButton = document.getElementById('createButton');
            const cancelButton = document.getElementById('cancelButton');
            const isRecurringCheckbox = document.getElementById('is_recurring');
            const nonRecurringDates = document.querySelectorAll('.non_recurring_dates');
            const recurringDates = document.querySelectorAll('.recurring_dates');

            const editModeImageUrl = "<?php echo $image; ?>";
            uploadButton.classList.add('d-none');
            removeButton.classList.remove('d-none');

            isRecurringCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    nonRecurringDates.forEach(el => el.setAttribute('hidden', ''));
                    recurringDates.forEach(el => el.removeAttribute('hidden'));
                } else {
                    nonRecurringDates.forEach(el => el.removeAttribute('hidden'));
                    recurringDates.forEach(el => el.setAttribute('hidden', ''));
                }
            });

            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 0) {
                    is_image_chnaged = 1;
                    uploadButton.classList.add('d-none');
                    removeButton.classList.remove('d-none');

                    const tempDivElements = document.querySelectorAll('.temp_div');

                    // Loop through the selected elements and hide them
                    tempDivElements.forEach(function (element) {
                        element.style.display = 'none';
                    });
                } else {
                    uploadButton.classList.remove('d-none');
                    removeButton.classList.add('d-none');


                }
                checkFileInputEmpty('fileInput', 'errorFileInput', 'Please choose a image.');
            });

            uploadButton.addEventListener('click', function () {
                // Trigger the file input click event to initiate file selection/upload
                fileInput.click();
            });

            removeButton.addEventListener('click', function () {
                // Add logic here to remove the selected file or handle the removal action.
                // For now, we'll just reset the file input.
                fileInput.value = '';
                uploadButton.classList.remove('d-none');
                removeButton.classList.add('d-none');
                const tempDivElements = document.querySelectorAll('.temp_div');

                // Loop through the selected elements and hide them
                tempDivElements.forEach(function (element) {
                    element.style.display = 'none';
                });
                is_image_chnaged = 1;

            });

            createButton.addEventListener('click', function () {
                // Check if the file input is empty and display an error
                checkFileInputEmpty('fileInput', 'errorFileInput', 'Please choose a Profile image.');
                // Check for empty inputs and display error messages
                checkEmptyInput('title', 'errortitle', 'Please fill Admin Name');
                checkEmptyInput('amount', 'erroramount', 'Please fill Admin Surname Name');
                // Check email format for Input 3 if it's not empty

                if (isRecurringCheckbox.checked) {
                    checkEmptyInput('recurring_start_month', 'error_start_month', 'Please select Start Month');
                    checkEmptyInput('recurring_start_day', 'error_start_day', 'Please select Start Day');
                    checkEmptyInput('recurring_end_month', 'error_end_month', 'Please select End Month');
                    checkEmptyInput('recurring_end_day', 'error_end_day', 'Please select End Day');
                } else {
                    // checkEmptyInput('active_from', 'errorfrom', 'Please select Active From Date');
                    // checkEmptyInput('active_to', 'errorto', 'Please select Active To Date');
                }




                // Check if all inputs are valid before retrieving values
                if (
                    document.querySelectorAll('.input-invalid').length === 0 &&
                    (fileInput.files.length > 0 || is_image_chnaged == 0)
                ) {
                    // All inputs are valid, retrieve values
                    const title = document.getElementById('title').value.trim();
                    const amount = document.getElementById('amount').value.trim();
                    const active_from = document.getElementById('active_from').value.trim();
                    const active_to = document.getElementById('active_to').value.trim();
                    const description = $('#description').summernote('code');
                    let selectedCategoryId = document.getElementById("category_id").value;
                    let type = document.getElementById("type").value;


                    const is_recurring = isRecurringCheckbox.checked ? 1 : 0;
                    const recurring_start_month = is_recurring ? document.getElementById('recurring_start_month').value : '';
                    const recurring_start_day = is_recurring ? document.getElementById('recurring_start_day').value : '';
                    const recurring_end_month = is_recurring ? document.getElementById('recurring_end_month').value : '';
                    const recurring_end_day = is_recurring ? document.getElementById('recurring_end_day').value : '';
                    const fileValue = fileInput.files[0];


                    const id = "<?php echo $id; ?>";

                    var lang = "<?php echo $lang; ?>"


                    var fd = new FormData();

                    var files = $('#fileInput')[0].files;

                    fd.append('file', files[0]);


                    fd.append('title', title);
                    fd.append('amount', amount);
                    fd.append('active_from', active_from);
                    fd.append('active_to', active_to);
                    fd.append('description', description);
                    fd.append('selectedCategoryId', selectedCategoryId);
                    fd.append('save_lang', lang);
                    fd.append('type', type);

                    fd.append('id', id);


                    fd.append('is_image_chnaged', is_image_chnaged);
                    fd.append('editModeImageUrl', editModeImageUrl);

                    fd.append('is_recurring', is_recurring);
                    fd.append('recurring_start_month', recurring_start_month);
                    fd.append('recurring_start_day', recurring_start_day);
                    fd.append('recurring_end_month', recurring_end_month);
                    fd.append('recurring_end_day', recurring_end_day);

                    $.ajax({
                        url: 'utill/add_vochers.php',
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            console.log(response);
                            if (response == '1') {
                                Swal.fire({
                                    title: 'Edit Successfully',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.href = "hotel_vouchers.php";
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something Went Wrong!!!',
                                    footer: ''
                                });
                            }

                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        },
                    });


                }





            });

            cancelButton.addEventListener('click', function () {
                window.history.back();
            });

            // Function to check if an input is empty and display an error message
            function checkEmptyInput(inputId, errorId, errorMessageText) {
                const inputElement = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);
                if (inputElement.value.trim() === '') {
                    inputElement.classList.add('input-invalid');
                    errorElement.textContent = errorMessageText;
                } else {
                    // If the input is not empty, remove the error
                    inputElement.classList.remove('input-invalid');
                    errorElement.textContent = '';
                }
            }

            // Function to validate email format and display an error message
            function validateEmail(inputId, errorId, errorMessageText) {
                const inputElement = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);
                const inputValue = inputElement.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(inputValue)) {
                    inputElement.classList.add('input-invalid');
                    errorElement.textContent = errorMessageText;
                } else {
                    // If the email format is valid, remove the error
                    inputElement.classList.remove('input-invalid');
                    errorElement.textContent = '';
                }
            }
            // Function to check if the file input is empty and display an error message
            function checkFileInputEmpty(inputId, errorId, errorMessageText) {
                const fileInput = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);

                console.log(is_image_chnaged);

                if (is_image_chnaged == 1) {

                    if (fileInput.files.length === 0) {
                        fileInput.classList.add('input-invalid');
                        errorElement.textContent = errorMessageText;
                    } else {
                        // If a file is selected, remove the error
                        fileInput.classList.remove('input-invalid');
                        errorElement.textContent = '';
                    }
                } else {
                    fileInput.classList.remove('input-invalid');
                    errorElement.textContent = '';
                }
            }
        });
    </script>

    <script>
        jQuery(document).ready(function () {
            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function () {
                new Switchery($(this)[0], $(this).data());
            });
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();
            //Bootstrap-TouchSpin
            $(".vertical-spin").TouchSpin({
                verticalbuttons: true
            });
            var vspinTrue = $(".vertical-spin").TouchSpin({
                verticalbuttons: true
            });
            if (vspinTrue) {
                $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
            }
            $("input[name='tch1']").TouchSpin({
                min: 0,
                max: 100,
                step: 0.1,
                decimals: 2,
                boostat: 5,
                maxboostedstep: 10,
                postfix: '%'
            });
            $("input[name='tch2']").TouchSpin({
                min: -1000000000,
                max: 1000000000,
                stepinterval: 50,
                maxboostedstep: 10000000,
                prefix: '$'
            });
            $("input[name='tch3']").TouchSpin();
            $("input[name='tch3_22']").TouchSpin({
                initval: 40
            });
            $("input[name='tch5']").TouchSpin({
                prefix: "pre",
                postfix: "post"
            });
            // For multiselect
            $('#pre-selected-options').multiSelect();
            $('#optgroup').multiSelect({
                selectableOptgroup: true
            });
            $('#public-methods').multiSelect();
            $('#select-all').click(function () {
                $('#public-methods').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function () {
                $('#public-methods').multiSelect('deselect_all');
                return false;
            });
            $('#refresh').on('click', function () {
                $('#public-methods').multiSelect('refresh');
                return false;
            });
            $('#add-option').on('click', function () {
                $('#public-methods').multiSelect('addOption', {
                    value: 42,
                    text: 'test 42',
                    index: 0
                });
                return false;
            });
            $(".ajax").select2({
                ajax: {
                    url: "https://api.github.com/search/repositories",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                // templateResult: formatRepo, // omitted for brevity, see the source of this page
                //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
        });
    </script>





</body>

</html>