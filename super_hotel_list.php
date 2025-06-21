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

$page_text = getTranslation('hotel_list', $lang) ;



// Pagination configuration
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $searchCondition = "AND (`name` = '$search' OR `name` LIKE '%$search%' OR `email` LIKE '%$search%' 
    OR `name` LIKE '%$search%')";
} else {
    $searchCondition = '';
}

// Filter functionality
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all'; 
$filterCondition = '';
if ($statusFilter === 'active') {
    $filterCondition = "AND `status` = 'ACTIVE'";
} elseif ($statusFilter === 'inactive') {
    $filterCondition = "AND `status` = 'DEACTIVE'";
}


// Query to fetch a page of user records with search
$sql = "SELECT * FROM `tbl_user` WHERE `is_delete` = 0 and `user_type` = 'NORMAL' $searchCondition $filterCondition LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);
$sql_mobile = $sql;

// Query to count total records with search
$countSql = "SELECT COUNT(*) as  total FROM `tbl_user` WHERE `is_delete` = 0 and `user_type` = 'NORMAL' $searchCondition $filterCondition"; 


//echo $countSql;
//
//exit;

$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalRecords / $recordsPerPage);

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
        <title>Vouchers - <?php echo getTranslation('hotel_list', $lang) ?></title>
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
                        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="dark_gray_color modal-title" id="filterModalLabel">Filter</h5>
                                        <span onclick="reset()" class="pointer" ><i class="mdi mdi-refresh"></i>&nbsp;Reset</span>


                                        <!--
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
-->
                                    </div>
                                    <div class="modal-body">
                                        <form id="filterForm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="filterStatus" id="filterAll" value="all"
                                                       <?php echo ($statusFilter === 'all') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="filterAll">All Users</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="filterStatus" id="filterActive"
                                                       value="active" <?php echo ($statusFilter === 'active') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="filterActive">Active Users</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="filterStatus" id="filterInactive"
                                                       value="inactive" <?php echo ($statusFilter === 'inactive') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="filterInactive">Inactive Users</label>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="row" >
                                            <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                                <button data-dismiss="modal" class="btn btn-cancel btn-full-width" id="cancelButton">Cancel</button>
                                            </div>
                                            <div class="col-lg-6 col-xlg-6 col-md-6 mt-1">
                                                <button class="btn btn-create btn-full-width" id="applyFilter">Apply Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row page-titles mb-3 heading_style desktop">
                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <span id="rowCount"><b class="text_black_color" >Result (</b><b class="text_black_red" ><?php echo $totalRecords; ?></b><b class="text_black_color" >)</b></span>
                                    </div>
                                    <div class="col-md-6 text-right">

                                        <span id="deleteButton" class="pl-2 pr-2 text_black_color pointer" ><i class="fas fa-trash-alt"></i>&nbsp;Delete</span>
                                        <!--                                        <span data-toggle="modal" data-target="#filterModal" id="" class="pl-2 pr-2 text_black_color pointer" >  <i class="fas fa-filter"></i>&nbsp;Filter</span>-->
                                        <button onclick="url_redirect('super_add_hotel.php')" class="btn btn-add" id="addButton"> <i class="mdi mdi-plus"></i> Add Hotel Admin</button>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                  
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Hotel Name</th>
                                                 
                                                    <th>Edit</th>
                                                    <th class="text-center" >Created Date</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()) :



                                                $name = $row['name'];
                                                $surname = $row['surname'];
                                                $person_phone	 = $row['person_phone'];
                                                $email = $row['email'];
                                                $status = $row['status'];
                                                $hotel_name = $row['hotel_name'];
                                                $user_id = $row['user_id'];
                                                

                                                $image_url  =  $row['image'];
                                                $date  =  $row['entry_by_time'];  
                                                $edit_is = 'super_edit_hotel.php?id='.$user_id

                                                ?>
                                                <tr>



                                                  
                                                    <td>
                                                        <img src="<?php echo $image_url; ?>" onerror="this.src='./assets/images/users/user.png'"  alt="user" width="40" height="40" class="rounded-circle"  />&nbsp;&nbsp;<?php echo $name; ?></td>


                                                    <td>

                                                        <i class="fas fa-envelope row_icon_size"></i>&nbsp;&nbsp;<?php echo $email; ?>


                                                    </td>


                                                    <td  class="" ><span class=" " ><?php echo $hotel_name;  ?></span></td>
                                                    


                                                    <td  class="text-center" >



                                                        <a href="<?php echo $edit_is; ?>" ><i class="mdi mdi-lead-pencil"></i> </a>
                                                    </td>



                                                    <td  class="text-center" ><span class=" " ><?php echo $date;  ?></span></td>



                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="pagination justify-content-between">
                                            <!-- Previous Button -->
                                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link page-linknew" href="?page=<?php echo ($page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                                    <span class="desktop pagination_color" aria-hidden="true"> <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Previous</span>

                                                    <span class="mobile pagination_color" aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            <li class="mmm desktop"></li>

                                            <!-- First Page -->
                                            <?php if ($page > 2) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=1&search=<?php echo $search; ?>">1</a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Dots for middle pages -->
                                            <?php if ($page > 3) : ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Display the previous page if it exists -->
                                            <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo ($page - 1); ?>&search=<?php echo $search; ?>"><?php echo ($page - 1); ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Display the current page -->
                                            <li class="page-item active">
                                                <span class="page-link"><?php echo $page; ?></span>
                                            </li>

                                            <!-- Display the next page if it exists -->
                                            <?php if ($page < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo ($page + 1); ?>&search=<?php echo $search; ?>"><?php echo ($page + 1); ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Dots for middle pages -->
                                            <?php if ($page < $totalPages - 2) : ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Last Page -->
                                            <?php if ($page < $totalPages - 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo $search; ?>"><?php echo $totalPages; ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <li class="mmm desktop"></li>
                                            <!-- Next Button -->
                                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                                <a class="page-link page-linknew" href="?page=<?php echo ($page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                                    <span class="desktop pagination_color"  aria-hidden="true">Next&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></span>

                                                    <span class="mobile pagination_color" aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row page-titles mb-3  mobile">

                            <button onclick="url_redirect('super_add_hotel.php')" class="btn fixed-button" id="addButton"> <i class="mdi mdi-plus"></i></button>
                            <!--
<div class="col-md-12 text-right">
<span data-toggle="modal" data-target="#filterModal" id="" class="pl-2 pr-2 text_black_color pointer" >  <i class="fas fa-filter"></i>&nbsp;Filter</span>
</div>
-->

                            <div class="col-md-12 mt-2 pt-2">
                                <div class="row" >
                                    <div class="col-md-12">

                                        <form method="get">
                                            <div class="input-group ">
                                                <input type="text" name="search" id="searchInput" value="<?php echo $search; ?>" placeholder="Search By" class="form-control input_background ">
                                                <div class="input-group-append pointer">
                                                    <button class="input-group-text" type="submit" ><i id="searchButton" class="ti-search"></i></button>
                                                </div>

                                            </div>

                                        </form>

                                    </div>
                                </div>

                                <div class="row" >



                                    <?php

                                    $result_mobile = $conn->query($sql_mobile);
                                    while ($row = mysqli_fetch_array($result_mobile)) {




                                        $name = $row['name'];
                                        $surname = $row['surname'];
                                        $person_phone	 = $row['person_phone'];
                                        $email = $row['email'];
                                        $status = $row['status'];
                                        $hotel_name = $row['hotel_name'];
                                        $hotel_website = $row['hotel_website'];
                                        $user_id = $row['user_id'];
                                        

                                        $image_url  =  $row['image'];
                                        $date  =  $row['entry_by_time'];
                                        
                                        $edit_is = 'super_edit_hotel.php?id='.$user_id

                                    ?>
                                    <div class="col-md-12 single_div">

                                        <div class="row">
                                            <div class="col-2 ">
                                                <!-- First Column - Image -->
                                                <img src="<?php echo $image_url; ?>" onerror="this.src='./assets/images/users/user.png'"  alt="user" width="40" height="40" class="rounded-circle"  />
                                            </div>
                                            <div class="col-6 ">
                                                <!-- Second Column - Name -->
                                                <a href="<?php echo $edit_is; ?>" >
                                                    <span class="single_div__dark_text" ><?php echo $name; ?></span>
                                                </a>
                                                <br>
                                                <small><?php echo $email; ?></small>
                                            </div>
                                            <div class="col-4 text-right">
                                              

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 ">
                                                <span class="single_div__dark_text" >Hotel Detail:</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 ">
                                                <span><?php echo $hotel_name; ?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 ">
                                                <span><?php echo $hotel_website; ?></span>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-6 ">

                                            </div>
                                            <div class="col-6 text-right">
                                                <span  class="" ><?php echo $date;  ?></span>
                                            </div>
                                        </div>

                                    </div>

                                    <?php } ?>


                                </div>


                                <div class="row heading_style pt-2">
                                    <div class="col-md-12">
                                        <ul class="pagination justify-content-between">
                                            <!-- Previous Button -->
                                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link page-linknew" href="?page=<?php echo ($page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                                    <span class="desktop pagination_color" aria-hidden="true"> <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Previous</span>

                                                    <span class="mobile pagination_color" aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>

                                            <li class="mmm desktop"></li>

                                            <!-- First Page -->
                                            <?php if ($page > 2) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=1&search=<?php echo $search; ?>">1</a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Dots for middle pages -->
                                            <?php if ($page > 3) : ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Display the previous page if it exists -->
                                            <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo ($page - 1); ?>&search=<?php echo $search; ?>"><?php echo ($page - 1); ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Display the current page -->
                                            <li class="page-item active">
                                                <span class="page-link"><?php echo $page; ?></span>
                                            </li>

                                            <!-- Display the next page if it exists -->
                                            <?php if ($page < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo ($page + 1); ?>&search=<?php echo $search; ?>"><?php echo ($page + 1); ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Dots for middle pages -->
                                            <?php if ($page < $totalPages - 2) : ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            <?php endif; ?>

                                            <!-- Last Page -->
                                            <?php if ($page < $totalPages - 1) : ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo $search; ?>"><?php echo $totalPages; ?></a>
                                            </li>
                                            <?php endif; ?>

                                            <li class="mmm desktop"></li>
                                            <!-- Next Button -->
                                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                                <a class="page-link page-linknew" href="?page=<?php echo ($page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                                    <span class="desktop pagination_color"  aria-hidden="true">Next&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></span>

                                                    <span class="mobile pagination_color" aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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

            function action(id,what,name) {

                var fd = new FormData();


                fd.append('id',id);
                fd.append('what',what);
                fd.append('base','benifit_holder');
                fd.append('name','bh_id');
                $.ajax({
                    url:'utill/active_or_dective.php',
                    type: 'post',
                    data:fd,
                    processData: false,
                    contentType: false,
                    success:function(response){

                        console.log(response);

                        if (response.trim() === 'done') {
                            Swal.fire({
                                title: what+' Successful',
                                text: name+' is '+what,
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = "city_benefit_holder.php";
                                }
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



            // JavaScript code for "Select All" checkbox functionality
            $(document).ready(function () {
                $("#selectAll").change(function () {
                    $(".userCheckbox").prop("checked", $(this).prop("checked"));
                });

                // AJAX request to delete selected rows
                $("#deleteButton").click(function () {
                    var selectedRows = $(".userCheckbox:checked").map(function () {
                        return $(this).val();
                    }).get();

                    if (selectedRows.length === 0) {
                        alert("Please select at least one row to delete.");
                        return;
                    }

                    $.ajax({
                        type: "POST",
                        url: "utill/del.php",
                        data: { selectedRows: selectedRows,base: 'user',base_name: 'user_id' },
                        success: function (response) {
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
                        error: function () {
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
                window.location.href = 'city_benefit_holder.php';
            }

        </script>



        <script>
            // JavaScript code for handling the search input
            $(document).ready(function () {
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
                searchInput.on("input", function () {
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
            $(document).ready(function () {
                // Get the filter form elements
                var filterForm = $("#filterForm");
                var applyFilterButton = $("#applyFilter");
                var resetFilterButton = $("#resetFilter");
                var clearCityFilterButton = $("#clearCityFilter");

                // Handle the "Apply Filter" button click
                applyFilterButton.click(function () {
                    // Get the selected filter status
                    var filterStatus = filterForm.find('input[name="filterStatus"]:checked').val();

                    // Get the selected city filter
                    var cityFilter = filterForm.find('#cityFilter').val();

                    // Update the URL with the filter status and city filter
                    var url = window.location.href.split('?')[0]; // Get the current URL without query parameters
                    if (filterStatus && filterStatus !== 'all') {
                        url += "?status=" + filterStatus;
                    }
                    if (cityFilter && cityFilter !== 'all') {
                        url += (url.includes("?") ? "&" : "?") + "city=" + cityFilter;
                    }
                    window.location.href = url; // Redirect to the filtered URL
                });

                // Handle the "Reset Filter" button click
                resetFilterButton.click(function () {
                    // Reset the filter by removing the "status" and "city" parameters from the URL
                    var url = window.location.href.split('?')[0]; // Get the current URL without query parameters
                    window.location.href = url; // Redirect to the URL without the filter status and city filter
                });

                // Handle the "Clear City Filter" button click
                clearCityFilterButton.click(function () {
                    // Clear the city filter by removing the "city" parameter from the URL
                    var url = window.location.href.split('?')[0]; // Get the current URL without query parameters
                    var searchParams = new URLSearchParams(window.location.search);
                    searchParams.delete('city');
                    var newParams = searchParams.toString();
                    if (newParams) {
                        url += "?" + newParams;
                    }
                    window.location.href = url; // Redirect to the URL without the city filter
                });
            });
        </script>



    </body>

</html>