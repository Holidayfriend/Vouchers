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

// Close database connection
$conn->close();
?>