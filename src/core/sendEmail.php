<?php
function sendEmail($email, $subject, $message) {
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = "From: Camagru <" . getenv("SMTP_USERNAME") . ">";
    $headers[] = "Reply-To: " . getenv("SMTP_USERNAME");

    mail($email, $subject, $message, implode("\r\n", $headers));
}
?>
