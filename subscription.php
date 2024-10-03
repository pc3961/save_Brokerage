<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './mail/Exception.php';
require './mail/PHPMailer.php';
require './mail/SMTP.php';

header('Content-Type: application/json');  // Set response to JSON format
$response = ['status' => 'error', 'message' => 'An error occurred.'];  // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'sh42-750.omesa-hosting.ca';  // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'archana@saveonbrokerage.ca';  // SMTP username
        $mail->Password = 'H@^zx8t7-hf7';  // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('archana@saveonbrokerage.ca', 'Archana');
        $mail->addAddress('smerai@omesacreative.ca', 'Shailesh Merai');  // Add recipient
        $mail->addAddress('sachitshetty13@gmail.com', 'Sachit Shetty');  // Add recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'E-newsletter Subscription';
        $mail->Body = '<b>E-newsletter Subscription</b><br><br>' . '<br><b>Email:</b> ' . $_POST['email'];
        $mail->AltBody = "E-newsletter Subscription" . "\nEmail: " . $_POST['email'];

        // Send the email
        $mail->send();

        // If mail is sent successfully, return success response
        $response['status'] = 'success';
        $response['message'] = 'Thank you for subscribing to our newsletter!';

    } catch (Exception $e) {
        // If there was an error sending the email
        $response['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }

    // Return the JSON response
    echo json_encode($response);
}
?>