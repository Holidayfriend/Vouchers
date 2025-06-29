<?php
require_once 'util_config.php';
require_once 'util_session.php';

include 'lang/translation.php';
$lang = strtolower($my_language_is);

if (isset($_SESSION['my_user_type_is'])) {
    if ($my_user_type_is == 'ADMIN') {
    } else if ($my_user_type_is == 'NORMAL') {
        echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
    } else {
        echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
} else {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
}



if (isset($_GET['id'])) {
    $id = $_GET['id'];
}


$sql = "SELECT * FROM `tbl_user`  WHERE `user_type` =  'NORMAL' AND `is_delete` = 0 AND `user_id` = $id ";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {

        $user_id = $row['user_id'];
        $name  = $row['name'];
        $surname  = $row['surname'];


        $email = $row['email'];
        $phone = $row['person_phone'];
        $hotel_website = $row['hotel_website'];
        $hotel_name = $row['hotel_name'];
        $hotel_address = $row['hotel_address'];


        $image = $row['image'];
        $status = $row['status'];
    }
} else {
}




$page_text = "Edit Admin";
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
    <title>Vouchers - Edit Admin</title>
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
            <p class="loader__label">Vouchers</p>
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
        <?php include 'super_util_side_nav.php'; ?>
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
                            <span class="add_top_heading">Edit Admin</span>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <span class="add_gray_text">Fill the given details to edit Admin</span>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10 temp_div">
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2 mb-2 temp_div">
                            <img class="p-2" src="<?php echo $image; ?>" onerror="this.src='./assets/images/users/user.png'" alt="user" style="width: 100%; height: 100%;" />
                        </div>
                        <div class="col-lg-10 col-xlg-10 col-md-10">
                            <div class="input-group">
                                <input type="file" class="form-control input_background" accept="image/png, image/jpeg" id="fileInput">

                            </div>
                            <div class="text-danger" id="errorFileInput"></div>
                        </div>
                        <div class="col-lg-2 col-xlg-2 col-md-2">
                            <div class="input-group-append">
                                <button class="btn btn-cancel btn-upload btn-full-width" id="uploadButton"><i id="" class="ti-arrow-up"></i>&nbsp;Profile Image</button>
                                <button class="btn btn-danger d-none btn-upload btn-full-width" id="removeButton">Remove</button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable">Admin Name:</label>
                                <input value="<?php echo $name; ?>" type="text" class="form-control input_background" id="adminname" placeholder="Brooklyn">
                                <div class="text-danger" id="erroradminname"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable">Admin Surname:</label>
                                <input value="<?php echo $surname ?>" type="text" class="form-control input_background" id="adminsurname" placeholder="Admin Surname">
                                <div class="text-danger" id="erroradminsurname"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"> Email:</label>
                                <input value="<?php echo $email ?>" type="email" class="form-control input_background" id="adminemail" placeholder="Admin Email">
                                <div class="text-danger" id="erroradminemail"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"> Telephone:</label>
                                <input value="<?php echo $phone; ?>" type="text" class="form-control input_background" id="admin_tel" placeholder="Admin Telephone">
                                <div class="text-danger" id="erroradmin_tel"></div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable">Hotel Name:</label>
                                <input value="<?php echo $hotel_name; ?>" type="text" class="form-control input_background" id="hotelname" placeholder="Destination Name">
                                <div class="text-danger" id="errorcityname"></div>
                            </div>
                        </div>



                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable">Website:</label>
                                <input value="<?php echo $hotel_website; ?>" type="text" class="form-control input_background" id="website" placeholder="Website">
                                <div class="text-danger" id="erroraddress"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable">Hotel Address:</label>
                                <input value="<?php echo $hotel_address; ?>" type="text" class="form-control input_background" id="website1" placeholder="Address">
                                <div class="text-danger" id="erroraddress1"></div>
                            </div>
                        </div>




                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-cancel btn-full-width" id="cancelButton">Cancel</button>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                            <button class="btn btn-create btn-full-width" id="createButton">Edit</button>
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
        jQuery(document).ready(function() {

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

        window.edit = function() {
                $(".click2edit").summernote()
            },
            window.save = function() {
                $(".click2edit").summernote('destroy');
            }
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var is_image_chnaged = 0;
            // JavaScript code to handle file input, button visibility, and file upload
            const fileInput = document.getElementById('fileInput');
            const uploadButton = document.getElementById('uploadButton');
            const removeButton = document.getElementById('removeButton');
            const createButton = document.getElementById('createButton');
            const cancelButton = document.getElementById('cancelButton');

            const editModeImageUrl = "<?php echo $image; ?>";
            uploadButton.classList.add('d-none');
            removeButton.classList.remove('d-none');

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    is_image_chnaged = 1;
                    uploadButton.classList.add('d-none');
                    removeButton.classList.remove('d-none');

                    const tempDivElements = document.querySelectorAll('.temp_div');

                    // Loop through the selected elements and hide them
                    tempDivElements.forEach(function(element) {
                        element.style.display = 'none';
                    });
                } else {
                    uploadButton.classList.remove('d-none');
                    removeButton.classList.add('d-none');


                }
                checkFileInputEmpty('fileInput', 'errorFileInput', 'Please choose a image.');
            });

            uploadButton.addEventListener('click', function() {
                // Trigger the file input click event to initiate file selection/upload
                fileInput.click();
            });

            removeButton.addEventListener('click', function() {
                // Add logic here to remove the selected file or handle the removal action.
                // For now, we'll just reset the file input.
                fileInput.value = '';
                uploadButton.classList.remove('d-none');
                removeButton.classList.add('d-none');
                const tempDivElements = document.querySelectorAll('.temp_div');

                // Loop through the selected elements and hide them
                tempDivElements.forEach(function(element) {
                    element.style.display = 'none';
                });
                is_image_chnaged = 1;

            });

            createButton.addEventListener('click', function() {
                // Check if the file input is empty and display an error
                checkFileInputEmpty('fileInput', 'errorFileInput', 'Please choose a Profile image.');
                // Check for empty inputs and display error messages
                checkEmptyInput('adminname', 'erroradminname', 'Please fill Admin Name');
                checkEmptyInput('adminsurname', 'erroradminsurname', 'Please fill Admin Surname Name');
                checkEmptyInput('admin_tel', 'erroradmin_tel', 'Please fill Admin Telephone');
                checkEmptyInput('hotelname', 'errorcityname', 'Please fill hotel Name');
                checkEmptyInput('website', 'erroraddress', 'Please fill website That Show On Landing Page');
                // Check email format for Input 3 if it's not empty


                const adminemailValue = document.getElementById('adminemail').value.trim();
                if (adminemailValue !== '') {
                    validateEmail('adminemail', 'erroradminemail', 'Invalid email format');
                } else {
                    checkEmptyInput('adminemail', 'erroradminemail', 'Please fill Admin Email');
                }

                // Check if all inputs are valid before retrieving values
                if (
                    document.querySelectorAll('.input-invalid').length === 0 &&
                    (fileInput.files.length > 0 || is_image_chnaged == 0)
                ) {
                    // All inputs are valid, retrieve values
                    const adminname = document.getElementById('adminname').value.trim();
                    const adminsurname = document.getElementById('adminsurname').value.trim();
                    const admin_tel = document.getElementById('admin_tel').value.trim();
                    const adminemail = document.getElementById('adminemail').value.trim();
                    const hotelname = document.getElementById('hotelname').value.trim();

                    const fileValue = fileInput.files[0];
                    const website = document.getElementById('website').value.trim();


                    const id = "<?php echo $user_id; ?>";


                    var fd = new FormData();

                    var files = $('#fileInput')[0].files;

                    fd.append('file', files[0]);


                    fd.append('adminname', adminname);
                    fd.append('adminsurname', adminsurname);
                    fd.append('admin_tel', admin_tel);
                    fd.append('adminemail', adminemail);
                    fd.append('hotelname', hotelname);
                    fd.append('website', website);
                    fd.append('save_lang', 'EN');

                    fd.append('id', id);


                    fd.append('is_image_chnaged', is_image_chnaged);
                    fd.append('editModeImageUrl', editModeImageUrl);

                    $.ajax({
                        url: 'utill/add_user.php',
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            console.log(response);
                            if (response == '1') {
                                Swal.fire({
                                    title: ' Admin Edit Successfully',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.href = "super_hotel_list.php";
                                    }
                                })
                            } else if (response == "EXIT" || response == "EXITEXIT") {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Admin Email Already Exist!!!',
                                    footer: ''
                                });
                            } else if (response == "EXITC" || response == "EXITCEXITC") {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: ' Name is Already Exist!!!',
                                    footer: ''
                                });
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

            cancelButton.addEventListener('click', function() {
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
        jQuery(document).ready(function() {
            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function() {
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
            $('#select-all').click(function() {
                $('#public-methods').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function() {
                $('#public-methods').multiSelect('deselect_all');
                return false;
            });
            $('#refresh').on('click', function() {
                $('#public-methods').multiSelect('refresh');
                return false;
            });
            $('#add-option').on('click', function() {
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
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
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
                escapeMarkup: function(markup) {
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