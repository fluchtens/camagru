<?php
require "../lib/PHPMailer/Exception.php";
require "../lib/PHPMailer/PHPMailer.php";
require "../lib/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendActivationEmail($email) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = "";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("camagru.fluchtens@gmail.com", "Camagru");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->AddEmbeddedImage("../assets/camagru.png", "logo");
    $mail->Subject = "Confirmation of account registration";
    $mail->Body = "
        <div style='max-width: 640px; margin: 0 auto; text-align: center;'>
            <img src='cid:logo' alt='logo' style='width: 300px'>
            <p>Thank you for creating a new account to access Camagru. To benefit from all Camagru services, you must verify the e-mail address on your account.</p>
        </div>
    ";

    $mail->send();
}
?>
