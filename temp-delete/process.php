<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Who is getting this email?
    $to = "info@hspl.in.net"; 
    
    // Grab the hidden field so the subject line is specific
    $serviceType = isset($_POST['service_type']) ? $_POST['service_type'] : "General Service";
    $subject = "New Website Inquiry: " . $serviceType;

    // Start building the email content dynamically
    $message = "You have received a new inquiry for: " . $serviceType . "\n\n";
    $message .= "Here are the details:\n";
    $message .= "------------------------\n";

    // Loop through ALL submitted form fields automatically
    foreach ($_POST as $key => $value) {
        // Skip the hidden field since we already used it for the subject
        if ($key === 'service_type') continue; 
        
        // Clean up the field names (e.g., "discharging_into" becomes "Discharging Into")
        $clean_key = ucwords(str_replace('_', ' ', $key));
        
        // Clean the user input to prevent injection
        $clean_value = htmlspecialchars(trim($value));
        
        $message .= "$clean_key: $clean_value\n";
    }

    $message .= "------------------------\n";

    // Email Headers
    $headers = "From: noreply@hspl.in.net\r\n";
    $headers .= "Reply-To: " . $_POST['email'] . "\r\n";

    // Send it!
    if (mail($to, $subject, $message, $headers)) {
        echo "<h2>Success! Your inquiry for the $serviceType has been sent.</h2>";
    } else {
        echo "<h2>Oops! Something went wrong. Please try again.</h2>";
    }
} else {
    echo "Bro, you can't access this page directly.";
}
?>