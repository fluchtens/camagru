<?php
require "../../lib/PHPMailer/Exception.php";
require "../../lib/PHPMailer/PHPMailer.php";
require "../../lib/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($email, $subject, $body) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = getenv("SMTP_USERNAME");
    $mail->Password = getenv("SMTP_PASSWORD");
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("camagru.fluchtens@gmail.com", "Camagru");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->AddEmbeddedImage("../../assets/camagru.png", "logo");
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
}
?>
