<?php
include '../util_config.php'; // Database connection

// Get POST data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$country = $_POST['country'] ?? '';
$address = $_POST['address'] ?? '';
$tax_number = $_POST['tax_number'] ?? '';
$promoCode = $_POST['promoCode'] ?? '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the received JSON cart data
    $cart = json_decode($_POST['cart'], true) ?? [];

    // Initialize variables
    $voucher_id = $cart[0]['voucher_id'] ?? null;
    $title = $cart[0]['title'] ?? null;
    $amount = $cart[0]['amount'] ?? null;
    $description = $cart[0]['description'] ?? null;
    $ourDescription = $cart[0]['ourDescription'] ?? null;
    $image = $cart[0]['image'] ?? null;

    $image = str_replace(['http://localhost/vouchers/', 'https://vouchers.qualityfriend.solutions/'], '', $image);
    $image = str_replace(['../'], './', $image);


    $quantity = $cart[0]['quantity'] ?? null;


} else {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}


$v = '';
$user_id = 0;
$sql = "SELECT user_id,is_recurring,recurring_end_day,recurring_end_month,active_to,active_from FROM tbl_voucher WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $voucher_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $active_from = $row['active_from'];
    $user_id = $row['user_id'];
    $active_to = $row['active_to'];
    $is_recurring = $row['is_recurring'];
    $recurring_end_day = $row['recurring_end_day'];
    $recurring_end_month = $row['recurring_end_month'];
    $current_year = date('Y');

    if ($is_recurring == 1) {
        $v = sprintf('%02d/%02d/%04d', $recurring_end_day, $recurring_end_month, $current_year);
    } else {
        if ($active_to == '' || is_null($active_to)) {
            $v = date('d/m/Y', strtotime($create_at . ' +1 year'));
        } else {
            $v = date('d/m/Y', strtotime($active_to));
        }
    }
} else {

}

$stmt->close();


?>


<?php

// check promocode
$promo_code_id = 0;
$discount = 0;
$total = $amount;
if ($promoCode != '') {
    // Prepare the SELECT statement
    $stmt = $conn->prepare("SELECT promo_code_id, discount_type, discount_value, max_uses, current_uses
                            FROM tbl_promocodes
                            WHERE user_id = ? AND code = ? AND is_delete = 0 
                            AND start_date <= NOW() AND end_date >= NOW()");
    $stmt->bind_param("is", $user_id, $promoCode);
    $stmt->execute();
    $result_promo = $stmt->get_result();

    if ($result_promo && $result_promo->num_rows > 0) {
        $row = $result_promo->fetch_assoc();

        if ($row['current_uses'] < $row['max_uses'] || $row['max_uses'] == 0) {
            $promo_code_id = $row['promo_code_id'];
            $discount_type = $row['discount_type'];
            $discount_value = $row['discount_value'];

            if ($discount_type == "PERCENTAGE") {
                $discount = ($amount * $discount_value) / 100;
                $total = $amount - $discount;
            } else if ($discount_type == "FIXED") {
                $discount = $discount_value;
                $total = $amount - $discount;
            }

            // Prepare the UPDATE statement to increment current_uses
            $update_stmt = $conn->prepare("UPDATE tbl_promocodes 
                                           SET current_uses = current_uses + 1 
                                           WHERE promo_code_id = ?");
            $update_stmt->bind_param("i", $promo_code_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }

    $stmt->close();
}


$qr_code = 'QR-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
$tbl_transaction_id = '';
$p_time = date("Y-m-d H:i:s"); // Purchase time
$no_of_redeems = 0;
$redeem_time = NULL;

// Prepare SQL statement
$sql = "INSERT INTO tbl_users_vouchers 
    (first_name, last_name, email, phone, country, address, voucher_id, title, amount, description,our_description,
     image, quantity, total, qr_code, tbl_transaction_id, p_time, no_of_redaems, redaem_time,user_id,tax_number,`valid_until`,`promo_code_id`,`discount`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param(
    "ssssssssdsssidsisssissid",
    $first_name,
    $last_name,
    $email,
    $phone,
    $country,
    $address,
    $voucher_id,
    $title,
    $amount,
    $description,
    $ourDescription,
    $image,
    $quantity,
    $total,
    $qr_code,
    $tbl_transaction_id,
    $p_time,
    $no_of_redeems,
    $redeem_time,
    $user_id,
    $tax_number,
    $v,
    $promo_code_id,
    $discount
);

// Execute statement
if ($stmt->execute()) {
    $last_insert_id = $conn->insert_id; // Get last inserted ID

    if ($promo_code_id != 0) {
        $insert_stmt = $conn->prepare("INSERT INTO tbl_promocodeusage (promo_code_id, purchase_id, applied_discount, usage_date) 
                               VALUES (?, ?, ?, NOW())");
        $insert_stmt->bind_param("iid", $promo_code_id, $last_insert_id, $discount);
        $insert_stmt->execute();
        $insert_stmt->close();
    }




    echo 'done|' . $last_insert_id; // Output both values separated by "|"  
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();


?>