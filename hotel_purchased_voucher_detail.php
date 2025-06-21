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
$this_voucher_id = 0;




if (isset($_GET['id'])) {
    $this_voucher_id = $_GET['id'];
}


$sql = "SELECT 
            a.first_name, a.last_name, a.email, a.phone, a.country, a.address, a.redaem_time, a.voucher_id, a.valid_until, a.discount,
            a.qr_code, a.quantity, a.total, a.user_id, a.title, a.description, a.our_description, 
            a.amount, a.image AS v_image, 
            b.name, b.hotel_name, b.hotel_website, b.language, b.image AS hotel_image,
            c.code as p_code
        FROM `tbl_users_vouchers` AS a 
        INNER JOIN `tbl_user` AS b ON a.user_id = b.user_id 
        LEFT JOIN `tbl_promocodes` AS c ON a.`promo_code_id` = c.`promo_code_id` 
        WHERE a.id = $this_voucher_id AND a.user_id = $my_user_id_is";
// Execute query
$result = $conn->query($sql);

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base = 'http://localhost/vouchers/';
} else {
    $base = 'https://vouchers.qualityfriend.solutions/';
}

// Fetch data
if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $title = $row['title'];
    $description = $row['description'];
    $our_description = $row['our_description'];
    $amount_v = $row['amount'];
    $name = $row['name'];
    $hotel_name = $row['hotel_name'];
    $hotel_website = $row['hotel_website'];
    $language = $row['language'];
    $hotel_image = $base . $row['hotel_image'];
    $image = $base . $row['v_image'];
    $redaem_time = $row['redaem_time'];
    $voucher_id = $row['voucher_id'];



    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $country = $row['country'];
    $address = $row['address'];
    $qr_code = $row['qr_code'];
    $quantity = $row['quantity'];
    $total = $row['total'];
    $valid_until = $row['valid_until'];
    $discount = $row['discount'];

    $p_code = $row['p_code'];
    if ($p_code == '') {
        $p_code = 'N/A';
    }
} else {
    echo "No voucher found.";
}



$current_date = new DateTime(); // Current date: June 9, 2025
$valid_date = DateTime::createFromFormat('d/m/Y', $valid_until); // Parse DD/MM/YYYY format

if ($valid_date <= $current_date) {
    $check = 'no';
} else {
    $check = 'yes';
}
$sql = "SELECT * FROM `tbl_transaction` where `voucher_id` = '$this_voucher_id' AND `user_id` = '$my_user_id_is'";
// Execute query
$result = $conn->query($sql);
$t_f = 1;
// Fetch data
if ($row = $result->fetch_assoc()) {
    $transaction_id = $row['transaction_id'];
    $payer_name = $row['payer_name'];
    $payer_email = $row['payer_email'];
    $payer_id = $row['payer_id'];
    $t_amount = $row['amount'];
    $is_used = $row['is_used'];
    $purchase_date = $row['purchase_date'];
    $used_date = $row['used_date'];
} else {

    $t_f = 0;
}


$query = "SELECT a.active_to,a.create_at,b.is_fixed 
    FROM tbl_voucher AS a 
    INNER JOIN tbl_category AS b ON a.cat_id = b.id 
    WHERE a.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $voucher_id);
$stmt->execute();
$result = $stmt->get_result();



if ($row = $result->fetch_assoc()) {
    $is_fixed = $row['is_fixed'];
    $active_to = $row['active_to'];
    $create_at = $row['create_at'];
}
$stmt->close();


$valid = '';

if ($active_to == '' || is_null($active_to)) {
    $valid = date('d/m/Y', strtotime($active_to));
} else {
    $valid = date('d/m/Y', strtotime($create_at . ' +1 year'));
}


$page_text = getTranslation('voucher_detail', $lang);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <title>Voucher Detail</title>
    <!-- This page CSS -->
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">


    <script>
        function printPage() {
            window.print();
        }
    </script>

    <style>
        @media print {
            #xyz {
                display: none !important;
            }

            #redeem {
                display: none !important;
            }
        }
    </style>

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
    <style>
        #voucherDiv {
            max-width: 600px;
            margin: auto;


            border: none;

            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .bg-gray-color {
            background-color: rgb(244, 240, 240);
        }

        .t-gray-color {
            color: #70706E
        }

        .t-black-color {
            color: black;
        }

        .padding-left-right {
            padding-left: 15px;
            /* Adjust this value as needed */
            padding-right: 15px;
            /* Adjust this value as needed */
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

                    <div id="xyz" class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-cancel " onclick="downloadVoucher()" title="Download Voucher">
                                    <?= getTranslation('download_voucher', $lang) ?> </button>
                                <!-- <button class="btn btn-create " onclick="printPage()">🖨️
                                    < ?= getTranslation('save_as_pdf', $lang) ?></button> -->

                            </div>


                        </div>

                    </div>
                </div>


                <div class="row page-titles mb-3 add_background">
                    <div class="col-lg-12 col-xlg-12 col-md-12">

                        <?php
                        if ($t_f == 1) {
                        ?>


                            <div class="text-center">
                                <img src="<?= $hotel_image ?>" class="img-fluid rounded" style="max-width: 200px;">
                            </div>
                            <h4 class="mt-3 text-center"><?= $hotel_name ?></h4>
                            <p class="text-center"><a href="<?= $hotel_website ?>" target="_blank"><?= $hotel_website ?></a>
                            </p>
                            <h4><?= getTranslation('transaction_detail', $lang) ?></h4>
                            <table class="table table-bordered mt-3">

                                <tr>
                                    <th><?= getTranslation('transaction_id', $lang) ?></th>
                                    <td><?= $transaction_id ?></td>
                                </tr>
                                <tr>
                                    <th><?= getTranslation('payer_name', $lang) ?></th>
                                    <td><?= $payer_name ?></td>
                                </tr>
                                <tr>
                                    <th><?= getTranslation('payer_email', $lang) ?></th>
                                    <td><?= $payer_email ?></td>
                                </tr>
                                <tr>
                                    <th><?= getTranslation('amount', $lang) ?></th>
                                    <td><?= $t_amount ?> <?= 'EUR' ?></td>
                                </tr>
                                <tr>
                                    <th><?= getTranslation('payer_id', $lang) ?></th>
                                    <td><?= $payer_id ?></td>
                                </tr>
                            </table>
                        <?php } ?>
                        <h4><?= getTranslation('more_detail', $lang) ?></h4>
                        <table class="table table-bordered mt-3">
                            <tr>
                                <th><?= getTranslation('first_name', $lang) ?> </th>
                                <td><?= $first_name ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('last_name', $lang) ?></th>
                                <td><?= $last_name ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('email', $lang) ?></th>
                                <td><?= $email ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('per_voucher', $lang) ?></th>
                                <td><?= $amount_v ?> <?= 'EUR' ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('discount', $lang) ?></th>
                                <td><?= $discount ?><?= 'EUR' ?></td>
                            </tr>

                            <tr>
                                <th><?= getTranslation('total_amount', $lang) ?></th>
                                <td><?= $total ?><?= 'EUR' ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('promo_code', $lang) ?></th>
                                <td><?= $p_code ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('phone', $lang) ?></th>
                                <td><?= $phone ?></td>
                            </tr>
                            <tr>
                                <th><?= getTranslation('address', $lang) ?></th>
                                <td><?= $address ?></td>
                            </tr>
                        </table>

                        <div id="voucherDiv" class="card shadow-lg border-0  rounded">
                            <div class="text-center pt-3 pb-2">
                                <img src="<?php echo $hotel_image; ?>" class="img-fluid " style="max-width: 200px;">
                            </div>
                            <div class="bg-gray-color">
                                <div class="text-center">
                                    <img src="<?php echo $image; ?>" class="img-fluid  shadow-sm mb-3"
                                        style="max-width: 100%; ">
                                </div>
                                <div class="padding-left-right">
                                    <h3 class="text-center t-gray-color"><?php echo $title; ?></h3>

                                    <?php if ($is_fixed == 0) { ?>
                                        <h3 class="text-center t-black-color"><?php echo '€' . $total; ?></h3>

                                    <?php } ?>

                                    <p class="text-muted text-left"><?php echo $our_description; ?></p>
                                    <p class="text-muted text-left"><?php echo $description; ?></p>
                                    <div style="text-align: right; width: 100%;">
                                        <div id="qrcode" style="display: inline-block;"></div>
                                        <br>
                                        <p style="font-size: 11px;">
                                            <b><?php echo $qr_code; ?></b>
                                            <br>
                                            <?= getTranslation('valid_untill', $lang) ?>:
                                            <b><?php echo $valid_until; ?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-2 mb-2">
                                <p style="font-size: 14px; padding: 0; margin: 0;">
                                    <?= getTranslation('company_address', $lang) ?>
                                </p>
                                <p style="font-size: 14px; padding: 0; margin: 0;">
                                    <?= getTranslation('company_reservation', $lang) ?>
                                </p>
                            </div>

                            <?php
                            if ($check == 'yes') {
                                if ($redaem_time == '') {
                            ?>
                                    <button id='redeem' class="btn btn-create btn-full-width" onclick="redeem()" title="Redeem">
                                        <?= getTranslation('redeem_it', $lang) ?>
                                    </button>
                                <?php } else { ?>
                                    <h6 class="mt-2 text-center"><?= ' Redeemed at : ' . $redaem_time; ?></h6>
                                <?php }
                            } else {
                                ?>
                                <h6 class="mt-2 text-center"><strong>Expired</strong></h6>
                            <?php
                            } ?>
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

    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>



    <script>
        var qrCodeText = "<?php echo $qr_code; ?>";
        new QRCode(document.getElementById("qrcode"), {
            text: qrCodeText,
            width: 70, // Adjust width
            height: 70, // Adjust height
        });

        function redeem() {
            var id = <?php echo $this_voucher_id ?>;
            console.log(id);

            Swal.fire({
                title: 'Are you sure ?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Redeem it!'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: 'utill/redeem.php',
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response == "1") {
                                Swal.fire({
                                    title: 'Redeemed',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.href = "hotel_purchased_voucher_detail.php?id=" + id;
                                    }
                                })
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                    footer: ''
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            l
                        },
                    });
                }
            });
        }
    </script>

    <script>
        function downloadVoucher() {
            var qr = "<?php echo $qr_code; ?>"; // Ensure PHP variable is correctly echoed
            var lang = "<?php echo $lang; ?>";
            var url = "https://vouchers.qualityfriend.solutions/api/download_voucher.php?qr_code=" + encodeURIComponent(qr) + "&lang=" + encodeURIComponent(lang);
            window.open(url, "_blank"); // Opens in a new tab
        }
    </script>




    </script>
</body>

</html>