<?php
include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');

// Fetch pending email jobs
$sql = "SELECT * FROM `tbl_other_email_jobs` WHERE `is_run` = 0";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $to = $row['email'];
        $subject = $row['subject'];
        $body = $row['email_text'];
        $lang = $row['lang'];
        $fromName = "QualityFriend Solutions";
        $replyTo = "noreply@qualityfriend.solutions";

        // Send email using the provided sendEmail function
        $emailSent = sendEmail($to, $subject, $body, $fromName, $replyTo);

        if ($emailSent) {
            // Update the email job status
            $done_at = date("Y-m-d H:i:s");
            $update_sql = "UPDATE `tbl_other_email_jobs` SET `is_run` = 1, `done_at` = '$done_at' WHERE `id` = $id";
            $conn->query($update_sql);
        } else {
            // Optionally log failed email attempts
            error_log("Failed to send email to $to with subject: $subject");
        }
    }
}
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

// Close database connection
$conn->close();
?>