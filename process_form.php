<?php
// Agar form submit hua hai tabhi aage badhein
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ==========================================
    // 1. MAIN SETTINGS (Apni details yahan dalein)
    // ==========================================
    $to_email = "owner_gmail_here@gmail.com"; // Yahan owner/client ka Gmail ID dalein
    
    // Website ka domain email (Jisse email aayega, e.g., info@hspl.in.net - Ye spam se bachayega)
    $from_email = "info@yourclientdomain.com"; 
    
    $subject = "New Quotation Request: " . (isset($_POST['product_type']) ? $_POST['product_type'] : 'Website Form');
    
    // User ka email ID jisko aap reply kar sako
    $reply_to = !empty($_POST['contact1_email']) ? filter_var($_POST['contact1_email'], FILTER_SANITIZE_EMAIL) : $from_email;

    // ==========================================
    // 2. BUILD THE HTML EMAIL BODY
    // ==========================================
    $html_content = "<html><body style='font-family: Arial, sans-serif; color: #333;'>";
    $html_content .= "<h2 style='color: #0056b3;'>New Quotation Request Received</h2>";
    $html_content .= "<p>A customer has submitted a new inquiry. Here are the complete details:</p>";
    $html_content .= "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%; max-width: 800px;'>";
    
    // Automatically capture all fields filled by the user
    foreach ($_POST as $key => $value) {
        // Sirf wahi fields dikhao jo user ne fill kiye hain
        if (!empty($value) || $value === '0') {
            
            // Agar value array hai (jaise multiple checkboxes), toh unko comma se jod do
            if (is_array($value)) {
                $value = implode(", ", $value);
            }

            // Key name ko clean karo (e.g., 'company_name' ko 'Company Name' banao)
            $clean_key = ucwords(str_replace('_', ' ', $key));
            
            $html_content .= "<tr>";
            $html_content .= "<td style='background-color: #f8f9fa; width: 40%; font-weight: bold;'>" . htmlspecialchars($clean_key) . "</td>";
            $html_content .= "<td>" . htmlspecialchars($value) . "</td>";
            $html_content .= "</tr>";
        }
    }
    $html_content .= "</table>";
    $html_content .= "<br><p><em>This email was generated automatically from your website.</em></p>";
    $html_content .= "</body></html>";

    // ==========================================
    // 3. EMAIL HEADERS & ATTACHMENT LOGIC
    // ==========================================
    $boundary = md5(uniqid(time()));
    
    // Standard Headers
    $headers = "From: Web Inquiry <" . $from_email . ">\r\n";
    $headers .= "Reply-To: " . $reply_to . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Email Body
    $message = "--$boundary\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $message .= $html_content . "\r\n\r\n";

    // File Attachment Handle Karna (Agar user ne sketch upload kiya hai)
    if (isset($_FILES['installation_sketch']) && $_FILES['installation_sketch']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name  = $_FILES['installation_sketch']['tmp_name'];
        $file_name      = $_FILES['installation_sketch']['name'];
        $file_type      = $_FILES['installation_sketch']['type'];
        
        $file_content = file_get_contents($file_tmp_name);
        $encoded_content = chunk_split(base64_encode($file_content));

        $message .= "--$boundary\r\n";
        $message .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= $encoded_content . "\r\n\r\n";
    }

    $message .= "--$boundary--";

    // ==========================================
    // 4. SEND MAIL AND REDIRECT
    // ==========================================
    if (mail($to_email, $subject, $message, $headers)) {
        // Success Message
        echo "<div style='text-align: center; margin-top: 50px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='color: #28a745;'>Form Submitted Successfully!</h2>";
        echo "<p>Thank you for your inquiry. We have received your details and will get back to you shortly.</p>";
        echo "<a href='javascript:history.back()' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;'>Go Back</a>";
        echo "</div>";
    } else {
        // Error Message
        echo "<div style='text-align: center; margin-top: 50px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='color: #dc3545;'>Submission Failed</h2>";
        echo "<p>There was an error sending your request. Please try again later.</p>";
        echo "<a href='javascript:history.back()' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px;'>Go Back</a>";
        echo "</div>";
    }
} else {
    echo "Direct access not allowed.";
}
?>