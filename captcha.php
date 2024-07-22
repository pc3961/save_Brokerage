<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_secret = '6LfIzRUqAAAAAPzljJhdvWZBkWtPov-9i0ouXn_q'; // Replace with your actual secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Make a POST request to the reCAPTCHA API
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        echo 'Please complete the CAPTCHA.';
    } else {
        echo 'CAPTCHA completed successfully.';
        // Continue with form processing
    }
}
?>
