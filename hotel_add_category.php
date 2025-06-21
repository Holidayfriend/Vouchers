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
$page_text = getTranslation('add_categoery', $lang);
$back = 'yes';

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title><?= getTranslation('add_categoery', $lang) ?></title>
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


</head>

<body class="skin-default-dark fixed-layout mini-sidebar lock-nav">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?= getTranslation('add_categoery', $lang) ?></p>
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
                            <span class="add_top_heading"><?= getTranslation('add_categoery', $lang) ?></span>
                        </div>

                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>


                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('title', $lang) ?>:</label>
                                <input type="text" class="form-control input_background" id="title" placeholder="">
                                <div class="text-danger" id="errortitle"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="form-check form-switch mt-4 p-4">
                                <input class="form-check-input" type="checkbox" id="check">
                                <label class="form-check-label" for="check"><?= getTranslation('fix_amount', $lang) ?></label>
                            </div>
                        </div>








                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-cancel btn-full-width" id="cancelButton"><?= getTranslation('cancel', $lang) ?></button>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-create btn-full-width" id="createButton"><?= getTranslation('create', $lang) ?></button>
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

    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <!-- ============================================================== -->



    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>


    <script src="./assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>


    <script src="./assets/node_modules/summernote/dist/summernote.min.js"></script>






    <script>
        document.addEventListener("DOMContentLoaded", function () {


            createButton.addEventListener('click', function () {

                // Check for empty inputs and display error messages
                checkEmptyInput('title', 'errortitle', 'Please fill');

                var check_is = 0;
                if (check.checked) {
                    check_is = 1;
                } else {
                    check_is = 0;
                }




                // Check if all inputs are valid before retrieving values
                if (
                    document.querySelectorAll('.input-invalid').length === 0 
                ) {
                    // All inputs are valid, retrieve values
                    const title = document.getElementById('title').value.trim();
                    var fd = new FormData();
                    fd.append('name', title);
                    fd.append('fixed', check_is);


                    $.ajax({
                        url: 'utill/add_category.php',
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            console.log(response);
                            window.location.href = "hotel_categories.php";
                            

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

         
            
        });
    </script>







</body>

</html>