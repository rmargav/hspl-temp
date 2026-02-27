<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $to = "bharatenergy8889@gmail.com";   // PUT YOUR EMAIL HERE
    $subject = "New Contact Form Submission";

    // Collect form data
    $name     = $_POST['Complete_Name'];
    $phone    = $_POST['Phone_Number'];
    $email    = $_POST['Email_Address'];
    $service  = $_POST['Service'];
    $location = $_POST['User_Location'];
    $message  = $_POST['Message'];

    // Email content
    $body = "
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Service:</strong> $service</p>
    <p><strong>Location:</strong> $location</p>
    <p><strong>Message:</strong><br>$message</p>
    ";

    // Headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send mail
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Message failed to send.'); window.history.back();</script>";
    }
}

?>
