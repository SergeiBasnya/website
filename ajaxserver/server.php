<?php
// Set the header response to JSON
header('Content-type: application/json');

// Do not show php error
ini_set('display_errors','Off');

$response = array();

/*
*Handle Message From
*/
// check email into post data
if (isset($_POST['submit_message'])) {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $product = trim($_POST['product']);
    $message = trim($_POST['message']);
    
    
    $email = filter_var(@$_POST['email'], FILTER_SANITIZE_EMAIL );
    
    $name = htmlentities($name);
    $product = htmlentities($product);
    $message = htmlentities($message);
    
    // Validate data first
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50 ) {
        http_response_code(403);
        $response['error']['email'] = "Une adresse mail valide est requise";
    }
    if (empty($name) ) {
        http_response_code(403);
        $response['error']['name'] = 'Votre nom ici';
    }
    if (empty($message)) {
        http_response_code(403);
        $response['error']['message'] = 'Les messages vides ne sont pas autorisés';
    }
    
    // Process to emailing if forms are correct
    if (!isset($response['error']) || $response['error'] === '') {
        
        
        /* in this sample code, messages will be stored in a text file */
        //        PROCESS TO STORE MESSAGE GOES HERE
        
        $content = "Name: " . $name . " \r\nEmail: " . $email .  " \r\nMessage: " . $message;
        $content = str_replace(array('<','>'),array('&lt;','&gt;'),$content);
        $name = str_replace(array('<','>'),array('&lt;','&gt;'),$name);
        $message = str_replace(array('<','>'),array('&lt;','&gt;'),$message);
        
        // -- BELOW : EXAMPLE SEND YOU AN EMAIL CONTAINING THE MESSAGE (comment to disable it/ uncomment it to enable it)
        // Set the recipient email address.
        // IMPORTANT - FIXME: Update this to your desired email address (relative to your server domaine).
        $recipient = "contact@addequa.fr";
        
        // Set the email subject.
        $subject = "Contact Site Web".$name;
        
        // Build the email content.
        $email_content = $message."\n \n";
        $email_content .= "Sincerely,";
        $email_content .= "From: $name\n\n";
        $email_content .= "Email: $email\n\n";
        
        // Build the email headers.
        $email_headers = "MIME-Version: 1.0" . "\r\n";
        $email_headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $email_headers .= "From: $name <$email>" . "\r\n";
        $email_headers .= "Reply-To: <$email>";
        
        
        // Send the email.
        if ( mail($recipient, $subject, $email_content, $email_headers) ) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            $response['success'] = 'Merci ! Votre message a bien été envoyé.';
            
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            $response['error'] = 'Oops! Quelque chose ne va pas avec votre message';
            $content = 'Erreur message - Ne peut être envoyé'. "\r\n" . $content;
            // Uncomment below to Write message into a file as a backup
            //file_put_contents("message.txt", $content . "\r\n---------\r\n", FILE_APPEND | LOCK_EX);
        }
        
    }
    else {
        // Set a 403 (error) forbidden response code due missing data to error.
        http_response_code(403);
        //$response['error'] = '<ul>' . $response['error'] . '</ul>';
    }
    
    
    $response['email'] = $email;
    $response['form'] = 'submit_message';
    echo json_encode($response);
    /*
    response will be : {
        email : 'user@email.com',
        form : 'submit_message',
        success : 'Feedback message if success',
        error : 'A feedback message if an error happened, or a JSON'
    }
    */
}


// Receive email newsletter subscription
if (isset($_POST['submit_email'])) {
    
    $email = filter_var(@$_POST['email'], FILTER_SANITIZE_EMAIL );
    
    // Form validation handled by the server here if required
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50 ) {
        $response['error']['email'] = "Un Email Valide est requis";
    }
    
    
    if (!isset($response['error']) || $response['error'] === '') {
        
        //        PROCESS TO STORE EMAIL GOES HERE
        
        
        $email = str_replace(array('<','>'),array('&lt;','&gt;'),$email);
        
        // -- BELOW : EXAMPLE SEND YOU AN EMAIL ABOUT THE NEW USER (comment to disable it/ uncomment it to enable it)
        // Set the recipient email address.
        // IMPORTANT - FIXME: Update this to your desired email address (relative to your server domaine).
        $recipient = "contact@addequa.fr";
        
        // Set the email subject.
        $subject = "New subscription";
        
        // Build the email content.
        $email_content = "Hello \n New user subscription.\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Sincerely,";
        
        // Build the email headers.
        
        $email_headers = "MIME-Version: 1.0" . "\r\n";
        $email_headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $email_headers .= "From: <$email>" . "\r\n";
        
        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            $response['success'] = "Merci de votre message et à très vite.";
            
        } else {
            // Set a 500 (internal server error) response code.
            //http_response_code(500);
            http_response_code(500);
            $response['error'] = "Oops! Quelque chose ne va pas avec votre message.";
            
        }
        // -- END OF : EXAMPLE YOU AN EMAIL ABOUT THE NEW USER
        
        // Uncomment below to save email list to a file as backup
        file_put_contents("email.txt", $email . " \r\n", FILE_APPEND | LOCK_EX);
        
        
    }
    $response['email'] = $email;
    $response['form'] = 'submit_email';
    echo json_encode($response);
    
    
    /*response will be : {
        email : 'user@email.com',
        form : 'submit_message',
        success : 'Feedback message if success',
        error : 'A feedback message if an error happened, or a JSON'
    }*/
    
}