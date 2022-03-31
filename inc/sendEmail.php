<?php
include ('./thanksmail.php'); // include the email sending script


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
$errorMessage =false;





if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
		$errorMessage =true;
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
		$errorMessage =true;
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
		$errorMessage =true;
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


   if ($errorMessage ==false) {

      // 	ini_set("sendmail_from", $siteOwnersEmail); // for windows server
    //   $mail = mail($siteOwnersEmail, $subject, $message, $headers);

	try {
		
	$mail = new PHPMailer(true);
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
	$mail->SMTPDebug  = false;
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->CharSet = 'UTF-8';
	$mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for Gmail
	//ses-smtp-user.20211121-125403
	// $mail->Host = 'email-smtp.us-east-2.amazonaws.com'; //Sets the SMTP hosts of your Email hosting, this for AWS
	// $mail->Username = 'AKIA3TDVL4JDWHU3CG7S'; //Sets SMTP username
    // $mail->Password = 'BI+Z5o3Onr80nUxB+GeW7bmbApqfEVJc09gjYLz4JLqk'; //Sets SMTP password
	$mail->Host = 'ssl://smtp.gmail.com'; //Sets the SMTP hosts of your Email hosting, this for AWS
	$mail->Username = 'jahidul0hasan@gmail.com'; //Sets SMTP username
    $mail->Password = 'OfficialPassword'; //Sets SMTP password
	$mail->Port       = '465';
	$mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
   
	
	
		//Recipients
		$mail->From = $email;
		$mail->FromName = 'HasanJahidul.com'; //Sets the From name of the message
		$mail->addAddress($siteOwnersEmail);     //Add a recipient
		$mail->addReplyTo($email,$name);
		
		
	
	
	
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject  = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		
	
		
		if($mail->send()==true){
           // echo 'Message has been sent';

			$ThanksMail = new PHPMailer;
			$ThanksMail->IsSMTP(); //Sets Mailer to send message using SMTP
			$ThanksMail->SMTPDebug = false; //Debug mode. Comment this for production mode 
			$ThanksMail->CharSet = 'UTF-8';
			$ThanksMail->Host = 'ssl://smtp.gmail.com'; //Sets the SMTP hosts of your EThanksMail hosting, this for AWS
			$ThanksMail->Username = 'jahidul0hasan@gmail.com'; //Sets SMTP username
			$ThanksMail->Password = 'OfficialPassword'; //Sets SMTP password
			$ThanksMail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			
			$ThanksMail->Port = '465'; //Sets the default SMTP server port
			$ThanksMail->SMTPAuth = true;	//Sets SMTP authentication. Utilizes the Username and Password variables
			$ThanksMail->SMTPSecure = ''; //Sets connection prefix. Options are "", "ssl" or "tls"
			$ThanksMail->From = 'jahidul0hasan@gmail.com'; //Sets the From eThanksMail address for the message
			$ThanksMail->FromName = 'Hasanjahidul.com'; //Sets the From name of the message
			$ThanksMail->AddAddress($email, 'Jahidul Hasan'); //Adds a "To" address
			$ThanksMail->IsHTML(true);//Sets message type to HTML
			$ThanksMail->Subject = 'Thanks for contacting to Hasanjahidul.com'; //Sets the Subject of the message
			//An HTML or plain text message body
			$ThanksMail->Body =$body ;
			// $ThanksMail->AltBody = '<b>Please wait for reply. Jahidul Hasan is going to reach you as soon as possible </b>';
		
		// 	$result = $ThanksMail->Send();

		// //Content
		// $ThanksMail->isHTML(true);                                  //Set email format to HTML
		// $ThanksMail->Subject  = 'Thanks for contacting to Hasanjahidul.com';
		// $ThanksMail->Body    =  "<b>Please wait for reply. Jahidul Hasan is going to reach you as soon as possible </b>";
		// $ThanksMail->AltBody = "</b>Please wait for reply. Jahidul Hasan is going to reach you as soon as possible</b>";
		//$ThanksMail->Send();
			$ThanksMail->Send();
			echo 'Message has been sent';
			
            }
            else { echo "Something went wrong. Please try again."; }
			
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
	}

	// 	if ($mail) { echo "OK"; }
    //   else { echo "Something went wrong. Please try again."; }
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>