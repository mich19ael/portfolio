<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Set up email parameters
        $to = "michaeltariku180@gmail.com";
        $subject = "New Contact Form Submission from $name";
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n\n";
        $email_message .= "Message:\n$message";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Send email
        if (mail($to, $subject, $email_message, $headers)) {
            // Log the submission
            $log_entry = date('Y-m-d H:i:s') . " - Name: $name, Email: $email\n";
            file_put_contents('contacts.txt', $log_entry, FILE_APPEND);
            
            // Success
            header("Location: ../index.php?status=success");
            exit();
        } else {
            // Email sending failed
            header("Location: ../index.php?status=error&message=Failed to send email");
            exit();
        }
    } else {
        // Redirect back with errors
        $error_string = implode(",", $errors);
        header("Location: ../index.php?status=error&message=" . urlencode($error_string));
        exit();
    }
} else {
    // If not a POST request, redirect to home
    header("Location: ../index.php");
    exit();
}
?> 