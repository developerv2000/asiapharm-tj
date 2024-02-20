<?php
$name = $_POST['name'];
$phone = $_POST['phone'];

$recaptcha_secret = 'YOUR_SECRET_KEY';
$recaptcha_response = $_POST['g-recaptcha-response'];
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_data = array(
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response
);


if ($name && $phone) {
    // setup email
    $to = 'info@asiapharma.tj';
    $subject = 'Новое сообщение из сайта asiapharm.tj';
    $message = "Новый запрос о бесплатной консультации. Имя: $name. Телефон: $phone";

    $headers = 'From: test@demo.asiapharm.tj' . "\r\n" .
        'Reply-To: test@demo.asiapharm.tj' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    
    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($recaptcha_data)
        )
    );

    // validate recaptcha
    $context  = stream_context_create($options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $context);
    $recaptcha_result = json_decode($recaptcha_result);

    if ($recaptcha_result->success && $recaptcha_result->score >= 0.5) {
        if (mail($to, $subject, $message, $headers)) {
            header("Location: http://asiapharm.tj/");
            die();
        } else {
            echo "Сервис временно не работает!";
        }
    } else {
        echo "Error!";
    }
}
