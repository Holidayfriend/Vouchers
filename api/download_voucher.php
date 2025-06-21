<?php
include '../util_config.php';

$qr_code = isset($_GET['qr_code']) ? $_GET['qr_code'] : '';
$lang = isset($_GET['lang']) ? $_GET['lang'] : '';

// Default language is English
$translations = [
    'en' => [
        'valid_until' => 'Valid until',
        'address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'reservation' => 'FOR RESERVATIONS TEL.: 0471 345102 INFO@WEIHRERHOF.COM'
    ],
    'de' => [
        'valid_until' => 'Gültig bis date',
        'address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'reservation' => 'RESERVIERUNG UNTER TEL.: 0471 345102 INFO@WEIHRERHOF.COM'
    ],
    'it' => [
        'valid_until' => 'VALIDO FINO',
        'address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'reservation' => 'PER PRENOTAZIONI TEL 0471 345102 INFO@WEIHRERHOF.COM'
    ]
];

// Ensure $lang is valid, default to 'en'
$lang = in_array($lang, ['en', 'de', 'it']) ? $lang : 'en';

$valid_until_text = $translations[$lang]['valid_until'];
$address_text = $translations[$lang]['address'];
$reservation_text = $translations[$lang]['reservation'];

if (empty($qr_code)) {
    die("Invalid QR code.");
}

$sql = "SELECT 
            title, amount, description, our_description, image, quantity, 
            total, qr_code, user_id,voucher_id,valid_until
        FROM tbl_users_vouchers 
        WHERE qr_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $qr_code);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $title = $row['title'];
    $amount_v = $row['amount'];
    $description = $row['description'];
    $our_description = $row['our_description'];
    $image = $row['image'];
    $quantity = $row['quantity'];
    $total = $row['total'];
    $qr_code = $row['qr_code'];
    $user_id = $row['user_id'];
    $voucher_id = $row['voucher_id'];
    $valid_until = $row['valid_until'];
} else {
    die("Voucher not found.");
}
$stmt->close();

// Fetch hotel details
$query = "SELECT `hotel_name`, `hotel_website`, `image` FROM `tbl_user` WHERE `user_id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$hotel_name = '';
$hotel_website = '';
$hotel_image = '';

if ($row = $result->fetch_assoc()) {
    $hotel_name = $row['hotel_name'];
    $hotel_website = $row['hotel_website'];
    $hotel_image = $row['image'];
}
$stmt->close();

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
$conn->close();

$valid = '';

if ($active_to == '' || is_null($active_to)) {
    $valid = date('d/m/Y', strtotime($active_to));

} else {
    $valid = date('d/m/Y', strtotime($create_at . ' +1 year'));
}

// Base URL for images
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base = 'http://localhost/vouchers/';
} else {
    $base = 'https://vouchers.qualityfriend.solutions/';
}

$image = $base . $image;
$hotel_image = $base . $hotel_image;
$currency = 'EUR'; // Hardcoded as per your transaction block
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voucher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #voucherDiv {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 10mm;
            box-sizing: border-box;
            border: none;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .bg-gray-color {
            /*background-color: rgb(244, 240, 240);*/
            flex-grow: 1;
            padding: 0;
            /* Remove padding to avoid extra margins */
        }

        .t-gray-color {
            color: #70706E;
        }

        .t-black-color {
            color: black;
        }

        .padding-left-right {
            margin-top: 40px;

        }

        .padding-left-right1 {
            margin-top: 20px;

        }

        .voucher-image-container {
            width: 210mm;
            margin-left: -10mm;
            /* Offset parent padding */
            margin-right: -10mm;
            /* Offset parent padding */
            padding: 0;
            box-sizing: border-box;
        }

        .voucher-image {
            width: 210mm;
            max-width: 210mm;
            margin: 0;
            /* Remove all margins */
            height: auto;
            display: block;
            object-fit: cover;
            /* Ensure image fills width */
        }

        .sized {
            font-size: 300px;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                width: 210mm;
                height: 297mm;
            }

            #voucherDiv {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 10mm;
                box-shadow: none;
            }

            .voucher-image-container {
                width: 210mm;
                margin-left: -10mm;
                margin-right: -10mm;
                padding: 0;
            }

            .voucher-image {
                width: 210mm;
                max-width: 210mm;
                margin: 0;
                height: auto;
                object-fit: cover;
            }

        }
    </style>
    <script>
        window.onload = function () {
            new QRCode(document.getElementById("qrcode"), {
                text: "<?php echo htmlspecialchars($qr_code); ?>",
                width: 70,
                height: 70
            });
            setTimeout(function () { window.print(); }, 700);
        };
    </script>
</head>

<body>
    <div id="voucherDiv">
        <div class="text-center pt-3 pb-2">
            <img src="<?php echo $hotel_image; ?>" class="img-fluid" style="max-width: 200px;">
        </div>
        <div class="bg-gray-color mt-3">
            <div class="text-center voucher-image-container">
                <img src="<?php echo $image; ?>" class="voucher-image ">
            </div>
            <div class="padding-left-right ">
                <h1 class="text-center t-gray-color "><?php echo $title; ?></h1>
                <?php if ($is_fixed == 0) { ?>
                    <h3 class="text-center t-black-color"><?php echo '€' . $total; ?></h3>
                <?php } ?>
                <p style="font-size: 19px;" class="text-muted text-left padding-left-right1">
                    <?php echo $our_description; ?></p>
                <p style="font-size: 19px;" class="text-muted text-left"><?php echo $description; ?></p>

            </div>
        </div>
        <div class="text-center mt-2 mb-2">
            <div style="text-align: right; width: 100%;">
                <div id="qrcode" style="display: inline-block;"></div>
                <br>
                <p style="font-size: 11px;">
                    <b><?php echo $qr_code; ?></b>
                    <br>
                    <?php echo $valid_until_text; ?>: <b><?php echo $valid_until ; ?></b>
                </p>
            
            </div>
            <p style="font-size: 14px; padding: 0; margin: 0;"> <?php echo $address_text; ?></p>
            <p style="font-size: 14px; padding: 0; margin: 0;"> <?php echo $reservation_text; ?></p>
        </div>
    </div>
</body>

</html>