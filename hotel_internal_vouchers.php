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
$check = 0;
$sql = "SELECT * FROM `tbl_user` WHERE `user_id` = $my_user_id_is ";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {


        $hotel_name = $row['hotel_name'];
        $hotel_website = $row['hotel_website'];
        $person_phone = $row['person_phone'];

        $is_paypal_live = $row['is_paypal_live'];
        $sandbox_client_id = $row['sandbox_client_id'];
        $sandbox_client_secret = $row['sandbox_client_secret'];
        $live_client_id = $row['live_client_id'];
        $live_client_secret = $row['live_client_secret'];
        $user_code = $row['user_code'];

        if ($is_paypal_live == 1) {
            $check = 'checked';
        } else {

        }



    }
} else {

}

// make ifram

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $iframe_src = "http://localhost/vouchers/api/voucher_widget.php?define=$user_code";
} else {
    $iframe_src = "https://vouchers.qualityfriend.solutions/api/voucher_widget.php?define=$user_code";
}

// The iframe code as a string
$iframe_code = htmlspecialchars("<iframe src=\"$iframe_src\" width=\"100%\" height=\"800px\" style=\"border:none;\"></iframe>");



$page_text = getTranslation('Internal Vouchers', $lang);
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
    <title><?= getTranslation('Internal Vouchers', $lang) ?></title>
    <!-- This page CSS -->
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <style>
        pre {
            background: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            overflow-x: auto;
        }

        .copy-btn {
            display: inline-block;
            margin-top: 5px;
            padding: 5px 10px;
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .copy-btn:hover {
            background: #0056b3;
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


                    <?php

                    if ($_SERVER['HTTP_HOST'] === 'localhost') {
                        ?>
                        <!-- //here -->
                        <iframe src="http://localhost/vouchers/api/voucher_widget.php?define=Q2eNJi930HBXZQ&lang=<?php echo $lang; ?>&type=INTERNAL" width="100%"
                            height="1500px" style="border:none;">

                        </iframe>

                        <?php
                    } else {
                        ?>
                        <!-- //here -->
                        <iframe
                            src="https://vouchers.qualityfriend.solutions/api/voucher_widget.php?define=<?php echo $user_code; ?>&lang=<?php echo $lang; ?>&type=INTERNAL"
                            width="100%" height="1500px" style="border:none;">
                        </iframe>


                        <?php
                    }

                    ?>


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

    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>

        createButton.addEventListener('click', function () {

            // checkEmptyInput('live_client_secret', 'errorlive_client_secret', 'Please fill Admin Name');
            // checkEmptyInput('live_client_secret', 'errorlive_client_secret', 'Please fill Admin Surname Name');
            // checkEmptyInput('sandbox_client_id', 'errorsandbox_client_id', 'Please fill Admin Name');
            // checkEmptyInput('sandbox_client_secret', 'errorsandbox_client_secret', 'Please fill Admin Surname Name');


            const mode = document.getElementById('mode');
            // Check if it's checked or not
            var mode_is = 0;
            if (mode.checked) {
                mode_is = 1;
            } else {
                mode_is = 0;
            }

            // Check if all inputs are valid before retrieving values
            if (
                document.querySelectorAll('.input-invalid').length === 0

            ) {
                // All inputs are valid, retrieve values
                const live_client_id = document.getElementById('live_client_id').value.trim();
                const live_client_secret = document.getElementById('live_client_secret').value.trim();
                const sandbox_client_id = document.getElementById('sandbox_client_id').value.trim();
                const sandbox_client_secret = document.getElementById('sandbox_client_secret').value.trim();

                var fd = new FormData();
                fd.append('live_client_id', live_client_id);
                fd.append('live_client_secret', live_client_secret);
                fd.append('sandbox_client_id', sandbox_client_id);
                fd.append('sandbox_client_secret', sandbox_client_secret);
                fd.append('mode_is', mode_is);
                fd.append('save_lang', 'EN');

                $.ajax({
                    url: 'utill/edit_paypal.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        console.log(response);
                        if (response == '1') {
                            Swal.fire({
                                title: 'Saved',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = "hotel_dashboard.php";
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


    </script>

    <script>
        function copyCode() {
            let codeElement = document.getElementById("iframe-code");
            let textArea = document.createElement("textarea");
            textArea.value = codeElement.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);

        }
    </script>
</body>

</html>