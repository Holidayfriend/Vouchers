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
$page_text = "Register Hotel";
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
        <title>Team Card -  Register Hotel</title>
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
                                <span class="add_top_heading" >Add New  Admin</span>
                            </div>
                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <span class="add_gray_text" >Fill the given details to add new  Admin</span>
                            </div>
                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <hr>
                            </div>
                            <div class="col-lg-10 col-xlg-10 col-md-10">
                                <div class="input-group">
                                    <input type="file" class="form-control input_background"  accept="image/png, image/jpeg"   id="fileInput">

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
                                    <label class="my-lable" >Admin Name:</label>
                                    <input type="text" class="form-control input_background" id="adminname" placeholder="Brooklyn">
                                    <div class="text-danger" id="erroradminname"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <div class="input-container">
                                    <label class="my-lable" >Admin Surname:</label>
                                    <input type="text" class="form-control input_background" id="adminsurname" placeholder="Admin Surname">
                                    <div class="text-danger" id="erroradminsurname"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <div class="input-container">
                                    <label class="my-lable" >Email:</label>
                                    <input type="email" class="form-control input_background" id="adminemail" placeholder="Admin Email">
                                    <div class="text-danger" id="erroradminemail"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <div class="input-container">
                                    <label class="my-lable" >Telephone:</label>
                                    <input type="text" class="form-control input_background" id="admin_tel" placeholder="Admin Telephone">
                                    <div class="text-danger" id="erroradmin_tel"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <div class="input-container">
                                    <label class="my-lable" >Hotel Name:</label>
                                    <input type="text" class="form-control input_background" id="hotelname" placeholder="Hotel Name">
                                    <div class="text-danger" id="errorcityname"></div>
                                </div>
                            </div>
                           

                          
                            <div class="col-lg-6 col-xlg-6 col-md-6">
                                <div class="input-container">
                                    <label class="my-lable" >Website:</label>   
                                    <input type="text" class="form-control input_background" id="website" placeholder="Website">
                                    <div class="text-danger" id="erroraddress"></div>
                                </div>
                            </div>


                          

                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                <hr>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                <button class="btn btn-cancel btn-full-width" id="cancelButton">Cancel</button>
                            </div>
                            <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                <button class="btn btn-create btn-full-width" id="createButton">Create</button>
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




        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // JavaScript code to handle file input, button visibility, and file upload
                const fileInput = document.getElementById('fileInput');
                const uploadButton = document.getElementById('uploadButton');
                const removeButton = document.getElementById('removeButton');
                const createButton = document.getElementById('createButton');
                const cancelButton = document.getElementById('cancelButton');

                fileInput.addEventListener('change', function () {
                    if (fileInput.files.length > 0) {
                        uploadButton.classList.add('d-none');
                        removeButton.classList.remove('d-none');
                    } else {
                        uploadButton.classList.remove('d-none');
                        removeButton.classList.add('d-none');
                    }
                    checkFileInputEmpty('fileInput', 'errorFileInput', 'Please choose a file.');
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
                });

                createButton.addEventListener('click', function () {
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
                    }else{
                        checkEmptyInput('adminemail', 'erroradminemail', 'Please fill Admin Email');
                    }


                    // Check if all inputs are valid before retrieving values
                    if (
                        document.querySelectorAll('.input-invalid').length === 0 &&
                        fileInput.files.length > 0
                    ) {
                        // All inputs are valid, retrieve values
                        const adminname = document.getElementById('adminname').value.trim();
                        const adminsurname = document.getElementById('adminsurname').value.trim();
                        const admin_tel = document.getElementById('admin_tel').value.trim();
                        const adminemail = document.getElementById('adminemail').value.trim();
                        const hotelname = document.getElementById('hotelname').value.trim();
                        const fileValue = fileInput.files[0];
                        const website = document.getElementById('website').value.trim();

                        var fd = new FormData();

                        var files = $('#fileInput')[0].files;

                        fd.append('file',files[0]);

                        fd.append('adminname',adminname);
                        fd.append('adminsurname',adminsurname);
                        fd.append('admin_tel',admin_tel);
                        fd.append('adminemail',adminemail);
                        fd.append('hotelname',hotelname);
                        fd.append('website',website);
                        fd.append('save_lang','EN');

                        $.ajax({
                            url:'utill/add_user.php',
                            type: 'post',
                            data:fd,
                            processData: false,
                            contentType: false,
                            success:function(response){

                                console.log(response);
                                if(response == '1'){
                                    Swal.fire({
                                        title: '  Added Successfully',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            window.location.href = "super_hotel_list.php";
                                        }
                                    })
                                } 
                                else if(response == "EXIT" || response == "EXITEXIT"){
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Admin Email Already Exist!!!',
                                        footer: ''
                                    });
                                }else if(response == "EXITC" || response == "EXITCEXITC"){
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'City Name is Already Exist!!!',
                                        footer: ''
                                    });
                                }
                                else{
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
                    if (fileInput.files.length === 0) {
                        fileInput.classList.add('input-invalid');
                        errorElement.textContent = errorMessageText;
                    } else {
                        // If a file is selected, remove the error
                        fileInput.classList.remove('input-invalid');
                        errorElement.textContent = '';
                    }
                }
            });
        </script>







    </body>

</html>