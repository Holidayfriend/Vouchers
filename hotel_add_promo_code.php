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
$page_text = getTranslation('add_promo_code', $lang);
$back = 'yes';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title><?= getTranslation('add_promo_code', $lang) ?></title>
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <link href="./assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">
    <link href="dist/css/pages/file-upload.css" rel="stylesheet">
    <link href="./assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="skin-default-dark fixed-layout mini-sidebar lock-nav">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?= getTranslation('add_promo_code', $lang) ?></p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'util_header.php'; ?>
        <?php include 'hotel_utill_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="mobile-container-padding">
                    <div class="row page-titles mb-3 add_background">
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <span class="add_top_heading"><?= getTranslation('add_promo_code', $lang) ?></span>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <hr>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('promo_code', $lang) ?>:</label>
                                <input type="text" class="form-control input_background" id="code" placeholder="">
                                <div class="text-danger" id="errorcode"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('discount_type', $lang) ?>:</label>
                                <select class="select2 m-b-10 select2-multipleselect2 form-control custom-select" id="discount_type" name="discount_type">
                                    <option value="PERCENTAGE"><?= getTranslation('PERCENTAGE', $lang) ?></option>
                                    <option value="FIXED"><?= getTranslation('FIXED', $lang) ?></option>
                                </select>
                                <div class="text-danger" id="errordiscounttype"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('discount_value', $lang) ?>:</label>
                                <input type="number" class="form-control input_background" id="discount_value" placeholder="" min="0" step="0.01">
                                <div class="text-danger" id="errordiscountvalue"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('max_uses', $lang) ?>:</label>
                                <input type="number" class="form-control input_background" id="max_uses" placeholder="0 for unlimited" min="0">
                                <div class="text-danger" id="errormaxuses"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('start_date', $lang) ?>:</label>
                                <input type="date" class="form-control input_background" id="start_date" placeholder="">
                                <div class="text-danger" id="errorstartdate"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xlg-6 col-md-6">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('end_date', $lang) ?>:</label>
                                <input type="date" class="form-control input_background" id="end_date" placeholder="">
                                <div class="text-danger" id="errorenddate"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('applicable', $lang) ?>:</label>
                                <select class="form-control input_background" id="apply_type" name="apply_type">
                                    <option value="all"><?= getTranslation('all', $lang) ?></option>
                                    <option value="voucher"><?= getTranslation('voucher', $lang) ?></option>
                                    <option value="category"><?= getTranslation('category', $lang) ?></option>
                                </select>
                                <div class="text-danger" id="errorapplytype"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12" id="voucher_container" style="display: none;">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('select_voucher', $lang) ?>:</label>
                                <select class="form-control input_background" id="voucher_id" name="voucher_id">
                                    <option value=""><?= getTranslation('select_voucher', $lang) ?></option>
                                    <?php
                                    $sql_vouchers = "SELECT `id`, `title` FROM `tbl_voucher` WHERE `user_id` = '$my_user_id_is' AND `is_delete` = 0";
                                    $result_vouchers = $conn->query($sql_vouchers);
                                    while ($row = $result_vouchers->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="text-danger" id="errorvoucherid"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xlg-12 col-md-12" id="category_container" style="display: none;">
                            <div class="input-container">
                                <label class="my-lable"><?= getTranslation('select_category', $lang) ?>:</label>
                                <select class="form-control input_background" id="category_id" name="category_id">
                                    <option value=""><?= getTranslation('select_category', $lang) ?></option>
                                    <?php
                                    $sql_categories = "SELECT `id`, `name` FROM `tbl_category` WHERE `user_id` = '$my_user_id_is' AND `is_delete` = 0";
                                    $result_categories = $conn->query($sql_categories);
                                    while ($row = $result_categories->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="text-danger" id="errorcategoryid"></div>
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
            </div>
        </div>
        <?php include 'util_footer.php'; ?>
    </div>
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script src="./assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const createButton = document.getElementById('createButton');
        const cancelButton = document.getElementById('cancelButton');
       

        // Handle apply_type change
        $('#apply_type').on('change', function () {
            const applyType = $(this).val();
            $('#voucher_container').hide();
            $('#category_container').hide();
            $('#voucher_id').val('');
            $('#category_id').val('');
            $('#errorvoucherid').text('');
            $('#errorcategoryid').text('');

            if (applyType === 'voucher') {
                $('#voucher_container').show();
            } else if (applyType === 'category') {
                $('#category_container').show();
            }
        });

        createButton.addEventListener('click', function () {
            // Validate inputs for empty fields
            checkEmptyInput('code', 'errorcode', '<?= getTranslation('required', $lang) ?>');
            checkEmptyInput('discount_type', 'errordiscounttype', '<?= getTranslation('required', $lang) ?>');
            checkEmptyInput('discount_value', 'errordiscountvalue', '<?= getTranslation('required', $lang) ?>');
            checkEmptyInput('start_date', 'errorstartdate', '<?= getTranslation('required', $lang) ?>');
            checkEmptyInput('end_date', 'errorenddate', '<?= getTranslation('required', $lang) ?>');
            checkEmptyInput('apply_type', 'errorapplytype', '<?= getTranslation('required', $lang) ?>');

            // Validate voucher_id or category_id based on apply_type
            const applyType = document.getElementById('apply_type').value;
            if (applyType === 'voucher') {
                checkEmptyInput('voucher_id', 'errorvoucherid', '<?= getTranslation('required', $lang) ?>');
            } else if (applyType === 'category') {
                checkEmptyInput('category_id', 'errorcategoryid', '<?= getTranslation('required', $lang) ?>');
            }

            // Proceed if no validation errors
            if (document.querySelectorAll('.input-invalid').length === 0) {
                const code = document.getElementById('code').value.trim();
                const discount_type = document.getElementById('discount_type').value;
                const discount_value = document.getElementById('discount_value').value.trim();
                const max_uses = document.getElementById('max_uses').value.trim() || '0';
                const start_date = document.getElementById('start_date').value.trim();
                const end_date = document.getElementById('end_date').value.trim();
                const apply_type = document.getElementById('apply_type').value;
                const voucher_id = apply_type === 'voucher' ? document.getElementById('voucher_id').value : '';
                const category_id = apply_type === 'category' ? document.getElementById('category_id').value : '';
                const save_lang = '<?= $lang ?>';

                const fd = new FormData();
                fd.append('code', code);
                fd.append('discount_type', discount_type);
                fd.append('discount_value', discount_value);
                fd.append('max_uses', max_uses);
                fd.append('start_date', start_date);
                fd.append('end_date', end_date);
                fd.append('what', apply_type);
                fd.append('voucher_id', voucher_id);
                fd.append('category_id', category_id);
                fd.append('save_lang', save_lang);

                $.ajax({
                    url: 'utill/add_promocodes.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response == '1') {
                            Swal.fire({
                                title: '<?= getTranslation('added_successfully', $lang) ?>',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: '<?= getTranslation('ok', $lang) ?>'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = "hotel_promo_code.php";
                                }
                            });
                        } else if (response.includes('Duplicate entry')) {
                            Swal.fire({
                                type: 'error',
                                title: '<?= getTranslation('error', $lang) ?>',
                                text: '<?= getTranslation('code_exists', $lang) ?>',
                                footer: ''
                            });
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: '<?= getTranslation('error', $lang) ?>',
                                text: '<?= getTranslation('something_went_wrong', $lang) ?>',
                                footer: ''
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            type: 'error',
                            title: '<?= getTranslation('error', $lang) ?>',
                            text: '<?= getTranslation('ajax_error', $lang) ?>',
                            footer: ''
                        });
                    }
                });
            }
        });

        cancelButton.addEventListener('click', function () {
            window.history.back();
        });

        // Function to check for empty inputs
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
    });
    </script>
</body>
</html>