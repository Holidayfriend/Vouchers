<?php

include '../util_config.php'; // Database connection

require_once '../util_session.php';
if (isset($_GET['voucher_id'])) {
    $voucher_id = $_GET['voucher_id'];
} else {
    exit;
}
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

$amount = 0;
$user_id = 0;

$query = "SELECT  `total`,`user_id`,`first_name`,`email` FROM `tbl_users_vouchers` WHERE `id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $voucher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Store data in variables
    // $title = $row['title'];
    // $description = $row['description'];
    $name = $row['first_name'];
    $email = $row['email'];
    $amount = $row['total'];
    $user_id = $row['user_id'];
    $orgnail_one = $row['voucher_id'];
} else {
    echo "No record found.";
}
$stmt->close();



$query = "SELECT  `type` FROM `tbl_voucher` WHERE `id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orgnail_one);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
   
    $type = $row['type'];
    
} else {
    echo "No record found.";
}
$stmt->close();



if ($type == 'INTERNAL') {
    $t_id = 0;
    $is_run = 0;
    $entry_time = date("Y-m-d H:i:s");
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

    $stmt->bind_param("ssiiiss", $name, $email, $voucher_id, $is_run, $t_id, $entry_time, $lang);
    if ($stmt->execute()) {
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: ../hotel_purchased_voucher_detail.php?id=" . $voucher_id);
    exit();
} else {
}






$query = "SELECT `is_paypal_live`, `sandbox_client_id`, `sandbox_client_secret`, `live_client_id`, `live_client_secret` 
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

// Get PayPal Access Token
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
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!isset($response['access_token'])) {
    die("Error getting access token: " . json_encode($response));
}

$accessToken = $response['access_token'];

// $amount = 10;
// Define URLs based on environment
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');
$returnUrl = $isLocal ? "http://localhost/vouchers/api/success.php" : "https://vouchers.qualityfriend.solutions/api/success.php";
$cancelUrl = $isLocal ? "http://localhost/vouchers/api/cancel.php" : "https://vouchers.qualityfriend.solutions/api/cancel.php";
// Create Order
$orderData = [
    "intent" => "CAPTURE",
    "purchase_units" => [
        [
            "amount" => [
                "currency_code" => "EUR",
                "value" => $amount
            ]
        ]
    ],
    "application_context" => [
        "return_url" => $returnUrl,
        "cancel_url" => $cancelUrl,
        "brand_name" => "Your Business Name",
        "payment_method" => [
            "payee_preferred" => "IMMEDIATE_PAYMENT_REQUIRED"
        ],
        "landing_page" => "BILLING"

    ]
];

$ch = curl_init("$apiUrl/v2/checkout/orders");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch), true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Debug Response
if (!isset($response['id'])) {
    die("Error creating PayPal order: " . json_encode($response));
}

$orderID = $response['id'];

// Save order_id in session or database
session_start();
$_SESSION['paypal_order_id'] = $orderID;
$_SESSION['this_voucher_id'] = $voucher_id;
$_SESSION['lang'] = $lang;



// Find the approval URL
$approvalUrl = null;
foreach ($response['links'] as $link) {
    if ($link['rel'] === 'approve') {
        $approvalUrl = $link['href'];
        break;
    }
}

// Debug Response
if (!$approvalUrl) {
    die("Error: No approval URL found in response: " . json_encode($response));
}

// Redirect user to PayPal approval URL
header("Location: $approvalUrl");
exit;
