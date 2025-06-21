<?php
include 'util_config.php';
include 'util_session.php';
if (isset($_SESSION['my_user_type_is'])) {
    if ($my_user_type_is == 'ADMIN') {
        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
    } else if ($my_user_type_is == 'NORMAL') {
        echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
    } else {

    }
}

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
    <title> Login</title>
    <!-- page css -->
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        body {
            background: url('./assets/images/background/new_bg.png') no-repeat center center fixed;
            background-size: cover;
            width: 100% 100%;
        }

        @media (max-width: 767px) {

            /* Adjustments for screens smaller than 768 pixels wide */
            body {}

            .span_color {
                padding-left: 20px;
            }
        }

        .span_color {
            color: white;
            font-size: 16x;
        }

        .heart_color {
            color: red;
            font-size: 19px;
        }

        .align-bottom {
            display: flex;
            align-items: flex-end;
        }
    </style>
</head>

<body class=" card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"> Login</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->

    <div class="login-register ">
        <div class="login-box card">
            <div class="card-body">
                <div class="form-group ">
                    <div class="col-xs-12 text-center">
                        <a href="/">
                            <img class="img-rounded" height="130px" width="130px" src="./assets/images/favicon.png"
                                alt="HolidayFriend Logo">
                        </a>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" id="loginform">
                    <h3 class="box-title m-b-20">Log In</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" aria-labe="Email" type="email" required=""
                                placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group" id="show_hide_password">
                                <input type="password" required name="password" class="form-control"
                                    id="signin-password" placeholder="Password" autocomplete="off">
                                <div class="input-group-append"><span class="input-group-text"><a
                                            href="javascript:void();"><i class="fa fa-eye-slash"
                                                aria-hidden="true"></i></a></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" name="login">Log
                                In</button>
                        </div>
                    </div>
                </form>
                <!-- enable if need Authentication -->
                <!-- <form class="form-horizontal" onsubmit="check_auth(event);" method="POST" id="recoverform">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>2 Factor Authentication Code</h3>
                            <p class="text-muted">Check your email! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="v_code" id="v_code" type="text" required=""
                                placeholder="CODE">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button
                                class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light"
                                type="submit" name="verify">Verify</button>
                        </div>
                    </div>
                </form> -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-xlg-9 col-md-9">
            </div>
            <div class="col-lg-3 col-xlg-3 col-md-3 align-bottom">
                <span class="span_color">Made With <b class="heart_color">❤</b> By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <a class="" href="https://www.holidayfriend.solutions/"><img
                        onerror="this.src='./assets/images/users/user.png'" src="./assets/images/background/holiday.png"
                        alt="user"></a>
            </div>



        </div>
    </div>



    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>

    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <!--Custom JavaScript -->
    <script type="text/javascript">
        var new_user_id = 0;
        $(function () {
            $(".preloader").fadeOut();
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function () {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });

        $('#login_back').on("click", function () {
            location.reload();
        });
    </script>

    <script>

        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("fa-eye-slash");
                    $('#show_hide_password i').removeClass("fa-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("fa-eye-slash");
                    $('#show_hide_password i').addClass("fa-eye");
                }
            });
        });


        function check_auth(event) {

            event.preventDefault();

            var v_code = document.getElementById("v_code").value;

            $.ajax({
                url: 'utill/check_auth.php',
                method: 'POST',
                data: { v_code: v_code, new_user_id: new_user_id, lan: 'en' },
                success: function (response) {
                    if (response.indexOf('window.location.href') !== -1) {
                        // Extract the URL from the response
                        var match = /window.location.href = "([^"]+)";/.exec(response);
                        if (match && match[1]) {
                            var redirectUrl = match[1];
                            // Redirect the user to the new URL
                            window.location.href = redirectUrl;
                        }
                    } else {
                        Swal.fire({
                            title: 'Invalid Code',
                            type: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                            }
                        });
                    }

                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Invalid Code',
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {
                        }
                    });
                },
            });

        }

        function jsFunction() {
            console.log('abc');
        }

    </script>
</body>

</html>


<?php
if (isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = md5($_POST['password']);
    if ($username == '' || $password == '') {
        echo '<script> alert("Please Fill All Fields..."); </script>';
    } else {
        $name_ = '';
        $email_ = '';

        $sql = " SELECT * FROM `tbl_user` WHERE `email` = '$username' AND `password` = '$password' AND `is_delete` =  0";

        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['status'] == 'ACTIVE') {
                $name = $row['name'];
                $surname = $row['surname'];
                $email = $row['email'];
                $person_phone = $row['person_phone'];
                $image = $row['image'];
                $hotel_name = $row['hotel_name'];
                $hotel_website = $row['hotel_website'];
                $language = $row['language'];
                $user_id = $row['user_id'];
                $user_type = $row['user_type'];

                $_SESSION['my_language_is'] = $language;
                $_SESSION['my_name_is'] = $name;
                $_SESSION['my_surname_is'] = $surname;
                $_SESSION['my_email_is'] = $email;
                $_SESSION['my_user_type_is'] = $user_type;
                $_SESSION['my_image_is'] = $image;
                $_SESSION['my_hotel_name_is'] = $hotel_name;
                $_SESSION['my_hotel_website_is'] = $hotel_website;
                $_SESSION['my_user_id_is'] = $user_id;



                if ($user_type == 'ADMIN') {

                    if ($language == 'EN') {
                        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
                    } else if ($language == 'DE') {
                        echo '<script type="text/javascript">window.location.href = "super_dashboard.php";</script>';
                    } else if ($language == 'IT') {
                        echo '<script type="text/javascript">window.location.href = "it/super_dashboard.php";</script>';
                    }




                } else if ($user_type == 'NORMAL') {
                    if ($language == 'EN') {
                        echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
                    } else if ($language == 'DE') {
                        echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
                    } else if ($language == 'IT') {
                        echo '<script type="text/javascript">window.location.href = "hotel_dashboard.php";</script>';
                    }


                    ?>

                    <?php
                } else {
                    ?>
                        <script>

                            Swal.fire({
                                title: 'You are Not Allow',
                                type: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                }
                            });

                        </script>

                    <?php
                }






                ?>
                <script>
                    <?php





            } else {
                ?>
                        < script >

                        Swal.fire({
                            title: 'Your account is deactivated...',
                            type: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                            }
                        });

                </script>

                <?php
            }

        } else {
            ?>
            <script>

                Swal.fire({
                    title: 'Invalid email or password',
                    type: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.value) {
                    }
                });

            </script>
            <?php
        }

    }
}

?>