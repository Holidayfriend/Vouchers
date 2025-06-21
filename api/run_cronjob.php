<?php
include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');
$lang = 'en';
$translations = [
    'en' => [
        'subject' => "Your Voucher Purchase Confirmation - $hotel_name",
        'payment_successful' => 'Payment Successful',
        'transaction_details' => 'Transaction Detail',
        'more_details' => 'More Detail',
        'transaction_id' => 'Transaction ID',
        'payer_name' => 'Payer Name',
        'email' => 'Email',
        'amount' => 'Amount',
        'payer_id' => 'Payer ID',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'per_voucher' => 'Per Voucher',
        'quantity' => 'Quantity',
        'total_amount' => 'Total Amount',
        'phone' => 'Phone',
        'address' => 'Address',
        'download_voucher' => 'Download Voucher',
        'contact_us' => 'For any inquiries, please contact us at',
        'copyright' => 'All rights reserved.',
    ],
    'it' => [
        'subject' => "Conferma di acquisto del tuo voucher - $hotel_name",
        'payment_successful' => 'Pagamento Riuscito',
        'transaction_details' => 'Dettagli Transazione',
        'more_details' => 'Maggiori Dettagli',
        'transaction_id' => 'ID Transazione',
        'payer_name' => 'Nome del Pagatore',
        'email' => 'E-mail',
        'amount' => 'Importo',
        'payer_id' => 'ID Pagatore',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'per_voucher' => 'Per Voucher',
        'quantity' => 'Quantità',
        'total_amount' => 'Importo Totale',
        'phone' => 'Telefono',
        'address' => 'Indirizzo',
        'download_voucher' => 'Scarica il Voucher',
        'contact_us' => 'Per qualsiasi richiesta, contattaci all’indirizzo',
        'copyright' => 'Tutti i diritti riservati.',
    ],
    'de' => [
        'subject' => "Ihre Gutschein-Kaufbestätigung - $hotel_name",
        'payment_successful' => 'Zahlung Erfolgreich',
        'transaction_details' => 'Transaktionsdetails',
        'more_details' => 'Weitere Details',
        'transaction_id' => 'Transaktions-ID',
        'payer_name' => 'Name des Zahlers',
        'email' => 'E-Mail',
        'amount' => 'Betrag',
        'payer_id' => 'Zahler-ID',
        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'per_voucher' => 'Pro Gutschein',
        'quantity' => 'Menge',
        'total_amount' => 'Gesamtbetrag',
        'phone' => 'Telefon',
        'address' => 'Adresse',
        'download_voucher' => 'Gutschein herunterladen',
        'contact_us' => 'Bei Fragen kontaktieren Sie uns unter',
        'copyright' => 'Alle Rechte vorbehalten.',
    ]
];



$sql = "SELECT 
            a.id, a.v_id, a.is_run, a.t_id, a.lang,
            b.first_name, b.last_name, b.email, b.title, 
            b.amount, b.description, b.our_description, b.image, b.quantity, 
            b.total, b.qr_code, b.p_time, b.user_id, b.phone, b.address
        FROM tbl_email_jobs AS a 
        INNER JOIN tbl_users_vouchers AS b ON a.v_id = b.id 
        WHERE a.is_run = 0";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Base URL for images and voucher download
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base = 'http://localhost/vouchers/';
} else {
    $base = 'https://vouchers.qualityfriend.solutions/';
}

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $lang = $row['lang'];
    $trans = $translations[$lang] ?? $translations['en'];
    $v_id = $row['v_id'];
    $is_run = $row['is_run'];
    $t_id = $row['t_id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $title = $row['title'];
    $amount_v = $row['amount'];
    $description = $row['description'];
    $our_description = $row['our_description'];
    $image = $base . $row['image'];
    $quantity = $row['quantity'];
    $total = $row['total'];
    $qr_code = $row['qr_code'];
    $p_time = $row['p_time'];
    $user_id = $row['user_id'];
    $phone = $row['phone'];
    $address = $row['address'];

    // Fetch hotel details
    $query = "SELECT `hotel_name`, `hotel_website`, `logo`,`email` FROM `tbl_user` WHERE `user_id` = ?";
    $stmt_hotel = $conn->prepare($query);
    $stmt_hotel->bind_param("i", $user_id);
    $stmt_hotel->execute();
    $result1 = $stmt_hotel->get_result();

    $hotel_name = '';
    $hotel_email = '';
    $hotel_website = '';
    $hotel_image = '';

    if ($row1 = $result1->fetch_assoc()) {
        $hotel_name = $row1['hotel_name'];
        $hotel_email = $row1['email'];
        $hotel_website = $row1['hotel_website'];
        $hotel_image = $base . $row1['logo'];
    }
    $stmt_hotel->close();

    // Fetch transaction details if t_id > 0 (your exact block)
    $transaction_id = '';
    $payer_name = '';
    $payer_email = '';
    $payer_id = '';
    $transaction_amount = '';
    $purchase_date = '';
    $currency = 'EUR';

    if ($t_id > 0) {
        $query = "SELECT `transaction_id`, `payer_name`, `payer_email`, `payer_id`, `amount`, `purchase_date`
                  FROM `tbl_transaction` WHERE `id` = ?";
        $stmt_trans = $conn->prepare($query);
        $stmt_trans->bind_param("i", $t_id);
        $stmt_trans->execute();
        $result_trans = $stmt_trans->get_result();

        if ($row_trans = $result_trans->fetch_assoc()) {
            $transaction_id = $row_trans['transaction_id'];
            $payer_name = $row_trans['payer_name'];
            $payer_email = $row_trans['payer_email'];
            $payer_id = $row_trans['payer_id'];
            $transaction_amount = $row_trans['amount'];
            $purchase_date = $row_trans['purchase_date'];
            $currency = 'EUR';
        }
        $stmt_trans->close();
    }

    // Voucher download URL with qr_code parameter
    $voucher_url = $base . "api/download_voucher.php?qr_code=" . urlencode($qr_code) . "&lang=" . urlencode($lang);

    // Email subject
    $emailsubject = $trans['subject'];

    // Email body (HTML)
    // Email body (HTML)
    $emailBody = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .header { background-color: #28a745; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f5f5f5; }
        img { max-width: 100%; height: auto; }
        .text-center { text-align: center; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .mt-5 { margin-top: 20px; }
        .mb-5 { margin-bottom: 20px; }
        .shadow-lg { box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .rounded { border-radius: 5px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #C72B42; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background-color: #C72B42; color: white; }
    </style>
</head>
<body>
    <div class='container mt-5 mb-5'>
        <div class='header'>
            <h2>{$trans['payment_successful']}</h2>
        </div>
        <div class='text-center mt-3 '>
            <img src='$hotel_image' alt='Hotel Image' style='max-width: 200px; border-radius: 5px;'>
        </div>
        <h4 class='mt-3 text-center'>$hotel_name</h4>
        <p class='text-center'><a href='$hotel_website' target='_blank'>$hotel_website</a></p>";

    // Add transaction details if $t_id > 0
    if ($t_id > 0) {
        $emailBody .= "
        <h5>{$trans['transaction_details']}</h5>
        <table class='mt-3'>
            <tr><th>{$trans['transaction_id']}</th><td>$transaction_id</td></tr>
            <tr><th>{$trans['payer_name']}</th><td>$payer_name</td></tr>
            <tr><th>{$trans['email']}</th><td>$payer_email</td></tr>
            <tr><th>{$trans['amount']}</th><td>$transaction_amount $currency</td></tr>
            <tr><th>{$trans['payer_id']}</th><td>$payer_id</td></tr>
        </table>";
    }

    $emailBody .= "
        <h5>{$trans['more_details']}</h5>
        <table class='mt-3'>
            <tr><th>{$trans['first_name']}</th><td>$first_name</td></tr>
            <tr><th>{$trans['last_name']}</th><td>$last_name</td></tr>
            <tr><th>{$trans['email']}</th><td>$email</td></tr>
            <tr><th>{$trans['per_voucher']}</th><td>$amount_v $currency</td></tr>
            <tr><th>{$trans['quantity']}</th><td>$quantity</td></tr>
            <tr><th>{$trans['total_amount']}</th><td>$total $currency</td></tr>
            <tr><th>{$trans['phone']}</th><td>$phone</td></tr>
            <tr><th>{$trans['address']}</th><td>$address</td></tr>
        </table>
        <div class='text-center mt-4'>
            <a href='$voucher_url' class='btn'>{$trans['download_voucher']}</a>
        </div>
        <div class='footer'>
            <p>{$trans['contact_us']} <a href='mailto:info@weihrerhof.com'>info@weihrerhof.com</a>.</p>
            <p>© " . date('Y') . " $hotel_name. {$trans['copyright']}</p>
        </div>
    </div>
</body>
</html>";


    // Send email
    if (sendEmail($email, $emailsubject, $emailBody, $hotel_name, $hotel_emai)) {
        markEmailAsSent($conn, $id);
        echo "Email sent to $email for Voucher ID: $v_id<br>";
    } else {
        echo "Failed to send email to $email for Voucher ID: $v_id<br>";
    }
}

// Close statement and connection
$stmt->close();
$conn->close();

function sendEmail($to, $subject, $body, $fromName, $replyTo)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.hostinger.com";
    $mail->Port = 587;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "noreply@qualityfriend.solutions";
    $mail->Password = 'Pakistan@143';
    $mail->SetFrom("noreply@qualityfriend.solutions", $fromName);
    $mail->AddReplyTo($replyTo, $fromName);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    return $mail->Send();
}

function markEmailAsSent($conn, $emailJobId)
{
    $sql = "UPDATE tbl_email_jobs SET done_at = NOW(), is_run = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $emailJobId);
    $stmt->execute();
    $stmt->close();
}
