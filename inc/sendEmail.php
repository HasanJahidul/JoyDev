<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';


// Replace this with your own email address
$siteOwnersEmail = 'jahidul0hasan@gmail.com';
$error='';

//phpmailer settings

$mail = new PHPMailer(true);

   

// $sendMail -> isSMTP();
// $sendMail ->Host='hasanjoy27@gmail.com';
// $sendMail ->Port=587;
// $sendMail ->SMTPAuth=true;
// $sendMail ->SMTPSecure='tls';
// $sendMail ->Username='';
// $sendMail ->Password='DevilHacker15';



if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message = "Email from: " . $name . "<br />";
   $message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


   if ($error) {
	$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
	$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
	$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
	
	echo $response;

      
		
	} # end if - no validation error

	else {
	// 	ini_set("sendmail_from", $siteOwnersEmail); // for windows server
    //   $mail = mail($siteOwnersEmail, $subject, $message, $headers);

	try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
	$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host       = "ssl://smtp.gmail.com";                   //Set the SMTP server to send through
    $mail->Username   = 'hasanjoy27@gmail.com';                     //SMTP username
    $mail->Password   = 'DevilHacker15';                               //SMTP password
    $mail->Port       = 465;  
	
		//Recipients
		$mail->setFrom($email);
		$mail->addAddress($siteOwnersEmail);     //Add a recipient
		$mail->addReplyTo($email,$name);
		
	
	
	
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
	
		if($mail->send()==true){
		echo 'Message has been sent';
		}
		else { echo "Something went wrong. Please try again."; }
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

	// 	if ($mail) { echo "OK"; }
    //   else { echo "Something went wrong. Please try again."; }

		

	} # end if - there was a validation error

}

?>