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
        $hotel_address = $row['hotel_address'];
        $person_phone = $row['person_phone'];
        $is_paypal_live = $row['is_paypal_live'];
        $sandbox_client_id = $row['sandbox_client_id'];
        $sandbox_client_secret = $row['sandbox_client_secret'];
        $live_client_id = $row['live_client_id'];
        $live_client_secret = $row['live_client_secret'];
        $user_code = $row['user_code'];
        $analytics_google = $row['analytics_google'];
        $header_color = $row['header_color'];
        $link_color = $row['link_color'];
        $button_color = $row['button_color'];
        $font_family = $row['font_family'];
        $text_color = $row['text_color'];
        $button_text_color = $row['button_text_color'];
        $button_hover = $row['button_hover'];
        $logo = $row['logo'];

        $analytics_google = $row['analytics_google'];

        if ($is_paypal_live == 1) {
            $check = 'checked';
        } else {
        }
    }
} else {
}

// make ifram

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $iframe_src_en = "http://localhost/vouchers/api/voucher_widget.php?define=$user_code&lang=en";
    $iframe_src_it = "http://localhost/vouchers/api/voucher_widget.php?define=$user_code&lang=it";
    $iframe_src_de = "http://localhost/vouchers/api/voucher_widget.php?define=$user_code&lang=de";
} else {
    $iframe_src_en = "https://vouchers.qualityfriend.solutions/api/voucher_widget.php?define=$user_code&lang=en";
    $iframe_src_it = "https://vouchers.qualityfriend.solutions/api/voucher_widget.php?define=$user_code&lang=it";
    $iframe_src_de = "https://vouchers.qualityfriend.solutions/api/voucher_widget.php?define=$user_code&lang=de";
}

// The iframe code as a string
$iframe_code_en = htmlspecialchars("<iframe src=\"$iframe_src_en\" width=\"100%\" height=\"800px\" style=\"border:none;\"></iframe>");

$iframe_code_it = htmlspecialchars("<iframe src=\"$iframe_src_it\" width=\"100%\" height=\"800px\" style=\"border:none;\"></iframe>");


$iframe_code_de = htmlspecialchars("<iframe src=\"$iframe_src_de\" width=\"100%\" height=\"800px\" style=\"border:none;\"></iframe>");


$page_text = getTranslation('dashboard', $lang)
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
    <title><?php echo getTranslation('dashboard', $lang) ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title></title>
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


                <div class="mobile-container-padding pt-3">
                    <div class="row page-titles mb-3   add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <h1><?php echo getTranslation('welcome', $lang) ?></h1>
                            <h6><?php echo getTranslation('hotel_name', $lang) ?> : <?php echo $hotel_name ?></h6>
                            <h6><?php echo getTranslation('hotel_website', $lang) ?> : <?php echo $hotel_website ?></h6>
                            <h6><?php echo getTranslation('hotel_phone', $lang) ?> : <?php echo $person_phone ?></h6>
                             <h6><?php echo getTranslation('hotel_address', $lang) ?> : <?php echo $hotel_address ?></h6>
                        </div>
                    </div>
                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <h3><?php echo getTranslation('ci_settings', $lang) ?></h3>
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10 temp_div">
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2 mb-2 temp_div">
                            <img class="p-2" src="<?php echo $logo; ?>"
                                onerror="this.src='./assets/images/users/user.png'" alt="user"
                                style="width: 100%; height: 100%;" />
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10">
                            <div class="input-group">
                                <input type="file" class="form-control input_background" accept="image/png, image/jpeg"
                                    id="logo">
                            </div>
                            <div class="text-danger" id="error_logo"></div>
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2">
                            <div class="input-group-append">
                                <button
                                    class="btn btn-cancel btn-upload btn-full-width <?php echo !empty($logo) ? 'd-none' : ''; ?>"
                                    id="uploadButton">
                                    <i class="ti-arrow-up"></i> <?php echo getTranslation('image', $lang) ?>
                                </button>
                                <button
                                    class="btn btn-danger btn-upload btn-full-width <?php echo empty($logo) ? 'd-none' : ''; ?>"
                                    id="removeButton">
                                    <?php echo getTranslation('remove', $lang) ?>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('header_color', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="header_color"
                                    value="<?php echo htmlspecialchars($header_color ?? '#FFFFFF'); ?>">
                                <div class="text-danger" id="error_header_color"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('link_color', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="link_color"
                                    value="<?php echo htmlspecialchars($link_color ?? '#0000FF'); ?>">
                                <div class="text-danger" id="error_link_color"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('button_color', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="button_color"
                                    value="<?php echo htmlspecialchars($button_color ?? '#28A745'); ?>">
                                <div class="text-danger" id="error_button_color"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label
                                    class="my-lable"><?php echo getTranslation('button_text_color', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="button_text_color"
                                    value="<?php echo htmlspecialchars($button_text_color ?? '#FFFFFF'); ?>">
                                <div class="text-danger" id="error_button_text_color"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('text_color', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="text_color"
                                    value="<?php echo htmlspecialchars($text_color ?? '#333333'); ?>">
                                <div class="text-danger" id="error_text_color"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('button_hover', $lang) ?>:</label>
                                <input type="color" class="form-control input_background" id="button_hover"
                                    value="<?php echo htmlspecialchars($text_color ?? '#333333'); ?>">
                                <div class="text-danger" id="error_button_hover"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('font_family', $lang) ?>:</label>
                                <select class="form-control input_background" id="font_family">
                                    <option value="Arial" <?php echo ($font_family == 'Arial') ? 'selected' : ''; ?>>Arial
                                    </option>
                                    <option value="Helvetica" <?php echo ($font_family == 'Helvetica') ? 'selected' : ''; ?>>Helvetica</option>
                                    <option value="Roboto" <?php echo ($font_family == 'Roboto') ? 'selected' : ''; ?>>
                                        Roboto</option>
                                    <option value="Open Sans" <?php echo ($font_family == 'Open Sans') ? 'selected' : ''; ?>>Open Sans</option>
                                    <option value="Lato" <?php echo ($font_family == 'Lato') ? 'selected' : ''; ?>>Lato
                                    </option>
                                    <option value="Montserrat" <?php echo ($font_family == 'Montserrat') ? 'selected' : ''; ?>>Montserrat</option>
                                    <option value="Poppins" <?php echo ($font_family == 'Poppins') ? 'selected' : ''; ?>>
                                        Poppins</option>
                                    <option value="Raleway" <?php echo ($font_family == 'Raleway') ? 'selected' : ''; ?>>
                                        Raleway</option>
                                    <option value="Ubuntu" <?php echo ($font_family == 'Ubuntu') ? 'selected' : ''; ?>>
                                        Ubuntu</option>
                                    <option value="Source Sans Pro" <?php echo ($font_family == 'Source Sans Pro') ? 'selected' : ''; ?>>Source Sans Pro</option>
                                    <option value="Georgia" <?php echo ($font_family == 'Georgia') ? 'selected' : ''; ?>>
                                        Georgia</option>
                                    <option value="Times New Roman" <?php echo ($font_family == 'Times New Roman') ? 'selected' : ''; ?>>Times New Roman</option>
                                    <option value="Verdana" <?php echo ($font_family == 'Verdana') ? 'selected' : ''; ?>>
                                        Verdana</option>
                                    <option value="Courier New" <?php echo ($font_family == 'Courier New') ? 'selected' : ''; ?>>Courier New</option>
                                    <option value="Trebuchet MS" <?php echo ($font_family == 'Trebuchet MS') ? 'selected' : ''; ?>>Trebuchet MS</option>
                                    <option value="Playfair Display" <?php echo ($font_family == 'Playfair Display') ? 'selected' : ''; ?>>Playfair Display</option>
                                    <option value="Merriweather" <?php echo ($font_family == 'Merriweather') ? 'selected' : ''; ?>>Merriweather</option>
                                    <option value="Oswald" <?php echo ($font_family == 'Oswald') ? 'selected' : ''; ?>>
                                        Oswald</option>
                                    <option value="Fira Sans" <?php echo ($font_family == 'Fira Sans') ? 'selected' : ''; ?>>Fira Sans</option>
                                    <option value="Noto Sans" <?php echo ($font_family == 'Noto Sans') ? 'selected' : ''; ?>>Noto Sans</option>
                                </select>
                                <div class="text-danger" id="error_font_family"></div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-3">
                            <button class="btn btn-create btn-full-width" id="ciSettingsButton">
                                <?php echo getTranslation('save', $lang) ?>
                            </button>
                        </div>
                    </div>


                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h3 class="mb-0">Code English</h3>
                                <a target="_blank" href="<?php echo $iframe_src_en ?>" class="btn btn-cancel">
                                    <?php echo getTranslation('preview', $lang) ?> <i class="ti-eye"></i>
                                </a>

                            </div>

                            <pre id="iframe-code_en"><?php echo $iframe_code_en; ?></pre>
                            <button class="btn btn-cancel" onclick="copyCode_en()">
                                <?php echo getTranslation('copy', $lang) ?>
                            </button>
                        </div>
                    </div>

                    <!-- Italian Section -->
                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h3 class="mb-0">Code Italian</h3>
                                <a target="_blank" href="<?php echo $iframe_src_it ?>" class="btn btn-cancel">
                                    <?php echo getTranslation('preview', $lang) ?> <i class="ti-eye"></i>
                                </a>
                            </div>

                            <pre id="iframe-code_it"><?php echo $iframe_code_it; ?></pre>
                            <button class="btn btn-cancel" onclick="copyCode_it()">
                                <?php echo getTranslation('copy', $lang) ?>
                            </button>
                        </div>
                    </div>

                    <!-- German Section -->
                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h3 class="mb-0">Code German</h3>
                                <a target="_blank" href="<?php echo $iframe_src_de ?>" class="btn btn-cancel">
                                    <?php echo getTranslation('preview', $lang) ?> <i class="ti-eye"></i>
                                </a>
                            </div>

                            <pre id="iframe-code_de"><?php echo $iframe_code_de; ?></pre>
                            <button class="btn btn-cancel" onclick="copyCode_de()">
                                <?php echo getTranslation('copy', $lang) ?>
                            </button>
                        </div>
                    </div>



                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <h3><?php echo getTranslation('paypal_settings', $lang) ?></h3>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('paypal_client_id', $lang) ?>:</label>
                                <input value="<?php echo $live_client_id; ?>" type="text"
                                    class="form-control input_background" id="live_client_id" placeholder="">
                                <div class="text-danger" id="errorlive_client_id"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label
                                    class="my-lable"><?php echo getTranslation('paypal_client_secret', $lang) ?>:</label>
                                <input value="<?php echo $live_client_secret ?>" type="text"
                                    class="form-control input_background" id="live_client_secret" placeholder="">
                                <div class="text-danger" id="errorlive_client_secret"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label
                                    class="my-lable"><?php echo getTranslation('paypal_sandbox_client_id', $lang) ?>:</label>
                                <input value="<?php echo $sandbox_client_id; ?>" type="text"
                                    class="form-control input_background" id="sandbox_client_id" placeholder="">
                                <div class="text-danger" id="errorsandbox_client_id"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">

                            <div class="input-container">
                                <label
                                    class="my-lable"><?php echo getTranslation('paypal_sandbox_client_secret', $lang) ?>:</label>
                                <input value="<?php echo $sandbox_client_secret ?>" type="text"
                                    class="form-control input_background" id="sandbox_client_secret" placeholder="">
                                <div class="text-danger" id="errorlive_client_secret"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 ">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('paypal_live_mode', $lang) ?></label>
                                <br>
                                <input disabled value="" type="text" class="form-control input_background" id=""
                                    placeholder="">
                                <input <?php echo $check; ?> class="form-check-input input_background" type="checkbox"
                                    id="mode">

                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 ">
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-create btn-full-width"
                                id="createButton"><?php echo getTranslation('paypal_save', $lang) ?></button>
                        </div>
                    </div>



                    <div class="row page-titles mb-3 add_background">

                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <h3><?php echo getTranslation('analytics_google', $lang) ?></h3>


                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <h5><?php echo getTranslation('analytics_google_help', $lang) ?>:</h5>
                            <small><?php echo getTranslation('ga_step_1', $lang) ?></small><br>
                            <small><?php echo getTranslation('ga_step_2', $lang) ?></small><br>
                            <small><?php echo getTranslation('ga_step_3', $lang) ?></small><br>
                            <small><?php echo getTranslation('ga_step_4', $lang) ?></small><br>
                            <small><?php echo getTranslation('ga_step_5', $lang) ?></small><br>
                            <small><?php echo getTranslation('ga_step_6', $lang) ?></small><br>

                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="input-container">
                                <label class="my-lable"><?php echo getTranslation('analytics_google', $lang) ?>:</label>
                                <textarea class="form-control input_background" id="analytics_google" rows="9"
                                    placeholder="'
                                "><?php echo htmlspecialchars($analytics_google); ?></textarea>
                                <div class="text-danger" id="error_analytics_google"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-1">
                            <button class="btn btn-create btn-full-width"
                                id="analyticsButton"><?php echo getTranslation('paypal_save', $lang) ?></button>
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

    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>
        createButton.addEventListener('click', function() {

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
                    success: function(response) {

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
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something Went Wrong!!!',
                                footer: ''
                            });
                        }

                    },
                    error: function(xhr, status, error) {
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
        function copyCode_en() {
            let codeElement = document.getElementById("iframe-code_en");
            let textArea = document.createElement("textarea");
            textArea.value = codeElement.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);

        }

        function copyCode_it() {
            let codeElement = document.getElementById("iframe-code_it");
            let textArea = document.createElement("textarea");
            textArea.value = codeElement.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);

        }

        function copyCode_de() {
            let codeElement = document.getElementById("iframe-code_de");
            let textArea = document.createElement("textarea");
            textArea.value = codeElement.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);

        }
    </script>

    <script>
        analyticsButton.addEventListener('click', function() {
            checkEmptyInput('analytics_google', 'error_analytics_google', 'Please paste the Google Analytics tracking code');

            if (document.querySelectorAll('.input-invalid').length === 0) {
                const analytics_google = document.getElementById('analytics_google').value.trim();

                // console.log(analytics_google);

                $.ajax({
                    url: 'utill/edit_analytics_google.php',
                    type: 'POST',
                    data: {
                        analytics_google: analytics_google
                    }, // Send as regular POST data
                    success: function(response) {
                        Swal.fire({
                            title: 'Saved',
                            icon: 'success', // use `icon` instead of `type` (deprecated)
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "hotel_dashboard.php";
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let is_logo_changed = <?php echo !empty($logo) ? '0' : '1'; ?>;
            const logoInput = document.getElementById('logo');
            const uploadButton = document.getElementById('uploadButton');
            const removeButton = document.getElementById('removeButton');
            const logoImage = document.querySelector('.temp_div img');
            const ciSettingsButton = document.getElementById('ciSettingsButton');
            const editModeLogoUrl = "<?php echo !empty($logo) ? htmlspecialchars($logo) : ''; ?>";

            if (editModeLogoUrl) {
                uploadButton.classList.add('d-none');
                removeButton.classList.remove('d-none');
            } else {
                uploadButton.classList.remove('d-none');
                removeButton.classList.add('d-none');
            }

            logoInput.addEventListener('change', function() {
                if (logoInput.files.length > 0) {
                    is_logo_changed = 1;
                    uploadButton.classList.add('d-none');
                    removeButton.classList.remove('d-none');
                    const file = logoInput.files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    uploadButton.classList.remove('d-none');
                    removeButton.classList.add('d-none');
                    logoImage.src = './assets/images/users/user.png';
                }
                checkFileInputEmpty('logo', 'error_logo', '<?php echo getTranslation('error_logo', $lang) ?: "Please choose a logo image."; ?>');
            });

            uploadButton.addEventListener('click', function() {
                logoInput.click();
            });

            removeButton.addEventListener('click', function() {
                logoInput.value = '';
                uploadButton.classList.remove('d-none');
                removeButton.classList.add('d-none');
                logoImage.src = './assets/images/users/user.png';
                is_logo_changed = 1;
            });

            ciSettingsButton.addEventListener('click', function() {
                checkEmptyInput('header_color', 'error_header_color', '<?php echo getTranslation('error_header_color', $lang) ?: "Please select a header color."; ?>');
                checkEmptyInput('link_color', 'error_link_color', '<?php echo getTranslation('error_link_color', $lang) ?: "Please select a link color."; ?>');
                checkEmptyInput('button_color', 'error_button_color', '<?php echo getTranslation('error_button_color', $lang) ?: "Please select a button color."; ?>');
                checkEmptyInput('font_family', 'error_font_family', '<?php echo getTranslation('error_font_family', $lang) ?: "Please select a font family."; ?>');
                checkEmptyInput('text_color', 'error_text_color', '<?php echo getTranslation('error_text_color', $lang) ?: "Please select a text color."; ?>');
                checkFileInputEmpty('logo', 'error_logo', '<?php echo getTranslation('error_logo', $lang) ?: "Please choose a logo image."; ?>');

                if (document.querySelectorAll('.input-invalid').length === 0 && (logoInput.files.length > 0 || is_logo_changed == 0)) {
                    const headerColor = document.getElementById('header_color').value.trim();
                    const linkColor = document.getElementById('link_color').value.trim();
                    const buttonColor = document.getElementById('button_color').value.trim();
                    const fontFamily = document.getElementById('font_family').value.trim();
                    const textColor = document.getElementById('text_color').value.trim();
                    const buttonTextColor = document.getElementById('button_text_color').value.trim();
                    const logoFile = logoInput.files[0];

                    const formData = new FormData();
                    if (logoFile) {
                        formData.append('logo', logoFile);
                    }
                    formData.append('header_color', headerColor);
                    formData.append('link_color', linkColor);
                    formData.append('button_color', buttonColor);
                    formData.append('font_family', fontFamily);
                    formData.append('text_color', textColor);
                    formData.append('button_text_color', buttonTextColor);
                    formData.append('is_logo_changed', is_logo_changed);
                    formData.append('editModeLogoUrl', editModeLogoUrl);

                    $.ajax({
                        url: 'utill/edit_ci_setting.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '<?php echo getTranslation('error_saving', $lang) ?: "Something went wrong!"; ?>'
                            });
                        }
                    });
                }
            });

            function checkEmptyInput(inputId, errorId, errorMessage) {
                const inputElement = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);
                if (inputElement.value.trim() === '') {
                    inputElement.classList.add('input-invalid');
                    errorElement.textContent = errorMessage;
                } else {
                    inputElement.classList.remove('input-invalid');
                    errorElement.textContent = '';
                }
            }

            function checkFileInputEmpty(inputId, errorId, errorMessage) {
                const fileInput = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);
                if (is_logo_changed == 1 && fileInput.files.length === 0) {
                    fileInput.classList.add('input-invalid');
                    errorElement.textContent = errorMessage;
                } else {
                    fileInput.classList.remove('input-invalid');
                    errorElement.textContent = '';
                }
            }
        });
    </script>
</body>

</html>