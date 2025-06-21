<?php


$main_url = basename($_SERVER['REQUEST_URI']);
if ($main_url == "teamcard") {
    $main_url = "index";
}
require_once 'util_config.php';
require_once 'util_session.php';

if (!isset($_SESSION['my_email_is'])) {
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php';
    </script>
    <?php
}
if (isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    echo "<script>  location.href='index.php';</script>";
}

// $count = 0;
// $sql21 ="SELECT COUNT(*) as count FROM `tbl_notification` where user_id = $my_user_id_ and is_view = 0";
// $result21= $conn->query($sql21);
// if ($result21 && $result21->num_rows > 0) {
//     while($row = mysqli_fetch_array($result21)) {
//         $count  = $row['count'];

//     }}



?>

<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <li id="" class="nav-item pl-3 header_show header_first_text_is">
                    <h4 class="margin_top_h1" ><?php echo $page_text; ?></h4>
                </li>


                <li class="nav-item hidden-sm-up"> <a class="nav-link nav-toggler waves-effect waves-light"
                        href="javascript:void(0)"><i class="ti-menu"></i></a></li>


            </ul>

            <ul class="navbar-nav mr-auto">

                <?php
                if (isset($search)) {
                    ?>

                    <li id="" class="nav-item pl-3 header_show header_secound_text_is margin_top_h">

                        <form method="get">
                            <div class="input-group pl-3">
                                <input type="text" name="search" id="searchInput" value="<?php echo $search; ?>"
                                    placeholder="<?= getTranslation('search_by', $lang) ?>"
                                    class="form-control input_background ">
                                <div class="input-group-append pointer">
                                    <button class="input-group-text" type="submit"><i id="searchButton"
                                            class="ti-search"></i></button>
                                </div>
                            </div>

                        </form>

                    </li>

                <?php } ?>
            </ul>

            <ul class="navbar-nav my-lg-0 ">
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->

                <li class="nav-item dropdown">
                    <a class="nav-link margin_top_h1" href="javascript:void(0)" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-globe text-primary1" aria-hidden="true"></i> <?php echo $my_language_is; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                        <!-- Dropdown header -->
                        <div class="px-3 py-3">
                            <h6 class="" style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                Choose Language</h6>
                        </div>
                        <!-- List group -->
                        <div class="list-group list-group-flush">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action <?php if ($my_language_is == 'EN') {
                                echo 'active_lang';
                            } ?> lang_hover" onclick="lang_change('EN')">
                                <div class="row align-items-center">
                                    <div class="col pl-4 pr-3">
                                        <h3 class="mb-0"
                                            style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                            EN</h3>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action <?php if ($my_language_is == 'IT') {
                                echo 'active_lang';
                            } ?> lang_hover" onclick="lang_change('IT')">
                                <div class="row align-items-center">
                                    <div class="col pl-4 pr-3">
                                        <h3 class="mb-0"
                                            style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                            IT</h3>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action <?php if ($my_language_is == 'DE') {
                                echo 'active_lang';
                            } ?> lang_hover" onclick="lang_change('DE')">
                                <div class="row align-items-center">
                                    <div class="col pl-4 pr-3">
                                        <h3 class="mb-0"
                                            style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                            DE</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>


                <?php

                if ($my_user_type_is == 'BENEFIT_HOLDER' || $my_user_type_is == 'BENEFIT_PROVIDER' || $my_user_type_is == 'CITY_ADMIN') {




                    ?>
                    <li class="nav-item dropdown">
                        <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark" href="notification.php" > <i class="ti-bell"></i>
                        <div class="notify" id="result_noti_num"> 

                            < ?php if($count > 0){ ?>
                            <span class="heartbit">

                            </span> <span class="point">< ?php echo $count; ?></span>

                            < ?php } ?>

                        </div>
                    </a> -->



                    </li>
                    <?php
                } else {

                }


                ?>




                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark margin_top_h1" href=""
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="img-circle-container"><img onerror="this.src='./assets/images/users/user.png'"
                                src="<?php echo $my_image_is; ?>" alt="user" class="img-circle" width="30"></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY"
                        style="border-bottom:10px solid #6C8EA1!important;">
                        <span class="with-arrow"><span class="bg-info"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-info text-white m-b-10">
                            <div class="img-circle-container">
                                <img onerror="this.src='./assets/images/users/user.png'"
                                    src="<?php echo $my_image_is; ?>" alt="user" class="img-circle">
                            </div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?php echo $my_hotel_name_is; ?></h4>
                                <p class=" m-b-0"><?php echo $my_email_is; ?></p>
                            </div>
                        </div>
                        <form id="my_form" method="POST">
                            <span class="dropdown-item"><i class="ti-unlink m-r-5 m-l-5"></i>
                                <input class="btn p-0 w-100" style="text-align:left;" type="submit" name="logout"
                                    value="Logout" />
                            </span>
                        </form>

                    </div>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link black_color font_name minus_padding"></a>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>


        </div>

    </nav>


</header>
<script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script>
    function goBack() {
        history.back();
    }
    function lang_change(type) {
        console.log(type);
        var fd = new FormData();
        fd.append('type', type);

        $.ajax({
            url: 'utill/update_language.php',
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            success: function (response) {

                console.log(response);
                window.location.href = window.location.href;


            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }

</script>