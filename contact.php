<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './mail/Exception.php';
require './mail/PHPMailer.php';
require './mail/SMTP.php';

header('Content-Type: application/json');  // Set response to JSON format
$response = ['status' => 'error', 'message' => 'An error occurred.'];  // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // reCAPTCHA secret key
    $recaptcha_secret = '6LfIzRUqAAAAAA0HTs7QCprDxkNsN7JLMTckmr7h';  // Replace with your actual secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response with Google
    $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response";
    $recaptcha_verify_response = file_get_contents($recaptcha_verify_url);
    $response_keys = json_decode($recaptcha_verify_response, true);

    if (intval($response_keys["success"]) !== 1) {
        // reCAPTCHA failed
        $response['message'] = 'Please complete the CAPTCHA.';
        echo json_encode($response);
        exit;
    }

    // reCAPTCHA successful, proceed with mailer
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'sh42-750.omesa-hosting.ca';  // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'archana@saveonbrokerage.ca';  // SMTP username
        $mail->Password = 'ojgavpiqkxixbqpz';  // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('archana@saveonbrokerage.ca ', 'Archana');
        $mail->addAddress('smerai@omesacreative.ca', 'Shailesh Merai');  // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body = '<b>Contact Form Submission</b>' . '<br><b>First Name:</b> ' . $_POST['fname'] . '<br><b>Last Name:</b> ' . $_POST['lname'] . '<br><b>Email:</b> ' . $_POST['email'] . '<br><b>Phone Number:</b> ' . $_POST['pnumber'] . '<br><b>Message:</b> ' . $_POST['message'];
        $mail->AltBody = "Contact Form Submission" . "\nFirst Name: " . $_POST['fname'] . "\nLast Name: " . $_POST['lname'] . "\nEmail: " . $_POST['email'] . "\nPhone Number: " . $_POST['pnumber'] . "\nMessage: " . $_POST['message'];

        // Send the email
        $mail->send();

        // If mail is sent successfully, return success response
        $response['status'] = 'success';
        $response['message'] = 'THANK YOU We are excited to hear from you! We will connect to discuss more about your interest.';

    } catch (Exception $e) {
        // If there was an error sending the email
        $response['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }

    // Return the JSON response
    echo json_encode($response);
}
?>