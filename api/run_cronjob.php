<?php
include '../util_config.php';
include '../util_session.php';
include '../smtp/PHPMailerAutoload.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$lang = 'en';
$translations = [
    'en' => [
        'subject' => "Your %s Voucher Is Ready",
        'greeting' => "Dear",
        'thank_you' => "Thank you for your purchase – your voucher is now available!",
        'download_voucher' => 'Download your voucher here',
        'contact_text' => "If you have any questions or special requests, feel free to contact us:",
        'closing' => "Warm regards",
        'team' => "Your %s Team",
        'powered_by' => "powered by Holidayfriend"
    ],
    'it' => [
        'subject' => "Il Suo buono %s è pronto",
        'greeting' => "Caro",
        'thank_you' => "Grazie per il Suo acquisto – il Suo buono è ora disponibile!",
        'download_voucher' => 'Scaricare il buono qui',
        'contact_text' => "Per domande o richieste particolari siamo volentieri a Sua disposizione:",
        'closing' => "Un cordiale saluto",
        'team' => "Il Suo team del %s",
        'powered_by' => "powered by Holidayfriend"
    ],
    'de' => [
        'subject' => "Ihr %s-Gutschein ist da",
        'greeting' => "Hallo",
        'thank_you' => "Vielen Dank für Ihren Kauf – Ihr Gutschein ist jetzt verfügbar!",
        'download_voucher' => 'Gutschein hier herunterladen',
        'contact_text' => "Bei Fragen oder besonderen Wünschen melden Sie sich gerne:",
        'closing' => "Freundliche Grüße",
        'team' => "Ihr %s-Team",
        'powered_by' => "powered by Holidayfriend"
    ]
];

$sql = "SELECT 
            a.id, a.v_id, a.is_run, a.t_id, a.lang,
            b.first_name, b.last_name, b.email, b.title, 
            b.amount, b.description, b.our_description, b.image, b.quantity, 
            b.total, b.qr_code, b.p_time, b.user_id, b.phone, b.address, b.valid_until
        FROM tbl_email_jobs AS a 
        INNER JOIN tbl_users_vouchers AS b ON a.v_id = b.id 
        WHERE a.is_run = 0";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Prepare Error (email_jobs): " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("SQL Execution Error (email_jobs): " . $conn->error);
}

// Base URL for images and voucher download
$base = $_SERVER['HTTP_HOST'] === 'localhost' ? 'http://localhost/vouchers/' : 'https://vouchers.qualityfriend.solutions/';
$holidayfriend_logo = $base . 'images/holidayfriend_logo.png'; // Adjust path to Holidayfriend logo

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $lang = $row['lang'];
    $trans = $translations[$lang] ?? $translations['en'];
    $v_id = $row['v_id'];
    $is_run = $row['is_run'];
    $t_id = $row['t_id'];
    $first_name = $row['first_name'] ?: 'Guest';
    $last_name = $row['last_name'];
    $email = $row['email'];
    $title = $row['title'];
    $amount_v = $row['amount'];
    $description = $row['description'];
    $our_description = $row['our_description'] ?: $description;
    $image = $base . $row['image'];
    $quantity = $row['quantity'];
    $total = $row['total'];
    $qr_code = $row['qr_code'];
    $p_time = $row['p_time'];
    $user_id = $row['user_id'];
    $phone = $row['phone'];
    $address = $row['address'];
    $valid_until = $row['valid_until'] ?: date('Y-m-d', strtotime($p_time . ' +1 year'));

    // Fetch hotel details
    $query = "SELECT `hotel_name`, `hotel_website`, `logo`, `email`, `person_phone`, `discover_more`, `discover_more_it`, `discover_more_de`, `hotel_address` 
              FROM `tbl_user` WHERE `user_id` = ?";
    $stmt_hotel = $conn->prepare($query);
    if (!$stmt_hotel) {
        die("SQL Prepare Error (user): " . $conn->error);
    }
    $stmt_hotel->bind_param("i", $user_id);
    $stmt_hotel->execute();
    $result1 = $stmt_hotel->get_result();

    $hotel_name = 'Default Hotel';
    $hotel_email = 'info@default.com';
    $hotel_phone = '+39 0471 345102'; // Fallback phone number
    $hotel_website = 'https://default.com';
    $hotel_address = $lang === 'it' ? 'Default Hotel – Costalovara 22 – I-39054 Renon' : 'Default Hotel – Wolfsgruben 22 – I-39054 Ritten';
    $hotel_image = $base . 'default_logo.png';
    $discover_more = $hotel_website;
    $discover_more_it = $hotel_website;
    $discover_more_de = $hotel_website;

    if ($result1->num_rows > 0) {
        $row1 = $result1->fetch_assoc();
        $hotel_name = $row1['hotel_name'] ?: $hotel_name;
        $hotel_email = $row1['email'] ?: $hotel_email;
        $hotel_phone = $row1['person_phone'] ?: $hotel_phone;
        $hotel_website = $row1['hotel_website'] ?: $hotel_website;
        $raw_hotel_address = $row1['hotel_address'] ?: ($lang === 'it' ? 'Costalovara 22 – I-39054 Renon' : 'Wolfsgruben 22 – I-39054 Ritten');
        $hotel_image = $row1['logo'] ? $base . $row1['logo'] : $hotel_image;
        $discover_more = $row1['discover_more'] ?: $hotel_website;
        $discover_more_it = $row1['discover_more_it'] ?: $hotel_website;
        $discover_more_de = $row1['discover_more_de'] ?: $hotel_website;
        // Format hotel address with hotel name
        $hotel_address = $hotel_name . ' – ' . $raw_hotel_address;
    } else {
        echo "No hotel data found for user_id: $user_id in Voucher ID: $v_id<br>";
    }
    $stmt_hotel->close();

    // Select discover_more URL based on language
    $discover_more_url = $lang === 'it' ? $discover_more_it : ($lang === 'de' ? $discover_more_de : $discover_more);
    $voucher_url = $base . "api/download_voucher.php?qr_code=" . urlencode($qr_code) . "&lang=" . urlencode($lang);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address ($email) for Voucher ID: $v_id, skipping email sending<br>";
        continue;
    }

    // Email subject
    $emailsubject = sprintf($trans['subject'], $hotel_name);

    // Format team name for email
    $team_name = sprintf($trans['team'], $hotel_name);

    // Email body (HTML)
    $emailBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
            color: #555;
        }
        .download-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #C72B42;
            color: white !important;
            text-decoration: none !important;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            transition: background-color 0.3s;
        }
        .download-btn:hover {
            background-color: #a12336;
            color: white !important;
            text-decoration: none !important;
        }
        .download-btn:visited, .download-btn:active {
            color: white !important;
            text-decoration: none !important;
        }
        .contact-info a {
            color: #007bff;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .footer img {
            max-width: 100px;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <img src='$hotel_image' alt='Hotel Logo'>
        </div>
        <div class='content'>
            <p>{$trans['greeting']} $first_name,</p>
            <p>{$trans['thank_you']}</p>
            <p><a href='$voucher_url' class='download-btn'>📥 {$trans['download_voucher']}</a></p>
            <p class='contact-info'>
                {$trans['contact_text']}<br>
                📞 <a href='tel:$hotel_phone'>$hotel_phone</a> – ✉️ <a href='mailto:$hotel_email'>$hotel_email</a>
            </p>
            <p>{$trans['closing']}</p>
            <p>$team_name</p>
            <p><a href='$hotel_website' target='_blank'>$hotel_website</a></p>
            <p>$hotel_address</p>
        </div>
        <div class='footer'>
            <p>{$trans['powered_by']}</p>
            <img src='https://vouchers.qualityfriend.solutions/assets/images/background/holiday.png' alt='Holidayfriend Logo'>
        </div>
    </div>
</body>
</html>";

    // Send email
    if (sendEmail($email, $emailsubject, $emailBody, $hotel_name, $hotel_email)) {
        markEmailAsSent($conn, $id);
        echo "Email sent to $email for Voucher ID: $v_id<br>";
    } else {
        echo "Failed to send email to $email for Voucher ID: $v_id: " . error_get_last()['message'] . "<br>";
    }
}

// Close statement and connection
$stmt->close();
$conn->close();

function sendEmail($to, $subject, $body, $fromName, $replyTo)
{
    $mail = new PHPMailer();
    try {
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
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        return $mail->Send();
    } catch (Exception $e) {
        echo "Email sending failed for $to: " . $mail->ErrorInfo . "<br>";
        return false;
    }
}

function markEmailAsSent($conn, $emailJobId)
{
    $sql = "UPDATE tbl_email_jobs SET done_at = NOW(), is_run = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Prepare Error (markEmailAsSent): " . $conn->error);
    }
    $stmt->bind_param("i", $emailJobId);
    $stmt->execute();
    $stmt->close();
}
?>