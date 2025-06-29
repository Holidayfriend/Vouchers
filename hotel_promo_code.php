<?php
require_once 'util_config.php';
require_once 'util_session.php';
include 'lang/translation.php';
$lang = strtolower($my_language_is);
// echo getTranslation('app_name', $lang);

//  exit;


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




$page_text = getTranslation('promo_code', $lang);




// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $searchCondition = "AND (`code` = '$search' OR `start_date` LIKE '%$search%')";
} else {
    $searchCondition = '';
}

// Filter functionality
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all'; // Default to 'all'
$cityFilter = isset($_GET['city']) ? $_GET['city'] : 'all'; // Default to 'all'
$expiryFilter = isset($_GET['expiry']) ? $_GET['expiry'] : '';

$filterConditions = array();
$filterCondition = '';
if ($statusFilter === 'active') {
    $filterCondition = "AND status = 'ACTIVE'";
} elseif ($statusFilter === 'inactive') {
    $filterCondition = "AND status = 'DEACTIVE'";
}

// if ($cityFilter !== 'all') {
//     $filterCondition .= " AND benefit_type = '$cityFilter'  ";
// }
// if (!empty($expiryFilter)) {
//     $filterCondition .= "AND expiry = '$expiryFilter'";
// }
// Remove the trailing "AND" if there are filter conditions
if (!empty($filterCondition)) {
    //    $filterCondition = ' AND ' . rtrim($filterCondition, ' ');
}



// Query to fetch a page of user records with search
$sql = "SELECT * FROM `tbl_promocodes` WHERE `user_id` =  $my_user_id_is AND is_delete = 0 $searchCondition $filterCondition ";
$result = $conn->query($sql);
$sql_mobile = $sql;

// Query to count total records with search
$countSql = "SELECT COUNT(*) as total FROM `tbl_promocodes`  WHERE `user_id` =  $my_user_id_is AND is_delete = 0 $searchCondition $filterCondition";



$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];



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
    <title><?= getTranslation('promo_code', $lang) ?></title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--c3 plugins CSS -->
    <link href="./assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">

    <style>
        .pagination.no-space li:not(.active) {
            margin-right: 0;
        }

        .mmm {
            width: 340px;
        }

        table thead tr {
            background-color: #F8FAFB;
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
            <p class="loader__label"><?= getTranslation('promo_code', $lang) ?></p>
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
                    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog"
                        aria-labelledby="filterModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="dark_gray_color modal-title" id="filterModalLabel">
                                        <?= getTranslation('filter', $lang) ?>
                                    </h5>
                                    <span onclick="reset()" class="pointer"><i
                                            class="mdi mdi-refresh"></i>&nbsp;<?= getTranslation('reset', $lang) ?></span>


                                </div>
                                <div class="modal-body">
                                    <form id="filterForm">



                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="filterStatus"
                                                id="filterAll" value="all" <?php echo ($statusFilter === 'all') ? 'checked' : ''; ?>>
                                            <label class="form-check-label"
                                                for="filterAll"><?= getTranslation('all', $lang) ?></label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="filterStatus"
                                                id="filterActive" value="active" <?php echo ($statusFilter === 'active') ? 'checked' : ''; ?>>
                                            <label class="form-check-label"
                                                for="filterActive"><?= getTranslation('active', $lang) ?> </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="filterStatus"
                                                id="filterInactive" value="inactive" <?php echo ($statusFilter === 'inactive') ? 'checked' : ''; ?>>
                                            <label class="form-check-label"
                                                for="filterInactive"><?= getTranslation('deactive', $lang) ?> </label>
                                        </div>


                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <div class="row">
                                        <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                            <button data-dismiss="modal" class="btn btn-cancel btn-full-width"
                                                id="cancelButton"><?= getTranslation('cancel', $lang) ?> </button>
                                        </div>
                                        <!-- <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                            <button class="btn btn-create btn-full-width"
                                                id="applyFilter">< ?= getTranslation('apply_filter', $lang) ?></button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row page-titles mb-3  ">
                        <div class="col-md-12">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <span id="rowCount"><b
                                            class="text_black_color"><?= getTranslation('promo_code_list', $lang) ?>(</b><b
                                            class="text_black_red"><?php echo $totalRecords; ?></b><b
                                            class="text_black_color">)</b></span>
                                </div>
                                <div class="col-md-6 text-right">

                                    <span id="deleteButton" class="pl-2 pr-2 text_black_color pointer"><i
                                            class="fas fa-trash-alt"></i>&nbsp;<?= getTranslation('delete', $lang) ?></span>
                                    <!-- <span data-toggle="modal" data-target="#filterModal" id=""
                                        class="pl-2 pr-2 text_black_color pointer"> <i
                                            class="fas fa-filter"></i>&nbsp;< ?= getTranslation('filter', $lang) ?></span> -->
                                    <button onclick="url_redirect('hotel_add_promo_code.php')" class="btn btn-add"
                                        id="addButton"> <i
                                            class="mdi mdi-plus"></i><?= getTranslation('add_promo_code', $lang) ?></button>
                                </div>
                            </div>
                            <div class="row mt-3">


                                <?php

                                $result_mobile = $conn->query($sql_mobile);
                                if (mysqli_num_rows($result_mobile) > 0) {
                                    while ($row = mysqli_fetch_array($result_mobile)) {


                                        $promo_code_id = $row['promo_code_id'];
                                        $code = $row['code'];
                                        $discount_type = $row['discount_type'];
                                        $discount_value = $row['discount_value'];
                                        $max_uses = $row['max_uses'];
                                        $current_uses = $row['current_uses'];
                                        $start_date = $row['start_date'];
                                        $end_date = $row['end_date'];
                                        $end_date = $row['end_date'];
                                        $what = $row['what'];
                                        $voucher_id = $row['voucher_id'];
                                        $category_id = $row['category_id'];

                                        $what = $row['what'];

                                        $promo_for = '';
                                        if ($what == 'all') {

                                            $promo_for = getTranslation('general_for_all', $lang);
                                        } else if ($what == 'voucher') {



                                            $sub_query = "SELECT * FROM `tbl_voucher` WHERE `id` = $voucher_id";
                                            $sub_result = mysqli_query($conn, $sub_query);

                                            // Check if query executed successfully
                                            if ($sub_result) {
                                                // Loop through the result set
                                                while ($sub_row = mysqli_fetch_assoc($sub_result)) {
                                                    $title = '';
                                                    if ($lang == 'en') {

                                                        if ($sub_row['title'] != "") {
                                                            $title = $sub_row['title'];
                                                        } else if ($sub_row['title_it'] != "") {
                                                            $title = $sub_row['title_it'];
                                                        } else if ($sub_row['title_de'] != "") {
                                                            $title = $sub_row['title_de'];
                                                        }
                                                    } else if ($lang == 'it') {
                                                        if ($sub_row['title_it'] != "") {
                                                            $title = $sub_row['title_it'];
                                                        } else if ($sub_row['title'] != "") {
                                                            $title = $sub_row['title'];
                                                        } else if ($sub_row['title_de'] != "") {
                                                            $title = $sub_row['title_de'];
                                                        }
                                                    } else {
                                                        if ($sub_row['title_de'] != "") {
                                                            $title = $sub_row['title_de'];
                                                        } else if ($sub_row['title'] != "") {
                                                            $title = $sub_row['title'];
                                                        } else if ($sub_row['title_it'] != "") {
                                                            $title = $sub_row['title_it'];
                                                        }
                                                    }
                                                }
                                            } else {
                                            }

                                            $promo_for =   getTranslation('vouchers_based', $lang) . ' : ' . $title;
                                        } else {

                                            $sub_query = "SELECT * FROM `tbl_category` WHERE `id` = $category_id";
                                            $sub_result = mysqli_query($conn, $sub_query);

                                            // Check if query executed successfully
                                            if ($sub_result) {
                                                // Loop through the result set
                                                while ($sub_row = mysqli_fetch_assoc($sub_result)) {
                                                    if ($lang == 'en') {

                                                        if ($sub_row['name'] != "") {
                                                            $name = $sub_row['name'];
                                                        } else if ($sub_row['name_it'] != "") {
                                                            $name = $sub_row['name_it'];
                                                        } else if ($sub_row['name_de'] != "") {
                                                            $name = $sub_row['name_de'];
                                                        }
                                                    } else if ($lang == 'it') {
                                                        if ($sub_row['name_it'] != "") {
                                                            $name = $sub_row['name_it'];
                                                        } else if ($sub_row['name'] != "") {
                                                            $name = $sub_row['name'];
                                                        } else if ($sub_row['name_de'] != "") {
                                                            $name = $sub_row['name_de'];
                                                        }
                                                    } else {
                                                        if ($sub_row['name_de'] != "") {
                                                            $name = $sub_row['name_de'];
                                                        } else if ($sub_row['name'] != "") {
                                                            $name = $sub_row['name'];
                                                        } else if ($sub_row['name_de'] != "") {
                                                            $name = $sub_row['name_de'];
                                                        }
                                                    }
                                                }
                                            } else {
                                            }
                                            $promo_for =   getTranslation('category_based', $lang);
                                             $promo_for =   getTranslation('vouchers_based', $lang) . ' : ' . $name;
                                        }






                                ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div onclick="toggleSelect(<?php echo $promo_code_id; ?>)"
                                                id="<?php echo $promo_code_id; ?>" class="row single_div_io pointer">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-12 text-right">
                                                            <a
                                                                href="hotel_edit_promo_code.php?id=<?php echo $promo_code_id; ?>">
                                                                <span class="single_div__dark_text edit_hover">
                                                                    <i class="mdi mdi-lead-pencil"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <h5 class="dark_gray_color">
                                                                <b><?php echo htmlspecialchars($code); ?></b>
                                                            </h5>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <span><b><?= getTranslation('discount_type', $lang) ?>:</b>
                                                                <?php echo htmlspecialchars($discount_type); ?></span><br>
                                                            <span><b><?= getTranslation('discount_value', $lang) ?>:</b>
                                                                <?php echo htmlspecialchars($discount_value); ?></span><br>
                                                            <span><b><?= getTranslation('max_uses', $lang) ?>:</b>
                                                                <?php echo $max_uses == 0 ? getTranslation('unlimited', $lang) : htmlspecialchars($max_uses); ?></span><br>
                                                            <span><b><?= getTranslation('current_uses', $lang) ?>:</b>
                                                                <?php echo htmlspecialchars($current_uses); ?></span><br>
                                                            <span><b><?= getTranslation('start_date', $lang) ?>:</b>
                                                                <?php echo date('Y-m-d', strtotime($start_date)); ?></span><br>
                                                            <span><b><?= getTranslation('end_date', $lang) ?>:</b>
                                                                <?php echo date('Y-m-d', strtotime($end_date)); ?></span><br>
                                                            <span><b><?php echo $promo_for ?></b></span><br>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    }
                                } else {
                                    ?>

                                    <div style="text-align: center;">
                                        <h3 style="margin: 0; padding: 20px; color: #333;">
                                            <?= getTranslation('not_found', $lang) ?>
                                        </h3>
                                    </div>

                                <?php } ?>
                            </div>

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


    <script>
        var selectedIds = [];

        // Function to toggle the selection of a div by ID
        function toggleSelect(id) {
            var div = document.getElementById(id);
            if (div) {
                if (div.classList.contains("selected")) {
                    div.classList.remove("selected");
                    // Remove ID from the selectedIds array
                    selectedIds = selectedIds.filter(function(item) {
                        return item !== id;
                    });
                } else {
                    div.classList.add("selected");
                    // Add ID to the selectedIds array
                    selectedIds.push(id);
                }
            }
            // Show or hide delete button based on selection
            if (selectedIds.length > 0) {
                $("#deleteButton").show();
            } else {
                $("#deleteButton").hide();
            }
        }

        function action(event, id, what, name) {
            event.stopPropagation(); // Prevent the outer div's click event
            var fd = new FormData();


            fd.append('id', id);
            fd.append('what', what);
            fd.append('base', 'voucher');
            fd.append('name', 'id');
            $.ajax({
                url: 'utill/active_or_dective.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function(response) {

                    console.log(response);
                    if (what == 'DEACTIVE') {
                        what = 'Successfully deactivated'
                    } else {
                        what = 'Successfully activated'
                    }

                    if (response.trim() === 'done') {
                        Swal.fire({
                            title: what,
                            text: name + ' is ' + what,
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.href = "hotel_vouchers.php";
                            }
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



        // JavaScript code for "Select All" checkbox functionality
        $(document).ready(function() {


            // AJAX request to delete selected rows
            $("#deleteButton").click(function() {
                if (selectedIds.length === 0) {
                    alert("Please select at least one row to delete.");
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "utill/del.php",
                    data: {
                        selectedRows: selectedIds,
                        base: 'promocodes',
                        base_name: 'promo_code_id'
                    },
                    success: function(response) {
                        // Display the response message from del.php (e.g., success or error message)
                        // After successful deletion, refresh the page

                        Swal.fire({
                            title: 'Deleted',
                            text: '',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        });




                    },
                    error: function() {
                        alert("An error occurred while deleting selected rows.");
                    }
                });
            });
        });
    </script>
    <script>
        function url_redirect(url) {
            // Redirect the user to the specified URL
            window.location.href = url;
        }

        function reset() {
            // Redirect the user to the specified URL
            window.location.href = 'city_benefit.php';
        }
    </script>



    <script>
        // JavaScript code for handling the search input
        $(document).ready(function() {
            // Get the search input element
            var searchInput = $("#searchInput");

            // Retrieve the search parameter from the URL and populate the search input
            var searchParam = new URLSearchParams(window.location.search).get("search");
            searchInput.val(searchParam);

            // Function to update the URL with the search parameter
            function updateUrl(searchTerm) {
                var url = window.location.href.split('?')[0]; // Get the current URL without query parameters
                if (searchTerm) {
                    url += "?search=" + searchTerm;
                }
                window.history.replaceState({}, '', url); // Replace the URL without triggering a full page reload
            }

            // Handle changes in the search input
            searchInput.on("input", function() {
                var searchTerm = searchInput.val();
                updateUrl(searchTerm);

                // Check if the search input is completely empty
                if (searchTerm.trim() === "") {
                    // If empty, reload the page to clear the search results
                    location.reload();
                }

                // You can also trigger an AJAX request to update the results based on the search term here
                // ...
            });

            // ... (the rest of your JavaScript code) ...
        });
    </script>
    <script>
        // JavaScript code for applying and resetting filters
        $(document).ready(function() {
            // Get the filter form elements
            // JavaScript code for applying and resetting filters
            var filterForm = $("#filterForm");
            var applyFilterButton = $("#applyFilter");
            var resetFilterButton = $("#resetFilter");
            var clearCityFilterButton = $("#clearCityFilter");
            var clearDateFilterButton = $("#clearDateFilter");

            applyFilterButton.click(function() {
                var filterStatus = filterForm.find('input[name="filterStatus"]:checked').val();
                var cityFilter = filterForm.find('select[name="cityFilter"]').val();
                var expiryFilter = filterForm.find('input[name="expiry"]').val();

                var url = window.location.href.split('?')[0];
                var params = [];

                if (filterStatus && filterStatus !== 'all') {
                    params.push("status=" + filterStatus);
                }

                if (cityFilter && cityFilter !== 'all') {
                    params.push("city=" + cityFilter);
                }

                if (expiryFilter) {
                    params.push("expiry=" + expiryFilter);
                }

                if (params.length > 0) {
                    url += "?" + params.join("&");
                }

                window.location.href = url;
            });

            resetFilterButton.click(function() {
                var url = window.location.href.split('?')[0];
                window.location.href = url;
            });

            clearCityFilterButton.click(function() {
                filterForm.find('select[name="cityFilter"]').val('all');
            });

            clearDateFilterButton.click(function() {
                filterForm.find('input[name="expiry"]').val('');
                applyFilters();
            });
        });
    </script>



</body>

</html>