<?php
session_start();
include '../util_config.php';

// Validate request
if (!isset($_GET['token']) || !isset($_SESSION['paypal_order_id'])) {
    die("Invalid request.");
}

$orderID = $_SESSION['paypal_order_id'];
$this_voucher_id = $_SESSION['this_voucher_id'];
$lang = $_SESSION['lang'];

// Destroy session after retrieving order ID
unset($_SESSION['paypal_order_id']);
unset($_SESSION['this_voucher_id']);
unset($_SESSION['lang']);
session_destroy();




$user_id = 0;

$query = "SELECT  `user_id` FROM `tbl_users_vouchers` WHERE `id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $this_voucher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
} else {
    echo "No record found.";
}
$stmt->close();



$hotel_name = '';
$hotel_email = '';
$query = "SELECT `hotel_name`,`email`,`is_paypal_live`, `sandbox_client_id`, `sandbox_client_secret`, `live_client_id`, `live_client_secret` 
          FROM `tbl_user` 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $is_paypal_live = $row['is_paypal_live'];
    $sandbox_client_id = $row['sandbox_client_id'];
    $sandbox_client_secret = $row['sandbox_client_secret'];
    $live_client_id = $row['live_client_id'];
    $live_client_secret = $row['live_client_secret'];

    $hotel_name = $row['hotel_name'];
    $hotel_email = $row['email'];

    // Set PayPal credentials based on is_paypal_live
    $clientID = $is_paypal_live
        ? $live_client_id
        : $sandbox_client_id;

    $clientSecret = $is_paypal_live
        ? $live_client_secret
        : $sandbox_client_secret;

    $apiUrl = $is_paypal_live
        ? "https://api-m.paypal.com"
        : "https://api-m.sandbox.paypal.com";
} else {

    echo "No record found.";
    exit;
}

$stmt->close();

if ($clientID != '') {
} else {
    exit;
}




// Get a new PayPal access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/oauth2/token");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US"
]);
curl_setopt($ch, CURLOPT_USERPWD, "$clientID:$clientSecret");
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['access_token'])) {
    die("Error getting access token: " . json_encode($response));
}

$accessToken = $response['access_token'];

// Capture Payment
$ch = curl_init("$apiUrl/v2/checkout/orders/$orderID/capture");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// Debug Response
if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
    die("Payment failed: " . json_encode($response));
}

// Get Payer Details
$payerID = $response['payer']['payer_id'] ?? 'N/A';
$payerEmail = $response['payer']['email_address'] ?? 'N/A';
$payerName = $response['payer']['name']['given_name'] . " " . $response['payer']['name']['surname'];
$amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 'N/A';
$currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? 'N/A';
$transactionID = $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? 'N/A';

// Store in Database 


// Show Success Page


$sql = "SELECT a.first_name,a.last_name,a.email,a.phone,a.country,a.address,a.qr_code,a.quantity,a.total,a.voucher_id,a.valid_until,a.discount,
a.`user_id`, a.`title`, a.`description`,a.`our_description`, a.`amount`, a.image as v_image,
               b.name, b.hotel_name, b.hotel_website, b.language, b.image as hotel_image,c.code as p_code
        FROM `tbl_users_vouchers` AS a 
        INNER JOIN `tbl_user` AS b ON a.`user_id` = b.`user_id` 
          INNER JOIN `tbl_promocodes` AS c ON a.`promo_code_id` = c.`promo_code_id` 
        WHERE a.`id` = ?";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $this_voucher_id);
$stmt->execute();
$result = $stmt->get_result();

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
    $p_voucher_id = $row['voucher_id'];

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
}


$payerID = $response['payer']['payer_id'] ?? 'N/A';
$payerEmail = $response['payer']['email_address'] ?? 'N/A';
$payerName = $response['payer']['name']['given_name'] . " " . $response['payer']['name']['surname'];
$amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 'N/A';
$currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? 'N/A';
$transactionID = $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? 'N/A';

$entry_time = date("Y-m-d H:i:s");



// Close connection
$stmt->close();



$tq = "INSERT INTO `tbl_transaction` 
    (`transaction_id`, `payer_name`, `payer_email`, `payer_id`, `amount`, `voucher_id`, `is_used`, `user_id`, `purchase_date`, `used_date`) 
    VALUES (?, ?, ?, ?, ?, ?, '0', ?, ?, '')";
$stmt = $conn->prepare($tq);
$stmt->bind_param("ssssssss", $transactionID, $payerName, $payerEmail, $payerID, $amount, $this_voucher_id, $user_id, $entry_time);
if ($stmt->execute()) {
} else {
}
$stmt->close();


$t_id = $conn->insert_id;
// Prepare the SQL statement
$sql = "INSERT INTO `tbl_email_jobs` (`name`, `email`, `v_id`, `is_run`, `t_id`, `create_at`,`lang`) 
        VALUES (?, ?, ?, ?, ?, ?,?)";

$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$name = $first_name . ' ' . $last_name;
$v_id = $this_voucher_id;
$is_run = 0;
$create_at = date('Y-m-d H:i:s');

$stmt->bind_param("ssiiiss", $name, $email, $v_id, $is_run, $t_id, $create_at, $lang);
if ($stmt->execute()) {
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();


$query = "SELECT a.active_to,a.create_at,b.is_fixed 
    FROM tbl_voucher AS a 
    INNER JOIN tbl_category AS b ON a.cat_id = b.id 
    WHERE a.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $p_voucher_id);
$stmt->execute();
$result = $stmt->get_result();



if ($row = $result->fetch_assoc()) {
    $is_fixed = $row['is_fixed'];
    $active_to = $row['active_to'];
    $create_at = $row['create_at'];
}
$stmt->close();
$valid = $valid_until;



$translations = [
    'en' => [
        'payment_successful' => 'Payment Successful',
        'transaction_detail' => 'Transaction Detail',
        'payer_name' => 'Payer Name',
        'email' => 'Email',
        'amount' => 'Amount',
        'payer_id' => 'Payer ID',
        'more_detail' => 'More Detail',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'per_voucher' => 'Voucher Amount',
        'discount' => 'Discount',
        'promocode' => 'Promo Code',

        'quantity' => 'Quantity',
        'address' => 'Address',
        'valid_until' => 'Valid until',
        'download_voucher' => 'Download Voucher',
        'save_as_pdf' => 'Save as PDF',
        'company_address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'company_reservation' => 'FOR RESERVATIONS TEL.: 0471 345102 INFO@WEIHRERHOF.COM',
        'transaction_id' => 'Transaction ID',
        'total_amount' => 'Total Amount',
        'phone' => 'Phone'
    ],
    'de' => [
        'payment_successful' => 'Zahlung erfolgreich',
        'transaction_detail' => 'Transaktionsdetails',
        'payer_name' => 'Zahlername',
        'email' => 'E-Mail',
        'amount' => 'Betrag',
        'payer_id' => 'Zahler-ID',
        'more_detail' => 'Weitere Details',
        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'per_voucher' => 'Betrag',
        'quantity' => 'Menge',
        'address' => 'Adresse',
        'valid_until' => 'Gültig bis',
        'download_voucher' => 'Gutschein herunterladen',
        'save_as_pdf' => 'Als PDF speichern',
        'company_address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'company_reservation' => 'RESERVIERUNG UNTER TEL.: 0471 345102 INFO@WEIHRERHOF.COM',
        'transaction_id' => 'Transaktions-ID',
        'total_amount' => 'Gesamtbetrag',
        'phone' => 'Telefon',
        'discount' => 'Discount',
        'promocode' => 'Promo Code',
    ],
    'it' => [
        'payment_successful' => 'Pagamento riuscito',
        'transaction_detail' => 'Dettagli della transazione',
        'payer_name' => 'Nome del pagatore',
        'email' => 'Email',
        'amount' => 'Importo',
        'payer_id' => 'ID del pagatore',
        'more_detail' => 'Ulteriori dettagli',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'per_voucher' => 'Per voucher',
        'quantity' => 'Quantità',
        'address' => 'Indirizzo',
        'valid_until' => 'Valido fino a',
        'download_voucher' => 'Scarica voucher',
        'save_as_pdf' => 'Salva come PDF',
        'company_address' => 'WOLFSGRUBEN/COSTALOVARA 22 39054 OBERBOZEN/SOPRABOLZANO',
        'company_reservation' => 'PER PRENOTAZIONI TEL 0471 345102 INFO@WEIHRERHOF.COM',

        'transaction_id' => 'ID transazione',
        'total_amount' => 'Importo totale',
        'phone' => 'Telefono',
        'discount' => 'Discount',
        'promocode' => 'Promo Code',
    ]
];

// Define variables with _text suffix
$payment_successful_text = $translations[$lang]['payment_successful'];
$transaction_detail_text = $translations[$lang]['transaction_detail'];
$payer_name_text = $translations[$lang]['payer_name'];
$email_text = $translations[$lang]['email'];
$amount_text = $translations[$lang]['amount'];
$payer_id_text = $translations[$lang]['payer_id'];
$more_detail_text = $translations[$lang]['more_detail'];
$first_name_text = $translations[$lang]['first_name'];
$last_name_text = $translations[$lang]['last_name'];
$per_voucher_text = $translations[$lang]['per_voucher'];
$quantity_text = $translations[$lang]['quantity'];
$address_text = $translations[$lang]['address'];
$valid_until_text = $translations[$lang]['valid_until'];


$download_voucher_text = $translations[$lang]['download_voucher'];
$save_as_pdf_text = $translations[$lang]['save_as_pdf'];
$company_address_text = $translations[$lang]['company_address'];
$company_reservation_text = $translations[$lang]['company_reservation'];
$transaction_id_text = $translations[$lang]['transaction_id'];
$total_amount_text = $translations[$lang]['total_amount'];
$phone_text = $translations[$lang]['phone'];
$discount_text = $translations[$lang]['discount'];
$promocode_text = $translations[$lang]['promocode'];



$f_name =  $first_name . ' ' . $last_name;
$email_subject = "New voucher order received";
$email_body = "Hi $hotel_name,<br><br>
A customer has purchased a voucher for your hotel: <strong>$title</strong>.<br><br>
Voucher Details:<br>
- <strong>Voucher Title</strong>: $title<br>
- <strong>QR Code</strong>: $qr_code<br>
- <strong>Total Amount</strong>: $total<br>
- <strong>Customer Name</strong>: $f_name.''<br><br>
- <strong>Customer Email</strong>: $email<br><br>
- <strong>Validity</strong>: $valid_until<br><br>

Please verify the voucher using the QR code provided when the customer redeems it. If you have any questions or need further details, feel free to contact our support team.<br><br>
Best regards,<br>
The QualityFriend Team";

$email_body = mysqli_real_escape_string($conn, $email_body);
$create_at = date("Y-m-d H:i:s");
$sql_email = "INSERT INTO `tbl_other_email_jobs` (`email`, `email_text`, `subject`, `create_at`, `lang`, `is_run`) 
              VALUES ('$hotel_email', '$email_body', '$email_subject', '$create_at', '$lang', '0')";
$conn->query($sql_email);

?>

<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $payment_successful_text; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>

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

<body class="bg-light">

    <div class="container mt-5 mb-5"> <!-- Bottom margin added -->
        <div class="card shadow-lg">
            <div class="card-header text-center bg-success text-white">
                <h2><?php echo $payment_successful_text; ?></h2>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img src="<?= $hotel_image ?>" class="img-fluid rounded" style="max-width: 200px;">
                </div>
                <h4 class="mt-3 text-center"><?= $hotel_name ?></h4>
                <p class="text-center"><a href="<?= $hotel_website ?>" target="_blank"><?= $hotel_website ?></a></p>
                <h5><?php echo $transaction_detail_text; ?></h5>
                <table class="table table-bordered mt-3">
                    <tr>
                        <th><?php echo $transaction_id_text; ?></th>
                        <td><?= $transactionID ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $payer_name_text; ?></th>
                        <td><?= $payerName ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $email_text; ?></th>
                        <td><?= $payerEmail ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $amount_text; ?></th>
                        <td><?= $amount ?> <?= $currency ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $payer_id_text; ?></th>
                        <td><?= $payerID ?></td>
                    </tr>
                </table>
                <h5><?php echo $more_detail_text; ?></h5>
                <table class="table table-bordered mt-3">
                    <tr>
                        <th><?php echo $first_name_text; ?> </th>
                        <td><?= $first_name ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $last_name_text; ?></th>
                        <td><?= $last_name ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $email_text; ?></th>
                        <td><?= $email ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $per_voucher_text; ?></th>
                        <td><?= $amount_v ?> <?= $currency ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $discount_text; ?></th>
                        <td><?= $discount ?><?= $currency ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $total_amount_text; ?></th>
                        <td><?= $total ?><?= $currency ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $promocode_text; ?></th>
                        <td><?= $p_code ?></td>
                    </tr>


                    <tr>
                        <th><?php echo $phone_text; ?></th>
                        <td><?= $phone ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $address_text; ?></th>
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
                                    <?php echo $valid_until_text; ?>: <b><?php echo $valid; ?></b>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2 mb-2">
                        <p style="font-size: 14px; padding: 0; margin: 0;"><?php echo $company_address_text; ?>
                        </p>
                        <p style="font-size: 14px; padding: 0; margin: 0;"><?php echo $company_reservation_text; ?>
                        </p>
                    </div>


                </div>


                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-success" onclick="downloadVoucher()" title="Download Voucher">
                        <?php echo $download_voucher_text; ?></button>
                    <!-- <button class="btn btn-primary" onclick="printPage()">🖨️ < ?php echo $save_as_pdf_text; ?></button> -->

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function downloadVoucher() {
            var lang = "<?php echo $lang ?? 'en'; ?>";

            var qr = "<?php echo $qr_code; ?>"; // Ensure PHP variable is correctly echoed
            const url = `https://vouchers.qualityfriend.solutions/api/download_voucher.php?qr_code=${encodeURIComponent(qr)}&lang=${encodeURIComponent(lang)}`;
            window.open(url, "_blank");
        }
    </script>

    <script>
        var qrCodeText = "<?php echo $qr_code; ?>";
        new QRCode(document.getElementById("qrcode"), {
            text: qrCodeText,
            width: 70, // Adjust width
            height: 70, // Adjust height
        });
    </script>
</body>

</html>