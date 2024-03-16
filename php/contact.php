<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require './../PHPMailer/Exception.php';
require './../PHPMailer/SMTP.php';
require './../PHPMailer/PHPMailer.php';

$errors = array();

// Check if name has been entered
if (!isset ($_POST['name'])) {
	$errors['name'] = 'Please enter your name';
}

// Check if email has been entered and is valid
if (!isset ($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$errors['email'] = 'Please enter a valid email address';
}

// Check if message has been entered
if (!isset ($_POST['message'])) {
	$errors['message'] = 'Please enter your message';
}

$errorOutput = '';

if (!empty ($errors)) {
	$errorOutput .= '<div class="alert alert-danger alert-dismissible" role="alert">';
	$errorOutput .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	$errorOutput .= '<ul>';
	foreach ($errors as $key => $value) {
		$errorOutput .= '<li>' . $value . '</li>';
	}
	$errorOutput .= '</ul>';
	$errorOutput .= '</div>';
	echo $errorOutput;
	die();
}

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	// Server settings
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'pravaskumar45@gmail.com';
	$mail->Password = 'byhgaqyvepqvdbey';
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	$mail->Port = 465;

	// Admin email
	$adminEmail = 'pravaskumar45@gmail.com';

	// Admin email content
	$adminSubject = 'New Contact Form Submission';
	$adminBody = "Dear Pravas Kumar,<br><br>";
	$adminBody .= "You have received a new message from the contact form:<br><br>";
	$adminBody .= "<strong>Name:</strong> $name<br>";
	$adminBody .= "<strong>Email:</strong> $email<br>";
	$adminBody .= "<strong>Message:</strong><br> $message<br><br>";
	$adminBody .= "Best regards,<br>TITAN";

	// User email content
	$userSubject = 'Thank You for Contacting Us';
	$userBody = "Dear $name,<br><br>";
	$userBody .= "Thank you for contacting us. We have received your message and will get back to you shortly.<br><br>";
	$userBody .= "Best regards,<br>TITAN";

	// Send email to admin
	$mail->setFrom('pravaskumar45@gmail.com', 'TITAN');
	$mail->addAddress($adminEmail);
	$mail->isHTML(true);
	$mail->Subject = $adminSubject;
	$mail->Body = $adminBody;
	$mail->send();

	// Clear recipients and send email to user
	$mail->clearAddresses();
	$mail->addAddress($email);
	$mail->Subject = $userSubject;
	$mail->Body = $userBody;
	$mail->send();

	echo '<div class="alert alert-success alert-dismissible" role="alert">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	echo 'Thank You! Your message has been sent.';
	echo '</div>';
} catch (Exception $e) {
	echo '<div class="alert alert-danger alert-dismissible" role="alert">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	echo 'Something went wrong while sending your message. Please try again later.';
	echo '</div>';
	// Log errors
	error_log("Error sending email: " . $mail->ErrorInfo);
}
?>